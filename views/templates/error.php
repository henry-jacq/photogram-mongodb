<div class="d-flex flex-wrap align-items-center justify-content-center min-vh-100">
    <div class="container p-5">
        <div class="row mx-auto text-center py-5 bg-body-tertiary rounded">
            <span class="display-1 d-block"><?= $code?></span>
            <span class="display-4 d-block mb-3">Error</span>
            <!-- <div class="mb-4 lead">The page you are looking for was not found or other error occured.</div> -->
            <div class="mb-4 lead text-truncate"><?= $exception?></div>
            <div class="d-grid col-sm-8 col-md-5 col-lg-3 mx-auto">
                <a href="/" class="btn text-secondary-emphasis border-0 text-decoration-none hvr-icon-back"><i class="fa fa-arrow-left me-2 hvr-icon"></i>Back to Storylog</a>
            </div>
        </div>
    </div>
</div>