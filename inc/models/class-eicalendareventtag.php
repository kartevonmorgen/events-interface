<?php
if ( ! class_exists( 'EICalendarEventTag' ) ) 
{

/**
  * EICalendarEventTag
  * EICalendarEventTag Objects contains Events Tags 
  * and are used to get a standard event tag format which 
  * is undependend of the event calendar that is used.
  * 
  * @author     Sjoerd Takken
  * @copyright  No Copyright.
  * @license    GNU/GPLv2, see https://www.gnu.org/licenses/gpl-2.0.html
  */
class EICalendarEventTag 
{
  private $_slug;
  private $_name;
  
  public function __construct($name, $slug='') 
  {
		$this->_name = $name;
    if(empty($slug))
    {
		  $this->_slug = sanitize_title_with_dashes($name);
    }
    else
    {
      $this->_slug = $slug;
    }
  }

  public static function create_tags($terms)
  {
    $eiTags = array();

    if(empty($terms))
    {
      return $eiTags;
    }

    foreach($terms as $term)
    {
      array_push($eiTags, 
                 new EICalendarEventTag($term->name, $term->slug));
    }
    return $eiTags;
  }

	public function set_name( $name ) 
  {
		$this->_name = $name;
	}

	public function get_name() 
  {
		return $this->_name;
	}

	public function set_slug( $slug ) 
  {
		$this->_slug = $slug;
	}

	public function get_slug() 
  {
		return $this->_slug;
	}

  public function to_string()
  {
    return ''. $this->_name.' (' . $this->_slug . ')';
  }
}

}
