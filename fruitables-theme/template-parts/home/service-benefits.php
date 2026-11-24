<?php
/** Homepage Free Shipping / Payment / Return / Support benefit cards. */
$visible_benefits = array();
if ( get_theme_mod( 'fruitables_benefits_show', true ) ) {
    foreach ( fruitables_service_benefits_defaults() as $card_key => $defaults ) {
        $prefix = 'fruitables_benefit_' . $card_key . '_';
        if ( ! get_theme_mod( $prefix . 'show', true ) ) {
            continue;
        }

        $icon = get_theme_mod( $prefix . 'icon', $defaults['icon'] );
        $allowed_icons = fruitables_service_benefits_icon_choices();
        if ( ! is_string( $icon ) || ! isset( $allowed_icons[ $icon ] ) ) {
            $icon = $defaults['icon'];
        }
        $visible_benefits[ $card_key ] = array(
            'title'       => get_theme_mod( $prefix . 'title', $defaults['title'] ),
            'description' => get_theme_mod( $prefix . 'description', $defaults['description'] ),
            'icon'        => $icon,
        );
    }
}

if ( ! $visible_benefits ) {
    return;
}

// Four cards retain the original grid; fewer cards fill the available row.
$card_count = count( $visible_benefits );
$column_class = 'col-md-6 col-lg-' . ( 4 === $card_count ? '3' : ( 3 === $card_count ? '4' : ( 2 === $card_count ? '6' : '12' ) ) );
?>
<!-- Featurs Section Start -->
<div class="container-fluid featurs py-5">
    <div class="container py-5">
        <div class="row g-4">
            <?php foreach ( $visible_benefits as $card_key => $card ) : ?>
                <div class="<?php echo esc_attr( $column_class ); ?>">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="<?php echo esc_attr( 'support' === $card_key ? 'fa' : 'fas' ); ?> <?php echo esc_attr( $card['icon'] ); ?> fa-3x text-white" aria-hidden="true"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5><?php echo esc_html( $card['title'] ); ?></h5>
                            <p class="mb-0"><?php echo esc_html( $card['description'] ); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Featurs Section End -->
