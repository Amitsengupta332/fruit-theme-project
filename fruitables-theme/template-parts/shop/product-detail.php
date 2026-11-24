<?php
/** Single product template for the Fruitables theme. */
defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product instanceof WC_Product ) {
    $product = wc_get_product( get_queried_object_id() );
}

if ( ! $product ) {
    get_header( 'shop' );
    echo '<main class="container py-5"><p>' . esc_html__( 'Product not found.', 'fruitables' ) . '</p></main>';
    get_footer( 'shop' );
    return;
}

$shop_url   = wc_get_page_permalink( 'shop' );
$categories = get_terms(
    array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'orderby'    => 'name',
    )
);
$featured   = array_slice(
    array_filter(
        wc_get_products(
            array(
                'status'   => 'publish',
                'featured' => true,
                'limit'    => 12,
                'exclude'  => array( $product->get_id() ),
                'orderby'  => 'name',
                'order'    => 'ASC',
            )
        ),
        static function ( $item ) {
            return $item->is_visible() && in_array( $item->get_catalog_visibility(), array( 'visible', 'catalog' ), true );
        }
    ),
    0,
    4
);

$image_id    = $product->get_image_id();
$gallery_ids = array_slice( $product->get_gallery_image_ids(), 0, 3 );
$thumb_ids   = array_values( array_unique( array_filter( array_merge( array( $image_id ), $gallery_ids ) ) ) );
if ( ! $thumb_ids ) {
    $thumb_ids = array( 0 );
}

$summary_text = $product->get_short_description();
if ( '' === trim( wp_strip_all_tags( $summary_text ) ) ) {
    $summary_text = wp_trim_words( wp_strip_all_tags( $product->get_description() ), 32 );
}
$category_list = wc_get_product_category_list( $product->get_id(), ', ' );

