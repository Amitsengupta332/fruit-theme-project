<?php
/** Real WooCommerce products, limited to five per tab. */
if ( ! function_exists( 'wc_get_product' ) ) { return; }
$tabs = array( 'all' => __( 'All Products', 'fruitables' ), 'fruits' => __( 'Fruits', 'fruitables' ), 'vegetables' => __( 'Vegetables', 'fruitables' ), 'organic' => __( 'Organic', 'fruitables' ), 'bestsellers' => __( 'Bestsellers', 'fruitables' ) );
foreach ( array( 'bread', 'meat' ) as $slug ) { $term = get_term_by( 'slug', $slug, 'product_cat' ) ?: get_term_by( 'slug', $slug . 's', 'product_cat' ); if ( $term && ! is_wp_error( $term ) && $term->count ) { $tabs[$term->slug] = $term->name; } }
$visibility = wc_get_product_visibility_term_ids();
$excluded = array_filter( array( $visibility['exclude-from-catalog'] ?? 0, 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ? ( $visibility['outofstock'] ?? 0 ) : 0 ) );
$groups = array();
foreach ( $tabs as $key => $label ) {
    $query = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 5, 'no_found_rows' => true, 'ignore_sticky_posts' => true, 'orderby' => 'title', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND' ) );
    if ( $excluded ) { $query['tax_query'][] = array( 'taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => $excluded, 'operator' => 'NOT IN' ); }
    if ( 'organic' === $key ) { $query['tax_query'][] = array( 'taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => 'organic' ); }
    elseif ( 'bestsellers' === $key ) { $query['meta_key'] = 'total_sales'; $query['orderby'] = 'meta_value_num'; $query['order'] = 'DESC'; $query['meta_query'] = array( array( 'key' => 'total_sales', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC' ) ); }
    elseif ( 'all' !== $key ) { $query['tax_query'][] = array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $key, 'include_children' => true ); }
    $result = new WP_Query( $query );
    $groups[$key] = array();
    foreach ( $result->posts as $post_item ) { $p = wc_get_product( $post_item->ID ); if ( $p && $p->is_visible() ) { $groups[$key][] = $p; } }
}
?>
<section class="ft-wrap ft-section ft-products" id="ft-products" aria-labelledby="ft-products-title">
    <div class="ft-section-head ft-products-head"><div><p class="ft-eyebrow"><?php echo esc_html( fruitables_home_mod( 'products_eyebrow' ) ); ?></p><h2 id="ft-products-title"><?php echo esc_html( fruitables_home_mod( 'products_title' ) ); ?></h2></div>
        <div class="nav ft-product-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Product filters', 'fruitables' ); ?>">
            <?php foreach ( $tabs as $key => $label ) : ?><button id="ft-tab-<?php echo esc_attr( $key ); ?>" class="ft-tab<?php echo 'all' === $key ? ' active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#ft-panel-<?php echo esc_attr( $key ); ?>" type="button" role="tab" aria-controls="ft-panel-<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo 'all' === $key ? 'true' : 'false'; ?>"><?php echo esc_html( $label ); ?></button><?php endforeach; ?>
        </div>
    </div>
    <div class="tab-content">
    <?php foreach ( $groups as $key => $products ) : ?>
        <div id="ft-panel-<?php echo esc_attr( $key ); ?>" class="tab-pane<?php echo 'all' === $key ? ' show active' : ''; ?>" role="tabpanel" aria-labelledby="ft-tab-<?php echo esc_attr( $key ); ?>">
            <?php if ( ! $products ) : ?><p class="ft-empty"><?php esc_html_e( 'No products found in this collection.', 'fruitables' ); ?> <a href="<?php echo esc_url( fruitables_home_shop_url() ); ?>"><?php esc_html_e( 'Browse the shop', 'fruitables' ); ?></a></p><?php else : ?>
            <div class="ft-product-grid">
                <?php foreach ( $products as $p ) : $url = $p->get_permalink(); $weight = $p->get_weight(); $organic = has_term( 'organic', 'product_tag', $p->get_id() ); $ajax = 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' ) && $p->supports( 'ajax_add_to_cart' ) && $p->is_purchasable() && $p->is_in_stock(); ?>
                    <article class="ft-product">
                        <div class="ft-product-media">
                            <a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $p->get_name() ); ?>"><?php echo $p->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy', 'class' => 'ft-product-image' ) ); ?></a>
                            <?php if ( $organic ) : ?><span class="ft-product-badge"><i class="fas fa-leaf" aria-hidden="true"></i> <?php esc_html_e( 'Organic', 'fruitables' ); ?></span><?php elseif ( $p->is_on_sale() ) : ?><span class="ft-product-badge"><?php esc_html_e( 'Sale', 'fruitables' ); ?></span><?php endif; ?>
                        </div>
                        <div class="ft-product-info"><h3><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $p->get_name() ); ?></a></h3><p class="ft-product-unit"><?php echo $weight !== '' ? esc_html( wc_format_weight( $weight ) ) : '&nbsp;'; ?></p><div class="ft-price"><?php echo wp_kses_post( $p->get_price_html() ); ?></div>
                        <?php $classes = 'ft-add-cart button product_type_' . $p->get_type() . ( $ajax ? ' add_to_cart_button ajax_add_to_cart' : '' ); ?>
                        <a href="<?php echo esc_url( $p->add_to_cart_url() ); ?>" class="<?php echo esc_attr( $classes ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $p->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $p->get_sku() ); ?>" aria-label="<?php echo esc_attr( wp_strip_all_tags( $p->add_to_cart_description() ) ); ?>" rel="nofollow"><i class="bi bi-cart3" aria-hidden="true"></i> <?php echo esc_html( $p->add_to_cart_text() ); ?></a></div>
                    </article>
                <?php endforeach; ?>
            </div><?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
</section>
