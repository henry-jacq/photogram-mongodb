<nav class="navbar navbar-expand-md border-bottom py-1 fixed-top bg-body-tertiary mb-3 shadow" aria-label="header">
    <div class="container py-1">
        <a class="navbar-brand fs-4 fw-light link-body-emphasis me-auto" href="/">
            <img src="/assets/brand/photogram-logo.png" alt="logo" width="30" class="d-inline-block align-text-top">
            <div class="d-none d-sm-inline-block">
                Photogram
            </div>
        </a>
        <button class="navbar-toggler collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#slHeader" aria-controls="slHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon fs-6"></span>
        </button>
        <div class="navbar-collapse collapse" id="slHeader">
            <div class="d-flex ms-auto">
                <ul class="nav flex-nowrap align-items-center list-unstyled">
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Create post">
                        <a href="/" class="btn border border-secondary rounded-pill" type="button"><i class="bi bi-plus-square-dotted"></i></a>
                    </li>
                    <li class="nav-item ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Switch theme">
                        <a id="themeSwitcher" class="btn border border-secondary rounded-pill" role="button"><i class="bi bi-moon-stars"></i></a>
                    </li>
                    <li class="nav-item ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Notifications">
                        <a class="btn border border-secondary rounded-pill position-relative" role="button">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger px-1">
                                9+
                                <span class="visually-hidden">unread messages</span>
                            </span>
                            <i class="bi bi-bell"></i>
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <div class="dropdown text-end">
                            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://github.com/mdo.png" alt="mdo" width="38" height="38" class="border border-secondary rounded-circle">
                            </a>
                            <ul class="dropdown-menu dropdown-animation dropdown-menu-end pt-2 small mt-2" aria-labelledby="profileDropdown" data-bs-popper="static">
                                <li class="px-2">
                                    <div class="d-flex align-items-center position-relative btn bg-body-tertiary">
                                        <div class="avatar avatar-story me-2">
                                            <a href="#" class="d-block link-dark text-decoration-none" aria-expanded="false">
                                                <img class="user-profile-img border rounded-circle" src="https://api.dicebear.com/6.x/shapes/svg?seed=1" width="36" height="36"></a>
                                        </div>
                                        <div>
                                            <a class="h6 stretched-link text-decoration-none" href="/profile/henry">Henry</a>
                                        </div>
                                    </div>
                                    <hr class="mt-2 mb-1">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/edit-profile">
                                        <i class="fa-fw bi bi-pencil me-2"></i>Edit profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/settings"><i class="bi bi-gear fa-fw me-2"></i>Settings</a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item bg-danger-soft-hover" href="/logout"><i class="bi bi-box-arrow-left fa-fw me-2"></i>Sign Out</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>