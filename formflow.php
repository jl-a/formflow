<?php
/*
Plugin Name: 	Form Flow
Plugin URI:		http://wordpress.org/plugins/formflow/
Description: 	Create stunning forms
Version: 		0.1.2
Author: 		James Armitage
Author URI: 	https://jarmitage.com.au
Text Domain: 	formflow
Domain Path:	/languages
License: 		GPLv3
License URI:	https://www.gnu.org/licenses/gpl-3.0.en.html

    Copyright (C) 2023  James Armitage

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

// Do not namespace this file, as alias functions in this file must be available at the global scope

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
 * Outputs the HTML for a form. Registered as a global function for convenience.
 */
function formflow_output_form( $id ) {
    Formflow\Frontend\Render::form( $id );
}

function formflow_get_all_forms() {
    return FormFlow\Core\Forms::get_all();
}

function formflow_get_single_form( $id ) {
    return FormFlow\Core\Forms::get_single( $id );
}


/**
 * Initialise the plugin
 */
$formflow_instance = new FormFlow\Core\FormFlow();
$formflow_instance->hook_events();

register_activation_hook(
	__FILE__,
	[ 'FormFlow\Core\FormFlow', 'activation' ],
);
