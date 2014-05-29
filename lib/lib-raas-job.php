<?php
/**
 * RaaS Handler
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

    /**s
     * Create
     *
     * @author potanin@UD
     * @method __construct
     * @param $args
     * @return mixed
     */
    public function __construct( $args ) {

      RaaS::setup();

      die( '<pre>' . print_r( $args, true ) . '</pre>' );
    }

  }

}