$(document).ready(function(){    
    
    $('#editpassbtn').click(function(){
        var field = $(this);
        var parent = field.parent().attr('id');
        var emtyvalue = $(this).val();
        var datafield = $(this).attr('data-field');
        //field.css('background-color','#eaeaea');
        

        //if($('#'+parent).find(".okrow").length){
        if($('#'+parent).find(".loaderbtn").length){
            $('#'+parent+' .okrow').remove();
            $('#'+parent+' .loaderbtn').remove();
            $('#'+parent).append('<div class="loaderbtn"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        }else{
            $('#'+parent).append('<div class="loaderbtn"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        }
        
        var pass= $('input[name="passform_confirmation"]').val();
        var replypass= $('input[name="passform"]').val();
        
        var dataString = 'value='+pass+'&post='+$(this).attr('data-post')+'&fieldedit='+$(this).attr('data-field')+'&replypass='+replypass;

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
                    
                    $('#'+parent+' .loaderbtn').fadeOut(function(){
                        $('#err'+datafield).html(response['error']).fadeIn('slow');
                    });

                }else{

                    $('#'+parent+' .loaderbtn').fadeOut(function(){
                                                
                        $('#err'+datafield).fadeOut();
                        
                        $('#responce'+datafield).html("<div class='alert alert-default bg-success alert-dismissible text-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> Contraseña </h4><p style='display:block;'>Tus cambios fueron guardados correctamente</p></div>");     
                        
                    });
                    
                }
            }
        });
        
    });
    
    
 
});