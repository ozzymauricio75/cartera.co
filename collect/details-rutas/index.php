<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-collects.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>

<?php 
//recibe datos de PEDIDOS
$idOrder = "";
$dataOrders = array();

/***********************************\
*TITULO DESARROLLO
*Autor
*Fecha
*version
*------------------------------------
\************************************/

/*
*TITULO SECCION
*====================================
*/

/*
*SUBTITULO SECCION
*/


/**
 **EXPLICACION/PROLOGO BLOQUE CODIGO
 ' paso 1
 ' paso 2
 ' paso 3
*/

//COMENTARIO ENTRE LINEAS CODIGO

/*[INICIO|FIN bloque codigo]*/




$loockVar = "false";
$idItemPOST = "";
$datasCuotasRuta = array();
if(isset($_POST['itemid_var']) && $_POST['itemid_var'] != ""){
    
    //Validacion variable POST
    $idItemPOST = $_POST['itemid_var'];
    $valida_idItemPOST = validaInteger($idItemPOST, "1");
    
    //Validacion TRUE
    if($valida_idItemPOST === true){   
        $loockVar = "true";      
        
        
        /*
        *DETALLES DE LA RUTA
        */
        $datasRutas = array();
        $datasRutas = queryRutasDetalle($idItemPOST);
        $rutas_lyt = "";

        //echo "<pre>";
        //print_r($datasRutas);

        $totalDeudores = 0;
        $valorRecaudosRuta = 0;
        /*[INICIO|$datasRutas]*/
        if(is_array($datasRutas) && !empty($datasRutas)){
            /*[INICIO FOREACH|$datasRutas]*/
            foreach($datasRutas as $drKey){
                $idRuta = $drKey['idRuta'];
                $consecutivoRuta = $drKey['consecutivoRuta'];
                $nombreCobradorRuta = $drKey['nombreCobradorRuta'];
                $fechaPublicacionRuta = date("d/m/Y", strtotime($drKey['fechaRegistroRuta']));
                $datasEspecificaRuta = $drKey['datasespecificacionesruta'];
                $datasCobrador = $drKey['datascobrador'];
                
                /*
                *INFORMACION COBRADOR
                */
                
                if(is_array($datasCobrador) && !empty($datasCobrador)){
                    foreach($datasCobrador as $dcKey){
                        $nombre_cobrador_ruta = $datasCobrador['nombre_cobrador'];
                        $tel1_cobrador_ruta = $datasCobrador['tel_uno_cobrador'];
                        $tel2_cobrador_ruta = $datasCobrador['tel_dos_cobrador']; 
                    }
                }
                
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

                
            }
            /*[FIN FOREACH|$datasRutas]*/
        }   
        /*[FIN|$datasRutas]*/
        
        
        
        /*
        *MAPA RUTA
        */
        //QUERY RUTAS
        function queryMapaRutas($idRuta_){
            global $db;    
            global $dateFormatDB;
            $dataQuery = array();

            $db->where('id_ruta', $idRuta_);                 
            $queryTbl = $db->getOne ("rutas_tbl", "consecutivo_ruta");

            $dataQuery = $queryTbl['consecutivo_ruta'];

            return $dataQuery;
        }

        
        
        //$mapaRutaHoy = "user_000001";
        $mapaRutaHoy = "";
        $mapaRutaHoy = queryMapaRutas($idItemPOST);
        //echo $mapaRutaHoy;
        $pathMapa = "../../appweb/files-display/markers/".$mapaRutaHoy.".xml";

        /*
        *DEFINIR LAT LNG
        */

        //ENCONTRAR LA RUTA DEFINIDA PARA HOY
        //$db->where("id_cobrador", $idSSUser);   
        //$db->where('fecha_creacion_ruta', $dateFormatDB);        
        //$queryR = $db->getOne ("rutas_tbl", "id_ruta");

        //QUERY ESPECIFICA RUTA
        $db->where('id_ruta', $idItemPOST);    
        $db->orderBy("id_especifica_ruta", "asc");
        $queryER = $db->getOne ("especifica_ruta_tbl", "id_deudor");

        //QUERY INFO DEUDOR
        $db->where('id_deudor', $queryER['id_deudor']);        
        $queryDeudor = $db->get ("deudor_tbl", 1, "latitud_geo_deudor, longitud_geo_deudor");

        $latitudGEO = 0;
        $longitudGEO = 0;
        $rowQueryDeudor = count($queryDeudor);
        if ($rowQueryDeudor > 0){        
            foreach ($queryDeudor as $qdKey) {                        
                $latitudGEO = $qdKey['latitud_geo_deudor'];
                $longitudGEO = $qdKey['longitud_geo_deudor'];
            }    
        }
        
        
        
        
        
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
    <link rel="stylesheet" href="../../appweb/plugins/datatables/dataTables.bootstrap.css">
    <?php echo _FAVICON_TOUCH_ ?>    
    
    <style>
      #map {
        height: 420px;
      }
    </style>
    
    
</head>
    
<?php echo LAYOUTOPTION ?><!---//print body tag--->    
<?php include '../../appweb/tmplt/loadevent.php';  ?>
<?php echo "<input type='hidden' id='pathfile' value='".$pathMapa."' />" ?>
<?php echo "<input type='hidden' id='latcenter' value='".$latitudGEO."' />" ?>
<?php echo "<input type='hidden' id='lngcenter' value='".$longitudGEO."' />" ?>    
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
                <span class="text-size-x6">Cobranza</span> / Rutas detalle
            </h1>      
            <a href="<?php echo $pathmm."/collect/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de rutas
            </a> 
        </section>
        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <?php if($loockVar != "false"){ ?>
        <section class="content">
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <div class="box box-solid bg-red-gradient margin-bottom-xs">
                        <div class="box-header with-border">
                            <i class="fa fa-money fa-2x margin-right-xs"></i>
                            <h3 class="box-title">Valor total ruta</h3>
                        </div>
                        <div class="box-body">
                            <p class="text-size-x6 text-center">
                            <span class="margin-right-xs text-size-x7">$</span><?php echo $valorRecaudosRutaFormat; ?>
                            </p>
                        </div>
                    </div>
                    
                    
                    <div class="box box-default ">
                        <div class="box-header with-border">
                            <span class="text-red margin-right-xs">Ref. Ruta</span>
                            <h3 class="box-title"><?php echo $consecutivoRuta; ?></h3>
                        </div>
                        <div class="box-body">
                            <p class="margin-bottom-xs">
                                <i class="fa fa-calendar fa-lg margin-right-xs"></i>
                                Fecha ruta
                                <strong class="pull-right"><?php echo $fechaPublicacionRuta; ?></strong>
                            </p>
                            <p class="margin-bottom-xs">
                                <i class="fa fa-user fa-lg margin-right-xs"></i>
                                Cobrador
                                <strong class="pull-right"><?php echo $nombre_cobrador_ruta; ?></strong>
                            </p>
                            <p class="margin-bottom-xs">
                                <i class="fa fa-phone fa-lg margin-right-xs"></i>
                                Télefono 1
                                <strong class="pull-right"><?php echo $tel1_cobrador_ruta; ?></strong>
                            </p>
                            <p class="margin-bottom-xs">
                                <i class="fa fa-phone fa-lg margin-right-xs"></i>
                                Télefono 2
                                <strong class="pull-right"><?php echo $tel2_cobrador_ruta; ?></strong>
                            </p>
                                                       
                        </div>
                    </div>
                    
                </div>
                <div class="col-xs-12 col-sm-8">
                    <div class="thumbnail">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
            
            
            <div class="section-title">
                <h3>Detalle ruta</h3>    
            </div> 
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">              
                        <div class="box-body ">
                            <table id="printdatatbl" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Credito</th>
                                        <th>Valor a pagar</th>
                                        <th># Cuota</th>
                                        <th>Deudor</th>
                                        <th>Dirección </th>
                                        <th>Status</th>
                                        <!--<th>Opciones</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                /*
                                *DETALLES DE LOS RECAUDOS PARA ESTA RUTA
                                */
                                $datasCuotasRuta = queryRecaudosRutaDetalles($idItemPOST); //queryCreditosDetalles($idSSUser, $idItemPOST);

                                //echo "<pre>";
                                //print_r($datasCuotasRuta);
                                /*[INICIO |$datasCuotasRuta]*/
                                if(is_array($datasCuotasRuta) && !empty($datasCuotasRuta)){
                                    /*[INICIO FOREACH |$datasCuotasRuta]*/
                                    foreach($datasCuotasRuta as $dcrKey){

                                        $id_especifica_ruta = $dcrKey['idespecificaruta'];
                                        $id_credito_ruta = $dcrKey['idcreditos'];                
                                        $datas_recaudo_ruta = $dcrKey['datasrecaudo'];


                                        //DATAS RECAUDO RUTA
                                        /*[INICIO FOREACH |$datas_recaudo_ruta]*/
                                        if(is_array($datas_recaudo_ruta) && !empty($datas_recaudo_ruta)){
                                            foreach($datas_recaudo_ruta as $dcrrKey){   
                                                $consecutivo_credito_ruta = $datas_recaudo_ruta['consecutivo'];
                                                $numecuota_credito_ruta = $datas_recaudo_ruta['numeroCuota'];
                                                $valor_cuota_ruta = $datas_recaudo_ruta['valorCuota'];
                                                $valor_faltante_cuota = $datas_recaudo_ruta['valorFaltanteCuota'];
                                                $valor_recalculado_ruta = $datas_recaudo_ruta['valorRecalculado'];
                                                $idstatus_recaudo_ruta = $datas_recaudo_ruta['idstatusrecaudo'];
                                                $datas_credito_ruta = $datas_recaudo_ruta['datascredito'];
                                                

                                                //DEFINE STATUS RECAUDO
                                                $statusRecaudo = "";
                                                switch($idstatus_recaudo_ruta){

                                                    case "1":
                                                        $statusRecaudo = "Pagado";    
                                                    break;
                                                    case "2":
                                                        $statusRecaudo = "Abono";    
                                                    break;
                                                    case "3":
                                                        $statusRecaudo = "Por pagar";    
                                                    break;
                                                }

                                                //RECALCULO PROXIMA CUOTA 
                                                $valorFinalCuota = 0;
                                                if($valor_faltante_cuota == 0){
                                                    $valorFinalCuota = $valor_cuota_ruta;
                                                    $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
                                                }else{
                                                    $valorFinalCuota = $valor_faltante_cuota;    
                                                    $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
                                                }

                                                //DATAS CREDITO RUTA
                                                if(is_array($datas_credito_ruta) && !empty($datas_credito_ruta)){
                                                    foreach($datas_credito_ruta as $dcredrKey){
                                                        $datas_deudor_ruta = $datas_credito_ruta['datadeudor'];
                                                        
                                                        
                                                        //DATAS DEUDOR RUTA
                                                        if(is_array($datas_deudor_ruta) && !empty($datas_deudor_ruta)){
                                                            foreach($datas_deudor_ruta as $ddrKey){
                                                                $nombre_deudor_ruta = $datas_deudor_ruta['primer_nombre_deudor'];
                                                                $apellido_deudor_ruta = $datas_deudor_ruta['primer_apellido_deudor'];

                                                                $nombreCompletoDeudorRuta = $nombre_deudor_ruta."&nbsp;".$apellido_deudor_ruta;

                                                                $direccion_deudor_ruta = $datas_deudor_ruta['dir_geo_deudor'];
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                


                                            }
                                        }
                                        /*[FIN FOREACH |$datas_recaudo_ruta]*/

                                        
                                        /*
                                        *LAYOUT DETALLES CUOTAS RUTA
                                        */        
                                        
                                        /*[INICIO |LAYOUT <TR>]*/
                                        $cuotas_ruta_lyt = "<tr>";
                                        
                                        /*
                                        *REFERENCIA CREDITO
                                        */
                                        $cuotas_ruta_lyt .= "<td>";
                                        $cuotas_ruta_lyt .= $consecutivo_credito_ruta;
                                        $cuotas_ruta_lyt .= "</td>";
                                                
                                        /*
                                        *VALOR A PAGAR
                                        */
                                        $cuotas_ruta_lyt .= "<td>";
                                        $cuotas_ruta_lyt .= "<span class='margin-right-xs'>$</span>".$valorFinalCuotaFormat;
                                        $cuotas_ruta_lyt .= "</td>";
                                                
                                        /*
                                        *NUMERO CUOTA
                                        */
                                        $cuotas_ruta_lyt .= "<td>";
                                        $cuotas_ruta_lyt .= $numecuota_credito_ruta;
                                        $cuotas_ruta_lyt .= "</td>";
                                                
                                        /*
                                        *DEUDOR
                                        */
                                        $cuotas_ruta_lyt .= "<td>";
                                        $cuotas_ruta_lyt .= $nombreCompletoDeudorRuta;
                                        $cuotas_ruta_lyt .= "</td>";
                                                
                                        /*
                                        *DIRECCION DEUDOR
                                        */
                                        $cuotas_ruta_lyt .= "<td>";
                                        $cuotas_ruta_lyt .= $direccion_deudor_ruta;
                                        $cuotas_ruta_lyt .= "</td>";
                                                
                                        /*
                                        *STATUS CUOTA RUTA
                                        */
                                        $cuotas_ruta_lyt .= "<td>";
                                        $cuotas_ruta_lyt .= $statusRecaudo;
                                        $cuotas_ruta_lyt .= "</td>";
                                                
                                        /*
                                        *ACCIONES ITEM
                                        */
                                        //$cuotas_ruta_lyt .= "<td>";
                                        //$cuotas_ruta_lyt .= $statusRecaudo;
                                        //$cuotas_ruta_lyt .= "</td>";
                                                
                                        $cuotas_ruta_lyt .= "</tr>";
                                        /*[FIN |LAYOUT <TR>]*/
                                                
                                        echo $cuotas_ruta_lyt;
                                    }
                                    /*[FIN FOREACH|$datasCuotasRuta]*/
                                }
                                /*[FIN |$datasCuotasRuta]*/
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->   
                    </div> 
                </div>
            </div>
        </section>
        <?php }else{ ?>
        
        <section class="content ">                    
            <div class="box50">
                <div class="alert">
                    <div class="media text-muted">
                        <div class=" media-left">
                            <i class="fa fa-unlink fa-4x"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Oops!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                No encontramos o fue eliminada la RUTA que deseas visualizar
                            </p>              
                            <p style="font-size:1.232em; line-height:1;"> Asegurate que seleccionaste la ruta correcta, e intentalo de nuevo</p>
                        </div>

                    </div>                    
                </div>
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group text-center">
                            <a href="<?php echo $pathmm."/collect/"; ?>" type="button" class="btn btn-default">
                                <i class='fa fa-th-list fa-lg margin-right-xs'></i>
                                <span>Ir a Rutas</span>                        
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        
        <?php } ?>
        
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
<?php echo _JSFILESLAYOUT_ ?>
    
<script>
    
var pathFile = $("#pathfile").val();
var latcenter = $("#latcenter").val();
var lngcenter = $("#lngcenter").val();
             
  var customLabel = {
    restaurant: {
      label: 'D'
    },
    bar: {
      label: 'D'
    }
  };

    function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      //center: new google.maps.LatLng(-18.941662, -48.285332),
      center: new google.maps.LatLng(latcenter, lngcenter),
      zoom: 12
    });
    var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP or XML file
      //downloadUrl('../../appweb/files-display/markers/user_000001.xml', function(data) {
    downloadUrl(pathFile, function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
        Array.prototype.forEach.call(markers, function(markerElem) {
          var name = markerElem.getAttribute('name');
          var address = markerElem.getAttribute('address');
          var phone = "";
            var phone2 = "";
            
            if(markerElem.getAttribute('tel1') != null){
              phone = markerElem.getAttribute('tel1');  
            }
            if(markerElem.getAttribute('tel2') != null){
              phone2 = markerElem.getAttribute('tel2');  
            }
            
          //var phone2 = markerElem.getAttribute('tel2');
          var type = markerElem.getAttribute('tel1');
          var point = new google.maps.LatLng(
              parseFloat(markerElem.getAttribute('lat')),
              parseFloat(markerElem.getAttribute('lng')));

          var infowincontent = document.createElement('div');
          var strong = document.createElement('strong');
          strong.textContent = name
          infowincontent.appendChild(strong);
          infowincontent.appendChild(document.createElement('br'));

          var phones = document.createElement('text');
          phones.textContent = "Tel:" +phone+" "+phone2
          infowincontent.appendChild(phones);
          infowincontent.appendChild(document.createElement('br'));
            
          var text = document.createElement('text');
          text.textContent = address
          infowincontent.appendChild(text);
                        
          var icon = customLabel[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            label: icon.label
          });
          marker.addListener('click', function() {
            infoWindow.setContent(infowincontent);
            infoWindow.open(map, marker);
          });
        });
      });
    }


  function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;

    request.onreadystatechange = function() {
      if (request.readyState == 4) {
        request.onreadystatechange = doNothing;
        callback(request, request.status);
      }
    };

    request.open('GET', url, true);
    request.send(null);
  }

  function doNothing() {}
</script>    
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgZFZPgdG2kRiFoE123zr-PA02WR_8yG4&callback=initMap">
</script>
    
<!-- DataTables -->
<script src="../../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    
    $('#printdatatbl').DataTable({        
        "scrollX": false,
        "ordering": true,
        "autoWidth": false
    });
  });
</script>    
</body>
</html>
