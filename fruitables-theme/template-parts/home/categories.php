<?php $categories = fruitables_home_categories(); if ( ! taxonomy_exists( 'product_cat' ) ) { return; } ?>
<section class="ft-wrap ft-section ft-categories" id="ft-categories" aria-labelledby="ft-categories-title">
    <div class="ft-section-head"><div><p class="ft-eyebrow"><?php echo esc_html( fruitables_home_mod( 'category_eyebrow' ) ); ?></p><h2 id="ft-categories-title"><?php echo esc_html( fruitables_home_mod( 'category_title' ) ); ?></h2></div><a class="ft-text-link" href="<?php echo esc_url( fruitables_home_shop_url() ); ?>"><?php esc_html_e( 'View All Categories', 'fruitables' ); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
    <?php if ( $categories ) : ?><div class="ft-category-grid">
    <?php foreach ( $categories as $term ) : $url = get_term_link( $term ); if ( is_wp_error( $url ) ) { continue; } ?>
        <a class="ft-category" href="<?php echo esc_url( $url ); ?>"><div class="ft-category-image"><?php echo fruitables_home_category_image( $term ); ?></div><h3><?php echo esc_html( $term->name ); ?></h3><span><?php echo esc_html( sprintf( _n( '(%s Product)', '(%s Products)', $term->count, 'fruitables' ), number_format_i18n( $term->count ) ) ); ?></span></a>
    <?php endforeach; ?></div><?php else : ?><p class="ft-empty"><?php esc_html_e( 'No product categories available yet.', 'fruitables' ); ?></p><?php endif; ?>
</section>
