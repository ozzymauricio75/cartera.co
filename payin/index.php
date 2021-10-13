<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars-payin.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-payin.php'; ?>
<?php require_once '../appweb/inc/valida-new-collect.php'; ?>
<?php require_once '../i18n-textsite.php'; ?>

<?php 
//recibe datos de PEDIDOS
$idOrder = "";
$dataOrders = array();
//$dataOrders = ordersQuery($idOrder);
//$dataOrders = unique_multidim_array($dataOrders, 'cod_orden_compra');


/*
*TOTAL DINERO A RECAUDAR HOY
*/
$dineroRutaHoy = array();
$dineroRutaHoy = queryValorRecaudosRutaHoy($dateFormatDB, $idSSUser);

$totalDineroRutaHoy = 0;
$totalDineroRutaHoyFormat = 0;
if(is_array($dineroRutaHoy) && !empty($dineroRutaHoy)){
    foreach($dineroRutaHoy as $drhKey){ 
        $valorCuotaHoy = $drhKey['total_cuota_plan_pago'];   
        $valorCuotaRecalculadoHoy = $drhKey['valor_cuota_recaulcaldo_recaudos'];
        $valorFaltanteHoy = $drhKey['valor_faltante_cuota'];
                
        //RECALCULO PROXIMA CUOTA 
        if($valorFaltanteHoy == 0){
            $totalDineroRutaHoy = $totalDineroRutaHoy + $valorCuotaHoy;
            $totalDineroRutaHoyFormat = number_format($totalDineroRutaHoy, 0, ',', '.');
        }else{
            $totalDineroRutaHoy = $totalDineroRutaHoy + $valorFaltanteHoy;    
            $totalDineroRutaHoyFormat = number_format($totalDineroRutaHoy, 0, ',', '.');
        }
    }
}

/*
*TOTAL RECAUDOS A RALIZARA HOY
*/
$totalRecaudos = count($dineroRutaHoy);


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
if($numeroRecaudosRealizados > 0){
    $progresoRecaudos = ceil(($numeroRecaudosRealizados/$totalRecaudos)*100);
}


/*
*RUTAS
*/

$datasRutas = array();
$datasRutas = queryRutas($idSSUser, /*"2017-05-12"*/ $dateFormatDB);
$rutas_lyt = "";

//echo "<pre>";
//print_r($datasRutas);

$totalDeudores = 0;

if(is_array($datasRutas) && !empty($datasRutas)){
    foreach($datasRutas as $drKey){
        $idRuta = $drKey['idRuta'];
        $consecutivoRuta = $drKey['consecutivoRuta'];
        $nombreCobradorRuta = $drKey['nombreCobradorRuta'];
        $fechaPublicacionRuta = date("d/m/Y", strtotime($drKey['fechaRegistroRuta']));
        $datasEspecificaRuta = $drKey['datasespecificacionesruta'];
        
        /*
        *ESPECIFICAIONES RUTA
        */        
        
        $totalDeudores = count($datasEspecificaRuta);
        
        if(is_array($datasEspecificaRuta) && !empty($datasEspecificaRuta)){
            foreach($datasEspecificaRuta as $derKey){
                $datasRecaudosRuta = $derKey['datasrecaudo'];
                
                if(is_array($datasRecaudosRuta) && !empty($datasRecaudosRuta)){
                    
                    foreach($datasRecaudosRuta as $drrKey){ 
                        $valorCuota = $datasRecaudosRuta['total_cuota_plan_pago'];
                        $valorRecalculadoCuota = $datasRecaudosRuta['valor_cuota_recaulcaldo_recaudos'];
                        $valorFaltanteCuota = $drhKey['valor_faltante_cuota'];

                    }
                }
 
            }
        }
        
        //RECALCULO PROXIMA CUOTA 
        $valorRecaudosRuta = 0;
        if($valorFaltanteCuota == 0){
            $valorRecaudosRuta += $valorCuota;
            $valorRecaudosRutaFormat = number_format($valorRecaudosRuta, 0, ',', '.');
        }else{
            $valorRecaudosRuta += $valorFaltanteCuota;    
            $valorRecaudosRutaFormat = number_format($valorRecaudosRuta, 0, ',', '.');
        }
        
        /*
        *LAYOUT
        */
        $rutas_lyt .= '<div class="col-xs-12 col-sm-6">';
                    
            //<!-- Box Comment -->
        $rutas_lyt .= '<div class="box box-widget">';
        $rutas_lyt .= '<div class="box-header with-border">';
        $rutas_lyt .= '<div class="user-block">';
        $rutas_lyt .= '<i class="fa fa-map fa-lg margin-top-xs"></i>';
        $rutas_lyt .= '<span class="username">'.$consecutivoRuta.'</span>';
        $rutas_lyt .= '<span class="description">'.$fechaPublicacionRuta.'</span>';
        $rutas_lyt .= '</div>';

        $rutas_lyt .= '</div>';
                //<!-- /.box-header -->

                //<!-- /.box-body -->
        $rutas_lyt .= '<div class="box-body box-comments">';
        $rutas_lyt .= '<div class="box-comment">';                       
        $rutas_lyt .= '<div class="comment-text margin-bottom-xs">';                     
                        //<!---
                        //    DETALLES RUTA
                        //-->
        $rutas_lyt .= '<dl class="dl-horizontal-custom">';
        $rutas_lyt .= '<dt>Cobrador:</dt>';
        $rutas_lyt .= '<dd>'.$nombreCobradorRuta.'</dd>';
        $rutas_lyt .= '<dt>Valor ruta:</dt>';
        $rutas_lyt .= '<dd>$ '.$valorRecaudosRutaFormat.'</dd>';
        $rutas_lyt .= '<dt>Deudores:</dt>';
        $rutas_lyt .= '<dd>'.$totalDeudores.'</dd>';
        $rutas_lyt .= '</dl>';

        $rutas_lyt .= '</div>';
        $rutas_lyt .= '<button class="btn btn-success btn-sm center-blockv godetails" data-id="'.$idRuta.'">Ver detalles</button>';
                    //<!-- /.comment-text -->
        $rutas_lyt .= '</div>';                        
        $rutas_lyt .= '</div>';

        $rutas_lyt .= '</div>';
            //<!-- /.box -->
        $rutas_lyt .= '</div>';//<!---/WRAPP STREAM--->*/
        
        
        
    }
}


