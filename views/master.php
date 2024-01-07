<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<?php echo ($this->renderLayout('head')); ?>

<body class="d-flex flex-column min-vh-100">
    
    {{header}}
    
    <main class="container">
        {{contents}}
    </main>

    {{footer}}

    <?php echo ($this->renderLayout('elements')); ?>
    <?php echo ($this->renderLayout('script')); ?>
</body>

</html>