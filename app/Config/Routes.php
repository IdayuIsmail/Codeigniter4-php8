<?php

namespace Config;

$routes = Services::routes();

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('/', 'Auth::index',['filter' => 'authenticated']);
$routes->get('/Auth', 'Auth::index',['filter' => 'authenticated']);
$routes->get('/Auth/(:segment)', 'Auth::$1',['filter' => 'authenticated']);
$routes->get('/login', 'Auth::index',['filter' => 'authenticated']);
$routes->match(['post'], '/login', 'Auth::index',['filter' => 'authenticated']);
$routes->get('/logout', 'Auth::logout');
$routes->get('/loginWithKeycloak', 'Auth::loginWithKeycloak');
$routes->get('/callback', 'Auth::keycloakCallback');

$routes->group('Main', ['filter'=>'authenticate'], static function($routes){
    $routes->get('', 'Main::index');
});


if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
