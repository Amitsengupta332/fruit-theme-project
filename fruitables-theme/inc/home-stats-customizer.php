<?php
/** Homepage stats: verified business values and live in-stock WooCommerce product count. */
defined( 'ABSPATH' ) || exit;

/** Keep blank values blank: never invent counts for unpublished business facts. */
function fruitables_stats_sanitize_nonnegative_count( $value ) {
    $value = trim( (string) $value );
    if ( '' === $value ) {
        return '';
    }
    // Reject signs, fractions, non-digits and overflowing values.
    if ( ! preg_match( '/^[0-9]{1,10}$/', $value ) ) {
        return '';
    }
    return (string) (int) $value;
}

/** Quality is an optional percentage from 0–100, not an invented default. */
function fruitables_stats_sanitize_percentage( $value ) {
    $value = trim( (string) $value );
    if ( '' === $value || ! preg_match( '/^[0-9]{1,3}(?:\.[0-9]{1,2})?$/', $value ) || (float) $value > 100 ) {
        return '';
    }
    return rtrim( rtrim( number_format( (float) $value, 2, '.', '' ), '0' ), '.' );
}

function fruitables_stats_sanitize_checkbox( $value ) {
    return (bool) $value;
}

/**
 * Count published product entries WooCommerce reports as 'instock'.
 * Variable products count as one parent product; individual variants aren't counted.
 * WooCommerce's query API supports stock_status and pagination's total field,
 * avoiding loading the whole catalog into PHP memory.
 *
 * @return int|null Null only if WooCommerce is missing/unable to provide a count.
 */
function fruitables_stats_available_products_count() {
    if ( ! function_exists( 'wc_get_products' ) ) {
        return null;
    }

    $query = wc_get_products( array(
        'status'       => 'publish',
        'stock_status' => 'instock',
        'limit'        => 1,
        'page'         => 1,
        'paginate'     => true,
        'return'       => 'ids',
    ) );

    if ( ! is_object( $query ) || ! isset( $query->total ) || ! is_numeric( $query->total ) ) {
        return null;
    }
    return max( 0, (int) $query->total );
}

function fruitables_register_home_stats_customizer( $customizer ) {
    $section = 'fruitables_home_stats';
    $customizer->add_section( $section, array(
        'title'       => __( 'Homepage – Business Stats', 'fruitables' ),
        'description' => __( 'Enter only verified business figures. Leave an unknown value empty to hide its card. Available Products comes automatically from published, in-stock WooCommerce products (each product counted once).', 'fruitables' ),
        'priority'    => 39,
    ) );

    $customizer->add_setting( 'fruitables_stats_show', array(
        'type' => 'theme_mod', 'default' => true,
        'sanitize_callback' => 'fruitables_stats_sanitize_checkbox', 'transport' => 'refresh',
    ) );
    $customizer->add_control( 'fruitables_stats_show', array(
        'label' => __( 'Show stats section', 'fruitables' ), 'section' => $section, 'type' => 'checkbox',
    ) );

    $metrics = array(
        'customers' => array(
            'name' => __( 'Customers', 'fruitables' ),
            'title' => __( 'satisfied customers', 'fruitables' ),
            'description' => __( 'Verified number of satisfied customers; leave empty if unknown.', 'fruitables' ),
            'sanitize' => 'fruitables_stats_sanitize_nonnegative_count',
        ),
        'quality' => array(
            'name' => __( 'Quality', 'fruitables' ),
            'title' => __( 'quality of service', 'fruitables' ),
            'description' => __( 'Verified quality percentage (0–100). Enter only the number, without %; leave empty if unknown.', 'fruitables' ),
            'sanitize' => 'fruitables_stats_sanitize_percentage',
        ),
        'certificates' => array(
            'name' => __( 'Certificates', 'fruitables' ),
            'title' => __( 'quality certificates', 'fruitables' ),
            'description' => __( 'Verified number of certificates; leave empty if unknown.', 'fruitables' ),
            'sanitize' => 'fruitables_stats_sanitize_nonnegative_count',
        ),
        'products' => array(
            'name' => __( 'Available Products', 'fruitables' ),
            'title' => __( 'Available Products', 'fruitables' ),
        ),
    );

    foreach ( $metrics as $key => $metric ) {
        $prefix = 'fruitables_stats_' . $key . '_';
        $customizer->add_setting( $prefix . 'show', array(
            'type' => 'theme_mod', 'default' => true,
            'sanitize_callback' => 'fruitables_stats_sanitize_checkbox', 'transport' => 'refresh',
        ) );
        $customizer->add_control( $prefix . 'show', array(
            'label' => sprintf( __( '%s — Show card', 'fruitables' ), $metric['name'] ),
            'section' => $section, 'type' => 'checkbox',
        ) );

        $customizer->add_setting( $prefix . 'title', array(
            'type' => 'theme_mod', 'default' => $metric['title'],
            'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh',
        ) );
        $customizer->add_control( $prefix . 'title', array(
            'label' => sprintf( __( '%s — Card title', 'fruitables' ), $metric['name'] ),
            'section' => $section, 'type' => 'text',
        ) );

        if ( 'products' !== $key ) {
            $customizer->add_setting( $prefix . 'value', array(
                'type' => 'theme_mod', 'default' => '',
                'sanitize_callback' => $metric['sanitize'], 'transport' => 'refresh',
            ) );
            $customizer->add_control( $prefix . 'value', array(
                'label' => sprintf( __( '%s — Verified figure', 'fruitables' ), $metric['name'] ),
                'description' => $metric['description'],
                'section' => $section, 'type' => 'number',
                'input_attrs' => 'quality' === $key
                    ? array( 'min' => 0, 'max' => 100, 'step' => 0.01 )
                    : array( 'min' => 0, 'step' => 1 ),
            ) );
        }
    }
}
add_action( 'customize_register', 'fruitables_register_home_stats_customizer' );
