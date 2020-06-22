jQuery(document).ready(function()
{

  jQuery("#submitPost").on( "click", function(e){
  //  alert(jQuery('#customerPhonenumber').val());
        e.preventDefault();
      
        if(jQuery('#customerName').val() =="" && jQuery('#customerPhonenumber').val() =="" && jQuery('#customerEmail').val() =="" && jQuery('#customerDesiredBudget').val() ==""
         && jQuery('#customerMessage').val() ==""){        
          jQuery('#message').html("<p class='error-red'> Oops! Please enter  required fields. </p>");          
           return false;
        } 
        else if(jQuery('#customerName').val() ==""){        
          jQuery('#message').html("<p class='error-red'> Oops! Please enter Name Field. </p>");          
           return false;
        }
        else if(jQuery('#customerPhonenumber').val() ==""){        
          jQuery('#message').html("<p class='error-red'> Oops! Please enter Phone number. </p>");
           return false;
        }
        else if(jQuery('#customerEmail').val() ==""){        
          jQuery('#message').html("<p class='error-red'> Oops! Please enter Email. </p>");
           return false;
        }
        else if(jQuery('#customerDesiredBudget').val() ==""){        
          jQuery('#message').html("<p class='error-red'> Oops! Please enter Budget. </p>");
           return false;
        }        
        else if(jQuery('#customerMessage').val() ==""){        
          jQuery('#message').html("<p class='error-red'> Oops! Please enter Message. </p>");
           return false;
        }
        else{    
       var formData = {
        'customerName'       :   jQuery('#customerName').val(),
        'customerPhonenumber'        :   jQuery('#customerPhonenumber').val(),
        'customerEmail'    :   jQuery('#customerEmail').val(),
        'customerDesiredBudget'    :   jQuery('#customerDesiredBudget').val(),
        'customerMessage'    :   jQuery('#customerMessage').val(),
        'crm-date'    :   jQuery('#crm-date').val(),
        action : 'crm_form_insert' };
     jQuery.ajax({
      type: 'POST',
      dataType:"json",
      url: customer_ajax_url.ajax_url,
      data: formData,
      success: function(response) {  
        if(response == 0)
        {
          jQuery('#message').html("<p class='error-red'> Oops! Something went wrong post submission failed. </p>");
        } 
        else{
          jQuery('#message').html("<p class='error-green'> Thank you! Your post has been submitted.</p>" );
        }                                    
        jQuery('#customer_info')[0].reset();                              
      }
    });

        }
    return false;
  });
} );


