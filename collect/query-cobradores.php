<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-tablas-complementarias.php'; ?>
<?php //require_once 'ssp.class.php'; ?>
<?php 




/*
*QUERY COBRADORES
*/

//SELECT `id_cobrador`, `id_acreedor`, `id_status_cobrador`, `nombre_cobrador`, `cedula_cobrador`, `mail_cobrador`, `nickname_cobrador`, `pass_cobrador`, `pass_humano_cobrador`, `tel_uno_cobrador`, `tel_dos_cobrador`, `direccion_cobrador`, `ciudad_cobrador`, `estado_cobrador`, `pais_cobrador`, `foto_cobrador`, `fecha_alta_cobrador`, `tag_seccion_plataforma` FROM `cobrador_tbl` WHERE 1
    
function queryCobrador($idAcreedor_){
    global $db;  
    $dataQuery = array();
    $db->where('id_acreedor', $idAcreedor_);        
    $db->where('id_status_cobrador', '5', '!='); 
    $db->orderBy('nombre_cobrador', "asc");        
    $queryTbl = $db->get ("cobrador_tbl", null, "id_cobrador, id_status_cobrador, nombre_cobrador, cedula_cobrador, mail_cobrador, tel_uno_cobrador, tel_dos_cobrador, direccion_cobrador, ciudad_cobrador, estado_cobrador, fecha_alta_cobrador");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}

$datas_array = array();
$datas_array = queryCobrador($idSSUser);


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
        
        $id_item = isset($daKey['id_cobrador'])? $db->escape($daKey['id_cobrador']) : "";        
        $status_item = isset($daKey['id_status_cobrador'])? $db->escape($daKey['id_status_cobrador']) : "";        
        $nombrecompleto_item = isset($daKey['nombre_cobrador'])? $db->escape($daKey['nombre_cobrador']) : "";
        $dirgeo_item = isset($daKey['direccion_cobrador'])? $db->escape($daKey['direccion_cobrador']) : "";
        $ciudad_item = isset($daKey['ciudad_cobrador'])? $db->escape($daKey['ciudad_cobrador']) : "";
        $estado_item = isset($daKey['estado_cobrador'])? $db->escape($daKey['estado_cobrador']) : "";
        $email_item = isset($daKey['mail_cobrador'])? $db->escape($daKey['mail_cobrador']) : "";
        $cedula_item = isset($daKey['cedula_cobrador'])? $db->escape($daKey['cedula_cobrador']) : "";   
        $tel1_item = isset($daKey['tel_uno_cobrador'])? $db->escape($daKey['tel_uno_cobrador']) : "";
        $tel2_item = isset($daKey['tel_dos_cobrador'])? $db->escape($daKey['tel_dos_cobrador']) : "";        
        $fecharegistro_item = isset($daKey['fecha_alta_cobrador'])? date("d/m/Y",strtotime($daKey['fecha_alta_cobrador'])) : "";
        
        $direccion_full = $dirgeo_item."<br>";
        $direccion_full .= $ciudad_item." / ".$estado_item;
        
        /*
        *LAYOUT BTN
        */
        $detallesBTN = '<a href=\"details-collector/?itemid_var='.$id_item.'&itemtype_var=cobradordb\" type=\"button\" data-item=\"'.$id_item.'\" data-type=\"cobradordb\" class=\"btn btn-primary \">Detalles <i class=\"fa fa-chevron-right margin-left-xs\" ></i></a>';
        
        
        /*
        *STATUS
        */
        $bgStatus = "";
        
        if($status_item == 1){
            $bgStatus = "bg-green";    
        }elseif($status_item == 2){
            $bgStatus = "bg-danger";    
        }elseif($status_item == 3){
            $bgStatus = "bg-black";    
        }
        
        $nombreStatus = queryStatusGB($status_item);
        $status_lyt = "";
        $status_lyt .= "<span class='badge ".$bgStatus."'>".$nombreStatus."</span>";
        
                                        
        /*
        *VALORES DATAS JSON
        */
                
        $layoutDataItem.='{
        "item":"'.$num.'",
        "id":"'.$id_item.'", 
        "status":"'.$status_lyt.'",
        "nombre":"'.$nombrecompleto_item.'",  
        "documento":"'.$cedula_item.'",  
        "email":"'.$email_item.'",
        "tel1":"'.$tel1_item.'",
        "tel2":"'.$tel2_item.'",
        "direccion":"'.$direccion_full.'",
        "fecharegistro":"'.$fecharegistro_item.'",
        "actions":"'.$detallesBTN.'"
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
    //echo json_encode( $data );
    
    $layoutDataItem.='{
    "item":"0",
    "id":"0",
    "status":"-",
    "nombre":"-",  
    "documento":"-",  
    "email":"-",
    "tel1":"-",
    "tel2":"-",
    "direccion":"-",
    "fecharegistro":"-",
    "actions":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
    
    
}


?>