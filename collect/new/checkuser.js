$(document).ready(function(){
    $('input.checkuser').focus(function() {
        $(this).css('background-color','#f5f5f5');
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
        field.css('background-color','#eaeaea');
                        
        $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        
        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name')+'&fieldedit='+$(this).attr('data-field')+'&datauser='+$(this).attr('data-user');

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
                        deudorapellidoJSON  = response.deudornickname;
                        deudorcedulaJSON  = response.deudorcedula;
                        deudorfecharegistroJSON  = response.deudorfecharegistro;
                        
                        //deudornitJSON = response.deudornit;
                        
                        //deudorsegundonombreJSON = response.deudorsegundonombre;
                        //deudorsegundoapellidoJSON = response.deudorsegundoapellido;
                        //deudoremailJSON = response.deudoremail;
                        deudortel1JSON = response.deudortel1;
                        deudortel2JSON = response.deudortel2;
                        deudordirdomicilioJSON = response.deudorciudad;
                        //deudorbarrioJSON = response.deudorbarrio;
                        //deudorfechaeditaJSON = response.deudorfechaedita;
                        //deudordirgeoJSON = response.deudordirgeo;
                    });
                        
                    if(existeJSON == "true"){

                        $('#'+parent+' .loaderrow').fadeOut(function(){
                            
                            $("#response"+datafield).html("<div class='alert bg-danger text-red alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h3><i class='icon fa fa-times fa-lg margin-right-xs'></i>Usuario registrado!<small style='display:block;' class=''>Este usuario ya esta registrado en tu sistema y no puedes registrarlo nuevamente</small></h3><p style='display:block;'><strong class='text-size-x4'>"+deudornombreJSON+"</strong></p><p style='display:block;'>Cedula: <strong>"+deudorcedulaJSON+"</strong></p><p style='display:block;'>Usuario desde: <strong>"+deudorfecharegistroJSON+"</strong></p></div>").fadeIn("slow");
                            
                        });

                    }else if(existeJSON == "false"){
                        $("#response"+datafield).fadeOut();
                        $('#'+parent+' .loaderrow').fadeOut();  
                        //$(".wrapinfoform").fadeIn(); 
                        //$(".userexiste").fadeOut();
                        //$("#right-bartbtn").fadeIn(); 
                        //$("#userexist-bartbtn").fadeOut();                    
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
        field.css('background-color','#eaeaea');
                        
        $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        
        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name')+'&fieldedit='+$(this).attr('data-field')+'&datauser='+$(this).attr('data-user');

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
                        //deudorapellidoJSON  = response.deudorapellido;
                        deudorcedulaJSON  = response.deudorcedula;
                        deudorfecharegistroJSON  = response.deudorfecharegistro;
                    });
                        
                    if(existeJSON == "true"){

                        $('#'+parent+' .loaderrow').fadeOut(function(){
                            
                            $("#response"+datafield).html("<div class='alert bg-danger text-red alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h3><i class='icon fa fa-times fa-lg margin-right-xs'></i>Email ya existe</h3><p style='display:block;'><strong>"+deudornombreJSON+"</strong></p><p style='display:block;'>Cedula: <strong>"+deudorcedulaJSON+"</strong></p><p style='display:block;'>Usuario desde: <strong>"+deudorfecharegistroJSON+"</strong></p><p>Esta cuenta de email ya fue registrada para otro usuario. Verificalo he intentalo de nuevo</p></div>").fadeIn("slow");
                            
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
    
    
    
    /*
    *VERIFICAR NOMBRE DE USUARIO
    */
    $('.checknickname').blur(function(){
        var field = $(this);
        var parent = field.parent().attr('id');
        var emtyvalue = $(this).val();
        var datafield = $(this).attr('data-field');
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        var paththisfile = $("#paththisfile").val();
        field.css('background-color','#eaeaea');
                        
        $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        
        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name')+'&fieldedit='+$(this).attr('data-field')+'&datauser='+$(this).attr('data-user');

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
                    var useridJSON  = "";
                    var nicknameJSON  = "";                    
                    var tipouserJSON  = "";
                    var userfecharegistroJSON  = "";

                    jQuery.each(response, function(k,v){
                        existeJSON = response.existe;
                        useridJSON  = response.userid;
                        nicknameJSON  = response.username;
                        //deudorapellidoJSON  = response.deudorapellido;
                        tipouserJSON  = response.tipousuario;
                        userfecharegistroJSON  = response.userfecharegistro;
                    });
                        
                    if(existeJSON == "true"){

                        $('#'+parent+' .loaderrow').fadeOut(function(){
                            
                            $("#response"+datafield).html("<div class='alert bg-danger text-red alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h3><i class='icon fa fa-times fa-lg margin-right-xs'></i>Nombre de usuario</h3><p style='display:block;'><strong>"+nicknameJSON+"</strong></p><p style='display:block;'>Tipo de usuario: <strong>"+tipouserJSON+"</strong></p><p style='display:block;'>Usuario desde: <strong>"+userfecharegistroJSON+"</strong></p><p>Este nombre de usuario ya fue registrado. Puedes intentar adicionar una letra o número, o escribir un nombre de usuario diferente</p></div>").fadeIn("slow");
                            
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