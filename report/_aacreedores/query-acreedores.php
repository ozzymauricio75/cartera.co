<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-tablas-complementarias.php'; ?>
<?php //require_once 'ssp.class.php'; ?>
<?php 

/*
*QUERY CREDITOS ACTIVOS
*/
function queryCreditosActivos($idAcreedor_){
    global $db;  
    $dataQuery = 0;
    
    $db->where('id_acreedor', $idAcreedor_);    
    $db->where('id_status_credito', array(1,3), "IN");    
    $queryTbl = $db->get("creditos_tbl", null, "id_creditos");
        
    $dataQuery = count($queryTbl);    
    return $dataQuery;
}

/*
*QUERY CREDITOS REGISTRADOS
*/
function queryCreditosRegistrados($idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);    
    //$db->where('id_status_credito', array(1,3), "IN");    
    $queryTbl = $db->get("creditos_tbl", null, "id_creditos, id_status_credito");
        
    $dataQuery = $queryTbl;    
    return $dataQuery;
}

/*
*QUERY NICK NAME
*/

function queryNickname($idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_usuario_plataforma', $idAcreedor_);  
    $db->where('tag_seccion_plataforma', "acreedor");  
    $queryTbl = $db->get("usuario_tbl", 1, "nickname_usuario, id_status_usuario");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }
    }
    return $dataQuery;
}

/*
*QUERY ACREEDORES
*/
function queryAcreedores(){
    global $db;  
    $dataQuery = array();
    //$numecreditosActivos = 0;
    //$numecreditosRegistrados = 0;
    $nickname = array();
        
    $queryTbl = $db->get ("acreedor_tbl", NULL, "id_acreedor, primer_nombre_acreedor, segundo_nombre_acreedor, primer_apellido_acreedor,segundo_apellido_acreedor, tel_uno_acreedor, tel_dos_acreedor, fecha_alta_acreedor");
    
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            //$dataQuery[] = $qKey;
            
            //$numecreditosActivos = queryCreditosActivos($qKey['id_acreedor']);
            //$numecreditosRegistrados = queryCreditosRegistrados($qKey['id_acreedor']);
            $nickname = queryNickname($qKey['id_acreedor']);
            
            $dataQuery[] = array(
                "idacreedor" => $qKey['id_acreedor'],
                "primernombre" => $qKey['primer_nombre_acreedor'],
                "segundonombre" => $qKey['segundo_nombre_acreedor'],
                "primerapellido" => $qKey['primer_apellido_acreedor'],
                "segundoapellido" => $qKey['segundo_apellido_acreedor'],
                "teluno" => $qKey['tel_uno_acreedor'],
                "teldos" => $qKey['tel_dos_acreedor'],
                "fecharegistro" => $qKey['fecha_alta_acreedor'],
                //"creditosactivos" => $numecreditosActivos,
                //"creditosregistrados" => $numecreditosRegistrados,
                "nickname" => $nickname
            );
        }    
        return $dataQuery;
    }
}

$datas_array = array();
$datas_array = queryAcreedores();
/*echo "<pre>";
print_r($datas_array);*/


/*
 *==================================
 *CREA LAYOUT DATAS
 *==================================
*/

$layoutDataItem = "";
$totalRegistros = count($datas_array);
$num = 0;

if(is_array($datas_array) && !empty($datas_array)){
    foreach($datas_array as $daKey){
        $idacreedor = isset($daKey['idacreedor'])? $db->escape($daKey['idacreedor']) : "";                
        $pimernombre = isset($daKey['primernombre'])? $db->escape($daKey['primernombre']) : "";
        $segundonombre = isset($daKey['segundonombre'])? $db->escape($daKey['segundonombre']) : "";
        $primerapellido = isset($daKey['primerapellido'])? $db->escape($daKey['primerapellido']) : "";
        $segundoapellido = isset($daKey['segundoapellido'])? $db->escape($daKey['segundoapellido']) : "";
        $teluno = isset($daKey['teluno'])? $db->escape($daKey['teluno']) : "";
        $teldos = isset($daKey['teldos'])? $db->escape($daKey['teldos']) : "";
        $fecharegistro = isset($daKey['fecharegistro'])? $db->escape($daKey['fecharegistro']) : "";
        //$totalcreditos = isset($daKey['totalcreditos'])? $db->escape($daKey['totalcreditos']) : "";
        $datanickname = $daKey['nickname'];
        
        if(is_array($datanickname ) && !empty($datanickname )){
            foreach($datanickname  as $dnKey){
                $nickname = $datanickname["nickname_usuario"];
                $statusacreedor = queryStatusGB($datanickname["id_status_usuario"]);
            }
        }
                        
        //define telefono
        $telefonoacreedor = "";
        if($teluno != "" && $teldos != ""){
            $telefonoacreedor = $teluno." / ".$teldos;
        }else if($teluno != "" && $teldos == ""){
            $telefonoacreedor = $teluno;
        }else if($teluno == "" && $teldos != ""){
            $telefonoacreedor = $teldos;
        }
                        
        //formato datos
        $fecha_registro_human = date("d/m/Y",strtotime($fecharegistro));        
        $nombre_acreedor = $pimernombre." ".$segundonombre." ".$primerapellido." ".$segundoapellido; 
        
        $status_lyt ="<span class='badge bg-gray padd-hori-xs text-size-x2'>".$statusacreedor."</span>";                                        
        
        //calculo creditos registrados
        $creditos_registrados = array();
        $creditos_registrados = queryCreditosRegistrados($idacreedor);
        
        $total_creditos_registrados = 0;
        $total_creditos_activos = 0;
        if(is_array($creditos_registrados) && !empty($creditos_registrados)){
            foreach($creditos_registrados as $crKey){
                $idStatusCredito = $crKey['id_status_credito'];
                
                if($idStatusCredito == "1" || $idStatusCredito == "3"){
                    $total_creditos_activos++;    
                }
                
                $total_creditos_registrados++;
                
            }
        }
        
        //calculo creditos activos
        /*
        *VALORES DATAS JSON
        */
                
        $layoutDataItem.='{
        "item":"'.$num.'",
        "nickname":"'.$nickname.'",
        "nombre":"'.$nombre_acreedor.'",  
        "tel":"'.$telefonoacreedor.'",  
        "fecharegistro":"'.$fecha_registro_human.'",
        "numerocreditosregistrados":"'.$total_creditos_registrados.'",
        "numerocreditosactivos":"'.$total_creditos_activos.'",
        "status":"'.$status_lyt.'"
        },';
        
        $num++;
    }
    /*-/FIN FOREACH DATAS/-*/

        
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);
    
    echo '{"draw": 1,
    "recordsTotal": '.$totalRegistros.',
    "recordsFiltered": '.$totalRegistros.',
    "data":['.$layoutDataItem.']}';    
    

    
}else{
    //$data['error'] = "ERROR: No se encontraron registros";
   // echo json_encode( $data );
    $layoutDataItem.='{
    "item":"-",
    "nickname":"-",
    "nombre":"-",
    "tel":"-",
    "fecharegistro":"-",
    "numerocreditosregistrados":"-",
    "numerocreditosactivos":"-",
    "status":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>