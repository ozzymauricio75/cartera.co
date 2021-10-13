$(document).ready(function(){ 
                       
    //ADD ITEM
    $("#additembtn").click(function(){

        event.preventDefault();
        
        
        /*
        ===================================
        */
        
        /*
        ======================================
        */
        
        
        
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        
        var field = $(this);
        var parent = $("#wrapadditem");
        var emtyvalue = $(this).val();
        var datafield = "additem";
        var iduser = $("input[name='codeuserform']").val();//$("#codeuserform");
        var iditem = $("input[name='codeitemform']").val();
        var pseudouser = $("input[name='pseudouser']").val();
        
        if($("#wrapadditem").find(".loader").length){
            $("#wrapadditem"+" .ok").remove();
            $("#wrapadditem"+" .loader").remove();
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }else{
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }
        
        //CREA OBJETO FORMDATA
        var form = document.getElementById('deudorform');
        var formData = new FormData(form);
                                    
                                
        /*
        **SELCTS - CHECKBOX - OPTIONGROUP
        */
        
                                                
        //TIPO DE VIVIENDA
        var tipodeviviendafrom;
        $("select[name='TipoViviendaform']").each(function(){
            if($(this).is(":selected")){
                tipodeviviendafrom = $(this).val(); 
                formData.append("TipoViviendaform", tipodeviviendafrom);
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
                        
        var Primer_Apellido = $("input[name='apellido1form']").val();
        formData.append("apellido1form", Primer_Apellido  ); 
                               
        var Numero_Documento = $("input[name='cedulaform']").val();
        formData.append("cedulaform", Numero_Documento  ); 
        
        var Fecha_Nacimiento = $("input[name='Nacimientoform']").val();
        formData.append("Nacimientoform", Fecha_Nacimiento );
        
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
        
        var Barrio = $("input[name='Barrioform']").val();
        formData.append("Barrioform", Barrio ); 
        
                
        /*
        **INFO CUENTA
        */
        var nombreusuario = $("input[name='userform']").val();
        formData.append("userform", nombreusuario);           
                        
        var contrasenausuario = $("input[name='passform']").val();
        formData.append("passform", contrasenausuario  ); 
        
        /*
        **VARS USUARIO
        */
        formData.append("fieldedit", datafield);
        formData.append("codeuserform", iduser);
        formData.append("codeitemform", iditem);
        formData.append("pseudouserpost", pseudouser);
        
        /*for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        }*/
                
                                                              
        //console.log(formData);               
        if (formData) {
          $.ajax({
            url: "../../appweb/inc/valida-new-cobrador.php",
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
                        
                        $("#wrapadditem").html("<div class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> Deudor </h4><p style='display:block;'>La información del cobrador fue guardada con exito.</p></div>").fadeIn("slow");
                        
                        $("#left-barbtn").append("<a href='../collectors.php' class='btn btn-default'><i class='fa fa-arrow-left fa-lg margin-right-xs'></i><span>Lista de cobradores</span></a>").show();
                        
                        $("#right-bartbtn").remove();                                                                                                                                                                         
                    });
                    
                }
              
            }
          });
        }                                
    });
                                
});