get_header( 'shop' );
?>
<main id="primary" class="fruitables-product-detail">
    <div class="container-fluid page-header py-5">
        <p class="text-center text-white display-6 fw-bold mb-2"><?php esc_html_e( 'Shop Detail', 'fruitables' ); ?></p>
        <nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'fruitables' ); ?>">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'fruitables' ); ?></a></li>
                <li class="breadcrumb-item"><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop', 'fruitables' ); ?></a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo esc_html( $product->get_name() ); ?></li>
            </ol>
        </nav>
    </div>

    <div class="container py-4 py-lg-5">
        <?php do_action( 'woocommerce_before_single_product' ); ?>

        <?php if ( post_password_required() ) : ?>
            <?php echo get_the_password_form(); ?>
            <?php return; ?>
        <?php endif; ?>

        <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'fruitables-single-product', $product ); ?>>
            <div class="row g-4 g-xl-5 align-items-start">
                <div class="col-xl-8">
                    <section class="fruitables-product-top row g-4 align-items-start">
                        <div class="col-lg-6">
                            <div class="fruitables-gallery-card">
                                <div class="fruitables-gallery-main">
                                    <?php if ( $image_id ) : ?>
                                        <?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'img-fluid w-100', 'id' => 'fruitables-main-product-image' ) ); ?>
                                    <?php else : ?>
                                        <?php echo wc_placeholder_img( 'large', array( 'class' => 'img-fluid w-100', 'id' => 'fruitables-main-product-image' ) ); ?>
                                    <?php endif; ?>
                                    <span class="fruitables-zoom-icon" aria-hidden="true"><i class="fas fa-search"></i></span>
                                </div>

                                <?php if ( $thumb_ids ) : ?>
                                    <div class="fruitables-thumb-row" aria-label="<?php esc_attr_e( 'Product images', 'fruitables' ); ?>">
                                        <?php foreach ( $thumb_ids as $index => $thumb_id ) : ?>
                                            <?php
                                            $thumb_html = $thumb_id ? wp_get_attachment_image( $thumb_id, 'woocommerce_thumbnail', false, array( 'class' => 'img-fluid w-100' ) ) : wc_placeholder_img( 'woocommerce_thumbnail', array( 'class' => 'img-fluid w-100' ) );
                                            $large_src  = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : wc_placeholder_img_src( 'large' );
                                            ?>
                                            <button type="button" class="fruitables-thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" data-large-src="<?php echo esc_url( $large_src ); ?>">
                                                <?php echo $thumb_html; ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="fruitables-summary-card">
                                <h1 class="product_title"><?php echo esc_html( $product->get_name() ); ?></h1>

                                <?php if ( wc_review_ratings_enabled() ) : ?>
                                    <div class="fruitables-rating-row">
                                        <?php echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); ?>
                                        <?php if ( wc_review_ratings_enabled() && $product->get_review_count() > 0 ) : ?>
                                            <a href="#reviews" class="fruitables-review-link"><?php echo esc_html( sprintf( _n( '(%d Review)', '(%d Reviews)', $product->get_review_count(), 'fruitables' ), $product->get_review_count() ) ); ?></a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="fruitables-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

                                <?php if ( '' !== trim( wp_strip_all_tags( $summary_text ) ) ) : ?>
                                    <div class="fruitables-summary-text"><?php echo wp_kses_post( wpautop( $summary_text ) ); ?></div>
                                <?php endif; ?>

                                <?php if ( $category_list ) : ?>
                                    <p class="fruitables-category-line"><?php esc_html_e( 'Category:', 'fruitables' ); ?> <span><?php echo wp_kses_post( $category_list ); ?></span></p>
                                <?php endif; ?>

                                <div class="fruitables-cart-area">
                                    <?php woocommerce_template_single_add_to_cart(); ?>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="fruitables-detail-block fruitables-tabs-block">
                        <?php woocommerce_output_product_data_tabs(); ?>
                    </div>

                    <div class="fruitables-detail-block fruitables-related-block">
                        <?php woocommerce_output_related_products(); ?>
                    </div>
                </div>

                <aside class="col-xl-4 fruitables-product-sidebar" aria-label="<?php esc_attr_e( 'Extra product information', 'fruitables' ); ?>">
                    <div class="fruitables-benefits-card">
                        <div class="fruitables-benefit-item">
                            <span class="fruitables-benefit-icon"><i class="fas fa-leaf" aria-hidden="true"></i></span>
                            <div>
                                <h3><?php esc_html_e( '100% Organic', 'fruitables' ); ?></h3>
                                <p><?php esc_html_e( 'Naturally grown & chemical-free', 'fruitables' ); ?></p>
                            </div>
                        </div>
                        <div class="fruitables-benefit-item">
                            <span class="fruitables-benefit-icon"><i class="fas fa-truck" aria-hidden="true"></i></span>
                            <div>
                                <h3><?php esc_html_e( 'Fast Delivery', 'fruitables' ); ?></h3>
                                <p><?php esc_html_e( 'Get it at your doorstep quickly', 'fruitables' ); ?></p>
                            </div>
                        </div>
                        <div class="fruitables-benefit-item">
                            <span class="fruitables-benefit-icon"><i class="fas fa-shield-alt" aria-hidden="true"></i></span>
                            <div>
                                <h3><?php esc_html_e( 'Quality Guaranteed', 'fruitables' ); ?></h3>
                                <p><?php esc_html_e( 'Freshness & premium quality', 'fruitables' ); ?></p>
                            </div>
                        </div>
                        <div class="fruitables-benefit-item">
                            <span class="fruitables-benefit-icon"><i class="fas fa-sync-alt" aria-hidden="true"></i></span>
                            <div>
                                <h3><?php esc_html_e( 'Easy Returns', 'fruitables' ); ?></h3>
                                <p><?php esc_html_e( 'Hassle-free return policy', 'fruitables' ); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if ( is_array( $categories ) && $categories ) : ?>
                        <div class="fruitables-side-card">
                            <h2 class="h4 mb-3"><?php esc_html_e( 'Categories', 'fruitables' ); ?></h2>
                            <ul class="list-unstyled mb-0 fruitables-side-links">
                                <?php foreach ( $categories as $category ) : ?>
                                    <?php $url = get_term_link( $category ); ?>
                                    <?php if ( ! is_wp_error( $url ) ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $url ); ?>"><i class="fas fa-apple-alt me-2" aria-hidden="true"></i><?php echo esc_html( $category->name ); ?></a>
                                            <span><?php echo esc_html( number_format_i18n( $category->count ) ); ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ( $featured ) : ?>
                        <div class="fruitables-side-card">
                            <h2 class="h4 mb-3"><?php esc_html_e( 'Featured Products', 'fruitables' ); ?></h2>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ( $featured as $item ) : ?>
                                    <li class="fruitables-featured-item">
                                        <a class="fruitables-featured-thumb" href="<?php echo esc_url( $item->get_permalink() ); ?>" aria-label="<?php echo esc_attr( $item->get_name() ); ?>"><?php echo $item->get_image( 'woocommerce_thumbnail' ); ?></a>
                                        <div>
                                            <a class="fruitables-featured-title" href="<?php echo esc_url( $item->get_permalink() ); ?>"><?php echo esc_html( $item->get_name() ); ?></a>
                                            <div class="fruitables-featured-price"><?php echo wp_kses_post( $item->get_price_html() ); ?></div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <a class="fruitables-detail-promo d-block position-relative rounded overflow-hidden" href="<?php echo esc_url( $shop_url ); ?>">
                        <img class="img-fluid w-100" src="<?php echo esc_url( get_theme_file_uri( 'img/banner-fruits.jpg' ) ); ?>" alt="">
                        <span><?php esc_html_e( 'Explore the Shop', 'fruitables' ); ?></span>
                    </a>
                </aside>
            </div>
        </div>

        <?php do_action( 'woocommerce_after_single_product' ); ?>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('fruitables-main-product-image');
    const thumbs = document.querySelectorAll('.fruitables-thumb[data-large-src]');
    if (!mainImage || !thumbs.length) return;
    thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            thumbs.forEach(function (item) { item.classList.remove('is-active'); });
            thumb.classList.add('is-active');
            mainImage.setAttribute('src', thumb.getAttribute('data-large-src'));
        });
    });
});
</script>
<?php get_footer( 'shop' ); ?>
