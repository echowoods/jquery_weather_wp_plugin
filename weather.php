<?php
/*
Plugin Name: jQuery Weather
Description: Get the weather of you favorite city!
Version: 0.1
Author: Brian Sanders
*/

function add_jquery(){
  wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'add_jquery');


function show_weather() {
  $key = get_option('brianweather-setting');
  $state = get_option('brianweather-setting-2');
  $city = get_option('brianweather-setting-3');
  echo '<script>
jQuery(document).ready(function($) {
      $.ajax({
      url : "http://api.wunderground.com/api/' . $key. '/geolookup/conditions/forecast/q/' . $state . '/' . $city . '.json",
      dataType : "jsonp",
      success : function(parsed_json) {
      var location = parsed_json.location.city;
      var temp_f = parsed_json.current_observation.temp_f;
      var heat_index_f = parsed_json.current_observation.heat_index_f;
      var forecast = parsed_json.forecast.simpleforecast.forecastday[0].conditions;

      var saying = "";
      if(temp_f > 80){
        saying = "damn its hot out!"
      }
      else {
        saying = "i wish it was warmer"
      }
      $("#temp").html("Current temperature in " + location + " is: " + temp_f + " " + saying);
      $("#heat").html("Current heat index is " + heat_index_f);
      
      $("#weather").html("The weather outside tomorrow is " + forecast);
        } 
      });
    });
  </script>';
}

add_action('wp_head', 'show_weather');



function weather( $atts) {
  return '<div id="temp"></div>
    <div id="heat"></div>
    <div id="weather"></div>';
}
add_shortcode( 'weather', 'weather' );

// Admin Panel
function brianweather_admin_menu() {
    add_menu_page( 'brianweather Plugin', 'brianweather Plugin', 'manage_options', 'brianweather-plugin', 'brianweather_options_page' ); //the callback below creates the actual html structure for your options section
}
add_action( 'admin_menu', 'brianweather_admin_menu' ); //this sets it to happen as the admin menu is being built

function brianweather_admin_init() {
    register_setting( 'brianweather-settings-group', 'brianweather-setting' );
    register_setting( 'brianweather-settings-group', 'brianweather-setting-2' );
    register_setting( 'brianweather-settings-group', 'brianweather-setting-3');
    add_settings_section( 'section-one', 'Section One', 'brianweather_section_one_callback', 'brianweather-plugin' ); //the callback below will create the html label for your page
    add_settings_field( 'field-one', 'Developer Key:', 'brianweather_field_one_callback', 'brianweather-plugin', 'section-one' ); //the callback below will create the actual html input
    add_settings_field( 'field-two', 'State:', 'brianweather_field_two_callback', 'brianweather-plugin', 'section-one' ); //the callback below will create the actual html input
    add_settings_field( 'field-three', 'City:', 'brianweather_field_three_callback', 'brianweather-plugin', 'section-one' ); //the callback below will create the actual html input
}
add_action( 'admin_init', 'brianweather_admin_init' ); //this sets it to happen as the text is being brought in for the admin page

function brianweather_section_one_callback() {
    echo "Some help text goes here."; //you'll see this in the admin, this is called in response to add_setting_section() in the above function 
}

function brianweather_field_one_callback() {
  //echo "hi";
    $setting = esc_attr( get_option( 'brianweather-setting' ) );
    echo "<input type='text' name='brianweather-setting' value='$setting' />"; //this is the input you'll use to store user settings; this is called in response to add_settings_field() above
}

function brianweather_field_two_callback() {
  //echo "hi";
    $setting2 = esc_attr( get_option( 'brianweather-setting-2' ) );
    echo "<input type='text' name='brianweather-setting-2' value='$setting2' />"; //this is the input you'll use to store user settings; this is called in response to add_settings_field() above
}

function brianweather_field_three_callback() {
  //echo "hi";
    $setting3 = esc_attr( get_option( 'brianweather-setting-3' ) );
    echo "<input type='text' name='brianweather-setting-3' value='$setting3' />"; //this is the input you'll use to store user settings; this is called in response to add_settings_field() above
}

function brianweather_options_page() { //creates the html structure for your menu
    ?>
    <div class="wrap">
        <h2>brianweather Plugin Options</h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'brianweather-settings-group' ); ?>
            <?php do_settings_sections( 'brianweather-plugin' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
?>
