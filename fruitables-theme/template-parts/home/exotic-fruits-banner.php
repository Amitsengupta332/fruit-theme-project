<?php
/** Editable homepage banner with a price only when backed by a real WooCommerce product. */
$fruitables_banner_defaults = fruitables_exotic_banner_defaults();
$fruitables_banner_heading = get_theme_mod( 'fruitables_exotic_banner_heading', $fruitables_banner_defaults['heading'] );
$fruitables_banner_subheading = get_theme_mod( 'fruitables_exotic_banner_subheading', $fruitables_banner_defaults['subheading'] );
$fruitables_banner_description = get_theme_mod( 'fruitables_exotic_banner_description', $fruitables_banner_defaults['description'] );
$fruitables_banner_button = get_theme_mod( 'fruitables_exotic_banner_button', $fruitables_banner_defaults['button'] );
$fruitables_banner_link = get_theme_mod( 'fruitables_exotic_banner_button_link', '' );
$fruitables_banner_image_id = absint( get_theme_mod( 'fruitables_exotic_banner_image', 0 ) );
$fruitables_banner_image = $fruitables_banner_image_id ? wp_get_attachment_image_url( $fruitables_banner_image_id, 'large' ) : false;
$fruitables_banner_image_alt = $fruitables_banner_image_id ? get_post_meta( $fruitables_banner_image_id, '_wp_attachment_image_alt', true ) : '';
$fruitables_banner_image = $fruitables_banner_image ?: get_theme_file_uri( 'img/baner-1.png' );

$fruitables_banner_product_id = absint( get_theme_mod( 'fruitables_exotic_banner_product_id', 0 ) );
$fruitables_banner_product = $fruitables_banner_product_id && function_exists( 'wc_get_product' ) ? wc_get_product( $fruitables_banner_product_id ) : false;
// Avoid displaying a draft, hidden, or otherwise unavailable product in a public promotion.
if ( $fruitables_banner_product && ( 'publish' !== $fruitables_banner_product->get_status() || ! $fruitables_banner_product->is_visible() ) ) {
    $fruitables_banner_product = false;
}
$fruitables_banner_price = $fruitables_banner_product ? $fruitables_banner_product->get_price_html() : '';

// A manual link takes precedence. Otherwise, the BUY button follows the selected
// product; if there is no product, it points to the real WooCommerce Shop page.
if ( ! $fruitables_banner_link && $fruitables_banner_product ) {
    $fruitables_banner_link = $fruitables_banner_product->get_permalink();
}
if ( ! $fruitables_banner_link && function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) {
    $fruitables_banner_link = wc_get_page_permalink( 'shop' );
}
?>
<section class="ft-wrap ft-section" id="ft-delivery" aria-labelledby="ft-delivery-title">
    <div class="ft-delivery">
        <div class="ft-delivery-image"><img src="<?php echo esc_url( $fruitables_banner_image ); ?>" alt="<?php echo esc_attr( $fruitables_banner_image_alt ?: $fruitables_banner_heading ); ?>" width="600" height="350" loading="lazy"></div>
        <div class="ft-delivery-copy">
            <p class="ft-eyebrow"><?php echo esc_html( $fruitables_banner_subheading ); ?></p><h2 id="ft-delivery-title"><?php echo esc_html( $fruitables_banner_heading ); ?></h2>
            <p class="ft-delivery-description"><?php echo esc_html( $fruitables_banner_description ); ?></p>
            <div class="ft-delivery-benefits">
            <?php if ( get_theme_mod( 'fruitables_benefits_show', true ) ) : foreach ( fruitables_service_benefits_defaults() as $key => $default ) : if ( ! get_theme_mod( 'fruitables_benefit_' . $key . '_show', true ) ) { continue; } $icon = get_theme_mod( 'fruitables_benefit_' . $key . '_icon', $default['icon'] ); if ( ! array_key_exists( $icon, fruitables_service_benefits_icon_choices() ) ) { $icon = $default['icon']; } ?>
                <div><i class="fas <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i><span><strong><?php echo esc_html( get_theme_mod( 'fruitables_benefit_' . $key . '_title', $default['title'] ) ); ?></strong><small><?php echo esc_html( get_theme_mod( 'fruitables_benefit_' . $key . '_description', $default['description'] ) ); ?></small></span></div>
            <?php endforeach; endif; ?>
            </div>
            <?php if ( $fruitables_banner_link ) : ?><a class="ft-text-link" href="<?php echo esc_url( $fruitables_banner_link ); ?>"><?php echo esc_html( $fruitables_banner_button ); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a><?php endif; ?>
            <?php if ( $fruitables_banner_price ) : ?><span class="ft-price"><?php echo wp_kses_post( $fruitables_banner_price ); ?></span><?php endif; ?>
        </div>
    </div>
</section>
