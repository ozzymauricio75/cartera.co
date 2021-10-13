<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars-payin.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-payin.php'; ?>
<?php require_once '../../appweb/inc/valida-new-collect.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>

<?php 
//recibe datos de PEDIDOS
$idOrder = "";
$dataOrders = array();
//$dataOrders = ordersQuery($idOrder);
//$dataOrders = unique_multidim_array($dataOrders, 'cod_orden_compra');


/*
*TOTAL DINERO A RECAUDAR HOY
*/

//RECAUDO RUTA
function queryRecaudosRutaHoy($idRecaudo){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_recaudo', $idRecaudo);    
    $queryTbl = $db->get ("recaudos_tbl", 1, "id_recaudo, id_status_recaudo, numero_cuota_recaudos, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_recaudo_realizado");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
    
}


//ESPECIFICACIONES RUTAS
function queryEspecificaRutasHoy($idRuta_){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
                    
    $db->where('id_ruta', $idRuta_);    
    $queryTbl = $db->get ("especifica_ruta_tbl");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
                        
            $idEspecificaRuta = $qKey['id_especifica_ruta'];
            $idRuta = $qKey['id_ruta'];
            $idCreditoRuta = $qKey['id_creditos'];
            $idDeudor = $qKey['id_deudor'];
            $idPlanPago = $qKey['id_plan_pago'];
            $idRecaudo = $qKey['id_recaudo'];
            $idCobrador = $qKey['id_cobrador'];
            
                        
            $dataSumatoriaRuta = queryRecaudosRutaHoy($idRecaudo);
            
            $dataQuery[] = array(
                'idespecificaruta' => $idEspecificaRuta,
                'idruta' => $idRuta,
                'idcreditos' => $idCreditoRuta,
                'iddeudor' => $idDeudor,
                'idplan_pago' => $idPlanPago,
                'idrecaudo' => $idRecaudo,
                'idcobrador' => $idCobrador,
                'datasrecaudo' => $dataSumatoriaRuta
            );
            
        }    
        return $dataQuery;
    }
}

//SOBRE LA RUTA
function queryRutasDetalleHoy($dateFormatDB, $idSSUser){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
    $datasCobrador = array();
                    
    //$db->where('id_ruta', $idRuta_);    
    $db->where('id_cobrador', $idSSUser);    
    $db->where('fecha_creacion_ruta', $dateFormatDB);    
    $queryTbl = $db->get ("rutas_tbl");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
            
            $idRuta = $qKey['id_ruta'];
            $idAcedorRuta = $qKey['id_acreedor'];
            $idCobradorRuta = $qKey['id_cobrador'];
            $statusRuta = $qKey['id_status_ruta'];
            $consecutivoRuta = $qKey['consecutivo_ruta'];
            $nombreCobradorRuta = $qKey['nombre_cobrador_ruta'];
            $fechaRegistroRuta = $qKey['fecha_creacion_ruta'];
                        
            $dataEspecificaRuta = queryEspecificaRutasHoy($idRuta);
            $totalRecaudosHoy = count($dataEspecificaRuta);
            //info cobrador
            //$datasCobrador = queryCobrador_FULL($idCobradorRuta);
                
            $dataQuery[] = array(
                'idRuta' => $idRuta,
                'idAcedorRuta' => $idAcedorRuta,
                'idCobradorRuta' => $idCobradorRuta,
                'statusRuta' => $statusRuta,
                'consecutivoRuta' => $consecutivoRuta,
                'nombreCobradorRuta' => $nombreCobradorRuta,
                'fechaRegistroRuta' => $fechaRegistroRuta,
                'totalrecaudoshoy' => $totalRecaudosHoy,
                'datasespecificacionesruta' => $dataEspecificaRuta,                
            ); 
        }    
        return $dataQuery;
    }
}

$dineroRutaHoy = array();
$dineroRutaHoy = queryRutasDetalleHoy($dateFormatDB, $idSSUser);
//echo "<pre>";
//print_r($dineroRutaHoy);
$totalRecaudosParaHoy = 0;
$totalDineroRutaHoy = 0;
$totalDineroRutaHoyFormat = 0;

$numeRecaudosRecibidosHoy = 0;
$recaudosRecibidosHoyFormat = 0;
$recaudosRecibidosHoy = 0;

