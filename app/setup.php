<?php

/**
 * Theme setup.
 */

namespace App;

use function Roots\bundle;

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    // initial (as tailwind/normalize/reset) styles enqueued separately from theme main styles,
    // as it must be enqueued before Gutenberg Global styles
    bundle('initial')->enqueue();

    // main theme styles, depend on initial (tailwind/normalize/reset) styles (@see `bud` config)
    bundle('app')->enqueue(); // depends on initial
}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

/*
 * Add frontend styles as editor styles.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    // add app frontend styles as editor styles
    bundle('app')->editorStyles();

    bundle('initial')->editorStyles();


    // enqueue app editor-only styles, extracted from app frontend styles
    $relEditorAppOnlyCssPath = asset('editor/app.css')->relativePath(get_theme_file_path());
    add_editor_style($relEditorAppOnlyCssPath);
});


// Move the initial (as tailwind/normalize/reset) styles of theme before the Gutenberg Global styles (`global-styles(-inline-css)`),
// so they can be overriden by Gutenberg theme styles
function move_initial_styles_before_gutenberg_global_styles($styles)
{
    $globalStylesHook  = 'global-styles';
    $initialStylesHook = 'initial/0';

    $initialStylesIndex = array_search($initialStylesHook, $styles, true); // index before the splicing!
    if ($initialStylesIndex === false) { // strict `false` check
        return $styles; // no initial styles enqueued, skip
    }
    unset($styles[$initialStylesIndex]);

    $globalStylesIndex = array_search($globalStylesHook, $styles, true);
    if ($globalStylesIndex === false) { // strict `false` check
        return $styles; // skip, otherwise `array_splice` can remove the wrong styles
    }
    array_splice($styles, $globalStylesIndex, 0, $initialStylesHook);

    return $styles;
}
add_action('print_styles_array', __NAMESPACE__ . '\\move_initial_styles_before_gutenberg_global_styles', 9999);



/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    //remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});
