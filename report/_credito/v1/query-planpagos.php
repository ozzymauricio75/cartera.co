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


$qUser = ""; 
if(isset($_POST['quser'])){ 
    $qUser = (string)$_POST['quser'];     
    $qUser = $db->escape($qUser);     
}


/*
*QUERY DEUDORES
*/
function queryDeudores($idDeudor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_deudor', $idDeudor_);    
    $queryTbl = $db->getOne("deudor_tbl", "cedula_deudor, primer_nombre_deudor, primer_apellido_deudor, tel_uno_deudor, tel_dos_deudor, barrio_domicilio_deudor, dir_geo_deudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){            
        //foreach ($queryTbl as $qKey) {                        
            $dataQuery = $queryTbl;
        //}    
        
    }
    return $dataQuery;
}

/*
*QUERY PLAN PAGO
*/
function queryPlanPago($idcredito_){
    global $db;  
    $dataQuery ="";// array();
    
    $db->where('id_credito', $idcredito_);    
    $queryTbl = $db->getOne("planes_pago_tbl", "valor_credito_plan_pago, valor_pagar_credito, periocidad_plan_pago, numero_cuotas_plan_pago, plazocredito_plan_pago, fecha_inicio_plan_pago, fecha_fin_plan_pago, valor_cuota_plan_pago");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){           
        $dataQuery = $queryTbl;
    }
    return $dataQuery;
}


/*
*QUERY RECAUDO
*/
function queryRecaudos($refcredito_){
    global $db;  
    $dataQuery = array();
            
    $db->where('ref_recaudo', $refcredito_);        
    $queryTbl = $db->get ("recaudos_tbl", null, "ref_recaudo, id_status_recaudo, numero_cuota_recaudos, activa_mora, total_cuota_plan_pago, valor_mora_cuota_recaudo, total_valor_recaudado_estacuota, valor_faltante_cuota, fecha_max_recaudo, fecha_recaudo_realizado");
            
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                                                                                            
            $dataQuery[] = $qKey;
        }           
    }
    return $dataQuery;
}


/*
*QUERY CREDITO
*/
function queryCredito($idSSUser_, $qUser_){
    global $db;  
    $dataQuery = array();
    $datasPlanPago = array();
    $datasRecaudos = array();
    $datasDeudor = array();
    
    $id_user = "";
    $ref_credito = "";
    
    //consultar usuario / credito        
    $db->where('id_acreedor', $idSSUser_);        
    $db->where('cedula_deudor', $qUser_);    
    $queryUser = $db->getOne("deudor_tbl", "id_deudor");
        
    if(count($queryUser) > 0){
        $id_user = $queryUser["id_deudor"];                
    }else{
        $db->where('id_acreedor', $idSSUser_);        
        $db->where('code_consecutivo_credito', $qUser_);    
        $queryCredito = $db->getOne("creditos_tbl", "code_consecutivo_credito");    
        
        if(count($queryCredito)>0){
            $ref_credito = $queryCredito["code_consecutivo_credito"];
        }        
    }
    
    
    $db->where ("(id_deudor = ? or code_consecutivo_credito = ?)", array($id_user, $ref_credito));    
    $db->where('id_acreedor', $idSSUser_);        
    $queryTbl = $db->get("creditos_tbl", null, "id_deudor, id_creditos, code_consecutivo_credito");        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){            
        foreach ($queryTbl as $qKey) {                        
            $datasPlanPago = queryPlanPago($qKey["id_creditos"]);
            $datasRecaudos = queryRecaudos($qKey["code_consecutivo_credito"]);
            $datasDeudor = queryDeudores($qKey["id_deudor"]);
            $dataQuery[] = array(
                "datasdeudor" => $datasDeudor,
                "datasplanpago" => $datasPlanPago,
                "datasrecaudos" => $datasRecaudos
            );
        }
        
    }
    return $dataQuery;
}

