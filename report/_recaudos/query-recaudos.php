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

$qDateStart = "0000-00-00"; //"2017-07-04";//"0000-00-00"; 
$qDateEnd = "0000-00-00"; //"2017-07-04";//"0000-00-00"; 
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
function queryCobradores($qCobrador_){
    global $db;  
    $dataQuery = "";//array();
    //$datasRecaudos = array();
        
    $db->where('id_cobrador', $qCobrador_);    
    $queryTbl = $db->getOne("cobrador_tbl", "nombre_cobrador");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){    
        $dataQuery = $queryTbl['nombre_cobrador'];
        /*foreach ($queryTbl as $qKey) {                        
            //$dataQuery[] = $qKey;
            $datasRecaudos = queryRecaudos($idSSUser_, $qDateStart_, $qDateEnd_, $qCobrador_);
            $dataQuery[] = array(
                "idcobrador" => $qKey['id_cobrador'],
                "nombrecobrador" => $qKey['nombre_cobrador'],
                "datascuotas" => $datasRecaudos
            );
        }*/
        
    }
    return $dataQuery;
}
/*
*QUERY DEUDORES
*/
function queryDeudores($idDeudor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_deudor', $idDeudor_);    
    $queryTbl = $db->get("deudor_tbl", 1, "primer_nombre_deudor, primer_apellido_deudor, tel_uno_deudor, tel_dos_deudor, barrio_domicilio_deudor, dir_geo_deudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){            
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}

/*
*QUERY PLAN PAGO
*/
function queryPlanPago($idPlanPago_){
    global $db;  
    $dataQuery ="";// array();
    //$dataDeudor = array();
    
    $db->where('id_plan_pago', $idPlanPago_);    
    $queryTbl = $db->getOne("planes_pago_tbl", "id_deudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){            
        //foreach ($queryTbl as $qKey) {                        
            //$dataQuery[] = $qKey;
            //$dataDeudor = queryDeudores($qKey['id_deudor']);
            
            /*$dataQuery = array(
                "datadeudor" =>$dataDeudor
            );*/
        //}    
        $dataQuery = $queryTbl['id_deudor'];
    }
    return $dataQuery;
}


/*
*QUERY RECAUDO
*/
function queryRecaudos($idAcreedor_, $qDateStart_, $qDateEnd_ = null, $qCobrador_ = null){
    global $db;  
    $dataQuery = array();
    $dataDeudor = array();
    $dataCobrador = array();
    
    if($qDateStart_ != $qDateEnd_){        
        $db->where('fecha_max_recaudo', array($qDateStart_, $qDateEnd_), 'BETWEEN');    
    }else{
        $db->where('fecha_max_recaudo', $qDateStart_, "<=");    
    }    
    if($qCobrador_ != null){
        $db->where('id_cobrador', $qCobrador_);    
    }    
    $db->where('id_status_recaudo', "1", "!=");
    $db->where('id_acreedor', $idAcreedor_);
    $db->orderBy('id_recaudo', "desc");
    
    $queryTbl = $db->get ("recaudos_tbl", null, "ref_recaudo, id_plan_pago, id_cobrador, numero_cuota_recaudos, fecha_max_recaudo");
            
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {        
                    
            //$dataQuery[] = $qKey;                 
            $dataDeudor = queryPlanPago($qKey['id_plan_pago']);    
            $dataCobrador = queryCobradores($qKey['id_cobrador']);    
                                                
            $dataQuery[] = array(
                "refcredito" => $qKey['ref_recaudo'],
                "numecuota" => $qKey['numero_cuota_recaudos'],                
                "fechacobrar" => $qKey['fecha_max_recaudo'],
                "deudor" => $dataDeudor,
                "idcobrador" =>$qKey['id_cobrador'],
                "cobrador" => $dataCobrador,
                
            );
        }           
    }
    return $dataQuery;
}


/*
*QUERY CUOTAS MORA
*/
function queryRecaudosMora($refrecaudo_, $qDateStart_, $qDateEnd_ = null){
    global $db;  
    $dataQuery = array();
    $valorMora = 0;    
    
    if($qDateStart_ != $qDateEnd_){        
        $db->where('fecha_max_recaudo', array($qDateStart_, $qDateEnd_), 'BETWEEN');    
    }else{
        $db->where('fecha_max_recaudo', $qDateStart_, "<=");    
    } 
    $db->where('activa_mora', "1");
    $db->where('id_status_recaudo', "1", "!=");
    $db->where('ref_recaudo', $refrecaudo_);
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(valor_mora_cuota_recaudo) as valormora");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){                                            
        
        $valorMora = $queryTbl['valormora'];    
    }
    return $valorMora;
}

/*
*QUERY ACUMULADO SIN MORA
*/
function queryAcumuladoRecaudos($refrecaudo_, $qDateStart_, $qDateEnd_ = null){
    global $db;  
    $dataQuery = array();
    $valorCuota = 0;    
    $valorRecaudado = 0;    
    $valorAcumulado = 0;
    
    if($qDateStart_ != $qDateEnd_){        
        $db->where('fecha_max_recaudo', array($qDateStart_, $qDateEnd_), 'BETWEEN');    
    }else{
        $db->where('fecha_max_recaudo', $qDateStart_, "<=");    
    } 
    $db->where('ref_recaudo', $refrecaudo_);
    $db->where('id_status_recaudo', "1", "!=");
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_cuota_plan_pago) as valorcuota, SUM(total_valor_recaudado_estacuota) as valorrecaudado");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){                                            
        
        $valorCuota = $queryTbl['valorcuota'];    
        $valorRecaudado = $queryTbl['valorrecaudado'];    
        $valorAcumulado = $valorCuota - $valorRecaudado;
    }
    return $valorAcumulado;
}


