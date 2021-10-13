<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars-payin.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-payin.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>

<?php 

/*
*MAPA RUTA
*/
//$mapaRutaHoy = "user_000001";
$mapaRutaHoy = "";
$mapaRutaHoy = queryMapaRutas($idSSUser, $dateFormatDB);
//echo $mapaRutaHoy;
$pathMapa = "../../appweb/files-display/markers/".$mapaRutaHoy.".xml";

/*
*DEFINIR LAT LNG
*/

//ENCONTRAR LA RUTA DEFINIDA PARA HOY
$db->where("id_cobrador", $idSSUser);   
$db->where('fecha_creacion_ruta', $dateFormatDB);        
$queryR = $db->getOne ("rutas_tbl", "id_ruta");

//QUERY ESPECIFICA RUTA
$db->where('id_ruta', $queryR['id_ruta']);    
$db->orderBy("id_especifica_ruta", "desc");
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


//***********
//SITE MAP
//***********

$rootLevel = "mapa";
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
    <link rel="stylesheet" href="../../appweb/plugins/datatables/dataTables.bootstrap.css">
    <?php echo _FAVICON_TOUCH_ ?>
    
    <style>

        #map_canvas_container {
            position: relative; 
            width: 100%; 
            height: 100%;            
        }

        #map {
            height: 480px;
            /*position: absolute; 
            top: 0; 
            right: 0; 
            bottom: 0; 
            left: 0;
            height: 100%;*/
          }
    </style>
</head>
    

<?php echo LAYOUTOPTION ?>
<?php include '../../appweb/tmplt/loadevent.php';  ?>
    
<div class="wrapper">   
    <?php echo "<input type='hidden' id='pathfile' value='".$pathMapa."' />" ?>
    <?php echo "<input type='hidden' id='latcenter' value='".$latitudGEO."' />" ?>
    <?php echo "<input type='hidden' id='lngcenter' value='".$longitudGEO."' />" ?>
    
    <?php 
    /*
    /
    ////HEADER
    /
    */
    ?>
    <?php include '../../appweb/tmplt/header-payin.php';  ?>
                
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php //include '../../appweb/tmplt/side-mm.php';  ?>
    <?php
    /*
    /
    ////WRAP CONTENT
    /
    */
    ?>   
    
    
    
    
    
    <div id="map_canvas_container">
        <?php
        /*
        /*****************************//*****************************
        /HEADER CONTENT
        /*****************************//*****************************
        */
        ?>
        <!--<section class="content-header bg-content-header">
            <h1>
                <span class="text-size-x6">Cobranza</span> / Lista rutas
            </h1>                                    
        </section>-->
        
        <?php if($mapaRutaHoy != ""){ ?>       
        <!--<section class="row  margin-bottom-md">
          
                <div class="col-xs-12 margin-bottom-md">                                        
                    <div class="thumbnail"> embed-responsive embed-responsive-16by9-->
                        <div id="map" class=""></div>
                   <!--</div>                       
                </div>   
            
                
                                                                             
        </section>-->
        <?php }else{ ?>       
        <section class="content box50 margin-top-lg margin-bottom-md">
          
            <div class="">
                <div class="callout">
                    <div class="media">
                        <div class=" media-left padd-hori-xs">
                            <i class="fa fa-map-marker fa-3x shades-text text-muted"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Ruta de cobro</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                No se ha creado una ruta de cobro para hoy
                            </p>



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
    <?php //include 'appweb/tmplt/footer.php';  ?>
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
      label: 'R'
    },
    bar: {
      label: 'B'
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
          var type = markerElem.getAttribute('tel1');
          var point = new google.maps.LatLng(
              parseFloat(markerElem.getAttribute('lat')),
              parseFloat(markerElem.getAttribute('lng')));

          var infowincontent = document.createElement('div');
          var strong = document.createElement('strong');
          strong.textContent = name
          infowincontent.appendChild(strong);
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
   
</body>
</html>
