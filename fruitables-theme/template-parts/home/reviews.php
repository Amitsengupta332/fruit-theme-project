<?php
/** Homepage reviews: only actual published CPT content. */
$reviews = new WP_Query( array( 'post_type' => 'ft_testimonial', 'post_status' => 'publish', 'posts_per_page' => 12, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ), 'no_found_rows' => true ) );
if ( ! $reviews->have_posts() ) { return; }
?>
<section class="ft-wrap ft-section ft-reviews" aria-labelledby="ft-reviews-title">
    <div class="ft-review-intro"><p class="ft-eyebrow"><?php echo esc_html( fruitables_home_mod( 'testimonial_eyebrow' ) ); ?></p><h2 id="ft-reviews-title"><?php echo esc_html( fruitables_home_mod( 'testimonial_title' ) ); ?></h2><p><?php echo esc_html( fruitables_home_mod( 'testimonial_description' ) ); ?></p>
    <?php if ( $reviews->post_count > 1 ) : ?><div class="ft-review-controls"><button type="button" data-ft-review="prev" aria-label="<?php esc_attr_e( 'Previous testimonials', 'fruitables' ); ?>"><i class="bi bi-chevron-left" aria-hidden="true"></i></button><button type="button" data-ft-review="next" aria-label="<?php esc_attr_e( 'Next testimonials', 'fruitables' ); ?>"><i class="bi bi-chevron-right" aria-hidden="true"></i></button></div><?php endif; ?></div>
    <div class="ft-review-track" tabindex="0" aria-label="<?php esc_attr_e( 'Customer testimonials', 'fruitables' ); ?>">
    <?php while ( $reviews->have_posts() ) : $reviews->the_post(); $profession = get_post_meta( get_the_ID(), '_fruitables_profession', true ); $rating = absint( get_post_meta( get_the_ID(), '_fruitables_rating', true ) ); ?>
        <article class="ft-review">
            <div class="ft-review-photo"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy', 'alt' => '' ) ); else : ?><i class="bi bi-person-circle" aria-hidden="true"></i><?php endif; ?></div>
            <div class="ft-review-body"><?php if ( $rating >= 1 && $rating <= 5 ) : ?><div class="ft-stars" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'fruitables' ), $rating ) ); ?>"><?php for ( $i = 1; $i <= 5; $i++ ) : ?><i class="<?php echo $i <= $rating ? 'fas' : 'far'; ?> fa-star" aria-hidden="true"></i><?php endfor; ?></div><?php endif; ?>
                <div class="ft-review-quote"><?php echo wpautop( wp_kses_post( get_the_content() ) ); ?></div><h3><?php echo esc_html( get_the_title() ); ?></h3><?php if ( $profession ) : ?><small><?php echo esc_html( $profession ); ?></small><?php endif; ?>
            </div>
        </article>
    <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
