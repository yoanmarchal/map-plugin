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
class map_plugin_admin
{
    /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         *
         * @var string The ID of this plugin.
         */
        private $mapPlugin;

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
          * @param      string    $mapPlugin       The name of this plugin.
          * @param      string    $version    The version of this plugin.
          */

    public function __construct($mapPlugin, $version)
    {
        $this->mapPlugin = $mapPlugin;
        $this->version = $version;
        add_action('init', [$this, 'cpt_store_init']);
        add_action('add_meta_boxes', [$this, 'init_metaboxes']);
        add_action('save_post', [$this, 'save_metabox_store']);
    }

        /**
         * Register the stylesheets for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueueStyles()
        {

            /*
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in map_plugin_loader as all of the hooks are defined
             * in that particular class.
             *
             * The map_plugin_loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_style($this->mapPlugin, plugin_dir_url(__FILE__).'css/map-admin.css', [], $this->version, 'all');
        }

        /**
         * Register the JavaScript for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueueScripts()
        {

            /*
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in map_plugin_loader as all of the hooks are defined
             * in that particular class.
             *
             * The map_plugin_loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_script($this->mapPlugin, plugin_dir_url(__FILE__).'js/map-admin.js', ['jquery'], $this->version, false);
        }

    public function init_metaboxes()
    {
        function store_infos($post)
        {
            $firstName = get_post_meta($post->ID, '_first_name', true);
            $lastName = get_post_meta($post->ID, '_last_name', true);
            $civility = get_post_meta($post->ID, '_civility', true);
            $adresse = get_post_meta($post->ID, '_adresse', true);
            $mail = get_post_meta($post->ID, '_mail', true);
            $phone = get_post_meta($post->ID, '_phone', true);

            $coords = get_post_meta($post->ID, '_coords', true);
            get_post_meta($post->ID, '_defined_coords', true);
            ?>
              <p>
                <input type="text" name="civility" value="<?php echo $civility;
            ?>" placeholder="<?= __('Civility', 'map-plugin');
            ?>"/>
                <input type="text" name="last_name" value="<?php echo $lastName;
            ?>" placeholder="<?= __('First name', 'map-plugin');
            ?>"/>
              </p>

              <p>
                <input type="text" name="first_name" value="<?php echo $firstName;
            ?>" placeholder="<?= __('Last name', 'map-plugin');
            ?>"/>
              </p>
              <p>
                <textarea id="" style="width: 250px;" name="_adress"><?php echo $adresse;
            ?></textarea>
                <input id="gps_coords" style="width: 250px;" type="text" name="_coords" value="<?php echo $coords['lat'].' , '.$coords['long'];
            ?>" disabled="disabled" />
                <input type="checkbox" name="defined_coords" checked="checked" value="1" id="defined_coords"> <label for="defined_coords">Coordonnées définies manuellement</label>
              </p>
              <p>
                <input type="text" name="mail" value="<?php echo $mail;
            ?>" placeholder="<?= __('Email', 'map-plugin');
            ?>" />
              </p>
              <p>
                <input type="text" name="phone" value="<?php echo $phone;
            ?>" placeholder="<?= __('Phone', 'map-plugin');
            ?>" />
              </p>
              <script type="text/javascript">// <![CDATA[
                jQuery(document).ready(function($){
                  var $gps_man = $('#defined_coords');
                  function test_manual_coords(){
                    if($gps_man.prop("checked")==true){
                      $('#gps_coords').prop("disabled",false);
                    }else{
                      $('#gps_coords').prop("disabled",true);
                    }
                  }
                  $gps_man.on('click',test_manual_coords);
                  test_manual_coords();
                });

              // ]]></script>
              <?php

        }

        add_meta_box('store_infos', 'Informations sur la boutique', 'store_infos', 'store', 'advanced', 'high');
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
    }

    public function save_metabox_store($postId)
    {
        $validCivility = filter_input(INPUT_POST, 'civility', FILTER_SANITIZE_STRING);
        if ($validCivility) {
            update_post_meta($postId, '_civility', $validCivility);
        }
        if (isset($_POST['last_name'])) {
            update_post_meta($postId, '_last_name', sanitize_text_field($_POST['last_name']));
        }
        if (isset($_POST['first_name'])) {
            update_post_meta($postId, '_first_name', sanitize_text_field($_POST['first_name']));
        }
        if (isset($_POST['mail'])) {
            update_post_meta($postId, '_mail', is_email($_POST['mail']));
        }
        if (isset($_POST['phone'])) {
            update_post_meta($postId, '_phone', esc_html($_POST['phone']));
        }

        if (isset($_POST['_adress'])) {
            $adresse = wp_strip_all_tags($_POST['_adress']);
            update_post_meta($postId, '_adresse', $adresse);

            function get_coords($a)
            {
                $map_url = 'http://maps.google.com/maps/api/geocode/json?address='.urlencode($a).'&sensor=false';
                $request = wp_remote_get($map_url);
                $json = wp_remote_retrieve_body($request);

                if (empty($json)) {
                    return false;
                }

                $json = json_decode($json);
                $lat = $json->results[0]->geometry->location->lat;
                $long = $json->results[0]->geometry->location->lng;

                return compact('lat', 'long');
            }

            //je récupère ma checkbox
            $coordonnes_definies = $_POST['defined_coords'];
            if ($coordonnes_definies == 1) { //si checkbox cochée...
              // je sauvegarde sa valeur
              update_post_meta($postId, '_defined_coords', 1);
              //je construis un tableu à partir des coordonnées de l'utilisateur
              $user_coords = explode(',', trim($_POST['_coords']));
                $coords = ['lat' => $user_coords[0], 'long' => $user_coords[1]];
              // j'update les coordonnées définies par l'utilisateur
              update_post_meta($postId, '_coords', $coords);
            } else { // sinon...
              //j'update sa valeur
              update_post_meta($postId, '_defined_coords', 0);
              // je fais le taf' normal
              $coords = get_coords($adresse);
                if ($coords != '') {
                    update_post_meta($postId, '_coords', $coords);
                }
            }
        }
    }
}
