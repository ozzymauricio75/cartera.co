$(document).ready(function(){ 
                       
    //ADD ITEM
    $("#addpay").click(function(){
        
        var field = $(this);
        var parent = $("#wrapadditem");
        //var emtyvalue = $(this).val();
        var datafield = "additem";
        var iduser = $("input[name='cobradorvar']").val();
        var idpresta = $("input[name='prestavar']").val();
        var refruta = $("input[name='refruta']").val();
                                
        if($("#wrapadditem").find(".loader").length){
            $("#wrapadditem"+" .ok").remove();
            $("#wrapadditem"+" .loader").remove();
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }else{
            $("#wrapadditem").append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }
        
        var form = document.getElementById('expenseform');
        var formData = new FormData(form);
        
        /*
        **SOBRE LOS GASTOS
        */
        var valorgastosinput = $("input[name='valorgastos']").val();
        formData.append("valorgastospost", valorgastosinput);           
        var descrigastosinput = $("#descrigastosinput").val();
        formData.append("descrigastospost", descrigastosinput);           
           
        /*
        **VARS USUARIO
        */
        formData.append("fieldedit", datafield);
        formData.append("uservarpost", iduser);
        formData.append("prestavarpost", idpresta);
        formData.append("refrutapost", refruta);
        
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        }               
        if (formData) {
            $.ajax({
                url: "../../appweb/inc/valida-new-gasto.php",
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

                            $("#wrapadditem").html("<div class='alert bg-success text-green margin-top-md alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> Gastos </h4><p style='display:block;'>Este registro de gastos fue guardado correctamente</p></div>").fadeIn("slow");                                                        
                        });                    
                    }              
                }   
            });
        }                             
    });                                    
});