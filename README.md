Astro script and widgets for met.no.

This is a script made for norwegian users, wanting a norwegian astro script on their webpage or blog.

LIVE DEMO: http://jpg.priv.no/scripts/astro/

Basic usage:
````
require_once('/astro.php');
require_once('/templates.php');
$location = 'Oslo, Norway';
astro_widget_small($location);
````

If you prefer cordinates instead of names, you can use that too.

At the moment, there is 3 sizes available
- Small widget, for today only.
- Medium widget, list style, defaults to 4 days.
- Large widget, table style, defaults to 7 days.

Functions
- Astro data from Yr.no
- Sunrise and sunset
- Moon rise, set and phase
- Choose location by searching Google Maps
- Custom location with cordinates
- Custom number of days
- Show or hide the moon phase
- Show or hide the location in the title bar
- 3 templates
- Make your own with ease, by re-using the back-end code

Get it with Composer:
Put this into your composer.json file
````
{
"require": {
"JanPetterMG/astro-script-met.no": "dev-master",
  }
}
````
