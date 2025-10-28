<?php

declare(strict_types=1);

/**
 * Theme bootstrap file.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Enqueue scripts and styles that the theme needs.
add_action('wp_enqueue_scripts', static function (): void {
    $themeVersion = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'theme-base-style',
        get_stylesheet_uri(),
        [],
        $themeVersion
    );
});
