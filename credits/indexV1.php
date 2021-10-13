<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-credits.php'; ?>
<?php require_once '../i18n-textsite.php'; ?>
<?php 
//recibe datos de PEDIDOS
$idAcreedor = $idSSUser;
$dataCredits = array();
$dataCredits = queryCreditos($idAcreedor);
//$dataCredits = unique_multidim_array($dataCredits, 'cod_orden_compra');
//echo "<pre>";
//print_r($dataCredits);


/*
*BALANCE GENERAL
*/
$dataBalanceGeneral = array();
$dataBalanceGeneral = queryBalanceGlobal($idAcreedor);

$valorCreditoArr = 0;
$dineroPrestadoArr =0;
$dineroRecaudadoArr =0;
$dineroCobrarArr =0;

$valorCredito = 0;
$totalDineroPrestado = 0;
$totalDineroRecaudado = 0;
$totalDineroCobrar = 0;

//echo "<pre>";
//print_r($dataBalanceGeneral);

if(is_array($dataBalanceGeneral) && !empty($dataBalanceGeneral)){
    foreach($dataBalanceGeneral as $bgKey){
        $valorCreditoArr +=  $bgKey['datavalorcredito'];
        $dineroPrestadoArr += $bgKey['datadineroencredito'];//number_format($bgKey['datadineroencredito'], 2, '.', ',');
        $dineroRecaudadoArr += $bgKey['datadinerorecaudado'];//number_format($bgKey['datadinerorecaudado'], 2, '.', ',');
        $dineroCobrarArr += $bgKey['dataporcobrar'];//number_format($bgKey['dataporcobrar'], 2, '.', ',');
        
        $valorCredito = number_format($valorCreditoArr, 0, ',', '.'); 
        $totalDineroPrestado = number_format($dineroPrestadoArr, 0, ',', '.');
        $totalDineroRecaudado = number_format($dineroRecaudadoArr, 0, ',', '.');
        $totalDineroCobrar = number_format($dineroCobrarArr, 0, ',', '.');
    }
}

//***********
//SITE MAP
//***********

$rootLevel = "creditos";
$sectionLevel = "lista";
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
    
