$(document).ready(function(){
    $('input, textarea, select').focus(function() {
        $(this).css('background-color','#d9d9d9');
    });

    $('input, textarea, select').blur(function(){
        var field = $(this);
        var parent = field.parent().attr('id');
        var emtyvalue = $(this).val();
        var datafield = $(this).attr('data-field');
        field.css('background-color','#eaeaea');
        

        if($('#'+parent).find(".okrow").length){
            $('#'+parent+' .okrow').remove();
            $('#'+parent+' .loaderrow').remove();
            $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        }else{
            $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        }

        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name')+'&post='+$(this).attr('data-post')+'&fieldedit='+$(this).attr('data-field');

        /*if(datafield == "ciudadtrabaja"){            
            var ciudaddresidencia;
            $(".ciudadtrabajalist").each(function(){
                if($(this).is(":selected")){
                    ciudaddresidencia = $(this).val();
                }
            });
            
            dataString += "&ciudadedit="+ciudaddresidencia;
        }*/
        //console.log(dataString);

        $.ajax({
            type: "POST",
            url: "../../appweb/inc/edit-custom-functions.php",
            data: dataString,
            success: function(data) {
                //field.val(data);
                var response = JSON.parse(data);
                //console.log(response);
                if (response['error']) {

                    $('#err'+datafield).html(response['error']).fadeIn('slow');

                }else{

                    $('#'+parent+' .loaderrow').fadeOut(function(){
                        //$('#'+parent).append('<div class="okrow"><img src="../../appweb/img/ok.png"/></div>').fadeIn('slow');
                        
                        $('#err'+datafield).fadeOut();
                        
                        field.val(response);
                                        
                        if(datafield == "editaciudad"){ 
                            $("#weeditaciudad").fadeOut();
                            $("#wpeditaciudad").fadeIn();
                            $("#ciudadform").val(response);                     
                        }
                    });
                    
                    
                }
            }
        });
        
    });
    
    
 
});