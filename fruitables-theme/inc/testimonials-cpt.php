<?php
/** Manage client testimonials independently from WooCommerce product reviews. */
defined( 'ABSPATH' ) || exit;

function fruitables_register_testimonials() {
    register_post_type( 'ft_testimonial', array(
        'labels' => array(
            'name'               => __( 'Testimonials', 'fruitables' ),
            'singular_name'      => __( 'Testimonial', 'fruitables' ),
            'add_new_item'       => __( 'Add Testimonial', 'fruitables' ),
            'edit_item'          => __( 'Edit Testimonial', 'fruitables' ),
            'all_items'          => __( 'All Testimonials', 'fruitables' ),
            'featured_image'     => __( 'Client Photo', 'fruitables' ),
            'set_featured_image' => __( 'Set client photo', 'fruitables' ),
        ),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'map_meta_cap'       => true,
        'exclude_from_search'=> true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields' ),
        'rewrite'            => false,
    ) );
    register_post_meta( 'ft_testimonial', '_fruitables_profession', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
}
add_action( 'init', 'fruitables_register_testimonials' );

function fruitables_testimonial_metabox() {
    add_meta_box(
        'fruitables_testimonial_details',
        __( 'Client Details', 'fruitables' ),
        'fruitables_testimonial_metabox_render',
        'ft_testimonial',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes_ft_testimonial', 'fruitables_testimonial_metabox' );

function fruitables_testimonial_metabox_render( $post ) {
    wp_nonce_field( 'fruitables_save_testimonial', 'fruitables_testimonial_nonce' );
    $profession = get_post_meta( $post->ID, '_fruitables_profession', true );
    echo '<p><label for="fruitables_profession"><strong>' . esc_html__( 'Profession / Job Title', 'fruitables' ) . '</strong></label></p>';
    echo '<input class="widefat" type="text" name="fruitables_profession" id="fruitables_profession" value="' . esc_attr( $profession ) . '" />';
    $rating = absint( get_post_meta( $post->ID, '_fruitables_rating', true ) );
    echo '<p><label for="fruitables_rating">' . esc_html__( 'Customer rating (optional, enter only a real rating)', 'fruitables' ) . '</label></p><select name="fruitables_rating" id="fruitables_rating">';
    for ( $i = 0; $i <= 5; $i++ ) { echo '<option value="' . esc_attr( $i ) . '" ' . selected( $rating, $i, false ) . '>' . esc_html( $i ? $i : __( 'Not provided', 'fruitables' ) ) . '</option>'; }
    echo '</select>';
    echo '<p>' . esc_html__( 'Client name: post title. Client comment: main content editor. Client photo: featured image.', 'fruitables' ) . '</p>';
}

function fruitables_save_testimonial( $post_id ) {
    if ( ! isset( $_POST['fruitables_testimonial_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fruitables_testimonial_nonce'] ) ), 'fruitables_save_testimonial' ) ||
         ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
         wp_is_post_revision( $post_id ) ||
         ! current_user_can( 'edit_post', $post_id ) ||
         ! isset( $_POST['fruitables_profession'] ) ) {
        return;
    }
    if ( isset( $_POST['fruitables_rating'] ) && is_scalar( $_POST['fruitables_rating'] ) ) {
        $rating = min( 5, absint( $_POST['fruitables_rating'] ) );
        update_post_meta( $post_id, '_fruitables_rating', $rating );
    }
    update_post_meta( $post_id, '_fruitables_profession', sanitize_text_field( wp_unslash( $_POST['fruitables_profession'] ) ) );
}
add_action( 'save_post_ft_testimonial', 'fruitables_save_testimonial' );
