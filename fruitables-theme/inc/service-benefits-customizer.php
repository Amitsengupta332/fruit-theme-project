<?php
/** Customizer fields for the four homepage service-benefit cards. */
defined( 'ABSPATH' ) || exit;

/** Keep these original texts and icons as defaults to preserve the current design. */
function fruitables_service_benefits_defaults() {
    return array(
        'shipping' => array(
            'label'       => __( 'Free Shipping', 'fruitables' ),
            'title'       => __( 'Free Shipping', 'fruitables' ),
            'description' => __( 'Free on order over $300', 'fruitables' ),
            'icon'        => 'fa-car-side',
        ),
        'payment' => array(
            'label'       => __( 'Payment', 'fruitables' ),
            'title'       => __( 'Security Payment', 'fruitables' ),
            'description' => __( '100% security payment', 'fruitables' ),
            'icon'        => 'fa-user-shield',
        ),
        'return' => array(
            'label'       => __( 'Return', 'fruitables' ),
            'title'       => __( '30 Day Return', 'fruitables' ),
            'description' => __( '30 day money guarantee', 'fruitables' ),
            'icon'        => 'fa-exchange-alt',
        ),
        'support' => array(
            'label'       => __( 'Support', 'fruitables' ),
            'title'       => __( '24/7 Support', 'fruitables' ),
            'description' => __( 'Support every time fast', 'fruitables' ),
            'icon'        => 'fa-phone-alt',
        ),
    );
}

/** Restrict icon selection to known Font Awesome classes used by the theme. */
function fruitables_service_benefits_icon_choices() {
    return array(
        'fa-car-side'     => __( 'Car / Delivery', 'fruitables' ),
        'fa-truck'        => __( 'Truck', 'fruitables' ),
        'fa-shipping-fast'=> __( 'Fast Shipping', 'fruitables' ),
        'fa-user-shield'  => __( 'User Shield', 'fruitables' ),
        'fa-shield-alt'   => __( 'Shield', 'fruitables' ),
        'fa-credit-card'  => __( 'Credit Card', 'fruitables' ),
        'fa-lock'         => __( 'Lock', 'fruitables' ),
        'fa-exchange-alt' => __( 'Exchange', 'fruitables' ),
        'fa-undo-alt'     => __( 'Return Arrow', 'fruitables' ),
        'fa-phone-alt'    => __( 'Phone', 'fruitables' ),
        'fa-headset'      => __( 'Headset', 'fruitables' ),
        'fa-clock'        => __( 'Clock', 'fruitables' ),
    );
}

function fruitables_service_benefits_sanitize_checkbox( $value ) {
    return (bool) $value;
}

function fruitables_service_benefits_sanitize_icon( $value ) {
    $choices = fruitables_service_benefits_icon_choices();
    return is_string( $value ) && isset( $choices[ $value ] ) ? $value : 'fa-car-side';
}

function fruitables_register_service_benefits_customizer( $wp_customize ) {
    $section_id = 'fruitables_home_service_benefits';
    $wp_customize->add_section( $section_id, array(
        'title'       => __( 'Homepage – Service Benefits', 'fruitables' ),
        'description' => __( 'Edit the four homepage benefit cards. These texts are display-only; actual WooCommerce shipping, payments, and return policies must be configured separately.', 'fruitables' ),
        'priority'    => 36,
    ) );

    $wp_customize->add_setting( 'fruitables_benefits_show', array(
        'type'              => 'theme_mod',
        'default'           => true,
        'sanitize_callback' => 'fruitables_service_benefits_sanitize_checkbox',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'fruitables_benefits_show', array(
        'section' => $section_id,
        'label'   => __( 'Show entire benefits section', 'fruitables' ),
        'type'    => 'checkbox',
    ) );

    $icon_choices = fruitables_service_benefits_icon_choices();
    foreach ( fruitables_service_benefits_defaults() as $card_key => $card ) {
        $prefix = 'fruitables_benefit_' . $card_key . '_';
        $label  = $card['label'];

        $fields = array(
            'show' => array(
                'label'    => sprintf( __( '%s — Show card', 'fruitables' ), $label ),
                'type'     => 'checkbox',
                'default'  => true,
                'sanitize' => 'fruitables_service_benefits_sanitize_checkbox',
            ),
            'title' => array(
                'label'    => sprintf( __( '%s — Title', 'fruitables' ), $label ),
                'type'     => 'text',
                'default'  => $card['title'],
                'sanitize' => 'sanitize_text_field',
            ),
            'description' => array(
                'label'    => sprintf( __( '%s — Description', 'fruitables' ), $label ),
                'type'     => 'text',
                'default'  => $card['description'],
                'sanitize' => 'sanitize_text_field',
            ),
            'icon' => array(
                'label'    => sprintf( __( '%s — Icon', 'fruitables' ), $label ),
                'type'     => 'select',
                'default'  => $card['icon'],
                'sanitize' => 'fruitables_service_benefits_sanitize_icon',
                'choices'  => $icon_choices,
            ),
        );

        foreach ( $fields as $field_key => $field ) {
            $setting_id = $prefix . $field_key;
            $wp_customize->add_setting( $setting_id, array(
                'type'              => 'theme_mod',
                'default'           => $field['default'],
                'sanitize_callback' => $field['sanitize'],
                'transport'         => 'refresh',
            ) );
            $control = array(
                'section' => $section_id,
                'label'   => $field['label'],
                'type'    => $field['type'],
            );
            if ( isset( $field['choices'] ) ) {
                $control['choices'] = $field['choices'];
            }
            $wp_customize->add_control( $setting_id, $control );
        }
    }
}
add_action( 'customize_register', 'fruitables_register_service_benefits_customizer' );
