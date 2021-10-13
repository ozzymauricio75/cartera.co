<?php require_once '../../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../../cxconfig/config.inc.php'; ?>
<?php require_once '../../../cxconfig/global-settings.php'; ?>
<?php require_once '../../../appweb/inc/sessionvars-payin.php'; ?>
<?php require_once '../../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../../appweb/inc/query-payin.php'; ?>
<?php require_once '../../../i18n-textsite.php'; ?>

<?php 
//recibe datos de PEDIDOS
$idOrder = "";
$dataOrders = array();

/***********************************\
*TITULO DESARROLLO
*Autor
*Fecha
*version
*------------------------------------
\************************************/

/*
*TITULO SECCION
*====================================
*/

/*
*SUBTITULO SECCION
*/


/**
 **EXPLICACION/PROLOGO BLOQUE CODIGO
 ' paso 1
 ' paso 2
 ' paso 3
*/

//COMENTARIO ENTRE LINEAS CODIGO

/*[INICIO|FIN bloque codigo]*/


$loockVar = "false";
$idItemPOST = "";
$datasCuotaRuta = array();
if(isset($_POST['itemid_var']) && $_POST['itemid_var'] != ""){
    
    //Validacion variable POST
    $idItemPOST = $_POST['itemid_var'];
    $valida_idItemPOST = validaAlphaDash($idItemPOST, "1");//validaInteger($idItemPOST, "1");
    
    $idCollectPost = $_POST['collectid_var'];
    $valida_idCollectPOST = validaInteger($idCollectPost, "1");
    
    //Validacion TRUE
    if($valida_idItemPOST === true && $valida_idCollectPOST === true){   
        
        $loockVar = "true";
        $valorAcumulado = 0;
        $valorMoraAcumulado = 0;
        $valorRecaudosAcumulado = 0;
        $valorMoraAcumulado = queryValorMoraAcumulado($idItemPOST); 
        $valorRecaudosAcumulado = queryValorAcumulado($idItemPOST);                
        $valorRecaudosAcumulado = $valorRecaudosAcumulado + $valorMoraAcumulado;
        $datasCuotaRuta = queryCuotas_FULL($idCollectPost);
        
        //echo $valorMora;
        if(is_array($datasCuotaRuta) && !empty($datasCuotaRuta)){
            foreach($datasCuotaRuta as $dcrKey){
                            
                //DATAS RECAUDO RUTA
                $id_acreedor_ruta = $db->escape($datasCuotaRuta['idacreedor']);
                $id_recaudo_ruta = $db->escape($datasCuotaRuta['idrecaudo']);
                $consecutivo_credito_ruta = $db->escape($datasCuotaRuta['consecutivo']);
                $numecuota_credito_ruta = $db->escape($datasCuotaRuta['numeroCuota']);
                $valor_cuota_ruta = $db->escape($datasCuotaRuta['valorCuota']);
                $valor_faltante_ruta = $db->escape($datasCuotaRuta['valorFaltanteCuota']);
                $valor_recalculado_ruta = $db->escape($datasCuotaRuta['valorRecalculado']);
                $idstatus_recaudo_ruta = $db->escape($datasCuotaRuta['idstatusrecaudo']);
                $actiMora_ruta = $db->escape($datasCuotaRuta['activamora']);
                $valorMora_ruta = $db->escape($datasCuotaRuta['valorMora']);
                $datas_credito_ruta = $datasCuotaRuta['datascredito'];
                $fecha_credito_ruta = date("d/m/Y",strtotime($datasCuotaRuta['fechaMaxRecaudo']));
                
                $valorMoraFormat = number_format($valorMora_ruta, 0, ',', '.');
                                                
                //DEFINE STATUS RECAUDO
                $statusRecaudo = "";
                switch($idstatus_recaudo_ruta){

                    case "1":
                        $statusRecaudo = "Pagado";    
                    break;
                    case "2":
                        $statusRecaudo = "Abono";    
                    break;
                    case "3":
                        $statusRecaudo = "Por pagar";    
                    break;
                }

                //RECALCULO PROXIMA CUOTA 
                $valorFinalCuota = 0;
                if($valor_faltante_ruta == 0){
                    $valorFinalCuota = $valor_cuota_ruta;
                    $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
                }else{
                    $valorFinalCuota = $valor_faltante_ruta;    
                    $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
                }
                
                //VALOR MORA - ACUMULADO
                if($valorRecaudosAcumulado == $valorFinalCuota){
                    $valorAcumulado = 0;
                    $valorAcumuladoFormat = number_format($valorAcumulado, 0, ',', '.');
                }else{
                    $valorAcumulado = $valorRecaudosAcumulado;
                    $valorAcumuladoFormat = number_format($valorAcumulado, 0, ',', '.');
                }

                //DATAS CREDITO RUTA
                $nombreCompletoDeudorRuta = "";
                if(is_array($datas_credito_ruta) && !empty($datas_credito_ruta)){
                    foreach($datas_credito_ruta as $dcredrKey){
                        $datas_deudor_ruta = $datas_credito_ruta['datadeudor'];

                        //DATAS DEUDOR RUTA
                        if(is_array($datas_deudor_ruta) && !empty($datas_deudor_ruta)){
                            foreach($datas_deudor_ruta as $ddrKey){
                                $nombre_deudor_ruta = $db->escape($datas_deudor_ruta['primer_nombre_deudor']);
                                $apellido_deudor_ruta = $db->escape($datas_deudor_ruta['primer_apellido_deudor']);

                                $nombreCompletoDeudorRuta = $nombre_deudor_ruta."&nbsp;".$apellido_deudor_ruta;

                                $direccion_deudor_ruta = $db->escape($datas_deudor_ruta['dir_geo_deudor']);
                            }//[FIN FOREACH| $datas_deudor_ruta]
                        }//[FIN IF | $datas_deudor_ruta]
                        
                    }//[FIN FOREACH| $datas_credito_ruta]
                }//[FIN IF | $datas_credito_ruta]

            }//[FIN FOREACH| $datasCuotaRuta]
        }//[FIN IF | $datasCuotaRuta]
        
        
        /*
        *CONOCER PROGRESO DEL CREDITO
         'muestra el comportamiento del credito, sus cuotas, valores en mora, valor por pagar
         'permite recalcular el valor real final del credito a pagar vs el valor a pagar inicial del credito
         'nos inidca si estos valores son iguales o por el contrario el valor final del credito va a ser mayor, debido al cobro de valores mora
         'al final comparamos estos valores para determi9nar si el credito se a cumplido en su totalidad
        */
        
        //INFO CREDITO - PLAN PAGOS
        $credito_sq = $db->subQuery('csq');         
        $credito_sq->where('code_consecutivo_credito', $idItemPOST); 
        $credito_sq->getOne('creditos_tbl', 'id_creditos');

        $db->join($credito_sq, 'csq.id_creditos=ppsq.id_credito');         
        $ppago_sq = $db->getOne('planes_pago_tbl ppsq', 'ppsq.id_credito, ppsq.valor_credito_plan_pago, ppsq.valor_pagar_credito, ppsq.numero_cuotas_plan_pago, ppsq.id_credito');
        //echo "ARRAY PLAN PAGOS<pre>";
        //print_r($ppago_sq);
                
        $real_numero_cuotas_plan = $ppago_sq['numero_cuotas_plan_pago'];
        $real_valor_credito = $ppago_sq['valor_pagar_credito'];
        
        //echo $real_numero_cuotas_plan."\ntotal cuotas credito\n";
        
        //PROGRESO RECAUDOS
        $db->where('ref_recaudo', $idItemPOST);            
        $querySeguimientoCredito = $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, id_status_recaudo, numero_cuota_recaudos, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, activa_mora, valor_mora_cuota_recaudo");
        
        $real_valor_credito = 0;
        $real_valor_pagado_credito = 0;
        
        if(is_array($querySeguimientoCredito) && !empty($querySeguimientoCredito)){
            foreach($querySeguimientoCredito as $qscKey){
                $real_cuota_mora_activa = $qscKey['activa_mora'];
                $real_valor_mora = $qscKey['valor_mora_cuota_recaudo'];
                $real_valor_cuota = $qscKey['total_cuota_plan_pago'];
                $real_valor_recaudado = $qscKey['total_valor_recaudado_estacuota'];
                //$real_numero_cuotas_plan = $qscKey['numero_cuota_recaudos'];
                
                //defino las cuotas que se les hayan aplicado mora
                $real_aplica_mora = ($real_cuota_mora_activa == 1)? $real_valor_mora : 0;
                
                //calcula el valor recaudado
                $real_valor_pagado_credito = $real_valor_pagado_credito + $real_valor_recaudado;
                
                //calcula el valor final real del credito
                $real_valor_credito = $real_valor_credito + ($real_valor_cuota + $real_aplica_mora);
            }
        }
        //$real_valor_credito = $real_valor_credito + $valorAcumuladoCuotaPost;
        //echo $real_valor_credito."valor pagar credito REAL\n\n";
        
        /*
        *PROGRESO CREDITO
        */
        //calcula el progreso de pago del credito hasta el momento
        $real_progreso_credito = $real_valor_pagado_credito/* + $valorRecibidoPost*/;
        //echo $real_valor_pagado_credito."valor pagado hasta ahora\n\n";
        
        //define credito pagado (2) o por pagar (1)
        $status_credito = 1;
        if($real_progreso_credito >= $real_valor_credito){
            //echo "CREDITO PAGADO COMPLETGAMENTE\n\n";
            $status_credito = 2;
        }
                
        //define valor total del credito , que aun falta por pagar
        $real_deuda_actual = $real_valor_credito - $real_valor_pagado_credito;
        //echo $real_deuda_actual."VALOR TODAVIA DEBE\n\n";
        
        $porcentaje_progreso = floor(($real_valor_pagado_credito/$real_valor_credito)*100);
        
        $real_deuda_actual_format = number_format($real_deuda_actual, 0, ',', '.');
        
        
        
    }//[FIN IF | $valida_idItemPOST]
}//[FIN IF | POST]


        
//***********
//SITE MAP
//***********

