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

/**QUERY COBRADORES*/
function queryCobradores($qCobrador_){
    global $db;
    $dataQuery = "";//array();
    //$datasRecaudos = array();

    $db->where('id_cobrador', $qCobrador_);
    $queryTbl = $db->getOne("cobrador_tbl", "nombre_cobrador");

    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        $dataQuery = $queryTbl['nombre_cobrador'];
    }
    return $dataQuery;
}

/**QUERY DEUDORES*/
function queryDeudores($idDeudor_){
    global $db;
    $dataQuery = array();

    $db->where('id_deudor', $idDeudor_);
    $queryTbl = $db->get("deudor_tbl", 1, "cedula_deudor, primer_nombre_deudor, primer_apellido_deudor");

    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        foreach ($queryTbl as $qKey) {
            $dataQuery = $qKey;
        }
        return $dataQuery;
    }
}

/**QUERY PLAN PAGO*/
function queryPlanPago($idPlanPago_){
    global $db;
    $dataQuery ="";// array();
    //$dataDeudor = array();

    $db->where('id_plan_pago', $idPlanPago_);
    $queryTbl = $db->getOne("planes_pago_tbl", "id_deudor");

    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        $dataQuery = $queryTbl['id_deudor'];
    }
    return $dataQuery;
}

/**QUERY CREDITOS*/
function queryCreditos($refcredito_){
    global $db;
    $dataQuery ="";

    $db->where('code_consecutivo_credito', $refcredito_);
    $queryTbl = $db->getOne("creditos_tbl", "id_deudor");

    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        $dataQuery = $queryTbl['id_deudor'];
    }
    return $dataQuery;
}

/**QUERY RECAUDO*/
function queryRecaudos($idAcreedor_, $qDateStart_, $qDateEnd_ = null, $qCobrador_ = null){
    global $db;
    $dataQuery = array();
    $dataDeudor = array();
    $dataCobrador = array();

    if($qDateStart_ != $qDateEnd_){
        $db->where('fecha_max_recaudo', array($qDateStart_, $qDateEnd_), 'BETWEEN');
    }else{
        $db->where('fecha_max_recaudo', $qDateStart_);
    }
    if($qCobrador_ != null){
        $db->where('id_cobrador', $qCobrador_);
    }

    $db->where('id_acreedor', $idAcreedor_);

    $queryTbl = $db->get ("recaudos_tbl", null, "ref_recaudo, id_plan_pago, id_cobrador, numero_cuota_recaudos, activa_mora, valor_mora_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, fecha_max_recaudo, fecha_recaudo_realizado");

    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        foreach ($queryTbl as $qKey) {

            $dataDeudor = queryPlanPago($qKey['id_plan_pago']);
            $dataCobrador = queryCobradores($qKey['id_cobrador']);

            $dataQuery[] = array(
                "refcredito" => $qKey['ref_recaudo'],
                "numecuota" => $qKey['numero_cuota_recaudos'],
                "activamoracuota" => $qKey['activa_mora'],
                "valormoracuota" => $qKey['valor_mora_cuota_recaudo'],
                "valorcuota" => $qKey['total_cuota_plan_pago'],
                "valorrecaudadocuota" => $qKey['total_valor_recaudado_estacuota'],
                "fechacobrar" => $qKey['fecha_max_recaudo'],
                "fecharecaudo" => $qKey['fecha_recaudo_realizado'],
                "deudor" => $dataDeudor,
                "idcobrador" =>$qKey['id_cobrador'],
                "cobrador" => $dataCobrador,

            );
        }
    }
    return $dataQuery;
}

/**QUERY CUOTAS MORA*/
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
    $db->where('ref_recaudo', $refrecaudo_);

    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(valor_mora_cuota_recaudo) as valormora");

    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){

        $valorMora = $queryTbl['valormora'];
    }
    return $valorMora;
}

/**QUERY ACUMULADO SIN MORA*/
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

    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_cuota_plan_pago) as valorcuota, SUM(total_valor_recaudado_estacuota) as valorrecaudado");

    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){

        $valorCuota = $queryTbl['valorcuota'];
        $valorRecaudado = $queryTbl['valorrecaudado'];
        $valorAcumulado = $valorCuota - $valorRecaudado;
    }
    return $valorAcumulado;
}

/**QUERY DINERO RECIBIDOS*/
function queryDineroRecibido($idAcreedor_, $qDateStart_, $qDateEnd_ = null, $qCobrador_ = null){
  global $db;
  $dataQuery = array();
  $dataDeudor = array();
  $dataCobrador = array();

  if($qDateStart_ != $qDateEnd_){
      $db->where('fecha_dinero_recibido', array($qDateStart_, $qDateEnd_), 'BETWEEN');
  }else{
      $db->where('fecha_dinero_recibido', $qDateStart_);
  }

  if($qCobrador_ != null){
      $db->where('id_cobrador', $qCobrador_);
  }

  $db->where('id_acreedor', $idAcreedor_);

  $queryTbl = $db->get("dinero_recibido_tbl", null,  "id_acreedor, id_plan_pago, id_cobrador, id_status_recibido, consecutivo_credito, numero_cuota_recaudos, metodo_pago_dinero_recibido, total_valor_recibido, fecha_dinero_recibido, comentarios_dinero_recibido");

  $rowQueryTbl = count($queryTbl);

  if ($rowQueryTbl > 0){
    foreach ($queryTbl as $qKey) {
      $dataDeudor   = queryCreditos($qKey['consecutivo_credito']);
      $dataCobrador = queryCobradores($qKey['id_cobrador']);

      $dataQuery[] = array(
        "refcredito"         => $qKey['consecutivo_credito'],
        "numecuota"          => $qKey['numero_cuota_recaudos'],
        "fecharecaudo"       => $qKey['fecha_dinero_recibido'],
        "deudor"             => $dataDeudor,
        "idcobrador"         => $qKey['id_cobrador'],
        "cobrador"           => $dataCobrador,
        "valortotalrecibido" => $qKey['total_valor_recibido'],

      );
    }
  }
  return $dataQuery;
}

