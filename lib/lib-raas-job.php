<?php
/**
 * RaaS Job Instance
 *
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
     *
     */
    private function getLogs() {

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
     * @param array $config
     * @param array $options
     *
     * @internal param $args
     * @return mixed
     */
    private function create( $type = null, $config = array(), $options = array()  ) {

      $data = (object) array(
        'cid'     => 'country-life.ud-dev',
        'url'     => admin_url( 'admin-ajax.php' ),
        'hash'    => wp_generate_password( 32 ),
        'title'   => null,
        'config'  => $config
      );

      $options = (object) wp_parse_args( $options, array(
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
        'post_status'   => 'publish',
        'post_title'    => isset( $data->title )    ? $data->title    : __( 'Job #' . $body->id ),
        'post_excerpt'  => isset( $body->message )  ? $body->message  : null,
        'post_content'  => $data->data,
        'post_guid'     => $options->hash, /// better than password
        'import_id'     => $body->id,
        'tax_input'     => array(
          '_job:type'     => $type,
          '_job:state'    => 'inactive'
        )
      );

      $id = wp_insert_post( $_post, true );

      if( !$id || is_wp_error( $id ) ) {
        return new Error( 'Fail...' );
      }

      // Task Fields.
      update_post_meta( $id, '_attempts.max',  $options->attempts );
      update_post_meta( $id, '_attempts.remaining',  null );
      update_post_meta( $id, '_priority',  0  );
      update_post_meta( $id, '_progress',  0  );

      // WordPress System Fields.
      update_post_meta( $id, '_edit_lock',  time() . ':0'  );
      update_post_meta( $id, 'import_id',   $body->id  );

      $this->id = $id;
      $this->data = $data;
      $this->options = $options;
      $this->post = $_post;

      return $this;

    }

  }

}