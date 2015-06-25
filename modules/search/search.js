/* global jetpackSearchData */

(function( window, document, $, data ){

	// Temporary slug, would need to be fleshed out.
	function doElasticsearchSearch( query ) {
		window.alert( 'Run query for `' + query + '`' );
	}

	// Run our initial search on page load.
	doElasticsearchSearch( data.initialSearchTerm );

	// Set up a listener to handle subsequent searches.
	$('form.search-form' ).submit( function( event ) {
		var search_query = $( event.target ).find('[name=s]' ).val();

		doElasticsearchSearch( search_query );

		event.preventDefault();
	} );

})( window, document, jQuery, jetpackSearchData );
