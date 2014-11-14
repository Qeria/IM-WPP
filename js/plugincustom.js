jQuery( document ).ready(function() {
//		var bla = jQuery.trim(jQuery('#check_track').val());
//
//		if ( bla !== null && bla !== undefined )
//		{
//				jQuery('#block2_radio').hide('');
//				jQuery('#block2').removeClass('block2').addClass('block_check');
//				jQuery('#block3').removeClass('block3').addClass('block_check');
//		}

    /*jQuery("#setting_button").click(function() {
         jQuery(".system_status_div").css('display', 'none');
		 jQuery(".setting_div").css('display', 'block');
                 jQuery('#setting_button').removeClass('').addClass('active_class');
                 jQuery('#system_status_button').removeClass('active_class').addClass('');

    }); 

    jQuery("#setting_button").click(function(event){
        event.preventDefault();
    });
    jQuery("#system_status_button").click(function() {  

         jQuery(".setting_div").css('display', 'none');
		 jQuery(".system_status_div").css('display', 'block');
                 jQuery('#system_status_button').removeClass('').addClass('active_class');
                 jQuery('#setting_button').removeClass('active_class').addClass('');
    }); 
        jQuery("#system_status_button").click(function(event){
        event.preventDefault();
    });*/
    
    jQuery("#whats_tab").click(function() {  

         jQuery("#credits").css('display', 'none');
		 jQuery("#whats_new").css('display', 'block');
                 jQuery('#whats_tab').removeClass('').addClass('nav-tab-active');
                 jQuery('#credit_tab').removeClass('nav-tab-active').addClass('');
    }); 
        jQuery("#whats_tab").click(function(event){
        event.preventDefault();
    });
    
    jQuery("#credit_tab").click(function() {  

         jQuery("#whats_new").css('display', 'none');
		 jQuery("#credits").css('display', 'block');
                 jQuery('#credit_tab').removeClass('').addClass('nav-tab-active');
                 jQuery('#whats_tab').removeClass('nav-tab-active').addClass('');
    }); 
        jQuery("#credit_tab").click(function(event){
        event.preventDefault();
    });
    
       jQuery('#checkAll').click(function () {    
     jQuery('input:checkbox').prop('checked', this.checked);    
 });
      function validateForm() {
         alert("here");
    var x = document.forms["info_form"]["javascript_code"].value;
    if (x === null || x === "") {
        alert("Code must be filled out");
        return false;
    }
}   
 
});