$datas_array = array();
$datas_array = queryCredito($idSSUser, $qUser);

/*echo "<pre>";
print_r($datas_array);
echo "</pre>";*/


/*
 *==================================
 *CREA LAYOUT DATAS
 *==================================
*/

$layoutDataItem = "";
$totalRegistros = count($datas_array);
$num = 0;
$prevRefCredito = "";
 
if(is_array($datas_array) && !empty($datas_array)){
    foreach($datas_array as $daKey){
        //datos deudor
        $datasdeudor = $daKey['datasdeudor'];
        //datas recaudos
        $datasrecaudos = $daKey['datasrecaudos'];
        //plan de pagos
        $datasplanepago = $daKey['datasplanpago'];


        $valor_cuota = 0;
        $total_cuota = 0;                                                               
                            
        //SOBRE EL DEUDOR    
        if(is_array($datasdeudor) && !empty($datasdeudor)){
            foreach($datasdeudor as $ddKey){
                $ceduladeduor = isset($datasdeudor['cedula_deudor'])? $db->escape($datasdeudor['cedula_deudor']) : "";        
                $nombrededuor = isset($datasdeudor['primer_nombre_deudor'])? $db->escape($datasdeudor['primer_nombre_deudor']) : "";        
                $apellidodeduor = isset($datasdeudor['primer_apellido_deudor'])? $db->escape($datasdeudor['primer_apellido_deudor']) : "";
                $tel1deduor = isset($datasdeudor['tel_uno_deudor'])? $db->escape($datasdeudor['tel_uno_deudor']) : "";
                $tel2deduor = isset($datasdeudor['tel_dos_deudor'])? $db->escape($datasdeudor['tel_dos_deudor']) : "";
                $barriodeduor = isset($datasdeudor['barrio_domicilio_deudor'])? $db->escape($datasdeudor['barrio_domicilio_deudor']) : "";
                $drecciondeduor = isset($datasdeudor['dir_geo_deudor'])? $db->escape($datasdeudor['dir_geo_deudor']) : "";

            }
        }
        
        //PLAN PAGOS
        if(is_array($datasplanepago) && !empty($datasplanepago)){
            foreach($datasplanepago as $dppKey){
            //valor_credito_plan_pago, valor_pagar_credito, periocidad_plan_pago, numero_cuotas_plan_pago, plazocredito_plan_pago, fecha_inicio_plan_pago, fecha_fin_plan_pago, valor_cuota_plan_pago
                $valor_credito = $datasplanepago['valor_credito_plan_pago'];
                $valor_a_pagar = $datasplanepago['valor_pagar_credito'];
                $periciodad_credito = $datasplanepago['periocidad_plan_pago'];
                $numero_cuotas_credito = $datasplanepago['numero_cuotas_plan_pago'];
                $plazo_credito = $datasplanepago['plazocredito_plan_pago'];
                $fecha_inicio_credito = $datasplanepago['fecha_inicio_plan_pago'];
                $fecha_fin_credito = $datasplanepago['fecha_fin_plan_pago'];
                $valor_cuota_credito = $datasplanepago['valor_cuota_plan_pago'];
            }
        }
        
        //SOBRE LAS CUOTAS
        if(is_array($datasrecaudos) && !empty($datasrecaudos)){
            foreach($datasrecaudos as $dcKey){              
                $refcredito = isset($dcKey["ref_recaudo"])? $db->escape($dcKey["ref_recaudo"]) : "";
                $status_cuota = isset($dcKey["id_status_recaudo"])? $db->escape($dcKey["id_status_recaudo"]) : "";
                $numero_cuota = isset($dcKey["numero_cuota_recaudos"])? $db->escape($dcKey["numero_cuota_recaudos"]) : "";
                $activamora = isset($dcKey["activa_mora"])? $db->escape($dcKey["activa_mora"]) : "";
                $valor_cuota = isset($dcKey["total_cuota_plan_pago"])? $db->escape($dcKey["total_cuota_plan_pago"]) : "";
                $valor_mora_cuota = isset($dcKey["valor_mora_cuota_recaudo"])? $db->escape($dcKey["valor_mora_cuota_recaudo"]) : "";
                $valor_recaudado_cuota = isset($dcKey["total_valor_recaudado_estacuota"])? $db->escape($dcKey["total_valor_recaudado_estacuota"]) : "";
                $valor_faltante_cuota = isset($dcKey["valor_faltante_cuota"])? $db->escape($dcKey["valor_faltante_cuota"]) : "";
                $fecha_cobro_cuota = ($dcKey["fecha_max_recaudo"] == "0000-00-00")? "" : $db->escape($dcKey["fecha_max_recaudo"]);
                $fecha_recaudo_cuota = ($dcKey["fecha_recaudo_realizado"] == "0000-00-00")? "" : $db->escape($dcKey["fecha_recaudo_realizado"]); 
                                
                
                //DEFINE STATUS CUOTA
                $status_lyt = "";
                switch($status_cuota){
                    case "1":
                        $status_lyt = "Pagado";
                    break;
                    case "2":
                        $status_lyt ="Abono";
                    break;
                    case "3":
                        $status_lyt ="Por cobrar";
                    break;
                }

                //DEFINE STATUS MORA
                $mora_lyt = "";        
                switch($activamora){
                    case "1":
                        $mora_lyt ="Aplico";                
                    break;            
                    case "0":
                        $mora_lyt ="Sin aplicar";
                    break;
                }

                $valor_mora_esta_cuota = ($activamora == 1) ? $valor_mora_cuota : 0;
                
                $valor_final_cuota = $valor_cuota + $valor_mora_esta_cuota;

                //FORMATO DATOS

                //define telefono
                $telefonodeudor = "";
                if($tel1deduor != "" && $tel2deduor != ""){
                    $telefonodeudor = $tel1deduor." / ".$tel2deduor;
                }else if($tel1deduor != "" && $tel2deduor == ""){
                    $telefonodeudor = $tel1deduor;
                }else if($tel1deduor == "" && $tel2deduor != ""){
                    $telefonodeudor = $tel2deduor;
                }
                
                //formato datos
                $fecha_cobrar_human = date("d/m/Y",strtotime($fecha_cobro_cuota));        
                $fecha_recaudo_human = ($fecha_recaudo_cuota != "") ? date("d/m/Y",strtotime($fecha_recaudo_cuota)) : "-";        
                $nombre_deudor = $nombrededuor." ".$apellidodeduor;        
                

                /*
                *VALORES DATAS JSON
                */

                $layoutDataItem.='{
                "item":"'.$num.'",
                "fechacobro":"'.$fecha_cobrar_human.'",  
                "refcredito":"'.$refcredito.'",                
                "cuota":"'.$numero_cuota.'",                
                "valorbasecuota":"'.$valor_cuota.'",  
                "valormoracuota":"'.$valor_mora_cuota.'",  
                "valorpagar":"'.$valor_final_cuota.'",
                "fechapago":"'.$fecha_recaudo_human.'",
                "valorrecaudado":"'.$valor_recaudado_cuota.'",
                "valorfaltante":"'.$valor_faltante_cuota.'",
                "nombre":"'.$nombre_deudor.'",
                "cedula":"'.$ceduladeduor.'",                
                "status":"'.$status_lyt.'",
                "mora":"'.$mora_lyt.'"
                },';

                $num++;                                                                                
            }            
        }
        
        
        
                                                        
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
    "fechacobro":"-",
    "refcredito":"-",
    "cuota":"-",
    "valorbasecuota":"-",  
    "valormoracuota":"-",  
    "valorpagar":"-",
    "fechapago":"-",
    "valorrecaudado":"-",
    "valorfaltante":"-",
    "nombre":"",
    "cedula":"",
    "status":"",
    "mora":""
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>