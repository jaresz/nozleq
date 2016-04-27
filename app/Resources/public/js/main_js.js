function rebinduj()
{
	/*
	 *  Funkcja wysyływania po wyrenderowaniu, lub zmianie dokumentu
	*/
	$( ".onlyoneclick" ).click(function() {
		$( this ).hide();
	});
}



$(document).ready(function() {
	rebinduj();
});  // end ready