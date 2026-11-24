<?php
/** Top bar and navbar display settings; the actual links come from WordPress menus. */
defined( 'ABSPATH' ) || exit;

function fruitables_header_checkbox_sanitize( $value ) {
    return (bool) $value;
}

function fruitables_register_header_customizer( $wp_customize ) {
    $wp_customize->add_section( 'fruitables_topbar', array(
        'title'       => __( 'Top Bar', 'fruitables' ),
        'description' => __( 'Edit the address and email in Contact Details (Top Bar + Footer). Edit top-right links in Appearance → Menus → Top Bar Links.', 'fruitables' ),
        'priority'    => 30,
    ) );

    $wp_customize->add_section( 'fruitables_navbar', array(
        'title'       => __( 'Navbar Options', 'fruitables' ),
        'description' => __( 'Edit the logo and site title in Site Identity. Edit navigation links and dropdowns in Appearance → Menus (Primary Navigation).', 'fruitables' ),
        'priority'    => 31,
    ) );

    $settings = array(
        'fruitables_topbar_show' => array(
            'section' => 'fruitables_topbar', 'label' => __( 'Show top bar', 'fruitables' ),
            'type' => 'checkbox', 'default' => true, 'sanitize' => 'fruitables_header_checkbox_sanitize',
        ),
        'fruitables_navbar_search_show' => array(
            'section' => 'fruitables_navbar', 'label' => __( 'Show product search icon', 'fruitables' ),
            'type' => 'checkbox', 'default' => true, 'sanitize' => 'fruitables_header_checkbox_sanitize',
        ),
        'fruitables_navbar_cart_show' => array(
            'section' => 'fruitables_navbar', 'label' => __( 'Show WooCommerce cart icon', 'fruitables' ),
            'type' => 'checkbox', 'default' => true, 'sanitize' => 'fruitables_header_checkbox_sanitize',
        ),
        'fruitables_navbar_account_show' => array(
            'section' => 'fruitables_navbar', 'label' => __( 'Show account icon', 'fruitables' ),
            'type' => 'checkbox', 'default' => true, 'sanitize' => 'fruitables_header_checkbox_sanitize',
        ),
    );

    foreach ( $settings as $id => $field ) {
        $wp_customize->add_setting( $id, array(
            'type'              => 'theme_mod',
            'default'           => $field['default'],
            'sanitize_callback' => $field['sanitize'],
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( $id, array(
            'section' => $field['section'],
            'label'   => $field['label'],
            'type'    => $field['type'],
        ) );
    }
}
add_action( 'customize_register', 'fruitables_register_header_customizer' );
