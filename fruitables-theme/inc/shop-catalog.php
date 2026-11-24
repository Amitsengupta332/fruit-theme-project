<?php
/** Fruitables catalog layout. Scope changes to Shop and product category pages. */
defined( 'ABSPATH' ) || exit;

function fruitables_is_catalog() {
    return function_exists( 'is_shop' ) && ( is_shop() || is_product_category() );
}

function fruitables_catalog_param( $key ) {
    return isset( $_GET[ $key ] ) && is_string( $_GET[ $key ] )
        ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) )
        : '';
}

// A classic theme without WooCommerce support can resolve /shop/ to the
// WordPress Page. Convert only that main query before WC_Query runs (priority 10).
add_action( 'pre_get_posts', static function ( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_page() || $query->is_preview() || ! function_exists( 'wc_get_page_id' ) ) {
        return;
    }

    $shop_id = wc_get_page_id( 'shop' );
    if ( $shop_id <= 0 || ! $query->is_page( $shop_id ) ) {
        return;
    }

    $query->set( 'post_type', 'product' );
    $query->set( 'page_id', '' );
    $query->set( 'pagename', '' );
    $query->set( 'name', '' );
    $query->set( 'page', '' );
    $query->is_page              = false;
    $query->is_singular          = false;
    $query->is_home              = false;
    $query->is_archive           = true;
    $query->is_post_type_archive = true;
}, 5 );

// Keep the real main product query on these archives. Otherwise classic themes
// without global WooCommerce support may get the shortcode/dummy-page fallback.
add_action( 'template_redirect', static function () {
    if ( fruitables_is_catalog() ) {
        remove_action( 'template_redirect', array( 'WC_Template_Loader', 'unsupported_theme_init' ), 10 );
    }
}, 1 );

add_filter( 'template_include', static function ( $template ) {
    return fruitables_is_catalog() ? get_theme_file_path( 'template-parts/shop/catalog.php' ) : $template;
}, 99 );

function fruitables_catalog_extra_filters() {
    $filters = array(
        'in-stock' => __( 'In stock', 'fruitables' ),
        'on-sale'  => __( 'On sale', 'fruitables' ),
        'featured' => __( 'Featured', 'fruitables' ),
    );
    foreach ( array( 'organic' => __( 'Organic', 'fruitables' ), 'fresh' => __( 'Fresh', 'fruitables' ) ) as $slug => $label ) {
        $term = get_term_by( 'slug', $slug, 'product_tag' );
        if ( $term && $term->count > 0 ) {
            $filters[ $slug ] = $label;
        }
    }
    return $filters;
}

add_action( 'woocommerce_product_query', static function ( $query ) {
    if ( ! $query->is_main_query() || ! ( $query->is_post_type_archive( 'product' ) || $query->is_tax( 'product_cat' ) || fruitables_is_catalog() ) ) {
        return;
    }

    // WooCommerce versions differ in their handling of blank price fields.
    foreach ( array( 'min_price', 'max_price' ) as $key ) {
        $value = fruitables_catalog_param( $key );
        if ( ! preg_match( '/^\d+(?:\.\d+)?$/D', $value ) ) {
            unset( $_GET[ $key ] );
        }
    }

    $keyword = fruitables_catalog_param( 'fruitables_q' );
    if ( '' !== $keyword ) {
        $query->set( 's', $keyword );
    }

    $extra = fruitables_catalog_param( 'fruitables_filter' );
    if ( 'on-sale' === $extra ) {
        $sale_ids = array_map( 'absint', wc_get_product_ids_on_sale() );
        $existing = $query->get( 'post__in' );
        if ( ! empty( $existing ) ) {
            $sale_ids = array_intersect( $sale_ids, array_map( 'absint', (array) $existing ) );
        }
        $query->set( 'post__in', array_values( $sale_ids ) ?: array( 0 ) );
    } elseif ( 'in-stock' === $extra ) {
        $query->set( 'meta_query', array(
            'relation' => 'AND',
            (array) $query->get( 'meta_query' ),
            array( 'key' => '_stock_status', 'value' => 'instock' ),
        ) );
    } elseif ( 'featured' === $extra || in_array( $extra, array( 'organic', 'fresh' ), true ) ) {
        $query->set( 'tax_query', array(
            'relation' => 'AND',
            (array) $query->get( 'tax_query' ),
            array(
                'taxonomy' => 'featured' === $extra ? 'product_visibility' : 'product_tag',
                'field'    => 'slug',
                'terms'    => $extra,
            ),
        ) );
    }
}, 10 );

function fruitables_catalog_badge() {
    $categories = get_the_terms( get_the_ID(), 'product_cat' );
    if ( ! is_array( $categories ) || empty( $categories ) ) {
        return;
    }

    $category = reset( $categories );
    $default  = (int) get_option( 'default_product_cat' );
    foreach ( $categories as $candidate ) {
        if ( (int) $candidate->term_id !== $default ) {
            $category = $candidate;
            break;
        }
    }
    echo '<span class="fruitables-card-category">' . esc_html( $category->name ) . '</span>';
}

function fruitables_catalog_card_description() {
    global $product;
    if ( ! $product instanceof WC_Product ) {
        return;
    }
    $description = wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), 16 );
    if ( '' !== $description ) {
        echo '<p class="fruitables-card-description">' . esc_html( $description ) . '</p>';
    }
}
