<?php

if ( ! class_exists( 'EIInterface' ) ) 
{

/** 
 * This Interface Class is the main point which other plugins can 
 * use to get and save events into the native Event Calendar.
 * 
 * The EIInterface is an Singelton, so there can be only one instance
 * in a Wordpress Session.
 * It can be used for example in this way.
 * 
 * $eiInterface = EIInterface::get_instance();
 * $eiInterface->get_events_by_cat();
 *
 * @author   Sjoerd Takken
 * @copyright  No Copyright.
 * @license    GNU/GPLv2, see https://www.gnu.org/licenses/gpl-2.0.html
 */
class EIInterface 
{
  private static $instance = null;

  private function __construct() 
  {
  }

  /** 
   * The object is created from within the class itself
   * only if the class has no instance.
   */
  public static function get_instance()
  {
    if (self::$instance == null)
    {
      self::$instance = new EIInterface();
    }
    return self::$instance;
  }

  public function get_event_calendar_plugin() 
  {
    return get_option( 'ei_event_calendar' );
  }

  public function save_event_calendar_plugin( $plugin ) 
  {
    update_option( 'ei_event_calendar', $plugin );
  }

  /**
   * Return all the available calendar plugins
   * -- all the calendar plugins which are activated --
   *
   * @return EICalendarFeed[]
   */
  public function get_available_calendar_feeds() 
  {
    $eiCalFactory = EICalendarFeedFactory::get_instance();
    $available_feeds = $eiCalFactory->get_feeds();
    return $available_feeds;
  }

  /**
   * Return the activated calendar.
   * If no activated Calendar is set (option event_calendar_plugin)
   * we return just the first Calendar Plugin that we find, because
   * most of the time there is just one calendar plugin installed.
   *
   * @return EICalendarFeed
   */
  public function get_event_calendar_feed() 
  {
    $available_calendar_feeds = $this->get_available_calendar_feeds();
    $event_calendar = $this->get_event_calendar_plugin();
    if(empty($event_calendar))
    {
      // If no event_calendar is set, we just take the first one
      if(!empty( $available_calendar_feeds ))
      {
        return reset($available_calendar_feeds);
      }
    }

    foreach($available_calendar_feeds as $feed)
    {
      if($feed->get_identifier() == $event_calendar)
      {
        return $feed;
      }
    }

    // The identifier $event_calendar is invalid,
    // so we set it to an empty string
    $this->save_event_calendar_plugin('');
    return null;
  }

  public function get_event_category() 
  {
    return get_option( 'ei_event_category' );
  }

  public function save_event_category( $event_category ) 
  {
    update_option( 'ei_event_category', $event_category );
  }

  public function get_future_events_to_use() 
  {
    return get_option( 'ei_events_future_in_days', 30 );
  }

  public function save_future_events_to_use( $future_events_in_days ) 
  {
    update_option( 'ei_events_future_in_days', $future_events_in_days );
  }

  /** 
   * Add a listener when an event is saved for the
   * activated Calendar
   *
   * @param listener EIEventSavedListenerIF
   */
  public function add_event_saved_listener($listener)
  {
    $feed = $this->get_event_calendar_feed();
    if(empty($feed))
    {
      return null;
    }
    $feed->add_event_saved_listener($listener);
  }

  /** 
   * Get a EICalendarEvent from the activated Calendar
   * selected by the $event_id from this activated Calendar
   * @param event_id int
   */
  public function get_event_by_event_id($event_id) 
  {
    $feed = $this->get_event_calendar_feed();
    if(empty($feed))
    {
      return null;
    }
    return $feed->get_event_by_event_id( $event_id);
  }

  /** 
   * Retrieve EICalendarEvents from the activated Calendar
   *
   * @param event_cat String: if this property is not defined, then
   *                          the 'Event Category to Select' option
   *                          from the admin area will be used. If this 
   *                          option is not filled then all events till
   *                          'future_in_days' will be returned
   * @param future_in_days int: if this property is not defined, then
   *                            the 'Future Events to Use' option from
   *                            the admin area will be used. If this 
   *                            option is not then all events for 30 days
   *                            will be returned.
   * @return EICalendarEvent[]
   */
  public function get_events_by_cat($event_cat = NULL, 
                                    $future_in_days = null) 
  {
    if(empty($future_in_days))
    {
      $future_in_days = $this->get_future_events_to_use();
    }

    if(empty($event_cat))
    {
      $event_cat = $this->get_event_category();
    }
    
    $start_date = strtotime( current_time( 'Y-m-d' ) . ' 00:00:00' );
    $end_date = $start_date + ( 86400 * ( $future_in_days + 1 ) );

    $feed = $this->get_event_calendar_feed();
    if(empty($feed))
    {
      return array();
    }
    $events =  $feed->get_events( $start_date, $end_date, $event_cat);
    return $events;
  }

  /** 
   * Saves an EICalendarEvent in the activated Calendar
   * A result will be returned of EICalendarEventSaveResult
   * it gives back the event_id or any error that occurs.
   *
   * @param eiEvent: EICalendarEvent
   * @return EICalendrEventSaveResult
   */
  public function save_event($eiEvent)
  {
    $feed = $this->get_event_calendar_feed();
    if(empty($feed))
    {
      $result = new EICalendarEventSaveResult();
      $result->set_error('No Calendar feed found, there are is no Event Calendar installed');
      return $result;
    }
    return $feed->save_event( $eiEvent);
  }
}

}
