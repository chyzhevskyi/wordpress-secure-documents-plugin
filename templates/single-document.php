<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php the_title(); ?></title>
    <?php wp_head(); ?>
    <style>
        body { font-family: sans-serif; background: #f1f1f1; padding: 40px 20px; }
        .doc-container { max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; box-shadow: 0 1px 3px rgba(0,0,0,.1); border-radius: 8px; }
    </style>
</head>
<body>
    <div class="doc-container">
        <h1><?php the_title(); ?></h1>
        <div class="doc-content">
            <?php the_content(); ?>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
