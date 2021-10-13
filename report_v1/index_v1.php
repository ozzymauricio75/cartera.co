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
    <title><?php echo METATITLE ?></title>    
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="author" content="massin">
    <?php echo _CSSFILESLAYOUT_ ?>    
    <link rel="stylesheet" href="../appweb/plugins/datatables/dataTables.bootstrap.css">
    <!--<link rel="stylesheet" href="../appweb/plugins/morris/morris.css">-->
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
                <span class="text-size-x6">Reportes</span>
            </h1>                  
        </section>
        


        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <div class="box50">
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Gastos
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Recaudos
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Acreedores
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Cuadre de caja
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Planes de pago
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Creditos
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Deudores escapados
                        </a>
                    </li>
                </ul>
            </div>
        <section class="content">       
            <div class="box50">
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            <strong>Gastos</strong>
                            <p>alfaudhn ad asld al alskd al laksdj laskjd lasdk</p>
                        </a>
                    </li>
                    <li>
                        <a href="">                                                          
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>  
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Recaudos</span>
                                <p class="description">alfaudhn ad asld al alskd al laksdj laskjd lasdk</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Acreedores
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Cuadre de caja
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Planes de pago
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Creditos
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-chevron-right pull-right text-blue"></i>    
                            Deudores escapados
                        </a>
                    </li>
                </ul>
            </div>
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
                    <div class="box50">
                    <canvas id="myChart" style="height:400px"></canvas>
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
                                        <th width="140px;">Opciones</th>
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

                      
<script src="../appweb/plugins/chartjs/Chart-2.4.0.min.js" />
<script src="../appweb/lib/moment.jsmoment.js"></script>
<script>
    
$(function() {
    /*
    *REALIZAR CONSULTA
    */
    /*var json = (function () {
        var json = null;
        $.ajax({
            'async': false,
            'global': false,
            'url': 'cobaJson.php',
            'dataType': "json",
            'success': function (data) {
                json = data;
            }
        });
        return json;
    });*/
    
    /*
    *CREAR EL GRAFICO
    */
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
                label: "My First dataset",
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [0, 10, 5, 2, 20, 30, 45],
            }]
        },

        // Configuration options go here
        options: {}
    });
    
});                        
</script>

      
<!-- DataTables     -->
<script src="../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- Morris.js charts 
<script src="../appweb/lib/raphael-min.js"></script>
<script src="../appweb/plugins/morris/morris.min.js"></script>    
-->    
    
    
<script>
    /*//CHARTJS
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });*/
        
    //MORRIS
    /*
    http://morrisjs.github.io/morris.js/#your_first_chart
    https://www.youtube.com/watch?v=ktUOY0OAFmA
    http://www.webslesson.info/2017/03/morrisjs-chart-with-php-mysql.html
    https://stackoverflow.com/questions/30910472/how-to-send-my-mysql-data-with-php-code-to-morris-data-js
    */
    /*$(document).ready(function(){
        var detailurl = $("#paththisfile").val();

        $(".userdetails").each(function(){ 
            

            var itemid = $(this).attr("data-item");
            var itemtype = $(this).attr("data-type");

            $(this).click(function(){        
                //alert("me dieron click "+itemid);
                //$.redirect(detailurl,{ itemid_var: itemid, itemtype_var: itemtype}); 
            });
        });

    });*/
        
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
                },
                {
                   "mData": "actions", "aTargets": [6],
                   "render": function (data) {
                       return data;
                   }
                },
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
