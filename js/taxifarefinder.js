jQuery(document).ready(function () {
    //
    // Set up google places autocompleters on the from and to inputs. If either input has a typed address but did not
    // have an autocomplete entry chosen, geocode the typed address.
    //
    // Known issue: if you select a choice from the autocomplete box, THEN type a choice but do not select, the old
    // choice will "stick"; geocoding will not happen to the newly typed address.
    //
    var tff_fromAutocomplete, tff_toAutocomplete, tff_fromInput, tff_toInput;

    jQuery('#tff_loading').hide();

    jQuery('#tff_load').click(function () {
            function resolvePlaces(successCB, errorCB) {
                var placesData = {
                    'from': {
                        'place': tff_fromAutocomplete.getPlace(),
                        'address': tff_fromInput.value
                    },
                    'to': {
                        'place': tff_toAutocomplete.getPlace(),
                        'address': tff_toInput.value
                    },
                };

                function checkPlaces() {
                    if (placesData.from.place && placesData.to.place) {
                        successCB(placesData.from.place, placesData.to.place);
                    }
                }

                function checkPlace(placeData) {
                    if (!placeData.place) {
                        var typedAddress = placeData.address;
                        if (typedAddress.length > 0) {
                            var geocoder = new google.maps.Geocoder();
                            geocoder.geocode({address: typedAddress}, function (results, status) {
                                if (status === 'OK' && results.length > 0) {
                                    placeData.place = results[0];
                                    checkPlaces();
                                } else {
                                    errorCB('Could not find "' + fromAddress + '"');
                                }
                            });
                        } else {
                            errorCB();
                        }
                    }
                }

                for (var which in placesData) {
                    checkPlace(placesData[which]);
                }

                checkPlaces();
            }

            var resultsDiv = jQuery('#tff_results');

            resolvePlaces(function (fromPlace, toPlace) {
                var fromLoc = fromPlace.geometry.location;
                var toLoc = toPlace.geometry.location;

                var tff_from_latlng = fromLoc.lat() + ',' + fromLoc.lng();
                var tff_to_latlng = toLoc.lat() + ',' + toLoc.lng();
                var tff_entity_handle = jQuery('#tff_entity_handle').val();
                var tff_api_key = jQuery('#tff_api_key').val();
                var tff_google_maps_api_key = jQuery('#tff_google_maps_api_key').val();
                var tff_client_id = jQuery('#tff_client_id').val();

                jQuery('#tff_loading').show();

                jQuery.post(
                    tffAjaxLink,
                    {
                        action: 'tffAjaxFareEstimate',
                        tff_from_address: tff_from_latlng,
                        tff_to_address: tff_to_latlng,
                        tff_entity_handle: tff_entity_handle,
                        tff_api_key: tff_api_key,
                        tff_google_maps_api_key: tff_google_maps_api_key,
                        tff_client_id: tff_client_id
                    },
                    function (results) {
                        resultsDiv.html(results);
                        resultsDiv.slideDown(400);
                        jQuery('#tff_loading').delay(400).hide();
                    }
                );
            }, function (msg) {
                msg = msg || "Please enter starting and ending locations";
                resultsDiv.html(msg);
                resultsDiv.slideDown(400);
            });
        }
    );

    tff_fromInput = document.getElementById('tff_from_address');
    tff_toInput = document.getElementById('tff_to_address');
    if (tff_fromInput && tff_toInput && typeof(google) !== "undefined") {
        tff_fromAutocomplete = new google.maps.places.Autocomplete(tff_fromInput, {});
        tff_toAutocomplete = new google.maps.places.Autocomplete(tff_toInput, {});
    }
});
