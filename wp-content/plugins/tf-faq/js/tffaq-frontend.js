jQuery(document).ready(function($) {
            $("#tffaq-tabs").tabs({cookie: {}}).show();
            $('p.tffaq-question a').click(function() {
            var tfaid = $(this).data("options").id;
	    $('#tffaq-answer-' + tfaid).toggle();
	    $('#tffaq-tabs-2 #tffaq-answer-' + tfaid).toggle();

	    });

});