<div class="px-md-5 my-3">
    <div class="container rounded">
        <div class="row border rounded-3">
            <div class="col-lg-3 bg-body-tertiary rounded-3 py-5">
                <div class="d-flex flex-column align-items-center text-center mt-3">
                    <img class="rounded-circle border border-2 border-prime" width="150" src="https://api.dicebear.com/6.x/shapes/svg?seed=1">
                    <span class="fs-5 fw-semibold mt-2"><?= $user['fullname'] ?></span>
                    <span class="small mt-2"><?= $user['email'] ?></span>
                </div>
            </div>
            <div class="col-lg-9 profile-body">
                <div class="px-3 mt-4">
                    <h4 class="fw-normal"><i class="fa-fw bi bi-pencil me-2"></i>Edit Profile</h4>
                    <hr>
                </div>
                <form class="user-form-data p-3" method="POST" autocomplete="off">
                    <div class="form-group mb-3">
                        <label for="user-avatar" class="form-label fw-semibold">Upload new avatar</label>
                        <p class="small mb-2">You can change your avatar here or remove the current avatar to revert to <a href="https://dicebear.com" class="text-decoration-none" target="_blank">dicebear.com</a></p>
                        <input class="form-control" type="file" id="user-avatar" name="user_image">
                        <div class="text-secondary small mb-2">The maximum file size allowed is 800KB.</div>
                        <div class="d-flex justify-content-end mb-3">
                            <button id="btnRemoveAvatar" class="btn btn-sm btn-outline-danger" type="button"><i class="bi bi-trash me-1"></i>Remove avatar</button>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="fname" class="form-label fw-semibold">Full Name</label>
                        <input type="text" id="fname" class="form-control" name="fname" placeholder="First name" aria-label="First name" value="<?= $user['fullname']?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="website" class="form-label fw-semibold">Website</label>
                        <input id="website" class="form-control" type="text" name="website" placeholder="https://mywebsite.com">
                    </div>
                    <div class="form-group mb-3">
                        <label for="job" class="form-label fw-semibold">Job title</label>
                        <select id="job" class="form-select" name="job" aria-label="Default select example">
                            <option selected="">None</option>
                            <option>App Developer</option>
                            <option>Content Creator</option>
                            <option>Photographer</option>
                            <option>Software Engineer</option>
                            <option>Student</option>
                            <option>UI/UX Designer</option>
                        </select>
                        <p class="form-text mb-0">The job you selected will be shown in your posts.</p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="bio" class="form-label fw-semibold">Bio</label>
                        <textarea id="bio" class="form-control" rows="5" name="bio" placeholder="Write about you..." maxlength="100"></textarea>
                        <p class="form-text mb-0">Tell us about yourself in fewer than 100 characters.</p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="location" class="form-label fw-semibold">Location</label>
                        <input id="location" class="form-control" type="text" name="location" spellcheck="false" placeholder="City, Country">
                    </div>
                    <div class="form-group mb-3">
                        <label for="twitter" class="form-label fw-semibold">Twitter</label>
                        <input id="twitter" class="form-control" type="text" name="twitter" spellcheck="false" placeholder="@username">
                    </div>
                    <div class="form-group mb-4">
                        <label for="instagram" class="form-label fw-medium">Instagram</label>
                        <input id="instagram" class="form-control" type="text" name="instagram" spellcheck="false" placeholder="username">
                    </div>
                    <div class="d-flex justify-content-start gap-2">
                        <button class="btn btn-prime btn-save-data" type="submit">Update profile</button>
                        <a href="/" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>