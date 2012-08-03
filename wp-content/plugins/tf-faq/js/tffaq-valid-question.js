jQuery(document).ready( function ($) {

//	this validates the ask form

$('#tffaq-question').addClass("required");
$('#tffaq-email').addClass('required email');
$("#tffaq-questionForm").validate();

//	this validates the search form

	$('#tffaq-search').addClass("required");
	$("#tffaq_search_form").validate();

});