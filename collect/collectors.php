<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php //require_once '../appweb/inc/query-credits.php'; ?>
<?php require_once '../i18n-textsite.php'; ?>

<?php 

//***********
//SITE MAP
//***********

$rootLevel = "cobrar";
$sectionLevel = "cobradores";
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
    <style type="text/css">
        td.details-control {
            background: url('details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('details_close.png') no-repeat center center;
        }
        input[type="search"]{
            
            border: 1px solid #8a8a8a;
            background-color: #d8d8d8;
            font-size: 13px;
            font-weight: bold;
        }
        
    
    </style>
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
                <span class="text-size-x6">Cobradores</span> / Lista
            </h1>                  
        </section>
        


        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
 
        <section class="content">                 
            <div class="row">                
                <div class="col-xs-12 ">  
                    <div class="clearfix text-right">
                        <a href="new/" type="button" class="btn btn-app btn-xs bg-olive">
                            <i class="fa fa-plus fa-lg"></i>
                            Agregar Cobrador
                        </a>
                    </div>
                    <div class="box"> 
                        
                        <div class="box-body table-responsive">
                            <table id="printdatatbl" class="table table-striped " style="width:100%;">     
                                <thead>                                
                                    <tr>                                      
                                        <th >Cedula</th>
                                        <th >Nombre</th>
                                        <th >Tel. Fijo</th>
                                        <th >Tel. Celular</th>
                                        <th >Dirección</th>
                                        <th >Registró</th>
                                        <th >Status</th>
                                        <th >Opciones</th>
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
    <?php echo "<input id='paththisfile' type='hidden' value='".$pathFile."/details/'/>"; ?>
    <?php
    /*
    /
    ////FOOTER
    /
    */
    ?>
    <?php  include '../appweb/tmplt/footer.php';  ?>
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
            "order": [[ 1, "asc" ]],
            "processing": true,
            "bDeferRender": true,			
            "sPaginationType": "full_numbers",
            "ajax": {
                "url": "query-cobradores.php",
                "type": "POST"
            },
            "aoColumns": [
                {
                   "mData": "documento", "aTargets": [0],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "nombre", "aTargets": [1],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "tel1", "aTargets": [2],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "tel2", "aTargets": [3],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "direccion", "aTargets": [4],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "fecharegistro", "aTargets": [5],
                   "render": function (data) {
                       return data;
                   }
                }, 
                {
                   "mData": "status", "aTargets": [6],
                   "render": function (data) {
                       return data;
                   }
                },
                {
                   "mData": "actions", "aTargets": [7],
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
    
    
</script>       
</body>
</html>
