<?php
if ( ! class_exists( 'EICalendarEvent' ) ) 
{

/**
  * EICalendarEvent
  * EICalendarEvent Objects contains Events and are used to get
  * a standard event format which is undependend of the 
  * event calendar that is used.
  * 
  * The EICalendarEvent Object contains all the information
  * about an event. So the native implementation is hidden for 
  * the users of this Interface.
  *
  * Plugins that are using this interface are indepent of the underlying
  * active event calendar, so they do not have to write code for the
  * many different event calendar plugins that exists in Wordpress
  *
  * @author     Sjoerd Takken
  * @copyright  No Copyright.
  * @license    GNU/GPLv2, see https://www.gnu.org/licenses/gpl-2.0.html
  */
class EICalendarEvent 
{
  private $_title;
	private $_subtitle;
  private $_slug;
  private $_uid;
  private $_link;
  private $_event_id;
  private $_post_id;
  private $_event_instance_id;
  private $_blog_id;
  private $_owner_user_id;
  private $_description;
  private $_excerpt;
  private $_start_date;
  private $_end_date;
  private $_all_day;
	private $_published_date;
	private $_updated_date;
	private $_colour;
	private $_featured;
	private $_plugin;
  private $_contact_name;
  private $_contact_info;
  private $_contact_website;
  private $_contact_email;
  private $_contact_phone;
	private $_organizer_name;
	private $_organizer_website;
	private $_organizer_email;
	private $_organizer_phone;
	private $_event_website;
  private $_event_cost;
  private $_event_image_url;
  private $_event_image_alt;
  private $_repeat_frequency;
  private $_repeat_interval;
  private $_repeat_end;
	private $_recurrence_text;
	private $_categories = array();
	private $_tags = array();
	private $_location;

    const REPEAT_DAY = 'day';
    const REPEAT_MONTH = 'month';
    const REPEAT_YEAR = 'year';
    const REPEAT_WEEK = 'week';

    public function __construct() 
    {
      $_event_id = 0;
      $_tags = array();
      $_categories = array();
      $_location = null;
    }

    public static function get_day_text( $number = 1 ) 
    {
      return _n( 'day', 'days', $number, 'events-interface' );
    }

    public static function get_week_text( $number = 1 ) 
    {
      return _n( 'week', 'weeks', $number, 'events-interface' );
    }

    public static function get_month_text( $number = 1 ) 
    {
      return _n( 'month', 'months', $number, 'events-interface' );
    }

    public static function get_year_text( $number = 1 ) 
    {
      return _n( 'year', 'years', $number, 'events-interface' );
    }

    private function sanitize_link( $link ) 
    {
      return $link;
    }
    
    private function sanitize_date( $date ) 
    {
      if (empty($date))
      {
        return null;
      }
      else if ( is_numeric( $date ) )
      {
        return self::get_date_GMT($date);
      }
      elseif ( strtotime( $date ) !== FALSE )
      {
        return self::get_date_GMT( strtotime( $date ));
      }
      else
      {
        throw new Exception( __( 'Invalid date', 
                                 'event-calendar-newsletter' ));
      }
    }

    private function get_date_GMT( $timestamp_unix ) 
    {
      $timezone = "Europe/Berlin";
      
      if( function_exists( 'date_default_timezone_get' ) ) 
      {
        $timezone = date_default_timezone_get();
      }

      $dt = new DateTime(
        date( 'Y-m-d H:i:s', $timestamp_unix ),
          new DateTimeZone( $timezone )
        );
 
      return $dt->format( DateTime::ATOM );
    }

  public function set_title( $title ) 
  {
    $this->_title = $title;
    if(empty($this->get_slug()))
    {
      $this->set_slug( sanitize_title_with_dashes( $title) );
    }
  }

  public function get_title() 
  {
    return $this->_title;
  }

	public function set_subtitle( $subtitle ) 
  {
		$this->_subtitle = $subtitle;
	}

	public function get_subtitle() 
  {
		return $this->_subtitle;
	}

  public function set_slug( $slug ) 
  {
    $this->_slug = $slug;
  }

  public function get_slug() 
  {
    return $this->_slug;
  }

  /** 
   * Hera a unified identifier for the event should be setted.
   * If no unified identifier is setted for the event.
   * the get_uid() method will generate one.  @see get_uid().
   * 
   * @param uid String
   */
	public function set_uid( $uid ) 
  {
		$this->_uid = $uid;
	}

  /**
   * Get an unified id for the event. 
   * If no uid is set by set_uid(..) then
   * a uid is generate out of the 
   * hostname, event_slug, event_id and event_instance_id
   * @return String
   */ 
	public function get_uid() 
  {
    if(empty($this->_uid))
    {
      $uid .= $this->get_hostname_identifier();
      $uid .= '_';
      $uid .= $this->get_slug();
      if(!empty($this->get_event_id()))
      {
        $uid .= '_';
        $uid .= $this->get_event_id();
      }
      if(!empty($this->get_event_instance_id()))
      {
        $uid .= '_';
        $uid .= $this->get_event_instance_id();
      }
      $this->set_uid($uid);
    }
    return $this->_uid;
	}

  public function set_link( $link ) 
  {
    $this->_link = $link;
  }

  public function get_link() 
  {
    return $this->_link;
  }

  public function set_event_id( $event_id ) 
  {
    $this->_event_id = $event_id;
  }

  public function get_event_id() 
  {
    return $this->_event_id;
  }

  public function set_post_id( $post_id ) 
  {
    $this->_post_id = $post_id;
  }

  public function get_post_id() 
  {
    return $this->_post_id;
  }

  public function set_event_instance_id( $event_instance_id ) 
  {
    $this->_event_instance_id = $event_instance_id;
  }

  public function get_event_instance_id() 
  {
    return $this->_event_instance_id;
  }

  public function set_blog_id( $blog_id ) 
  {
    $this->_blog_id = $blog_id;
  }

  public function get_blog_id() 
  {
    return $this->_blog_id;
  }

  public function set_owner_user_id( $owner_user_id ) 
  {
    $this->_owner_user_id = $owner_user_id;
  }

  public function get_owner_user_id() 
  {
    return $this->_owner_user_id;
  }


  public function set_description( $description ) 
  {
    $this->_description = $description;
  }

  public function get_description() 
  {
    return $this->_description;
  }

  public function set_excerpt( $excerpt ) 
  {
    $this->_excerpt = $excerpt;
  }

  public function get_excerpt() 
  {
	  if ( ! $this->_excerpt ) 
    {
		  $excerpt = strip_shortcodes( $this->get_description() );
		  $excerpt = strip_tags( $excerpt );
		  return apply_filters( 'ei_get_excerpt_from_description', apply_filters( 'ei_get_excerpt', wp_trim_words( $excerpt, apply_filters( 'ei_get_excerpt_words', 55, $this ), apply_filters( 'ei_excerpt_more', ' [&hellip;]', $this ) ), $this ), $this );
	  }
    return apply_filters( 'ei_get_excerpt', $this->_excerpt, $this );
  }

	public function set_start_date( $start_date ) 
  {
    $this->_start_date = $this->sanitize_date( $start_date );
  }

  public function get_start_date() 
  {
    return $this->_start_date;
  }

	public function set_end_date( $end_date ) 
  {
    $this->_end_date = $this->sanitize_date( $end_date );
  }

  public function get_end_date() 
  {
    return $this->_end_date;
  }

	public function set_all_day( $all_day ) 
  {
    $this->_all_day = $all_day ? true : false;
  }

  public function get_all_day() 
  {
    return $this->_all_day;
  }

	public function set_published_date( $published_date ) 
  {
		$this->_published_date = $this->sanitize_date( $published_date );
	}

	public function get_published_date() 
  {
		return $this->_published_date;
	}

	public function set_updated_date( $updated_date ) 
  {
		$this->_updated_date = $this->sanitize_date( $updated_date );
	}

	public function get_updated_date() 
  {
		return $this->_updated_date;
	}

	public function set_plugin( $plugin ) 
  {
		$this->_plugin = $plugin;
	}

	public function get_plugin() 
  {
		return $this->_plugin;
	}

	public function set_colour( $colour ) 
  {
		$this->_colour = $colour;
	}

	public function get_colour() 
  {
		return $this->_colour;
	}

	public function set_featured( $featured ) 
  {
		$this->_featured = $featured;
	}

	public function get_featured() 
  {
		return $this->_featured;
	}

  public function set_contact_name( $contact_name ) 
  {
    $this->_contact_name = $contact_name;
  }

  public function get_contact_name() 
  {
    return $this->_contact_name;
  }

  public function set_contact_info( $contact_info ) 
  {
    $this->_contact_info = $contact_info;
  }

  public function get_contact_info() 
  {
    return $this->_contact_info;
  }

	public function set_contact_phone( $contact_phone ) 
  {
    $this->_contact_phone = $contact_phone;
  }

  public function get_contact_phone() 
  {
    return $this->_contact_phone;
  }

  public function set_contact_website( $contact_website ) 
  {
    $this->_contact_website = $contact_website;
  }

  public function get_contact_website() 
  {
    return $this->_contact_website;
  }

  public function set_contact_email( $contact_email ) 
  {
    $this->_contact_email = $contact_email;
  }

  public function get_contact_email() 
  {
    return $this->_contact_email;
  }

	public function set_organizer_name( $organizer_name ) 
  {
		$this->_organizer_name = $organizer_name;
	}

	public function get_organizer_name() 
  {
		return $this->_organizer_name;
	}

	public function set_organizer_phone( $organizer_phone ) 
  {
		$this->_organizer_phone = $organizer_phone;
	}

	public function get_organizer_phone() 
  {
		return $this->_organizer_phone;
	}

	public function set_organizer_website( $organizer_website ) 
  {
		$this->_organizer_website = $organizer_website;
	}

	public function get_organizer_website() 
  {
		return $this->_organizer_website;
	}

	public function set_organizer_email( $organizer_email ) 
  {
		$this->_organizer_email = $organizer_email;
	}

	public function get_organizer_email() 
  {
		return $this->_organizer_email;
	}

  public function set_event_website( $event_website ) 
  {
    $this->_event_website = $event_website;
  }

  public function get_event_website() 
  {
    return $this->_event_website;
  }

  public function set_event_cost( $event_cost ) 
  {
    $this->_event_cost = $event_cost;
  }

  public function get_event_cost() 
  {
    return $this->_event_cost;
  }

  public function get_event_image() 
  {
	  if ( $this->get_event_image_url() )
    {
		  return '<img src="' . esc_url( $this->get_event_image_url() ) . '" alt="' . esc_attr( $this->get_event_image_alt() ) . '" />';
    }
	  return '';
  }

	public function set_event_image_alt( $event_image_alt ) 
  {
		$this->_event_image_alt = sanitize_text_field( $event_image_alt );
	}

	public function get_event_image_alt() 
  {
		return $this->_event_image_alt;
	}

  public function set_event_image_url( $event_image_url ) 
  {
    $this->_event_image_url = $event_image_url;
  }


  public function get_event_image_url() 
  {
	  if ( ! $this->_event_image_url ) 
    {
		  preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $this->get_description(), $matches );
		  if ( is_array( $matches) and isset( $matches[1], $matches[1][0] ) and !empty( $matches[1][0] ) )
      {
			  return $matches[1][0];
      }
	  }
    return $this->_event_image_url;
  }

  public function set_repeat_frequency( $repeat_frequency ) 
  {
    $this->_repeat_frequency = $repeat_frequency;
  }

  public function get_repeat_frequency() 
  {
    return $this->_repeat_frequency;
  }

  public function set_repeat_interval( $repeat_interval ) 
  {
    if ( ! in_array( $repeat_interval, array( self::REPEAT_DAY, self::REPEAT_MONTH, self::REPEAT_WEEK, self::REPEAT_YEAR ) ) )
    {
      $this->_repeat_interval = false;
      return;
    }
    $this->_repeat_interval = $repeat_interval;
  }

  public function get_repeat_interval() 
  {
    return $this->_repeat_interval;
  }

  public function get_repeat_interval_text( $number = 1 ) 
  {
    return $this->{"get_" . $this->get_repeat_interval() . "_text"}( $number );
  }

  public function set_repeat_end( $repeat_end ) 
  {
    $this->_repeat_end = $this->sanitize_date( $repeat_end );
  }

  public function get_repeat_end() 
  {
    return date( 'Y-m-d', $this->_repeat_end );
  }

  public function set_recurrence_text( $text ) 
  {
  	$this->_recurrence_text = trim( strip_tags( $text ) );
  }

  public function get_recurrence_text() 
  {
  	return $this->_recurrence_text;
  }

  /*
   * Set an array of EICalendarEventCategory objects
   */
	public function set_categories( $categories ) 
  {
    $this->_categories = $categories;
	}

  /*
   * Add an array of EICalendarEventCategory objects
   */
	public function add_category( $category ) 
  {
    array_push($this->_categories, $category);
	}

  /*
   * Return an array of EICalendarEventCategory objects
   */
	public function get_categories() 
  {
		return $this->_categories;
	}

  /*
   * Add an array of EICalendarEventTag objects
   */
	public function add_tag( $tag ) 
  {
    array_push($this->_tags, $tag);
	}

  /*
   * Set an array of EICalendarEventTag objects
   */
	public function set_tags( $tags ) 
  {
    $this->_tags = $tags;
	}

  /*
   * Return an array of EICalendarEventTag objects
   */
	public function get_tags() 
  {
		return $this->_tags;
	}

  /*
   * Set an WPLocation object
   */
  public function set_location( $location )
  {
    $this->_location = $location;
  }

  /*
   * Get an WPLocation object
   */
  public function get_location()
  {
    return $this->_location;
  }

  function get_hostname_identifier()
  {
     $id = get_home_url();
     $id = str_replace("http://", "", $id);
     $id = str_replace("https://", "", $id);
     $id = str_replace("/", "", $id);
     $id = str_replace(".", "-", $id);
     return $id;
  }

	function replace_start_date_with_format( $matches ) 
  {
    return date_i18n( $matches[1], $this->get_start_date() );
  }

  function replace_end_date_with_format( $matches ) 
  {
    return date_i18n( $matches[1], $this->get_end_date() );
  }

  private function add_line($caption, $value)
  {
    return ''. $caption . ': ' . $value . PHP_EOL;
  }

  public function equals($eiEvent)
  {
    if(empty($eiEvent))
    {
      return false;
    }

    if(!is_object($eiEvent))
    {
      return false;
    }

    if(get_class($this) !== get_class($eiEvent))
    {
      return false;
    }

    echo 'EQUALS ' . $this->get_uid() . ' == ' . $eiEvent->get_uid();

    return $this->get_uid() === $eiEvent->get_uid();
  }

  public function to_string()
  {
    $result = '';
    $result .= $this->add_line('event_id', $this->get_event_id());
    $result .= $this->add_line('title', $this->get_title());
    $result .= $this->add_line('link', $this->get_link());
    $result .= $this->add_line('start_date', $this->get_start_date());
    return $result;
  }
  
  public function to_text()
  {
    $result = '';

    $result .= $this->add_line('event_id', $this->get_event_id());
    $result .= $this->add_line('title', $this->get_title());
    $result .= $this->add_line('slug', $this->get_slug());
    $result .= $this->add_line('subtitle', $this->get_subtitle());
    $result .= $this->add_line('link', $this->get_link());
    $result .= $this->add_line('uid', $this->get_uid());
    $result .= $this->add_line('start_date', $this->get_start_date());
    $result .= $this->add_line('end_date', $this->get_end_date());

    foreach ( $this->get_categories() as $cat )
    {
      if( !empty($cat))
      {
        $result .= $this->add_line('category', $cat->to_string());
      }
    }

    foreach ( $this->get_tags() as $tag )
    {
      if( !empty($tag))
      {
        $result .= $this->add_line('tag', $tag->to_string() );
      }
    }

    $result .= $this->add_line('published_date', $this->get_published_date());
    $result .= $this->add_line('updated_date', $this->get_updated_date());
    $location = $this->get_location();
    if(!empty ( $location ))
    {
      $result .= $this->add_line('location_name', $location->get_name());
      $result .= $this->add_line('location_street', $location->get_street());
      $result .= $this->add_line('location_streetnumber', $location->get_streetnumber());
      $result .= $this->add_line('location_zip', $location->get_zip());
      $result .= $this->add_line('location_city', $location->get_city());
      $result .= $this->add_line('location_state', $location->get_state());
      $result .= $this->add_line('location_country_code', $location->get_country_code());
      $result .= $this->add_line('location_lon', $location->get_lon());
      $result .= $this->add_line('location_lat', $location->get_lat());
    }
    $result .= $this->add_line('contact_name', $this->get_contact_name());
    $result .= $this->add_line('contact_email', $this->get_contact_email());
    $result .= $this->add_line('contact_phone', $this->get_contact_phone());
    $result .= $this->add_line('contact_website', $this->get_contact_website());
    $result .= $this->add_line('excerpt', $this->get_excerpt());
    $result .= $this->add_line('description', $this->get_description());
    return $result;
  }

}


}
