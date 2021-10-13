<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-credits.php'; ?>
<?php //require_once 'ssp.class.php'; ?>
<?php 
/*
*QUERY CREDITOS
*/
//recibe datos de PEDIDOS
$idAcreedor = $idSSUser;
$datas_array = array();
$datas_array = queryCreditos($idAcreedor);
//$dataCredits = unique_multidim_array($dataCredits, 'cod_orden_compra');
//echo "<pre>";
//print_r($dataCredits);


/*

function queryDeudor($idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    //$db->where('id_acreedor', $idAcreedor_);        
    $db->where('id_status_perfil_deduor', "5", "!=");        
    $queryTbl = $db->get ("deudor_tbl", null, "id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, ciudad_domicilio_deudor, estado_domicilio_deudor, dir_geo_deudor, cedula_deudor, email_deudor, tel_uno_deudor, tel_dos_deudor, fecha_alta_deudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $daKey) {                        
            $dataQuery[] = $daKey;
        }    
        return $dataQuery;
    }
}

$datas_array = array();
$datas_array = queryDeudor($idSSUser);*/


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
        
        $idItem = $db->escape($daKey['idcredito']);
        $consecutivoCredito = $db->escape($daKey['consecutivo']);
        $dataPlanPago = $daKey['dataplanpago'];
        $dataDeudor = $daKey['datadeudor'];
        $dataCobrador = $daKey['datacobrador'];
        $dataStatus = $daKey['datastatus'];
        $dataTipoCredito = $daKey['datatipocredito'];

        //SOBRE EL PLAN DE PAGOS

        $montoCredito = "";
        $numeCuotasCredito = "";
        $plazoCredito = "";
        $fechaInicioCredito = "";
        $fechaFinCredito = "";
        $capitalCuotaCredito = "";
        $valorTotalCuota="";

        $fechaInicioFormat = "";
        $fechaFinFormat = "";
        $montoCreditoFormat = "";

        if(is_array($dataPlanPago) && !empty($dataPlanPago)){
            foreach($dataPlanPago as $dppKey){                                        
                $periocidadCredito = isset($dataPlanPago['periocidad_plan_pago'])? $db->escape($dataPlanPago['periocidad_plan_pago']) : "";
                $montoCredito = isset($dataPlanPago['valor_credito_plan_pago'])? $db->escape($dataPlanPago['valor_credito_plan_pago']) : "";                
                $numeCuotasCredito = isset($dataPlanPago['numero_cuotas_plan_pago'])? $db->escape($dataPlanPago['numero_cuotas_plan_pago']) : "";
                $plazoCredito = isset($dataPlanPago['plazocredito_plan_pago'])? $db->escape($dataPlanPago['plazocredito_plan_pago']) : "";                
                $fechaInicioCredito = isset($dataPlanPago['fecha_inicio_plan_pago'])? $db->escape($dataPlanPago['fecha_inicio_plan_pago']) : "";
                $fechaFinCredito = isset($dataPlanPago['fecha_fin_plan_pago'])? $db->escape($dataPlanPago['fecha_fin_plan_pago']) : "";
                $capitalCuotaCredito = isset($dataPlanPago['capital_cuota_plan_pago'])? $db->escape($dataPlanPago['capital_cuota_plan_pago']) : "";
                $valorTotalCuota = isset($dataPlanPago['valor_cuota_plan_pago'])? $db->escape($dataPlanPago['valor_cuota_plan_pago']) : "";

                //FORMAT
                $fechaInicioFormat = date("d/m/Y", strtotime($fechaInicioCredito));
                $fechaFinFormat = date("d/m/Y", strtotime($fechaFinCredito));
                $montoCreditoFormat = number_format($montoCredito,0,",","."); 
                $valorTotalCuotaFormat = number_format($valorTotalCuota,0,",","."); 
            }

        }

        //SOBRE EL DEUDOR
        $nombreDeudor = "";               
        $apellidoDeudor = "";
        $ciudadDeudor = "";
        $regionDeudor = "";
        $nombreFullDeudor = "";
        if(is_array($dataDeudor) && !empty($dataDeudor)){
            foreach($dataDeudor as $ddKey){                                        
                $nombreDeudor = isset($dataDeudor['primer_nombre_deudor'])? $db->escape($dataDeudor['primer_nombre_deudor']) : "";                               
                $apellidoDeudor = isset($dataDeudor['primer_apellido_deudor'])? $db->escape($dataDeudor['primer_apellido_deudor']) : "";                
                $ciudadDeudor = isset($dataDeudor['ciudad_domicilio_deudor'])? $db->escape($dataDeudor['ciudad_domicilio_deudor']) : "";                
                $regionDeudor = isset($dataDeudor['estado_domicilio_deudor'])? $db->escape($dataDeudor['estado_domicilio_deudor']) : "";
            }

            $nombreFullDeudor = $nombreDeudor." ".$apellidoDeudor;
        }

        //SOBRE EL COBRADOR
        $nombreCobrador = "";
        if(is_array($dataCobrador) && !empty($dataCobrador)){
            foreach($dataCobrador as $dcKey){                                        
                $nombreCobrador = isset($dataCobrador['nombre_cobrador'])? $db->escape($dataCobrador['nombre_cobrador']) : "";
            }
        }

        //STATUS CREDITO
        $statusItem = "";
        if(is_array($dataStatus) && !empty($dataStatus)){
            foreach($dataStatus as $dsKey){                                        
                $statusItem = isset($dataStatus['nombre_status'])? $db->escape($dataStatus['nombre_status']) : "";
            }
        }

        //TIPO CREDITO
        $tipoCredito = "";
        if(is_array($dataTipoCredito) && !empty($dataTipoCredito)){
            foreach($dataTipoCredito as $dtcKey){  
                $tipoCredito = isset($dataTipoCredito['nombre_tipo_credito'])? $db->escape($dataTipoCredito['nombre_tipo_credito']) : "";
            }
        }
        
        //LAYOUT DINERO PRESTADO
        $dineroprestado_lyt ="<p><span class='margin-right-xs'>$</span>".$montoCreditoFormat."</p>";
        
        //LAYOUT VALOR CUOTA
        $valorcuota_lyt ="<p><span class='margin-right-xs'>$</span>".$valorTotalCuotaFormat."</p>";
        
        //LAYOUT STATUS
        $status_lyt ="<span class='badge bg-gray padd-hori-xs text-size-x2'>".$statusItem."</span>";
        
                        
        /*
        *LAYOUT ACCIONES BTN
        */
                    
        /*
        *URLs
        */
        $pathDetalleCredito = $pathmm."/credits/details/";
        
        //$accionesBTN = "<div class='btn-group'>";
        
        //$accionesBTN .= "<button type='button' class='btn bg-green gocredits' data-deudorvar='".$id_item."'><i class='fa fa-archive padd-hori-xs'></i></button>";
        //$accionesBTN .="<script>$(document).ready(function(){ $('.gocredits').each(function(){ var itemid = $(this).attr('data-deudorvar'); $(this).click(function(){ $.redirect('".$pathNewCredito."',{ deudorcod: itemid}); }); }); });</script>";
        
        $accionesBTN = "<button type='button' class='btn btn-info godetails' data-id='".$idItem."' >Detalles<i class='fa fa-chevron-right margin-left-xs'></i></button>";
        $accionesBTN .="<script>$(document).ready(function(){ $('.godetails').each(function(){ var itemid = $(this).attr('data-id'); $(this).click(function(){ $.redirect('".$pathDetalleCredito."', { itemid_var: itemid }); }); }); });</script>";
        
        //$accionesBTN .="</div>";
                                        
        /*
        *VALORES DATAS JSON
        */
                
        $layoutDataItem.='{
        "item":"'.$num.'",
        "id":"'.$idItem.'",
        "refcredio":"'.$consecutivoCredito.'",  
        "deudor":"'.$nombreFullDeudor.'",  
        "valorprestado":"'.$dineroprestado_lyt.'",
        "numecuotas":"'.$numeCuotasCredito.'",
        "valorcuota":"'.$valorcuota_lyt.'",
        "fechainicio":"'.$fechaInicioFormat.'",
        "fechafin":"'.$fechaFinFormat.'",
        "status":"'.$status_lyt.'",
        "actions":"'.$accionesBTN.'"
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
    "item":"0",
    "id":"0",
    "refcredio":"-",  
    "deudor":"-",  
    "valorprestado":"-",
    "numecuotas":"-",
    "valorcuota":"-",
    "fechainicio":"-",
    "fechafin":"-",
    "status":"-",
    "actions":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>