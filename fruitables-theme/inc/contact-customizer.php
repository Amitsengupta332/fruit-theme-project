<?php
/** Contact page presentation settings; shared business details remain the source of truth. */
defined( 'ABSPATH' ) || exit;

function fruitables_is_contact_page() {
    return is_page_template( 'page-contact.php' ) || ( is_page( 'contact' ) && in_array( get_page_template_slug( get_queried_object_id() ), array( '', 'default', 'page-contact.php' ), true ) );
}
add_filter( 'body_class', static function ( $classes ) {
    if ( fruitables_is_contact_page() ) { $classes[] = 'ft-contact-page'; }
    return $classes;
} );

function fruitables_contact_defaults() {
    return array(
        'eyebrow' => __( 'Get in Touch', 'fruitables' ),
        'intro' => __( "We’d love to hear from you! Reach out for any questions, orders, support or business inquiries.", 'fruitables' ),
        'hours' => '',
        'form_title' => __( 'Send Us a Message', 'fruitables' ),
        'form_description' => __( 'Have a question, suggestion or need support? Fill out the form below and we’ll get back to you as soon as possible.', 'fruitables' ),
        'map_title' => __( 'Find Us on Map', 'fruitables' ),
        'cta_title' => __( 'Get in Touch with Fruitables', 'fruitables' ),
        'cta_description' => __( 'We’re here to help you with fresh produce, orders, delivery and anything you need.', 'fruitables' ),
        'cta_label' => __( 'Contact Us Now', 'fruitables' ),
        'cta_url' => '',
    );
}
function fruitables_contact_mod( $key ) {
    $defaults = fruitables_contact_defaults();
    return get_theme_mod( 'fruitables_contact_page_' . $key, $defaults[$key] ?? '' );
}
/** Prefer the selected Media Library image, then new bundled image, then existing artwork. */
function fruitables_contact_image( $key, $fallback ) {
    $id = absint( get_theme_mod( 'fruitables_contact_page_' . $key . '_image', 0 ) );
    $url = $id ? wp_get_attachment_image_url( $id, 'full' ) : false;
    if ( $url ) { return $url; }
    $path = 'img/contact/contact-' . $key . '.avif';
    return get_theme_file_uri( is_readable( get_theme_file_path( $path ) ) ? $path : $fallback );
}
/** Only supported form-provider shortcodes, and only when a provider is active. */
function fruitables_sanitize_contact_shortcode( $value ) {
    $value = trim( (string) $value );
    return preg_match( '/^\[(?:contact-form-7|wpforms)\s+id\s*=\s*(?:"[a-zA-Z0-9_-]+"|\x27[a-zA-Z0-9_-]+\x27|[a-zA-Z0-9_-]+)\s*\]$/D', $value ) ? $value : '';
}
function fruitables_contact_form_html() {
    $shortcode = fruitables_sanitize_contact_shortcode( get_theme_mod( 'fruitables_contact_page_shortcode', '' ) );
    if ( ! $shortcode || ! preg_match( '/^\[([a-z0-9-]+)/', $shortcode, $match ) || ! shortcode_exists( $match[1] ) ) { return ''; }
    return do_shortcode( $shortcode );
}
add_action( 'customize_register', static function ( $c ) {
    $section = 'fruitables_contact_page';
    $c->add_section( $section, array( 'title' => __( 'Contact Page', 'fruitables' ), 'priority' => 35, 'description' => __( 'Edit page content and images here. Address, email and phone are shared with Contact Details (Top Bar + Footer). The page title comes from Pages. Add a configured contact form only when ready to receive messages.', 'fruitables' ) ) );
    foreach ( fruitables_contact_defaults() as $key => $default ) {
        $id = 'fruitables_contact_page_' . $key;
        $type = 'cta_url' === $key ? 'url' : ( in_array( $key, array( 'intro', 'form_description', 'cta_description' ), true ) ? 'textarea' : 'text' );
        $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'url' === $type ? 'esc_url_raw' : ( 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field' ) ) );
        $c->add_control( $id, array( 'section' => $section, 'label' => ucwords( str_replace( '_', ' ', $key ) ), 'type' => $type ) );
    }
    foreach ( array( 'hero' => __( 'Contact hero photo', 'fruitables' ), 'cta' => __( 'Contact CTA photo', 'fruitables' ) ) as $key => $label ) {
        $id = 'fruitables_contact_page_' . $key . '_image';
        $c->add_setting( $id, array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
        $c->add_control( new WP_Customize_Media_Control( $c, $id, array( 'section' => $section, 'label' => $label, 'mime_type' => 'image' ) ) );
    }
    $id = 'fruitables_contact_page_shortcode';
    $c->add_setting( $id, array( 'default' => '', 'sanitize_callback' => 'fruitables_sanitize_contact_shortcode' ) );
    $c->add_control( $id, array( 'section' => $section, 'label' => __( 'Configured contact form shortcode (optional)', 'fruitables' ), 'description' => __( 'Use [contact-form-7 id="123"] or [wpforms id="123"]. Leave empty to show a disabled design preview.', 'fruitables' ), 'type' => 'text' ) );
} );
