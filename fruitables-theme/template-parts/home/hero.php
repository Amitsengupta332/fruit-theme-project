<?php
/** New hero using the existing theme-mod keys. */
$hero = array();
foreach ( fruitables_hero_text_defaults() as $field => $default ) { $hero[$field] = get_theme_mod( 'fruitables_hero_' . $field, $default ); }
$background_id = absint( get_theme_mod( 'fruitables_hero_background_image', 0 ) );
$background = $background_id ? wp_get_attachment_image_url( $background_id, 'full' ) : false;
$style = $background ? 'background-image:linear-gradient(90deg,rgba(248,249,245,.96),rgba(248,249,245,.55)),url("' . str_replace( array( '"', "'", '(', ')', '\\' ), array( '%22', '%27', '%28', '%29', '%5C' ), esc_url_raw( $background ) ) . '")' : '';
$slides = array( 1 );
if ( get_theme_mod( 'fruitables_hero_slide_2_image', 0 ) || get_theme_mod( 'fruitables_hero_slide_2_link', '' ) ) { $slides[] = 2; }
?>
<section class="ft-hero"<?php if ( $style ) : ?> style="<?php echo esc_attr( $style ); ?>"<?php endif; ?> aria-labelledby="ft-hero-title">
    <div class="ft-wrap ft-hero-grid">
        <div class="ft-hero-copy">
            <p class="ft-eyebrow ft-hero-eyebrow"><?php echo esc_html( $hero['eyebrow'] ); ?></p>
            <h1 id="ft-hero-title"><?php echo esc_html( $hero['heading'] ); ?></h1>
            <p class="ft-hero-description"><?php echo esc_html( fruitables_home_mod( 'hero_description' ) ); ?></p>
            <div class="ft-hero-actions"><a class="ft-btn ft-btn-primary" href="<?php echo esc_url( fruitables_home_shop_url() ); ?>"><i class="bi bi-cart3" aria-hidden="true"></i><?php echo esc_html( fruitables_home_mod( 'hero_shop_label' ) ); ?></a><a class="ft-btn ft-btn-outline" href="<?php echo esc_url( fruitables_home_mod( 'hero_how_url' ) ?: '#ft-delivery' ); ?>"><i class="bi bi-play-circle" aria-hidden="true"></i><?php echo esc_html( fruitables_home_mod( 'hero_how_label' ) ); ?></a></div>
            <?php if ( get_theme_mod( 'fruitables_benefits_show', true ) ) : ?>
            <div class="ft-hero-benefits">
                <?php foreach ( fruitables_service_benefits_defaults() as $key => $default ) : if ( ! get_theme_mod( 'fruitables_benefit_' . $key . '_show', true ) ) { continue; } $icon = get_theme_mod( 'fruitables_benefit_' . $key . '_icon', $default['icon'] ); if ( ! array_key_exists( $icon, fruitables_service_benefits_icon_choices() ) ) { $icon = $default['icon']; } ?>
                    <span><i class="fas <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i><?php echo esc_html( get_theme_mod( 'fruitables_benefit_' . $key . '_title', $default['title'] ) ); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="ft-hero-visual">
            <div id="ftHeroSlides" class="carousel slide"<?php if ( count( $slides ) > 1 ) : ?> data-bs-ride="carousel"<?php endif; ?>>
                <div class="carousel-inner">
                    <?php foreach ( $slides as $n ) : $id = absint( get_theme_mod( 'fruitables_hero_slide_' . $n . '_image', 0 ) ); $link = get_theme_mod( 'fruitables_hero_slide_' . $n . '_link', '' ); $label = $hero['slide_' . $n . '_label']; ?>
                    <div class="carousel-item<?php echo 1 === $n ? ' active' : ''; ?>">
                        <?php if ( $id && wp_get_attachment_image_url( $id, 'large' ) ) : echo wp_get_attachment_image( $id, 'large', false, array( 'loading' => 1 === $n ? 'eager' : 'lazy', 'fetchpriority' => 1 === $n ? 'high' : 'auto' ) ); else : ?><img src="<?php echo esc_url( get_theme_file_uri( 1 === $n ? 'img/hero-img-1.png' : 'img/hero-img-2.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Fresh fruit and vegetables', 'fruitables' ); ?>" width="600" height="400" loading="<?php echo 1 === $n ? 'eager' : 'lazy'; ?>"<?php if ( 1 === $n ) : ?> fetchpriority="high"<?php endif; ?>><?php endif; ?>
                        <?php if ( $label ) : ?><?php if ( $link ) : ?><a class="ft-fresh-badge" href="<?php echo esc_url( $link ); ?>"><?php else : ?><span class="ft-fresh-badge"><?php endif; ?><i class="fas fa-leaf" aria-hidden="true"></i><?php echo esc_html( $label ); ?><?php if ( $link ) : ?></a><?php else : ?></span><?php endif; ?><?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if ( count( $slides ) > 1 ) : ?><button class="carousel-control-prev" type="button" data-bs-target="#ftHeroSlides" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button class="carousel-control-next" type="button" data-bs-target="#ftHeroSlides" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button><?php endif; ?>
            </div>
        </div>
    </div>
</section>
