<?php
/*
Plugin Name: TaxiFareFinder for WordPress
Plugin URI: http://www.taxifarefinder.com/plugin.php
Description: Taxi Estimates for WordPress. Powered by TaxiFareFinder.
Author: TaxiFareFinder.com, Unleashed, LLC
Author URI: http://www.taxifarefinder.com/
Version: 1.1.1
License: GPLv2
License URI: license.txt
*/

require_once( 'taxifarefinder-widget.php' ); // include the widget


/*****************************************************************
 * Function: tffMenu()
 * Description: Adds TaxiFareFinder to WordPress admin page
 *****************************************************************/
function tffMenu() {
	if ( is_admin() ) {
		register_setting( 'taxifarefinder', 'tffOptions' );
		add_options_page( 'TaxiFareFinder Settings', 'TaxiFareFinder', 'administrator', __FILE__, 'tffOptions', '' );
	}
}


/*****************************************************************
 * Function: tffOptions()
 * Description: Constructs the Options page for TaxiFareFinder Plugin
 * in WordPress Admin
 *****************************************************************/
function tffOptions() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have suffifient permissions to access this page.' ) );
	}

	echo '<div class="wrap">' . screen_icon() . '<h2>TaxiFareFinder for WordPress - Resource Page</h2>
	
	<p><b>Widget title:</b><br />
	This appears at the top of the Widget. This text will be displayed with the h2 tag.</p>

	<p><b>Widget message:</b><br />
	This appears below the Widget title.  It will be displayed with the h3 tag.</p>

	<p><b>TaxiFareFinder logo:</b><br />
	This appears at the bottom of the widget.  Please support us by displaying our logo and linking to us!</p>

	<p><b>Entity handle:</b><br />
	The entity handle determines which taxi fare calculator and taxi rate will be used by TaxiFareFinder. Entity Handle often is associated with a city but can also be a region, school, airport, or taxi company. To look up the Entity Handle for your city, visit <a href="http://www.taxifarefinder.com/plugin.php">TaxiFareFinder WordPress documentation</a>.</p>

	<p><b>Client ID:</b><br />
	This enables custom page at TaxiFareFinder that only lists your taxi company, logo, and/or its dispatch number. You must request and have this be generated by TaxiFareFinder.  For more information, please review the <a href="http://www.taxifarefinder.com/plugin.php">TaxiFareFinder WordPress documentation</a>.</p>
	
	<p><b>TaxiFareFinder API Key:</b><br />
	Enables estimates to be displayed inside the plugin. This must be directly requested from TaxiFareFinder.  For more information, please review the information at <a href="http://www.taxifarefinder.com/developers.php">TaxiFareFinder Developers</a>.</p>

	<p><b>Google Maps API Key:</b><br />
	Required for widget to function. You must enable the Places, Javascript, and Geocoding APIs for this key in your Google API Console. Get your Google Maps API key at https://cloud.google.com/maps-platform/</p>


	<p><b>Default "From" address:</b><br />
	The address you enter here will be prepopulated on forms.  This is an optional field.</p>

	<p><b>Default "To" address:</b><br />
	The address you enter here will be prepopulated on the forms.  This is an optional field.</p>
	
	<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>For the latest documentation, please visit us at <a href="http://www.taxifarefinder.com/plugin.php">TaxiFareFinder for WP Doc</a></p>
	';
}

/*****************************************************************
 * Function: tffAjaxFareEstimate ()
 * Description: Takes in user input from POST. Constructs the
 * call to TaxiFareFinder's fare estimate.
 * Note: This is designed to call for estimate in two different
 * ways.  One is to open a new window to TaxiFareFinder.com
 * with proper parameters.  The other is to construct the call
 * to TaxiFareFinder API.
 *****************************************************************/

