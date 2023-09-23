<?php
/*
Plugin Name: 	Form Flow
Plugin URI:		http://wordpress.org/plugins/form-flow/
Description: 	Create stunning forms
Version: 		0.0.1
Author: 		JArmitage
Author URI: 	https://jarmitage.com.au
Text Domain: 	form-flow
Domain Path:	/languages
License: 		GPLv2 or later
License URI:	http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2023 and beyond | JArmitage (email : james@jarmitage.com.au)

*/

namespace FormFlow;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Global variables
 */
define( 'FORMFLOW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // define the absolute plugin path for includes
define( 'FORMFLOW_PLUGIN_URI', plugin_dir_url( __FILE__ ) ); // define the plugin url for use in enqueue
define( 'FORMFLOW_VERSION', '0.0.1' );

/**
 * Composer autoload, so we can access by classnames rather than file paths
 */
require FORMFLOW_PLUGIN_PATH . '/vendor/autoload.php';

/**
 * Initialise the plugin
 */
$formflow_instance = new App\FormFlow();
$formflow_instance->hook_events();

register_activation_hook(
	__FILE__,
	[ 'FormFlow\App\FormFlow', 'activation' ],
);
