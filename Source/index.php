<?php
/**
 * Astro widget front-end sample
 *
 * @author jpg
 * @link http://jpg.cloud/scripts/astro/
 *
 * Made for Astro widget templates
 * Requires Astro widget script
 */

require_once(dirname(__FILE__) . '/astro.php');
require_once(dirname(__FILE__) . '/templates.php');

// Options and variables
date_default_timezone_set('Europe/Oslo');
setlocale(LC_TIME, 'nb_NO.UTF-8');

$location = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_ENCODED);
if (empty($location)) {
    $location = urlencode('Oslo');
}

$geocode_param['address'] = $location;
$geocode = astro_api_geocode($geocode_param);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <title>Sol og m&aring;ne for <?php echo $geocode['name']; ?></title>
    <meta charset="utf-8">
    <style>
        .astrowidget table {
            border-collapse: collapse;
        }

        .astrowidget p {
            margin: 5px 10px 5px 10px;
        }

        .astrowidgetL td {
            border: 1px solid lightgray;
        }
    </style>
</head>
<body>
<h1>Astro widgets</h1>

<form action="" method="get">
    <label for="q">Sted:
        <input type="text" name="q" value="<?php echo urldecode($location); ?>">
    </label>
    <input type="submit" value="Vis">
</form>

<br><br>

<div>
    <?php
    astro_widget_small($location);
    ?>
</div>

<br><br>

<div>
    <?php
    astro_widget_medium($location, 3);
    ?>
</div>

<br><br>

<div>
    <?php
    astro_widget_large($location, 7);
    ?>
</div>
</body>
</html>
