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

$qDateStart = "0000-00-00"; //"2017-01-01";//"0000-00-00"; 
$qDateEnd = "0000-00-00"; //"2017-07-04";//"0000-00-00"; 
$qstatus = "";
if(isset($_POST['qstart'])){ 
    $qDateStart = (string)$_POST['qstart']; 
    $qDateEnd = (string)$_POST['qend']; 
    $qstatus = (empty($_POST['qstatus'])) ? "" : (int)$_POST['qstatus']; 
    $qDateStart = $db->escape($qDateStart); 
    $qDateEnd = $db->escape($qDateEnd);    
    $qstatus = $db->escape($qstatus);
}

if($qDateStart == "0000-00-00"){
    echo "{}";
    return false;
}

/*
*QUERY STATUS CREDITO
*/
function queryStatus($idStatusCredito_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_status', $idStatusCredito_);        
    $queryTbl = $db->getOne ("status_credito_tbl", "nombre_status");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        //foreach ($queryTbl as $qKey) {                        
            $dataQuery = $queryTbl["nombre_status"];
        //}    
        
    }
    return $dataQuery;
}

/*
*QUERY COBRADORES
*/
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
*SOBRE LAS CUOTAS DEL CREDITO
*/
function queryCuotasSeguimiento($refcredito_, $qDateStart_, $qDateEnd_ = null){
    global $db;
    $datasQuery = array();
                 
    //$db->where('fecha_max_recaudo', $date_, "<=");
    //$db->where('id_status_recaudo', "1", "!=");
    
    if($qDateStart_ != $qDateEnd_){        
        $db->where('fecha_max_recaudo', array($qDateStart_, $qDateEnd_), 'BETWEEN');    
    }else{
        $db->where('fecha_max_recaudo', $qDateStart_, "<=");    
    }    
    $db->where('ref_recaudo', $refcredito_);    
    
    $queryTbl = $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, id_status_recaudo, ref_recaudo, numero_cuota_recaudos, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, activa_mora, valor_mora_cuota_recaudo, fecha_max_recaudo");/*, MAX(fecha_max_recaudo) as fechaultimacuota*/
    
    if(count($queryTbl) > 0){
        foreach($queryTbl as $qKey){
            $datasQuery[] = $qKey;
        }
    }
    return $datasQuery;
}

/*
*SOBRE EL PLAN DE PAGOS
*/
function queryFechaFinCredito($id_credito_){
    global $db;  
    $dataQuery = array();
        
    $db->where('id_credito', $id_credito_);    
    $queryTbl = $db->getOne("planes_pago_tbl", "fecha_fin_plan_pago");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){            
        //foreach ($queryTbl as $qKey) {                        
            //$dataQuery[] = $qKey;
            $dataQuery = $queryTbl['fecha_fin_plan_pago'];             
            /*$dataQuery = array(
                "fechafincredito" =>$qKey['fecha_fin_plan_pago']
            );*/
        //}    
        
    }
    return $dataQuery;
}

/*
*SOBRE EL CREDITO
*/
function queryCreditos($idAcreedor_, $qDateStart_, $qDateEnd_, $qstatus_ = null){
    global $db;
    $creditosArray = array();    
    $querySeguimientoCredito = array();
            
    if($qstatus_ != null){
        $db->where('id_status_credito', $qstatus_);    
    }    
    
    $db->where('id_acreedor', $idAcreedor_);
    
    $querySeguimientoCredito=$db->get("creditos_tbl", null, "id_creditos, id_deudor, id_cobrador, code_consecutivo_credito, id_status_credito");
    //print_r($querySeguimientoCredito);
    if(is_array($querySeguimientoCredito) && !empty($querySeguimientoCredito)){
        foreach($querySeguimientoCredito as $qscKey){ 
            $id_credito = $qscKey['id_creditos']; 
            $id_deudor = $qscKey['id_deudor']; 
            $id_cobrador = $qscKey['id_cobrador']; 
            $id_status_credito = $qscKey['id_status_credito'];             
            $consecutivo_credito = $qscKey['code_consecutivo_credito']; 

            $datasCuotas = queryCuotasSeguimiento($consecutivo_credito, $qDateStart_, $qDateEnd_);
            $fecha_fin_credito = queryFechaFinCredito($id_credito);
            $datasdeudor = queryDeudores($id_deudor);
            $datascobrador = queryCobradores($id_cobrador);
            $status_credito = queryStatus($id_status_credito);
                        
            $creditosArray[] = array(
                "refcredito" => $consecutivo_credito,
                "fechafincredito" => $fecha_fin_credito,
                "statuscredito" => $status_credito,
                "cobradorcredito" => $datascobrador,
                "deudorcredito" => $datasdeudor,
                "cuotascredito" => $datasCuotas                
            );
        }
    }//[FIN|$querySeguimientoCredito]
    
    return $creditosArray;
}