$rootLevel = "recaudos";
$sectionLevel = "";
$subSectionLevel = "";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo METATITLE ?></title>    
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="author" content="massin">
    <?php echo _CSSFILESLAYOUT_ ?>    
    <link rel="stylesheet" href="../../../appweb/plugins/datatables/dataTables.bootstrap.css">
    
    <!---switchmaster--->    
    <link rel="stylesheet" href="../../../appweb/plugins/switchmaster/css/bootstrap3/bootstrap-switch.min.css">
    <?php echo _FAVICON_TOUCH_ ?>    

</head>
    
<?php echo LAYOUTOPTION ?><!---//print body tag--->    

    
<div class="wrapper">            
    <?php
    /*
    /
    ////HEADER
    /
    */
    ?>
    <?php include '../../../appweb/tmplt/header-payin.php';  ?>           
    
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php //include '../../../appweb/tmplt/side-mm.php';  ?>
    <?php
    /*
    /
    ////WRAP CONTENT
    /
    */
    ?>        
    <div class="content-wrapper box50" style="padding-top:20px; margin-left: auto; margin-right:auto;">
        <?php
        /*
        /*****************************//*****************************
        /HEADER CONTENT
        /*****************************//*****************************
        */
        ?>
        <section class="content-header bg-content-header">
            <h1>
                <span class="text-size-x6">Recaudos</span> / Pagar
            </h1>      
            <a href="<?php echo $pathmm."/".$payinDir."/recaudos/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de recaudos
            </a> 
        </section>
        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <?php if($loockVar != "false"){ ?>
        <div class="row">
            <div class="col-xs-12">
                <!-- Progress bars -->
                <div class="clearfix">
                    <span class="pull-left text-red"><?php echo "<span class='margin-left-xs text-size-x6'>$</span><span class='margin-left-xs text-size-x5'>".$real_deuda_actual_format."</span>"; ?></span>
                    <small class="pull-right text-green"><?php echo "<span class='margin-left-xs text-size-x6'>".$porcentaje_progreso."%</span>"; ?></small>
                </div>
                <div class="progress active">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $porcentaje_progreso."%"; ?>">
                        <span class="sr-only">20% Complete</span>
                    </div>
                
                </div>
                
            </div>
        </div>
        <section class="content margin-bottom-md">
            <div class="row">
                
                <div class="col-xs-12">
                    <p>
                        <strong>Deudor</strong>
                        <span class="pull-right text-size-x4"><?php echo $nombreCompletoDeudorRuta; ?></span>
                    </p>
                    <p >
                        <strong>Ref: Credito</strong>
                        <span class="pull-right text-size-x4"><?php echo $consecutivo_credito_ruta; ?></span>
                    </p>
                    
                    <p>
                        <strong>Valor acumulado</strong>
                        <span class="pull-right text-size-x4 text-red"><span class="margin-right-xs">$</span><?php echo $valorAcumuladoFormat; ?></span>
                    </p>
                    
                    <hr/>
                    <p>
                        <strong># Cuota actual</strong>
                        <span class="pull-right text-size-x4"><?php echo $numecuota_credito_ruta; ?></span>
                    </p>
                    <p>
                        <strong>Valor cuota actual</strong>
                        <span class="pull-right text-size-x4 text-blue"><span class="margin-right-xs">$</span><?php echo $valorFinalCuotaFormat; ?></span>
                    </p>
                    <p>
                        <strong>Fecha cuota actual</strong>
                        <span class="pull-right text-size-x4"><?php echo $fecha_credito_ruta; ?></span>
                    </p>
                    
                </div> 
                               
            </div>
        </section>
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <form method="post" id="paycuotaform" class="newrecaudoform">
                                                
                        <!--<div class="margin-bottom-xs">
                            <label class="margin-right-md">Abono</label>
                            <input id="switchabono" type="checkbox" name="" data-size="mini" class="opcicredit ">
                        </div>
                        <div class="form-group">
                            <div class="input-group">                                            
                                <div class="input-group-addon"><i class="fa fa-dollar"></i></div>
                                <input type="number" id="abonoval" name="abonocutoainput" class="form-control " value =""  placeholder="0">
                            </div>
                        </div> --> 
                        
                        <div class="form-group">
                            <label>Valor recibido</label>
                            <div class="input-group">                                            
                                <div class="input-group-addon"><i class="fa fa-dollar"></i></div>
                                <input type="text" class="form-control monedaval" name="valorrecibido"/>
                            </div>
                        </div>
                        
                        <!--<div class="form-group">                            
                            <p>
                                <strong>Debe</strong>
                                <span class="pull-right text-red text-size-x3 "><span class="margin-right-xs">$</span><span id="valordebe">0</span></span>
                            </p>
                        </div> -->
                        
                        <div class="margin-bottom-xs">
                            <label class="margin-right-md">Mora</label>
                            <input id="switchmora" type="checkbox" name="" data-size="mini" class="opcicredit ">
                        </div>
                        <div class="form-group">
                            <div class="input-group">                                            
                                <div class="input-group-addon"><i class="fa fa-dollar"></i></div>
                                <input type="text" id="moraval" name="moracutoainput" class="form-control " value ="<?php echo $valorMoraFormat; ?>"  placeholder="0">
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            <label class="margin-right-md">Comentarios</label>
                            <textarea id="comentainput" name="comentainput" class="form-control" placeholder="Escribe observaciones que necesites destacar en este recaudo" style="width: 100%; height: 120px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize:none;"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <p id="valorfinal"></p>                            
                        </div> 
                        
                        <button id="addpay" type="button" class="btn btn-info btn-flat btn-block">Pagar</button>
                        
                        <input type="hidden" name="numecuota" value="<?php echo $numecuota_credito_ruta; ?>">
                        <input type="hidden" name="prestavar" value="<?php echo $id_acreedor_ruta; ?>">
                        <input type="hidden" name="cobradorvar" value="<?php echo $idSSUser; ?>">
                        <input type="hidden" name="recaudovar" value="<?php echo $id_recaudo_ruta; ?>">
                        <input type="hidden" name="refcredito" value="<?php echo $consecutivo_credito_ruta; ?>">
                        <input type="hidden" name="valorpagarcuota" value="<?php echo $valor_cuota_ruta; ?>">
                        <input type="hidden" name="valorfinalcuota" value="<?php echo $valorFinalCuota; ?>">
                        <input type="hidden" name="valoracumladocuota" value="<?php echo $valorRecaudosAcumulado; ?>">
                        <input type="hidden" name="valormoraestacuota" value="<?php echo $valorMora_ruta; ?>">
                        <input type="hidden" id="cobrarmoraestacuota" name="cobrarmoraestacuota">
                        
                    </form> 
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12">
                    <div id="wrapadditem"></div>
                    <div id="erradditem"></div>                        
                </div>
            </div>
        </section>
        
        
        <?php }else{ ?>
        
        <section class="content ">                    
            <div class="box50">
                <div class="alert">
                    <div class="media text-muted">
                        <div class=" media-left">
                            <i class="fa fa-unlink fa-4x"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Oops!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                No encontramos o fue eliminada EL RECAUDO que deseas visualizar 
                            </p>              
                            <p style="font-size:1.232em; line-height:1;"> Asegurate que seleccionaste el recaudo correcto, e intentalo de nuevo</p>
                        </div>

                    </div>                    
                </div>
                
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group text-center">
                            <a href="<?php echo $pathmm."/payin/recaudos/"; ?>" type="button" class="btn btn-default">
                                <i class='fa fa-th-list fa-lg margin-right-xs'></i>
                                <span>Ir a Recaudos</span>                        
                            </a> 
                        </div>
                    </div> 
                </div>
            </div>
        </section>
        
        
        <?php } ?>
       
        
    </div>
    
    <?php echo "<input id='pathdir' type='hidden' value='".$pathmm."/payin/recaudos/'/>"; ?>
    <?php echo "<input id='paththisfile' type='hidden' value='".$pathFile."/pay/'/>"; ?>
    <?php
    /*
    /
    ////FOOTER
    /
    */
    ?>
    <?php //include 'appweb/tmplt/footer.php';  ?>
    <?php
    /*
    /
    ////RIGHT BAR
    /
    */
    ?>
    <?php //include '../../../appweb/tmplt/right-side.php';  ?>