$datas_array = array();
$datas_array = queryRecaudos($idSSUser, $qDateStart, $qDateEnd, $qCobrador);
//queryCobradores($idSSUser, $qDateStart, $qDateEnd, $qCobrador);
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
        //datas array
        $idcobrador = isset($daKey['idcobrador'])? $db->escape($daKey['idcobrador']) : "";
        $nombrecobrador = isset($daKey['cobrador'])? $db->escape($daKey['cobrador']) : "";
        $refcredito = isset($daKey['refcredito'])? $db->escape($daKey['refcredito']) : "";
        $numerocuota = isset($daKey['numecuota'])? $db->escape($daKey['numecuota']) : ""; 
        /*$activamora = isset($daKey['actimora'])? $db->escape($daKey['actimora']) : ""; 
        $valormora = isset($daKey['valormora'])? $db->escape($daKey['valormora']) : "";         
        $valorbasecuota = isset($daKey['valorbasecuota'])? $db->escape($daKey['valorbasecuota']) : "";        
        $valorrecaudadocuota = isset($daKey['valorrecaudado'])? $db->escape($daKey['valorrecaudado']) : "";        
        $valorfaltante = isset($daKey['valorfaltante'])? $db->escape($daKey['valorfaltante']) : "";*/
        $fechacobrar = isset($daKey['fechacobrar'])? $db->escape($daKey['fechacobrar']) : "";
        $iddeudor = isset($daKey['deudor'])? $db->escape($daKey['deudor']) : "";

        //datos deudor
        $datasdeudor = queryDeudores($iddeudor);
        
        //definir status credito
        $statusCredito = "";
        $idStatusCredito = array();
        $db->where("code_consecutivo_credito",$refcredito);
        $idStatusCredito = $db->getOne("creditos_tbl", "id_status_credito");
                
        if(count($idStatusCredito)>0){
            $statusCredito =  queryStatusdeCredito($idStatusCredito["id_status_credito"]);   
        }

        $valor_cuota = 0;
        $total_cuota = 0;                                                               
        
        if($prevRefCredito != $refcredito){
            
            
            if(is_array($datasdeudor) && !empty($datasdeudor)){
                foreach($datasdeudor as $ddKey){
                    $nombrededuor = isset($datasdeudor['primer_nombre_deudor'])? $db->escape($datasdeudor['primer_nombre_deudor']) : "";        
                    $apellidodeduor = isset($datasdeudor['primer_apellido_deudor'])? $db->escape($datasdeudor['primer_apellido_deudor']) : "";
                    $tel1deduor = isset($datasdeudor['tel_uno_deudor'])? $db->escape($datasdeudor['tel_uno_deudor']) : "";
                    $tel2deduor = isset($datasdeudor['tel_dos_deudor'])? $db->escape($datasdeudor['tel_dos_deudor']) : "";
                    $barriodeduor = isset($datasdeudor['barrio_domicilio_deudor'])? $db->escape($datasdeudor['barrio_domicilio_deudor']) : "";
                    $drecciondeduor = isset($datasdeudor['dir_geo_deudor'])? $db->escape($datasdeudor['dir_geo_deudor']) : "";

                }
            }

            //define telefono
            $telefonodeudor = "";
            if($tel1deduor != "" && $tel2deduor != ""){
                $telefonodeudor = $tel1deduor." / ".$tel2deduor;
            }else if($tel1deduor != "" && $tel2deduor == ""){
                $telefonodeudor = $tel1deduor;
            }else if($tel1deduor == "" && $tel2deduor != ""){
                $telefonodeudor = $tel2deduor;
            }


            //define si cuota tiene mora
            $cuota_con_mora = queryRecaudosMora($refcredito, $qDateStart, $qDateEnd);//($activamora == 1)? $valormora : 0;
            
            //define valor cuota
            $valor_cuota = queryAcumuladoRecaudos($refcredito, $qDateStart, $qDateEnd);//$valor_cuota + ($valorbasecuota + $cuota_con_mora) - $valorrecaudadocuota;
            
            $valor_cuota = $valor_cuota + $cuota_con_mora;

            $total_cuota++;
            
             
            //formato datos
            $fecha_cobrar_human = date("d/m/Y",strtotime($fechacobrar));        
            $nombre_deudor = $nombrededuor." ".$apellidodeduor;        
            $valor_cuota_format = number_format($valor_cuota,0,",","."); 
            //$valor_cuota_lyt = "<p><span class='margin-right-xs'>$</span>".$valor_cuota_format."</p>"; 
            $valor_cuota_lyt = "$ ".$valor_cuota_format; 
            
              
            /*
            *VALORES DATAS JSON
            */

            $layoutDataItem.='{
            "item":"'.$num.'",
            "refcredito":"'.$refcredito.'",
            "statuscredito":"'.$statusCredito.'",
            "cobrador":"'.$nombrecobrador.'",
            "cuota":"'.$numerocuota.'",
            "vence":"'.$fecha_cobrar_human.'",  
            "valorcuota":"'.$valor_cuota.'",  
            "nombre":"'.$nombre_deudor.'",
            "tel":"'.$telefonodeudor.'",
            "barrio":"'.$barriodeduor.'",
            "dir":"'.$drecciondeduor.'",
            "estado":"",
            "valor":""
            },';

            $num++;
            
            
            
            $prevRefCredito = $refcredito;
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
    "refcredito":"-",
    "statuscredito":"-",
    "cobrador":"-",
    "cuota":"-",
    "vence":"-",  
    "valorcuota":"-",  
    "nombre":"-",
    "tel":"-",
    "barrio":"-",
    "dir":"-",
    "estado":"",
    "valor":""
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>