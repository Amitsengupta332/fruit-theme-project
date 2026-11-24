<?php
/** Homepage settings and bundled AVIF images. */
defined( 'ABSPATH' ) || exit;
function fruitables_home_shop_url() {
    return function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ? wc_get_page_permalink( 'shop' ) : home_url( '/?post_type=product' );
}
function fruitables_home_categories( $limit = 6 ) {
    if ( ! taxonomy_exists( 'product_cat' ) ) { return array(); }
    $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0, 'exclude' => array( absint( get_option( 'default_product_cat' ) ) ), 'orderby' => 'name', 'pad_counts' => true ) );
    if ( is_wp_error( $terms ) ) { return array(); }
    $preferred = array( 'fruits', 'vegetables', 'leafy-greens', 'roots-tubers', 'dry-fruits', 'juices-more' );
    usort( $terms, static function ( $a, $b ) use ( $preferred ) {
        $x = array_search( $a->slug, $preferred, true ); $y = array_search( $b->slug, $preferred, true );
        return ( false === $x ? 99 : $x ) <=> ( false === $y ? 99 : $y ) ?: strcasecmp( $a->name, $b->name );
    } );
    return array_slice( $terms, 0, $limit );
}
function fruitables_home_asset( $name ) {
    $path = 'img/home/' . $name;
    return is_readable( get_theme_file_path( $path ) ) ? get_theme_file_uri( $path ) : '';
}
function fruitables_home_category_image( $term ) {
    $map = array(
        'fruits' => 'category-fruits.avif', 'fruit' => 'category-fruits.avif',
        'vegetables' => 'category-vegetables.avif', 'vegetable' => 'category-vegetables.avif',
        'leafy-greens' => 'category-leafy-greens.avif', 'leafy-green' => 'category-leafy-greens.avif',
        'roots-tubers' => 'category-roots-tubers.avif', 'roots-and-tubers' => 'category-roots-tubers.avif',
        'dry-fruits' => 'category-dry-fruits.avif', 'dried-fruits' => 'category-dry-fruits.avif',
        'juices-more' => 'category-juices-more.avif', 'juices-and-more' => 'category-juices-more.avif', 'juices' => 'category-juices-more.avif',
    );
    if ( fruitables_home_mod( 'bundled_catalog_images' ) && isset( $map[ $term->slug ] ) ) {
        $url = fruitables_home_asset( $map[ $term->slug ] );
        if ( $url ) { return '<img src="' . esc_url( $url ) . '" alt="" width="400" height="400" loading="lazy" decoding="async">'; }
    }
    $id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
    if ( $id ) { $image = wp_get_attachment_image( $id, 'woocommerce_thumbnail', false, array( 'loading' => 'lazy', 'alt' => '' ) ); if ( $image ) { return $image; } }
    $fallbacks = array( 'fruits' => 'fruite-item-6.jpg', 'vegetables' => 'vegetable-item-2.jpg', 'leafy-greens' => 'vegetable-item-6.jpg', 'roots-tubers' => 'vegetable-item-5.jpg' );
    $src = isset( $fallbacks[ $term->slug ] ) ? get_theme_file_uri( 'img/' . $fallbacks[ $term->slug ] ) : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_thumbnail' ) : get_theme_file_uri( 'img/hero-img-1.png' ) );
    return '<img src="' . esc_url( $src ) . '" alt="" loading="lazy" width="160" height="100">';
}
function fruitables_home_setting_defaults() {
    return array(
        'topbar_message' => __( 'Fresh produce delivered to your door', 'fruitables' ),
        'topbar_secondary' => __( 'Fresh • Organic • Natural', 'fruitables' ),
        'hero_description' => __( 'Handpicked, farm-fresh produce for a healthier, happier lifestyle.', 'fruitables' ),
        'hero_shop_label' => __( 'Shop Now', 'fruitables' ),
        'hero_how_label' => __( 'How It Works', 'fruitables' ),
        'hero_how_url' => '',
        'category_eyebrow' => __( 'Shop by category', 'fruitables' ),
        'category_title' => __( 'Explore Our Fresh Categories', 'fruitables' ),
        'products_eyebrow' => __( 'Our products', 'fruitables' ),
        'products_title' => __( 'Fresh Picks for You', 'fruitables' ),
        'testimonial_eyebrow' => __( 'What our customers say', 'fruitables' ),
        'testimonial_title' => __( 'Trusted by Healthy Families', 'fruitables' ),
        'testimonial_description' => __( 'Real stories from our customers who choose Fruitables for fresh, organic produce.', 'fruitables' ),
        'newsletter_title' => __( 'Stay Fresh with Fruitables', 'fruitables' ),
        'newsletter_description' => __( 'Subscribe to get fresh offers, new arrivals and healthy tips.', 'fruitables' ),
        'legacy_sections' => false,
        'bundled_hero_image' => true,
        'bundled_delivery_image' => true,
        'bundled_catalog_images' => true,
        'newsletter_decoration' => true,
    );
}
function fruitables_home_mod( $key ) {
    $defaults = fruitables_home_setting_defaults();
    return get_theme_mod( 'fruitables_refresh_' . $key, $defaults[ $key ] ?? '' );
}
add_action( 'customize_register', static function ( $c ) {
    $c->add_section( 'fruitables_home_refresh', array( 'title' => __( 'Homepage – New Design', 'fruitables' ), 'priority' => 34 ) );
    $descriptions = array(
        'bundled_hero_image' => __( 'Use the new basket photo. Uncheck to restore your saved Hero images and background.', 'fruitables' ),
        'bundled_delivery_image' => __( 'Use the new delivery photo. Uncheck to restore your saved banner image.', 'fruitables' ),
        'bundled_catalog_images' => __( 'Use bundled photos for matching homepage products and categories only. Uncheck to use WooCommerce media.', 'fruitables' ),
    );
    foreach ( fruitables_home_setting_defaults() as $key => $default ) {
        $id = 'fruitables_refresh_' . $key;
        $type = is_bool( $default ) ? 'checkbox' : ( 'hero_how_url' === $key ? 'url' : 'text' );
        $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'checkbox' === $type ? 'fruitables_header_checkbox_sanitize' : ( 'url' === $type ? 'esc_url_raw' : 'sanitize_text_field' ) ) );
        $c->add_control( $id, array( 'section' => 'fruitables_home_refresh', 'label' => ucwords( str_replace( '_', ' ', $key ) ), 'type' => $type, 'description' => $descriptions[ $key ] ?? '' ) );
    }
} );
/** Apply only on the site's actual front page; no database or media changes. */
add_action( 'wp', static function () {
    if ( ! is_front_page() ) { return; }
    $hero = fruitables_home_mod( 'bundled_hero_image' ) && fruitables_home_asset( 'hero-fresh-basket.avif' );
    $delivery = fruitables_home_mod( 'bundled_delivery_image' ) && fruitables_home_asset( 'organic-delivery-banner.avif' );
    if ( $hero ) {
        // Temporarily prefer bundled artwork without deleting stored theme mods.
        foreach ( array( 'background_image', 'slide_1_image', 'slide_2_image' ) as $field ) {
            add_filter( 'theme_mod_fruitables_hero_' . $field, '__return_zero' );
        }
        add_filter( 'theme_mod_fruitables_hero_slide_2_link', '__return_empty_string' );
    }
    if ( $delivery ) { add_filter( 'theme_mod_fruitables_exotic_banner_image', '__return_zero' ); }
    add_filter( 'theme_file_uri', static function ( $url, $file ) use ( $hero, $delivery ) {
        if ( $hero && 'img/hero-img-1.png' === $file ) { return fruitables_home_asset( 'hero-fresh-basket.avif' ) ?: $url; }
        if ( $delivery && 'img/baner-1.png' === $file ) { return fruitables_home_asset( 'organic-delivery-banner.avif' ) ?: $url; }
        return $url;
    }, 10, 2 );
    if ( fruitables_home_mod( 'bundled_catalog_images' ) ) {
        add_filter( 'woocommerce_product_get_image', 'fruitables_home_product_image', 10, 4 );
    }
} );
function fruitables_home_product_image( $html, $product, $size, $attr ) {
    // Match exact product slugs/names, never create or replace catalog entries.
    $map = array(
        'apple' => 'product-fresh-apple.avif', 'apples' => 'product-fresh-apple.avif', 'fresh-apple' => 'product-fresh-apple.avif', 'fresh-apples' => 'product-fresh-apple.avif', 'red-apple' => 'product-fresh-apple.avif', 'red-apples' => 'product-fresh-apple.avif',
        'banana' => 'product-banana.avif', 'bananas' => 'product-banana.avif', 'fresh-banana' => 'product-banana.avif', 'fresh-bananas' => 'product-banana.avif',
        'broccoli' => 'product-broccoli.avif', 'fresh-broccoli' => 'product-broccoli.avif',
        'strawberry' => 'product-strawberry.avif', 'strawberries' => 'product-strawberry.avif', 'fresh-strawberry' => 'product-strawberry.avif', 'fresh-strawberries' => 'product-strawberry.avif',
    );
    $slug = $product->get_slug();
    $name = sanitize_title( $product->get_name() );
    $file = $map[ $slug ] ?? $map[ $name ] ?? '';
    $url = $file ? fruitables_home_asset( $file ) : '';
    if ( ! $url ) { return $html; }
    $attrs = array( 'src' => $url, 'width' => '600', 'height' => '600', 'alt' => $product->get_name(), 'class' => 'ft-product-image', 'loading' => 'lazy', 'decoding' => 'async' );
    foreach ( array( 'class', 'alt', 'loading', 'decoding', 'style', 'fetchpriority' ) as $key ) {
        if ( is_array( $attr ) && isset( $attr[ $key ] ) && is_scalar( $attr[ $key ] ) ) { $attrs[ $key ] = $attr[ $key ]; }
    }
    $out = '<img';
    foreach ( $attrs as $key => $value ) { $out .= ' ' . $key . '="' . ( 'src' === $key ? esc_url( $value ) : esc_attr( $value ) ) . '"'; }
    return $out . '>';
}
add_action( 'wp_enqueue_scripts', static function () {
    if ( ! is_front_page() || ! fruitables_home_mod( 'newsletter_decoration' ) ) { return; }
    $url = fruitables_home_asset( 'newsletter-leaf-decoration.avif' );
    if ( ! $url ) { return; }
    $url = str_replace( array( '"', "'", '(', ')', '\\' ), array( '%22', '%27', '%28', '%29', '%5C' ), esc_url_raw( $url ) );
    // Extend the existing footer stylesheet rather than creating another CSS file.
    wp_add_inline_style( 'fruitables-footer', 'body.home .ft-newsletter{position:relative;isolation:isolate;overflow:hidden;}body.home .ft-newsletter::after{content:"";position:absolute;right:0;bottom:0;width:clamp(75px,12vw,160px);height:100%;background:url("' . $url . '") right center/contain no-repeat;mix-blend-mode:multiply;opacity:.75;pointer-events:none;z-index:-1;}@media(max-width:650px){body.home .ft-newsletter::after{opacity:.3;}}' );
}, 40 );
