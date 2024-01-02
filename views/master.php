<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<?php echo ($this->renderLayout('head')); ?>

<body>
    
    {{header}}
    
    <main class="container">
        {{contents}}
    </main>

    {{footer}}

    <?php echo ($this->renderLayout('elements')); ?>
    <?php echo ($this->renderLayout('script')); ?>
</body>

</html>