<?php
/*****************************************************************
 *
 * TaxiFareFinder for WordPress Widget
 *
 *****************************************************************/

class TaxiFareFinder_Widget extends WP_Widget {

	function TaxiFareFinder_Widget() {

		$widget_ops = array(
			'description' => 'TaxiFareFinder Widget for Fares Estimates'
		);

		$this->WP_Widget(
			'taxifarefinder',
			'TaxiFareFinder Widget',
			$widget_ops
		);

	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$widget_title            = $instance['widget_title'];
		$widget_message          = empty( $instance['widget_message'] ) ? '' : '<p>' . $instance['widget_message'] . '</p>';
		$logo_selection          = $instance['logo_selection'];
		$entity_selection        = $instance['entity_selection'];
		$tff_api_key             = $instance['tff_api_key'];
		$tff_google_maps_api_key = $instance['tff_google_maps_api_key'];
		$client_id               = $instance['client_id'];
		$from_default            = $instance['from_default'];
		$to_default              = $instance['to_default'];
		$widget_id               = $args['widget_id'];
		$loading_gif_url         = plugins_url( 'taxi-fare-calculator-by-taxifarefindercom/images/loading.gif' );

		echo <<<EOD
		<div id="$widget_id" class="widget taxifarefinder-widget">
		    <h2 class="widgettitle">$widget_title</h2>
            $widget_message
	        <div class="taxifarefinder_widget">
		        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places,adsense,geometry,geocoding&key=$tff_google_maps_api_key"></script>
		        <div id="tff-widget">
		            <div id="tff_formfields">
		                <label for="tff_from_address">From:</label><br />
		                <input type="text" name="tff_from_address" id="tff_from_address" value="$from_default"/>
		                <label for="tff_to_address">To:</label><br />
			            <input type="text" name="tff_to_address" id="tff_to_address" value="$to_default"/>
			        </div>
					<p>
						<input type="hidden" name="tff_entity_handle" id="tff_entity_handle" value="$entity_selection" />
						<input type="hidden" name="tff_api_key" id="tff_api_key" value="$tff_api_key" />
						<input type="hidden" name="tff_google_maps_api_key" id="tff_google_maps_api_key" value="$tff_google_maps_api_key" />
						<input type="hidden" name="tff_client_id" id="tff_client_id" value="$client_id" />
					</p>
		            <div id="tff_button">
			            <input type="button" value="Estimate Fare" name="tff_load" id="tff_load" />
				        <br/><img src="$loading_gif_url" alt="" id="tff_loading" />
		            </div>
					<p id="tff_results"></p>
					<div id="tff_disclaimer">
					    Estimates provided by <a href="http://www.taxifarefinder.com/" target="_blank">TaxiFareFinder</a>. <br />For reference only.
					</div>
					<script type="text/javascript">var tffData = { city: "$entity_selection"};</script>
		        </div>
            </div>
EOD;

		if ( $logo_selection != 'Select One' ) {
			$image_url = 'taxi-fare-calculator-by-taxifarefindercom/images/' . $logo_selection . '.png';

			echo '<div id="tff_logo"><a href="http://www.taxifarefinder.com/" target="_blank"><img src="' . plugins_url( $image_url ) . '"/></a></div>';
		}

		echo '</div>'; // close widget

	}

	/*****************************************************************
	 * Function: retrieveLogoOptions()
	 * Description: Creates an array of logos available.
	 *****************************************************************/

