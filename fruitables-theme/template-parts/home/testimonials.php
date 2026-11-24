<?php
/** Both the homepage slider and Testimonial page use these published CPT entries. */
$testimonial_context = isset( $args['context'] ) && 'page' === $args['context'] ? 'page' : 'home';
$testimonials = new WP_Query( array(
    'post_type'           => 'ft_testimonial',
    'post_status'         => 'publish',
    'posts_per_page'      => -1,
    'orderby'             => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
) );
if ( ! $testimonials->have_posts() && 'home' === $testimonial_context ) {
    wp_reset_postdata();
    return; // Never show fake client reviews on the homepage.
}
?>
<!-- Testimonial Start -->
<section class="container-fluid testimonial py-5" aria-labelledby="fruitables-testimonial-title">
    <div class="container py-5">
        <div class="testimonial-header text-center">
            <h4 class="text-primary"><?php esc_html_e( 'Our Testimonial', 'fruitables' ); ?></h4>
            <h2 id="fruitables-testimonial-title" class="display-5 mb-5 text-dark"><?php esc_html_e( 'Our Client Saying!', 'fruitables' ); ?></h2>
        </div>
        <?php if ( $testimonials->have_posts() ) : ?>
            <div class="owl-carousel testimonial-carousel">
                <?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
                    <?php
                    $profession = get_post_meta( get_the_ID(), '_fruitables_profession', true );
                    $comment    = trim( (string) get_the_content() );
                    ?>
                    <article class="testimonial-item img-border-radius bg-light rounded p-4">
                        <div class="position-relative">
                            <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;" aria-hidden="true"></i>
                            <?php if ( '' !== $comment ) : ?>
                                <div class="mb-4 pb-4 border-bottom border-secondary fruitables-client-comment"><?php echo wpautop( wp_kses_post( $comment ) ); ?></div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center flex-nowrap">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="bg-secondary rounded">
                                        <?php the_post_thumbnail( 'thumbnail', array(
                                            'class'   => 'img-fluid rounded fruitables-client-photo',
                                            'style'   => 'width: 100px; height: 100px; object-fit: cover;',
                                            'loading' => 'lazy',
                                            'alt'     => get_the_title(),
                                        ) ); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="ms-4 d-block">
                                    <h3 class="h4 text-dark"><?php echo esc_html( get_the_title() ); ?></h3>
                                    <?php if ( '' !== $profession ) : ?>
                                        <p class="m-0 pb-3"><?php echo esc_html( $profession ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php elseif ( current_user_can( 'edit_posts' ) ) : ?>
            <p class="text-center"><?php esc_html_e( 'No testimonials published yet. Add one from Testimonials → Add New.', 'fruitables' ); ?></p>
        <?php endif; ?>
    </div>
</section>
<!-- Testimonial End -->
<?php wp_reset_postdata(); ?>
