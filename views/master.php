<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<?php echo ($this->renderLayout('head')); ?>

<body>

    {{header}}

    <section class="container">
        {{contents}}
    </section>

    {{footer}}

    <?php echo ($this->renderComponent('scroll')); ?>
    <?php echo ($this->renderComponent('modal')); ?>
    <?php echo ($this->renderLayout('script')); ?>
</body>

</html>