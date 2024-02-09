<div class="container px-md-5">
    <div class="post-feed-section py-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-light mt-3">My Feed</h3>
            <div class="d-none d-md-inline-block">
                <div class="btn-group btn-group-sm" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="view_mode" id="btnRadioGrid" autocomplete="off" checked="">
                    <label class="btn btn-outline-prime rounded-start-4" for="btnRadioGrid"><i class="bi bi-grid-3x3 me-2"></i>Grid</label>
                    <input type="radio" class="btn-check" name="view_mode" id="btnRadioList" autocomplete="off">
                    <label class="btn btn-outline-prime rounded-end-4" for="btnRadioList"><i class="bi bi-grid-1x2 me-2"></i>List</label>
                </div>
            </div>
        </div>
        <hr class="m-0 py-2">
        <?php if ($posts !== false && count($posts) > 0) : ?>
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
        <?php else : ?>
            <div class="text-center py-5">
                <i class="bi bi-plus-circle display-4 mb-4"></i>
                <p class="text-muted text-center align-items-center mb-0 ">Posts not available!</p>
            </div>
        <?php endif; ?>
    </div>
</div>