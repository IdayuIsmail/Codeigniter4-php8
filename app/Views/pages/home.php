<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card rounded-0 shadow">
    <div class="card-body text-center">
        <h1 class="fw-bold">Welcome to Payroll Management System</h1>
    </div>
</div>
<hr>
<div class="row">
    <!-- Departments Card -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="<?= base_url('Main/departments') ?>" class="text-decoration-none">
            <div class="card rounded-0 shadow border-start-3 border-success card-clickable">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-3x mb-2 text-success"></i>
                    <h5 class="fw-bolder text-success">Departments</h5>
                    <h6 class="fw-bolder text-end text-success"><?= number_format($departments) ?></h6>
                </div>
            </div>
        </a>
    </div>
    <!-- Designations Card -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="<?= base_url('Main/designations') ?>" class="text-decoration-none">
            <div class="card rounded-0 shadow border-start-3 border-danger card-clickable">
                <div class="card-body text-center">
                    <i class="fas fa-user-tie fa-3x mb-2 text-danger"></i>
                    <h5 class="fw-bolder  text-danger">Designations</h5>
                    <h6 class="fw-bolder text-end text-danger"><?= number_format($designations) ?></h6>
                </div>
            </div>
        </a>
    </div>
    <!-- Employees Card -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="<?= base_url('Main/employees') ?>" class="text-decoration-none">
            <div class="card rounded-0 shadow border-start-3 border-primary card-clickable">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-2 text-primary"></i>
                    <h5 class="fw-bolder">Employees</h5>
                    <h6 class="fw-bolder text-end"><?= number_format($employees) ?></h6>
                </div>
            </div>
        </a>
    </div>
    <!-- Payrolls Card -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="<?= base_url('Main/payrolls') ?>" class="text-decoration-none">
            <div class="card rounded-0 shadow border-start-3 border-dark card-clickable">
                <div class="card-body text-center">
                    <i class="fas fa-file-invoice-dollar fa-3x mb-2 text-dark"></i>
                    <h5 class="fw-bolder text-dark">Payrolls</h5>
                    <h6 class="fw-bolder text-end text-dark"><?= number_format($payrolls) ?></h6>
                </div>
            </div>
        </a>
    </div>
</div>
<?= $this->endSection() ?>
