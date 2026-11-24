<?php
/** Footer settings, genuine provider form, and real WordPress menus. */
$footer_about_title = trim( (string) get_theme_mod( 'fruitables_footer_about_title', 'Why People Like Us!' ) );
$footer_about       = trim( (string) get_theme_mod( 'fruitables_footer_about_text', '' ) );
$footer_tagline     = trim( (string) get_theme_mod( 'fruitables_footer_tagline', '' ) );
$footer_more        = get_theme_mod( 'fruitables_footer_read_more_url', '' );
$footer_address     = trim( fruitables_shared_contact( 'address' ) );
$footer_address_url = fruitables_shared_contact( 'address_url' );
$footer_email       = sanitize_email( fruitables_shared_contact( 'email' ) );
$footer_phone       = trim( fruitables_shared_contact( 'phone' ) );
$newsletter_html    = fruitables_newsletter_form_html();
$footer_socials     = array(
    'twitter'  => array( 'fab fa-twitter', __( 'X / Twitter', 'fruitables' ) ),
    'facebook' => array( 'fab fa-facebook-f', __( 'Facebook', 'fruitables' ) ),
    'youtube'  => array( 'fab fa-youtube', __( 'YouTube', 'fruitables' ) ),
    'linkedin' => array( 'fab fa-linkedin-in', __( 'LinkedIn', 'fruitables' ) ),
);
?>
<?php if ( is_front_page() ) { require get_theme_file_path( 'template-parts/home/footer.php' ); } elseif ( fruitables_is_contact_page() ) { require get_theme_file_path( 'template-parts/contact/footer.php' ); } else { ?>
<!-- Footer Start -->
<footer class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-5">
        <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5);">
            <div class="row g-4">
                <div class="col-lg-3">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <h2 class="text-primary mb-0"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h2>
                        <?php if ( $footer_tagline ) : ?>
                            <p class="text-secondary mb-0"><?php echo esc_html( $footer_tagline ); ?></p>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="col-lg-6">
                    <?php if ( '' !== trim( $newsletter_html ) ) : ?>
                        <div class="fruitables-newsletter position-relative mx-auto">
                            <?php echo $newsletter_html; // Trusted, plugin-rendered shortcode output. ?>
                        </div>
                    <?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
                        <p class="small text-white-50 mt-3"><?php esc_html_e( 'Newsletter: install MailPoet or MC4WP, create a signup form, and paste the form shortcode in Customize → Footer + Newsletter.', 'fruitables' ); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-lg-3">
                    <div class="d-flex justify-content-end pt-3">
                        <?php foreach ( $footer_socials as $platform => $info ) : ?>
                            <?php $social_url = get_theme_mod( 'fruitables_footer_' . $platform, '' ); ?>
                            <?php if ( $social_url ) : ?>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href="<?php echo esc_url( $social_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $info[1] ); ?>"><i class="<?php echo esc_attr( $info[0] ); ?>" aria-hidden="true"></i></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <?php if ( $footer_about_title ) : ?>
                        <h3 class="h4 text-light mb-3"><?php echo esc_html( $footer_about_title ); ?></h3>
                    <?php endif; ?>
                    <?php if ( $footer_about ) : ?>
                        <p class="mb-4"><?php echo nl2br( esc_html( $footer_about ) ); ?></p>
                    <?php endif; ?>
                    <?php if ( $footer_more ) : ?>
                        <a href="<?php echo esc_url( $footer_more ); ?>" class="btn border-secondary py-2 px-4 rounded-pill text-primary"><?php esc_html_e( 'Read More', 'fruitables' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h3 class="h4 text-light mb-3"><?php esc_html_e( 'Shop Info', 'fruitables' ); ?></h3>
                    <?php if ( has_nav_menu( 'footer_shop' ) ) : ?>
                        <?php wp_nav_menu( array(
                            'theme_location' => 'footer_shop', 'container' => false,
                            'menu_class' => 'list-unstyled m-0 fruitables-footer-links',
                            'depth' => 1, 'fallback_cb' => false,
                        ) ); ?>
                    <?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
                        <p class="small"><?php esc_html_e( 'Assign the Footer – Shop Info menu in Appearance → Menus.', 'fruitables' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h3 class="h4 text-light mb-3"><?php esc_html_e( 'Account', 'fruitables' ); ?></h3>
                    <?php if ( has_nav_menu( 'footer_account' ) ) : ?>
                        <?php wp_nav_menu( array(
                            'theme_location' => 'footer_account', 'container' => false,
                            'menu_class' => 'list-unstyled m-0 fruitables-footer-links',
                            'depth' => 1, 'fallback_cb' => false,
                        ) ); ?>
                    <?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
                        <p class="small"><?php esc_html_e( 'Assign the Footer – Account menu in Appearance → Menus.', 'fruitables' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h3 class="h4 text-light mb-3"><?php esc_html_e( 'Contact', 'fruitables' ); ?></h3>
                    <?php if ( $footer_address ) : ?>
                        <p><?php esc_html_e( 'Address:', 'fruitables' ); ?>
                            <?php if ( $footer_address_url ) : ?>
                                <a class="fruitables-contact-link" href="<?php echo esc_url( $footer_address_url ); ?>"><?php echo esc_html( $footer_address ); ?></a>
                            <?php else : ?>
                                <?php echo esc_html( $footer_address ); ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( $footer_email ) : ?>
                        <p><?php esc_html_e( 'Email:', 'fruitables' ); ?> <a class="fruitables-contact-link" href="<?php echo esc_url( 'mailto:' . $footer_email ); ?>"><?php echo esc_html( $footer_email ); ?></a></p>
                    <?php endif; ?>
                    <?php if ( $footer_phone ) : ?>
                        <p><?php esc_html_e( 'Phone:', 'fruitables' ); ?> <a class="fruitables-contact-link" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $footer_phone ) ); ?>"><?php echo esc_html( $footer_phone ); ?></a></p>
                    <?php endif; ?>
                    <?php if ( (bool) get_theme_mod( 'fruitables_footer_payment_show', false ) ) : ?>
                        <p><?php esc_html_e( 'Payment Accepted', 'fruitables' ); ?></p>
                        <img src="<?php echo esc_url( get_theme_file_uri( 'img/payment.png' ) ); ?>" class="img-fluid" alt="<?php esc_attr_e( 'Accepted payment methods', 'fruitables' ); ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->

<!-- Copyright Start -->
<div class="container-fluid copyright bg-dark py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="text-light"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-copyright text-light me-2" aria-hidden="true"></i><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>, <?php esc_html_e( 'All rights reserved.', 'fruitables' ); ?></span>
            </div>
            <div class="col-md-6 my-auto text-center text-md-end text-white">
                Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->
<?php } ?>

<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'fruitables' ); ?>"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>

<?php wp_footer(); ?>
</body>
</html>
