<div class="px-md-5 mt-3">
    <div class="profile-page-cover bg-body-secondary position-relative rounded">
        <div class="position-absolute top-0 end-0 p-3">
            <?php if (!empty($profileUser['website'])) : ?>
                <a class="small text-secondary me-2" href="<?= $profileUser['website'] ?>" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Website"><i class="bi bi-globe fs-5"></i></a>
            <?php endif;
            if (!empty($profileUser['instagram'])) : ?>
                <a class="small me-2 text-danger" href="https://instagram.com/<?= $profileUser['instagram'] ?>" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Instagram"><i class="bi bi-instagram text-danger fs-5"></i></a>
            <?php endif;
            if (!empty($profileUser['twitter'])) : ?>
                <a class="small text-primary" href="https://twitter.com/<?= $profileUser['twitter'] ?>" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Twitter"><i class="bi bi-twitter text-primary fs-5"></i></a>
            <?php endif; ?>
        </div>
        <div class="profile-page-avatar bg-body-secondary">
            <img class="img-fluid rounded-circle" src="<?= $profileAvatar ?>" alt="">
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
        <div class="row ms-2 me-1 mb-2">
            <div class="col-md-7">
                <h5 class="m-0"><?= ucfirst($profileUser['fullname']) ?>
                </h5>
                <p class="mb-2">@<?= $profileUser['username'] ?>
                    <?php if (!empty($profileUser['job']) && $profileUser['job'] != 'None') :
                        echo ('<span class="small mb-2"> • ' . $profileUser['job'] . '</span>');
                    endif; ?></p>
                <?php if (!empty($profileUser['location'])) : ?>
                    <p class="text-secondary small"><i class="bi bi-geo-alt me-1"></i><?= $profileUser['location'] ?></p>
                <?php endif;
                if (!empty($profileUser['bio'])) : ?>
                    <p><?= nl2br($profileUser['bio']) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-5 mb-2 px-1">
                <div class="hstack gap-3 gap-xl-3 float-md-end">
                    <div class="text-center text-body-emphasis px-2">
                        <h6 class="mb-0"><?= count($posts); ?></h6>
                        <small>Posts</small>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center text-body-emphasis px-2">
                        <h6 class="mb-0"><?= $profileLikes ?></h6>
                        <small>Likes</small>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center btn-get-followers link-body-emphasis" role="button">
                        <h6 class="mb-0">0</h6>
                        <small>Followers</small>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center btn-get-followings link-body-emphasis" role="button">
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
            if ($posts !== false && count($posts) > 0) { ?>
                <div class="row g-3" id="masonry-area">
                    <?php
                    foreach ($posts as $post) : ?>
                        <div class="col-xxl-3 col-lg-4 col-md-6" id="post-<?= $post['_id'] ?>">
                            <?php
                            $this->renderComponent('card', [
                                'p' => $post,
                                'user' => $user,
                                'avatar' => $profileAvatar
                            ]); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php } else if ($title !== 'Home') { ?>
                <div class="text-center py-5">
                    <i class="bi bi-plus-circle display-4 mb-4"></i>
                    <p class="text-muted text-center align-items-center mb-0 ">No posts yet!</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>