</div>
<?php echo _JSFILESLAYOUT_ ?>
<script src="creud-new-recaudo.js" type="text/javascript"></script>
    
<script src="../../../appweb/plugins/misc/jquery.redirect.js"></script>      
<!---switchmaster---> 
<script src="../../../appweb/plugins/switchmaster/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../../appweb/plugins/jquery.number.js"></script>      
<script>
$(document).ready(function(){
    var detailurl = $("#paththisfile").val();

    $('button.godetails').each(function(){ 
        
        var itemid = $(this).attr("data-id");
        
        $(this).click(function(){                   
            //window.location = detailurl+"?itemid_var="+itemid;
            $.redirect(detailurl,{ itemid_var: itemid}); 
        });
    });
    
    $('.monedaval').number( true, 0 );
    
    /*
    *CALCULA NUMERO DE CUOTAS
    */
    
    //VALOPR TOTAL CUOTA
    $(".newplanpago").keyup(function(){
        //var capitalinput = $(this).find('input[name="capitalcuotahidden"]').val(); 
        //var interesinput = $(this).find('input[name="interescuotainput"]').val(); 
        //var morainput = $(this).find('input[name="moracutoainput"]').val(); 
        //var sobcargoinput = $(this).find('input[name="sobcargocuotainput"]').val(); 
        //var numecuotas = $(this).find('input[name="numecuotasinput"]').val(); 
        var valorrecibido = $(this).find('input[name="valorrecibido"]').val();
        var valorpagar = $(this).find('input[name="valorpagarcuota"]').val();
        
         
        
        //var valtotalcuota = parseFloat(capitalinput) + parseFloat(interesinput);//+parseFloat(morainput)+parseFloat(sobcargoinput);
        
        /*if(morainput != ""){
            valtotalcuota += parseFloat(morainput) 
        }
        
        if(sobcargoinput != ""){
            valtotalcuota += parseFloat(sobcargoinput) 
        }*/
        
        //var totalpagar =  parseFloat(valtotalcuota) * parseInt(numecuotas);
        var valordebe = parseFloat(valorpagar) - parseFloat(valorrecibido);
        
        //$(this).find('input[name="valorcuotainput"]').val(valtotalcuota).number( true, 2 );
        //$(this).find('input[name="valtotalcuotainput"]').val(valtotalcuota).number( true, 2 );
        //$(this).find('input[name="valtotalcuotafullinput"]').val(valtotalcuota);
        //$(this).find('input[name="valtotalcuotafullinput"]').val(valtotalcuota);
        
        //$(this).find('input[name="valtotalpagarinput"]').val(totalpagar).number( true, 2 );
        if(valordebe <= valorpagar){
            
            //$(this).find('#valordebe').html(valordebe).number( true, 2 );    
        }else if(valordebe >= valorpagar){
            valordebe = 0;
           // $(this).find('#valordebe').html(valordebe).number( true, 2 );    
        }
        
        
        
    });
    
    
    
});    
    
$(".opcicredit").bootstrapSwitch(); 
$("#moraval").attr("disabled", "disabled");
$("#abonoval").attr("disabled", "disabled");    
    
$("#switchmora").on('switchChange.bootstrapSwitch', function(event, state) {
    if($(this).is(':checked')) {
        //$("#moraval").removeAttr("disabled"); 
        $("#cobrarmoraestacuota").val("ok"); 
    }else{
        //$("#moraval").attr("disabled", "disabled");           
        $("#cobrarmoraestacuota").val(""); 
    }
});
    
$("#switchabono").on('switchChange.bootstrapSwitch', function(event, state) {
    if($(this).is(':checked')) {
        //$("#abonoval").removeAttr("disabled"); 
        /*var moraval = $("#moraval").val();
        var debefinal = $('#valordebe').html();
        
        var valordebefinal = parseFloat(moraval) - parseFloat(debefinal);
        
        $(".newplanpago").on(function(){
            $(this).find('#valordebe').html(valordebefinal).number( true, 2 );       
        });*/
        $("#cobrarmoraestacuota").attr("val", "ok"); 
        
        
    }else{
        $("#abonoval").attr("disabled", "disabled");           
    }
});    
</script>      
</body>
</html>
