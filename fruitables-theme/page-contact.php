<?php
/**
 * Template Name: Contact
 * Description: Fruitables contact page; also selected automatically for the contact slug.
 */
defined( 'ABSPATH' ) || exit;
get_header();

$contact_title   = get_the_title( get_queried_object_id() );
$contact_address = trim( fruitables_shared_contact( 'address' ) );
$contact_url     = esc_url( fruitables_shared_contact( 'address_url' ) );
$contact_email   = sanitize_email( fruitables_shared_contact( 'email' ) );
$contact_phone   = trim( fruitables_shared_contact( 'phone' ) );
$contact_tel     = preg_replace( '/[^0-9+]/', '', $contact_phone );
$has_details     = $contact_address || $contact_email || $contact_phone;
$map_url         = $contact_address ? add_query_arg( array( 'q' => rawurlencode( $contact_address ), 'output' => 'embed' ), 'https://maps.google.com/maps' ) : '';
?>
<main id="primary">
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6"><?php echo esc_html( $contact_title ); ?></h1>
        <nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'fruitables' ); ?>">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'fruitables' ); ?></a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo esc_html( $contact_title ); ?></li>
            </ol>
        </nav>
    </div>
    <!-- Single Page Header End -->

    <!-- Contact Start -->
    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="p-5 bg-light rounded">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="text-center mx-auto" style="max-width: 700px;">
                            <h2 class="h1 text-primary"><?php esc_html_e( 'Get in touch', 'fruitables' ); ?></h2>
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php if ( '' !== trim( get_the_content() ) ) : ?>
                                    <div class="mb-4"><?php the_content(); ?></div>
                                <?php else : ?>
                                    <p class="mb-4"><?php esc_html_e( 'Have a question about our products or your order? We would love to hear from you.', 'fruitables' ); ?></p>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php if ( $map_url ) : ?>
                        <div class="col-lg-12">
                            <div class="h-100 rounded">
                                <iframe class="rounded w-100" style="height: 400px;" src="<?php echo esc_url( $map_url ); ?>" title="<?php esc_attr_e( 'Business location map', 'fruitables' ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="<?php echo $has_details ? 'col-lg-7' : 'col-12'; ?>">
                        <!-- Layout preview only. Replace with the configured contact form in the next step. -->
                        <p class="mb-3"><?php esc_html_e( 'Online message sending is not available yet.', 'fruitables' ); ?></p>
                        <fieldset disabled>
                            <label class="visually-hidden" for="fruitables-contact-name"><?php esc_html_e( 'Your Name', 'fruitables' ); ?></label>
                            <input id="fruitables-contact-name" type="text" class="w-100 form-control border-0 py-3 mb-4" placeholder="<?php esc_attr_e( 'Your Name', 'fruitables' ); ?>">
                            <label class="visually-hidden" for="fruitables-contact-email"><?php esc_html_e( 'Enter Your Email', 'fruitables' ); ?></label>
                            <input id="fruitables-contact-email" type="email" class="w-100 form-control border-0 py-3 mb-4" placeholder="<?php esc_attr_e( 'Enter Your Email', 'fruitables' ); ?>">
                            <label class="visually-hidden" for="fruitables-contact-message"><?php esc_html_e( 'Your Message', 'fruitables' ); ?></label>
                            <textarea id="fruitables-contact-message" class="w-100 form-control border-0 mb-4" rows="5" cols="10" placeholder="<?php esc_attr_e( 'Your Message', 'fruitables' ); ?>"></textarea>
                            <button class="w-100 btn form-control border-secondary py-3 bg-white text-primary" type="button" disabled><?php esc_html_e( 'Submit', 'fruitables' ); ?></button>
                        </fieldset>
                    </div>
                    <?php if ( $has_details ) : ?>
                        <div class="col-lg-5">
                            <?php if ( $contact_address ) : ?>
                                <div class="d-flex p-4 rounded mb-4 bg-white">
                                    <i class="fas fa-map-marker-alt fa-2x text-primary me-4" aria-hidden="true"></i>
                                    <div>
                                        <h4><?php esc_html_e( 'Address', 'fruitables' ); ?></h4>
                                        <p class="mb-2"><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( $contact_address ); ?></a><?php else : ?><?php echo esc_html( $contact_address ); ?><?php endif; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ( $contact_email ) : ?>
                                <div class="d-flex p-4 rounded mb-4 bg-white">
                                    <i class="fas fa-envelope fa-2x text-primary me-4" aria-hidden="true"></i>
                                    <div>
                                        <h4><?php esc_html_e( 'Mail Us', 'fruitables' ); ?></h4>
                                        <p class="mb-2"><a href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ( $contact_phone ) : ?>
                                <div class="d-flex p-4 rounded bg-white">
                                    <i class="fa fa-phone-alt fa-2x text-primary me-4" aria-hidden="true"></i>
                                    <div>
                                        <h4><?php esc_html_e( 'Telephone', 'fruitables' ); ?></h4>
                                        <p class="mb-2"><?php if ( $contact_tel ) : ?><a href="<?php echo esc_url( 'tel:' . $contact_tel ); ?>"><?php echo esc_html( $contact_phone ); ?></a><?php else : ?><?php echo esc_html( $contact_phone ); ?><?php endif; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
</main>
<?php get_footer(); ?>
