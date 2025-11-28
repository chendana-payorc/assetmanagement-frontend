<header class="header">
            <div class="page-brand">
                <a class="link" href="">
                <?php if (!empty($organizations[0]['logo'])): ?>
            <img src="<?= env('Image_url').'uploads/organizations/'.$organizations[0]['logo'] ?>"
                 alt="Organization Logo"
                 class="brand-logo"
                 style="height:40px;">
        <?php else: ?>
            <span class="brand">Asset 
                <span class="brand-tip">Manager</span>
            </span>
        <?php endif; ?>

                </a>
            </div>
            <div class="flexbox flex-1">
                <!-- START TOP-LEFT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li>
                        <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
                    </li>
                   
                </ul>
                <!-- END TOP-LEFT TOOLBAR-->
                <!-- START TOP-RIGHT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li class="dropdown dropdown-user">
                        <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                            <img src="./assets/img/admin-avatar.png" />
                            <span></span><?= esc(session('admin_name') ?? 'Admin') ?><i class="fa fa-angle-down m-l-5"></i></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <!-- <a class="dropdown-item" href="profile.html"><i class="fa fa-user"></i>Profile</a>
                            <a class="dropdown-item" href="profile.html"><i class="fa fa-cog"></i>Settings</a> -->
                            <!-- <li class="dropdown-divider"></li> -->
                            <a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fa fa-power-off"></i>Logout</a>
                        </ul>
                    </li>
                </ul>
                <!-- END TOP-RIGHT TOOLBAR-->
            </div>
        </header>