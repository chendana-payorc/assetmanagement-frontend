<header class="header">
    <div class="page-brand">
        <a class="link" href="<?= base_url('employee-dashboard') ?>">
            <span class="brand">Employee <span class="brand-tip">Panel</span></span>
        </a>
    </div>

    <div class="flexbox flex-1">
        <ul class="nav navbar-toolbar">
            <li>
                <a class="nav-link sidebar-toggler js-sidebar-toggler">
                    <i class="ti-menu"></i>
                </a>
            </li>
        </ul>

        <ul class="nav navbar-toolbar">
            <li class="dropdown dropdown-user">
                <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                    <img src="<?= base_url('assets/img/admin-avatar.png') ?>" />
                    <span><?= esc(session('employee_name') ?? 'Employee') ?></span>
                    <i class="fa fa-angle-down m-l-5"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?= base_url('employee-logout') ?>">
                        <i class="fa fa-power-off"></i> Logout
                    </a>
                </ul>
            </li>
        </ul>
    </div>
</header>
