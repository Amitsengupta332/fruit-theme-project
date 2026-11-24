<?php
/** WordPress menu locations, Bootstrap dropdown markup, and Woo cart badge. */
defined( 'ABSPATH' ) || exit;

/** Render a WordPress menu with the original Fruitables navbar CSS classes. */
class Fruitables_Primary_Menu_Walker extends Walker_Nav_Menu {
    private $opened_dropdown = false;

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '<div class="dropdown-menu m-0 bg-secondary rounded-0">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '</div>';
        }
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $has_children = 0 === $depth && ( $this->has_children || in_array( 'menu-item-has-children', (array) $item->classes, true ) );
        $is_active = ! empty( $item->current ) || in_array( 'current-menu-item', (array) $item->classes, true ) || in_array( 'current-menu-ancestor', (array) $item->classes, true );

        if ( 0 === $depth && $has_children ) {
            $output .= '<div class="nav-item dropdown">';
            $this->opened_dropdown = true;
        }

        $classes = 0 === $depth
            ? ( $has_children ? 'nav-link dropdown-toggle' : 'nav-item nav-link' )
            : 'dropdown-item';
        if ( $is_active ) {
            $classes .= ' active';
        }

        $attributes = ' class="' . esc_attr( $classes ) . '"';
        $attributes .= ' href="' . esc_url( $item->url ) . '"';
        if ( ! empty( $item->target ) ) {
            $attributes .= ' target="' . esc_attr( $item->target ) . '"';
        }
        if ( ! empty( $item->xfn ) ) {
            $attributes .= ' rel="' . esc_attr( $item->xfn ) . '"';
        }
        if ( ! empty( $item->current ) ) {
            $attributes .= ' aria-current="page"';
        }
        if ( $has_children ) {
            $attributes .= ' data-bs-toggle="dropdown" aria-expanded="false"';
        }
        $output .= '<a' . $attributes . '>' . esc_html( $item->title ) . '</a>';
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        if ( 0 === $depth && $this->opened_dropdown ) {
            $output .= '</div>';
            $this->opened_dropdown = false;
        }
    }
}

/** A usable first-run menu; it is replaced automatically when a Primary Menu is assigned. */
function fruitables_primary_menu_fallback( $args ) {
    echo '<div class="navbar-nav mx-auto">';
    echo '<a class="nav-item nav-link' . ( is_front_page() ? ' active' : '' ) . '" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'fruitables' ) . '</a>';

    if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) {
        $active = function_exists( 'is_shop' ) && ( is_shop() || is_product() || is_product_category() );
        echo '<a class="nav-item nav-link' . ( $active ? ' active' : '' ) . '" href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">' . esc_html__( 'Shop', 'fruitables' ) . '</a>';
    }

    $secondary = array();
    if ( function_exists( 'wc_get_page_id' ) ) {
        foreach ( array( 'cart' => __( 'Cart', 'fruitables' ), 'checkout' => __( 'Checkout', 'fruitables' ) ) as $key => $label ) {
            if ( wc_get_page_id( $key ) > 0 ) {
                $secondary[] = array( 'url' => wc_get_page_permalink( $key ), 'label' => $label );
            }
        }
    }
    if ( $secondary ) {
        echo '<div class="nav-item dropdown">';
        echo '<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">' . esc_html__( 'Pages', 'fruitables' ) . '</a>';
        echo '<div class="dropdown-menu m-0 bg-secondary rounded-0">';
        foreach ( $secondary as $link ) {
            echo '<a class="dropdown-item" href="' . esc_url( $link['url'] ) . '">' . esc_html( $link['label'] ) . '</a>';
        }
        echo '</div></div>';
    }

    $contact = get_page_by_path( 'contact' );
    if ( $contact instanceof WP_Post && 'publish' === $contact->post_status ) {
        echo '<a class="nav-item nav-link' . ( is_page( $contact->ID ) ? ' active' : '' ) . '" href="' . esc_url( get_permalink( $contact ) ) . '">' . esc_html__( 'Contact', 'fruitables' ) . '</a>';
    }
    echo '</div>';
}

/** Return only the cart-count element so WooCommerce AJAX fragments can refresh it. */
function fruitables_cart_count_html() {
    $count = function_exists( 'WC' ) && WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
    return '<span class="fruitables-cart-count position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">' . esc_html( (string) $count ) . '</span>';
}

add_filter( 'woocommerce_add_to_cart_fragments', static function ( $fragments ) {
    $fragments['span.fruitables-cart-count'] = fruitables_cart_count_html();
    return $fragments;
} );
