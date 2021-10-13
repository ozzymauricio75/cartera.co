<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php 

/*
*======================================
*QUERYS FULL RUTAS
*======================================
*/

//QUERY DEUDOR
function queryDeudor_FULL($idDeudor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_deudor', $idDeudor_);        
    $queryTbl = $db->get ("deudor_tbl", 1, "id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, ciudad_domicilio_deudor, estado_domicilio_deudor, dir_geo_deudor, cedula_deudor, email_deudor, tel_uno_deudor, tel_dos_deudor, latitud_geo_deudor, longitud_geo_deudor, url_maps_deudor, codigo_geo_ciudad_deudor, codigo_geo_estado_deudor, codigo_geo_pais_deudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}


//QUERY PLAN DE PAGOS
function queryPlanPago_FULL($idCredito_){
    global $db;  
    $dataQuery = array();
        
    $db->where('id_credito', $idCredito_);        
    $queryTbl = $db->get ("planes_pago_tbl", 1, "id_plan_pago, periocidad_plan_pago, valor_credito_plan_pago, valor_pagar_credito, utilidad_credito, numero_cuotas_plan_pago, plazocredito_plan_pago, fecha_inicio_plan_pago, fecha_fin_plan_pago, capital_cuota_plan_pago, valor_cuota_plan_pago, valor_mora_plan_pago");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}



//QUERY PLAN DE COBRADOR
function queryCobrador_FULL($idCobrador_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_cobrador', $idCobrador_);        
    $queryTbl = $db->get ("cobrador_tbl", 1, "id_cobrador, nombre_cobrador, mail_cobrador, tel_uno_cobrador, tel_dos_cobrador, direccion_cobrador, ciudad_cobrador");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}


//QUERY STATUS CREDITO
function queryStatus_FULL($idStatusCredito_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_status', $idStatusCredito_);        
    $queryTbl = $db->getOne ("status_credito_tbl", "nombre_status");
    
    $nombreStatus = $queryTbl['nombre_status'];
    return $nombreStatus;
    /*$rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }*/
}

//QUERY TIPO CREDITO
function queryTipoCredito_FULL($idtipoCredito_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_tipo_credito', $idtipoCredito_);        
    $queryTbl = $db->getOne ("tipo_credito_tbl", "nombre_tipo_credito");
    
    $nombreTipoCredito = $queryTbl['nombre_tipo_credito'];
    return $nombreTipoCredito;
    
    /*$rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }*/
}


//QUERY CREDITOS DETALLES
function queryCreditosDetalles_FULL($consecutivo_){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
    
    $dataPlanPago = array();
    $dataCuotas = array();
    $dataDeudor = array();
    $dataCobrador = array();
    $dataStatus = array();
    $dataTipoCredito = array();    
    $dataCoDeudor = array();
    $dataRefFami = array();    
    $dataRefPer = array();    
    $dataRefComer = array();    
    //$dataCobrador = array();
            
    $db->where('code_consecutivo_credito', $consecutivo_);    
    $queryTbl = $db->get ("creditos_tbl", 1, "id_creditos, id_acreedor, id_deudor, id_cobrador, id_status_credito, id_plan_pago, code_consecutivo_credito, tipo_credito, fecha_apertura_credito, hora_apertura_credito, fecha_cierre_definitivo_credito, descripcion_credito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
            
            $idCredito = $qKey['id_creditos'];
            $idAcreedor = $qKey['id_acreedor'];
            $idDeudor = $qKey['id_deudor'];                        
            $idCobrador = $qKey['id_cobrador'];
            
            $fechaAbreCredito = $qKey['fecha_apertura_credito'];
            $horaAbreCredito = $qKey['hora_apertura_credito'];
            $fechaCierreCredito = $qKey['fecha_cierre_definitivo_credito'];
            $descripcionCredito = $qKey['descripcion_credito'];
                        
            $idStatusCredito = $qKey['id_status_credito'];
            //$idPlanPago = $qKey['id_plan_pago'];
            $concecutivo = $qKey['code_consecutivo_credito'];
            $idtipoCredito = $qKey['tipo_credito'];
            
            //SOBRE EL PLAN DE PAGO
            $dataPlanPago = queryPlanPago_FULL($idCredito);
            
            
            //SOBRE EL COBRADOR
            $dataCobrador = queryCobrador_FULL($idCobrador);
            
            //SOBRE EL DEUDOR
            $dataDeudor = queryDeudor_FULL($idDeudor);
                        
            //SOBRE EL STATUS CREDITO
            $statusCredito = queryStatus_FULL($idStatusCredito);
            
            //SOBRE EL TIPO DE CREDITO
            $tipoCredito = queryTipoCredito_FULL($idtipoCredito);
                        
            $dataQuery = array(                
                'idcredito' => $idCredito,
                'consecutivo' =>  $concecutivo,
                'dataplanpago' => $dataPlanPago,
                //'datacobrador' => $dataCobrador,
                'datadeudor' => $dataDeudor,                
                'statuscredito' => $statusCredito,
                'tipocredito' => $tipoCredito,
                'fechaabrecredito' =>$fechaAbreCredito,
                'horaabrecredito' =>$horaAbreCredito,
                'fechaterminacredito' =>$fechaCierreCredito,
                'descricredito' => $descripcionCredito
            );
            
            //$dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}


function queryCuotas_FULL($idAcreedor_, $fechaHoy_ ){
    global $db;  
    $dataQuery = array();
    $dataCredito = array();
            
    $db->where('id_acreedor', $idAcreedor_);
    //$db->where('id_cobrador', $idCobrador_);
    $db->where('fecha_max_recaudo', $fechaHoy_, "<=");    
    $db->where('id_status_recaudo', '1', "!=");    
    //$db->where('id_recaudo', $idRecaudo_);
    $queryTbl = $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, ref_recaudo, id_plan_pago, id_cobrador,  activa_mora, id_status_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, comentarios_recaudo");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {        
            $idStatusRecaudo = $qKey['id_status_recaudo'];
            $idRecaudo = $qKey['id_recaudo'];
            $consecutivo = $qKey['ref_recaudo'];
            $idAcreedor = $qKey['id_acreedor'];
            //$idDeudor = $qKey['id_deudor'];                        
            $idCobrador = $qKey['id_cobrador'];
            //$idPlanPago = $qKey['id_plan_pago'];
            $numeroCuota = $qKey['numero_cuota_recaudos'];
            $activaValorMora = $qKey['activa_mora'];
            $valorMora = $qKey['valor_mora_cuota_recaudo'];
            $valorCuota = $qKey['total_cuota_plan_pago'];
            $valorRecaudado = $qKey['total_valor_recaudado_estacuota'];
            $valorFaltanteCuota = $qKey['valor_faltante_cuota'];
            $valorRecalculado = $qKey['valor_cuota_recaulcaldo_recaudos'];
            $fechaMaxRecaudo = $qKey['fecha_max_recaudo'];
            $fechaRecaudado = $qKey['fecha_recaudo_realizado'];
            $comentarioCuota = $qKey['comentarios_recaudo'];
                                    
            //SOBRE EL LAS CUOTAS
            $dataCredito = queryCreditosDetalles_FULL($consecutivo); //$dateFormatDB
            
            /*//VALOR TOTAL RECAUDOS
            $valor_cuota_final = 0;
            if($valorRecalculado != 0){
                $dataValorTotalRecaudos = $dataValorTotalRecaudos + $valorRecalculado;
            }else{
                $dataValorTotalRecaudos = $dataValorTotalRecaudos + $valorCuota;//querySumatoriaRecaudos($idRecaudo);    
            }*/
            
                                                
            $dataQuery[] = array(  
                'idstatusrecaudo' => $idStatusRecaudo,
                'idacreedor' => $idAcreedor,
                'idcobrador' => $idCobrador,
                'idrecaudo' => $idRecaudo,
                'consecutivo' => $consecutivo,
                'numeroCuota' =>  $numeroCuota,
                'activaValorMora' => $activaValorMora,
                'valorMora' => $valorMora,
                'valorCuota' => $valorCuota,
                'valorRecaudado' => $valorRecaudado,                
                'valorFaltanteCuota' => $valorFaltanteCuota,
                'valorRecalculado' => $valorRecalculado,
                'fechaMaxRecaudo' =>$fechaMaxRecaudo,
                'fechaRecaudado' =>$fechaRecaudado,
                'comentarioCuota' =>$comentarioCuota,
                'datascredito' => $dataCredito
                //'totalrecaudos' => $dataValorTotalRecaudos
            );
            
        }    
        
    }
    return $dataQuery;
}


//SOBRE LA RUTA
function queryRecaudosRutaDetalles($idRuta_){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
    $datasCuota = array();
                    
    $db->where('id_ruta', $idRuta_);    
    $queryTbl = $db->get ("especifica_ruta_tbl");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
                        
            $idEspecificaRuta = $qKey['id_especifica_ruta'];
            $idRuta = $qKey['id_ruta'];
            $idCreditoRuta = $qKey['id_creditos'];
            $idDeudor = $qKey['id_deudor'];
            $idPlanPago = $qKey['id_plan_pago'];
            $idRecaudo = $qKey['id_recaudo'];
            $idCobrador = $qKey['id_cobrador'];
            
                        
            $datasCuota = queryCuotas_FULL($idRecaudo);
            
            $dataQuery[] = array(
                'idespecificaruta' => $idEspecificaRuta,
                'idruta' => $idRuta,
                'idcreditos' => $idCreditoRuta,
                'iddeudor' => $idDeudor,
                'idplan_pago' => $idPlanPago,
                'idrecaudo' => $idRecaudo,
                'idcobrador' => $idCobrador,
                'datasrecaudo' => $datasCuota
            );
            
        }    
        return $dataQuery;
    }
}


