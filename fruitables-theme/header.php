<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( ! is_front_page() ) : ?>
<!-- Spinner Start -->
<div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
<!-- Spinner End -->
<?php endif; ?>

<?php
$topbar_visible = (bool) get_theme_mod( 'fruitables_topbar_show', true );
$address        = trim( fruitables_shared_contact( 'address' ) );
$address_url    = fruitables_shared_contact( 'address_url' );
$email          = sanitize_email( fruitables_shared_contact( 'email' ) );
$show_search    = (bool) get_theme_mod( 'fruitables_navbar_search_show', true );
$show_cart      = (bool) get_theme_mod( 'fruitables_navbar_cart_show', true );
$show_account   = (bool) get_theme_mod( 'fruitables_navbar_account_show', true );
$has_wc         = class_exists( 'WooCommerce' );
$account_url    = $has_wc && function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'myaccount' ) > 0
    ? wc_get_page_permalink( 'myaccount' )
    : wp_login_url();

$shop_available = $has_wc && function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0;
$search_action  = $shop_available ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$search_name    = $shop_available ? 'fruitables_q' : 's';
$search_args    = array();
$action_query   = wp_parse_url( $search_action, PHP_URL_QUERY );
if ( is_string( $action_query ) ) {
    parse_str( $action_query, $search_args );
}
?>
<?php if ( is_front_page() ) { require get_theme_file_path( 'template-parts/home/header.php' ); return; } ?>
<!-- Navbar start -->
<div class="container-fluid fixed-top">
    <?php if ( $topbar_visible ) : ?>
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <?php if ( '' !== $address ) : ?>
                        <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary" aria-hidden="true"></i>
                            <?php if ( $address_url ) : ?>
                                <a href="<?php echo esc_url( $address_url ); ?>" class="text-white"><?php echo esc_html( $address ); ?></a>
                            <?php else : ?>
                                <span class="text-white"><?php echo esc_html( $address ); ?></span>
                            <?php endif; ?>
                        </small>
                    <?php endif; ?>
                    <?php if ( $email ) : ?>
                        <small class="me-3"><i class="fas fa-envelope me-2 text-secondary" aria-hidden="true"></i><a href="<?php echo esc_url( 'mailto:' . $email ); ?>" class="text-white"><?php echo esc_html( $email ); ?></a></small>
                    <?php endif; ?>
                </div>
                <?php if ( has_nav_menu( 'topbar' ) ) : ?>
                    <?php
                    wp_nav_menu( array(
                        'theme_location'       => 'topbar',
                        'container'            => 'nav',
                        'container_class'      => 'top-link pe-2',
                        'container_aria_label' => __( 'Top bar links', 'fruitables' ),
                        'menu_class'           => 'fruitables-top-links',
                        'depth'                => 1,
                        'link_before'          => '<small class="text-white mx-2">',
                        'link_after'           => '</small>',
                        'fallback_cb'          => false,
                    ) );
                    ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl" aria-label="<?php esc_attr_e( 'Primary navigation', 'fruitables' ); ?>">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand"><h1 class="text-primary display-6"><?php echo esc_html( get_bloginfo( 'name' ) ?: 'Fruitables' ); ?></h1></a>
            <?php endif; ?>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'fruitables' ); ?>">
                <span class="fa fa-bars text-primary" aria-hidden="true"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '<div id="%1$s" class="navbar-nav mx-auto">%3$s</div>',
                    'depth'          => 2,
                    'walker'         => new Fruitables_Primary_Menu_Walker(),
                    'fallback_cb'    => 'fruitables_primary_menu_fallback',
                ) );
                ?>
                <div class="d-flex m-3 me-0">
                    <?php if ( $show_search ) : ?>
                        <button type="button" class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="<?php esc_attr_e( 'Search products', 'fruitables' ); ?>"><i class="fas fa-search text-primary" aria-hidden="true"></i></button>
                    <?php endif; ?>
                    <?php if ( $show_cart && $has_wc && function_exists( 'wc_get_cart_url' ) ) : ?>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="position-relative me-4 my-auto" aria-label="<?php esc_attr_e( 'Shopping cart', 'fruitables' ); ?>">
                            <i class="fa fa-shopping-bag fa-2x" aria-hidden="true"></i>
                            <?php echo fruitables_cart_count_html(); // Escaped markup generated in helper. ?>
                        </a>
                    <?php endif; ?>
                    <?php if ( $show_account ) : ?>
                        <a href="<?php echo esc_url( $account_url ); ?>" class="my-auto" aria-label="<?php esc_attr_e( 'My account', 'fruitables' ); ?>">
                            <i class="fas fa-user fa-2x" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->

<?php if ( $show_search ) : ?>
    <!-- WooCommerce Product Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="fruitablesSearchTitle" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="fruitablesSearchTitle"><?php esc_html_e( 'Search by keyword', 'fruitables' ); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'fruitables' ); ?>"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <form class="input-group w-75 mx-auto d-flex" role="search" action="<?php echo esc_url( $search_action ); ?>" method="get">
                        <?php foreach ( $search_args as $arg => $value ) : ?>
                            <?php if ( is_scalar( $value ) ) : ?>
                                <input type="hidden" name="<?php echo esc_attr( $arg ); ?>" value="<?php echo esc_attr( $value ); ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ( $has_wc && ! $shop_available ) : ?>
                            <input type="hidden" name="post_type" value="product">
                        <?php endif; ?>
                        <label class="visually-hidden" for="fruitables-navbar-search"><?php esc_html_e( 'Search products', 'fruitables' ); ?></label>
                        <input id="fruitables-navbar-search" name="<?php echo esc_attr( $search_name ); ?>" type="search" class="form-control p-3" placeholder="<?php esc_attr_e( 'Search products', 'fruitables' ); ?>" required>
                        <button type="submit" class="input-group-text p-3" aria-label="<?php esc_attr_e( 'Submit product search', 'fruitables' ); ?>"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Search Modal End -->
<?php endif; ?>
