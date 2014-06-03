<?php
/**
 * RaaS Handler
 *
 * @namespace UsabilityDynamics
 * @module UsabilityDynamics
 * @author potanin@UD
 * @version 0.0.1
 */
namespace UsabilityDynamics {

  /**
   * Class RaaS
   *
   * @author team@UD
   * @version 0.1.1
   * @class RaaS
   * @subpackage RaaSs
   */
  final class RaaS {

    /**
     * RaaSs Class version.
     *
     * @public
     * @static
     * @property $version
     * @type {Object}
     */
    public static $version = '0.1.0';

    public static $is_setup = null;

    public static $settings = array(
      'url' => 'http://raas.udx.io/api/v2/job'
    );

    public static function search() {

    }

    public static function stats() {

    }

    public static function setup() {

      if( self::$is_setup ) {
        return false;
      }

      try {

        $_type = register_post_type( '_job', array(
          'labels'             => array(
            'name'               => _x( 'Jobs', 'post type general name', 'cl' ),
            'singular_name'      => _x( 'Job', 'post type singular name', 'cl' ),
            'menu_name'          => _x( 'Jobs', 'admin menu', 'cl' ),
            'name_admin_bar'     => _x( 'Job', 'add new on admin bar', 'cl' ),
            'add_new'            => _x( 'Add New', 'book', 'cl' ),
            'add_new_item'       => __( 'Add New Job', 'cl' ),
            'new_item'           => __( 'New Job', 'cl' ),
            'edit_item'          => __( 'Edit Job', 'cl' ),
            'view_item'          => __( 'View Job', 'cl' ),
            'all_items'          => __( 'All Jobs', 'cl' ),
            'search_items'       => __( 'Search Jobs', 'cl' ),
            'parent_item_colon'  => __( 'Parent:', 'cl' ),
            'not_found'          => __( 'No jobs found.', 'cl' ),
            'not_found_in_trash' => __( 'No jobs found in Trash.', 'cl' ),
          ),
          'public'             => false,
          'publicly_queryable' => false,
          'show_ui'            => true,
          'show_in_menu'       => true,
          'query_var'          => false,
          'rewrite'            => false,
          'capability_type'    => 'post',
          'has_archive'        => false,
          'can_export'        => false,
          'hierarchical'       => false,
          'menu_position'      => 210,
          'supports'           => array( 'title', 'custom-fields' ),
          'menu_icon'         => 'dashicons-randomize'
        ));

        register_taxonomy( '_job:type', '_job', array(
          'label' => __( 'Type' ),
          'labelsl' => array(
            'name'              => _x( 'Job Type', 'taxonomy general name' ),
            'singular_name'     => _x( 'Job Types', 'taxonomy singular name' ),
            'search_items'      => __( 'Search Job Type' ),
            'all_items'         => __( 'All Job Type' ),
            'parent_item'       => __( 'Parent Job Type' ),
            'parent_item_colon' => __( 'Parent Job Type:' ),
            'edit_item'         => __( 'Edit Job Type' ),
            'update_item'       => __( 'Update Job Type' ),
            'add_new_item'      => __( 'Add New Job Type' ),
            'new_item_name'     => __( 'New Job Type Name' ),
            'menu_name'         => __( 'Job Type' )
          ),
          'hierarchical' => false,
          'rewrite' => false,
          'public' => false,
          'show_ui' => false,
          'show_admin_column' => true,
          '_builtin' => false
        ));

        register_taxonomy( '_job:state', '_job', array(
          'label' => __( 'State' ),
          'labels' => array(
            'name'              => _x( 'Job State', 'taxonomy general name' ),
            'singular_name'     => _x( 'Job States', 'taxonomy singular name' ),
            'search_items'      => __( 'Search Job State' ),
            'all_items'         => __( 'All Job State' ),
            'parent_item'       => __( 'Parent Job State' ),
            'parent_item_colon' => __( 'Parent Job State:' ),
            'edit_item'         => __( 'Edit Job State' ),
            'update_item'       => __( 'Update Job State' ),
            'add_new_item'      => __( 'Add New Job State' ),
            'new_item_name'     => __( 'New Job State Name' ),
            'menu_name'         => __( 'Job State' )
          ),
          'hierarchical' => false,
          'rewrite' => false,
          'public' => false,
          'show_ui' => false,
          'show_admin_column' => true,
          '_builtin' => false
        ));

        self::$is_setup = true;

      } catch( Exception $e ) {}

      return true;

    }

    /**
     * Make RPC Request.
     *
     * @example
     *
     *      // Create Import Request.
     *      $_response = self::raasRequest( 'wpp.startImport', array( 'asdf' => 'sadfsadfasdfsadf' ) );
     *
     * @param string $method
     * @param array  $data
     *
     * @method raasRequest
     * @since 5.0.0
     *
     * @return array
     * @author potanin@UD
     */
    public function makeRequest( $method = '', $data = array() ) {

      include_once( ABSPATH . WPINC . '/class-IXR.php' );
      include_once( ABSPATH . WPINC . '/class-wp-http-ixr-client.php' );

      $client = new \WP_HTTP_IXR_Client( 'raas.udx.io', '/rpc/v1' );

      // Set User Agent.
      $client->useragent = 'WordPress/3.7.1 WP-Property/3.6.1 XML-Importer/' . self::$version;

      // Request Headers.
      $client->headers = array(
        'authorization'    => 'Basic ' . base64_encode( $wpp_property_import[ 'raas' ][ 'token' ] . ':' . $wpp_property_import[ 'raas' ][ 'session' ] ),
        'x-access-token'   => $wpp_property_import[ 'raas' ][ 'key' ],
        'x-session'        => $wpp_property_import[ 'raas' ][ 'session' ],
        'x-region'         => 'us-east',
        'x-callback'       => site_url( 'xmlrpc.php' ),
        'x-callback-token' => wp_generate_password( 20 )
      );

      // Execute Request.
      $client->query( $method, $data );

      // Return Message.
      return isset( $client->message ) && isset( $client->message->params ) && is_array( $client->message->params ) ? $client->message->params[ 0 ] : array();

    }

  }

}