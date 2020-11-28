# WP Events Interface

WP Events Interface is a Wordpress Plugin that converts the common used Events Calendars in Wordpress to a Standard Interface, which can be used by other Plugins, which want to deal with events in Wordpress.

The Plugin depends on the ["WP Libraries"](https://github.com/kartevonmorgen/wp-libraries) - Wordpress Plugin which need to be installed and activated first.

On the "WP Events Interface" Page some default settings can be entered.
* Calendar plugin: All the available and activated Calendars (and supported by Events Interface) are listed here. You can select one which "Events Interface" will use. The following calendars are supported for now:
  * The Events Manager.
  * All in One Events Calendar (only loading, saving is not supported at the Moment).
  * The Events Calendar (only loading, saving is not supported at the Moment).
* Timerange to select in days: How many days (from now) into the future should be selected when we are loading events.
* Category (slug) to select: Filter on specific category by the slug name.

The WP Events Interface can be used to load and save events into the selecte Event Calendar plugin.

In PHP the following Interface can be used to load and save events.

Loading:
```php
$eiInterface = EIInterface::get_instance();
$eiEvents = $eiInterface->get_events_by_cat();
```

Saving:
```php
$eiInterface = EIInterface::get_instance();
$eiInterface->save_event($eiEvent);
```
