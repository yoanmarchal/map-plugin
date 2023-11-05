<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Your Name <marchalyoan@gmail.com>
 */
class map_plugin_Public
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
     * @param string $map_plugin The name of the plugin.
     * @param string $version    The version of this plugin.
     */
    public function __construct($map_plugin, $version)
    {
        $this->map_plugin = $map_plugin;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in map_plugin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The map_plugin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->map_plugin, plugin_dir_url(__FILE__).'css/map-public.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        $map_settings = get_option( 'map_settings' );
 
        if (isset($map_settings['google_map_api_key']) && !empty($map_settings['google_map_api_key'])) {
            $url =  "https://maps.googleapis.com/maps/api/js?key=" . $map_settings['google_map_api_key'];
            wp_register_script('googlemaps',  $url, [], '', true);
        }

        wp_enqueue_script($this->map_plugin, plugin_dir_url(__FILE__).'js/map-public.js', ['jquery', 'googlemaps'], $this->version, true);
    }
}
