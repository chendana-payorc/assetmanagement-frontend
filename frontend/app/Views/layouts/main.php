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
        });


    // Global variable to store edit form's iti instance
    window.editFormItiInstance = null;
    
    function editRecord(url, id) {
    $.ajax({
        url: url,
        method: 'POST',
        data: { id: id },
        success: function(response, textStatus, xhr) {
            // Check if response is JSON (error) - jQuery may have parsed it
            if (typeof response === 'object' && response !== null && !(response instanceof jQuery)) {
                // Check if it looks like an error response
                if (response.error || response.message || response.success === false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.error || response.message || 'Something went wrong!'
                    });
                    return;
                }
            }
           
            if (typeof response === 'string') {
                const trimmed = response.trim();
                if (trimmed.startsWith('{') && trimmed.endsWith('}')) {
                    try {
                        const errorData = JSON.parse(response);
                        if (errorData.error || errorData.message || errorData.success === false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorData.error || errorData.message || 'Something went wrong!'
                            });
                            return;
                        }
                    } catch (e) {
                        
                    }
                }
            }
          
            $('#editFormData').html(response);
            
            window.editFormItiInstance = null;
            
            // Function to initialize phone input
            function initPhoneInput() {
                const phoneInput = document.querySelector('#editFormData #phone');
                if (!phoneInput) return;
                
                if (typeof window.intlTelInput === 'undefined') {
                   
                    setTimeout(initPhoneInput, 100);
                    return;
                }
                
                const countryCode = $('#editFormData #country_code').val() || '';
                const phoneNumber = $('#editFormData #phone').val() || '';
              
                try {
                    const iti = window.intlTelInput(phoneInput, {
                        separateDialCode: true,
                        initialCountry: 'in',
                        preferredCountries: ['in', 'us', 'gb', 'ae'],
                        autoPlaceholder: 'polite',
                        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js'
                    });
                    
                    window.editFormItiInstance = iti;
                    
                    if (countryCode && phoneNumber) {
                        try {
                           
                            if (window.intlTelInputGlobals && window.intlTelInputGlobals.getCountryData) {
                                const countryData = window.intlTelInputGlobals.getCountryData().find(function(item) {
                                    return item.dialCode === countryCode;
                                });
                                if (countryData) {
                                    iti.setCountry(countryData.iso2);
                                }
                            }
                            // Then set the number
                            const dialPrefixedNumber = `+${countryCode}${phoneNumber}`;
                            iti.setNumber(dialPrefixedNumber);
                        } catch (err) {
                            console.warn('Failed to set phone number', err);
                            // Fallback: just set the value
                            phoneInput.value = phoneNumber;
                        }
                    } else if (phoneNumber) {
                        phoneInput.value = phoneNumber;
                    }
                } catch (err) {
                    console.warn('Failed to initialize phone input', err);
                }
            }
            
            setTimeout(initPhoneInput, 100);
           
            $('#editUserCanvas #submitBtn').text('Update');
            $('#editUserCanvasLabel').text('Update User');
           
            const canvasEl = document.getElementById('editUserCanvas');
            const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(canvasEl);
            offcanvasInstance.show();
            
            canvasEl.addEventListener('hidden.bs.offcanvas', function() {
                window.editFormItiInstance = null;
            }, { once: true });
        },
        error: function(xhr) {
            console.log(xhr.responseJSON);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong!'
            });
        }
    });
}
    </script>
</body>
</html>
