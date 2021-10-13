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
    <title><?php echo METATITLE ?> - Reportes - Cuadre_caja_<?php echo $dateFormatPost; ?></title>    
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="author" content="massin">
    <?php echo _CSSFILESLAYOUT_ ?>    
    <link rel="stylesheet" href="../../appweb/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="../../appweb/plugins/datatables/extensions/buttons/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../../appweb/plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
        
    <link rel="stylesheet" href="../../appweb/plugins/daterangepicker/daterangepicker.css">
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
                
                <span class="text-size-x6">Reportes</span> / Cuadre caja
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
        
        <div class="box bg-gray margin-bottom-xs maxwidth-layout">
            <div class="box-header with-border">
                <i class="fa fa-sliders"></i>
                <h3 class="box-title">Opciones de consulta</h3>
            </div>
            <div class="box-body">                
                <div class="form-inline">
                    <div class="form-group">
                        <label class="sr-only">Seleccionar un rango de fechas</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-default form-control" id="daterange-btn">
                                <span>
                                    <i class="fa fa-calendar margin-right-xs"></i> 
                                    Rango de fechas
                                </span>
                                <i class="fa fa-caret-down margin-left-xs"></i>
                            </button>
                        </div>
                        <input type="hidden" id="fechainicio">
                        <input type="hidden" id="fechafin">
                    </div>
   
                    <div class="form-group">
                        <button class="btn btn-primary" id="qreport">
                            <i class="fa fa-search margin-right-xs"></i>
                            Consultar
                        </button>
                    </div>
                </div>                
            </div>            
        </div>
        <section class="content">                   
            <div class="row">                
                <div class="col-xs-12 ">
                    <div class="box">                    
                        <div class="box-body table-responsive">
                            
                            <table id="printdatatbl" class="table table-striped " style="width:100%;">     
                                <thead>                                
                                    <tr>
                                        <th >Fecha</th>                                        
                                        <th >Valor a recoger</th>
                                        <th >Valor recaudado</th>
                                        <th >Valor faltante</th>
                                        <th >Valor gastos</th>                                        
                                        <th>Valor a capital</th>
                                        <th >Valor a intereses</th>
                                        <th >Ingreso Neto</th>
                                        <th>Abono Neto a capital</th>
                                        <th >Abono Neto a intereses</th>
                                        <th >Valor disponible</th>                                        
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
        
<!-- date-range-picker -->
<script src="../../appweb/lib/moment.js"></script>
<script src="../../appweb/plugins/daterangepicker/daterangepicker.js"></script>    
    
<script>
    
    //Date range as a button
    $('#daterange-btn').daterangepicker({
        "locale": {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "hasta",
            "customRangeLabel": "Seleccionar otro rango de fechas",
            "weekLabel": "W",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Augosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        },
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
            'Mes actual': [moment().startOf('month'), moment().endOf('month')],
            'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        opens: "right",
        },       
        function (start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#fechainicio').val(start.format('YYYY-MM-DD'));
            $('#fechafin').val(end.format('YYYY-MM-DD'));
        
            
    });
    
    /*
    *DATOS CONSULTA
    'sumar valores de la columna
    //https://stackoverflow.com/questions/21940213/sum-column-value-using-datatable-updated
    //http://jsfiddle.net/ishandemon/tb058mLq/2/
    */       
    
    
    /*
    *DATOS CONSULTA
    */       
    $(function(){
        $("#qreport").click(function(){
            var datestart = $("#fechainicio").val();
            var dateend = $("#fechafin").val();                                        
        
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
                    "url": "query-cuadrecaja.php?",
                    "type": "POST",
                    "data": {
                        "qstart" : datestart,
                        "qend" : dateend,
                    }
                },
                "aoColumnDefs": [ {
                    "aTargets": [1,2,3,4,5,6,7,8,9,10],
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        var $currencyCell = $(nTd);
                        var commaValue = $currencyCell.text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
                        $currencyCell.text(commaValue);
                    }
                }],
                "aoColumns": [
                    {
                       "mData": "fecha", "aTargets": [0],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "valoracobrar", "aTargets": [1],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "valorcobrado", "aTargets": [2],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "valorfaltante", "aTargets": [3],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "valorgastos", "aTargets": [4],
                       "render": function (data) {
                           return data;
                       }
                    },                    
                    {
                       "mData": "abonoacapital", "aTargets": [5],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "abonoainteres", "aTargets": [6],
                       "render": function (data) {
                           return data;
                       }
                    },                    
                    {
                       "mData": "ingresoneto", "aTargets": [7],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "abonoacapitalreal", "aTargets": [8],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "abonoainteresreal", "aTargets": [9],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "valordisponible", "aTargets": [10],
                       "render": function (data) {
                           return data;
                       }
                    }
                ],
                "dom": "Bftip",
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Exportar a Excel',
                        /*exportOptions: {
                            columns: [0,1, 2, 3]
                        },
                        extend: 'excelHtml5',
                        text: 'Save as Excel',
                        customize: function( xlsx ) {
                            var sheet = xlsx.xl.worksheets['gastos.xml'];
                            $('row:first c', sheet).attr( 's', '42' );
                        }*/
                        

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
            
        });
    });
    
    
</script>       
</body>
</html>
