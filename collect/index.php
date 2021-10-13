<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-collects.php'; ?>
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
$dineroRutaHoy = queryValorRecaudosRutaHoy($idSSUser, $dateFormatDB);

$totalDineroRutaHoy = 0;
$totalDineroRutaHoyFormat = 0;
$cuotaCobraMora = 0;
if(is_array($dineroRutaHoy) && !empty($dineroRutaHoy)){
    foreach($dineroRutaHoy as $drhKey){
        $valorCuotaHoy = $drhKey['total_cuota_plan_pago'];
        $valorCuotaRecalculadoHoy = $drhKey['valor_cuota_recaulcaldo_recaudos'];
        $valorCuotaRecaudadoHoy = $drhKey['total_valor_recaudado_estacuota'];
        $valorFaltanteCuotaHoy = $drhKey['valor_faltante_cuota'];
        $activaMoraCuotaHoy = $drhKey['activa_mora'];
        $valorMoraCuotaHoy = $drhKey['valor_mora_cuota_recaudo'];
        $refcredito = $drhKey['ref_recaudo'];

        $status_credito = queryStatusCredito($refcredito);

        if($status_credito == 1 || $status_credito == 3){
            $cuotaCobraMora = ($activaMoraCuotaHoy == 1)? $valorMoraCuotaHoy : 0;

            //RECALCULO PROXIMA CUOTA
            $totalDineroRutaHoy = $totalDineroRutaHoy + $valorCuotaHoy + $cuotaCobraMora - $valorCuotaRecaudadoHoy;
            $totalDineroRutaHoyFormat = number_format($totalDineroRutaHoy, 0, ',', '.');
        }
    }
}


/*
*COBRADORES SELECT
*/

$existeCobrador = "false";
$cobradores_select_lyt = "<ul class='nav nav-pills nav-stacked'>";

$db->where("id_acreedor", $idSSUser);//$idSSUser
$db->where("id_status_cobrador", "1");
$db->orderBy("nombre_cobrador", "asc");
$queryCobradores = $db->get("cobrador_tbl", null, "id_cobrador, nombre_cobrador");

if(is_array($queryCobradores) && !empty($queryCobradores)){
    $existeCobrador = "true";
    foreach($queryCobradores as $qcKey){

        $id_cobrador = $qcKey['id_cobrador'];
        $name_cobrador = $qcKey['nombre_cobrador'];

        //STATUS RUTA ASIGNADA
        $rutaasignada = array();
        $db->where("id_cobrador", $id_cobrador);
        $db->where("fecha_creacion_ruta", $dateFormatDB);
        $rutaasignada = $db->getOne("rutas_tbl");

        $linkCheck = "goruta";
        $asignadaCheck = "<span class='pull-right text-blue'>Asignar Ruta<i class='fa fa-chevron-right fa-1x margin-left-xs'></i></span>";
        if(!empty($rutaasignada)){
            $linkCheck = "";
            $asignadaCheck = "<span class='label bg-success text-green pull-right margin-top-xs text-size-x2'>Ruta asignada</span>";
        }

        $cobradores_select_lyt .= "<li>";
        $cobradores_select_lyt .= "<a data-href='".$pathFile."/' style='height:60px; line-height:40px; cursor: pointer;' class='".$linkCheck."' data-field='newcollect' data-item='".$id_cobrador."' data-uservar='".$idSSUser."' data-usernick='".$pseudoSSUser."' data-fecharuta='".$dateFormatDB."'>";
        $cobradores_select_lyt .= $name_cobrador;
        //$cobradores_select_lyt .= "<span class='pull-right text-blue'>Asignar Ruta<i class='fa fa-chevron-right fa-1x margin-left-xs'></i></span>";
        $cobradores_select_lyt .= $asignadaCheck;
        $cobradores_select_lyt .= "</a>";
        $cobradores_select_lyt .= "</li>";
    }
}

$cobradores_select_lyt .= "</ul>";


/*
*RUTAS
*/
$idCobrador = $id_cobrador;
$datasRutas = array();
$datasRutas = queryRutas($idSSUser, /*"2017-05-12"*/ $dateFormatDB, $idCobrador);
$rutas_lyt = "";

//echo "<pre>";
//print_r($datasRutas);

