<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php //require_once '../../appweb/inc/session-traslados.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php //require_once '../../appweb/inc/query-productos-catalogo.php'; ?>
<?php //require_once 'ssp.class.php'; ?>
<?php 


/*//-DEFINE ITEM-//*/
$itemID = "null";

if(isset($_POST['qvar'])){ $itemID = $_POST['qvar']; }


/*
*QUERY RECAUDOS
*/
function queryRecaudos($concecutivo_){
    global $db;  
    $dataQuery = array();
    
    $db->where('ref_recaudo', $concecutivo_);
    $db->orderBy("fecha_max_recaudo", "asc");
    $queryTbl = $db->get ("recaudos_tbl", null, "id_recaudo, id_status_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, comentarios_recaudo, activa_mora");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}

$recaudos_array = array();
$recaudos_array = queryRecaudos($itemID);


/*
 *==================================
 *CREA LAYOUT RECAUDOS
 *==================================
*/

$layoutDataItem = "";
$totalRegistros = count($recaudos_array);
$num = 0;

if(is_array($recaudos_array) && !empty($recaudos_array)){
    
    foreach($recaudos_array as $drKey){    
        $cuota_id = $db->escape($drKey['id_recaudo']);
        $cuota_activamora = $db->escape($drKey['activa_mora']);
        $cuota_status = $db->escape($drKey['id_status_recaudo']);
        $cuota_numero = $db->escape($drKey['numero_cuota_recaudos']);
        $cuota_capital = number_format($drKey['capital_cuota_recaudo'], 0, ',', '.');
        //$cuota_interes = $db->escape($drKey['interes_cuota_recaudo']);
        $cuota_mora = number_format($drKey['valor_mora_cuota_recaudo'], 0, ',', '.');      
        //$cuota_sobrecargo = $db->escape($drKey['sobrecargo_cuota_recaudo']);
        $cuota_valor_cuota = number_format($drKey['total_cuota_plan_pago'], 0, ',', '.');
        $cuota_valor_recaudado = number_format($drKey['total_valor_recaudado_estacuota'], 0, ',', '.');
        $cuota_valor_faltante = number_format($drKey['valor_faltante_cuota'], 0, ',', '.');
        $cuota_valor_recaulculado = number_format($drKey['valor_cuota_recaulcaldo_recaudos'], 0, ',', '.');
        $cuota_fecha_recaudo = date("d/m/Y",strtotime($drKey['fecha_max_recaudo']));
        $cuota_fecha_recaudo_realizado = $db->escape($drKey['fecha_recaudo_realizado']);//date("d/m/Y",strtotime($drKey['fecha_recaudo_realizado']));
        $cuota_comentarios = $db->escape($drKey['comentarios_recaudo']); 
        
        //FECHA PAGO CUOTA RECIBIDO
        $fecha_pago_recibido = "" ;
        if($cuota_fecha_recaudo_realizado == "0000-00-00"){
            $fecha_pago_recibido = "-";
        }else{
            $fecha_pago_recibido = date("d/m/Y",strtotime($cuota_fecha_recaudo_realizado));
        }
       

        //RECALCULO PROXIMA CUOTA
        $valor_cuota_final = 0;
        if($cuota_valor_recaulculado != 0){
            $valor_cuota_final = "<span class='margin-right-xs'>$</span>" .$cuota_valor_recaulculado;    
        }else{
            $valor_cuota_final = "<span class='margin-right-xs'>$</span>" .$cuota_valor_cuota; 
        }
        
        
        //DEFINE STATUS CUOTA
        $status_lyt = "";
        switch($cuota_status){
            case "1":
                $status_lyt = "<span class='badge bg-green padd-hori-xs text-size-x2'>Pagado</span>";
            break;
            case "2":
                $status_lyt ="<span class='badge bg-orange padd-hori-xs text-size-x2'>Abono</span>";
            break;
            case "3":
                $status_lyt ="<span class='badge bg-gray padd-hori-xs text-size-x2'>Por cobrar</span>";
            break;
        }
        
        //DEFINE STATUS MORA
        $mora_lyt = "";
        switch($cuota_activamora){
            case "1":
                $mora_lyt ="<span class='badge bg-green padd-hori-xs text-size-x2'>On</span>";
            break;            
            case "0":
                $mora_lyt ="<span class='badge bg-gray padd-hori-xs text-size-x2'>Off</span>";
            break;
        }
        
                                
        /*
        *VALORES DATAS JSON
        */
        /*$layoutDataItem.='{        
        "numecuota":"'.$cuota_numero.'",          
        "cuotafinal":"'.$valor_cuota_final.'",        
        "cuotafecha":"'.$cuota_fecha_recaudo.'",
        "cuotastatus":"'.$status_lyt.'"
        },';*/
        
        $layoutDataItem.='{
        "item":"'.$num.'",
        "id":"'.$cuota_id.'",
        "numecuota":"'.$cuota_numero.'",  
        "cuotacapital":"'.$cuota_capital.'",
        "cuotamora":"'.$cuota_mora.'",
        "cuotafinal":"'.$valor_cuota_final.'",
        "cuotarecaudado":"'.$cuota_valor_recaudado.'",
        "cuotafaltante":"'.$cuota_valor_faltante.'",
        "cuotafecha":"'.$cuota_fecha_recaudo.'",        
        "fechapago":"'.$fecha_pago_recibido.'",
        "cuotacoment":"'.$cuota_comentarios.'",
        "statusmora":"'.$mora_lyt.'",
        "cuotastatus":"'.$status_lyt.'"
        },';
        
        $num++;
    }
    /*-/FIN FOREACH RECAUDOS/-*/

        
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);
    
    echo '{"draw": 1,
    "recordsTotal": '.$totalRegistros.',
    "recordsFiltered": '.$totalRegistros.',
    "data":['.$layoutDataItem.']}';    
    

    
}else{
    //$data['error'] = "ERROR: No se encontraron registros para este credito. Por favor verificalo he intentalo de nuevo";
    //echo json_encode( $data );
    
    $layoutDataItem.='{
    "item":"0",
    "id":"0",
    "numecuota":"-",  
    "cuotacapital":"-",
    "cuotamora":"-",
    "cuotafinal":"-",
    "cuotarecaudado":"-",
    "cuotafaltante":"-",
    "cuotafecha":"-",        
    "fechapago":"-",
    "cuotacoment":"-",
    "statusmora":"-",
    "cuotastatus":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>