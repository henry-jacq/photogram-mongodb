<?php
$view = $user['preferences']['view'] ?? 'grid';
?>
<div class="container px-md-5">
    <div class="post-feed-section py-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-light mt-3">My Feed</h3>
            <div class="d-none d-md-inline-block">
                <div class="btn-group btn-group-sm" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="view_mode" value="grid" id="btnRadioGrid" autocomplete="off" <?php if($view == 'grid'): echo('checked'); endif; ?> >
                    <label class="btn btn-outline-prime rounded-start-4" for="btnRadioGrid"><i class="bi bi-grid-3x3 me-2"></i>Grid</label>
                    <input type="radio" class="btn-check" name="view_mode" value="list" id="btnRadioList" autocomplete="off" <?php if($view == 'list'): echo('checked'); endif; ?> >
                    <label class="btn btn-outline-prime rounded-end-4" for="btnRadioList"><i class="bi bi-grid-1x2 me-2"></i>List</label>
                </div>
            </div>
        </div>
        <hr class="m-0 py-2">
        <?php if ($posts !== false && count($posts) > 0) : 
            $view = $user['preferences']['view'] ?? 'grid';
            if (!empty($view) && $view == 'list') : ?>
                <div class="row g-3">
                    <div class="col-md-12 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="h5">@<?= $user['username']?></div>
                                <div class="h7 text-muted">Fullname : <?= $user['fullname'] ?></div>
                                <div class="h7"><?php nl2br($user['bio']); ?>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="h6 text-muted">Followers</div>
                                    <div class="h5">0</div>
                                </li>
                                <li class="list-group-item">
                                    <div class="h6 text-muted">Following</div>
                                    <div class="h5">0</div>
                                </li>
                                <li class="list-group-item">Subscription: <span class="badge rounded-pill text-bg-secondary">Free</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                    <div class="d-flex flex-column gap-3 align-items-center">
                            <?php
                            foreach ($posts as $post) : ?>
                            <div class="col-sm-12 col-lg-8" id="post-<?= $post['_id'] ?>">
                            <?php
                                $this->renderComponent('card', [
                                    'p' => $post,
                                    'user' => $user,
                                    'avatar' => $avatar
                                ]);?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Photogram Pro 🚀</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Unleash creativity!</h6>
                                <p class="card-text">Unlocking pro will allow you to do advanced post manipulation and gives some customization options.</p>
                                <a href="#" class="card-link">Upgrade to Pro</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
               <div class="row g-3" id="masonry-area">
                    <?php
                    foreach ($posts as $post) : ?>
                    <div class="col-xxl-3 col-lg-4 col-md-6" id="post-<?= $post['_id'] ?>">
                    <?php
                        $this->renderComponent('card', [
                            'p' => $post,
                            'user' => $user,
                            'avatar' => $avatar
                        ]);?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="text-center py-5">
                <i class="bi bi-plus-circle display-4 mb-4"></i>
                <p class="text-muted text-center align-items-center mb-0 ">Posts not available!</p>
            </div>
        <?php endif; ?>
    </div>
</div>