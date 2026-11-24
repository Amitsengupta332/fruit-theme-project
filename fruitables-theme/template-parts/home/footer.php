<?php /** Homepage-only footer using existing footer settings and menus. */ ?>
<footer class="ft-footer footer">
    <div class="ft-wrap ft-newsletter"><div class="ft-newsletter-intro"><i class="bi bi-envelope" aria-hidden="true"></i><div><h2><?php echo esc_html( fruitables_home_mod( 'newsletter_title' ) ); ?></h2><p><?php echo esc_html( fruitables_home_mod( 'newsletter_description' ) ); ?></p></div></div>
        <div class="fruitables-newsletter ft-newsletter-form">
        <?php if ( trim( $newsletter_html ) !== '' ) : echo $newsletter_html; else : ?>
            <div class="ft-newsletter-preview"><input type="email" placeholder="<?php esc_attr_e( 'Enter your email address', 'fruitables' ); ?>" aria-label="<?php esc_attr_e( 'Newsletter not configured', 'fruitables' ); ?>" disabled><button type="button" disabled><?php esc_html_e( 'Subscribe', 'fruitables' ); ?></button></div>
            <small class="ft-newsletter-note"><?php esc_html_e( 'Newsletter signup is not available yet.', 'fruitables' ); ?></small>
        <?php endif; ?>
        </div>
    </div>
    <div class="ft-footer-main"><div class="ft-wrap ft-footer-grid">
        <div class="ft-footer-brand"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ft-brand"><i class="fas fa-leaf" aria-hidden="true"></i><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
            <?php if ( $footer_tagline ) : ?><p><?php echo esc_html( $footer_tagline ); ?></p><?php endif; ?>
            <?php if ( $footer_about_title && 'Why People Like Us!' !== $footer_about_title ) : ?><h3><?php echo esc_html( $footer_about_title ); ?></h3><?php endif; ?>
            <?php if ( $footer_about ) : ?><p><?php echo nl2br( esc_html( $footer_about ) ); ?></p><?php else : ?><p><?php esc_html_e( 'Your online store for fresh fruits and vegetables.', 'fruitables' ); ?></p><?php endif; ?>
            <?php if ( $footer_more ) : ?><a class="ft-text-link" href="<?php echo esc_url( $footer_more ); ?>"><?php esc_html_e( 'Read More', 'fruitables' ); ?></a><?php endif; ?>
            <div class="ft-socials"><?php foreach ( $footer_socials as $platform => $info ) : $social_url = get_theme_mod( 'fruitables_footer_' . $platform, '' ); if ( ! $social_url ) { continue; } ?><a href="<?php echo esc_url( $social_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $info[1] ); ?>"><i class="<?php echo esc_attr( $info[0] ); ?>" aria-hidden="true"></i></a><?php endforeach; ?></div>
        </div>
        <?php foreach ( array( 'footer_shop' => __( 'Quick Links', 'fruitables' ), 'categories' => __( 'Categories', 'fruitables' ), 'footer_account' => __( 'Help & Support', 'fruitables' ) ) as $location => $title ) : ?><div><h3><?php echo esc_html( $title ); ?></h3>
            <?php if ( 'categories' === $location ) : ?><ul class="ft-footer-links"><?php foreach ( fruitables_home_categories() as $term ) : $link = get_term_link( $term ); if ( is_wp_error( $link ) ) { continue; } ?><li><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $term->name ); ?></a></li><?php endforeach; ?></ul>
            <?php elseif ( has_nav_menu( $location ) ) : wp_nav_menu( array( 'theme_location' => $location, 'container' => false, 'menu_class' => 'ft-footer-links', 'depth' => 1, 'fallback_cb' => false ) );
            elseif ( 'footer_shop' === $location ) : ?><ul class="ft-footer-links"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'fruitables' ); ?></a></li><li><a href="<?php echo esc_url( fruitables_home_shop_url() ); ?>"><?php esc_html_e( 'Shop', 'fruitables' ); ?></a></li></ul><?php elseif ( $footer_email ) : ?><a href="<?php echo esc_url( 'mailto:' . $footer_email ); ?>"><?php esc_html_e( 'Contact support', 'fruitables' ); ?></a><?php endif; ?>
        </div><?php endforeach; ?>
        <div class="ft-footer-contact"><h3><?php esc_html_e( 'Contact Us', 'fruitables' ); ?></h3>
            <?php if ( $footer_address ) : ?><p><i class="bi bi-geo-alt" aria-hidden="true"></i><?php if ( $footer_address_url ) : ?><a href="<?php echo esc_url( $footer_address_url ); ?>"><?php echo esc_html( $footer_address ); ?></a><?php else : ?><span><?php echo esc_html( $footer_address ); ?></span><?php endif; ?></p><?php endif; ?>
            <?php if ( $footer_phone ) : ?><p><i class="bi bi-telephone" aria-hidden="true"></i><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $footer_phone ) ); ?>"><?php echo esc_html( $footer_phone ); ?></a></p><?php endif; ?>
            <?php if ( $footer_email ) : ?><p><i class="bi bi-envelope" aria-hidden="true"></i><a href="<?php echo esc_url( 'mailto:' . $footer_email ); ?>"><?php echo esc_html( $footer_email ); ?></a></p><?php endif; ?>
            <?php if ( get_theme_mod( 'fruitables_footer_payment_show', false ) ) : ?><img src="<?php echo esc_url( get_theme_file_uri( 'img/payment.png' ) ); ?>" alt="<?php esc_attr_e( 'Accepted payment methods', 'fruitables' ); ?>" loading="lazy" width="180"><?php endif; ?>
        </div>
    </div></div>
    <div class="ft-copyright"><div class="ft-wrap"><span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'fruitables' ); ?></span><div class="ft-legal"><?php if ( function_exists( 'wc_terms_and_conditions_page_id' ) && wc_terms_and_conditions_page_id() > 0 ) : ?><a href="<?php echo esc_url( get_permalink( wc_terms_and_conditions_page_id() ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'fruitables' ); ?></a><?php endif; ?><?php if ( get_privacy_policy_url() ) : ?><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy Policy', 'fruitables' ); ?></a><?php endif; ?></div></div></div>
    <div class="ft-attribution"><div class="ft-wrap">Original template by <a href="https://htmlcodex.com">HTML Codex</a> · Distributed by <a href="https://themewagon.com">ThemeWagon</a></div></div>
</footer>
