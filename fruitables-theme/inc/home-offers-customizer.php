<?php
/** Homepage offer cards + exotic fruits banner: editable presentation only. */
defined( 'ABSPATH' ) || exit;

function fruitables_home_offers_defaults() {
    return array(
        1 => array( 'title' => __( 'Fresh Apples', 'fruitables' ), 'offer' => __( '20% OFF', 'fruitables' ), 'image' => 'img/featur-1.jpg', 'card' => 'secondary', 'content' => 'primary', 'title_color' => 'white' ),
        2 => array( 'title' => __( 'Tasty Fruits', 'fruitables' ), 'offer' => __( 'Free delivery', 'fruitables' ), 'image' => 'img/featur-2.jpg', 'card' => 'dark', 'content' => 'light', 'title_color' => 'primary' ),
        3 => array( 'title' => __( 'Exotic Vegitable', 'fruitables' ), 'offer' => __( 'Discount 30$', 'fruitables' ), 'image' => 'img/featur-3.jpg', 'card' => 'primary', 'content' => 'secondary', 'title_color' => 'white' ),
    );
}

function fruitables_exotic_banner_defaults() {
    return array(
        'heading'     => __( 'Organic Produce Delivery', 'fruitables' ),
        'subheading'  => __( 'Fresh to your door', 'fruitables' ),
        'description' => __( 'Farm-fresh fruits and vegetables delivered fast, safe and fresh right to your home.', 'fruitables' ),
        'button'      => __( 'Shop Now', 'fruitables' ),
    );
}

/** Listed products are used only for the optional live-price selector. */
function fruitables_exotic_banner_product_choices() {
    $choices = array( 0 => __( 'No product — hide price badge', 'fruitables' ) );
    if ( ! function_exists( 'wc_get_products' ) ) {
        return $choices;
    }

    $products = wc_get_products( array(
        'status'  => 'publish',
        'limit'   => 300,
        'orderby' => 'title',
        'order'   => 'ASC',
    ) );
    foreach ( $products as $product ) {
        if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
            continue;
        }
        $choices[ $product->get_id() ] = $product->get_name() . ' (#' . $product->get_id() . ')';
    }
    return $choices;
}

function fruitables_home_offers_sanitize_checkbox( $value ) {
    return (bool) $value;
}

function fruitables_register_home_offers_customizer( $customizer ) {
    $cards_section = 'fruitables_home_offer_cards';
    $banner_section = 'fruitables_home_exotic_banner';
    $customizer->add_section( $cards_section, array(
        'title'       => __( 'Homepage – Three Offer Cards', 'fruitables' ),
        'description' => __( 'Edit the card images, texts, and links. Actual discounts and delivery rules must be configured separately in WooCommerce. Unset links produce non-clickable cards.', 'fruitables' ),
        'priority'    => 37,
    ) );
    $customizer->add_section( $banner_section, array(
        'title'       => __( 'Homepage – Delivery Banner', 'fruitables' ),
        'description' => __( 'The price badge appears only when a WooCommerce product is selected and has a valid price. The BUY link uses your custom URL, otherwise the selected product URL, otherwise the shop page.', 'fruitables' ),
        'priority'    => 38,
    ) );

    foreach ( fruitables_home_offers_defaults() as $number => $card ) {
        $prefix = 'fruitables_offer_card_' . $number . '_';
        $fields = array(
            'show'  => array( 'label' => 'Show this card', 'type' => 'checkbox', 'default' => true, 'sanitize' => 'fruitables_home_offers_sanitize_checkbox' ),
            'title' => array( 'label' => 'Heading', 'type' => 'text', 'default' => $card['title'], 'sanitize' => 'sanitize_text_field' ),
            'offer' => array( 'label' => 'Offer text', 'type' => 'text', 'default' => $card['offer'], 'sanitize' => 'sanitize_text_field' ),
            'link'  => array( 'label' => 'Card link URL', 'type' => 'url', 'default' => '', 'sanitize' => 'esc_url_raw' ),
            'image' => array( 'label' => 'Card image', 'type' => 'image', 'default' => 0, 'sanitize' => 'absint' ),
        );
        foreach ( $fields as $key => $field ) {
            $setting = $prefix . $key;
            $customizer->add_setting( $setting, array(
                'type'              => 'theme_mod',
                'default'           => $field['default'],
                'sanitize_callback' => $field['sanitize'],
                'transport'         => 'refresh',
            ) );
            $control_args = array(
                'label'   => sprintf( __( 'Card %1$d — %2$s', 'fruitables' ), $number, $field['label'] ),
                'section' => $cards_section,
            );
            if ( 'image' === $field['type'] ) {
                $control_args['mime_type'] = 'image';
                $customizer->add_control( new WP_Customize_Media_Control( $customizer, $setting, $control_args ) );
            } else {
                $control_args['type'] = $field['type'];
                $customizer->add_control( $setting, $control_args );
            }
        }
    }

    $banner_fields = array(
        'heading'     => array( 'label' => __( 'Main heading', 'fruitables' ), 'type' => 'text', 'default' => fruitables_exotic_banner_defaults()['heading'], 'sanitize' => 'sanitize_text_field' ),
        'subheading'  => array( 'label' => __( 'Second heading', 'fruitables' ), 'type' => 'text', 'default' => fruitables_exotic_banner_defaults()['subheading'], 'sanitize' => 'sanitize_text_field' ),
        'description' => array( 'label' => __( 'Description', 'fruitables' ), 'type' => 'textarea', 'default' => fruitables_exotic_banner_defaults()['description'], 'sanitize' => 'sanitize_textarea_field' ),
        'button'      => array( 'label' => __( 'Button text', 'fruitables' ), 'type' => 'text', 'default' => fruitables_exotic_banner_defaults()['button'], 'sanitize' => 'sanitize_text_field' ),
        'button_link' => array( 'label' => __( 'Buy button URL (optional)', 'fruitables' ), 'type' => 'url', 'default' => '', 'sanitize' => 'esc_url_raw' ),
        'image'       => array( 'label' => __( 'Banner image', 'fruitables' ), 'type' => 'image', 'default' => 0, 'sanitize' => 'absint' ),
        'product_id'  => array( 'label' => __( 'WooCommerce product for LIVE price badge', 'fruitables' ), 'type' => 'select', 'default' => 0, 'sanitize' => 'absint', 'choices' => fruitables_exotic_banner_product_choices() ),
    );
    foreach ( $banner_fields as $key => $field ) {
        $setting = 'fruitables_exotic_banner_' . $key;
        $customizer->add_setting( $setting, array(
            'type'              => 'theme_mod',
            'default'           => $field['default'],
            'sanitize_callback' => $field['sanitize'],
            'transport'         => 'refresh',
        ) );
        $args = array( 'label' => $field['label'], 'section' => $banner_section );
        if ( 'image' === $field['type'] ) {
            $args['mime_type'] = 'image';
            $customizer->add_control( new WP_Customize_Media_Control( $customizer, $setting, $args ) );
        } else {
            $args['type'] = $field['type'];
            if ( isset( $field['choices'] ) ) {
                $args['choices'] = $field['choices'];
            }
            $customizer->add_control( $setting, $args );
        }
    }
}
add_action( 'customize_register', 'fruitables_register_home_offers_customizer' );
