<div class="h-100 d-flex align-items-center justify-content-center row min-vh-100 user-select-none">
    <div class="py-3 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <form class="user-register-form needs-validation" method="post" autocomplete="off">
            <div class="form-control p-4 bg-body shadow-lg bg-opacity-25">
                <img src="/assets/brand/photogram-logo.png" alt="logo" class="img-fluid mx-auto d-block mb-2" width="63" height="63">
                <h4 class="fw-light text-center mb-4">Photogram</h4>
                <hr class="mb-3">
                <h5 class="fw-semi-bold mb-4">Register</h5>
                <label for="fullname" class="form-label">Full name</label>
                <input type="text" id="fullname" name="fullname" class="form-control bg-transparent" spellcheck="false" required="">
                <div class="invalid-feedback"></div>
                <label for="username" class="form-label mt-3">Username</label>
                <input type="text" id="username" name="username" class="form-control text-lowercase bg-transparent" spellcheck="false" required="">
                <div class="invalid-feedback"></div>
                <label for="email" class="form-label mt-3">Email address</label>
                <input type="email" id="email" name="email" class="form-control bg-transparent" spellcheck="false" required="">
                <div class="invalid-feedback"></div>
                <label for="password" class="form-label mt-3">Password</label>
                <input type="password" id="password" name="password" class="form-control bg-transparent" required="">
                <div class="invalid-feedback"></div>
                <div class="d-grid mt-4 mb-2">
                    <button type="submit" class="btn btn-prime btn-register" disabled>Register now!</button>
                </div>
                <p class="text-center text-muted mb-0">Do you already have an account? <a href="/login">Login now</a>.</p>
            </div>
        </form>
    </div>
</div>