<?php
/** Three promotional cards, with styling retained from the original theme. */
$fruitables_offer_defaults = fruitables_home_offers_defaults();
?>
<!-- Featurs Start -->
<div class="container-fluid service py-5">
    <div class="container py-5">
        <div class="row g-4 justify-content-center">
            <?php foreach ( $fruitables_offer_defaults as $number => $card ) : ?>
                <?php
                $prefix = 'fruitables_offer_card_' . $number . '_';
                if ( ! get_theme_mod( $prefix . 'show', true ) ) {
                    continue;
                }
                $title      = get_theme_mod( $prefix . 'title', $card['title'] );
                $offer      = get_theme_mod( $prefix . 'offer', $card['offer'] );
                $link       = get_theme_mod( $prefix . 'link', '' );
                $image_id   = absint( get_theme_mod( $prefix . 'image', 0 ) );
                $image      = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : false;
                $image_alt  = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
                $image_url  = $image ?: get_theme_file_uri( $card['image'] );
                ?>
                <div class="col-md-6 col-lg-4">
                    <?php if ( $link ) : ?><a href="<?php echo esc_url( $link ); ?>"><?php endif; ?>
                        <div class="service-item bg-<?php echo esc_attr( $card['card'] ); ?> rounded border border-<?php echo esc_attr( $card['card'] ); ?>">
                            <img src="<?php echo esc_url( $image_url ); ?>" class="img-fluid rounded-top w-100" alt="<?php echo esc_attr( $image_alt ?: $title ); ?>">
                            <div class="px-4 rounded-bottom">
                                <div class="service-content bg-<?php echo esc_attr( $card['content'] ); ?> text-center p-4 rounded">
                                    <h5 class="text-<?php echo esc_attr( $card['title_color'] ); ?>"><?php echo esc_html( $title ); ?></h5>
                                    <h3 class="mb-0"><?php echo esc_html( $offer ); ?></h3>
                                </div>
                            </div>
                        </div>
                    <?php if ( $link ) : ?></a><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Featurs End -->