function tffAjaxFareEstimate() {

	$fromLatLng      = $_POST['tff_from_address'];
	$toLatLng        = $_POST['tff_to_address'];
	$entityHandle     = $_POST['tff_entity_handle'];
	$apiKey           = $_POST['tff_api_key'];
	$googleMapsApiKey = $_POST['tff_google_maps_api_key'];
	$clientID         = $_POST['tff_client_id'];

	//Create a path for TaxiFareFinder
	$strTFFURL = 'http://www.taxifarefinder.com/main.php?city=' . $entityHandle . '&from=' . $fromLatLng . '&to=' . $toLatLng . '&client_id=' . $clientID;


	//If API key is not specified, open a new window and send to TaxiFareFinder.com for results.
	//If API key is specifeid, pass the parameters to TaxiFareFinder API and process the results through AJAX.
	try {
		if ( $apiKey == '' ) {
			if ( $entityHandle == '' ) {
				echo '<script type="text/javascript" language="javascript">alert("Entity/city is not set. Please contact the webmaster.");</script>';
			} else {
				echo '<script type="text/javascript" language="javascript">window.open("' . $strTFFURL . '", "_blank", "menubar=1,resizable=1,width=1064,height=720");</script>';
			}
		} else {
			$tffapiurl = 'https://api.taxifarefinder.com/fare?key=' . $apiKey . '&entity_handle=' . $entityHandle . '&origin=' . $fromLatLng . '&destination=' . $toLatLng;

			//WP_HTTP class to retrieve JSON from TFF API
			$request  = new WP_Http;
			$response = $request->request( $tffapiurl );
			$json     = $response['body'];

			$result = json_decode( $json );
			$status = $result->status;

			if ( $status == 'OK' ) {
				$totalFare           = $result->total_fare;
				$intialFare          = $result->initial_fare;
				$meteredFare         = $result->metered_fare;
				$tipPercentage       = $result->tip_percentage;
				$locale              = $result->locale;
				$rateArea            = $result->rate_area;
				$totalExtraCharges   = 0;
				$totalFareWithoutTip = 0;

				foreach ( $result->extra_charges as $chgArr ) {
					$totalExtraCharges += $chgArr->charge;
				}

				// Calculate total charge without tip.
				$totalFareWithoutTip = $intialFare + $meteredFare + $totalExtraCharges;

				//Figure out currency symbol from locale.
				$currencySymbol = retrieveCurrencySymbol( $locale );

				echo '<div id="tff_fareresults"><a href="' . $strTFFURL . '" target=_blank">' . $currencySymbol . $totalFareWithoutTip . '</a></div>';
				echo '<div id="tff_rateinfo"><a href="' . $strTFFURL . '" target=_blank">';
				if ( $tipPercentage <> 0 ) {
					echo $currencySymbol . $totalFare . ' incl.' . $tipPercentage . '% tip<br />';
				}

				echo 'Per ' . $rateArea . ' taxi rates</a></div>';
			} else {
				throw new Exception('Error occurred when retrieving fare estimate. Please contact TaxiFareFinder for assistance. Error was ' . $status);
			}
		}
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
	}

	exit();
}

/*****************************************************************
 * Function: retrieveCurrencySymbol ()
 * Description: Takes in locale returned from TFF API, then
 * looks up currency symbol.
 *****************************************************************/

function retrieveCurrencySymbol( $locale ) {

	setlocale( LC_MONETARY, $locale );
	$localeArray          = localeconv();
	$localeCurrencySymbol = $localeArray[ currency_symbol ];

	return $localeCurrencySymbol;
}

function tffInit() {
	add_action( 'admin_menu', 'tffMenu' );

	if ( ! is_admin() ) {
		wp_enqueue_script( 'taxifarefinder', plugins_url( 'taxi-fare-calculator-by-taxifarefindercom/js/taxifarefinder.js' ), array( 'jquery' ) );
		wp_enqueue_style( 'taxifarefinder', plugins_url( 'taxi-fare-calculator-by-taxifarefindercom/css/taxifarefinder.css' ) );
	}
}

function tffAdminInit() {
	wp_enqueue_script( 'taxifarefinder-admin', plugins_url( 'taxi-fare-calculator-by-taxifarefindercom/js/taxifarefinder-admin.js' ), array( 'jquery' ) );
}

function tffHead() {
	if ( ! is_admin() ) {
		echo '<script type="text/javascript">var tffAjaxLink = "' . admin_url( 'admin-ajax.php' ) . '";</script>';
	}
}

register_activation_hook( __FILE__, 'tffActivation' );
add_action( 'init', 'tffInit' );
add_action( 'admin_init', 'tffAdminInit' );
add_action( 'wp_head', 'tffHead' );
add_action( 'wp_ajax_tffAjaxFareEstimate', 'tffAjaxFareEstimate' );
add_action( 'wp_ajax_nopriv_tffAjaxFareEstimate', 'tffAjaxFareEstimate' );
