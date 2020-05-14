<?php
if ( ! class_exists( 'EICalendarEventLocation' ) ) 
{

/**
  * EICalendarEventLocation
  * EICalendarEventLocation Objects contains Events Locations 
  * and are used to get a standard event location format which 
  * is undependend of the event calendar that is used.
  * 
  * @author     Sjoerd Takken
  * @copyright  No Copyright.
  * @license    GNU/GPLv2, see https://www.gnu.org/licenses/gpl-2.0.html
  */
class EICalendarEventLocation 
{
  private $_name;
  private $_address;
  private $_city;
  private $_zip;
  private $_state;
  private $_country_code;
  private $_website;
	private $_phone;
  private $_lon;
	private $_lat;
  
  public function __construct($name) 
  {
		$this->set_name($name);
  }

	public function set_name( $name ) 
  {
		$this->_name = $name;
	}

	public function get_name() 
  {
		return $this->_name;
	}

  public function set_address( $address ) 
  {
    $this->_address = $address;
  }

  public function get_address() 
  {
    return $this->_address;
  }

  public function set_zip( $zip ) 
  {
    $this->_zip = $zip;
  }

  public function get_zip() 
  {
    return $this->_zip;
  }

  public function set_city( $city ) 
  {
    $this->_city = $city;
  }

  public function get_city() 
  {
    return $this->_city;
  }

  public function set_state( $state ) 
  {
    $this->_state = $state;
  }

  public function get_state() 
  {
    return $this->_state;
  }

  public function set_country_code( $country_code ) 
  {
    $this->_country_code = $country_code;
  }

  public function get_country_code() 
  {
	  if ( empty( $this->_country_code )) 
    {
      return 'DE';
    }
    return $this->_country_code;
  }

  public function set_website( $website ) 
  {
    $this->_website = $website;
  }

  public function get_website() 
  {
    return $this->_website;
  }

	public function set_phone( $phone ) 
  {
		$this->_phone = $phone;
	}

	public function get_phone() 
  {
		return $this->_phone;
	}
    
	public function set_lon( $lon ) 
  {
		$this->_lon = $lon;
	}

	public function get_lon() 
  {
		return $this->_lon;
	}
    
	public function set_lat( $lat ) 
  {
		$this->_lat = $lat;
	}

	public function get_lat() 
  {
		return $this->_lat;
	}

  public function fill_lonlat_by_osm_nominatim()
  {
    $osm = new OsmAddress();
    if( !empty ($this->get_address() ))
    {
      $osm->set_street_and_number( $this->get_address());
    }

    if( !empty ($this->get_city()))
    {
      $osm->set_town( $this->get_city());
    }
    
    if( !empty ($this->get_zip()))
    {
      $osm->set_postcode( $this->get_zip());
    }

    if( !empty( $this->get_country_code()))
    {
      $osm->set_country_code( $this->get_country_code());
    }

    $osmN = new OsmNominatim();
    $osmRet = $osmN->find_by_address($osm);

    if( !empty($osmRet->get_street()))
    {
      if( !empty($osmRet->get_streetnumber()))
      {
        $this->set_address($osmRet->get_street() . ' ' .
          $osmRet->get_streetnumber());
      }
      else
      {
        $this->set_address($osmRet->get_street());
      }
    }
    
    if( !empty($osmRet->get_town()))
    {
      $this->set_city($osmRet->get_town());
    }
    
    if( !empty($osmRet->get_postcode()))
    {
      $this->set_zip($osmRet->get_postcode());
    }
    
    if( !empty($osmRet->get_country_code()))
    {
      $this->set_country_code(strtoupper($osmRet->get_country_code()));
    }

    if( !empty($osmRet->get_lon()))
    {
      $this->set_lon($osmRet->get_lon());
    }
    
    if( !empty($osmRet->get_lat()))
    {
      $this->set_lat($osmRet->get_lat());
    }
  }
    

  public function to_string()
  {
    return ''. $this->get_name() .' (' . $this->get_address() . ')';
  }
}

}
