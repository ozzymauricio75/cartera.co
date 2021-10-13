<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php //require_once '../../appweb/inc/query-credits.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>

<?php

//dinero prestado


//***********
//SITE MAP
//***********

$rootLevel = "reportes";
$sectionLevel = "";
$subSectionLevel = "";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo METATITLE ?> - Reportes - Acreedores_<?php echo $dateFormatPost; ?></title>    
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="author" content="massin">
    <?php echo _CSSFILESLAYOUT_ ?>    
    <link rel="stylesheet" href="../../appweb/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="../../appweb/plugins/datatables/extensions/buttons/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../../appweb/plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
            
    <?php echo _FAVICON_TOUCH_ ?>    
    <style type="text/css">
        
        input[type="search"]{
            
            border: 1px solid #8a8a8a;
            background-color: #d8d8d8;
            font-size: 13px;
            font-weight: bold;
        }
        
    
    </style>
    <?php echo _JSFILESLAYOUT_ ?>
    <script src="../../appweb/plugins/misc/jquery.redirect.js"></script>      
</head>
    
<?php echo LAYOUTOPTION ?><!---//print body tag--->    
<?php include '../../appweb/tmplt/loadevent.php';  ?> 
    
<div class="wrapper">            
    <?php
    /*
    /
    ////HEADER
    /
    */
    ?>
    <?php include '../../appweb/tmplt/header.php';  ?>           
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php include '../../appweb/tmplt/side-mm.php';  ?>
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
                
                <span class="text-size-x6">Reportes</span> / Acreedores
            </h1>
            <a href="<?php echo $pathmm."/report/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de reportes
            </a> 
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
                    <div class="box">                    
                        <div class="box-body table-responsive">
                            
                            <table id="printdatatbl" class="table table-striped " style="width:100%;">     
                                <thead>                                
                                    <tr>                                      
                                        <th >Usuario</th>
                                        <th >Nombre</th>
                                        <th >Telefono</th>
                                        <th >Creditos activos</th>
                                        <th >Fecha registro</th>
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
    <?php
    /*
    /
    ////FOOTER
    /
    */
    ?>
    <?php include '../../appweb/tmplt/footer.php';  ?>
    <?php
    /*
    /
    ////RIGHT BAR
    /
    */
    ?>
    <?php //include '../../appweb/tmplt/right-side.php';  ?>    
    
</div>
      
<!-- DataTables     -->
<script src="../../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>    
       
<script>
        
    /*
    *DATOS CONSULTA
    */            
    /*
     'https://datatables.net/manual/tech-notes/3
     'como la pagina no se refresca, debemos destruir la tabla previamente inicializada para cargar los datos nuevos con la consulta mas reciente
    */
    $('#printdatatbl').DataTable({	
        "destroy": true,//para resetear la tabla previamente creada
        "autoWidth": false,
        "order": [[ 0, "asc" ]],
        "processing": true,
        "bDeferRender": true,			
        "sPaginationType": "full_numbers",
        "ajax": {
            "url": "query-acreedores.php",
            "type": "POST"/*,
            "data": {
                "qstart" : datestart,
                "qend" : dateend,
            }*/
        },
        "aoColumns": [
            {
               "mData": "nickname", "aTargets": [0],
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
               "mData": "tel", "aTargets": [2],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "numerocreditos", "aTargets": [3],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "fecharegistro", "aTargets": [4],
               "render": function (data) {
                   return data;
               }
            },
            {
               "mData": "status", "aTargets": [5],
               "render": function (data) {
                   return data;
               }
            }
        ],                
        "dom": "Bftip",                    
        "buttons": [
            {
                extend: 'excelHtml5',
                text: 'Exportar a Excel'
            }
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
            
    
</script>       
</body>
</html>
