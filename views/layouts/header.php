<nav class="navbar navbar-expand-md border-bottom py-1 fixed-top bg-body-tertiary mb-3 shadow" aria-label="header">
    <div class="container py-1">
        <a class="navbar-brand fs-4 link-body-emphasis display-6 me-auto" href="/">
            <img src="/assets/brand/photogram-logo.png" alt="logo" width="30" class="d-inline-block align-text-top">
            <div class="d-none d-sm-inline-block">
                <label>Photogram</label>
            </div>
        </a>
        <button class="navbar-toggler collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#slHeader" aria-controls="slHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon fs-6"></span>
        </button>
        <div class="navbar-collapse collapse" id="slHeader">
            <div class="d-flex ms-auto">
                <ul class="nav flex-nowrap align-items-center list-unstyled">
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Write a blog">
                        <a href="/" class="btn border border-secondary rounded-pill" type="button"><i class="bi bi-plus-circle me-2"></i>Post</a>
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
                            <ul class="dropdown-menu dropdown-menu-end mt-2 shadow">
                                <li>
                                    <a class="dropdown-item" href="/profile" role="button"><i class="bi bi-person-circle me-2"></i>My Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/profile/edit" role="button"><i class="bi bi-pencil-square me-2"></i>Edit Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" onclick="dialog('Not Implemented!',' This feature is not implemented');" role="button"><i class="bi bi-gear me-2"></i>Settings</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-left me-2"></i>Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>