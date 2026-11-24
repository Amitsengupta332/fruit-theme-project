<?php
/** Redesigned Fruitables homepage. */
get_header();
?>
<main id="primary" class="ft-home">
    <?php
    if ( function_exists( 'wc_print_notices' ) ) { echo '<div class="ft-wrap ft-notices">'; wc_print_notices(); echo '</div>'; }
    get_template_part( 'template-parts/home/hero' );
    get_template_part( 'template-parts/home/categories' );
    get_template_part( 'template-parts/home/organic-products' );
    get_template_part( 'template-parts/home/exotic-fruits-banner' );
    get_template_part( 'template-parts/home/reviews' );
    if ( fruitables_home_mod( 'legacy_sections' ) ) {
        get_template_part( 'template-parts/home/promo-cards' );
        get_template_part( 'template-parts/home/vegetable-carousel' );
        get_template_part( 'template-parts/home/bestsellers' );
        get_template_part( 'template-parts/home/stats' );
    }
    ?>
</main>
<?php get_footer(); ?>
