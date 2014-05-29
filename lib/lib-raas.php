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
          'show_in_menu'       => false,
          'query_var'          => false,
          'rewrite'            => false,
          'capability_type'    => 'post',
          'has_archive'        => false,
          'can_export'        => false,
          'hierarchical'       => false,
          'menu_position'      => 210,
          'supports'           => array( 'title', 'excerpt' )
        ));

        register_taxonomy( '_job:type', '_job', array(
          'labelsl' => array(),
          'hierarchical' => true,
          'rewrite' => false,
          'public' => false,
          'show_ui' => false,
          'show_admin_column' => true,
          '_builtin' => false
        ));

        register_taxonomy( '_job:priority', '_job', array(
          'labelsl' => array(),
          'hierarchical' => true,
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

  }

}