<?php
/** The template for displaying a genuine 404 (page not found) response. */
defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="primary">
    <!-- Single Page Header Start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6"><?php esc_html_e( '404 Error', 'fruitables' ); ?></h1>
        <nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'fruitables' ); ?>">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'fruitables' ); ?></a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">404</li>
            </ol>
        </nav>
    </div>
    <!-- Single Page Header End -->

    <!-- 404 Start -->
    <div class="container-fluid py-5">
        <div class="container py-5 text-center">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <i class="bi bi-exclamation-triangle display-1 text-secondary" aria-hidden="true"></i>
                    <div class="display-1 fw-bold" aria-hidden="true">404</div>
                    <h2 class="h1 mb-4"><?php esc_html_e( 'Page Not Found', 'fruitables' ); ?></h2>
                    <p class="mb-4"><?php esc_html_e( 'We’re sorry, the page you have looked for does not exist in our website! Maybe go to our home page or try to use a search?', 'fruitables' ); ?></p>
                    <a class="btn border-secondary rounded-pill py-3 px-5" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go Back To Home', 'fruitables' ); ?></a>
                </div>
            </div>
        </div>
    </div>
    <!-- 404 End -->
</main>
<?php get_footer(); ?>
