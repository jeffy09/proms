<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="home" class="logo logo-light mt-4">
        <span class="logo-lg">
            <img src="assets/images/logo.png" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="assets/images/logo-sm.png" alt="small logo">
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="home" class="logo logo-dark mt-4">
        <span class="logo-lg">
            <img src="assets/images/logo-dark.png" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="assets/images/logo-sm.png" alt="small logo">
        </span>
    </a>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title">Main</li>

            <li class="side-nav-item">
                <a href="home" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <!-- <span class="badge bg-success float-end">9+</span> -->
                    <span> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-title">Projects</li>

            <li class="side-nav-item">
                <a href="manage_projects" class="side-nav-link">
                    <i class="ri-slideshow-line"></i>
                    <span> โครงการทั้งหมด </span>
                </a>
            </li>
            <?php if ($_SESSION['user_role'] == 'Admin') { ?>
                <li class="side-nav-item">
                    <a href="select_add_project" class="side-nav-link">
                        <i class="ri-file-add-line"></i>
                        <span> เพิ่มโครงการ </span>
                    </a>
                </li>
            <?php } ?>
            <?php if ($_SESSION['user_role'] == 'Superadmin') { ?>
                <li class="side-nav-title">Setting</li>

                <li class="side-nav-item">
                    <a href="manage_budget" class="side-nav-link">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span> จัดการข้อมูลงบประมาณ </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="add_budget" class="side-nav-link">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span> เพิ่มข้อมูลงบประมาณ </span>
                    </a>
                </li>

                <li class="side-nav-title">Users</li>

                <li class="side-nav-item">
                    <a href="manage_user" class="side-nav-link">
                        <i class="ri-shield-user-line"></i>
                        <span> ผู้ใช้งานทั้งหมด </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="insert_user" class="side-nav-link">
                        <i class="ri-shield-user-line"></i>
                        <span> เพิ่มผู้ใช้งาน </span>
                    </a>
                </li>
            <?php } ?>

        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>