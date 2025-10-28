<?php

declare(strict_types=1);

/**
 * Primary template file.
 */

get_header();
?>
<main id="site-content">
    <section class="container">
        <h1><?php esc_html_e('Hello from your theme base!', 'theme-base'); ?></h1>
        <p><?php esc_html_e('Edit theme templates in the theme directory to start building.', 'theme-base'); ?></p>
    </section>
</main>
<?php
get_footer();
