<?php
/** Homepage Hero settings for the classic Theme Customizer. */
defined( 'ABSPATH' ) || exit;

function fruitables_hero_text_defaults() {
    return array(
        'eyebrow'            => __( 'Fresh · Organic · Natural', 'fruitables' ),
        'heading'            => __( 'Fresh Fruits & Vegetables Delivered to Your Door', 'fruitables' ),
        'search_placeholder' => __( 'Search for fruits, vegetables…', 'fruitables' ),
        'search_button'      => __( 'Search', 'fruitables' ),
        'slide_1_label'      => __( 'Fresh', 'fruitables' ),
        'slide_2_label'      => __( 'Vegetables', 'fruitables' ),
    );
}

function fruitables_sanitize_hero_url( $value ) {
    return esc_url_raw( $value );
}

function fruitables_register_hero_customizer( $customizer ) {
    $section = 'fruitables_home_hero';
    $defaults = fruitables_hero_text_defaults();

    $customizer->add_section( $section, array(
        'title'       => __( 'Homepage – Hero', 'fruitables' ),
        'description' => __( 'Preview the homepage to see your changes. Remove an uploaded image to restore the original theme image.', 'fruitables' ),
        'priority'    => 35,
    ) );

    $fields = array(
        'eyebrow'            => array( __( 'Small heading', 'fruitables' ), 'text' ),
        'heading'            => array( __( 'Main heading', 'fruitables' ), 'text' ),
        'search_placeholder' => array( __( 'Search placeholder', 'fruitables' ), 'text' ),
        'search_button'      => array( __( 'Search button text', 'fruitables' ), 'text' ),
        'background_image'   => array( __( 'Hero background image', 'fruitables' ), 'image' ),
        'slide_1_image'      => array( __( 'Slide 1 image', 'fruitables' ), 'image' ),
        'slide_1_label'      => array( __( 'Slide 1 button text', 'fruitables' ), 'text' ),
        'slide_1_link'       => array( __( 'Slide 1 button URL', 'fruitables' ), 'url' ),
        'slide_2_image'      => array( __( 'Slide 2 image', 'fruitables' ), 'image' ),
        'slide_2_label'      => array( __( 'Slide 2 button text', 'fruitables' ), 'text' ),
        'slide_2_link'       => array( __( 'Slide 2 button URL', 'fruitables' ), 'url' ),
    );

    foreach ( $fields as $field => list( $label, $type ) ) {
        $id = 'fruitables_hero_' . $field;
        $customizer->add_setting( $id, array(
            'type'              => 'theme_mod',
            'default'           => $defaults[ $field ] ?? ( 'image' === $type ? 0 : '' ),
            'sanitize_callback' => 'image' === $type ? 'absint' : ( 'url' === $type ? 'fruitables_sanitize_hero_url' : 'sanitize_text_field' ),
            'transport'         => 'refresh',
        ) );

        $args = array( 'label' => $label, 'section' => $section );
        if ( 'image' === $type ) {
            $args['mime_type'] = 'image';
            $customizer->add_control( new WP_Customize_Media_Control( $customizer, $id, $args ) );
        } else {
            $args['type'] = $type;
            $customizer->add_control( $id, $args );
        }
    }
}
add_action( 'customize_register', 'fruitables_register_hero_customizer' );
