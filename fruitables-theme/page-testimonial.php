<?php
/**
 * Template Name: Testimonials
 * Description: Use this for the Testimonial page; also auto-loads for slug /testimonial/.
 */
get_header();
?>
<main id="primary">
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6"><?php echo esc_html( get_the_title( get_queried_object_id() ) ); ?></h1>
        <nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'fruitables' ); ?>">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'fruitables' ); ?></a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo esc_html( get_the_title( get_queried_object_id() ) ); ?></li>
            </ol>
        </nav>
    </div>
    <?php get_template_part( 'template-parts/home/testimonials', null, array( 'context' => 'page' ) ); ?>
</main>
<?php get_footer(); ?>
