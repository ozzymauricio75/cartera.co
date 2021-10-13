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
    <title><?php echo METATITLE ?> - Reportes - Recaudos_<?php echo $dateFormatPost; ?></title>    
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
                
                <span class="text-size-x6">Reportes</span> / Recaudos
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
                <?php 
                    $cobradorLyt = "";

                    $db->where("id_acreedor", $idSSUser );
                    $db->orderBy("nombre_cobrador","Asc");			
                    $cobradorQuery = $db->get('cobrador_tbl', null, 'id_cobrador, nombre_cobrador');                        
                    if(is_array($cobradorQuery)) {
                        foreach($cobradorQuery as $cobraKey){
                            $idCobradorTbl = $cobraKey['id_cobrador'];
                            $nameCobradorTbl = $cobraKey['nombre_cobrador'];                           

                            $cobradorLyt .= "<option value='".$idCobradorTbl."'>";
                            $cobradorLyt .= $nameCobradorTbl;
                            $cobradorLyt .= "</option>";                                                                
                        }                                                    
                    }   

                    if($cobradorLyt != ""){
                ?>
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
                        <select id="cobradorinput" class="form-control " name="cobradorinput">
                            <option value="" selected>Todos los cobradores</option>
                            <?php echo $cobradorLyt;  ?>
                        </select>
                    </div>    
                    <div class="form-group">
                        <button class="btn btn-primary" id="qreport">
                            <i class="fa fa-search margin-right-xs"></i>
                            Consultar
                        </button>
                    </div>
                </div>

                <?php }else{ ?>
                <div class="box50">
                    <div class="callout  margin-verti-md">
                        <div class="media">
                            <div class=" media-left padd-hori-xs">
                                <i class="fa fa-motorcycle fa-3x shades-text text-muted"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="no-padmarg">No has creado cobradores</h3>
                                <p style="font-size:1.232em; line-height:1;">
                                    Registra almenos un cobrador en tu sistema
                                </p>
                                <a href="<?php echo $pathmm."/collect/new/"; ?>" class="btn btn-success btn-sm" style="text-decoration:none; ">Nuevo cobrador <i class="fa fa-plus fa-lg margin-left-xs"></i></a>
                            </div>
                        </div>
                    </div>   
                </div>

                
                <?php } ?>
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
                                        <th >Cobrador</th>
                                        <th >Ref. Credito</th>
                                        <th >Status Credito</th>
                                        <th ># Cuota</th>
                                        <th >Fecha</th>
                                        <th>Valor</th>
                                        <th >Deudor</th>
                                        <th >Tel.</th>
                                        <th >Barrio</th>
                                        <th >Dir.</th>
                                        <th class="hide">Estado</th>
                                        <th class="hide">Valor</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot class="infofoot ">
                                    <tr>
                                        <td colspan='11' class="text-right">
                                            <p class="badge bg-blue text-size-x3 padd-hori-xs">
                                                <span class="margin-righ-xs">$</span>
                                                <span id="valorTotal"></span> 
                                            </p>
                                        </td>
                                    </tr>
                                </tfoot>
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
    
<script src="../../appweb/plugins/jquery.number.js"></script>    
    
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
    
    
    $(function(){
        //captura valor de cobrador
        var cobradorinput;
        var totalrecaudos = 0;
        /*$("#cobradorinput").change(function(){                
            cobradorinput = $(this).val();
        });*/
        
        $(".infofoot").hide();
        
        $("select[name='cobradorinput']").change( function(){
            $("option:selected").each(function(){
                cobradorinput = $(this).val(); 

            });
        });                                        
        //click en el boton consultar
        $("#qreport").click(function(){
            
            var datestart = $("#fechainicio").val();
            var dateend = $("#fechafin").val();       
            
            if(datestart == ""){
                return false;    
            }
            //alert(datestart);
            /*
             'https://datatables.net/manual/tech-notes/3
             'como la pagina no se refresca, debemos destruir la tabla previamente inicializada para cargar los datos nuevos con la consulta mas reciente
            */
                                               
            $(".infofoot").show();
           
            var tablarecaudos = $('#printdatatbl').DataTable({                 
                "destroy": true,//para resetear la tabla previamente creada
                "autoWidth": false,
                "order": [[ 0, "asc" ]],
                "processing": true,
                "bDeferRender": true,			
                "sPaginationType": "full_numbers",
                "ajax": {
                    "url": "query-recaudos.php?",
                    "type": "POST",
                    "data": {
                        "qstart" : datestart,
                        "qend" : dateend,
                        "qcobrador" : cobradorinput
                    }
                },
                "aoColumnDefs": [ {
                    "aTargets": [5],
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        var $currencyCell = $(nTd);
                        var commaValue = $currencyCell.text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
                        $currencyCell.text(commaValue);
                    }
                }],
                "aoColumns": [
                    {
                       "mData": "cobrador", "aTargets": [0],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "refcredito", "aTargets": [1],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "statuscredito", "aTargets": [2],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "cuota", "aTargets": [3],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "vence", "aTargets": [4],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "valorcuota", "aTargets": [5],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "nombre", "aTargets": [6],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "tel", "aTargets": [7],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "barrio", "aTargets": [8],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "dir", "aTargets": [9],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "estado", "aTargets": [10],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "valor", "aTargets": [11],
                       "render": function (data) {
                           return data;
                       }
                    }
                ],  
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;	 
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ? i.replace(/[\$.]/g, '')*1 : typeof i === 'number' ?	i : 0;
                    };

                    // valor_total over all pages
                    valor_total = api.column( 5 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    },0 );

                    // valor_total_pagina over this page
                    valor_total_pagina = api.column( 5, { page: 'current'} ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    valor_total_pagina = parseFloat(valor_total_pagina);
                    valor_total = parseFloat(valor_total);
                    
                    // Update footer
                    //$('#valorTotal').html(valor_total_pagina+" / "+valor_total );				
                    $('#valorTotal').html(valor_total).number( true, 0, ',', '.' );  
                },	
                "dom": "Bftip",                
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Exportar a Excel'
                        /*$.extend( true, {}, buttonCommon, {
                            extend: 'excelHtml5',
                            text: 'Exportar a Excel'
                        } )*/
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
            
            //totalrecaudos = tablarecaudos.column( 4 ).data().sum();
        
        });
        
        //$("#valortotalrecaudos").val(totalrecaudos);
        //$('#valorTotal').html().number( true, 2 );      
    });
    
    
</script>       
</body>
</html>
