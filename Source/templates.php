<?php
/**
 * Astro widget templates
 * Last change 2018-04-03
 *
 * @author jpg
 * @link http://jpg.cloud/scripts/astro/
 *
 * Made for Astro widget script
 * Version 1.0 compatible
 */

require_once(dirname(__FILE__) . '/astro.php');

/**
 * Astro widget - large
 *
 * @param  string $address - location to request
 * @param  int $days - show this many days into the future
 * @param  boolean $show_location - show or hide text
 * @param  boolean $show_phase - show or hide the phase column
 * @param  int $lat - latitude if static location is preferred
 * @param  int $lon - longitude if static location is preferred
 * @return void
 */
function astro_widget_large($address, $days = 7, $show_location = true, $show_phase = true, $lat = null, $lon = null)
{
    $location = $address;
// Geocode API parameters
    $geocode_param['address'] = $address;
    //$geocode_param['key'] = 'INSERT YOUR GOOGLE API_KEY HERE';

// Sunrise API parameters
    $sunrise_param['from'] = date('Y-m-d', strtotime('now'));
    $sunrise_param['to'] = date('Y-m-d', time() + ($days * 86400) - 86400);
    //$sunrise_param['date'] = date('Y-m-d', strtotime('now'));
    $sunrise_param['lat'] = $lat;
    $sunrise_param['lon'] = $lon;

// Google Maps: Geocode API request
    if (isset($geocode_param['address']) && !isset($sunrise_param['lat']) && !isset($sunrise_param['lon'])) {
        $geocode = astro_api_geocode($geocode_param);
        $sunrise_param['lat'] = $geocode['lat'];
        $sunrise_param['lon'] = $geocode['lon'];
        $location = $geocode['name'];
    }

// Meteorologisk institutt: Sunrise API request
    $sunrise = astro_api_sunrise($sunrise_param);
// Set location text
    $location_text = (isset($location) && $show_location) ? ' for ' . $location : '';
    ?>
    <div class="astrowidget astrowidgetL">
        <!-- Astro widget by jpg: http://jpg.cloud/scripts/astro/ -->
        <table>
            <tr>
                <th colspan="<?php echo ($show_phase) ? 6 : 5; ?>">
                    <p style="font-size: 150%;">Sol og m&aring;ne<?php echo $location_text; ?></p>
                </th>
            </tr>
            <tr>
                <th>
                    <p><img src="http://jpg.cloud/pic/astro/solarsystem50h.png"
                            style="max-height: 50px; max-width: 128px;"/>
                    </p>
                </th>
                <th colspan="2">
                    <p style="white-space: nowrap;">opp<img src="http://jpg.cloud/pic/astro/sun50h.png"
                                                            style="max-height: 50px;"/>ned</p>
                </th>
                <th colspan="2">
                    <p style="white-space: nowrap;">opp<img src="http://jpg.cloud/pic/astro/moon50h.png"
                                                            style="max-height: 50px;"/>ned</p>
                </th>
                <?php
                if ($show_phase) {
                    ?>
                    <th>
                        <p><img src="http://jpg.cloud/pic/astro/moonphase128w.png"
                                style="max-width: 128px; max-height: 50px;"/></p>
                    </th>
                    <?php
                }
                ?>
            </tr>
            <?php
            foreach ($sunrise as $key => $value) {
                ?>
                <tr>
                    <td>
                        <p style="font-size: 110%; text-align: center; text-wrap: avoid;"><?php echo ucfirst(strftime('%A %e. %b', strtotime($key))); ?></p>
                    </td>
                    <?php
                    if ($value['sun']['never_rise']) {
                        ?>
                        <td colspan="2" style="text-align: center;">
                            <p>nede hele dagen</p>
                        </td><?php
                    } elseif ($value['sun']['never_set'] && !isset($value['sun']['rise'])) {
                        ?>
                        <td colspan="2" style="text-align: center;">
                            <p>midnattssol</p>
                        </td><?php
                    } else {
                        ?>
                        <td>
                            <p><?php echo (isset($value['sun']['rise'])) ? $value['sun']['rise'] : '&nbsp;'; ?></p>
                        </td>
                        <td>
                            <p><?php echo (isset($value['sun']['set'])) ? $value['sun']['set'] : '&nbsp;'; ?></p>
                        </td>
                        <?php
                    }
                    if ($value['moon']['never_rise']) {
                        ?>
                        <td colspan="2" style="text-align: center;">
                            <p>ingen m&aring;ne</p>
                        </td><?php
                    } elseif ($value['moon']['never_set'] && !isset($value['moon']['rise'])) {
                        ?>
                        <td colspan="2" style="text-align: center;">
                            <p>oppe hele tiden</p>
                        </td><?php
                    } else {
                        ?>
                        <td>
                            <p><?php echo (isset($value['moon']['rise'])) ? $value['moon']['rise'] : '&nbsp;'; ?></p>
                        </td>
                        <td>
                            <p><?php echo (isset($value['moon']['set'])) ? $value['moon']['set'] : '&nbsp;'; ?></p>
                        </td>

                        <?php
                    }
                    if ($show_phase) {
                        $previous_phase = (isset($previous_phase)) ? $previous_phase : null;
                        $current_phase = (isset($current_phase)) ? $current_phase : null;
                        foreach ($sunrise as $key2 => $value2) {
                            $some_phase = (isset($value2['moon']['phase_no']) && !$value2['moon']['never_rise']) ? $value2['moon']['phase_no'] : '';
                            if ($key === $key2) {
                                $current_phase = (isset($value['moon']['phase_no']) && !$value['moon']['never_rise']) ? $value['moon']['phase_no'] : '';
                                if ($current_phase != $previous_phase) {
                                    $rowspan_phase = 0;
                                }
                            }
                            if (isset($rowspan_phase) && $current_phase == $some_phase) {
                                $rowspan_phase++;
                            } elseif (isset($rowspan_phase) && $current_phase != $some_phase) {
                                break;
                            }
                        }
                        if (isset($rowspan_phase)) {
                            $previous_phase = $current_phase;
                            ?>
                            <td rowspan="<?php echo $rowspan_phase; ?>" style="text-align: center;">
                                <p><?php echo (isset($value['moon']['phase_no']) && !$value['moon']['never_rise']) ? ucfirst($value['moon']['phase_no']) : '&nbsp;'; ?></p>
                            </td>
                            <?php
                            unset($rowspan_phase);
                        }
                    }
                    ?>
                </tr>
            <?php } ?>
            <tr>
                <th colspan="<?php echo ($show_phase) ? 6 : 5; ?>">
                    <p style="color: gray; font-size: 75%; font-weight: normal;">
                        Astrodata levert av <a href="http://met.no/" style="color: gray; text-decoration: none;"
                                               target="_blank">met.no</a>
                    </p>
                </th>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Astro widget - medium
 *
 * @param  string $address - location to request
 * @param  int $days - show this many days into the future
 * @param  boolean $show_location - show or hide text
 * @param  int $lat - latitude if static location is preferred
 * @param  int $lon - longitude if static location is preferred
 * @return void
 */
function astro_widget_medium($address, $days = 3, $show_location = true, $lat = null, $lon = null)
{
    $location = $address;
// Geocode API parameters
    $geocode_param['address'] = $address;
    //$geocode_param['key'] = 'INSERT YOUR GOOGLE API_KEY HERE';

// Sunrise API parameters
    $sunrise_param['from'] = date('Y-m-d', strtotime('now'));
    $sunrise_param['to'] = date('Y-m-d', time() + ($days * 86400));
    //$sunrise_param['date'] = date('Y-m-d', strtotime('now'));
    $sunrise_param['lat'] = $lat;
    $sunrise_param['lon'] = $lon;

// Google Maps: Geocode API request
    if (isset($geocode_param['address']) && !isset($sunrise_param['lat']) && !isset($sunrise_param['lon'])) {
        $geocode = astro_api_geocode($geocode_param);
        $sunrise_param['lat'] = $geocode['lat'];
        $sunrise_param['lon'] = $geocode['lon'];
        $location = $geocode['name'];
    }

// Meteorologisk institutt: Sunrise API request
    $sunrise = astro_api_sunrise($sunrise_param);

// Set location text
    $location_text = (isset($location) && $show_location) ? ' for ' . $location : '';
    ?>
    <div class="astrowidget astrowidgetM">
        <!-- Astro widget by jpg: http://jpg.cloud/scripts/astro/ -->
        <table>
            <tr>
                <th colspan="4">
                    <p style="font-size: 150%;">Sol og m&aring;ne<?php echo $location_text ?></p>
                </th>
            </tr>
            <?php foreach ($sunrise as $key => $value) { ?>
                <tr>
                    <th colspan="3">


                        <p style="font-size: 110%; text-align: center; vertical-align: text-bottom;"><img
                                src="http://jpg.cloud/pic/astro/sun30h.png"
                                style="max-width: 30px; float: left;"/><?php echo strftime('%A %e. %B', strtotime($key)); ?>
                            <img src="http://jpg.cloud/pic/astro/moon30h.png"
                                 style="max-height: 30px; float: right;"/>
                        </p>
                    </th>
                </tr>
                <tr>
                    <?php
                    if ($value['sun']['never_rise']) {
                        ?>
                        <td rowspan="2" style="text-align: center;">
                            <p>nede hele dagen</p>
                        </td><?php
                    } elseif ($value['sun']['never_set'] && !isset($value['sun']['rise'])) {
                        ?>
                        <td rowspan="2" style="text-align: center;">
                            <p>midnattssol</p>
                        </td><?php
                    } else {
                        $insert_sun_p2 = true;
                        ?>
                        <td style="text-align: center; vertical-align: middle; text-wrap: avoid;">
                            <p>
                                Soloppgang: <?php echo (isset($value['sun']['rise'])) ? $value['sun']['rise'] : '&nbsp;'; ?></p>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="text-align: center; vertical-align: middle;">
                        <img src="http://jpg.cloud/pic/astro/up8w.png" style="max-height: 8px; max-width: 8px;"/>
                    </td>
                    <?php
                    if ($value['moon']['never_rise']) {
                        ?>
                        <td rowspan="2" style="text-align: center;">
                            <p>ingen m&aring;ne</p>
                        </td><?php
                    } elseif ($value['moon']['never_set'] && !isset($value['moon']['rise'])) {
                        ?>
                        <td rowspan="2" style="text-align: center;">
                            <p>oppe hele tiden</p>
                        </td><?php
                    } else {
                        $insert_moon_p2 = true;
                        ?>
                        <td style="text-align: center; vertical-align: middle; text-wrap: avoid;">
                            <p>M&aring;nen
                                opp: <?php echo (isset($value['moon']['rise'])) ? $value['moon']['rise'] : '&nbsp;'; ?></p>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <?php if (isset($insert_sun_p2) && $insert_sun_p2) {
                        ?>
                        <td style="text-align: center; vertical-align: middle; text-wrap: avoid;">
                            <p>
                                Solnedgang: <?php echo (isset($value['sun']['set'])) ? $value['sun']['set'] : '&nbsp;'; ?></p>
                        </td>
                        <?php
                        unset($insert_sun_p2);
                    }
                    ?>
                    <td style="text-align: center; vertical-align: middle;">
                        <img src="http://jpg.cloud/pic/astro/down8w.png" style="max-height: 8px; max-width: 8px;"/>
                    </td>
                    <?php
                    if (isset($insert_moon_p2) && $insert_moon_p2) {
                        ?>
                        <td style="text-align: center; vertical-align: middle; text-wrap: avoid;">
                            <p>M&aring;nen
                                ned: <?php echo (isset($value['moon']['set'])) ? $value['moon']['set'] : '&nbsp;'; ?></p>
                        </td>
                        <?php
                        unset($insert_moon_p2);
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
            <tr>
                <th colspan="4">
                    <p style="color: gray; font-size: 75%; font-weight: normal;">
                        Astrodata levert av <a href="http://met.no/" style="color: gray; text-decoration: none;"
                                               target="_blank">met.no</a>
                    </p>
                </th>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Astro widget - small
 *
 * @param  string $address - location to request
 * @param  boolean $show_location - show or hide text
 * @param  int $lat - latitude if static location is preferred
 * @param  int $lon - longitude if static location is preferred
 * @return void
 */
function astro_widget_small($address, $show_location = true, $lat = null, $lon = null)
{
    $location = $address;
// Geocode API parameters
    $geocode_param['address'] = $address;
    //$geocode_param['key'] = 'INSERT YOUR GOOGLE API_KEY HERE';

// Sunrise API parameters
    $sunrise_param['date'] = date('Y-m-d', strtotime('now'));
    //$sunrise_param['from'] = date('Y-m-d', strtotime('now'));
    //$sunrise_param['to'] = date('Y-m-d', strtotime('now'));
    $sunrise_param['lat'] = $lat;
    $sunrise_param['lon'] = $lon;

// Google Maps: Geocode API request
    if (isset($geocode_param['address']) && !isset($sunrise_param['lat']) && !isset($sunrise_param['lon'])) {
        $geocode = astro_api_geocode($geocode_param);
        $sunrise_param['lat'] = $geocode['lat'];
        $sunrise_param['lon'] = $geocode['lon'];
        $location = $geocode['name'];
    }

// Meteorologisk institutt: Sunrise API request
    $sunrise = astro_api_sunrise($sunrise_param);

// Set location text
    $location_text = (isset($location) && $show_location) ? $location . ' - ' : '';
    ?>
    <div class="astrowidget astrowidgetS">
        <!-- Astro widget by jpg: http://jpg.cloud/scripts/astro/ -->
        <table>
            <tr>
                <th colspan="5">
                    <p style="font-size: 150%;">Sol og m&aring;ne</p>
                </th>
            </tr>
            <?php foreach ($sunrise as $key => $value) { ?>
                <tr>
                    <th colspan="5">
                        <p style="font-size: 110%;  font-weight: normal; text-align: center;"><?php echo $location_text . strftime('%A', strtotime($key)); ?></p>
                    </th>
                </tr>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">
                        <img src="http://jpg.cloud/pic/astro/sun50h.png"
                             style="max-height: 50px;"/>
                    </th>
                    <?php
                    if ($value['sun']['never_rise']) {
                        ?>
                        <td rowspan="2" style="text-align: center;">
                            <p>nede hele dagen</p>
                        </td><?php
                    } elseif ($value['sun']['never_set'] && !isset($value['sun']['rise'])) {
                        ?>
                        <td rowspan="2" style="text-align: center;">
                            <p>midnattssol</p>
                        </td><?php
                    } else {
                        $insert_sun_p2 = true;
                        ?>
                        <td style="text-align: center; vertical-align: middle;">
                            <p><?php echo (isset($value['sun']['rise'])) ? $value['sun']['rise'] : '&nbsp;'; ?></p>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="text-align: center; vertical-align: middle;">
                        <img src="http://jpg.cloud/pic/astro/up8w.png" style="max-height: 8px; max-width: 8px;"/>
                    </td>
                    <?php
                    if ($value['moon']['never_rise']) {
                        ?>
                        <td rowspan="2" style="text-align: center;">
                            <p>ingen m&aring;ne</p>
                        </td><?php
                    } elseif ($value['moon']['never_set'] && !isset($value['moon']['rise'])) {
                        ?>
                        <td rowspan="2" style="text-align: center;">
                            <p>oppe hele tiden</p>
                        </td><?php
                    } else {
                        $insert_moon_p2 = true;
                        ?>
                        <td style="text-align: center; vertical-align: middle;">
                            <p><?php echo (isset($value['moon']['rise'])) ? $value['moon']['rise'] : '&nbsp;'; ?></p>
                        </td>
                        <?php
                    }
                    ?>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">
                        <img src="http://jpg.cloud/pic/astro/moon50h.png"
                             style="max-height: 50px;"/>
                    </th>
                </tr>
                <?php
                if (isset($insert_sun_p2) || isset($insert_moon_p2)) {
                    ?>
                    <tr>
                        <?php if (isset($insert_sun_p2) && $insert_sun_p2) {
                            ?>
                            <td style="text-align: center; vertical-align: middle;">
                                <p><?php echo (isset($value['sun']['set'])) ? $value['sun']['set'] : '&nbsp;'; ?></p>
                            </td>
                            <?php
                            unset($insert_sun_p2);
                        }
                        ?>
                        <td style="text-align: center; vertical-align: middle;">
                            <img src="http://jpg.cloud/pic/astro/down8w.png"
                                 style="max-height: 8px; max-width: 8px;"/>
                        </td>
                        <?php
                        if (isset($insert_moon_p2) && $insert_moon_p2) {
                            ?>
                            <td style="text-align: center; vertical-align: middle;">
                                <p><?php echo (isset($value['moon']['set'])) ? $value['moon']['set'] : '&nbsp;'; ?></p>
                            </td>
                            <?php
                            unset($insert_moon_p2);
                        }
                        ?>
                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <th colspan="5">
                    <p style="color: gray; font-size: 75%; font-weight: normal;">
                        Astrodata levert av <a href="http://met.no/" style="color: gray; text-decoration: none;"
                                               target="_blank">met.no</a>
                    </p>
                </th>
            </tr>
        </table>
    </div>
    <?php
}
