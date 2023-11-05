<?php

/**
 * Provide a public-facing view for the plugin.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<section id="map-canvas" data-draggable="true" ></section>
<div class="row d-flex">
<?php
  $args = array (
    'post_type' => array( 'store' ),
  );
  // The Query
  $query = new WP_Query( $args );

  // The Loop
  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
      $query->the_post();  ?>
      <div class="col-md-6 store">
        <h3><?php the_title(); ?></h3>
        <?php
        $adress = get_post_meta( get_the_ID(), '_adresse', true );
        // Check if the custom field _adresse has a value.
        if ( ! empty( $adress ) ) {
            echo '<p>' . $adress . '</p>';
        }?>
        <?php
        $phone = get_post_meta( get_the_ID(), '_phone', true );
        // Check if the custom field _phone has a value.
        if ( ! empty( $phone ) ) {
            echo '<p><abbr title="' . __('Phone', 'Espace-forme') . '">Tel</abbr> : ' . $phone . '</p>';
        }?>

        <?php
        $mail = get_post_meta( get_the_ID(), '_mail', true );
        // Check if the custom field _phone has a value.
        if ( ! empty( $mail ) ) {
            echo '<p><abbr title="' . __('Email', 'Espace-forme') . '">E</abbr> : ' . $mail . '</p>';
        }?>

        <?php
        $coords = get_post_meta( get_the_ID(), '_coords', true );
        // Check if the custom field _phone has a value.
        if ( ! empty( $coords ) ) {
            echo '<div class="store-item" data-coords="';
            echo $coords['lat'] . ',';
            echo $coords['long'].'"';
            echo ' data-title="' . get_the_title() . '"';
            echo ' data-adress="' . esc_html($adress) . '"';
            echo ' data-phone="' . esc_html($phone) . '"';
            echo '></div>';
        }?>


      </div>


      <?php
    }
  } else {
    // no posts found
  }

  // Restore original Post Data
  wp_reset_postdata();
  ?>
</div>