//QUERY RUTAS
function queryRutas($idAcreedor_, $fechaHoy_){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
                    
    $db->where('id_acreedor', $idAcreedor_);    
    $db->where('fecha_creacion_ruta', $fechaHoy_);        
    $queryTbl = $db->get ("rutas_tbl");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
            
            $idRuta = $qKey['id_ruta'];
            $idAcedorRuta = $qKey['id_acreedor'];
            $idCobradorRuta = $qKey['id_cobrador'];
            $statusRuta = $qKey['id_status_ruta'];
            $consecutivoRuta = $qKey['consecutivo_ruta'];
            $nombreCobradorRuta = $qKey['nombre_cobrador_ruta'];
            $fechaRegistroRuta = $qKey['fecha_creacion_ruta'];
                        
            $dataEspecificaRuta = queryRecaudosRutaDetalles($idRuta);
                
            $dataQuery[] = array(
                'idRuta' => $idRuta,
                'idAcedorRuta' => $idAcedorRuta,
                'idCobradorRuta' => $idCobradorRuta,
                'statusRuta' => $statusRuta,
                'consecutivoRuta' => $consecutivoRuta,
                'nombreCobradorRuta' => $nombreCobradorRuta,
                'fechaRegistroRuta' => $fechaRegistroRuta,
                'datasespecificacionesruta' => $dataEspecificaRuta
            );
 
        }    
        return $dataQuery;
    }
}




