<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
            <div id="sidebar-collapse">
               
                <ul class="side-menu metismenu">
                    <li>
                        <a class="active mt-2" href="<?= base_url('/') ?>"><i class="sidebar-item-icon fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="heading">Asset Management</li>
                   
                    <li>
                        <a href="<?= base_url('asset-list') ?>"><i class="sidebar-item-icon fa fa-calendar"></i>
                            <span class="nav-label">Asset Details</span>
                        </a>
                    </li>
                    <li class="heading">User Management</li>
                    <li>
                        <a href="<?= base_url('users-list') ?>"><i class="sidebar-item-icon fa fa-user"></i>
                            <span class="nav-label">Admin List</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('department-list') ?>"><i class="sidebar-item-icon fa fa-file-text"></i>
                            <span class="nav-label">Department</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('designation-list') ?>"><i class="sidebar-item-icon fa fa-envelope"></i>
                            <span class="nav-label">Designation</span>
                        </a>
                    </li>
                    <li>
    <a href="<?= base_url('organization-list') ?>"><i class="sidebar-item-icon fa fa-building"></i>
        <span class="nav-label">Organization</span>
    </a>
</li>

                   
                </ul>
            </div>
        </nav>
        <!-- END SIDEBAR-->