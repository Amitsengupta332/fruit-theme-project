<?php
/** Load the styles and scripts used by the original layout. */

function fruitables_enqueue_assets() {
    wp_enqueue_style( 'fruitables-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap', array(), null );
    wp_enqueue_style( 'fruitables-font-awesome', 'https://use.fontawesome.com/releases/v5.15.4/css/all.css', array(), '5.15.4' );
    wp_enqueue_style( 'fruitables-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css', array(), '1.4.1' );
    wp_enqueue_style( 'fruitables-lightbox', get_theme_file_uri( 'lib/lightbox/css/lightbox.min.css' ) );
    wp_enqueue_style( 'fruitables-owl', get_theme_file_uri( 'lib/owlcarousel/assets/owl.carousel.min.css' ) );
    wp_enqueue_style( 'fruitables-bootstrap', get_theme_file_uri( 'css/bootstrap.min.css' ) );
    $dependencies = array( 'fruitables-fonts', 'fruitables-font-awesome', 'fruitables-bootstrap-icons', 'fruitables-lightbox', 'fruitables-owl', 'fruitables-bootstrap' );
    wp_enqueue_style( 'fruitables-layout', get_theme_file_uri( 'css/style.css' ), $dependencies, (string) filemtime( get_theme_file_path( 'css/style.css' ) ) );
    wp_enqueue_style( 'fruitables-style', get_stylesheet_uri(), array( 'fruitables-layout' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'fruitables-header', get_theme_file_uri( 'css/header.css' ), array( 'fruitables-style' ), (string) filemtime( get_theme_file_path( 'css/header.css' ) ) );
    wp_enqueue_style( 'fruitables-footer', get_theme_file_uri( 'css/footer-custom.css' ), array( 'fruitables-style' ), (string) filemtime( get_theme_file_path( 'css/footer-custom.css' ) ) );

    wp_enqueue_script( 'fruitables-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js', array(), '5.0.0', true );
    wp_enqueue_script( 'fruitables-easing', get_theme_file_uri( 'lib/easing/easing.min.js' ), array( 'jquery' ), false, true );
    wp_enqueue_script( 'fruitables-waypoints', get_theme_file_uri( 'lib/waypoints/waypoints.min.js' ), array( 'jquery' ), false, true );
    wp_enqueue_script( 'fruitables-lightbox', get_theme_file_uri( 'lib/lightbox/js/lightbox.min.js' ), array( 'jquery' ), false, true );
    wp_enqueue_script( 'fruitables-owl', get_theme_file_uri( 'lib/owlcarousel/owl.carousel.min.js' ), array( 'jquery' ), false, true );
    $dependencies = array( 'jquery', 'fruitables-bootstrap', 'fruitables-easing', 'fruitables-waypoints', 'fruitables-lightbox', 'fruitables-owl' );
    wp_enqueue_script( 'fruitables-main', get_theme_file_uri( 'js/main.js' ), $dependencies, (string) filemtime( get_theme_file_path( 'js/main.js' ) ), true );

    if ( is_front_page() || fruitables_is_contact_page() ) {
        wp_enqueue_style( 'fruitables-home-font', 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap', array(), null );
        if ( is_front_page() && class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script( 'wc-add-to-cart' );
        }
    }

    // WooCommerce 7.8+ does not always enqueue fragments by default.
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script( 'wc-cart-fragments' );
    }
}
add_action( 'wp_enqueue_scripts', 'fruitables_enqueue_assets' );

/** Keep catalog card styles off the homepage and individual product pages. */
function fruitables_enqueue_shop_cards() {
    if ( ! fruitables_is_catalog() ) {
        return;
    }

    $file = 'css/shop-cards.css';
    wp_enqueue_style(
        'fruitables-shop-cards',
        get_theme_file_uri( $file ),
        array( 'fruitables-style' ),
        (string) filemtime( get_theme_file_path( $file ) )
    );
}
add_action( 'wp_enqueue_scripts', 'fruitables_enqueue_shop_cards', 30 );

require_once get_theme_file_path( 'inc/shop-catalog.php' );
