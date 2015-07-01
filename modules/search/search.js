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

	function animateEllipses( el ) {
		switch ( el.innerText ) {
			case '':
			case '.':
			case '..':
				el.innerText += '.';
				break;
			default:
				el.innerText = '';
		}
		setTimeout( animateEllipses, 300, el );
	}

	$('.ellipses.animate').each( function(){
		animateEllipses( this );
	} );

})( window, document, jQuery, jetpackSearchData );
