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
            url: "../../appweb/inc/edit-collector-functions.php",
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
    
    
    
    //DELETE ITEM LIST
    $('.adminuserbtn').each(function(){
        
        //var field = $(this);            
        //var emtyvalue = $(this).attr('data-post');
        //var datafield = "wrapitemplto";//$(this).attr('data-field');
        //var parent = $("#wrapitemplto"+emtyvalue);//field.parent().attr('id');
        //var priceunit = $(this).attr('data-unit');
                
        function adminItem(nameItem, titleEv, msjEv, itemVar, fieldvalue, fieldedit) {
            swal({
              title: titleEv, 
              text: "<span style='color:#DB4040; font-weight:bold;'>" + nameItem + "</span><br>" + msjEv, 
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: "Continuar",
              cancelButtonText: "Cancelar",
              closeOnConfirm: true,
              closeOnCancel: true,
              animation: false,
              html: true
            }, function(isConfirm){
                  if (isConfirm) {
                    confiAdminItem(itemVar, fieldvalue, fieldedit);
                  } else {
                    return false;	
                  }
                });
          }
        
        
        function confiAdminItem(itemVar, fieldvalue, fieldedit){
            
            var dataString = 'fieldedit='+fieldedit+'&post='+itemVar+'&value='+fieldvalue;
            
            $.ajax({
                type: "POST",
                url: "../../appweb/inc/edit-collector-functions.php",
                data: dataString,
                success: function(data) {

                    var response = JSON.parse(data);                        
                    if (response['error']) {           
                        
                        $("#erradminuser").html(response['error']).fadeIn('slow');                            
                    } else {
                        
                        if(fieldedit == "deleteitem"){
                            $("#erradminuser").fadeOut();
                            $("#wrappcontent").fadeOut();
                            $("#confirmdelete").show();
                        }else{
                            $("#erradminuser").fadeOut();    
                            $("#successadminuser").html("<div class='alert bg-success alert-dismissible box50 text-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><i class='icon fa fa-check fa-2x margin-right-xs'></i> Los cambios fueron guardados correctamente</p></div>").fadeIn(function(){
                                location.reload();
                            });
                        }                        
                    }

                }
            });    
        };
        
        $(this).click(function(e) {
            e.preventDefault(); 
            //var linkURL = $(this).attr("href");
            var nameItem = $(this).attr("data-name");
            var titleEv = $(this).attr("data-title");
            var msjEv = $(this).attr("data-msj");
            //var reMsjProd = $(this).attr("data-remsj");
            //var datafield = "weeditalbumref";
            //var itemtrashto =  $(this).attr('data-field');
            var itemVar =  $(this).attr('data-post');
            var fieldvalue = $(this).attr('data-value');            
            var fieldedit = $(this).attr('data-field');
            
            adminItem(nameItem, titleEv, msjEv, itemVar, fieldvalue, fieldedit);
        });
    });
 
});