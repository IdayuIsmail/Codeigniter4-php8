
<h2 class="text-center py-5" style="color: #FFFFFF; font-weight: bold;">
</h2>
<div class="col-lg-4 col-md-6 col-sm-10 col-xs-12 mx-auto">
    <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header bg-primary text-white rounded-top">
            <h4 class="mb-0 text-center">Login</h4>
        </div>
        <div class="card-body p-4">
            <div class="container-fluid">
                <form action="<?= base_url('login') ?>" method="POST">
                <?= csrf_field() ?>
                    <?php if ($session->getFlashdata('error')): ?>
                        <div class="alert alert-danger rounded-0">
                            <?= $session->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($session->getFlashdata('success')): ?>
                        <div class="alert alert-success rounded-0">
                            <?= $session->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="email" class="control-label">Email</label>
                        <div class="input-group rounded-0">
                            <input type="email" class="form-control rounded-0" id="email" name="email" autofocus placeholder="ahmad@gmail.com" value="<?= !empty($data->getPost('email')) ? $data->getPost('email') : '' ?>" required="required">
                            <div class="input-group-text bg-light bg-gradient rounded-0"><i class="fa fa-user"></i></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="control-label">Password</label>
                        <div class="input-group rounded-0">
                            <input type="password" class="form-control rounded-0" id="password" name="password" placeholder="**********" required="required">
                            <div class="input-group-text bg-light bg-gradient rounded-0"><i class="fa fa-key"></i></div>
                        </div>
                    </div>
                    <div class="d-grid gap-1">
                        <button class="btn rounded-0 btn-primary bg-gradient">Login</button>
                    </div>

                    <!-- Keycloak Login Button -->
                    <div class="d-grid gap-2 mt-3">
                        <a href="<?= base_url('loginWithKeycloak') ?>" class="btn rounded-0 btn-primary bg-gradient btn-block">
                            <i class="fa fa-lock"></i> Login with MyDigital ID
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
