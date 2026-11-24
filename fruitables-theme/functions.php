<?php
/** Fruitables theme setup and assets. */

function fruitables_theme_setup() {
    add_theme_support( 'title-tag' );
    // Site Identity provides the editable navbar logo/title.
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array( 'height' => 72, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
    register_nav_menus( array(
        'primary' => __( 'Primary Navigation', 'fruitables' ),
        'topbar'  => __( 'Top Bar Links', 'fruitables' ),
        'footer_shop' => __( 'Footer – Shop Info', 'fruitables' ),
        'footer_account' => __( 'Footer – Account', 'fruitables' ),
    ) );
}
add_action( 'after_setup_theme', 'fruitables_theme_setup' );

require_once get_theme_file_path( 'inc/enqueue.php' );

require_once get_theme_file_path( 'inc/product-detail.php' );

require_once get_theme_file_path( 'inc/hero-customizer.php' );

require_once get_theme_file_path( 'inc/header-customizer.php' );
require_once get_theme_file_path( 'inc/header-menus.php' );

require_once get_theme_file_path( 'inc/service-benefits-customizer.php' );

require_once get_theme_file_path( 'inc/home-offers-customizer.php' );

require_once get_theme_file_path( 'inc/home-stats-customizer.php' );

require_once get_theme_file_path( 'inc/testimonials-cpt.php' );
require_once get_theme_file_path( 'inc/footer-customizer.php' );

require_once get_theme_file_path( 'inc/home-redesign.php' );