/*
======================================================
*/


$datas_array = array();
$datas_array = queryCreditos($idSSUser, $qDateStart, $qDateEnd, $qstatus);
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

if(is_array($datas_array) && !empty($datas_array)){
    foreach($datas_array as $daKey){
         
        //datos cuota
        $refcredito = isset($daKey['refcredito'])? $db->escape($daKey['refcredito']) : "";
        $fechaterminacredito = isset($daKey['fechafincredito'])? $db->escape($daKey['fechafincredito']) : "";        
        $statuscredito = isset($daKey['statuscredito'])? $db->escape($daKey['statuscredito']) : "";
        $cobradorcredito = isset($daKey['cobradorcredito'])? $db->escape($daKey['cobradorcredito']) : "";                
        $datasdeudor = $daKey['deudorcredito'];
        $datascuotas = $daKey['cuotascredito'];
                
        //datos deudor
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
        
                
        $ultimacuotaarr = array();
        $real_valor_credito = 0;
        $real_valor_pagado_credito = 0;
        $real_numero_cuotas_debe = 0;
        $real_deuda_actual = 0;
        $real_max_acumlado_cuotas = 0; 
        $real_valor_acumulado_debe = 0;
        $numerodias = 0;
        $real_valor_cuota = 0;

        /*
        *cuotas credito
        */
        if(is_array($datascuotas) && !empty($datascuotas)){
            foreach($datascuotas as $dcsKey){
                $real_cuota_mora_activa = $dcsKey['activa_mora'];
                $real_valor_mora = $dcsKey['valor_mora_cuota_recaudo'];
                $real_valor_cuota = $dcsKey['total_cuota_plan_pago'];
                $real_valor_recaudado = $dcsKey['total_valor_recaudado_estacuota'];
                $real_fecha_cobro = $dcsKey['fecha_max_recaudo'];
                //$real_fecha_ultimacuota = $dcsKey['fechaultimacuota'];
                //$real_numero_cuotas_plan = $qscKey['numero_cuota_recaudos'];

                //defino las cuotas que se les hayan aplicado mora
                $real_aplica_mora = ($real_cuota_mora_activa == 1)? $real_valor_mora : 0;

                //calcula el valor recaudado
                //$real_valor_pagado_credito = $real_valor_pagado_credito + $real_valor_recaudado;

                //calcula el valor final real del credito
                //$real_valor_credito = $real_valor_credito + ($real_valor_cuota + $real_aplica_mora);   

                //calcula el valor acumulado que debe
                $real_valor_acumulado_debe = $real_valor_acumulado_debe + ($real_valor_cuota + $real_aplica_mora) - $real_valor_recaudado;
            }
        }//[FIN|$datasCuotas]

        
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
        $fecha_termina_human = date("d/m/Y",strtotime($fechaterminacredito));        
        $nombre_deudor = $nombrededuor." ".$apellidodeduor;        
        $real_valor_acumulado_debe_format = number_format($real_valor_acumulado_debe,0,".",","); 
        $valor_acumulado_lyt = "$".$real_valor_acumulado_debe_format;//"<p><span class='margin-right-xs'>$</span>".$real_valor_acumulado_debe_format."</p>"; 
        $status_lyt = "<span class='badge'>".$statuscredito."</span>"; 
                        
                                                
        /*
        *VALORES DATAS JSON
        */
                
        $layoutDataItem.='{
        "item":"'.$num.'",
        "refcredito":"'.$refcredito.'",
        "valordebe":"'.$real_valor_acumulado_debe.'",
        "cobrador":"'.$cobradorcredito.'",
        "nombre":"'.$nombre_deudor.'",        
        "tel":"'.$telefonodeudor.'",
        "barrio":"'.$barriodeduor.'",
        "dir":"'.$drecciondeduor.'",
        "fechatermina":"'.$fecha_termina_human.'",
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
    "refcredito":"-",
    "valordebe":"-",
    "cobrador":"-",
    "nombre":"-",
    "tel":"-",
    "barrio":"-",
    "dir":"-",
    "fechatermina":"-",
    "status":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>