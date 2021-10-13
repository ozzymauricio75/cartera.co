$(document).ready(function(){ 
                       
    //ADD ITEM
    $("#additembtn").click(function(){
        event.preventDefault();
        
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        
        var field = $(this);
        var parent = $("#wrapadditem");
        var emtyvalue = $(this).val();
        var datafield = "additem";
        var iduser = $("input[name='codeuserform']").val();//$("#codeuserform");
        //var iditem = $("input[name='codeitemform']").val();
        var deudorform = $("input[name='deudorform']").val();
        //var codeudorform = $("input[name='codeudorform']").val();
        //var refpersoform = $("input[name='refpersoform']").val();
        //var reffamiform = $("input[name='reffamiform']").val();
        //var refcomerform = $("input[name='refcomerform']").val();
        var pseudouser = $("input[name='pseudouser']").val();
        
        
        if($("#wrapadditem").find(".loader").length){
            $("#wrapadditem"+" .ok").remove();
            $("#wrapadditem"+" .loader").remove();
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }else{
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }
        
        //CREA OBJETO FORMDATA
        var form = document.getElementById('creditoform');
        var formData = new FormData(form);                      
        /*
        **SELCTS - CHECKBOX - OPTIONGROUP
        */
        
        //TIPO CREDITO
        //var generofrom = $("input[name='Generoform']").val();
        //formData.append("Generoform", generofrom);
        var tipocreditoinput;
        $("input[name='tipocreditoinput']").each(function(){
            if($(this).is(":checked")){
                tipocreditoinput = $(this).val(); 
                formData.append("tipocreditopost", tipocreditoinput);
            }
        });
        
        //PERIOCIDAD
        //var generofrom = $("input[name='EstadoCivilform']").val();
        //formData.append("EstadoCivilform", generofrom);
        var periocidadcuotainput;
        $("input[name='periocidadcuotainput']").each(function(){
            if($(this).is(":checked")){
                //periocidadcuotainput = $(this).val(); 
                periocidadcuotainput = $(this).attr("data-tagperio");
                formData.append("periocidadcuotapost", periocidadcuotainput);
            }
        });
                                       
        //COBRADOR
        var cobradorinput;
        /*$("#cobradorinput").each(function(){
            if($(this).is(":selected")){
                cobradorinput = $(this).val(); 
                formData.append("cobradorpost", cobradorinput);
            }
        });*/
        $("#cobradorinput").change(function(){
            //if($(this).is(":selected")){
                cobradorinput = $(this).val(); 
                formData.append("cobradorpost", cobradorinput);
            //}
        });
        
                        
        //STATUS
        /*var status;// = $("input[name='statusitem']").val();
        //formData.append("statusitem", status);
        $("input[name='statusitem']").each(function(){
            if($(this).is(":checked")){
                //periocidadcuotainput = $(this).val(); 
                status = $(this).val();
                formData.append("statusitem", status);
            }
        });*/
        
        
        
        /*
        **SOBRE EL CREDITO
        */
        var montoinput = $("input[name='montoinput']").val();
        formData.append("montopost", montoinput);           
        
        var valtotalpagarinput = $("input[name='valtotalpagarinput']").val();
        formData.append("valtotalpagarpost", valtotalpagarinput);              
        
        var valutilidadinput = $("input[name='valutilidadinput']").val();
        formData.append("valutilidadpost", valutilidadinput);  
        
        var descricreditoinput = $("#descricreditoinput").val();
        formData.append("descricreditopost", descricreditoinput);
                        
        var fechainiciocreditoinput = $("input[name='fechainiciocreditoinput']").val();
        formData.append("fechainiciocreditopost", fechainiciocreditoinput );
         
        var plazoinput = $("input[name='plazoinput']").val();
        formData.append("plazopost", plazoinput );
        
        //var fechafincreditoinput = $("input[name='fechafincreditoinput']").val();
        //formData.append("fechafincreditopost", fechafincreditoinput );
        
        //var periocidadcuotainput = $("input[name='periocidadcuotainput']").val();
        //formData.append("periocidadcuotapost", periocidadcuotainput );
        
        //var numecuotasinput = $("input[name='numecuotasinput']").val();
        //formData.append("numecuotaspost", numecuotasinput );
        
        //var capitalcuotainput = $("input[name='capitalcuotahidden']").val();
        //formData.append("capitalcuotapost", capitalcuotainput  ); 
                
        //var interescuotainput = $("input[name='interescuotainput']").val();
        //formData.append("interescuotapost", interescuotainput  );  
        
        var moracutoainput = $("input[name='moracutoainput']").val();
        formData.append("moracutoapost", moracutoainput  ); 
        
        //var sobcargocuotainput = $("input[name='sobcargocuotainput']").val();
        //formData.append("sobcargocuotapost", sobcargocuotainput );
        
        //var valtotalcuotainput = $("input[name='valtotalcuotainput']").val();
        //formData.append("valtotalcuotapost", valtotalcuotainput );
        
       
        /*
        **VARS USUARIO
        */
        formData.append("fieldedit", datafield);
        formData.append("codeuserform", iduser);
        //formData.append("codeitemform", iditem);
        formData.append("pseudouserpost", pseudouser);
        
        formData.append("deudorpost", deudorform);
        //formData.append("codeudorpost", codeudorform);
        //formData.append("refpersopost", refpersoform);
        //formData.append("reffamipost", reffamiform);
        //formData.append("refcomerpost", refcomerform);
        
        /*for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        }*/
                
                                                              
        //console.log(formData);               
        if (formData) {
          $.ajax({
            url: "../../appweb/inc/valida-new-credito.php",
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
                        
                        $("#wrapadditem").html("<div class='alert bg-success text-green alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> Credito </h4><p style='display:block;'>Este credito fue guardado correctamente</p></div>").fadeIn("slow");
                        
                        $("#left-barbtn").append("<a href='"+pathfile+"/"+ pathdir+"/' class='btn btn-default margin-hori-xs'><i class='fa fa-th-list fa-1x margin-right-xs'></i><span>Lista de creditos</span></a><button type='button' class='btn btn-info margin-hori-xs godetails'><span>Detalles</span><i class='fa fa-chevron-right fa-1x margin-left-xs'></i></a><script>$(document).ready(function(){ $('button.godetails').click(function(){ $.redirect('"+pathfile+"/"+ pathdir+"/details/',{ itemid_var: "+response+"}); }); }); </script>").show();
                        
                        $("#right-bartbtn").remove();                                                                  
                    });                    
                }              
            }
          });
        }                                
    });                                    
});