<?php echo LAYOUTOPTION ?><!---//print body tag--->    
<?php include '../appweb/tmplt/loadevent.php';  ?>  
<div class="wrapper">            
    <?php
    /*
    /
    ////HEADER
    /
    */
    ?>
    <?php include '../appweb/tmplt/header.php';  ?>           
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php include '../appweb/tmplt/side-mm.php';  ?>
    <?php
    /*
    /
    ////WRAP CONTENT
    /
    */
    ?>        
    <div class="content-wrapper">
        <?php
        /*
        /*****************************//*****************************
        /HEADER CONTENT
        /*****************************//*****************************
        */
        ?>
        <section class="content-header bg-content-header">
            
            <!--<div class="nav navbar-nav navbar-right margin-right-xs">                
                <a href="new.php" class="btn btn-info" type="button" >
                    <i class="fa fa-plus margin-right-xs"></i>
                    Publicar
                </a>                
            </div>-->
            
            <h1>
                <span class="text-size-x6">Credito</span> / Lista
            </h1>                                    
        </section>
        
        <div class="content-header header-multi-seccion">
            <h2>Balance global</h2>
        </div>
        
        <section class="content margin-bottom-md">
            
            <div class="row text-center">
                <div class="clearfix"></div> 
                <div class="col-xs-12 col-sm-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$totalDineroPrestado; ?></strong>
                        </div>
                    </a>
                    <h3>Prestado</h3>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$valorCredito; ?></strong>
                        </div>
                    </a>
                    <h3>Creditos</h3>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$totalDineroRecaudado; ?></strong>
                        </div>
                    </a>
                    <h3>Recaudado</h3>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$totalDineroCobrar; ?></strong>
                        </div>
                    </a>
                    <h3>Por cobrar</h3>
                </div>
            </div>
        </section>

        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <div class="content-header header-multi-seccion">
            <h2>Lista de  creditos</h2>
        </div>

        <section class="content margin-bottom-md">
            
            <div class="box" style="margin-top:-2px;">
                               
                <div class="box-body ">
                    <table id="printdatatbl" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Credito</th>
                                <th>Deudor</th>                                
                                <th>Monto</th>
                                <th># Cuotas</th>
                                <th>Val. Cuota</th>
                                <th>Fecha inicio</th>
                                <th>Fecha fin</th>
                                <th>Status</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(is_array($dataCredits)){
                            foreach($dataCredits as $qKey){//$doKey
                                
                                /*                                
                                 [idcredito] => 1
            [consecutivo] => 
            [dataplanpago] => 
            [datadeudor] => 
            [datacobrador] => 
            [datastatus] => 
            [datatipocredito] =>                                                                 
                                */

                                $idItem = $qKey['idcredito'];
                                $consecutivoCredito = $qKey['consecutivo'];
                                $dataPlanPago = $qKey['dataplanpago'];
                                $dataDeudor = $qKey['datadeudor'];
                                $dataCobrador = $qKey['datacobrador'];
                                $dataStatus = $qKey['datastatus'];
                                $dataTipoCredito = $qKey['datatipocredito'];
                                
                                //SOBRE EL PLAN DE PAGOS
                                
                                $montoCredito = "";
                                $numeCuotasCredito = "";
                                $plazoCredito = "";
                                $fechaInicioCredito = "";
                                $fechaFinCredito = "";
                                $capitalCuotaCredito = "";
                                $valorTotalCuota="";
                                
                                $fechaInicioFormat = "";
                                $fechaFinFormat = "";
                                $montoCreditoFormat = "";
                                
                                if(is_array($dataPlanPago) && !empty($dataPlanPago)){
                                    foreach($dataPlanPago as $dppKey){                                        
                                        $periocidadCredito = $dataPlanPago['periocidad_plan_pago'];                                        
                                        $montoCredito = $dataPlanPago['valor_credito_plan_pago'];
                                        $numeCuotasCredito = $dataPlanPago['numero_cuotas_plan_pago'];
                                        $plazoCredito = $dataPlanPago['plazocredito_plan_pago'];
                                        $fechaInicioCredito = $dataPlanPago['fecha_inicio_plan_pago'];
                                        $fechaFinCredito = $dataPlanPago['fecha_fin_plan_pago'];
                                        $capitalCuotaCredito = $dataPlanPago['capital_cuota_plan_pago']; 
                                        $valorTotalCuota = $dataPlanPago['valor_cuota_plan_pago']; 
                                        
                                        //FORMAT
                                        $fechaInicioFormat = date("d/m/Y", strtotime($fechaInicioCredito));
                                        $fechaFinFormat = date("d/m/Y", strtotime($fechaFinCredito));
                                        $montoCreditoFormat = number_format($montoCredito,0,",","."); 
                                        $valorTotalCuotaFormat = number_format($valorTotalCuota,0,",","."); 
                                    }
                                    
                                }
                                
                                //SOBRE EL DEUDOR
                                $nombreDeudor = "";               
                                $apellidoDeudor = "";
                                $ciudadDeudor = "";
                                $regionDeudor = "";
                                $nombreFullDeudor = "";
                                if(is_array($dataDeudor) && !empty($dataDeudor)){
                                    foreach($dataDeudor as $ddKey){                                        
                                        $nombreDeudor = $dataDeudor['primer_nombre_deudor'];               
                                        $apellidoDeudor = $dataDeudor['primer_apellido_deudor'];
                                        $ciudadDeudor = $dataDeudor['ciudad_domicilio_deudor'];
                                        $regionDeudor = $dataDeudor['estado_domicilio_deudor'];                                        
                                    }
                                    
                                    $nombreFullDeudor = $nombreDeudor."&nbsp;".$apellidoDeudor;
                                }
                                
                                //SOBRE EL COBRADOR
                                $nombreCobrador = "";
                                if(is_array($dataCobrador) && !empty($dataCobrador)){
                                    foreach($dataCobrador as $dcKey){                                        
                                        $nombreCobrador = $dataCobrador['nombre_cobrador'];                                        
                                    }
                                }
                                
                                //STATUS CREDITO
                                $statusItem = "";
                                if(is_array($dataStatus) && !empty($dataStatus)){
                                    foreach($dataStatus as $dsKey){                                        
                                        $statusItem = $dataStatus['nombre_status'];                                        
                                    }
                                }
                                
                                //TIPO CREDITO
                                $tipoCredito = "";
                                if(is_array($dataTipoCredito) && !empty($dataTipoCredito)){
                                    foreach($dataTipoCredito as $dtcKey){  
                                        $tipoCredito = $dataTipoCredito['nombre_tipo_credito'];
                                    }
                                }
                                
                                
                                
                                
                                //if(is_array($doKey)){
                                    //foreach($doKey as $doVal){
                                //SOBRE EL PEDIDO

                                
                                //LAYOUT TABLE ITEM                                    
                                $layoutDataItem = "";
                                $layoutDataItem .= "<tr>";
                                
                                $layoutDataItem .= "<td><p>".$consecutivoCredito."</p></td>";
                                
                                $layoutDataItem .= "<td><p>".$nombreFullDeudor."</p></td>";
                                
                                $layoutDataItem .= "<td><p><span class='margin-right-xs'>$</span>".$montoCreditoFormat."</p></td>";
                                
                                $layoutDataItem .= "<td><p>".$numeCuotasCredito."</p></td>";
                                
                                $layoutDataItem .= "<td><p>$ ".$valorTotalCuotaFormat."</p></td>";
                                
                                $layoutDataItem .= "<td><p>".$fechaInicioFormat."</p></td>";
                                
                                $layoutDataItem .= "<td><p>".$fechaFinFormat."</p></td>";
                                
                                $layoutDataItem .= "<td><p>".$statusItem."</p></td>";
                                
                                $layoutDataItem .= "<td>";//opciones

                                $layoutDataItem .= "<div class='btn-group btn-sm'>";
                                //$layoutDataItem .= "<a href='details.php?coditemget=".$idItem."' type='button' class='btn btn-info'>Más detalles</a>";
                                $layoutDataItem .= "<button type='button' class='btn btn-info godetails' data-id='".$idItem."'>Más detalles</button>";
                                $layoutDataItem .= "<a href='#' type='button' class='btn btn-default'><i class='fa fa-trash'></i></a>";
                                //$layoutDataItem .= "<button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                                //$layoutDataItem .= "<span class='caret'></span>";
                                //$layoutDataItem .= "<span class='sr-only'>edititem</span>";
                                //$layoutDataItem .= "</button>";
                                //$layoutDataItem .= "<ul class='dropdown-menu pull-right'>";
                                //$layoutDataItem .= "<li><a href='#'><i class='fa fa-trash margin-right-xs'></i>Eliminar</a></li>";
                                //$layoutDataItem .= "</ul>";
                                $layoutDataItem .= "</div>";//btn-group

                                $layoutDataItem .= "</td>";//fin opciones

                                $layoutDataItem .= "</tr>";

                                echo $layoutDataItem;
                                    //}
                                //}

                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        </section>        
    </div>
    <?php echo "<input id='paththisfile' type='hidden' value='".$pathFile."/details/'/>"; ?>
    <?php
    /*
    /
    ////FOOTER
    /
    */
    ?>
    <?php include '../appweb/tmplt/footer.php';  ?>
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
<script>
  $(function () {
    
    $('#printdatatbl').DataTable({        
        "scrollX": false,
        "ordering": true,
        "autoWidth": false
    });
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
