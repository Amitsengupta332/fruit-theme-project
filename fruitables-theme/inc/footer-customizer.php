<?php
/** One shared contact source for Top Bar and Footer, plus footer/newsletter settings. */
defined( 'ABSPATH' ) || exit;

function fruitables_shared_contact( $key ) {
    $legacy_keys = array(
        'address'     => 'fruitables_topbar_address',
        'address_url' => 'fruitables_topbar_address_url',
        'email'       => 'fruitables_topbar_email',
    );
    $value = get_theme_mod( 'fruitables_contact_' . $key, null );
    if ( null === $value && isset( $legacy_keys[ $key ] ) ) {
        // Existing explicitly saved top-bar details remain in use until edited in Contact Details.
        $value = get_theme_mod( $legacy_keys[ $key ], '' );
    }
    return (string) ( null === $value ? '' : $value );
}

/** Only accept genuine MailPoet or Mailchimp form shortcodes (no arbitrary input). */
function fruitables_sanitize_newsletter_shortcode( $value ) {
    $value = trim( (string) $value );
    if ( preg_match( '/^\[(?:mailpoet_form|mc4wp_form)(?:\s+id\s*=\s*(?:"[1-9][0-9]*"|\x27[1-9][0-9]*\x27|[1-9][0-9]*))?\s*\]$/i', $value ) ) {
        return $value;
    }
    return '';
}

/** Returns rendered provider form only when the plugin registered the shortcode. */
function fruitables_newsletter_form_html() {
    $shortcode = fruitables_sanitize_newsletter_shortcode( get_theme_mod( 'fruitables_footer_newsletter_shortcode', '' ) );
    if ( ! $shortcode || ! preg_match( '/^\[([a-z0-9_]+)/i', $shortcode, $matches ) || ! shortcode_exists( strtolower( $matches[1] ) ) ) {
        return '';
    }
    return do_shortcode( $shortcode );
}

function fruitables_register_footer_customizer( $customizer ) {
    $customizer->add_section( 'fruitables_contact_details', array(
        'title'       => __( 'Contact Details (Top Bar + Footer)', 'fruitables' ),
        'description' => __( 'These contact details are shared by the Top Bar and Footer. Existing saved Top Bar values are preserved until you edit them here. Blank fields are hidden.', 'fruitables' ),
        'priority'    => 32,
    ) );
    $customizer->add_section( 'fruitables_footer', array(
        'title'       => __( 'Footer + Newsletter', 'fruitables' ),
        'description' => __( 'Footer links: Appearance → Menus → Footer – Shop Info / Footer – Account. For newsletters, install and configure MailPoet or MC4WP and paste its form shortcode below.', 'fruitables' ),
        'priority'    => 33,
    ) );

    $fields = array(
        'fruitables_contact_address' => array( 'fruitables_contact_details', __( 'Address', 'fruitables' ), 'text', '', 'sanitize_text_field' ),
        'fruitables_contact_address_url' => array( 'fruitables_contact_details', __( 'Address / map URL', 'fruitables' ), 'url', '', 'esc_url_raw' ),
        'fruitables_contact_email' => array( 'fruitables_contact_details', __( 'Email', 'fruitables' ), 'email', '', 'sanitize_email' ),
        'fruitables_contact_phone' => array( 'fruitables_contact_details', __( 'Phone number', 'fruitables' ), 'text', '', 'sanitize_text_field' ),
        'fruitables_footer_tagline' => array( 'fruitables_footer', __( 'Brand tagline', 'fruitables' ), 'text', '', 'sanitize_text_field' ),
        'fruitables_footer_about_title' => array( 'fruitables_footer', __( 'About us heading', 'fruitables' ), 'text', 'Why People Like Us!', 'sanitize_text_field' ),
        'fruitables_footer_about_text' => array( 'fruitables_footer', __( 'About us description', 'fruitables' ), 'textarea', '', 'sanitize_textarea_field' ),
        'fruitables_footer_read_more_url' => array( 'fruitables_footer', __( 'Read More URL (optional)', 'fruitables' ), 'url', '', 'esc_url_raw' ),
        'fruitables_footer_newsletter_shortcode' => array( 'fruitables_footer', __( 'Newsletter form shortcode (MailPoet or MC4WP)', 'fruitables' ), 'text', '', 'fruitables_sanitize_newsletter_shortcode' ),
        'fruitables_footer_twitter' => array( 'fruitables_footer', __( 'X / Twitter URL', 'fruitables' ), 'url', '', 'esc_url_raw' ),
        'fruitables_footer_facebook' => array( 'fruitables_footer', __( 'Facebook URL', 'fruitables' ), 'url', '', 'esc_url_raw' ),
        'fruitables_footer_youtube' => array( 'fruitables_footer', __( 'YouTube URL', 'fruitables' ), 'url', '', 'esc_url_raw' ),
        'fruitables_footer_linkedin' => array( 'fruitables_footer', __( 'LinkedIn URL', 'fruitables' ), 'url', '', 'esc_url_raw' ),
        'fruitables_footer_payment_show' => array( 'fruitables_footer', __( 'Show original payment-method image (only if accurate)', 'fruitables' ), 'checkbox', false, 'fruitables_header_checkbox_sanitize' ),
    );
    foreach ( $fields as $id => $field ) {
        list( $section, $label, $type, $default, $sanitize ) = $field;
        $customizer->add_setting( $id, array(
            'type' => 'theme_mod', 'default' => $default,
            'sanitize_callback' => $sanitize, 'transport' => 'refresh',
        ) );
        $customizer->add_control( $id, array(
            'section' => $section, 'label' => $label, 'type' => $type,
        ) );
    }
}
add_action( 'customize_register', 'fruitables_register_footer_customizer' );
