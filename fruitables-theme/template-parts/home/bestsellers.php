<?php
/** Homepage featured products section. */
if ( ! function_exists( 'wc_get_products' ) ) {
    return;
}

$fruitables_featured_products = wc_get_products( array(
    'status'   => 'publish',
    'featured' => true,
    'limit'    => -1,
    'orderby'  => 'name',
    'order'    => 'ASC',
) );

$fruitables_featured_products = array_slice( array_filter( $fruitables_featured_products, static function ( $product ) {
    return in_array( $product->get_catalog_visibility(), array( 'visible', 'catalog' ), true ) && $product->is_visible();
} ), 0, 10 );

if ( empty( $fruitables_featured_products ) ) {
    return;
}

$fruitables_render_details = static function ( $product, $centered ) {
    $rating = (float) $product->get_average_rating();
    $stars  = round( $rating * 2 ) / 2;
    ?>
    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="h5"><?php echo esc_html( $product->get_name() ); ?></a>
    <?php if ( $product->get_rating_count() > 0 ) : ?>
        <div class="d-flex my-3<?php echo $centered ? ' justify-content-center' : ''; ?>" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'fruitables' ), number_format_i18n( $rating, 1 ) ) ); ?>">
            <?php for ( $star = 1; $star <= 5; $star++ ) : ?>
                <?php $icon = $stars >= $star ? 'fas fa-star text-primary' : ( $stars >= $star - 0.5 ? 'fas fa-star-half-alt text-primary' : 'fas fa-star' ); ?>
                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
    <h4 class="mt-2 mb-3"><?php echo wp_kses_post( $product->get_price_html() ); ?></h4>
    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn border border-secondary rounded-pill px-3 text-primary">
        <i class="fa fa-shopping-bag me-2 text-primary" aria-hidden="true"></i><?php echo esc_html( $product->add_to_cart_text() ); ?>
    </a>
    <?php
};
?>
<!-- Bestsaler Product Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
            <h1 class="display-4"><?php esc_html_e( 'Featured Products', 'fruitables' ); ?></h1>
            <p><?php esc_html_e( 'Explore our featured fruits and vegetables.', 'fruitables' ); ?></p>
        </div>
        <div class="row g-4">
            <?php foreach ( $fruitables_featured_products as $index => $product ) : ?>
                <?php if ( $index < 6 ) : ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light h-100">
                            <div class="row align-items-center h-100">
                                <div class="col-6">
                                    <?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'img-fluid rounded-circle w-100', 'style' => 'aspect-ratio: 1 / 1; object-fit: cover;' ) ); ?>
                                </div>
                                <div class="col-6">
                                    <?php $fruitables_render_details( $product, false ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'img-fluid rounded w-100', 'style' => 'aspect-ratio: 4 / 3; object-fit: cover;' ) ); ?>
                            <div class="py-2">
                                <?php $fruitables_render_details( $product, true ); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Bestsaler Product End -->
