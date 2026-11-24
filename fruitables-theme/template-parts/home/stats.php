<?php
/** Homepage stats: no fabricated business figures; product count is WooCommerce-owned. */
if ( ! get_theme_mod( 'fruitables_stats_show', true ) ) {
    return;
}

$cards = array();
$business_metrics = array(
    'customers'    => __( 'satisfied customers', 'fruitables' ),
    'quality'      => __( 'quality of service', 'fruitables' ),
    'certificates' => __( 'quality certificates', 'fruitables' ),
);
foreach ( $business_metrics as $key => $default_title ) {
    if ( ! get_theme_mod( 'fruitables_stats_' . $key . '_show', true ) ) {
        continue;
    }
    $raw = get_theme_mod( 'fruitables_stats_' . $key . '_value', '' );
    $verified = 'quality' === $key
        ? fruitables_stats_sanitize_percentage( $raw )
        : fruitables_stats_sanitize_nonnegative_count( $raw );
    // Empty means unknown; the card must not suggest any verified statistic.
    if ( '' === $verified ) {
        continue;
    }
    $cards[] = array(
        'label' => get_theme_mod( 'fruitables_stats_' . $key . '_title', $default_title ),
        'value' => $verified . ( 'quality' === $key ? '%' : '' ),
    );
}

if ( get_theme_mod( 'fruitables_stats_products_show', true ) ) {
    $available = fruitables_stats_available_products_count();
    if ( null !== $available ) {
        $cards[] = array(
            'label' => get_theme_mod( 'fruitables_stats_products_title', __( 'Available Products', 'fruitables' ) ),
            'value' => (string) $available,
        );
    }
}

if ( empty( $cards ) ) {
    return;
}
?>
<!-- Fact Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="bg-light p-5 rounded">
            <div class="row g-4 justify-content-center">
                <?php foreach ( $cards as $card ) : ?>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter bg-white rounded p-5">
                            <i class="fa fa-users text-secondary" aria-hidden="true"></i>
                            <h4><?php echo esc_html( $card['label'] ); ?></h4>
                            <h1><?php echo esc_html( $card['value'] ); ?></h1>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- Fact End -->
