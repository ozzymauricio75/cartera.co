$(document).ready(function(){

    //ADD ITEM
    $("#addpayabono").click(function(){
        //event.preventDefault();

        //var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();

        var form = document.getElementById('paycuotaabonoform');
        var formData = new FormData(form);

        var field = $(this);
        var parent = $("#wrapadditem");
        //var emtyvalue = $(this).val();
        var datafield = "additem";
        var iduser = $("input[name='cobradorvarabono']").val();//$("#codeuserform");
        var iditem = $("input[name='recaudovarabono']").val();
        var refcredito = $("input[name='refcreditoabono']").val();
        var valoracumladocuota = $("input[name='valoracumladocuotaabono']").val();
        var valorcuota = $("input[name='valorpagarcuotaabono']").val();
        var valorfinalcuota = $("input[name='valorfinalcuotaabono']").val();
        var valormora = $("input[name='valormoraestacuotaabono']").val();
        var actimora  = $("input[name='cobrarmoraestacuotaabono']").val();
        var numecuota = $("input[name='numecuotaabono']").val();
        var idpresta = $("input[name='prestavarabono']").val();

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
        var valorrecibeinput = $("input[name='valorrecibidoabono']").val();
        formData.append("valorrecibidoabonopost", valorrecibeinput);

        var comentainput = $("#comentainput").val();
        formData.append("comentapost", comentainput);


        /*
        **VARS USUARIO
        */
        formData.append("fieldedit", datafield);
        formData.append("prestavarabonopost", idpresta);
        formData.append("uservarabonopost", iduser);
        formData.append("recaudovarabonopost", iditem);
        formData.append("refcreditoabonopost", refcredito);
        formData.append("numecuotaabonopost", numecuota);

        formData.append("valoracumladocuotaabonopost", valoracumladocuota);
        formData.append("valorcuotaabonopost", valorcuota);
        formData.append("valorfinalcuotaabonopost", valorfinalcuota);
        formData.append("valormoraabonopost", valormora);
        formData.append("actimoraabonopost", actimora);

        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]);
        }
        if (formData) {
            $.ajax({
                url: "../../../appweb/inc/valida-new-abono.php",
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

                            $("#addpayabono").fadeOut("slow");

                            $("#wrapadditem").html("<div class='alert bg-success text-green margin-top-md alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> Abonos </h4><p style='display:block;'>Este abono fue guardado correctamente</p></div><div class='margin-top-md'><a href='"+pathdir+"' class='btn btn-default btn-sm padd-hori-md'><i class='fa fa-chevron-left fa-lg margin-right-xs'></i>Volver a lista de abonos</a></div>").fadeIn("slow");
                        });
                    }
                }
            });
        }
    });
});
