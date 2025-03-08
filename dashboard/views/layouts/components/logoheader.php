<div class="sidebar-logo">
    <div class="logo-header" data-background-color="dark">
        <a href="index.php?controller=dashboard&action=index" class="logo">
            <img
                src="<?php echo ($_SESSION['role'] == 'superadmin') ? 'assets/img/superadmin.png' : 'assets/img/dash-logo.png'; ?>"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
            />
        </a>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
            <i class="gg-menu-left"></i>
            </button>
        </div>
        <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
        </button>
    </div>
</div>