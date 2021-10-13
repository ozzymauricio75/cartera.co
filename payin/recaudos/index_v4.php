<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars-payin.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-payin.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>

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
    <link rel="stylesheet" href="../../appweb/plugins/datatables/dataTables.bootstrap.css">
    <?php echo _FAVICON_TOUCH_ ?>    
    
    <style>
      #map {
        height: 420px;
      }
    </style>
    
    
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
    <?php include '../../appweb/tmplt/header-payin.php';  ?>           
    
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php //include '../../appweb/tmplt/side-mm.php';  ?>
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
        <!--<section class="content-header bg-content-header">
            <h1>
                <span class="text-size-x6">Cobranza</span> / Rutas detalle
            </h1>      
            <a href="<?php //echo $pathmm."/collect/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de rutas
            </a> 
        </section>-->
        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>

        <section class="box50">
            <div class="row">
                
                
                         
                <?php
                
                //fecha actual humano
                //esta variable me permite compararr la fecha de pago realizado para desactivar el boton de cobrar en caso de que ya se haya realizado cobro en la fecha actual
                $fechaActualHumano = date("d/m/Y", strtotime($dateFormatDB));
                                                
                $db->where("id_cobrador", $idSSUser);   
                $db->where('fecha_creacion_ruta', $dateFormatDB);        
                $queryR = $db->getOne ("rutas_tbl", "id_ruta");
                $idRutaHoy = $queryR['id_ruta'];
                /*
                *DETALLES DE LOS RECAUDOS PARA ESTA RUTA
                */
                $datasCuotasRuta = queryRecaudosRutaDetalles($idRutaHoy); //queryCreditosDetalles($idSSUser, $idItemPOST);

                //echo "<pre>";
                //print_r($datasCuotasRuta);
                /*[INICIO |$datasCuotasRuta]*/
                                                
                if(is_array($datasCuotasRuta) && !empty($datasCuotasRuta)){
                    $prevAcumula = "";
                    $prevBox = "";
                    /*[INICIO |LAYOUT <COL-XS-12>]*/
                    $cuotas_ruta_lyt = "<div class='col-xs-12'>";
            
                    /*[INICIO FOREACH |$datasCuotasRuta]*/
                    foreach($datasCuotasRuta as $dcrKey){
                       $valorFinal = 0;       
                        $id_recaudo_ruta = $dcrKey['idrecaudo'];
                        $id_especifica_ruta = $dcrKey['idespecificaruta'];
                        $id_credito_ruta = $dcrKey['idcreditos'];       
                        $ref_credito_ruta = $dcrKey['refcredito'];
                        $datas_recaudo_ruta = $dcrKey['datasrecaudo'];                        
                        $acumulado = 0;
                        //DATAS RECAUDO RUTA
                        /*[INICIO FOREACH |$datas_recaudo_ruta]*/
                        if(is_array($datas_recaudo_ruta) && !empty($datas_recaudo_ruta)){
                            
                            foreach($datas_recaudo_ruta as $dcrrKey){
                                                             
                                $idRecaudo_credito_ruta = $datas_recaudo_ruta['idrecaudo'];
                                $consecutivo_credito_ruta = $datas_recaudo_ruta['consecutivo'];
                                $numecuota_credito_ruta = $datas_recaudo_ruta['numeroCuota'];
                                $activa_mora_credito_ruta = $datas_recaudo_ruta['activamora'];
                                $valor_mora_credito_ruta = $datas_recaudo_ruta['valorMora'];
                                $valor_cuota_ruta = $datas_recaudo_ruta['valorCuota'];
                                $valor_recalculado_ruta = $datas_recaudo_ruta['valorRecalculado'];
                                $valor_recaudado_ruta = $datas_recaudo_ruta['valorRecaudado'];
                                $valor_faltante_ruta = $datas_recaudo_ruta['valorFaltanteCuota'];
                                $idstatus_recaudo_ruta = $datas_recaudo_ruta['idstatusrecaudo'];
                                $datas_credito_ruta = $datas_recaudo_ruta['datascredito'];
                                $fecha_maxrecaudo_ruta = ($datas_recaudo_ruta['fechaMaxRecaudo'] == "0000-00-00")? "0000-00-00" : date("d/m/Y", strtotime($datas_recaudo_ruta['fechaMaxRecaudo']));
                                $fecha_recaudado_ruta = ($datas_recaudo_ruta['fechaRecaudado'] == "0000-00-00")? "0000-00-00" : date("d/m/Y", strtotime($datas_recaudo_ruta['fechaRecaudado']));
                                
                                $valor_faltante_rutaFormat = number_format($valor_faltante_ruta, 0, ',', '.');
                                                                         
                                //DEFINE STATUS RECAUDO
                                $statusRecaudo = "";
                                $pagar_btn = "";
                                switch($idstatus_recaudo_ruta){

                                    case "1":
                                        $statusRecaudo = "<span class='badge bg-green padd-hori-md'>Pagado</span>";    
                                        $pagar_btn = "disabled";
                                    break;
                                    case "2":
                                        $statusRecaudo = "<span class='badge bg-orange padd-hori-md'>Abono</span>";    
                                    break;
                                    case "3":
                                        $statusRecaudo = "<span class='badge bg-black padd-hori-md'>Por pagar</span>";    
                                    break;
                                }

                                //RECALCULO PROXIMA CUOTA 
                                $valorFinalCuota = 0;
                                $valorFinalCuota = $valor_cuota_ruta;
                                $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
                                
                                /*if($valor_faltante_ruta == 0){
                                    $valorFinalCuota = $valor_cuota_ruta;
                                    $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
                                }else{
                                    $valorFinalCuota = $valor_faltante_ruta;    
                                    $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
                                }*/
                                
                                
                                
                                 
                                //DATAS CREDITO RUTA
                                if(is_array($datas_credito_ruta) && !empty($datas_credito_ruta)){
                                    foreach($datas_credito_ruta as $dcredrKey){
                                        $datas_deudor_ruta = $datas_credito_ruta['datadeudor'];

                                        //DATAS DEUDOR RUTA
                                        if(is_array($datas_deudor_ruta) && !empty($datas_deudor_ruta)){
                                            foreach($datas_deudor_ruta as $ddrKey){
                                                $nombre_deudor_ruta = $datas_deudor_ruta['primer_nombre_deudor'];
                                                $apellido_deudor_ruta = $datas_deudor_ruta['primer_apellido_deudor'];

                                                $nombreCompletoDeudorRuta = $nombre_deudor_ruta."&nbsp;".$apellido_deudor_ruta;
                                                                                                
                                                $tel1_deudor_ruta = $datas_deudor_ruta['tel_uno_deudor'];    
                                                $tel2_deudor_ruta = $datas_deudor_ruta['tel_dos_deudor'];
                                                $direccion_deudor_ruta = $datas_deudor_ruta['dir_geo_deudor'];
                                            }
                                        }
                                    }
                                }
                            }                            
                        }
                        /*[FIN FOREACH |$datas_recaudo_ruta]*/
                        
                        
                        /*
                        *LAYOUT DETALLES CUOTAS RUTA
                        */        
                        
                        //[INICIO PREVBOX]
                        if($prevBox != $consecutivo_credito_ruta){
                           
                            if($prevBox != ""){      
                                $cuotas_ruta_lyt .= "</div>";//[FIN | BOX-BODY]
                                $cuotas_ruta_lyt .= "</div>";//[FIN | BOX]
                            }
                                                       
                            //calculamos el acumulado para cada credito
                            $totalAcumuladoCredito = 0;
                            $totalAcumuladoCredito = queryValorAcumulado($consecutivo_credito_ruta);
                            
                            //suma el valor de mora si existe
                            $valorFinalAcumulado = $totalAcumuladoCredito + $valor_mora_credito_ruta;                                                                                        
                            $totalAcumuladoCreditoFormat = number_format($valorFinalAcumulado, 0, ',', '.');
                            
                            //recaudo correspondiente a la fecha actual
                            $idRecaudoACtual = queryCuotaActual($consecutivo_credito_ruta);
                            
                            $cuotas_ruta_lyt .= "<div class='box box-default collapsed-box'>";

                            $cuotas_ruta_lyt .= "<div class='box-header with-border'>";                            
                            
                            //$cuotas_ruta_lyt .= "<p class='text-size-x3 text-red pull-right' style='margin-right:95px; margin-bottom:0;'><span class='margin-right-xs text-size-x5' >$</span>".$totalAcumuladoCreditoFormat."</p>";
                            
                            $cuotas_ruta_lyt .= "<h3 class='box-title text-red'><span class='margin-right-xs text-size-x5' >$</span>".$totalAcumuladoCreditoFormat."</h3>";
                            $cuotas_ruta_lyt .= "<h4 class=''>".$nombreCompletoDeudorRuta."</h4>";
                            $cuotas_ruta_lyt .= "<span class='text-muted' style='display:block;'>Credito:&nbsp;&nbsp;<b>".$consecutivo_credito_ruta."</b></span>";
                            $cuotas_ruta_lyt .= "<span class='text-muted' style='display:block;'>Tel:&nbsp;&nbsp;<b>".$tel1_deudor_ruta." / ".$tel2_deudor_ruta."</b></span>";
                            $cuotas_ruta_lyt .= "<span class='text-muted' style='display:block;'>Dir:&nbsp;&nbsp;<b>".$direccion_deudor_ruta."</b></span>";
                            
                            //define clase activa desactiva del boton cobrar
                            $lt_btn_cobro = "";
                            if($fechaActualHumano == $fecha_recaudado_ruta){
                                $lt_btn_cobro = "<button type='button' class='btn btn-box-tool margin-right-xs bg-green' disabled>Cobrar</button>";;
                            }else{
                                $lt_btn_cobro = "<button type='button' class='btn btn-box-tool margin-right-xs bg-green godetails' data-id='".$consecutivo_credito_ruta."' data-collect='".$idRecaudoACtual."' >Cobrar</button>";;
                            }
                            
                            //$cuotas_ruta_lyt .= "<p class='text-size-x3 text-red pull-right' style='margin-bottom:0;'><span class='margin-right-xs text-size-x5' >$</span>".$totalAcumuladoCreditoFormat."</p>";
                            $cuotas_ruta_lyt .= "<div class='box-tools pull-right'>";                            
                            //$cuotas_ruta_lyt .= "<button type='button' class='btn btn-box-tool margin-right-xs bg-green godetails' data-id='".$consecutivo_credito_ruta."' data-collect='".$idRecaudoACtual."' >Cobrar</button>";
                            $cuotas_ruta_lyt .= $lt_btn_cobro;
                            $cuotas_ruta_lyt .= "<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-plus fa-lg'></i></button>";
                            $cuotas_ruta_lyt .= "</div>";//box tool                        
                            $cuotas_ruta_lyt .= "</div>";//box header
                            
                            $cuotas_ruta_lyt .= "<div class='box-body padd-hori-md'>";//inicia contenedor cuotas
                            
                            $prevBox = $consecutivo_credito_ruta;
                            
                        }//[FIN PREVBOX]

                            
                        //[INICIO CONTENIDOS CUOTAS    ]
                        //$cuotas_ruta_lyt .= "CONTENIDOS DEL BODY RECAUDO # ".$idRecaudo_credito_ruta;
                        
                        /*
                        *STATUS CUOTA RUTA
                        */                        
                        $cuotas_ruta_lyt .= "<p class=''>";                          
                        $cuotas_ruta_lyt .= "<strong># Cuota</strong><span class='text-size-x5 text-red margin-left-xs'>".$numecuota_credito_ruta." </span>";
                        $cuotas_ruta_lyt .= "<span class='text-size-x4 pull-right text-red'>".$statusRecaudo."</span>";
                        //$cuotas_ruta_lyt .= $statusRecaudo;
                        $cuotas_ruta_lyt .= "</p>";
                        
                        /*
                        *REFERENCIA CREDITO
                        
                        $cuotas_ruta_lyt .= "<p class='no-padmarg'>";
                        $cuotas_ruta_lyt .= "<strong>Ref. Credito</strong>";
                        $cuotas_ruta_lyt .= "<span class='text-size-x4 pull-right text-red'>".$consecutivo_credito_ruta."</span>";
                        $cuotas_ruta_lyt .= "</p>";
                        */                       
                        /*
                        *VALOR A PAGAR
                        */
                        $cuotas_ruta_lyt .= "<p class='margin-top-xs'>";
                        $cuotas_ruta_lyt .= "<strong>Valor cuota</strong>";
                        $cuotas_ruta_lyt .= "<span class='text-size-x4 pull-right'><span class='margin-right-xs'>$</span>".$valorFinalCuotaFormat."</span>";
                        $cuotas_ruta_lyt .= "</p>";
                        
                        
                        /*
                        *VALOR FALTANTE
                        */
                        if($valor_faltante_ruta >0){
                        $cuotas_ruta_lyt .= "<p class='margin-top-xs'>";
                        $cuotas_ruta_lyt .= "<strong>Valor faltante</strong>";
                        $cuotas_ruta_lyt .= "<span class='text-size-x3 pull-right badge bg-red'><span class='margin-right-xs'>$</span>".$valor_faltante_rutaFormat."</span>";
                        $cuotas_ruta_lyt .= "</p>";
                        }
                        
                        /*
                        *VALOR MORA
                        */                                                                      
                        if($activa_mora_credito_ruta == 1){
                        
                        $valorMoraFormato = number_format($valor_mora_credito_ruta, 0, ',', '.');
                            
                        $cuotas_ruta_lyt .= "<p class='margin-top-xs'>";
                        $cuotas_ruta_lyt .= "<strong>Valor Mora</strong>";
                        $cuotas_ruta_lyt .= "<span class='text-size-x3 pull-right badge bg-red'><span class='margin-right-xs'>$</span>".$valorMoraFormato."</span>";
                        $cuotas_ruta_lyt .= "</p>";
                        }
                        
                        /*
                        *FECHA MAX RECAUDO
                        */
                        $cuotas_ruta_lyt .= "<p class='margin-top-xs'>";
                        $cuotas_ruta_lyt .= "<strong>Fecha Max. recaudo</strong>";
                        $cuotas_ruta_lyt .= "<span class='text-size-x3 pull-right'><span class='margin-right-xs'></span>".$fecha_maxrecaudo_ruta."</span>";
                        $cuotas_ruta_lyt .= "</p>";
                        
                         
                        /*
                        *FECHA RECAUDADO
                        */
                        if($fecha_recaudado_ruta != "0000-00-00"){
                        $cuotas_ruta_lyt .= "<p class='margin-top-xs'>";
                        $cuotas_ruta_lyt .= "<strong>Fecha recaudado</strong>";
                        $cuotas_ruta_lyt .= "<span class='text-size-x3 pull-right'><span class='margin-right-xs'></span>".$fecha_recaudado_ruta."</span>";
                        $cuotas_ruta_lyt .= "</p>";
                        }
                        
                        /*                                                 
                        $cuotas_ruta_lyt .= "<hr style='width:150px;'/>";    
                        
                        /
                        *DEUDOR
                        *
                        if(isset($tel1_deudor_ruta)){
                            $cuotas_ruta_lyt .= "<p class='margin-top-xs'>";
                            $cuotas_ruta_lyt .= "<strong>Teléfono </strong>";
                            $cuotas_ruta_lyt .= "<span class='text-size-x3 pull-right'>".$tel1_deudor_ruta."</span>";
                            $cuotas_ruta_lyt .= "</p>";
                        }
                        
                        if(isset($tel2_deudor_ruta)){
                            $cuotas_ruta_lyt .= "<p class='margin-top-xs'>";
                            $cuotas_ruta_lyt .= "<strong>Celular </strong>";
                            $cuotas_ruta_lyt .= "<span class='text-size-x3 pull-right'>".$tel2_deudor_ruta."</span>";
                            $cuotas_ruta_lyt .= "</p>";
                        }
                        /
                        *DIRECCION DEUDOR
                        *
                        $cuotas_ruta_lyt .= "<p class='margin-top-xs'>";
                        $cuotas_ruta_lyt .= "<strong>Dirección</strong>";
                        $cuotas_ruta_lyt .= "<span class='text-size-x3 pull-right'>".$direccion_deudor_ruta."</span>";
                        $cuotas_ruta_lyt .= "</p>";
                                                
                        /
                        *ACCIONES ITEM
                        *
                        $cuotas_ruta_lyt .= "<p class='margin-top-md text-center'>";                        
                        $cuotas_ruta_lyt .= "<button class='btn btn-flat btn-primary godetails padd-hori-lg' data-id='".$id_recaudo_ruta."' ".$pagar_btn.">Pagar</button>";
                        $cuotas_ruta_lyt .= "</p>";*/
                        
                        $cuotas_ruta_lyt .= "<hr/>";                    
                        
                             

                        //[FIN CONTENIDOS CUOTAS]
                    }
                    /*[FIN FOREACH|$datasCuotasRuta]*/
                                        
                    $cuotas_ruta_lyt .= "</div>";//[FIN | col-xs-12]
                    
                    echo $cuotas_ruta_lyt;
                    
                }else{ /*[SINO |$datasCuotasRuta]*/
                
                ?>
                
                <div class="col-xs-12">
                    <div class="alert">
                        <div class="media text-muted">
                            <div class=" media-left">
                                <i class="fa fa-th-list fa-4x"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="no-padmarg">No hay recaudos</h3>
                                <p style="font-size:1.232em; line-height:1;">
                                    No se encontraron recaudos asignados para este día
                                </p>              
                                
                            </div>

                        </div>                    
                    </div>
                    
                </div>
                
                
                <?php } /*[FIN |$datasCuotasRuta]*/ ?>
            </div>
        </section>
       
        
    </div>
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
    <?php //include '../../appweb/tmplt/right-side.php';  ?>
</div>
<?php echo _JSFILESLAYOUT_ ?>
<script src="../../appweb/plugins/misc/jquery.redirect.js"></script>      
<script>
$(document).ready(function(){
    var detailurl = $("#paththisfile").val();

    $('button.godetails').each(function(){ 
        
        var itemid = $(this).attr("data-id");
        var collectid = $(this).attr("data-collect");
        
        $(this).click(function(){                   
            //window.location = detailurl+"?itemid_var="+itemid;
            $.redirect(detailurl,{ itemid_var: itemid, collectid_var:collectid}); 
        });
    });
});    
</script>      
</body>
</html>
