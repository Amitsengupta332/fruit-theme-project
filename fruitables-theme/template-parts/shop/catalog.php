<?php
/** Fruitables Shop and category archives, based on the original shop.html layout. */
defined( 'ABSPATH' ) || exit;

$is_category = is_product_category();
$current     = $is_category ? get_queried_object() : null;
$shop_url    = wc_get_page_permalink( 'shop' );
$active_url  = $current instanceof WP_Term ? get_term_link( $current ) : $shop_url;
$active_url  = is_wp_error( $active_url ) ? $shop_url : $active_url;
$shop_title  = __( 'Shop', 'fruitables' );
$heading     = $current instanceof WP_Term ? $current->name : $shop_title;
$categories  = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'orderby' => 'name' ) );
$categories  = is_array( $categories ) ? $categories : array();

$state = array();
foreach ( array( 'fruitables_q', 'min_price', 'max_price', 'fruitables_filter', 'orderby' ) as $key ) {
    $value = fruitables_catalog_param( $key );
    if ( '' !== $value ) {
        $state[ $key ] = $value;
    }
}
$form_args = array();
$form_query = wp_parse_url( $active_url, PHP_URL_QUERY );
if ( is_string( $form_query ) ) {
    parse_str( $form_query, $form_args );
}
if ( isset( $state['orderby'] ) ) {
    $form_args['orderby'] = $state['orderby'];
}
$featured = array_slice( array_filter( wc_get_products( array(
    'status' => 'publish', 'featured' => true, 'limit' => -1, 'orderby' => 'title', 'order' => 'ASC',
) ), static function ( $item ) {
    return $item->is_visible() && in_array( $item->get_catalog_visibility(), array( 'visible', 'catalog' ), true );
} ), 0, 3 );

