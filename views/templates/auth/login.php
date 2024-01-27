<div class="h-100 d-flex align-items-center justify-content-center row user-select-none min-vh-100">
    <div class="py-3 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <form class="user-login-form" method="post" autocomplete="off">
            <div class="form-control p-4 shadow-lg bg-opacity-25">
                <img src="/assets/brand/photogram-logo.png" alt="logo" class="img-fluid mx-auto d-block mb-2" width="63" height="63">
                <h4 class="fw-light text-center mb-4">Photogram</h4>
                <hr class="mb-4">
                <h5 class="fw-semi-bold mb-4">Login</h5>
                <label for="user" class="form-label">Username or email</label>
                <input type="text" id="user" name="user" class="form-control mb-3 bg-transparent" required="">
                <label for="pass" class="form-label">Password</label>
                <div class="input-group mb-3">
                    <input type="password" id="pass" name="password" class="form-control bg-transparent" required="">
                    <span class="input-group-text bg-transparent focus-ring focus-ring-prime" id="icon-click" role="button" tabindex="0">
                        <i class="bi-eye-slash" id="icon"></i>
                    </span>
                </div>
                <div class="row mb-3">
                    <div class="col text-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" role="button">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                    </div>
                    <div class="col text-end">
                        <a href="/forgot-password">Forgot password?</a>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-prime btn-login mb-3">Login now!</button>
                </div>
                <p class="text-center text-muted">Want to join Photogram? <a href="/register">Register now</a>.</p>
            </div>
        </form>
    </div>
</div>