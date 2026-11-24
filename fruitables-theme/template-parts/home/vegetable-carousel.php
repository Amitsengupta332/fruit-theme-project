<?php
$fruitables_vegetables = function_exists( 'wc_get_products' )
    ? wc_get_products( array(
        'status'   => 'publish',
        'category' => array( 'vegetables' ),
        'limit'    => -1,
        'orderby'  => 'title',
        'order'    => 'ASC',
    ) )
    : array();

$fruitables_vegetables = array_filter( $fruitables_vegetables, static function ( $vegetable ) {
    return in_array( $vegetable->get_catalog_visibility(), array( 'visible', 'catalog' ), true );
} );
?>
<!-- Vesitable Shop Start-->
<div class="container-fluid vesitable py-5">
    <div class="container py-5">
        <h1 class="mb-0">Fresh Organic Vegetables</h1>
        <?php if ( ! empty( $fruitables_vegetables ) ) : ?>
            <div class="owl-carousel vegetable-carousel justify-content-center">
                <?php foreach ( $fruitables_vegetables as $vegetable ) : ?>
                    <div class="border border-primary rounded position-relative vesitable-item">
                        <div class="vesitable-img">
                            <?php echo $vegetable->get_image( 'woocommerce_thumbnail', array(
                                'class' => 'img-fluid w-100 rounded-top d-block',
                                'style' => 'aspect-ratio: 4 / 3; height: auto; object-fit: cover;',
                            ) ); ?>
                        </div>
                        <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;"><?php esc_html_e( 'Vegetable', 'fruitables' ); ?></div>
                        <div class="p-4 rounded-bottom">
                            <h4 class="text-break"><?php echo esc_html( $vegetable->get_name() ); ?></h4>
                            <?php $description = trim( wp_strip_all_tags( $vegetable->get_short_description() ) ); ?>
                            <?php if ( '' !== $description ) : ?>
                                <p><?php echo esc_html( $description ); ?></p>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between flex-wrap">
                                <p class="text-dark fs-5 fw-bold mb-0"><?php echo wp_kses_post( $vegetable->get_price_html() ); ?></p>
                                <a href="<?php echo esc_url( $vegetable->add_to_cart_url() ); ?>" class="btn border border-secondary rounded-pill px-3 text-primary">
                                    <i class="fa fa-shopping-bag me-2 text-primary" aria-hidden="true"></i>
                                    <?php echo esc_html( $vegetable->add_to_cart_text() ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="mt-3 mb-0"><?php esc_html_e( 'No vegetables found.', 'fruitables' ); ?></p>
        <?php endif; ?>
    </div>
</div>
<!-- Vesitable Shop End -->
