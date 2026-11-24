<?php
/** Fruitables layout for single WooCommerce products only. */
defined( 'ABSPATH' ) || exit;

add_action( 'wp', static function () {
    if ( ! function_exists( 'is_product' ) || ! is_product() ) {
        return;
    }

    remove_action( 'template_redirect', array( 'WC_Template_Loader', 'unsupported_theme_init' ), 10 );

    add_filter( 'template_include', static function ( $template ) {
        $detail = get_theme_file_path( 'template-parts/shop/product-detail.php' );
        return is_readable( $detail ) ? $detail : $template;
    }, 100 );

    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    add_action( 'wp_enqueue_scripts', static function () {
        $file = 'css/product-detail.css';
        wp_enqueue_style(
            'fruitables-product-detail',
            get_theme_file_uri( $file ),
            array( 'fruitables-style' ),
            (string) filemtime( get_theme_file_path( $file ) )
        );
    }, 35 );

    add_action( 'woocommerce_before_shop_loop_item_title', static function () {
        $categories = get_the_terms( get_the_ID(), 'product_cat' );
        if ( is_array( $categories ) && $categories ) {
            echo '<span class="fruitables-card-category">' . esc_html( reset( $categories )->name ) . '</span>';
        }
    }, 11 );

    add_filter( 'woocommerce_product_tabs', static function ( $tabs ) {
        global $product;

        if ( isset( $tabs['description'] ) || ! $product instanceof WC_Product ) {
            return $tabs;
        }

        $text = $product->get_description();
        if ( '' === trim( wp_strip_all_tags( $text ) ) ) {
            $text = $product->get_short_description();
        }

        if ( '' === trim( wp_strip_all_tags( $text ) ) ) {
            return $tabs;
        }

        $tabs['description'] = array(
            'title'    => __( 'Description', 'fruitables' ),
            'priority' => 10,
            'callback' => static function () use ( $text ) {
                echo '<div class="fruitables-tab-content">' . wp_kses_post( wpautop( $text ) ) . '</div>';
            },
        );

        return $tabs;
    }, 20 );

    add_filter( 'woocommerce_output_related_products_args', static function ( $args ) {
        $args['posts_per_page'] = 4;
        $args['columns']        = 4;
        return $args;
    } );
} );
