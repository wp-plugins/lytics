(function( $ ) {
    'use strict';
    $( window ).load(function(){
      if (typeof(LyticsAddons) == 'object' ){
	Object.keys(LyticsAddons).forEach(function(addon) {
	    if( LyticsAddons[addon]['type'] === 'event' ){
		$(LyticsAddons[addon]['selector']).on(LyticsAddons[addon]['event'], function(e){
			var value = $(LyticsAddons[addon]['field']).val();
			lytics_send(addon, LyticsAddons[addon]['key'], value);
		});
	    } else if ( LyticsAddons[addon]['type'] === 'static' ){
		for ( var key in LyticsAddons[addon]['data']) {
		    lytics_send( addon, key, LyticsAddons[addon]['data'][key]);
		}
	    }
	});
      }
    });

    function lytics_send(src, key, value){
	if( typeof(jstag) == 'object' ){
	    var data = { wp_source: src };
	    data[key] = value;
	    console.log( data );
	    jstag.send( data );
	}
    }

})( jQuery );
