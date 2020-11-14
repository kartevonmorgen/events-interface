<?php
/*
Plugin Name: WP Events Interface
Plugin URI: https://wordpress.org/extend/plugins/wp-events-interface/
Description: Convert the Different common used Events Calendars to a Standard Interface
Version: 1.0
Author: Sjoerd Takken
Author URI: https://www.sjoerdscomputerwelten.de/
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


$loaderClass = WP_PLUGIN_DIR . '/wp-libraries/inc/lib/plugin/class-wp-pluginloader.php';
if(!file_exists($loaderClass))
{
  echo "Das Plugin 'wp-libraries' muss erst installiert und aktiviert werden";
  exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
include_once( $loaderClass);

class WPEventsInterfacePluginLoader extends WPPluginLoader
{
  public function init()
  {
    $this->add_dependency('wp-libraries/wp-libraries.php');
// -- Models --
    $this->add_include('/inc/models/class-eieventsavedlistener.php' );
    $this->add_include('/inc/models/class-eieventdeletedlistener.php' );
    $this->add_include('/inc/models/class-eicalendareventsaveresult.php' );
    $this->add_include('/inc/models/class-eicalendarevent.php' );
    $this->add_include('/inc/models/class-eicalendarfeed.php' );

// -- Models for Supported plugins --
//require_once( dirname( __FILE__ ) . '/inc/models/ecncalendarfeedtheeventscalendar.class.php' );
    $this->add_include('/inc/models/class-eicalendarfeedai1ec.php' );
    $this->add_include('/inc/models/class-eicalendarfeedeventsmanager.php' );


// -- Controllers --
    $this->add_include('/inc/controllers/class-eicalendarfeedfactory.php' );
    $this->add_include('/inc/controllers/class-eiinterface.php' );
    $this->add_include('/inc/controllers/class-eiadmincontrol.php' );

  }

  public function start()
  {
    $this->add_starter( new EIAdminControl());
  }
}

$loader = new WPEventsInterfacePluginLoader();
$loader->register( __FILE__ , 20);




