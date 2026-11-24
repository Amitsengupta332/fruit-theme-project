<?php /** Homepage-only header. Variables are supplied by header.php. */ ?>
<header class="ft-header">
    <?php if ( $topbar_visible ) : ?>
    <div class="ft-topbar"><div class="ft-wrap ft-topbar-inner">
        <div class="ft-top-messages">
            <?php if ( fruitables_home_mod( 'topbar_message' ) ) : ?><span><i class="fas fa-truck" aria-hidden="true"></i> <?php echo esc_html( fruitables_home_mod( 'topbar_message' ) ); ?></span><?php endif; ?>
            <?php if ( fruitables_home_mod( 'topbar_secondary' ) ) : ?><span><?php echo esc_html( fruitables_home_mod( 'topbar_secondary' ) ); ?></span><?php endif; ?>
        </div>
        <?php if ( has_nav_menu( 'topbar' ) ) : ?>
            <?php wp_nav_menu( array( 'theme_location' => 'topbar', 'container' => 'nav', 'container_aria_label' => __( 'Top bar links', 'fruitables' ), 'menu_class' => 'ft-top-links', 'depth' => 1, 'fallback_cb' => false ) ); ?>
        <?php elseif ( $email ) : ?><a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><i class="fas fa-headset" aria-hidden="true"></i> <?php esc_html_e( 'Help & Support', 'fruitables' ); ?></a><?php endif; ?>
    </div></div>
    <?php endif; ?>
    <div class="ft-wrap"><nav class="navbar navbar-expand-xl ft-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'fruitables' ); ?>">
        <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
        <a class="ft-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-leaf" aria-hidden="true"></i> <?php echo esc_html( get_bloginfo( 'name' ) ?: 'Fruitables' ); ?></a>
        <?php endif; ?>
        <button class="navbar-toggler ft-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#ftHomeNav" aria-controls="ftHomeNav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'fruitables' ); ?>"><i class="fas fa-bars" aria-hidden="true"></i></button>
        <div class="collapse navbar-collapse" id="ftHomeNav">
            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'items_wrap' => '<div id="%1$s" class="navbar-nav mx-auto">%3$s</div>', 'depth' => 2, 'walker' => new Fruitables_Primary_Menu_Walker(), 'fallback_cb' => 'fruitables_primary_menu_fallback' ) ); ?>
            <div class="ft-nav-actions">
                <?php if ( $show_search ) : ?>
                <form class="ft-search" role="search" action="<?php echo esc_url( $search_action ); ?>" method="get">
                    <?php foreach ( $search_args as $arg => $value ) : if ( is_scalar( $value ) ) : ?><input type="hidden" name="<?php echo esc_attr( $arg ); ?>" value="<?php echo esc_attr( $value ); ?>"><?php endif; endforeach; ?>
                    <?php if ( $has_wc && ! $shop_available ) : ?><input type="hidden" name="post_type" value="product"><?php endif; ?>
                    <button type="submit" aria-label="<?php echo esc_attr( get_theme_mod( 'fruitables_hero_search_button', __( 'Search', 'fruitables' ) ) ); ?>"><i class="fas fa-search" aria-hidden="true"></i></button>
                    <label class="visually-hidden" for="ft-home-search"><?php esc_html_e( 'Search products', 'fruitables' ); ?></label><input id="ft-home-search" type="search" name="<?php echo esc_attr( $search_name ); ?>" placeholder="<?php echo esc_attr( get_theme_mod( 'fruitables_hero_search_placeholder', fruitables_hero_text_defaults()['search_placeholder'] ) ); ?>" required>
                </form>
                <?php endif; ?>
                <?php if ( $show_account ) : ?><a class="ft-nav-icon" href="<?php echo esc_url( $account_url ); ?>" aria-label="<?php esc_attr_e( 'My account', 'fruitables' ); ?>"><i class="bi bi-person" aria-hidden="true"></i></a><?php endif; ?>
                <?php if ( $show_cart && $has_wc ) : ?><a class="ft-nav-icon ft-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Shopping cart', 'fruitables' ); ?>"><i class="bi bi-cart3" aria-hidden="true"></i><?php echo fruitables_cart_count_html(); ?></a><?php endif; ?>
            </div>
        </div>
    </nav></div>
</header>