//***********
//SITE MAP
//***********

$rootLevel = "payin";
$sectionLevel = "home";
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
    <link rel="stylesheet" href="../appweb/plugins/datatables/dataTables.bootstrap.css">
    <?php echo _FAVICON_TOUCH_ ?>    
</head>
    

<?php echo LAYOUTOPTION ?>
<?php include '../appweb/tmplt/loadevent.php';  ?>
    
<div class="wrapper">            
    <?php
    /*
    /
    ////HEADER
    /
    */
    ?>
    <?php //include '../appweb/tmplt/header.php';  ?>     
    
    <header class="headerpayin row">
        <div class="col-xs-2"></div>
        <div class="col-xs-8 text-center">
            <span class="" style="margin-top:8px;">
                <span class="margin-right-xs txtCapitalice text-shadow" style="font-size:22px; color:#fff;"><?php echo $nombre_dia; ?></span>
                <span class="margin-right-xs txtCapitalice text-shadow" style="font-size:15px; color:#fff;"><?php echo $dateFormatHuman; ?></span>
            </span>
        </div>
        <div class="col-xs-2">
            <a href="" class="btnout">
                <i class="fa fa-sign-out"></i>
            </a>            
        </div>
    </header>
    
    <div class="mmpayin">
        <div class="mmp-item active">
            <a href="">
                <i class="fa fa-home"></i>
                <p>Inicio</p>
            </a>
        </div>    
        <div class="mmp-item">
            <a href="">
                <i class="fa fa-map-marker"></i>
                <p>Mapa</p>
            </a>
        </div>    
        <div class="mmp-item">
            <a href="">
                <i class="fa fa-money"></i>
                <p>Recaudos</p>
            </a>
        </div>    
    </div>
    
    
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php //include '../appweb/tmplt/side-mm.php';  ?>
    <?php
    /*
    /
    ////WRAP CONTENT
    /
    */
    ?>   
    
    
    
    
    
    <div class="content-wrapper" style="padding-top:20px; margin-left: 230px;">
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
                <div class="col-xs-12 col-sm-6 margin-bottom-md">                                        
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
                                <?php echo $totalRecaudos; ?>
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
                <div class="col-xs-12 col-sm-6 margin-bottom-md">                                        
                    <div class="box box25 box-solid bg-green-gradient">
                        <div class="box-header with-border">
                            <i class="fa fa-money fa-2x margin-right-xs"></i>
                            <h3 class="box-title">Dinero recibido</h3>
                        </div>
                        <div class="box-body">
                            <p class="text-size-x6 text-center">
                            <span class="margin-right-xs text-size-x7">$</span><?php echo $totalDineroRecibidoHoyFormat; ?>
                            </p>
                        </div>
                    </div>
                </div>                            
            </div>
            
            
                    
        </section>
        
        
        <?php
        /*        
        *DETALES RUTAS HOY
        ******************************//*****************************
        */
        ?>
        
 
        <div class="content-header header-multi-seccion">
            <h2>Detalles recaudos para hoy</h2>
        </div>
        <section class="maxwidth-layout content">                 
            <div class="row">                
                <div class="col-xs-12 ">
                    
                    <div class="box">                    
                        <div class="box-body table-responsive">
                            <table id="printdatatbl" class="table table-striped " style="width:100%;">     
                                <thead>                                
                                    <tr>                 
                                        <th >Deudor</th>
                                        <th >Credito</th>
                                        <th >Valor a pagar</th>
                                        <th ># Cuota</th>                                        
                                        <th >Dirección</th>
                                        <th >Status</th>                                        
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>            
                        </div>                    
                    </div>                                               
                </div>
            </div>
        </section>
        

                
    </div>
    <?php echo "<input id='paththisfile' type='hidden' value='".$pathFile."/details-rutas/'/>"; ?>
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
    <?php //include '../appweb/tmplt/right-side.php';  ?>
</div>
<?php echo _JSFILESLAYOUT_ ?>
<script src="../appweb/plugins/misc/jquery.redirect.js"></script>        
<!-- DataTables -->
<script src="../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- jQuery Knob Chart -->
<script src="../appweb/plugins/knob/jquery.knob.js"></script>    
    
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
    
$(document).ready(function(){
    var detailurl = $("#paththisfile").val();

    $('button.godetails').each(function(){ 
        
        var itemid = $(this).attr("data-id");
        
        $(this).click(function(){                   
            //window.location = detailurl+"?itemid_var="+itemid;
            $.redirect(detailurl,{ itemid_var: itemid}); 
        });
    });
});    
</script>    
</body>
</html>