	function retrieveLogoOptions( $logoSelected ) {

		$logoOptionsArray[0]  = 'Select One';
		$logoOptionsArray[1]  = 'tfflogo90';
		$logoOptionsArray[2]  = 'tffpower-rectblack150x25';
		$logoOptionsArray[3]  = 'tffpower-rectblack300x50';
		$logoOptionsArray[4]  = 'tffpower-rectwhite150x25';
		$logoOptionsArray[5]  = 'tffpower-rectwhite300x50';
		$logoOptionsArray[6]  = 'tffpower-rectyellow150x25';
		$logoOptionsArray[7]  = 'tffpower-rectyellow300x50';
		$logoOptionsArray[8]  = 'tffpower-boxblack90x90';
		$logoOptionsArray[9]  = 'tffpower-boxblack130x130';
		$logoOptionsArray[10] = 'tffpower-boxwhite90x90';
		$logoOptionsArray[11] = 'tffpower-boxwhite130x130';
		$logoOptionsArray[12] = 'tffpower-boxyellow90x90';
		$logoOptionsArray[13] = 'tffpower-boxyellow130x130';

		$optionsArray = array();
		foreach ( $logoOptionsArray as $key => $value ) {
			$optionsArray[] = '<option value="' . trim( $value ) . '"' .
			                  ( strcasecmp( trim( $value ), trim( $logoSelected ) ) == 0 ? ' selected="selected"' : '' ) . '>' .
			                  trim( $value ) . '</option>';
		}

		return implode( "\n", $optionsArray );
	}


	function update( $new_instance, $old_instance ) {
		$instance     = $old_instance;
		$new_instance = (array) $new_instance;

		$instance['widget_title']            = ( ! empty( $new_instance['widget_title'] ) ? strip_tags( $new_instance['widget_title'] ) : '' );
		$instance['widget_message']          = $new_instance['widget_message'];
		$instance['logo_selection']          = trim( ! empty( $new_instance['logo_selection'] ) ? strip_tags( $new_instance['logo_selection'] ) : '' );
		$instance['entity_selection']        = trim( ! empty( $new_instance['entity_selection'] ) ? strip_tags( $new_instance['entity_selection'] ) : '' );
		$instance['tff_api_key']             = trim( ! empty( $new_instance['tff_api_key'] ) ? strip_tags( $new_instance['tff_api_key'] ) : '' );
		$instance['tff_google_maps_api_key'] = trim( ! empty( $new_instance['tff_google_maps_api_key'] ) ? strip_tags( $new_instance['tff_google_maps_api_key'] ) : '' );
		$instance['client_id']               = trim( ! empty( $new_instance['client_id'] ) ? strip_tags( $new_instance['client_id'] ) : '' );
		$instance['from_default']            = trim( ! empty( $new_instance['from_default'] ) ? strip_tags( $new_instance['from_default'] ) : '' );
		$instance['to_default']              = trim( ! empty( $new_instance['to_default'] ) ? strip_tags( $new_instance['to_default'] ) : '' );

		return $instance;
	}


	/*****************************************************************
	 * Function: form()
	 * Description: Creates the form for Widget Setup page in
	 * WordPreess admin console. This form is used by admins
	 * to set widget configurations.
	 *****************************************************************/

