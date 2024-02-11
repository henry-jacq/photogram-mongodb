<div class="container mt-3 px-md-5">
    <nav class="navbar navbar-expand-md bg-body-tertiary border rounded" aria-label="Photogram Navbar">
        <div class="container-fluid gap-2">
            <a class="navbar-brand fw-light fs-3 me-auto link-body-emphasis" href="/home">
                <img src="/assets/brand/photogram-logo.png" width="35" class="d-inline-block align-text-top">
                <div class="d-none d-md-inline-block">
                    <span>Photogram</span>
                </div>
            </a>
            <button class="navbar-toggler border-0 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#photogramNavbar" aria-controls="photogramNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse d-md-flex collapse" id="photogramNavbar">
                <div class="d-md-flex justify-content-md-end ms-auto">
                    <ul class="navbar-nav gap-md-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/home">
                                <i class="bi bi-house-door<?php if ($title == 'Home'): echo('-fill'); endif; ?> fs-5" <?php if ($title != 'Home'): echo('data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Home"'); endif; ?>></i><span class="d-md-none ms-2">Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/discover">
                                <i class="bi bi-compass<?php if ($title == 'Discover'): echo('-fill'); endif; ?> fs-5" <?php if ($title != 'Discover'): echo('data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Discover"'); endif; ?> ></i><span class="d-md-none ms-2">Discover</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="button" id="postUploadButton" class="nav-link active" aria-current="page">
                                <i class="bi bi-plus-square fs-5" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Create Post"></i><span class="d-md-none ms-2">Create Post</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="button" class="nav-link active" aria-current="page" onclick="dialog('Not Implemented!',' This feature is not implemented');"><i class="bi bi-heart fs-5" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="My Activities"></i><span class="d-md-none ms-2">My Activities</span></a>
                        </li>
                        <li class="nav-item">
                            <a role="button" class="nav-link active" aria-current="page" onclick="dialog('Not Implemented!',' This feature is not implemented');">
                                <div class="d-flex">
                                    <div class="position-relative">
                                        <i class="bi bi-bell fs-5"></i>
                                        <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger fw-light fst-normal">3</span>
                                    </div>
                                    <span class="d-md-none ms-2 mt-1">Notifications</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link active" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <img class="img-fluid border border-prime border-2 rounded-circle" src="<?= $avatar ?>" width="28" height="28">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708" />
                                </svg>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-md-end">
                                <li>
                                    <a class="dropdown-item" href="/profile/<?= $user['username']?>"><i class="bi bi-person-circle me-2"></i>My Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/profile/edit"><i class="bi bi-pencil me-2"></i>Edit Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/settings/account"><i class="bi bi-gear me-2"></i>Settings</a>
                                </li>
                                <li class="">
                                    <a href="/subscribe" role="button" class="dropdown-item bg-body-tertiary text-warning"><i class="bi bi-star me-2"></i>Upgrade to Pro!</a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-left me-2"></i>Sign Out</a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <div class="d-flex justify-content-center gap-2 align-items-center px-2 py-0">
                                        <span class="user-select-none">Mode:</span>
                                        <?php 
                                        $theme = $user->preferences[0]->theme ?? 'dark';
                                        ?>
                                        <button type="button" class="btn border <?php if($theme=='light'): echo('btn-prime'); endif;?>" data-bs-theme-value="light">
                                            <i class="bi bi-sun fs-5"></i>
                                        </button>
                                        <button type="button" class="btn border <?php if($theme=='dark'): echo('btn-prime'); endif;?>" data-bs-theme-value="dark">
                                            <i class="bi bi-moon-stars fs-5"></i>
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>