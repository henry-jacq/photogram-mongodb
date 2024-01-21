<div class="px-md-5 mt-3">
    <div class="profile-page-cover bg-body-secondary position-relative rounded">
        <div class="position-absolute top-0 end-0 p-3">
            <a class="small text-secondary me-2" href="https://example.com" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Website"><i class="bi bi-globe fs-5"></i></a>
            <a class="small me-2 text-danger" href="https://instagram.com/" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Instagram"><i class="bi bi-instagram text-danger fs-5"></i></a>
            <a class="small text-primary" href="https://twitter.com/" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Twitter"><i class="bi bi-twitter text-primary fs-5"></i></a>
        </div>
        <div class="profile-page-avatar bg-body-secondary">
            <img class="img-fluid rounded-circle" src="https://api.dicebear.com/6.x/shapes/svg?seed=1" alt="">
        </div>
        <div class="position-absolute bottom-0 end-0 p-2">
            <?php if ($profileUser['username'] == $user['username']) : ?>
                <a href="/profile/edit" class="btn btn-prime btn-sm"><i class="bi bi-pencil me-1"></i>Edit Profile</a>
            <?php else : ?>
                <button class="btn btn-sm btn-primary btn-follow" data-id="<?= $profileUser['_id'] ?>"><i class="bi-person-add me-1"></i>Follow</button>
                <button class="btn btn-sm btn-secondary" onclick="dialog('Not Implemented!',' This feature is not implemented');"><i class="bi bi-chat-left-text-fill me-1"></i>Message</button>
            <?php endif; ?>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row mx-2 mb-2">
            <div class="col-md-7">
                <h5 class="m-0"><?= ucfirst($profileUser['fullname']) ?> </h5>
                <p class="mb-2">@<?= $profileUser['username'] ?> <span class="small mb-2"> • App Developer</span></p>
                <p class="text-secondary small"><i class="bi bi-geo-alt me-1"></i>Chennai, India</p>
                <p>#!/bin/bash<br>
                    Full stack developer</p>
            </div>
            <div class="col-md-5 mb-2 px-1">
                <div class="hstack gap-3 gap-xl-3 float-md-end">
                    <div class="text-center px-2">
                        <h6 class="mb-0">0</h6>
                        <small>Posts</small>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center px-2">
                        <h6 class="mb-0">0</h6>
                        <small>Likes</small>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center">
                        <h6 class="mb-0">0</h6>
                        <small>Followers</small>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center">
                        <h6 class="mb-0">0</h6>
                        <small>Following</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="ms-2 mt-4">
            <h4 class="fw-normal">Posts</h4>
        </div>
        <hr class="mt-2 mb-3">
        <div class="user-posts-section mb-5">
            <?php
            if ($posts !== false) {
                $this->renderComponent('card', [
                    'posts' => $posts,
                    'user' => $user,
                    'name' => $name,
                    'profileUser' => $profileUser
                ]);
            } else if ($title !== 'Home') {?>
                <div class="text-center py-5">
                    <i class="bi bi-plus-circle display-4 mb-4"></i>
                    <p class="text-muted text-center align-items-center mb-0 ">No posts yet!</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>