<?php
/*
Plugin Name: Events Interface
Plugin URI: https://wordpress.org/extend/plugins/events-interface/
Description: Convert the Different common used Events Calendars to a Standard Interface
Version: 1.0
Author: Sjoerd Takken
Author URI: https://www.sjoerdscomputerwelten.de/
Text Domain: events-interface
License: GPL2

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'EI_PLUGINS_URL', plugins_url( '', __FILE__ ) );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( !is_plugin_active( 'wp-libraries/wp-libraries.php' ) ) 
{
	// It is Okey to load the plugin if it is not already activated.
  // TODO: See https://waclawjacek.com/check-wordpress-plugin-dependencies/
  echo 'The plugin WP Libraries must be activated';
  die();
}

// -- Models --
require_once( dirname( __FILE__ ) . '/inc/models/class-eieventsavedlistener.php' );
require_once( dirname( __FILE__ ) . '/inc/models/class-eicalendareventsaveresult.php' );
require_once( dirname( __FILE__ ) . '/inc/models/class-eicalendareventlocation.php' );
require_once( dirname( __FILE__ ) . '/inc/models/class-eicalendareventtag.php' );
require_once( dirname( __FILE__ ) . '/inc/models/class-eicalendareventcategory.php' );
require_once( dirname( __FILE__ ) . '/inc/models/class-eicalendarevent.php' );
require_once( dirname( __FILE__ ) . '/inc/models/class-eicalendarfeed.php' );

// -- Models for Supported plugins --
//require_once( dirname( __FILE__ ) . '/inc/models/ecncalendarfeedtheeventscalendar.class.php' );
require_once( dirname( __FILE__ ) . '/inc/models/class-eicalendarfeedai1ec.php' );
require_once( dirname( __FILE__ ) . '/inc/models/class-eicalendarfeedeventsmanager.php' );


// -- Controllers --
require_once( dirname( __FILE__ ) . '/inc/controllers/class-eicalendarfeedfactory.php' );
require_once( dirname( __FILE__ ) . '/inc/controllers/class-eiinterface.php' );
require_once( dirname( __FILE__ ) . '/inc/controllers/class-eiadmincontrol.php' );


if ( ! function_exists( 'ei_load_textdomain' ) ) 
{
  /**
   * Load in any language files that we have setup
   */
  function ei_load_textdomain() 
  {
    load_plugin_textdomain( 'events-interface', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
  }
  add_action( 'plugins_loaded', 'ei_load_textdomain' );
}

function ei_start()
{
  $eiAdminControl = EIAdminControl::get_instance();
  $eiAdminControl->start();
}

add_action( 'init', 'ei_start' );


