jQuery(document).ready(function($) {

 $("#tffaq_checkbox_submit2").click(function(){
 $("#tffaq_action").val($("#tffaq_action2").val()) ;
 $(this).attr('name', 'tffaq_checkbox_submit');
 $("#tffaq_checkbox_submit").click();
 });

 $("#tffaq_checkbox_submit").click(function(){
 tffaq_action = $("#tffaq_action").val();
 tffaq_type = $("#tffaq_is_question").val();
 tffaq_type = ((tffaq_type == 'true')) ? 'Questions' : 'Categories' ;
 tffaq_confirm = ((tffaq_action == 'delete')) ? confirm('Are you sure you want to delete these ' + tffaq_type + ' ?'):'';
 $("#tffaq_confirm").val(tffaq_confirm);

 });

});