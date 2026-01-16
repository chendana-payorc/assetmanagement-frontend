<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($organizations[0]['name'] ?? 'Asset Manager') ?></title>
    <link rel="icon" type="image/png" 
      href="<?= !empty($organizations[0]['favicon']) 
                ? env('Image_url').'uploads/organizations/'.$organizations[0]['favicon'] 
                : '' ?>">

    <!-- GLOBAL MAINLY STYLES -->
    <link href="<?= base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/themify-icons/css/themify-icons.css') ?>" rel="stylesheet">

    <!-- PLUGINS STYLES -->
    <link href="<?= base_url('assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/DataTables/datatables.min.css') ?>" rel="stylesheet" />

    <!-- THEME STYLES -->
    <link href="<?= base_url('assets/css/main.min.css') ?>" rel="stylesheet">

    <style>
        .tooltip-btn:hover { font-weight: bold; }
        .tooltip-btn:hover i { font-weight: bold; }
        .fa-angle-down:before { content:none; }
        .page-sidebar ul li a { text-decoration: none; }
        .form-group label { display: block; margin-bottom: 5px; }
        #phone + label, .form-group label[for="phone"] { display: block; margin-bottom: 5px; }
        .iti { width: 100% !important; display: block !important; }
        .iti input { width: 100% !important; }
        .valid-rule { color: green; font-weight: bold; }
        .invalid-rule { color: red; }
    </style>
</head>

<body class="fixed-navbar">
<div class="page-wrapper">

    <!-- HEADER -->
    <?= $this->include('partials/employee_header') ?>

    <!-- EMPLOYEE SIDEBAR -->
    <?= $this->include('partials/employee_sidebar') ?>

    <!-- CONTENT WRAPPER -->
    <div class="content-wrapper">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- FOOTER -->
    <?= $this->include('partials/footer') ?>

</div>

<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
    <div class="page-preloader">Loading</div>
</div>

<!-- CORE PLUGINS -->
<script src="<?= base_url('assets/vendors/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/popper.js/dist/umd/popper.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/metisMenu/dist/metisMenu.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>

<!-- PAGE LEVEL PLUGINS -->
<script src="<?= base_url('assets/vendors/chart.js/dist/Chart.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') ?>"></script>

<!-- CORE SCRIPTS -->
<script src="<?= base_url('assets/js/app.min.js') ?>"></script>
<script src="<?= base_url('assets/js/scripts/dashboard_1_demo.js') ?>"></script>
<script src="<?= base_url('assets/vendors/DataTables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script type="text/javascript">
    // Copy your JS from admin main.php (DataTables init, tooltip, offcanvas, password validation)
</script>
</body>
</html>
