﻿=== Taxi Fare Calculator - TaxiFareFinder for WordPress ===
Contributors: ipptak, cjh79
Donate Link: http://www.taxifarefinder.com
Tags: TaxiFarefinder, Taxi Fare Finder, taxi, fare, cab, taxicab, estimator, calculator
Requires at least: 3.2.1
Tested up to: 5.1
Requires PHP: 5.4
Stable tag: trunk
License: GPLv2

Offer taxi fare calculator on your website. Results provided by TaxiFareFinder.com.

== Description ==
This plugin/widget enables a Taxi Fare Calculator on your WordPress-based website.  It provides two text fields where users would enter "To" and "From" locations and, upon submission, are shown a taxi fare estimate.  The fares are provided by TaxiFareFinder.com and similar results are offered.

The WordPress administrator would set up the widget by specifying a city (and hence the taxi rates) and modifying various options.

The results can be displayed in a separate browser window or within the widget box, depending on whether the administrator provide a valid "API Key". A key can be requested on our [Plugin Page](http://www.taxifarefinder.com/plugin.php).

Features:

* Allows your users to estimate taxi fares by providing "To" and "From" addresses.

* Choose from over 600 localities in taxi rates.  "US Average" and "Canada Average" are also available.

* Utilizes calculations and rates from the trusted TaxiFareFinder.com

* AutoComplete functionality for addresses. (Utilizing Google Maps API)

Disclaimers:

* The route and fare calculations provided on this site are estimates only.  The fares are based on the published rates provided by the respective municipalities for travel within city limits. Construction, traffic, weather, recent rate increases and other unforeseeable events may impact or alter the fare. TaxiFareFinder does not guarantee the accuracy of the information provided. 

* The admin installing this plugin should try his or her best to communicate the disclaimer to the end users.

* By default, this plugin opens the results in a separate browser window pointing to TaxiFareFinder.com, while sending information such as "To" and "From".

* The actual calculation is being performed on TaxiFareFinder.com's servers; therefore, information such as "To", "From", and "Entity Handle" (i.e. city) are sent to TaxiFareFinder.com.  

* Disclaimers and privacy information is covered in [Disclaimers](http://www.taxifarefinder.com/disclaimer.php).



== Installation ==

1. Install TaxiFareFinder for WordPress either via the WordPress.org plugin repository or by uploading the /taxi-fare-calculator-by-taxifarefindercom/ directory and its files to your webserver (in the `/wp-content/plugins/` directory).

2. If you are not already, go to WordPress administrator console.

3. Activate the plugin, under Plugins -> Installed plugins.

4. Enable the widget by dragging it into the Main Sidebar, under Appearance ->Widgets.

5. Click on the up-side-triangle on the widget to set properties. 

a. Widget title: Appears at the top of the widget.  Visible to users.

b. Widget message: Appears below the Widget title.  Visible to users.

c. TaxiFareFinder logo: Appears at the bottom of the widget. Please support us by displaying this logo and linking to us!

d. Entity handle: Determines which taxi fare calculator and taxi rate will be used. (required)

e. Default "From" address: Pre-populates the "From" field. (optional)

f. Default "To" address: Pre-populates the "To" field. (optional)

g. Your TaxiFareFinder API Key: Enables estimates to be displayed inside the widget. (optional)

h. Your client ID: Enables custom TaxiFareFinder pages that only lists your Dispatch #. (optional)

i. Your Google Maps API key. Required so that from and to fields can autocomplete. Get your Google Maps API key at https://cloud.google.com/maps-platform/

Note on Entity handle:
The entity handle determines which taxi fare calculator and taxi rate will be used by TaxiFareFinder. This is a required field. The entity handle is often associated with a city but can also be a region, school, airport, or taxi company. To look up the Entity Handle for your city, please visit our [Plugin page](http://www.taxifarefinder.com/plugin.php).

Note on Client ID:
This enables a custom page at TaxiFareFinder that only lists your taxi company, logo, and/or its dispatch number. You must request and have this be generated by TaxiFareFinder.  You can search for your Client ID at our [Plugin page](http://www.taxifarefinder.com/plugin.php).  If you do not have one, please inquire [here](http://www.taxifarefinder.com/contactus.php).

Note on API Key:
Specifying the API Key enables the widget to display the results within the widget.  For your own API key, you must request it directly from TaxiFareFinder.  For more information, please visit our [Developers page](http://www.taxifarefinder.com/developers.php).


== Frequently Asked Questions ==

= I do not see taxi fare estimates. A new window opens, but I only see the main TaxiFareFinder page. (i.e. http://www.taxifarefinder.com/index.php)  Why? =
You most likely do not have the Entity Handle specified correctly.  Please make sure to look up the proper code at http://www.taxifarefinder.com/plugin.php.

= What kind of information is being sent to TaxiFareFinder.com =
The information that are sent back are: a) "To", b) "From", c) entity handle, d) client ID (if specified), and e) API key (if specified).

= Where can I find the links to the TaxiFareFinder logos? =
Please refer to http://www.taxifarefinder.com/links.php.

= What are the disclaimers? =
Please refer to [Disclaimers](http://www.taxifarefinder.com/disclaimer.php).


More FAQ available at [FAQ Page](http://www.taxifarefinder.com/faq.php).



== Screenshots ==

1. Sample deployment on a test site. This is on a side bar.  

2. Sample deployment on a test site.  This is on the footer.

== Known Issues ==

* Only one instance of the widget can be placed on a given WordPress page.

* With 1.0.0, there was an installation issue upon installing directly from WordPress setting's page.  This issue has been addressed in 1.0.1. (2013/12/01)

* jQuery is required for all features to function properly.

* If your WordPress site already uses Google Maps API, there can be conflicting calls that may cause some features to not function properly.  This has been observed with AutoComplete feature, for example.


== Changelog ==

= 1.1.1 =

* Fix error in console if widget not included on page

= 1.1.0 =

* Add field for Google Maps API key, as now required by Google
* Updated app for newer Wordpress versions

= 1.0.3 =

* Fixed issue with JavaScript reference

= 1.0.2 =

* Performance improvement with JavaScript

= 1.0.1 = 

* Fixed issue in initial installation.

= 1.0.0 =

* Launch

== Upgrade Notice ==

= 1.1 =
Adds requirement to add your Google Maps API key, as Google Maps can no longer be used without one. Get your Google Maps API key at https://cloud.google.com/maps-platform/.