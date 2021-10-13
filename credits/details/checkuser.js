$(document).ready(function(){
    $('input.checkuser').focus(function() {
        $(this).css('background-color','#ffffff');
    });
    
    
    /*
    *VERIFICAR CEDULA
    */
    $('.checkuser').blur(function(){
        var field = $(this);
        var parent = field.parent().attr('id');
        var emtyvalue = $(this).val();
        var datafield = $(this).attr('data-field');
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        var paththisfile = $("#paththisfile").val();
        var creditoform = $("#creditoform").val();
        
        field.css('background-color','#F3F3F3');
                        
        $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        
        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name')+'&fieldedit='+$(this).attr('data-field');

        //console.log(dataString);

        $.ajax({
            type: "POST",
            url: "checkuser.php",
            data: dataString,
            success: function(data) {
                //field.val(data);
                var response = JSON.parse(data);
                                
                //console.log(existeJSON);
                if (response['error']) {
                    
                    $("#response"+datafield).fadeOut();
                    $('#'+parent+' .loaderrow').fadeOut();

                }else{
                    
                    var existeJSON = "";
                    var deudoridJSON  = "";
                    var deudornombreJSON  = "";
                    var deudorapellidoJSON  = "";
                    var deudorcedulaJSON  = "";                    
                    var deudorfecharegistroJSON  = "";
                    
                    //var deudornitJSON  = "";
                    
                    var deudorsegundonombreJSON = "";
                    var deudorsegundoapellidoJSON = "";
                    var deudoremailJSON = "";
                    var deudortel1JSON = "";
                    var deudortel2JSON = "";
                    var deudordirdomicilioJSON = "";
                    var deudorbarrioJSON = "";
                    var deudorfechaeditaJSON = "";
                    var deudordirgeoJSON = "";

                    jQuery.each(response, function(k,v){
                        
                        /*
                        'existe' => 'true',
                        'deudorid' => $deudorid,
                        'deudornombre' => $deudornombre,
                        'deudorapellido' => $deudorapellido,
                        'deudorsegundonombre' =>$deudorsegundonombre,                 
                        'deudorsegundoapellido' => $deudorsegundoapellido, 
                        'deudorcedula' => $deudorcedula,                
                        'deudoremail' => $deudoremail, 
                        'deudortel1'=> $deudortel1, 
                        'deudortel2' => $deudortel2, 
                        'deudordirdomicilio' => $deudordirdomicilio, 
                        'dir_domicilio_deudor',
                        'deudorbarrio' => $deudorbarrio, 
                        //'deudordirgeo' => $deudordirgeo,                 
                        'deudorfecharegistro' => $deudorfecharegistro,
                        'deudorfechaedita' => $ultimaFechaEdita
                        deudornit
                        */
                        
                        existeJSON = response.existe;
                        deudoridJSON  = response.deudorid;
                        deudornombreJSON  = response.deudornombre;
                        deudorapellidoJSON  = response.deudorapellido;
                        deudorcedulaJSON  = response.deudorcedula;
                        deudorfecharegistroJSON  = response.deudorfecharegistro;
                        
                        //deudornitJSON = response.deudornit;
                        
                        deudorsegundonombreJSON = response.deudorsegundonombre;
                        deudorsegundoapellidoJSON = response.deudorsegundoapellido;
                        deudoremailJSON = response.deudoremail;
                        deudortel1JSON = response.deudortel1;
                        deudortel2JSON = response.deudortel2;
                        deudordirdomicilioJSON = response.deudordirdomicilio;
                        deudorbarrioJSON = response.deudorbarrio;
                        deudorfechaeditaJSON = response.deudorfechaedita;
                        deudordirgeoJSON = response.deudordirgeo;
                    });
                        
                    if(existeJSON == "true"){

                        $('#'+parent+' .loaderrow').fadeOut(function(){
                            
                            //$("#response"+datafield).html("<div class='alert bg-success text-green  alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h3><i class='icon fa fa-check fa-lg margin-right-xs'></i>Lo encontramos!<small style='display:block;' class='white-text'>Este usuario ya esta registrado en tu sistema, presiona [Continuar] para ir al siguiente paso</small></h3><p style='display:block;'><strong>"+deudornombreJSON+" "+deudorapellidoJSON+"</strong></p><p style='display:block;'>Cedula: <strong>"+deudorcedulaJSON+"</strong></p><p style='display:block;'>Cliente desde: <strong>"+deudorfecharegistroJSON+"</strong></p></div>").fadeIn("slow");

                            //$("#response"+datafield).append("<a href='"+paththisfile+"?cc="+deudorcedulaJSON+"&existe=ok&dbtype="+datafield+"' class='btn btn-default '><span>Continuar</span><i class='fa fa-arrow-right fa-lg margin-left-xs'></i></a>").show();
                            
                            
                            
                            
                            $("#response"+datafield).html("<div class='alert bg-success text-green  alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h3><i class='icon fa fa-check fa-lg margin-right-xs'></i>Usuario ya ha sido registrado en el sistema</h3><p style='display:block;'>Usuario desde: <strong>"+deudorfecharegistroJSON+"</strong></p></div>").fadeIn("slow");
                            
                            if(deudorfechaeditaJSON != "false"){
                                $("#response"+datafield).append("<div class='callout bg-info text-blue'><h3 class='margin-top-xs'><i class='icon fa fa-info-circle fa-lg margin-right-xs'></i>Perfil modificado</h3><p style='display:block;'>Ultima actualización: <strong>"+deudorfechaeditaJSON+"</strong></p></div>").show();
                            }
                            
                            $("input[name='existnombre1form']").val(deudornombreJSON);
                            //$("input[name='nombre2form']").val(deudorsegundonombreJSON);
                            $("input[name='existapellido1form']").val(deudorapellidoJSON);
                            //$("input[name='apellido2form']").val(deudorsegundoapellidoJSON);
                            //$("input[name='Emailform']").val(deudoremailJSON);
                            $("input[name='existTelefono1form']").val(deudortel1JSON);
                            $("input[name='existTelefono2form']").val(deudortel2JSON);
                            
                            if(deudordirdomicilioJSON != ""){
                                $("input[name='existeDireccionResidenciaform']").val(deudordirdomicilioJSON);    
                            }
                            
                            //$("input[name='Barrioform']").val(deudorbarrioJSON);
                            
                            /*if(deudornitJSON != ""){
                                $("input[name='nitrefcomercial']").html(deudornitJSON);
                            }*/
                            
                            if(deudordirgeoJSON != ""){
                                $("#geodomicilio").html(deudordirgeoJSON);
                            }
                            $("input[name='itemexistform']").val(deudoridJSON);
                            
                            $(".wrapinfoform").fadeOut(); 
                            $(".userexiste").fadeIn();
                            
                            /*if($(".userexiste").find(".existinfocomercial").length){
                                $(".existinfocomercial").fadeOut();
                            }*/
                            
                            $("#right-bartbtn").fadeOut(); 
                            $("#userexist-bartbtn").fadeIn();
                            
                            //$("#userexist-bartbtn").html("<a id='addrefexist' class='btn btn-default ' type='button' data-ref='"+deudoridJSON+"' data-credito='"+creditoform+"' data-field='addrefcredito'>Agregar referencia<i class='fa fa-save fa-1x margin-left-xs'></i></a>").show();
                        });

                    }else if(existeJSON == "false"){
                        $("#response"+datafield).fadeOut();
                        $('#'+parent+' .loaderrow').fadeOut();  
                        $(".wrapinfoform").fadeIn(); 
                        $(".userexiste").fadeOut();
                        $("#right-bartbtn").fadeIn(); 
                        $("#userexist-bartbtn").fadeOut();
                        
                        

                    }      

                }
            }
        });
        
    }); 
    
    
    /*
    *VERIFICA NIT
    */        
    $('.checknit').blur(function(){
        var field = $(this);
        var parent = field.parent().attr('id');
        var emtyvalue = $(this).val();
        var datafield = $(this).attr('data-field');
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        var paththisfile = $("#paththisfile").val();
        field.css('background-color','#F3F3F3');
                        
        $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        
        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name')+'&fieldedit='+$(this).attr('data-field');

        //console.log(dataString);

        $.ajax({
            type: "POST",
            url: "checkuser.php",
            data: dataString,
            success: function(data) {
                //field.val(data);
                var response = JSON.parse(data);
                                
                //console.log(existeJSON);
                if (response['error']) {
                    
                    $("#response"+datafield).fadeOut();
                    $('#'+parent+' .loaderrow').fadeOut();

                }else{
                    
                    var existeJSON = "";
                    var deudoridJSON  = "";
                    var razonsocialJSON  = "";
                    var nombrecontactoJSON  = "";                                        
                    var empresanitJSON  = "";
                    var empresafecharegistro = "";                                        
                    var empresatelJSON = "";
                    var empresadirJSON = "";
                    var empresaciudadJSON = "";                    
                    var empresafechaeditaJSON = "";
                    

                    jQuery.each(response, function(k,v){
                        
                        /*
                        'existe' => 'true',
                        'deudorid' => $deudorid,
                        'razonsocial' => $razonsocial,
                        'nombrecontacto' => $nombrecontacto,
                        'nitempresa' => $nitempresa,
                        'empresafecharegistro' => $deudorfecharegistro,
                        'empresadir' => $dirempresa,
                        'empresatel'=> $telempresa, 
                        'empresaciudad' => $ciudadempresa,                 
                        'empresafechaedita' => $ultimaFechaEdita
                        */
                        
                        existeJSON = response.existe;
                        deudoridJSON  = response.deudorid;
                        razonsocialJSON  = response.razonsocial;
                        nombrecontactoJSON = response.nombrecontacto;                        
                        empresanitJSON = response.nitempresa;
                        empresafecharegistro = response.empresafecharegistro;
                        
                        empresatelJSON = response.empresatel;
                        empresadirJSON = response.empresadir;
                        empresaciudadJSON = response.empresaciudad;
                        empresafechaeditaJSON = response.empresafechaedita;
                    });
                        
                    if(existeJSON == "true"){

                        $('#'+parent+' .loaderrow').fadeOut(function(){
                            
                            $("#response"+datafield).html("<div class='alert bg-success text-green  alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h3><i class='icon fa fa-check fa-lg margin-right-xs'></i>Usuario ya ha sido registrado en el sistema</h3><p style='display:block;'>Usuario desde: <strong>"+empresafecharegistro+"</strong></p></div>").fadeIn("slow");
                            
                            if(empresafechaeditaJSON != "false"){
                                $("#response"+datafield).append("<div class='callout bg-info text-blue'><h3 class='margin-top-xs'><i class='icon fa fa-info-circle fa-lg margin-right-xs'></i>Perfil modificado</h3><p style='display:block;'>Ultima actualización: <strong>"+empresafechaeditaJSON+"</strong></p></div>").show();
                            }
                            
                            $("input[name='existNombreEmpresaform']").val(razonsocialJSON);
                           
                            $("input[name='existcontatoform']").val(nombrecontactoJSON);
                           
                            $("input[name='existTelefonoform']").val(empresatelJSON);
                            $("input[name='existDireccionform']").val(empresadirJSON);
                            $("input[name='existCiudadEmpresaform']").val(empresaciudadJSON);
                            
                            if(empresadirJSON != ""){
                                $("#dirempresa").html(empresadirJSON+" / "+empresaciudadJSON);
                            }
                            
                            if(empresaciudadJSON != ""){
                                $("#dirempresa").append("<br>"+empresaciudadJSON);
                            }
                            
                            $("input[name='itemexistform']").val(deudoridJSON);
                            
                            $(".wrapinfoform").fadeOut(); 
                            $(".userexiste").fadeIn();
                            
                            $(".existinfocomercial").fadeIn();
                            $("#infocomerform").fadeOut();
                            
                            
                            $("#right-bartbtn").fadeOut(); 
                            
                            //$("#userexist-bartbtn").html("<a href='"+paththisfile+"?nit="+empresanitJSON+"&existe=ok&dbtype="+datafield+"' class='btn btn-default '><span>Continuar</span><i class='fa fa-arrow-right fa-1x margin-left-xs'></i></a>").show();
                                                        
                            $("#userexist-bartbtn").fadeIn();
                        });

                    }else if(existeJSON == "false"){
                        $("#response"+datafield).fadeOut();
                        $('#'+parent+' .loaderrow').fadeOut();  
                        $(".wrapinfoform").fadeIn(); 
                        $(".userexiste").fadeOut();
                        
                        $(".existinfocomercial").fadeOut();
                        $("#infocomerform").fadeIn();
                        
                        $("#right-bartbtn").fadeIn(); 
                        $("#userexist-bartbtn").fadeOut();
                                                
                    }      

                }
            }
        });
        
    });
    
    
    /*
    *VERIFICAR EMAIL
    */
    $('.checkemailuser').blur(function(){
        var field = $(this);
        var parent = field.parent().attr('id');
        var emtyvalue = $(this).val();
        var datafield = $(this).attr('data-field');
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        var paththisfile = $("#paththisfile").val();
        field.css('background-color','#F3F3F3');
                        
        $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        
        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name')+'&fieldedit='+$(this).attr('data-field');

        //console.log(dataString);

        $.ajax({
            type: "POST",
            url: "checkuser.php",
            data: dataString,
            success: function(data) {
                //field.val(data);
                var response = JSON.parse(data);
                                
                //console.log(existeJSON);
                if (response['error']) {
                    
                    $("#response"+datafield).fadeOut();
                    $('#'+parent+' .loaderrow').fadeOut();

                }else{
                    
                    var existeJSON = "";
                    var deudoridJSON  = "";
                    var deudornombreJSON  = "";
                    var deudorapellidoJSON  = "";
                    var deudorcedulaJSON  = "";
                    var deudorfecharegistroJSON  = "";

                    jQuery.each(response, function(k,v){
                        existeJSON = response.existe;
                        deudoridJSON  = response.deudorid;
                        deudornombreJSON  = response.deudornombre;
                        deudorapellidoJSON  = response.deudorapellido;
                        deudorcedulaJSON  = response.deudorcedula;
                        deudorfecharegistroJSON  = response.deudorfecharegistro;
                    });
                        
                    if(existeJSON == "true"){

                        $('#'+parent+' .loaderrow').fadeOut(function(){
                            
                            $("#response"+datafield).html("<div class='alert bg-danger text-red alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h3><i class='icon fa fa-times fa-lg margin-right-xs'></i>Email ya existe</h3><p style='display:block;'><strong>"+deudornombreJSON+" "+deudorapellidoJSON+"</strong></p><p style='display:block;'>Cedula: <strong>"+deudorcedulaJSON+"</strong></p><p style='display:block;'>Cliente desde: <strong>"+deudorfecharegistroJSON+"</strong></p><p>Esta cuenta de email ya fue registrada para otro usuario. Verificalo he intentalo de nuevo</p>").fadeIn("slow");
                            
                        });

                    }else if(existeJSON == "false"){
                        $("#response"+datafield).fadeOut();
                        $('#'+parent+' .loaderrow').fadeOut();  
                        //$("#wrapinfoform").fadeIn(); 

                    }      

                }
            }
        });
                                
    });
    
    
    
                                
});