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
    .tooltip-btn:hover {
    font-weight: bold;
}
.tooltip-btn:hover i {
    font-weight: bold;
}
    .fa-angle-down:before {
    content:none;
}
.page-sidebar ul li a {
    text-decoration: none;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
}
#phone + label,
.form-group label[for="phone"] {
    display: block;
    margin-bottom: 5px;
}
.iti {
    width: 100% !important;
    display: block !important;
}
.iti input {
    width: 100% !important;
}
.valid-rule {
    color: green;
    font-weight: bold;
}

.invalid-rule {
    color: red;
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script type="text/javascript">
     
    //  $(function() {
    //         $('#example-table').DataTable({
    //             pageLength: 10,
    //             ordering: false,
    //             //"ajax": './assets/demo/data/table_data.json',
    //             /*"columns": [
    //                 { "data": "name" },
    //                 { "data": "office" },
    //                 { "data": "extn" },
    //                 { "data": "start_date" },
    //                 { "data": "salary" }
    //             ]*/
    //         });
    //     });

    document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
})


function editRecord(requestUrl, id) {
   
    $.ajax({
        url: requestUrl,
        method: 'POST',
        data: { id: id },
        beforeSend: function () {
            $('#editFormData').html('<div class="text-center p-3">Loading...</div>');
        },
        success: function (response) {
            
            $('#editFormData').html(response);

            var editCanvas = new bootstrap.Offcanvas(document.getElementById('editDepartmentCanvas'));
            console.log(editCanvas);
            editCanvas.show();
        },
        error: function (xhr) {
            let message = 'Something went wrong!';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
            });
        }
    });
}

const addCanvas = document.getElementById('addUserCanvas');
if (addCanvas !== null) {
    addCanvas.addEventListener('shown.bs.offcanvas', initPasswordValidation);
}

const editCanvas = document.getElementById('editUserCanvas');
if (editCanvas !== null) {
    editCanvas.addEventListener('shown.bs.offcanvas', initPasswordValidation);
}


    
function initPasswordValidation() {

// Find password fields inside the visible offcanvas
let container =
    document.querySelector("#editUserCanvas.show #editFormData") ||
    document.querySelector("#addUserCanvas.show");

if (!container) {
    console.warn("No visible form found.");
    return;
}

const passwordInput = container.querySelector("#passwordInput");
const confirmPasswordInput = container.querySelector("#confirmPasswordInput");
const confirmError = container.querySelector("#confirmError");

const ruleUpper = container.querySelector("#ruleUpper");
const ruleLower = container.querySelector("#ruleLower");
const ruleNumber = container.querySelector("#ruleNumber");
const ruleSpecial = container.querySelector("#ruleSpecial");
const ruleLength = container.querySelector("#ruleLength");

if (!passwordInput || !confirmPasswordInput) {
    console.warn("Password fields not found in this form.");
    return;
}

passwordInput.addEventListener("input", function () {
    const password = passwordInput.value;

    updateRule(ruleUpper, /[A-Z]/.test(password));
    updateRule(ruleLower, /[a-z]/.test(password));
    updateRule(ruleNumber, /[0-9]/.test(password));
    updateRule(ruleSpecial, /[!@#$%^&*(),.?":{}|<>]/.test(password));
    updateRule(ruleLength, password.length >= 8);

    checkConfirmPassword();
});

confirmPasswordInput.addEventListener("input", checkConfirmPassword);

function updateRule(el, isValid) {
    if (!el) return;
    el.classList.toggle("valid-rule", isValid);
    el.classList.toggle("invalid-rule", !isValid);
}

function checkConfirmPassword() {
    if (!confirmError) return;

    if (confirmPasswordInput.value === "") {
        confirmError.textContent = "";
        return;
    }

    confirmError.textContent =
        passwordInput.value === confirmPasswordInput.value ? "" : "Passwords do not match";
}
}


    </script>
</body>
</html>
