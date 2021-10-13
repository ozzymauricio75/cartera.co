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
        //$totalDineroCobrar = number_format($dineroCobrarArr, 0, ',', '.');
        $valorACobrar = $valorCreditoArr - $dineroRecaudadoArr;
        $totalDineroCobrar = number_format($valorACobrar, 0, ',', '.');
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
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$totalDineroPrestado; ?></strong>
                        </div>
                    </a>
                    <h3>Prestado</h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$valorCredito; ?></strong>
                        </div>
                    </a>
                    <h3>Creditos</h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$totalDineroRecaudado; ?></strong>
                        </div>
                    </a>
                    <h3>Recaudado</h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
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
                    <div class="box-body table-responsive">
                        <table id="printdatatbl" class="table table-striped " style="width:100%;">     
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
                            <tbody></tbody>
                        </table>
                    </div>
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
$(function(){
    $('#printdatatbl').DataTable( {	
        "autoWidth": false,
        "order": [[ 0, "asc" ]],
        "processing": true,
        "bDeferRender": true,			
        "sPaginationType": "full_numbers",
        "ajax": {
            "url": "query-list-creditos.php?",
            "type": "POST"
        },
        "aoColumns": [
            {
               "mData": "refcredio", "aTargets": [0],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "deudor", "aTargets": [1],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "valorprestado", "aTargets": [2],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "numecuotas", "aTargets": [3],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "valorcuota", "aTargets": [4],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "fechainicio", "aTargets": [5],
               "render": function (data) {
                   return data;
               }
            },
             {
               "mData": "fechafin", "aTargets": [6],
               "render": function (data) {
                   return data;
               }
            },
             {
               "mData": "status", "aTargets": [7],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "actions", "aTargets": [8],
               "render": function (data) {
                   return data;
               }
            },
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
/*  $(function () {
    
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
    
});*/
</script>    
</body>
</html>