if(is_array($dineroRutaHoy) && !empty($dineroRutaHoy)){
    foreach($dineroRutaHoy as $drhKey){ 
        $totalRecaudosParaHoy = $drhKey['totalrecaudoshoy'];   
        $datasEspecificaRutaHoy = $drhKey['datasespecificacionesruta'];
        
        //especificaciones ruta dia actual
        if(is_array($datasEspecificaRutaHoy) && !empty($datasEspecificaRutaHoy)){
            foreach($datasEspecificaRutaHoy as $derKey){
                $datasRecaudosHoy = $derKey['datasrecaudo'];
                
                if(is_array($datasRecaudosHoy) && !empty($datasRecaudosHoy)){
                    foreach($datasRecaudosHoy as $drhKey){

                        $statusRecaudoHoy = $datasRecaudosHoy['id_status_recaudo'];
                        $valorCuotaRecaudoHoy = $datasRecaudosHoy['total_cuota_plan_pago'];
                        $valorRecaudadoRecaudoHoy = $datasRecaudosHoy['total_valor_recaudado_estacuota'];
                        $valorFaltanteRecaudoHoy = $datasRecaudosHoy['valor_faltante_cuota'];
                        $valorRecalculadoRecaudoHoy = $datasRecaudosHoy['valor_cuota_recaulcaldo_recaudos'];
                        $fechaRecaudoHoy = $datasRecaudosHoy['fecha_recaudo_realizado'];
                                                         
                    }
                }
                
                //RECALCULO PROXIMA CUOTA 
                if($valorFaltanteRecaudoHoy == 0){
                    $totalDineroRutaHoy = $totalDineroRutaHoy + $valorCuotaRecaudoHoy;
                    $totalDineroRutaHoyFormat = number_format($totalDineroRutaHoy, 0, ',', '.');
                }else{
                    $totalDineroRutaHoy = $totalDineroRutaHoy + $valorFaltanteRecaudoHoy;    
                    $totalDineroRutaHoyFormat = number_format($totalDineroRutaHoy, 0, ',', '.');
                }
                
                //DINERO RECIBIDO HOY
                if($fechaRecaudoHoy == $dateFormatDB && $statusRecaudoHoy != "3"){

                    $recaudosRecibidosHoy = $recaudosRecibidosHoy + $valorRecaudadoRecaudoHoy;
                    $recaudosRecibidosHoyFormat = number_format($recaudosRecibidosHoy, 0, ',', '.');

                    $numeRecaudosRecibidosHoy++;
                }
                
            }
        }
                        
    }
}


/*
*TOTAL DINERO RECIBIDO
*/
$numeroRecaudosRealizados = 0;
$dineroRecibidoHoy = array();
$dineroRecibidoHoy = queryValorRecibidoRutaHoy($dateFormatDB, $idSSUser);
$numeroRecaudosRealizados = count($dineroRecibidoHoy);

$totalDineroRecibidoHoy = 0;
$totalDineroRecibidoHoyFormat = 0;
if(is_array($dineroRecibidoHoy) && !empty($dineroRecibidoHoy)){
    foreach($dineroRecibidoHoy as $dpayhKey){ 
        $valorRecaudadoHoy = $dpayhKey['total_valor_recaudado_estacuota'];   
        
        $totalDineroRecibidoHoy = $totalDineroRecibidoHoy  + $valorRecaudadoHoy;
        $totalDineroRecibidoHoyFormat = number_format($totalDineroRecibidoHoy, 0, ',', '.');
                
    }
}

/*
*CALCULO PROGRESO RECAUDOS
*/
$progresoRecaudos = 0;

if($numeRecaudosRecibidosHoy > 0){
    //$progresoRecaudos = ceil(($numeRecaudosRecibidosHoy/$totalRecaudosParaHoy)*100);
    $progresoRecaudos = round(($numeRecaudosRecibidosHoy/$totalRecaudosParaHoy)*100);
}


//***********
//SITE MAP
//***********

$rootLevel = "home";
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
</head>
    

<?php echo LAYOUTOPTION ?>
<?php include '../../appweb/tmplt/loadevent.php';  ?>
    
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
                <span class="text-size-x6">Cobranza</span> / Lista rutas
            </h1>                                    
        </section>-->
        
                
        <section class="content margin-bottom-md">
            <div class="row ">
                <div class="col-xs-12 margin-bottom-md">                                        
                    <div class="box box25 box-solid bg-red-gradient">
                        <div class="box-header with-border">
                            <i class="fa fa-money fa-2x margin-right-xs"></i>
                            <h3 class="box-title">Dinero a recaudar</h3>
                        </div>
                        <div class="box-body">
                            <p class="text-size-x6 text-center">
                            <span class="margin-right-xs text-size-x7">$</span><?php echo $totalDineroRutaHoyFormat; ?>
                            </p>
                        </div>
                    </div>
                </div>                            
            </div>
            
            <div class="row ">
                <div class="col-xs-12 margin-bottom-md">                                        
                    <div class="box box25 box-solid bg-light-blue-gradient">
                        <div class="box-header with-border">
                            <i class="fa fa-money fa-2x margin-right-xs"></i>
                            <h3 class="box-title">Total recaudos</h3>
                        </div>
                        <div class="box-body">
                            <p class="text-size-x6 text-center">
                                <?php echo $totalRecaudosParaHoy; ?>
                            </p>
                        </div>
                        <div class="box-footer no-border">
                            <div class="row">
                                <div class="col-xs-12 text-center" style="border-right: 1px solid #f4f4f4">
                                    <input type="text" class="knob" value="<?php echo $progresoRecaudos; ?>" data-width="90" data-height="90" data-fgColor="#00c0ef" data-readonly="true">
                                    <div class="knob-label">Progreso recaudos</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                            
            </div>
            
            <div class="row ">
                <div class="col-xs-12 margin-bottom-md">                                        
                    <div class="box box25 box-solid bg-green-gradient">
                        <div class="box-header with-border">
                            <i class="fa fa-money fa-2x margin-right-xs"></i>
                            <h3 class="box-title">Dinero recibido</h3>
                        </div>
                        <div class="box-body">
                            <p class="text-size-x6 text-center">
                            <span class="margin-right-xs text-size-x7">$</span><?php echo $recaudosRecibidosHoyFormat; ?>
                            </p>
                        </div>
                    </div>
                </div>                            
            </div>
            
            
                    
        </section>
        
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
<!-- jQuery Knob Chart -->
<script src="../../appweb/plugins/knob/jquery.knob.js"></script>    
    
<script>
  $(function () {
    
 
    /* jQueryKnob */

    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value);
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
    });
    /* END JQUERY KNOB */      
      
      
  });
      
</script>    
</body>
</html>
