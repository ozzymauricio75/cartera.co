<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php //require_once '../../appweb/inc/query-productos-catalogo.php'; ?>
<?php //require_once 'ssp.class.php'; ?>
<?php 

$qDateStart = "0000-00-00"; 
$qDateEnd = "0000-00-00"; 
$qCobrador = "";
if(isset($_POST['qstart'])){ 
    $qDateStart = (string)$_POST['qstart']; 
    $qDateEnd = (string)$_POST['qend']; 
    $qCobrador = (empty($_POST['qcobrador'])) ? "" : (int)$_POST['qcobrador']; 
    $qDateStart = $db->escape($qDateStart); 
    $qDateEnd = $db->escape($qDateEnd);
    $qCobrador = $db->escape($qCobrador);
}


/*
*QUERY COBRADORES
*/
//SELECT `id_cobrador`, `id_acreedor`, `id_status_cobrador`, `nombre_cobrador`, `cedula_cobrador`, `mail_cobrador`, `nickname_cobrador`, `pass_cobrador`, `pass_humano_cobrador`, `tel_uno_cobrador`, `tel_dos_cobrador`, `direccion_cobrador`, `barrio_cobrador`, `ciudad_cobrador`, `estado_cobrador`, `pais_cobrador`, `foto_cobrador`, `fecha_alta_cobrador`, `tag_seccion_plataforma` FROM `cobrador_tbl` WHERE 1
function queryCobradores($idCobrador_){
    global $db;  
    $dataQuery = "";//array();
    
    $db->where('id_cobrador', $idCobrador_);    
    $queryTbl = $db->getOne ("cobrador_tbl", "nombre_cobrador");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){    
        $dataQuery = $queryTbl['nombre_cobrador'];
        /*foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        } */   
        return $dataQuery;
    }
}

/*
*QUERY GASTOS
*/
function queryGastos($idAcreedor_, $qDateStart_, $qDateEnd_, $qCobrador_ = null){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);        
    //$db->where('fecha_gastos', array($qDateStart_, $qDateEnd_), 'BETWEEN');
    
    if($qDateStart_ != $qDateEnd_){        
        $db->where('fecha_gastos', array($qDateStart_, $qDateEnd_), 'BETWEEN');    
    }else{
        $db->where('fecha_gastos', $qDateStart_);    
    }    
    if($qCobrador_ != null){
        $db->where('id_cobrador', $qCobrador_);    
    } 
    
    $queryTbl = $db->get ("gastos_tbl");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}

$datas_array = array();
$datas_array = queryGastos($idSSUser, $qDateStart, $qDateEnd, $qCobrador);


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
        
        //SELECT `id_gastos`, `id_acreedor`, `id_cobrador`, `id_status_gastos`, `consecutivo_ruta`, `total_valor_gastos`, `fecha_gastos`, `comentarios_gastos` FROM `gastos_tbl` WHERE 1
        
        $id_item = isset($daKey['id_gastos'])? $db->escape($daKey['id_gastos']) : "";
        $id_cobrador = isset($daKey['id_cobrador'])? $db->escape($daKey['id_cobrador']) : "";
        
        $valor_gastos = isset($daKey['total_valor_gastos'])? $db->escape($daKey['total_valor_gastos']) : "";
        $fecha_gastos = isset($daKey['fecha_gastos'])? $db->escape($daKey['fecha_gastos']) : "";
        $comentarios_gastos = isset($daKey['comentarios_gastos'])? $db->escape($daKey['comentarios_gastos']) : "";
        
        $fecha_gastos_human = date("d/m/Y",strtotime($fecha_gastos));        
        $nombre_cobrador = queryCobradores($id_cobrador);
        $nombre_cobrador = $db->escape($nombre_cobrador);
        $valor_gastos_format = number_format($valor_gastos,0,",","."); 
        $valor_gastos_lyt = "<p><span class='margin-right-xs'>$</span>".$valor_gastos_format."</p>"; 
                                                
        /*
        *VALORES DATAS JSON
        */
                
        $layoutDataItem.='{
        "item":"'.$num.'",
        "id":"'.$id_item.'",
        "cobrador":"'.$nombre_cobrador.'",  
        "valor":"'.$valor_gastos.'",  
        "fecha":"'.$fecha_gastos_human.'",
        "comentario":"'.$comentarios_gastos.'"
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
    "id":"-",
    "cobrador":"-",  
    "valor":"-",  
    "fecha":"-",
    "comentario":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>