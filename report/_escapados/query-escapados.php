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
*QUERY CREDITOS
*/
function queryTotalCreditos($idAcreedor_){
    global $db;  
    $dataQuery = 0;
    
    $db->where('id_acreedor', $idAcreedor_);    
    //$db->where('id_status_credito', "1");    
    $queryTbl = $db->get("creditos_tbl", null, "id_creditos");
        
    $dataQuery = count($queryTbl);    
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
function queryUsuariosEscapados(){
    global $db;  
    $dataQuery = array();    
       
    $queryTbl = $db->get ("usuario_escapado");
    
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;            
        }            
    }
    return $dataQuery;
}

$datas_array = array();
$datas_array = queryUsuariosEscapados();
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
        ////SELECT `id_usuario_escapado`, `id_deudor`, `nombre_deudor`, `cedula_deudor`, `consecutivo_credito`, `valor_deuda_credito`, `usuario_acreedor`, `estado_acreedor`, `ciudad_acreedor`, `hora_consulta_usuario_escapado`, `fecha_consulta_uuario_escapado` FROM `usuario_escapado` WHERE 1 
                        
        $nombre_usuario = isset($daKey['nombre_deudor'])? $db->escape($daKey['nombre_deudor']) : "";
        $cedula_usuario = isset($daKey['cedula_deudor'])? $db->escape($daKey['cedula_deudor']) : "";
        $refcredito = isset($daKey['consecutivo_credito'])? $db->escape($daKey['consecutivo_credito']) : "";
        $totaldeuda = isset($daKey['valor_deuda_credito'])? $db->escape($daKey['valor_deuda_credito']) : "";
        $estado_consulta = isset($daKey['estado_acreedor'])? $db->escape($daKey['estado_acreedor']) : "";
        $ciudad_consulta = isset($daKey['ciudad_acreedor'])? $db->escape($daKey['ciudad_acreedor']) : "";
        $fecha_consulta = ($daKey['fecha_consulta_uuario_escapado'] == "0000-00-00")? "" : $db->escape($daKey['fecha_consulta_uuario_escapado']);
        $hora_consulta = isset($daKey['hora_consulta_usuario_escapado'])? $db->escape($daKey['hora_consulta_usuario_escapado']) : "";
                        
                                
        //formato datos
        $fecha_consulta_human = ($fecha_consulta != "") ? date("d/m/Y",strtotime($fecha_consulta)) : "";
                        
        /*
        *VALORES DATAS JSON
        */
                
        $layoutDataItem.='{
        "item":"'.$num.'",
        "fecha":"'.$fecha_consulta_human.'",
        "hora":"'.$hora_consulta.'",  
        "estado":"'.$estado_consulta.'",  
        "ciudad":"'.$ciudad_consulta.'",
        "nombre":"'.$nombre_usuario.'",
        "cedula":"'.$cedula_usuario.'",
        "refcredito":"'.$refcredito.'",
        "valordeuda":"'.$totaldeuda.'"
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
    "fecha":"-",
    "hora":"-",
    "estado":"-",
    "ciudad":"-",
    "nombre":"-",
    "cedula":"-",
    "refcredito":"-",
    "valordeuda":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>