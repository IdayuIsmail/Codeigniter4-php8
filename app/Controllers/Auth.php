<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Auth as Auth_Model;
use Jumbojett\OpenIDConnectClient;

#[\AllowDynamicProperties]
class Auth extends BaseController
{
    protected $request;
    protected $auth_model;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->auth_model = new Auth_Model;
    }

    public function index()
    {
        $data = [];
        $data['page_title'] = "Login";
        $data['data'] = $this->request;
        $session = session();
        if ($this->request->getMethod() == 'post') {
            $user = $this->auth_model->where('email', $this->request->getPost('email'))->first();
            if ($user) {
                $verify_password  = password_verify($this->request->getPost('password'), $user['password']);
                if ($verify_password) {
                    foreach ($user as $k => $v) {
                        $session->set('login_' . $k, $v);
                    }
                    return redirect()->to('/Main');
                } else {
                    $session->setFlashdata('error', 'Incorrect Password');
                }
            } else {
                $session->setFlashdata('error', 'Incorrect Email or Password');
            }
        }
        $data['session'] = $session;
        return view('auth/login', $data);
    }

    public function loginWithKeycloak()
    {
        $oidc = new OpenIDConnectClient(
            getenv('KEYCLOAK_BASE_URL') . '/realms/' . getenv('KEYCLOAK_REALM'),
            getenv('KEYCLOAK_CLIENT_ID'),
            getenv('KEYCLOAK_CLIENT_SECRET')
        );

        $oidc->setRedirectURL(getenv('KEYCLOAK_REDIRECT_URI'));
        $oidc->authenticate();

        $userInfo = $oidc->requestUserInfo();

        $nama = $userInfo->nama ?? null;
        $nric = $userInfo->nric ?? null;

        if (!$nama || !$nric) {
            return redirect()->to('/login')->with('error', 'Authentication failed.');
        }

        // Store Keycloak data in session
        session()->set('keycloak_user', [
            'nama' => $nama,
            'nric' => $nric
        ]);
        $user = $this->auth_model->where('ic_number', $nric)->first();

        if ($user) {
            foreach ($user as $k => $v) {
                session()->set('login_' . $k, $v);
            }
            return redirect()->to('/Main');
        } else {
            return redirect()->to('/auth/registerWithKeycloak');
        }
    }

    private function getAccessToken($code)
    {
        $client = \Config\Services::curlrequest();
        $keycloakBaseUrl = getenv('KEYCLOAK_BASE_URL');
        $realm = getenv('KEYCLOAK_REALM');
        $response = $client->post("$keycloakBaseUrl/realms/$realm/protocol/openid-connect/token", [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => getenv('KEYCLOAK_CLIENT_ID'),
                'client_secret' => getenv('KEYCLOAK_CLIENT_SECRET'),
                'redirect_uri' => getenv('KEYCLOAK_REDIRECT_URI'),
                'code' => $code,
            ],
        ]);
        if ($response instanceof \CodeIgniter\HTTP\Response) {
            if ($response->getStatusCode() !== 200) {
                throw new \Exception("Failed to fetch access token from Keycloak: " . $response->getBody());
            }
            return json_decode($response->getBody(), true);
        }

        throw new \Exception("Invalid response from Keycloak");
    }

    private function getUserInfo($accessToken)
    {
        $client = \Config\Services::curlrequest();
        $keycloakBaseUrl = getenv('KEYCLOAK_BASE_URL');
        $realm = getenv('KEYCLOAK_REALM');

        $response = $client->get("$keycloakBaseUrl/realms/$realm/protocol/openid-connect/userinfo", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        if ($response instanceof \CodeIgniter\HTTP\Response) {
            if ($response->getStatusCode() !== 200) {
                throw new \Exception("Failed to fetch user info from Keycloak: " . $response->getBody());
            }
            return json_decode($response->getBody(), true);
        }
        throw new \Exception("Invalid response from Keycloak");
    }

    public function keycloakCallback()
    {
        try {
            // Step 1: Fetch the Authorization Code
            $code = $this->request->getGet('code');
            if (!$code) {
                throw new \Exception("Authorization code missing.");
            }
            // Step 2: Fetch the Access Token
            $tokenData = $this->getAccessToken($code);
            $accessToken = $tokenData['access_token'];
            $idToken = $tokenData['id_token'] ?? null;
            if ($idToken) {
                session()->set('keycloak_id_token', $idToken);
            }
            // Step 3: Fetch User Info
            $userInfo = $this->getUserInfo($accessToken);
            // Step 4: Validate User Info
            $nric = $userInfo['nric'] ?? null;
            if (!$nric) {
                throw new \Exception("NRIC is missing.");
            }
            // Step 5: Store in Session
            session()->set('keycloak_user', [
                'nama' => $userInfo['nama'] ?? 'Unknown',
                'nric' => $nric,
            ]);
            // Step 6: Check User in Database - agency business logic
            $user = $this->auth_model->where('ic_number', $nric)->first();
            if ($user) {
                foreach ($user as $k => $v) {
                    session()->set('login_' . $k, $v);
                }
                return redirect()->to('/Main');
            } else {
                return view('auth/ic_number_not_found');
            }
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Authentication failed.');
        }
    }

    public function logout()
    {
        $idToken = session()->get('keycloak_id_token');
        session()->destroy();

        if ($idToken) {
            $logoutUrl = getenv('KEYCLOAK_BASE_URL') . "/realms/" . getenv('KEYCLOAK_REALM') . "/protocol/openid-connect/logout?" . http_build_query([
                'id_token_hint' => $idToken,
                'post_logout_redirect_uri' => base_url('/'),
                'client_id' => getenv('KEYCLOAK_CLIENT_ID'),
            ]);
            return redirect()->to($logoutUrl);
        }
        return redirect()->to('/Auth/index');
    }
}