get_header();
?>
<main id="primary" class="fruitables-catalog">
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6"><?php echo esc_html( $heading ); ?></h1>
        <nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'fruitables' ); ?>">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'fruitables' ); ?></a></li>
                <?php if ( $is_category ) : ?>
                    <li class="breadcrumb-item"><a href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( $shop_title ); ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo esc_html( $heading ); ?></li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid fruite py-4 py-lg-5">
        <div class="container py-3 py-lg-5">
            <h2 class="h1 mb-4"><?php esc_html_e( 'Fresh fruits shop', 'fruitables' ); ?></h2>
            <?php if ( $current instanceof WP_Term && '' !== trim( $current->description ) ) : ?>
                <div class="mb-4"><?php echo wp_kses_post( wc_format_content( $current->description ) ); ?></div>
            <?php endif; ?>

            <div class="row g-4">
                <aside class="col-lg-3 fruitables-catalog-sidebar" aria-label="<?php esc_attr_e( 'Shop filters', 'fruitables' ); ?>">
                    <form method="get" action="<?php echo esc_url( $active_url ); ?>">
                        <?php foreach ( $form_args as $key => $value ) : ?>
                            <?php if ( is_scalar( $value ) ) : ?>
                                <input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="input-group mb-4">
                            <label class="visually-hidden" for="fruitables-catalog-search"><?php esc_html_e( 'Search products', 'fruitables' ); ?></label>
                            <input id="fruitables-catalog-search" type="search" name="fruitables_q" class="form-control py-3" placeholder="<?php esc_attr_e( 'Keywords', 'fruitables' ); ?>" value="<?php echo esc_attr( $state['fruitables_q'] ?? '' ); ?>">
                            <button class="btn btn-light border px-3" type="submit" aria-label="<?php esc_attr_e( 'Search products', 'fruitables' ); ?>"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>

                        <nav class="mb-4" aria-label="<?php esc_attr_e( 'Product categories', 'fruitables' ); ?>">
                            <h3 class="h4"><?php esc_html_e( 'Categories', 'fruitables' ); ?></h3>
                            <ul class="list-unstyled fruite-categorie mb-0">
                                <li><div class="fruite-name"><a href="<?php echo esc_url( add_query_arg( $state, $shop_url ) ); ?>"<?php if ( ! $is_category ) : ?> class="fw-bold" aria-current="page"<?php endif; ?>><?php esc_html_e( 'All Products', 'fruitables' ); ?></a></div></li>
                                <?php foreach ( $categories as $category ) : ?>
                                    <?php
                                    $url = get_term_link( $category );
                                    if ( is_wp_error( $url ) ) {
                                        continue;
                                    }
                                    $selected = $current instanceof WP_Term && $current->term_id === $category->term_id;
                                    ?>
                                    <li>
                                        <div class="d-flex justify-content-between gap-2 fruite-name">
                                            <a href="<?php echo esc_url( add_query_arg( $state, $url ) ); ?>"<?php if ( $selected ) : ?> class="fw-bold" aria-current="page"<?php endif; ?>><i class="fas fa-apple-alt me-2" aria-hidden="true"></i><?php echo esc_html( $category->name ); ?></a>
                                            <span>(<?php echo esc_html( number_format_i18n( $category->count ) ); ?>)</span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>

                        <fieldset class="mb-4">
                            <legend class="h4"><?php esc_html_e( 'Price', 'fruitables' ); ?> <small class="text-muted">(<?php echo esc_html( get_woocommerce_currency() ); ?>)</small></legend>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label for="fruitables-min-price" class="form-label"><?php esc_html_e( 'Min', 'fruitables' ); ?></label>
                                    <input id="fruitables-min-price" class="form-control" type="number" min="0" step="any" name="min_price" value="<?php echo esc_attr( $state['min_price'] ?? '' ); ?>">
                                </div>
                                <div class="col-6">
                                    <label for="fruitables-max-price" class="form-label"><?php esc_html_e( 'Max', 'fruitables' ); ?></label>
                                    <input id="fruitables-max-price" class="form-control" type="number" min="0" step="any" name="max_price" value="<?php echo esc_attr( $state['max_price'] ?? '' ); ?>">
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mb-3">
                            <legend class="h4"><?php esc_html_e( 'Additional', 'fruitables' ); ?></legend>
                            <label class="visually-hidden" for="fruitables-extra-filter"><?php esc_html_e( 'Product status', 'fruitables' ); ?></label>
                            <select id="fruitables-extra-filter" name="fruitables_filter" class="form-select">
                                <option value=""><?php esc_html_e( 'Any status', 'fruitables' ); ?></option>
                                <?php foreach ( fruitables_catalog_extra_filters() as $slug => $label ) : ?>
                                    <option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $state['fruitables_filter'] ?? '', $slug ); ?>><?php echo esc_html( $label ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </fieldset>
                        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
                            <button class="btn border border-secondary rounded-pill px-3 text-primary" type="submit"><?php esc_html_e( 'Apply filters', 'fruitables' ); ?></button>
                            <?php if ( $state ) : ?>
                                <a href="<?php echo esc_url( $active_url ); ?>"><?php esc_html_e( 'Reset', 'fruitables' ); ?></a>
                            <?php endif; ?>
                        </div>
                    </form>

                    <?php if ( $featured ) : ?>
                        <section class="mb-4">
                            <h3 class="h4 mb-3"><?php esc_html_e( 'Featured products', 'fruitables' ); ?></h3>
                            <?php foreach ( $featured as $item ) : ?>
                                <div class="d-flex gap-3 align-items-center mb-3">
                                    <a class="fruitables-featured-thumb" href="<?php echo esc_url( $item->get_permalink() ); ?>" tabindex="-1" aria-hidden="true"><?php echo $item->get_image( 'woocommerce_thumbnail' ); ?></a>
                                    <div>
                                        <a class="fw-bold" href="<?php echo esc_url( $item->get_permalink() ); ?>"><?php echo esc_html( $item->get_name() ); ?></a>
                                        <div class="text-dark"><?php echo wp_kses_post( $item->get_price_html() ); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <a class="btn border border-secondary rounded-pill text-primary w-100" href="<?php echo esc_url( add_query_arg( 'fruitables_filter', 'featured', $shop_url ) ); ?>"><?php esc_html_e( 'View featured products', 'fruitables' ); ?></a>
                        </section>
                    <?php endif; ?>

                    <?php $fruits = get_term_by( 'slug', 'fruits', 'product_cat' );
                    $banner_url = $fruits ? get_term_link( $fruits ) : $shop_url;
                    if ( is_wp_error( $banner_url ) ) {
                        $banner_url = $shop_url;
                    }
                    ?>
                    <a href="<?php echo esc_url( $banner_url ); ?>" class="fruitables-sidebar-banner d-block position-relative rounded overflow-hidden">
                        <img src="<?php echo esc_url( get_theme_file_uri( 'img/banner-fruits.jpg' ) ); ?>" class="img-fluid w-100" alt="" loading="lazy">
                        <span class="h3 mb-0 fw-bold"><?php esc_html_e( 'Fresh Fruits', 'fruitables' ); ?></span>
                    </a>
                </aside>

                <section class="col-lg-9 fruitables-catalog-results" aria-label="<?php esc_attr_e( 'Products', 'fruitables' ); ?>">
                    <?php if ( have_posts() ) : ?>
                        <div class="fruitables-catalog-toolbar">
                            <?php do_action( 'woocommerce_before_shop_loop' ); ?>
                        </div>
                        <?php
                        add_action( 'woocommerce_before_shop_loop_item_title', 'fruitables_catalog_badge', 12 );
                        add_action( 'woocommerce_after_shop_loop_item_title', 'fruitables_catalog_card_description', 7 );
                        woocommerce_product_loop_start();
                        while ( have_posts() ) {
                            the_post();
                            do_action( 'woocommerce_shop_loop' );
                            wc_get_template_part( 'content', 'product' );
                        }
                        woocommerce_product_loop_end();
                        remove_action( 'woocommerce_before_shop_loop_item_title', 'fruitables_catalog_badge', 12 );
                        remove_action( 'woocommerce_after_shop_loop_item_title', 'fruitables_catalog_card_description', 7 );
                        do_action( 'woocommerce_after_shop_loop' );
                        ?>
                    <?php else : ?>
                        <?php wc_print_notices(); ?>
                        <?php do_action( 'woocommerce_no_products_found' ); ?>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>
