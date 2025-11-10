<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Asset Manager') ?></title>

    <!-- GLOBAL MAINLY STYLES -->
    <link href="<?= base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/themify-icons/css/themify-icons.css') ?>" rel="stylesheet">

    <!-- PLUGINS STYLES -->
    <link href="<?= base_url('assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/DataTables/datatables.min.css') ?>" rel="stylesheet" />
    <!-- THEME STYLES -->
   <!-- THEME STYLES -->
<link href="<?= base_url('assets/css/main.min.css') ?>" rel="stylesheet">
<style>
    .fa-angle-down:before {
    content:none;
}
.page-sidebar ul li a {
    text-decoration: none;
}
</style>
</head>

<body class="fixed-navbar">
<div class="page-wrapper">

    <!-- HEADER -->
    <?= $this->include('partials/header') ?>

    <!-- SIDEBAR -->
    <?= $this->include('partials/sidebar') ?>

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
        $(function() {
            $('#example-table').DataTable({
                pageLength: 10,
                //"ajax": './assets/demo/data/table_data.json',
                /*"columns": [
                    { "data": "name" },
                    { "data": "office" },
                    { "data": "extn" },
                    { "data": "start_date" },
                    { "data": "salary" }
                ]*/
            });
        })
    </script>
</body>
</html>