	function form( $instance ) {

		// Defaults
		$defaults = array(
			'widget_title'            => 'Taxi Fare Estimator',
			'widget_message'          => 'Estimates by <a href="https://www.taxifarefinder.com">TaxiFareFinder.com</a>',
			'logo_selection'          => 'Select One',
			'entity_handle'           => 'Boston',
			'tff_api_key'             => '',
			'tff_google_maps_api_key' => '',
			'client_id'               => '',
			'from_default'            => '',
			'to_default'              => ''
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		echo '<p><label for="'
		     . $this->get_field_id( 'widget_title' )
		     . '">Widget title: <span title="Appears at the top of the widget.  Visible to users." class="wp_help_hover">[?]</span></label>
		<input size="20" type="text" id="'
		     . $this->get_field_id( 'widget_title' )
		     . '" name="' . $this->get_field_name( 'widget_title' ) . '" value="' . $instance['widget_title'] . '" />
		</p>';

		echo '<p>
		<label for="' .
		     $this->get_field_id( 'widget_message' ) .
		     '">Widget message: <span title="Appears at the top of the widget.  Visible to users." class="wp_help_hover">[?]</span></label>
		<textarea rows="1" cols="40" id="' .
		     $this->get_field_id( 'widget_message' ) .
		     '" name="' .
		     $this->get_field_name( 'widget_message' ) .
		     '">' . $instance['widget_message'] . '</textarea>
		</p>';

		// Logo selection
		$logoOptions = $this->retrieveLogoOptions( $instance['logo_selection'] );
		echo '<p>TaxiFareFinder logo: <span title="Determines which TaxiFareFinder logo will be displayed. Thank you for supporting us." class="wp_help_hover">[?]</span><br />
		<select id="' . $this->get_field_id( 'logo_selection' ) . '" name="' . $this->get_field_name( 'logo_selection' ) .
		     '">' . $logoOptions . '</select></p>';

		// Entity handle text field
		echo '<p>
		<label for="' .
		     $this->get_field_id( 'entity_selection' ) .
		     '">Entity Handle (required):  <span title="Determines which taxi fare calculator and taxi rate will be used. Click on Look up for a listing of entity handles available to you.." class="wp_help_hover">[?]</span> (<a href="http://www.taxifarefinder.com/plugin.php" target="_blank">Look up</a>)</label>
		<input type="text" id="' .
		     $this->get_field_id( 'entity_selection' ) .
		     '" name="' .
		     $this->get_field_name( 'entity_selection' ) .
		     '" value="' .
		     $instance['entity_selection'] . '" />
		</p>';

		// Label
		echo '<p><center><b>---- Optional parameters ----</b></center></p>';

		// From address text field
		echo '<p>
		<label for="' .
		     $this->get_field_id( 'from_default' ) .
		     '">Default "From" address: <span title="Pre-populates the From field. It must include city, state. (e.g. 1 Main Street, Boston, MA)" class="wp_help_hover">[?]</span></label>
		<input type="text" id="' .
		     $this->get_field_id( 'from_default' ) .
		     '" name="' .
		     $this->get_field_name( 'from_default' ) .
		     '" value="' .
		     $instance['from_default'] . '" />
		</p>';

		// To address text field
		echo '<p>
		<label for="' .
		     $this->get_field_id( 'to_default' ) .
		     '">Default "To" address: <span title="Pre-populates the To field. It must include city, state. (e.g. 1 Main Street, Boston, MA)" class="wp_help_hover">[?]</span></label>
		<input type="text" id="' .
		     $this->get_field_id( 'to_default' ) .
		     '" name="' .
		     $this->get_field_name( 'to_default' ) .
		     '" value="' .
		     $instance['to_default'] . '" />
		</p>';

		// Client ID text field
		echo '<p>
		<label for="' .
		     $this->get_field_id( 'client_id' ) .
		     '">Your client ID (optional): <span title="Enables custom TaxiFareFinder pages that only lists your taxi company. Click on info for more information. " class="wp_help_hover">[?]</span> (<a href="http://www.taxifarefinder.com/plugin.php" target="_blank">info</a>)</label>
		<input type="text" id="' .
		     $this->get_field_id( 'client_id' ) .
		     '" name="' .
		     $this->get_field_name( 'client_id' ) .
		     '" value="' .
		     $instance['client_id'] . '" />
		</p>';

		// API Key text field.
		echo '<p>
		<label for="' .
		     $this->get_field_id( 'tff_api_key' ) .
		     '">Your TaxiFareFinder API key (optional): <span title="Enables estimates to be displayed inside the plugin. TaxiFareFinder API license key is required. Click on info for more information." class="wp_help_hover">[?]</span> (<a href="http://www.taxifarefinder.com/plugin.php" target="_blank">info</a>)</label>
		<input type="text" id="' .
		     $this->get_field_id( 'tff_api_key' ) .
		     '" name="' .
		     $this->get_field_name( 'tff_api_key' ) .
		     '" value="' .
		     $instance['tff_api_key'] . '" />
		</p>';

		// Google Maps API Key text field.
		echo '<p>
		<label for="' .
		     $this->get_field_id( 'tff_google_maps_api_key' ) .
		     '">Google Maps API key: <span title="Required for widget to function. You must enable the Places, Javascript, and Geocoding APIs for this key in your Google API Console." class="wp_help_hover">[?]</span></label>
		<input type="text" id="' .
		     $this->get_field_id( 'tff_google_maps_api_key' ) .
		     '" name="' .
		     $this->get_field_name( 'tff_google_maps_api_key' ) .
		     '" value="' .
		     $instance['tff_google_maps_api_key'] . '" />
		</p>';

	}
}


add_action( 'widgets_init', 'tffRegisterWidget' );

function tffRegisterWidget() {
	register_widget( 'TaxiFareFinder_Widget' );
}

?>
