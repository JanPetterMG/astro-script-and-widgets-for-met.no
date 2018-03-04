#Astro script and widgets for Yr.no.#

[![Join the chat at https://gitter.im/JanPetterMG/astro-script-yr-no](https://badges.gitter.im/JanPetterMG/astro-script-yr-no.svg)](https://gitter.im/JanPetterMG/astro-script-yr-no?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

This is a script made for norwegian users, wanting a norwegian astro script on their webpage or blog.

**LIVE DEMO: https://jpg.cloud/scripts/astro/**

#####Basic usage:#####
````
require_once('/source/templates.php');

$location = 'Oslo, Norway';

// Choose your variant:
astro_widget_small($location);
astro_widget_medium($location);
astro_widget_large($location);
````

#####3 premade sizes available#####
- Small widget, sun and moon today
- Medium widget, list style, defaults to 4 days.
- Large widget, table style, defaults to 7 days.

#####Functions#####
- Astro data from Yr.no
- Sunrise and sunset
- Moon rise, set and phase
- Choose location by searching Google Maps
- Custom location with cordinates
- Custom number of days
- Show or hide the moon phase
- Show or hide the location in the title bar
- 3 premade templates
- Build your own with ease

####Download with Composer:####
Put this into your composer.json file
````
{
  "require": {
    "JanPetterMG/astro-script-met.no": "dev-master"
  }
}
````
