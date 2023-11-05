<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Your Name <marchalyoan@gmail.com>
 */
class map_plugin_Admin
{
  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   *
   * @var string The ID of this plugin.
   */
  private $map_plugin;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   *
   * @var string The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   *
   * @param      string    $map_plugin       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */

  /**
   * Holds the values to be used in the fields callbacks.
   */
  private $options;

  public function __construct($map_plugin, $version)
  {
    $this->map_plugin = $map_plugin;
    $this->version = $version;
    add_action('init', [$this, 'cpt_store_init']);
    add_action('admin_menu', [$this, 'add_plugin_page']);
    add_action('admin_init', [$this, 'page_init']);
  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {
    wp_enqueue_style($this->map_plugin, plugin_dir_url(__FILE__) . 'css/map-admin.css', [], $this->version, 'all');
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {
    wp_enqueue_script($this->map_plugin, plugin_dir_url(__FILE__) . 'js/map-admin.js', ['jquery'], $this->version, false);
  }


  public function cpt_store_init()
  {
    $labels = [
      'name'                => _x('Stores', 'Post Type General Name', 'map-plugin'),
      'singular_name'       => _x('Store', 'Post Type Singular Name', 'map-plugin'),
      'menu_name'           => __('My stores', 'map-plugin'),
      'parent_item_colon'   => __('Parent Store:', 'map-plugin'),
      'all_items'           => __('All Stores', 'map-plugin'),
      'view_item'           => __('View store', 'map-plugin'),
      'add_new_item'        => __('Add New store', 'map-plugin'),
      'add_new'             => __('New store', 'map-plugin'),
      'edit_item'           => __('Edit store', 'map-plugin'),
      'update_item'         => __('Update store', 'map-plugin'),
      'search_items'        => __('Search stores', 'map-plugin'),
      'not_found'           => __('No stores found', 'map-plugin'),
      'not_found_in_trash'  => __('No stores found in Trash', 'map-plugin'),
    ];

    $args = [
      'label'               => __('Store', 'map-plugin'),
      'description'         => __('Stores', 'map-plugin'),
      'labels'              => $labels,
      'supports'            => ['title', 'thumbnail', 'page-attributes', 'custom-fields'],
      'taxonomies'          => ['category', 'post_tag'],
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => true,
      'menu_position'       => 20,
      'menu_icon'           => 'dashicons-location-alt',
      'can_export'          => false,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
    ];
    register_post_type('store', $args);

    add_action('add_meta_boxes', 'init_metabox');

    function init_metabox()
    {
      add_meta_box('store_infos', 'Informations sur la boutique', 'store_infos', 'store', 'advanced', 'high');
    }

    function store_infos($post)
    {
      $first_name = get_post_meta($post->ID, '_first_name', true);
      $last_name = get_post_meta($post->ID, '_last_name', true);
      $civility = get_post_meta($post->ID, '_civility', true);
      $adresse = get_post_meta($post->ID, '_adresse', true);
      $mail = get_post_meta($post->ID, '_mail', true);
      $phone = get_post_meta($post->ID, '_phone', true);
      $special = get_post_meta($post->ID, '_special', true);

      $coords = get_post_meta($post->ID, '_coords', true);
      $coordonnes_definies = get_post_meta($post->ID, '_defined_coords', true);?>
      <p>
        <input type="text" name="civility" value="<?= $civility;?>" placeholder="<?= __('Civility', 'map-plugin');?>" />
        <input type="text" name="last_name" value="<?= $last_name; ?>" placeholder="<?= __('First name', 'map-plugin');?>" />
      </p>

      <p>
        <input type="text" name="first_name" value="<?= $first_name; ?>" placeholder="<?= __('Last name', 'map-plugin'); ?>" />
      </p>
      <p>
      <label for="">Adresse</label>
        <div>
          <textarea id="" style="width: 250px;" name="_adress"><?= $adresse; ?></textarea>
        </div>

        <?php if (isset($coords) && is_array($coords) && isset($coords['lat']) && isset($coords['long'])) { ?>
          <input id="gps_coords" style="width: 250px;" type="text" name="_coords" value="<?= $coords['lat'] . ' , ' . $coords['long']; ?>" disabled="disabled" />
        <?php } ?>
        <input type="checkbox" name="defined_coords" checked="checked" value="1" id="defined_coords"> <label for="defined_coords">Coordonnées définies manuellement</label>
      </p>
      <p>
        <input type="text" name="mail" value="<?php echo $mail; ?>" placeholder="<?= __('Email', 'map-plugin'); ?>" />
      </p>
      <p>
        <input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="<?= __('Phone', 'map-plugin'); ?>" />
      </p>
      <p>
        <input type="text" name="special" value="<?php echo $special; ?>" placeholder="<?= __('Spécial', 'map-plugin'); ?>" />
      </p>
      <script type="text/javascript">
        // <![CDATA[
        jQuery(document).ready(function($) {
          var $gps_man = $('#defined_coords');

          function test_manual_coords() {
            if ($gps_man.prop("checked") == true) {
              $('#gps_coords').prop("disabled", false);
            } else {
              $('#gps_coords').prop("disabled", true);
            }
          }
          $gps_man.on('click', test_manual_coords);
          test_manual_coords();
        });

        // ]]>
      </script>
      <?php

    }

    add_action('save_post', 'save_metabox');

    function save_metabox($post_id)
    {
      if (isset($_POST['civility'])) {
        update_post_meta($post_id, '_civility', sanitize_text_field($_POST['civility']));
      }
      if (isset($_POST['last_name'])) {
        update_post_meta($post_id, '_last_name', sanitize_text_field($_POST['last_name']));
      }
      if (isset($_POST['first_name'])) {
        update_post_meta($post_id, '_first_name', sanitize_text_field($_POST['first_name']));
      }
      if (isset($_POST['mail'])) {
        update_post_meta($post_id, '_mail', is_email($_POST['mail']));
      }
      if (isset($_POST['phone'])) {
        update_post_meta($post_id, '_phone', esc_html($_POST['phone']));
      }
      if (isset($_POST['special'])) {
        update_post_meta($post_id, '_special', esc_html($_POST['special']));
      }

      if (isset($_POST['_adress'])) {
        $adresse = wp_strip_all_tags($_POST['_adress']);
        update_post_meta($post_id, '_adresse', $adresse);

        function get_coords($a)
        {
          $map_url = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode($a). '&limit=1';

          $request = wp_remote_get($map_url);
          $json = wp_remote_retrieve_body($request);

          if (empty($json)) {
            return false;
          }

          $json = json_decode($json);

          $long = $json->features[0]->geometry->coordinates[0];
          $lat = $json->features[0]->geometry->coordinates[1];
          return compact('lat', 'long');
        }

        //je récupère ma checkbox
        $coordonnes_definies = $_POST['defined_coords'];
        if ($coordonnes_definies == 1) { //si checkbox cochée...
          // je sauvegarde sa valeur
          update_post_meta($post_id, '_defined_coords', 1);
          //je construis un tableu à partir des coordonnées de l'utilisateur
          $user_coords = explode(',', trim($_POST['_coords']));
          $coords = ['lat' => $user_coords[0], 'long' => $user_coords[1]];
          // j'update les coordonnées définies par l'utilisateur
          update_post_meta($post_id, '_coords', $coords);
        } else { // sinon...
          //j'update sa valeur
          update_post_meta($post_id, '_defined_coords', 0);
          // je fais le taf' normal
          $coords = get_coords($adresse);
          if ($coords != '') {
            update_post_meta($post_id, '_coords', $coords);
          }
        }
      }
    }
  }

      /**
     * Add options page.
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Map settings',
            'manage_options',
            'map-settings-admin',
            [$this, 'create_admin_page']
        );
    }

    /**
     * Options page callback.
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('map_settings'); ?>
        <div class="wrap">
            <h2><?= __('Map settings') ?></h2>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('my_option_group');
                do_settings_sections('map-settings-admin');
                submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings.
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'map_settings', // Option name
            [$this, 'sanitize'] // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Réglages', // Title
            [$this, 'print_section_info'], // Callback
            'map-settings-admin' // Page
        );

        add_settings_field(
            'google_map_api_key', // ID
            'Google map api key', // Title
            [$this, 'googleMapApiKeyCallback'], // Callback
            'map-settings-admin', // Page
            'setting_section_id' // Section
        );
    }

    /**
     * Sanitize each setting field as needed.
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = [];

        if (isset($input['google_map_api_key'])) {
            $new_input['google_map_api_key'] = sanitize_text_field($input['google_map_api_key']);
        }

        return $new_input;
    }

    /**
     * Print the Section text.
     */
    public function print_section_info()
    {
        echo __('Enter your vos réglages ci-dessous:');
    }

    /**
     * Get the settings option array and print one of its values.
     */
    public function googleMapApiKeyCallback()
    {
        printf(
            '<input type="text" id="google_map_api_key" name="map_settings[google_map_api_key]" value="%s" />',
            isset($this->options['google_map_api_key']) ? esc_attr($this->options['google_map_api_key']) : ''
        );
    }
}