$prevDeduores = "";


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
        $totalDeudores = 0;
        //$totalDeudores = count($datasEspecificaRuta);
        $valorRecaudosRuta = 0;
        if(is_array($datasEspecificaRuta) && !empty($datasEspecificaRuta)){
            foreach($datasEspecificaRuta as $derKey){
                $datasRecaudosRuta = $derKey['datasrecaudo'];
                $deudorRuta = $derKey['iddeudor'];

                //echo $deudorRuta;
                /*
                *determinar cantiad de deudores por ruta
                */
                if($prevDeduores != $deudorRuta){
                    $totalDeudores++;
                    $prevDeduores = $deudorRuta;
                }

                if(is_array($datasRecaudosRuta) && !empty($datasRecaudosRuta)){

                    foreach($datasRecaudosRuta as $drrKey){
                        $valorCuota = $datasRecaudosRuta['total_cuota_plan_pago'];
                        $valorRecaudadoCuota = $datasRecaudosRuta['total_valor_recaudado_estacuota'];
                        $activamoraCuota = $datasRecaudosRuta['activa_mora'];
                        $valormoraCuota = $datasRecaudosRuta['valor_mora_cuota_recaudo'];

                    }
                }

                $cuotaconmora = ($activamoraCuota == 1) ? $valormoraCuota : 0;

                //RECALCULO PROXIMA CUOTA


                $valorRecaudosRuta = $valorRecaudosRuta + ($valorCuota + $cuotaconmora) - $valorRecaudadoCuota;
                $valorRecaudosRutaFormat = number_format($valorRecaudosRuta, 0, ',', '.');



            }
        }



        /*
        *LAYOUT
        */
        $rutas_lyt .= '<div class="col-xs-12 col-sm-12">';

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
        $rutas_lyt .= '<button class="btn btn-success btn-sm center-block godetails" data-id="'.$idRuta.'">Ver detalles </button>';
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

