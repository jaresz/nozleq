function rebinduj()
{
	/*
	 *  Funkcja wysyływania po wyrenderowaniu, lub zmianie dokumentu
	*/
	$( "#target" ).click(function() {
		$( this ).attr("disabled", true);
	});
}



$(document).ready(function() {
	rebinduj();
});  // end ready