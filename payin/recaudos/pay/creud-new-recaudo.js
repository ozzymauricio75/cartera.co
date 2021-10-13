$(document).ready(function(){ 
                       
    //ADD ITEM
    $("#addpay").click(function(){
        //event.preventDefault();
        
        //var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        
        var form = document.getElementById('paycuotaform');
        var formData = new FormData(form);
        
        var field = $(this);
        var parent = $("#wrapadditem");
        //var emtyvalue = $(this).val();
        var datafield = "additem";
        var iduser = $("input[name='cobradorvar']").val();//$("#codeuserform");
        var iditem = $("input[name='recaudovar']").val();
        var refcredito = $("input[name='refcredito']").val();
        var valoracumladocuota = $("input[name='valoracumladocuota']").val();
        var valorcuota = $("input[name='valorpagarcuota']").val();
        var valorfinalcuota = $("input[name='valorfinalcuota']").val();
        var valormora = $("input[name='valormoraestacuota']").val();
        var actimora = $("input[name='cobrarmoraestacuota']").val();
        var numecuota = $("input[name='numecuota']").val();
        var idpresta = $("input[name='prestavar']").val();
        
        
                
        if($("#wrapadditem").find(".loader").length){
            $("#wrapadditem"+" .ok").remove();
            $("#wrapadditem"+" .loader").remove();
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }else{
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }
        
                
        
        /*
        **SOBRE EL CREDITO
        */
        var valorrecibeinput = $("input[name='valorrecibido']").val();
        formData.append("valorrecibidopost", valorrecibeinput);       
        
        var comentainput = $("#comentainput").val();
        formData.append("comentapost", comentainput);       
        
           
        /*
        **VARS USUARIO
        */
        formData.append("fieldedit", datafield);
        formData.append("prestavarpost", idpresta);
        formData.append("uservarpost", iduser);
        formData.append("recaudovarpost", iditem);
        formData.append("refcreditopost", refcredito);
        formData.append("numecuotapost", numecuota);
        
        formData.append("valoracumladocuotapost", valoracumladocuota);
        formData.append("valorcuotapost", valorcuota);
        formData.append("valorfinalcuotapost", valorfinalcuota);
        formData.append("valormorapost", valormora);
        formData.append("actimorapost", actimora);
                        
                                                              
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        }               
        if (formData) {
            $.ajax({
                url: "../../../appweb/inc/valida-new-recaudo.php",
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
                            
                            $("#addpay").fadeOut("slow");

                            $("#wrapadditem").html("<div class='alert bg-success text-green margin-top-md alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> Recaudos </h4><p style='display:block;'>Este pago fue guardado correctamente</p></div><div class='margin-top-md'><a href='"+pathdir+"' class='btn btn-default btn-sm padd-hori-md'><i class='fa fa-chevron-left fa-lg margin-right-xs'></i>Volver a lista de recaudos</a></div>").fadeIn("slow");                                                        
                        });                    
                    }              
                }   
            });
        }                               
    });                                    
});