function queryDineroRecibidoAbonos($refrecaudo_, $qDateStart_, $qDateEnd_){
    global $db;
    $dataQuery = array();
    $dineroRecibidoAbonos = 0;

    if($qDateStart_ != $qDateEnd_){
        $db->where('fecha_dinero_recibido', array($qDateStart_, $qDateEnd_), 'BETWEEN');
    }else{
        $db->where('fecha_dinero_recibido', $qDateStart_);
    }

    $db->where('consecutivo_credito', $refrecaudo_);
    $queryTbl = $db->getOne ("dinero_recibido_tbl", "SUM(total_valor_recibido) as dineroRecibidoAbonos");

    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        $dineroRecibidoAbonos = $queryTbl['dineroRecibidoAbonos'];
    }
    return $dineroRecibidoAbonos;
}

$datas_array = array();
$datas_array = queryDineroRecibido($idSSUser, $qDateStart, $qDateEnd, $qCobrador);

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
        $nombrecobrador      = isset($daKey['cobrador'])? $db->escape($daKey['cobrador']) : "";
        $refcredito          = isset($daKey['refcredito'])? $db->escape($daKey['refcredito']) : "";
        $numerocuota         = isset($daKey['numecuota'])? $db->escape($daKey['numecuota']) : "";
        $valorrecaudadocuota = isset($daKey['valortotalrecibido'])? $db->escape($daKey['valortotalrecibido']) : "";
        $fecharecaudo        = ($daKey['fecharecaudo'] == "0000-00-00")? "" : $db->escape($daKey['fecharecaudo']);
        $iddeudor            = isset($daKey['deudor'])? $db->escape($daKey['deudor']) : "";

        //datos deudor
        $datasdeudor = queryDeudores($iddeudor);

        $valor_cuota            = 0;
        $total_cuota            = 0;
        $dinero_recibido_abonos = 0;

        if(is_array($datasdeudor) && !empty($datasdeudor)){
            foreach($datasdeudor as $ddKey){
                $nombrededuor   = isset($datasdeudor['primer_nombre_deudor'])? $db->escape($datasdeudor['primer_nombre_deudor']) : "";
                $apellidodeduor = isset($datasdeudor['primer_apellido_deudor'])? $db->escape($datasdeudor['primer_apellido_deudor']) : "";
                $ceduladeduor   = isset($datasdeudor['cedula_deudor'])? $db->escape($datasdeudor['cedula_deudor']) : "";
            }
        }

        //dinero recibido en esta cuota
        $dinero_recibido_cuota = queryDineroRecibido($refcredito, $numerocuota);

        //dinero recibido con abonos
        $fecha_recaudo_human   = ($fecharecaudo != "") ? date("d/m/Y",strtotime($fecharecaudo)) : "";
        //$dinero_recibido_abonos = queryDineroRecibidoAbonos($refcredito, $qDateStart, $qDateEnd);

        //define si cuota tiene mora
        //$cuota_con_mora = ($activamora == 1)? $valormora : 0;

        //define valor cuota
        $valor_cuota            = $valorrecaudadocuota;//($valorbasecuota + $cuota_con_mora) - $valorrecaudadocuota;
        $dinero_recibido_abonos = $dinero_recibido_abonos + $valor_cuota;

        $total_cuota++;

        //definir status credito
        $statusCredito   = "";
        $idStatusCredito = array();

        $db->where("code_consecutivo_credito",$refcredito);
        $idStatusCredito = $db->getOne("creditos_tbl", "id_status_credito");

        if(count($idStatusCredito)>0){
          $statusCredito =  queryStatusdeCredito($idStatusCredito["id_status_credito"]);
        }

        //formato datos
        $fecha_cobrar_human  = ($fecharecaudo != "") ? date("d/m/Y",strtotime($fecharecaudo)) : "";
        $fecha_recaudo_human = ($fecharecaudo != "") ? date("d/m/Y",strtotime($fecharecaudo)) : "";
        $nombre_deudor       = $nombrededuor." ".$apellidodeduor;

        /**VALORES DATAS JSON*/
        $layoutDataItem.='{
        "item":"'.$num.'",
        "refcredito":"'.$refcredito.'",
        "statuscredito":"'.$statusCredito.'",
        "cobrador":"'.$nombrecobrador.'",
        "cuota":"'.$numerocuota.'",
        "vence":"'.$fecha_cobrar_human.'",
        "fecharcaudo":"'.$fecha_recaudo_human.'",
        "valorcuota":"'.$valor_cuota.'",
        "dinerorecibido":"'.$dinero_recibido_abonos.'",
        "nombre":"'.$nombre_deudor.'",
        "cedula":"'.$ceduladeduor.'"
        },';

        $num++;


    } /*-/FIN FOREACH DATAS/-*/

    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": '.$totalRegistros.',
    "recordsFiltered": '.$totalRegistros.',
    "data":['.$layoutDataItem.']}';

}else{
    $layoutDataItem.='{
    "item":"-",
    "refcredito":"-",
    "statuscredito":"-",
    "cobrador":"-",
    "cuota":"-",
    "vence":"-",
    "fecharcaudo":"-",
    "valorcuota":"-",
    "dinerorecibido":"-",
    "nombre":"-",
    "cedula":"-"
    },';

    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}

?>
