$(document).ready(function(){
    
    $("#closecustombtn").hide();
    
    $('#customuserbtn').click(function(){
        var field = $(this);
        var parent = field.parent().attr('id');        
        var datafield = $(this).attr('data-field');
        
        if($('#'+parent).find(".okrow").length){
            $('#'+parent+' .okrow').remove();
            $('#'+parent+' .loaderrow').remove();
            $('#'+parent).append('<div class="loaderrow"><img src="../appweb/img/loading.gif" width="20px"/></div>').fadeIn('slow');
        }else{
            $('#'+parent).append('<div class="loaderrow"><img src="../appweb/img/loading.gif" width="20px"/></div>').fadeIn('slow');
        }
                
        var capitalinicio = $('#capitalinicialform').val();        
        var sabadodia = $('#sabadodiaform').val();
        var domingodia = $('#domingodiaform').val();
        var festivodia = $('#festivodiaform').val();
        var sabado = $('#sabadoform').val();
        var domingo = $('#domingoform').val();
        var festivo = $('#festivoform').val();
        var caja = $('#cajaform').val();    
        var codeuser = $('#codeuserform').val();    
        var fieldedit = $('#introcustomform').val();    

        var dataString = 'capitalinicio='+capitalinicio+'&sabadodia='+sabadodia+'&domingodia='+domingodia+'&festivodia='+festivodia+'&sabado='+sabado+'&domingo='+domingo+'&festivo='+festivo+'&caja='+caja+'&post='+codeuser+'&fieldedit='+fieldedit;
        
        //console.log(dataString);

        $.ajax({
            type: "POST",
            url: "../appweb/inc/edit-custom-functions.php",
            data: dataString,
            success: function(data) {
                
                var response = JSON.parse(data);
                
                if (response['error']) {
                    
                    $('#'+parent+' .loaderrow').fadeOut(function(){
                        $('#err'+datafield).html(response['error']).fadeIn('slow');
                    });

                }else{

                    $('#'+parent+' .loaderrow').fadeOut(function(){
                        
                        $('#err'+datafield).fadeOut();
                        
                        $('#responce'+datafield).html("<div class='alert alert-default bg-success alert-dismissible text-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> Personalización </h4><p style='display:block;'>Tus configuraciones fueron guardadas correctamente.</p></div>");
                                                
                        $("#lastarrowshw").fadeOut();
                        $("#customuserbtn").fadeOut();
                        $("#closecustombtn").show();
                                                                
                    });
                                        
                }
            }
        });
        
    });
                 
});