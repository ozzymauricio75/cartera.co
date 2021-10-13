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

$rootLevel = "usuarios";
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
    <?php echo _JSFILESLAYOUT_ ?>
    <script src="../appweb/plugins/misc/jquery.redirect.js"></script>
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
                <span class="text-size-x6">Usuarios</span> / Lista
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
                    <!--<ul class="nav nav-tabs">
                        <li role="navigation" class="active">
                            <a href="#">
                                <span class="hidden-xs">Deudores</span>
                                <span class="visible-xs">Deu</span>
                            </a>
                        </li>
                        <li role="navigation">
                            <a href="#">
                                <span class="hidden-xs">Codeudores</span>
                                <span class="visible-xs">Cod</span>
                            </a>
                        </li>
                        <li role="navigation">
                            <a href="#">
                                <span class="hidden-xs">Ref. Personal</span>
                                <span class="visible-xs">Per</span>
                            </a>
                        </li>
                        <li role="navigation">
                            <a href="#">
                                <span class="hidden-xs">Ref. Familiar</span>
                                <span class="visible-xs">Fam</span>
                            </a>
                        </li>
                        <li role="navigation">
                            <a href="#">
                                <span class="hidden-xs">Ref. Comercial</span>
                                <span class="visible-xs">Com</span>
                            </a>
                        </li>                        
                    </ul>-->
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
                                       <!-- <th width="140px;">Opciones</th>-->
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
<!-- DataTables -->
<script src="../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
            
    $(function(){
        $('#printdatatbl').DataTable( {	
            "autoWidth": false,
            "order": [[ 1, "asc" ]],
            "processing": true,
            "bDeferRender": true,			
            "sPaginationType": "full_numbers",
            "ajax": {
                "url": "query-deudores.php?",
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
                }/*,
                {
                   "mData": "actions", "aTargets": [6],
                   "render": function (data) {
                       return data;
                   }
                },*/
            ],            
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