$rootLevel = "cobrar";
$sectionLevel = "rutas";
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
            <h1>
                <span class="text-size-x6">Cobranza</span> / Lista rutas
            </h1>
        </section>

        <?php
        /*
        /*****************************//*****************************
        /ERRORES REGISTRO RUTAS
        /*****************************//*****************************
        */
        ?>
        <?php if(is_array($ruta_err) && !empty($ruta_err)){ ?>
        <div class="maxwidth-layout">
            <div class="alert alert-default alert-dismissible box50">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <ul class="list-group text-left">
                    <?php
                        foreach($ruta_err as $reKey){
                            echo '<li class="list-group-item list-group-item-danger">'.$reKey.'</li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
        <?php } ?>

        <section class="content margin-bottom-md">
            <div class="row ">
                <div class="col-xs-12 col-sm-6 margin-bottom-xs">
                    <div class="box box-solid bg-red-gradient">
                        <div class="box-header with-border">
                            <i class="fa fa-money fa-2x margin-right-xs"></i>
                            <h3 class="box-title">Por cobrar a la fecha actual</h3>
                        </div>
                        <div class="box-body">
                            <p class="text-size-x6 text-center">
                            <span class="margin-right-xs text-size-x7">$</span><?php echo $totalDineroRutaHoyFormat; ?>
                            </p>
                        </div>
                    </div>


                    <div class="box box-default margin-top-md">
                        <div class="box-header with-border">
                            <i class="fa fa-motorcycle fa-2x margin-right-xs"></i>
                            <h3 class="box-title">Cobradores</h3>
                        </div>
                        <div class="box-body no-padmarg">
                            <?php if($existeCobrador == "true"){ ?>

                            <div class=" padd-verti-xs">
                                <?php echo $cobradores_select_lyt; ?>
                            </div>

                            <?php }else{ ?>


                            <div class="box50">
                                <div class="callout  margin-verti-md">
                                    <div class="media">
                                        <div class=" media-left padd-hori-xs">
                                            <i class="fa fa-motorcycle fa-3x shades-text text-white"></i>
                                        </div>
                                        <div class="media-body">
                                            <h3 class="no-padmarg">No has creado cobradores</h3>
                                            <p style="font-size:1.232em; line-height:1;">
                                                Registra almenos un cobrador en tu sistema
                                            </p>



                                        </div>
                                    </div>
                                </div>
                                <a href="new/" class="btn btn-success btn-sm pull-right">Nuevo cobrador <i class="fa fa-plus fa-lg margin-left-xs"></i></a>
                            </div>

                            <?php } ?>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12 col-sm-6  margin-bottom-xs">
                    <div class="section-title">
                        <h3>Rutas de cobro para hoy</h3>
                    </div>
                    <?php if($rutas_lyt != ""){ ?>
                    <div class="row">
                    <?php echo $rutas_lyt; ?>
                    </div><!--/WRAPP MAIN--->
                    <?php }else{ ?>
                    <div class="box50">
                        <div class="callout  margin-verti-md">
                            <div class="media">
                                <div class=" media-left padd-hori-xs">
                                    <i class="fa fa-list-alt fa-4x text-muted"></i>
                                </div>
                                <div class="media-body">
                                    <h3 class="no-padmarg">Hola!</h3>
                                    <p style="font-size:1.232em; line-height:1;">
                                        No se han creado rutas de cobro para hoy
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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
            <h2>
                Cuotas por cobrar

            </h2>
            <p class="help-block no-padmarg" style="display:block;">Muestra a la fecha actual, las cuotas que aun no se han pagado o que estan en mora</p>
        </div>
        <section class="maxwidth-layout content">
            <div class="row">
                <div class="col-xs-12 ">

                    <div class="box">
                        <div class="box-body table-responsive">
                            <table id="printdatatbl" class="table table-striped " style="width:100%;">
                                <thead>
                                    <tr>
                                        <th >Fecha Recaudo</th>
                                        <th >Credito</th>
                                        <th >Deudor</th>
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
    $(document).ready(function(){
        var detailurl = $("#paththisfile").val();

        $(".userdetails").each(function(){


            var itemid = $(this).attr("data-item");
            var itemtype = $(this).attr("data-type");

            $(this).click(function(){
                alert("me dieron click "+itemid);
                //$.redirect(detailurl,{ itemid_var: itemid, itemtype_var: itemtype});
            });
        });

    });

    $(function(){
        $('#printdatatbl').DataTable( {
            "autoWidth": false,
            "order": [[ 0, "asc" ]],
            "processing": true,
            "bDeferRender": true,
            "sPaginationType": "full_numbers",
            "ajax": {
                "url": "query-detalles-rutas.php",
                "type": "POST"
            },
            "aoColumns": [

                {
                   "mData": "fechamaxrecaudo", "aTargets": [0],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "concecutivo", "aTargets": [1],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "deudor", "aTargets": [2],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "valorpagar", "aTargets": [3],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "numerocuota", "aTargets": [4],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "direccion", "aTargets": [5],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "status", "aTargets": [6],
                   "render": function (data) {
                       return data;
                   }
                }/*,
                {
                   "mData": "actions", "aTargets": [6],
                   "render": function (data) {
                       return data;
                   }
                },*/
            ],
            /*"columns": [
                { "data": "documento"},
                { "data": "nombre"},
                { "data": "tel1"},
                { "data": "tel2"},
                { "width": "20%", "data": "direccion"},
                { "data": "fecharegistro"},
                { "data": "actions"},
            ],*/
            "oLanguage": {
                "sProcessing":     "Procesando...",
                "sLengthMenu": 'Mostrar&nbsp;&nbsp;<select>'+
                    '<option value="10">10</option>'+
                    '<option value="20">20</option>'+
                    '<option value="30">30</option>'+
                    '<option value="40">40</option>'+
                    '<option value="50">50</option>'+
                    '<option value="-1">All</option>'+
                    '</select>&nbsp;&nbsp;registros',
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:&nbsp;&nbsp;",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Por favor espere - cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    });


</script>

<script>
  /*$(function () {

    $('#printdatatbl').DataTable({
        "scrollX": false,
        "ordering": true,
        "autoWidth": false
    });
  });*/

$(document).ready(function(){
    var detailurl = $("#paththisfile").val();

    $('button.godetails').each(function(){

        var itemid = $(this).attr("data-id");

        $(this).click(function(){
            //window.location = detailurl+"?itemid_var="+itemid;
            $.redirect(detailurl,{ itemid_var: itemid});
        });
    });

    $('a.goruta').each(function(){

        var gohref = $(this).attr("data-href");
        var field = $(this).attr("data-field");
        var itemid = $(this).attr("data-item");
        var uservar = $(this).attr("data-uservar");
        var usernick = $(this).attr("data-usernick");
        var fecharuta = $(this).attr("data-fecharuta");

        $(this).click(function(){
            $.redirect(gohref,{ field_var: field, itemid_var: itemid, uservar_var:uservar, usernick_var: usernick, fecharuta_var: fecharuta});
        });
    });

});
</script>
</body>
</html>
