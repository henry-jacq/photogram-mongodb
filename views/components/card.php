<div class="card shadow-lg">
    <header class="card-header p-2 user-select-none border-0">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-story me-2">
                    <a href="/profile/<?= $p['userData']['username'] ?>" class="d-block link-dark text-decoration-none" aria-expanded="false">
                        <img class="user-profile-img border rounded-circle" src="<?= $p['avatar'] ?>" width="40" height="40" loading="lazy"></a>
                </div>
                <div class="skeleton-header">
                    <div class="nav nav-divider">
                        <h7 class="nav-item card-title mb-0"> <a href="/profile/<?= $p['userData']['username'] ?>" class="text-decoration-none" style="color: var(--bs-dark-text)"><?= ucfirst($p['userData']['username']) ?></a>
                        </h7>

                        <div class="ms-1 align-items-center justify-content-between">
                            <span class="nav-item small fw-light"> •
                                <?= $p['created_at'] ?></span>
                        </div>
                    </div>
                    <?php
                    if (!empty($p['userData']['job']) && $p['userData']['job'] !== 'None') : ?>
                        <p class="mb-0 small fw-light">App Developer</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="dropdown">
                <a role="button" class="btn py-1 px-2 rounded-circle" id="postCardAction-<?= $p['_id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="postCardAction-<?= $p['_id'] ?>">
                    <li data-id="<?= $p['_id'] ?>">
                        <a class="dropdown-item btn-download" role="button">
                            <i class="fa-solid fa-download" aria-hidden="true"></i>
                            <span class="ms-2">Download</span>
                        </a>
                    </li>
                    <li data-id="<?= $p['_id'] ?>">
                        <a class="dropdown-item btn-copy-link" role="button" <?php if (count($p['images']) == 1) : echo ("value='/files/posts/{$p['images'][0]}'");
                                                                                endif; ?>>
                            <i class="fa-solid fa-paperclip" aria-hidden="true"></i>
                            <span class="ms-2">Copy Link</span>
                        </a>
                    </li>
                    <li data-id="<?= $p['_id'] ?>"><a class="dropdown-item btn-full-preview" role="button" value="/files/posts/<?= $p['images'][0] ?>">
                            <i class=" fa-solid fa-expand" aria-hidden="true"></i>
                            <span class="ms-2">Full Preview</span>
                        </a>
                    </li>
                    <?php if ($user['_id'] == $p['user_id']) : ?>
                        <li data-id="<?= $p['_id'] ?>">
                            <a class="dropdown-item btn-edit-post" role="button">
                                <i class="fa-solid fa-pen-to-square fa-sm" aria-hidden="true"></i>
                                <span class="ms-2">Edit Post</span>
                            </a>
                        </li>
                        <li data-id="<?= $p['_id'] ?>">
                            <a class="dropdown-item btn-toggle-comment" role="button">
                                <i class="fa-solid fa-comment-slash fa-sm" aria-hidden="true"></i>
                                <span class="ms-2">Turn off comment</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li data-id="<?= $p['_id'] ?>">
                            <a class="dropdown-item btn btn-delete text-danger" role="button">
                                <i class="fa-solid fa-trash-can fa-sm" aria-hidden="true"></i>
                                <span class="ms-2">Delete</span>
                            </a>
                        </li>
                    <?php else : ?>
                        <li>
                            <a class="dropdown-item btn btn-report text-danger" role="button">
                                <i class="fa-solid fa-exclamation-circle fa-sm" aria-hidden="true"></i>
                                <span class="ms-2">Report post</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>
    <?php if (count($p['images']) > 1) : ?>
        <div id="post-image-<?= $p['_id'] ?>" class="carousel slide user-select-none" data-id="<?= $p['_id'] ?>">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/files/posts/<?= $p['images'][0] ?>" class="d-block post-img w-100 rounded" loading="lazy">
                </div>
                <?php foreach ($p['images'] as $index => $image_uri) :
                    if ($index !== 0) : ?>
                        <div class="carousel-item">
                            <img src="/files/posts/<?= $image_uri ?>" class="d-block post-img w-100 rounded" loading="lazy">
                        </div>
                <?php endif;
                endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#post-image-<?= $p['_id'] ?>" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#post-image-<?= $p['_id'] ?>" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    <?php else : ?>
        <img class="post-card-image post-img user-select-none rounded" src="/files/posts/<?= $p['images'][0] ?>" loading="lazy" data-id="<?= $p['_id'] ?>">
    <?php endif; ?>
    <div class="card-body px-3 py-2">
        <div class="btn-group fs-5 user-select-none w-100 gap-3 mb-1">
            <div class="btn-like" data-id="<?= $p['_id'] ?>">
                <!-- btn fs-5 mb-1 p-0 border-0 fa-solid fa-heart text-danger -->
                <a id="like-<?= $p['_id'] ?>" role="button">
                    <?php
                    if (in_array($user['_id'], (array)$p['liked_users'])) : ?>
                        <i class="btn fs-5 mb-1 p-0 border-0 fa-solid fa-heart text-danger" aria-hidden="true"></i>
                        <?php else : ?>
                            <i class="btn fs-5 mb-1 p-0 border-0 fa-regular fa-heart" aria-hidden="true"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="btn-comment" data-id="<?= $p['_id'] ?>">
                <a role="button"><i class="fa-regular fa-comment" aria-hidden="true"></i></a>
            </div>
            <div class="btn-share">
                <a role="button"><i class="fa-regular fa-paper-plane mt-1" aria-hidden="true"></i></a>
            </div>
        </div>
        <p class="card-text user-select-none fw-semibold mb-2">
            <span class="likedby-users" role="button" data-id="<?= $p['_id'] ?>">
                <span class="like-count me-1"><?= $p['likes'] ?></span>Likes
            </span>
        </p>
        <p class="card-text post-text mb-2"><?= nl2br(hashtag($p['caption'])); ?></p>
    </div>
</div>