# Map plugin

  - Contributors: (this should be a list of wordpress.org userid's)
  - Donate link: https://pledgie.com/campaigns/31846
  - Tags: comments, spam
  - Requires at least: 4.5.2
  - Tested up to: 4.5.2
  - Stable tag: 4.5.2
  - License: GPLv2 or later
  - License URI: http://www.gnu.org/licenses/gpl-2.0.html

[![Code Climate](https://codeclimate.com/github/yoanmarchal/map-plugin/badges/gpa.svg)](https://codeclimate.com/github/yoanmarchal/map-plugin) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/d6d94ab94d394b02bbe07b91d7021a70)](https://www.codacy.com/app/marchalyoan/map-plugin?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=yoanmarchal/map-plugin&amp;utm_campaign=Badge_Grade)

# Description

Minimal map plugin for multi stores cpt

# Installation

This section describes how to install the plugin and get it working.

1. Activate the plugin through the 'Plugins' menu in WordPress
```php
add to contact page
<section id="map-canvas" ></section>
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
        echo $coords[lat] . ',';
        echo $coords[long].'"';
        echo ' data-title="' . get_the_title() . '"';
        echo ' data-adress="' . esc_html($adress) . '"';
        echo ' data-phone="' . esc_html($phone) . '"';
        echo '></div>';
    }
  }
} else {
  // no posts found
}

// Restore original Post Data
wp_reset_postdata();
?>
```

# Changelog

## 1.0.1
Add Icon to dashboard, correct definition .

## 1.0
Initial plugin.

## Todo

* Test
* svg icons