/*
*DETALLES DE LOS RECAUDOS PARA ESTA RUTA
*/
$datasCuotasRuta = queryCuotas_FULL($idSSUser, $dateFormatDB );

$totalRegistros = count($datasCuotasRuta);
$layoutDataItem = "";
$num = 0;
//echo "<pre>";
//print_r($datasCuotasRuta);



/*[INICIO |$datasCuotasRuta]*/
if(is_array($datasCuotasRuta) && !empty($datasCuotasRuta)){
    /*[INICIO FOREACH |$datasCuotasRuta]*/
    foreach($datasCuotasRuta as $dcrKey){

        /*$id_especifica_ruta = $dcrKey['idespecificaruta'];
        $id_credito_ruta = $dcrKey['idcreditos'];                
        $datas_recaudo_ruta = $dcrKey['datasrecaudo'];*/


        /*
        'idstatusrecaudo' => $idStatusRecaudo,
                'idacreedor' => $idAcreedor,
                'idcobrador' => $idCobrador,
                'idrecaudo' => $idRecaudo,
                'consecutivo' => $consecutivo,
                'numeroCuota' =>  $numeroCuota,
                'valorMora' => $valorMora,
                'valorCuota' => $valorCuota,
                'valorRecaudado' => $valorRecaudado,                
                'valorFaltanteCuota' => $valorFaltanteCuota,
                'valorRecalculado' => $valorRecalculado,
                'fechaMaxRecaudo' =>$fechaMaxRecaudo,
                'fechaRecaudado' =>$fechaRecaudado,
                'comentarioCuota' =>$comentarioCuota,
                'datascredito' => $dataCredito
        
        */
        
        
        
        //DATAS RECAUDO RUTA
        $id_recaudo_ruta = $db->escape($dcrKey['idrecaudo']);
        $consecutivo_credito_ruta = $db->escape($dcrKey['consecutivo']);
        $numecuota_credito_ruta = $db->escape($dcrKey['numeroCuota']);
        $valor_cuota_ruta = $db->escape($dcrKey['valorCuota']);
        $activa_mora_cuota_ruta = $db->escape($dcrKey['activaValorMora']);
        $valor_mora_cuota_ruta = $db->escape($dcrKey['valorMora']);
        $valor_recaudado_cuota_ruta = $db->escape($dcrKey['valorRecaudado']);
        $valor_faltante_cuota = $db->escape($dcrKey['valorFaltanteCuota']);
        $valor_recalculado_ruta = $db->escape($dcrKey['valorRecalculado']);
        $idstatus_recaudo_ruta = $db->escape($dcrKey['idstatusrecaudo']);
        $fecha_maxrecaudo_ruta = $db->escape($dcrKey['fechaMaxRecaudo']);
        $fecha_max_recaudo_formato = date("d/m/Y", strtotime($fecha_maxrecaudo_ruta));
        $datas_credito_ruta = $dcrKey['datascredito'];


        //DEFINE STATUS RECAUDO
        $statusRecaudo = "";
        switch($idstatus_recaudo_ruta){

            case "1":
                $statusRecaudo = "Pagado";    
            break;
            case "2":
                $statusRecaudo = "Abono";    
            break;
            case "3":
                $statusRecaudo = "Por pagar";    
            break;
        }

        //RECALCULO PROXIMA CUOTA 
        $valorFinalCuota = 0;
        $cuota_con_mora = ($activa_mora_cuota_ruta == 1) ? $valor_mora_cuota_ruta : 0;
        
        $valorFinalCuota = $valor_cuota_ruta + $cuota_con_mora - $valor_recaudado_cuota_ruta;        
        $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
        
        /*if($valor_faltante_cuota == 0){
            $valorFinalCuota = $valor_cuota_ruta;
            $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
        }else{
            $valorFinalCuota = $valor_faltante_cuota;    
            $valorFinalCuotaFormat = number_format($valorFinalCuota, 0, ',', '.');
        }*/
        
        $nombre_deudor_ruta = "";
        $apellido_deudor_ruta = "";
        $nombreCompletoDeudorRuta = "";
        $direccion_deudor_ruta = "";
        //DATAS CREDITO RUTA
        if(is_array($datas_credito_ruta) && !empty($datas_credito_ruta)){
            foreach($datas_credito_ruta as $dcredrKey){
                $datas_deudor_ruta = $datas_credito_ruta['datadeudor'];


                //DATAS DEUDOR RUTA
                if(is_array($datas_deudor_ruta) && !empty($datas_deudor_ruta)){
                    foreach($datas_deudor_ruta as $ddrKey){
                        $nombre_deudor_ruta = $db->escape($datas_deudor_ruta['primer_nombre_deudor']);
                        $apellido_deudor_ruta = $db->escape($datas_deudor_ruta['primer_apellido_deudor']);

                        $nombreCompletoDeudorRuta = $nombre_deudor_ruta." ".$apellido_deudor_ruta;

                        $direccion_deudor_ruta = $db->escape($datas_deudor_ruta['dir_geo_deudor']);
                    }
                }
            }
        }
            


        /*
        *LAYOUT BTN
        */
        //$detallesBTN = '<a href=\"details/?itemid_var='.$id_especifica_ruta.'&itemtype_var=deudordb\" type=\"button\" data-item=\"'.$id_especifica_ruta.'\" data-type=\"deudordb\" class=\"btn btn-primary \">Detalles <i class=\"fa fa-chevron-right margin-left-xs\" ></i></a>';


        /*
        *VALORES DATAS JSON
        */

        $layoutDataItem.='{
        "item":"'.$num.'",
        "id":"'.$id_recaudo_ruta.'",
        "fechamaxrecaudo":"'.$fecha_max_recaudo_formato.'",
        "concecutivo":"'.$consecutivo_credito_ruta.'",  
        "valorpagar":"$ '.$valorFinalCuotaFormat.'",  
        "numerocuota":"'.$numecuota_credito_ruta.'",
        "deudor":"'.$nombreCompletoDeudorRuta.'",
        "direccion":"'.$direccion_deudor_ruta.'",
        "status":"'.$statusRecaudo.'"
        },';
        
        
        //"acciones":"'.$dirgeo_item.'"

        $num++;

    }
    /*[FIN FOREACH|$datasCuotasRuta]*/


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
    "fechamaxrecaudo":"-",
    "concecutivo":"-",  
    "valorpagar":"-",  
    "numerocuota":"-",
    "deudor":"-",
    "direccion":"-",
    "status":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';   
    
    
}

/*[FIN |$datasCuotasRuta]*/