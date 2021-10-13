$(document).ready(function(){ 
                       
    //ADD ITEM
    $("#additembtn").click(function(){
        //event.preventDefault();
        
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        
        var field = $(this);
        var parent = $("#wrapadditem");
        var emtyvalue = $(this).val();
        var datafield = "additem";
        var iduser = $("input[name='codeuserform']").val();//$("#codeuserform");
        //var idref = $("input[name='deudorform']").val();
        var creditoform = $("input[name='creditoform']").val();
        var typeuserform = $("input[name='typeuserform']").val();
        var userlabel = $("input[name='userlabel']").val();
        
        if($("#wrapadditem").find(".loader").length){
            $("#wrapadditem"+" .ok").remove();
            $("#wrapadditem"+" .loader").remove();
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }else{
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }
        
        //CREA OBJETO FORMDATA
        var form = document.getElementById('refcomerform');
        var formData = new FormData(form);
                                    
                
        //SOBRE LOS ARCHIVOS DE IMAGEN
        var fileSelect = document.getElementById('imgmutifile');
        var fileMulti = fileSelect.files;
                        
        // Loop through each of the selected files.
        for (var i = 0; i < fileMulti.length; i++) {
            var file = fileMulti[i];

            // Check the file type.
            if (!file.type.match('image.*')) {
                continue;
            }

            // Add the file to the request.
            formData.append('multifileimg[]', file);//, file.name
        }
        
        /*
        **SELCTS - CHECKBOX - OPTIONGROUP
        */
        
        //GENERO
        //var generofrom = $("input[name='Generoform']").val();
        //formData.append("Generoform", generofrom);
        var generofrom;
        $("input[name='Generoform']").each(function(){
            if($(this).is(":checked")){
                generofrom = $(this).val(); 
                formData.append("Generoform", generofrom);
            }
        });
        
        //ESTADO CIVIL
        //var generofrom = $("input[name='EstadoCivilform']").val();
        //formData.append("EstadoCivilform", generofrom);
        var EstadoCivilform;
        $("input[name='EstadoCivilform']").each(function(){
            if($(this).is(":checked")){
                EstadoCivilform = $(this).val(); 
                formData.append("EstadoCivilform", EstadoCivilform);
            }
        });
                        
        //ESCOLARIDAD
        var escolaridadfrom;
        $("select[name='escolaridadform']").each(function(){
            if($(this).is(":selected")){
                escolaridadfrom = $(this).val(); 
                formData.append("escolaridadform", escolaridadfrom);
            }
        });
        
        //TIPO DE VIVIENDA
        var tipodeviviendafrom;
        $("select[name='TipoViviendaform']").each(function(){
            if($(this).is(":selected")){
                tipodeviviendafrom = $(this).val(); 
                formData.append("TipoViviendaform", tipodeviviendafrom);
            }
        });
        
        //ESTRATO SOCIAL
        //var Estrato = $("input[name='Estratoform']").val();    
        //formData.append("Estratoform", Estrato  ); 
        var Estrato;
        $("select[name='Estratoform']").each(function(){
            if($(this).is(":selected")){
                Estrato = $(this).val(); 
                formData.append("Estratoform", Estrato);
            }
        });
        
        //LUGAR NACIMIENTO
        //var Lugar_Nacimiento = $("input[name='LugarNacimientoform']").val();
        //formData.append("LugarNacimientoform", Lugar_Nacimiento );
        var Lugar_Nacimiento;
        $("select[name='LugarNacimientoform']").each(function(){
            if($(this).is(":selected")){
                Lugar_Nacimiento = $(this).val(); 
                formData.append("LugarNacimientoform", Lugar_Nacimiento);
            }
        });
        
        //CIUDAD TRABAJA
        //var Ciudad_Empresa = $("input[name='CiudadEmpresaform']").val();
        //formData.append("CiudadEmpresaform", Ciudad_Empresa ); 
        var Ciudad_Empresa;
        $("select[name='CiudadEmpresaform']").each(function(){
            if($(this).is(":selected")){
                Ciudad_Empresa = $(this).val(); 
                formData.append("CiudadEmpresaform", Ciudad_Empresa);
            }
        });
        
        //OPCION IDENTIDAD
        var opciidentiform;
        $("input[name='opciidentiform']").each(function(){
            if($(this).is(":checked")){
                opciidentiform = $(this).val(); 
                formData.append("opciidentiform", opciidentiform);
            }
        });
        //var opciidentiform = $("input[name='opciidentiform']").val();
        //formData.append("opciidentiform", opciidentiform);  
        
        //DOCUMENTOS DEUDOR
        /*var Documentos = [];
        $("input[name='Documentosform']").each(function(){
            if($(this).is(":checked")){
                Documentos.push($(this).val());
            }
        });
        formData.append("Documentosform", JSON.stringify(Documentos));*/
        
        //STATUS
        //var status = $("input[name='statusprod']").val();
        //formData.append("statusitem", status);
        
        
        /*
        **INFO PERSONAL
        */
        var Primer_Nombre = $("input[name='nombre1form']").val();
        formData.append("nombre1form", Primer_Nombre);           
        
        var Segundo_Nombre = $("input[name='nombre2form']").val();
        formData.append("nombre2form", Segundo_Nombre);
        
        var Primer_Apellido = $("input[name='apellido1form']").val();
        formData.append("apellido1form", Primer_Apellido  ); 
                
        var Segundo_Apellido = $("input[name='apellido2form']").val();
        formData.append("apellido2form", Segundo_Apellido  );  
        
        var Numero_Documento = $("input[name='cedulaform']").val();
        formData.append("cedulaform", Numero_Documento  ); 
        
        var Fecha_Nacimiento = $("input[name='Nacimientoform']").val();
        formData.append("Nacimientoform", Fecha_Nacimiento );
        
       // var Lugar_Nacimiento = $("input[name='LugarNacimientoform']").val();
        //formData.append("LugarNacimientoform", Lugar_Nacimiento );
        
        /*
        **ESCOLARIDAD
        */
        var Profesion = $("input[name='Profesionform']").val();        
        formData.append("Profesionform", Profesion  ); 
        
        var Oficio = $("input[name='Oficioform']").val();
        formData.append("Oficioform", Oficio ); 
        
        /*
        **INFORMACION LABORAL
        */
        var nitform = $("input[name='nitform']").val();
        formData.append("nitform", nitform); 
        
        var Nombre_Empresa = $("input[name='NombreEmpresaform']").val();
        formData.append("NombreEmpresaform", Nombre_Empresa  ); 
        
        var contatoform = $("input[name='contatoform']").val();
        formData.append("contatoform", contatoform  );
        
        
        var Cargo = $("input[name='Cargoform']").val();
        formData.append("Cargoform", Cargo ); 
        
        var Telefono = $("input[name='Telefonoform']").val();
        formData.append("Telefonoform", Telefono  ); 
        
        var Direccion = $("input[name='Direccionform']").val();
        formData.append("Direccionform", Direccion  ); 
        
        //var Ciudad_Empresa = $("input[name='CiudadEmpresaform']").val();
        //formData.append("CiudadEmpresaform", Ciudad_Empresa ); 
        
        /*
        **INFORMACION CONTACTO  
        */        
        var Email = $("input[name='Emailform']").val();
        formData.append("Emailform", Email  ); 
        
        var Telefono1 = $("input[name='Telefono1form']").val();
        formData.append("Telefono1form", Telefono1  ); 
        
        var Telefono2 = $("input[name='Telefono2form']").val();
        formData.append("Telefono2form", Telefono2 ); 
        
        var Direccion_Residencia = $("input[name='DireccionResidenciaform']").val();
        formData.append("DireccionResidenciaform", Direccion_Residencia  ); 
        
        var Complemento = $("input[name='Complementoform']").val();
        formData.append("Complementoform", Complemento  ); 
        
        var Barrio = $("input[name='Barrioform']").val();
        formData.append("Barrioform", Barrio ); 
        
        //var Estrato = $("input[name='Estratoform']").val();    
        //formData.append("Estratoform", Estrato  ); 
        
        var comentariosform = $("textarea[name='comentariosform']").val();    
        formData.append("comentariosform", comentariosform  ); 
        
                
        /*
        **GEOPOSICIONAMIENTO
        */                
        var txtEndereco = $("input[name='txtEndereco']").val();
        formData.append("txtEndereco", txtEndereco  ); 
        
        var countrycod = $("input[name='countrycod']").val();
        formData.append("countrycod", countrycod  ); 
        
        var country = $("input[name='country']").val();
        formData.append("country", country  ); 
        
        var idplacegeomap = $("input[name='mapstore']").val();
        formData.append("idplacegeomap", idplacegeomap  ); 
        
        var usercity1 = $("input[name='usercity1']").val();
        formData.append("usercity1", usercity1  ); 
                
        var usercity2 = $("input[name='usercity2']").val();
        formData.append("usercity2", usercity2  ); 
        
        var usercity3 = $("input[name='usercity3']").val();
        formData.append("usercity3", usercity3  ); 
        
        var userstate = $("input[name='userstate']").val();
        formData.append("userstate", userstate  ); 
        
        var userstateshort = $("input[name='userstateshort']").val();
        formData.append("userstateshort", userstateshort  ); 
        
        var userzip = $("input[name='userzip']").val();
        formData.append("userzip", userzip  ); 
        
        var txtLatitude = $("input[name='txtLatitude']").val();
        formData.append("txtLatitude", txtLatitude  ); 
        
        var txtLongitude = $("input[name='txtLongitude']").val();
        formData.append("txtLongitude", txtLongitude  ); 
        
        /*
        **VARS USUARIO
        */
        formData.append("fieldedit", datafield);
        formData.append("codeuserform", iduser);
        //formData.append("codeitemform", idref);
        formData.append("creditoform", creditoform);
        formData.append("typeuserform", typeuserform);
        
        /*for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        }*/
                
                                                              
        //console.log(formData);               
        if (formData) {
          $.ajax({
            url: "../../appweb/inc/valida-new-usuario-credito.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                var response = JSON.parse(data);
                if (response["error"]) { 
                    $("#wrapadditem"+" .loader").fadeOut(function(){                         
                        var errresponse = response["error"];
                        $("#err"+datafield).html("<div class='alert alert-default alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+errresponse+"</div>").fadeIn("slow"); 
                    }); 
                     //console.log(response);
                }else{
                    //console.log(response);
                    
                    $("#wrapadditem"+" .loader").fadeOut(function(){
                        
                        $("#err"+datafield).fadeOut("slow"); 
                        
                        $("#wrapadditem").html("<div class='alert bg-success text-green alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> "+userlabel+" </h4><p style='display:block;'>Fue creado y asignado al credito con exito</p></div>").fadeIn("slow");
                        
                        //$("#left-barbtn").append("<a href='"+pathfile+"/"+ pathdir+"/new-step6.php' class='btn btn-default margin-hori-xs'><span>Continuar</span><i class='fa fa-chevron-right fa-1x margin-left-xs'></i></a>").show();
                        
                        $("#left-barbtn").show();
                        $("#right-bartbtn").remove();                                                                  
                    });                    
                }              
            }
          });
        }                                
    });
    
    
    
    var $inputMultiImg = $("#imgmutifile");
    $inputMultiImg.fileinput({
        theme: "fa",
        language: 'es',        
        maxFileCount: 5,
        fileTypeSettings: 'image',        
        maxFileSize: 2000,
        maxImageWidth: 1600,
        maxImageHeight: 1600,  
        showUpload: false,
        showRemove: false,       
        showBrowse: false,
        browseOnZoneClick: true,
        //layoutTemplates: {main2: '{preview}  {remove} {browse}'},
        allowedFileExtensions: ["jpg", "jpeg", "png"],                                 
        uploadAsync: false,
        uploadUrl: "../../appweb/inc/upload-imgref.php", 
        
    });/*.on("filebatchselected", function(event, files) {        
        $inputMultiImg.fileinput("upload");
    });*/
    
                
});