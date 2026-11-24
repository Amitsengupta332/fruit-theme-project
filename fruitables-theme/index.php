<?php
/** Fallback template for posts and archives. */
get_header();
?>
<main id="primary" class="container py-5 mt-5">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class(); ?>>
                <?php if ( is_singular() ) : ?>
                    <?php the_title( '<h1>', '</h1>' ); ?>
                    <?php the_content(); ?>
                    <?php wp_link_pages(); ?>
                <?php else : ?>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php esc_html_e( 'Nothing found.', 'fruitables' ); ?></p>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
