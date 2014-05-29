<?php
/**
 * RaaS Job Instance
 *
 * @namespace UsabilityDynamics
 * @module UsabilityDynamics
 * @author potanin@UD
 * @version 0.0.1
 */
namespace UsabilityDynamics\RaaS {

  use UsabilityDynamics;

  /**
   * Class Job
   *
   * @property object id
   * @property object data
   * @property object options
   * @property object post
   * @author team@UD
   * @version 0.1.1
   * @class Job
   * @subpackage Jobs
   */
  class Job {

    /**
     * Jobs Class version.
     *
     * @public
     * @static
     * @property $version
     * @type {Object}
     */
    public static $version = '0.1.0';

    /**
     * Create Job instance, post to RaaS API and save.
     *
     * @author potanin@UD
     * @method __construct
     *
     * @param null  $type
     * @param array $data
     * @param array $options
     *
     * @internal param $args
     * @return mixed
     */
    public function __construct( $type = null, $data = array(), $options = array() ) {

      UsabilityDynamics\RaaS::setup();

      $this->create( $type, $data, $options );

      return $this;

    }

    /**
     * Fetch From API
     *
     */
    private function fetch() {

    }

    /**
     *
     *
     */
    private function update() {

    }

    /**
     * Delete Job.
     *
     * Delete it from local store and from API.
     *
     */
    private function delete() {

    }

    /**
     * Create Job instance, post to RaaS API and save.

     * @param null  $type
     * @param array $data
     * @param array $options
     *
     * @internal param $args
     * @return mixed
     */
    private function create( $type = null, $data = array(), $options = array()  ) {

      $data = (object) wp_parse_args( $data, array(
        'title' => null
      ));

      $options = (object) wp_parse_args( $options, array(
        'cid' => 'country-life.ud-dev',
        'hash' => wp_generate_password( 32 ),
        'attempts' => 5,
        'priority' => 'normal'
      ));

      $_response = wp_remote_request( UsabilityDynamics\RaaS::$settings[ 'url' ], array(
        'method' => 'POST',
        'body' => array(
          'type' => $type,
          'data' => $data,
          'options' => $options
        )
      ));

      $body = isset( $_response['body'] ) ? json_decode( $_response['body'] ) : new Error( __( 'No response.') );

      $_post = array(
        'post_type'     => '_job',
        'post_title'    => isset( $data->title ) ? $data->title : null,
        'post_content'  => isset( $body->message ) ? $body->message : null,
        'post_password' => $options->hash,
        'import_id'     => $body->id,
        'tax_input'     => array(
          '_job:type'     => array( $type ),
          '_job:priority' => array( $options->priority )
        ),
        'post_status'   => 'publish'
      );

      $id = wp_insert_post( $_post, true );

      if( !$id || is_wp_error( $id ) ) {
        return new Error( 'Fail...' );
      }

      update_post_meta( $id, 'import_id',   $body->id  );
      update_post_meta( $id, '_edit_lock',  time() . ':0'  );

      $this->id = $id;
      $this->data = $data;
      $this->options = $options;
      $this->post = $_post;

      return $this;

    }

  }

}