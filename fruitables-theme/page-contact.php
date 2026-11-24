<?php
/**
 * Template Name: Contact
 * Description: Fruitables contact page; also selected automatically for the contact slug.
 */
defined( 'ABSPATH' ) || exit;
get_header();
$contact_title   = get_the_title( get_queried_object_id() );
$contact_address = trim( fruitables_shared_contact( 'address' ) );
$contact_url     = esc_url( fruitables_shared_contact( 'address_url' ) );
$contact_email   = sanitize_email( fruitables_shared_contact( 'email' ) );
$contact_phone   = trim( fruitables_shared_contact( 'phone' ) );
$contact_tel     = preg_replace( '/[^0-9+]/', '', $contact_phone );
$contact_hours   = trim( fruitables_contact_mod( 'hours' ) );
$map_url         = $contact_address ? add_query_arg( array( 'q' => rawurlencode( $contact_address ), 'output' => 'embed' ), 'https://maps.google.com/maps' ) : '';
$directions_url  = $contact_url ?: ( $contact_address ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $contact_address ) : '' );
$hero_image      = fruitables_contact_image( 'hero', 'img/home/hero-fresh-basket.avif' );
$cta_image       = fruitables_contact_image( 'cta', 'img/home/organic-delivery-banner.avif' );
$details = array(
    array( 'fas fa-phone-alt', __( 'Call Us', 'fruitables' ), $contact_phone, $contact_tel ? 'tel:' . $contact_tel : '' ),
    array( 'far fa-envelope', __( 'Email Us', 'fruitables' ), $contact_email, $contact_email ? 'mailto:' . $contact_email : '' ),
    array( 'fas fa-map-marker-alt', __( 'Our Location', 'fruitables' ), $contact_address, $directions_url ),
    array( 'far fa-clock', __( 'Working Hours', 'fruitables' ), $contact_hours, '' ),
);
$details = array_filter( $details, static function ( $item ) { return '' !== $item[2]; } );
?>
<main id="primary" class="ft-contact">
    <section class="fc-hero" aria-labelledby="fc-title">
        <div class="fc-hero-art"><img src="<?php echo esc_url( $hero_image ); ?>" alt="" width="1200" height="500" fetchpriority="high" decoding="async"></div>
        <div class="fc-wrap fc-hero-inner">
            <div class="fc-hero-copy">
                <p class="fc-eyebrow"><i class="fas fa-leaf" aria-hidden="true"></i> <?php echo esc_html( fruitables_contact_mod( 'eyebrow' ) ); ?></p>
                <h1 id="fc-title"><?php echo esc_html( $contact_title ); ?></h1>
                <p class="fc-hero-intro"><?php echo esc_html( fruitables_contact_mod( 'intro' ) ); ?></p>
                <nav class="fc-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'fruitables' ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="bi bi-house-door" aria-hidden="true"></i> <?php esc_html_e( 'Home', 'fruitables' ); ?></a><i class="bi bi-chevron-right" aria-hidden="true"></i><span aria-current="page"><?php echo esc_html( $contact_title ); ?></span></nav>
            </div>
        </div>
    </section>
    <div class="fc-wrap fc-content">
        <?php if ( $details ) : ?>
        <section class="fc-details" aria-label="<?php esc_attr_e( 'Contact information', 'fruitables' ); ?>">
            <?php foreach ( $details as $item ) : ?>
                <div class="fc-detail"><span class="fc-detail-icon"><i class="<?php echo esc_attr( $item[0] ); ?>" aria-hidden="true"></i></span><div><h2><?php echo esc_html( $item[1] ); ?></h2><p><?php if ( $item[3] ) : ?><a href="<?php echo esc_url( $item[3] ); ?>"><?php echo esc_html( $item[2] ); ?></a><?php else : echo esc_html( $item[2] ); endif; ?></p></div></div>
            <?php endforeach; ?>
        </section>
        <?php endif; ?>
        <?php while ( have_posts() ) : the_post(); if ( '' !== trim( get_the_content() ) ) : ?><div class="fc-page-content"><?php the_content(); ?></div><?php endif; endwhile; ?>
        <div class="fc-contact-grid">
            <section class="fc-panel fc-form-panel" id="fc-message" aria-labelledby="fc-form-title">
                <h2 id="fc-form-title"><i class="fas fa-leaf" aria-hidden="true"></i><?php echo esc_html( fruitables_contact_mod( 'form_title' ) ); ?></h2>
                <p class="fc-panel-description"><?php echo esc_html( fruitables_contact_mod( 'form_description' ) ); ?></p>
                <?php $form_html = fruitables_contact_form_html(); if ( '' !== trim( $form_html ) ) : ?>
                    <div class="fc-provider-form"><?php echo $form_html; // Trusted plugin-rendered form. ?></div>
                <?php else : ?>
                    <fieldset class="fc-form-preview" disabled aria-describedby="fc-form-note">
                        <div class="fc-field-row">
                            <div class="fc-field"><i class="bi bi-person" aria-hidden="true"></i><label class="visually-hidden" for="fc-name"><?php esc_html_e( 'Your Name (required)', 'fruitables' ); ?></label><input id="fc-name" type="text" placeholder="<?php esc_attr_e( 'Your Name *', 'fruitables' ); ?>" autocomplete="name" required></div>
                            <div class="fc-field"><i class="bi bi-envelope" aria-hidden="true"></i><label class="visually-hidden" for="fc-email"><?php esc_html_e( 'Your Email (required)', 'fruitables' ); ?></label><input id="fc-email" type="email" placeholder="<?php esc_attr_e( 'Your Email *', 'fruitables' ); ?>" autocomplete="email" required></div>
                        </div>
                        <div class="fc-field"><i class="bi bi-telephone" aria-hidden="true"></i><label class="visually-hidden" for="fc-phone"><?php esc_html_e( 'Your Phone (optional)', 'fruitables' ); ?></label><input id="fc-phone" type="tel" placeholder="<?php esc_attr_e( 'Your Phone (Optional)', 'fruitables' ); ?>" autocomplete="tel"></div>
                        <div class="fc-field fc-message-field"><i class="bi bi-chat-left-text" aria-hidden="true"></i><label class="visually-hidden" for="fc-text"><?php esc_html_e( 'Your Message (required)', 'fruitables' ); ?></label><textarea id="fc-text" rows="4" placeholder="<?php esc_attr_e( 'Your Message *', 'fruitables' ); ?>" required></textarea></div>
                        <button class="fc-button" type="button" disabled><i class="fas fa-paper-plane" aria-hidden="true"></i><?php esc_html_e( 'Send Message', 'fruitables' ); ?></button>
                    </fieldset>
                    <p id="fc-form-note" class="fc-form-note"><?php esc_html_e( 'Online message sending is not available yet.', 'fruitables' ); ?><?php if ( $contact_email ) : ?> <a href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>"><?php esc_html_e( 'Email us instead.', 'fruitables' ); ?></a><?php endif; ?></p>
                <?php endif; ?>
            </section>
            <section class="fc-panel fc-map-panel" aria-labelledby="fc-map-title">
                <h2 id="fc-map-title"><i class="fas fa-map-marker-alt" aria-hidden="true"></i><?php echo esc_html( fruitables_contact_mod( 'map_title' ) ); ?></h2>
                <?php if ( $map_url ) : ?><iframe src="<?php echo esc_url( $map_url ); ?>" title="<?php esc_attr_e( 'Business location map', 'fruitables' ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe><a class="fc-map-link" href="<?php echo esc_url( $directions_url ); ?>"><?php esc_html_e( 'Open map / Get directions', 'fruitables' ); ?> <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                <?php else : ?><div class="fc-map-empty"><i class="bi bi-geo-alt" aria-hidden="true"></i><p><?php esc_html_e( 'Business location has not been configured yet.', 'fruitables' ); ?></p></div><?php endif; ?>
            </section>
        </div>
        <section class="fc-cta" aria-labelledby="fc-cta-title">
            <div class="fc-cta-art"><img src="<?php echo esc_url( $cta_image ); ?>" alt="" width="640" height="320" loading="lazy" decoding="async"></div>
            <div class="fc-cta-copy"><h2 id="fc-cta-title"><?php echo esc_html( fruitables_contact_mod( 'cta_title' ) ); ?></h2><p><?php echo esc_html( fruitables_contact_mod( 'cta_description' ) ); ?></p></div>
            <a class="fc-button" href="<?php echo esc_url( fruitables_contact_mod( 'cta_url' ) ?: '#fc-message' ); ?>"><?php echo esc_html( fruitables_contact_mod( 'cta_label' ) ); ?><i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </section>
    </div>
</main>
<?php get_footer(); ?>
