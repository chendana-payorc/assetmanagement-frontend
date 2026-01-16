<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <ul class="side-menu metismenu">
            <li>
                <a class="active mt-2" href="<?= base_url('employee-dashboard') ?>">
                    <i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>

            <li class="heading">Asset Management</li>

            <li>
                <a href="<?= base_url('employee-request-asset') ?>">
                    <i class="sidebar-item-icon fa fa-plus-square"></i>
                    <span class="nav-label">Request Asset</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('employee-my-requests') ?>">
                    <i class="sidebar-item-icon fa fa-list-alt"></i>
                    <span class="nav-label">My Requests</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('employee/assigned-assets') ?>">
                    <i class="sidebar-item-icon fa fa-check-square"></i>
                    <span class="nav-label">Assigned Assets</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- END SIDEBAR-->
