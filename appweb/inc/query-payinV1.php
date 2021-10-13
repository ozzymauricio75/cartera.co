<?php
//QUERY PLAN DE PAGOS
function queryCuotas_recaudo($idRecaudo_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_recaudo', $idRecaudo_);
    $queryTbl = $db->get ("recaudos_tbl", 1, "id_acreedor, id_recaudo, ref_recaudo, id_plan_pago, id_cobrador,  activa_mora, id_status_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, comentarios_recaudo");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {        
            $dataQuery[] = $qKey;                 
        }    
        return $dataQuery;
    }
}



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




//SELECT `id_recaudo`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recaudo`, `ref_recaudo`, `numero_cuota_recaudos`, `metodo_pago_recaudo`, `capital_cuota_recaudo`, `interes_cuota_recaudo`, `valor_mora_cuota_recaudo`, `sobrecargo_cuota_recaudo`, `total_cuota_plan_pago`, `total_valor_recaudado_estacuota`, `valor_faltante_cuota`, `valor_cuota_recaulcaldo_recaudos`, `fecha_max_recaudo`, `fecha_recaudo_realizado`, `comentarios_recaudo` FROM `recaudos_tbl` WHERE 1
//QUERY PLAN DE PAGOS
function queryCuotas_FULL($idRecaudo_){
    global $db;  
    $dataQuery = array();
    $dataCredito = array();
            
    //$db->where('id_acreedor', $idAcreedor_);
    //$db->where('id_cobrador', $idCobrador_);
    //$db->where('fecha_max_recaudo', $fechaHoy_);    
    //$db->where('id_status_recaudo', '3');
    $db->where('id_recaudo', $idRecaudo_);
    $queryTbl = $db->get ("recaudos_tbl", 1, "id_acreedor, id_recaudo, ref_recaudo, id_plan_pago, id_cobrador,  activa_mora, id_status_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, comentarios_recaudo");
        
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
            
                                                
            $dataQuery = array(  
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
                //'totalrecaudos' => $dataValorTotalRecaudos
            );
            
        }    
        return $dataQuery;
    }
}


/*
*======================================
*QUERYS SIMPLIFICADO PARA PUBLICAR RUTAS
*======================================
*/


/*//--DINERO ACUMULADO POR CREDITO--//*/
function queryValorAcumulado($concecutivo){
    global $db;  
    global $dateFormatDB;
    $dataQuery = array();

    $db->where('ref_recaudo', $concecutivo);        
    $db->where('id_status_recaudo', '1', '!='); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $db->where('fecha_max_recaudo', $dateFormatDB, '<=');
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_cuota_plan_pago) AS totalcuotas, SUM(total_valor_recaudado_estacuota) AS totalrecaudos");

    $totalCuotas = $queryTbl['totalcuotas'];
    $totalRecaudos = $queryTbl['totalrecaudos'];

    $dineroporcobrar = $totalCuotas - $totalRecaudos;
    return $dineroporcobrar;
}

/*//--CUOTA ACTUAL DEL CREDITO--//*/
function queryCuotaActual($concecutivo){
    global $db;  
    global $dateFormatDB;
    $dataQuery = array();

    $db->where('ref_recaudo', $concecutivo);    
    $db->where('fecha_max_recaudo', $dateFormatDB, '<=');
    $queryTbl = $db->getOne ("recaudos_tbl", "MAX(id_recaudo) AS recaudoactual");

    $recaudoActual = $queryTbl['recaudoactual'];                                        
    return $recaudoActual;
}



//QUERY DEUDOR
function queryDeudor($idDeudor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_deudor', $idDeudor_);        
    $queryTbl = $db->get ("deudor_tbl", 1, "id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, dir_geo_deudor, cedula_deudor, tel_uno_deudor, tel_dos_deudor, latitud_geo_deudor, longitud_geo_deudor, codigo_geo_ciudad_deudor, codigo_geo_estado_deudor, codigo_geo_pais_deudor");
    
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {  
            $dataQuery = $qKey;
        }
        return $dataQuery;
    }
    //$idDeudor = $queryTbl['id_deudor'];
    //return $idDeudor;
}


//QUERY PLAN DE PAGOS
function queryPlanPago($idCredito_){
    global $db;  
    $dataQuery = array();
        
    $db->where('id_credito', $idCredito_);        
    $queryTbl = $db->getOne ("planes_pago_tbl", "id_plan_pago");
    
    $idPlanPago = $queryTbl['id_plan_pago'];
    return $idDeudor;
    
}



//QUERY PLAN DE COBRADOR
function queryCobrador($idCobrador_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_cobrador', $idCobrador_);        
    $queryTbl = $db->getOne ("cobrador_tbl", "id_cobrador");
        
    $idCobrador = $queryTbl['id_cobrador'];
    return $idDeudor;
}


//QUERY STATUS CREDITO
function queryStatus($idStatusCredito_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_status', $idStatusCredito_);        
    $queryTbl = $db->getOne ("status_credito_tbl", "nombre_status");
    
    $nombreStatus = $queryTbl['nombre_status'];
    return $nombreStatus;
   
}

//QUERY TIPO CREDITO
function queryTipoCredito($idtipoCredito_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_tipo_credito', $idtipoCredito_);        
    $queryTbl = $db->getOne ("tipo_credito_tbl", "nombre_tipo_credito");
    
    $nombreTipoCredito = $queryTbl['nombre_tipo_credito'];
    return $nombreTipoCredito;
}


//QUERY CREDITOS DETALLES
function queryCreditosDetalles($consecutivo_){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
    //$dataDeudor = array();
        
    $db->where('code_consecutivo_credito', $consecutivo_);    
    $queryTbl = $db->get ("creditos_tbl", 1, "id_creditos, id_deudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
            
            $idCredito = $qKey['id_creditos'];            
            $idDeudor = $qKey['id_deudor'];
                                    
            $dataQuery = array(                
                'idcredito' => $idCredito,               
                'iddeudor' => $idDeudor,                
                //'datasdeudor' => $dataDeudor                
            );
            
            //$dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}




//SELECT `id_recaudo`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recaudo`, `ref_recaudo`, `numero_cuota_recaudos`, `metodo_pago_recaudo`, `capital_cuota_recaudo`, `interes_cuota_recaudo`, `valor_mora_cuota_recaudo`, `sobrecargo_cuota_recaudo`, `total_cuota_plan_pago`, `total_valor_recaudado_estacuota`, `valor_faltante_cuota`, `valor_cuota_recaulcaldo_recaudos`, `fecha_max_recaudo`, `fecha_recaudo_realizado`, `comentarios_recaudo` FROM `recaudos_tbl` WHERE 1
//QUERY PLAN DE PAGOS
function queryCuotas($idAcreedor_, $idCobrador_, $fechaHoy_){
    global $db;  
    $dataQuery = array();
    $dataCredito = array();
    $last_err_query = "";
            
    $db->where('id_acreedor', $idAcreedor_);//$idAcreedor_
    $db->where('id_cobrador', $idCobrador_);
    $db->where('fecha_max_recaudo', $fechaHoy_);    
    $db->where('id_status_recaudo', '3');
    $queryTbl = $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, ref_recaudo, id_plan_pago, id_cobrador,  activa_mora, id_status_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, comentarios_recaudo");
    
    $last_err_query = $db->getLastErrno();
    
    if(isset($last_err_query) && $last_err_query != ""){
        $dataQuery['errquery'] = $last_err_query;
        return $dataQuery;
        exit();
    }
    
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {        
            $idRecaudo = $qKey['id_recaudo'];
            $consecutivo = $qKey['ref_recaudo'];
            $idAcreedor = $qKey['id_acreedor'];
            //$idDeudor = $qKey['id_deudor'];                        
            $idCobrador = $qKey['id_cobrador'];
            $idPlanPago = $qKey['id_plan_pago'];
            $numeroCuota = $qKey['numero_cuota_recaudos'];
            $valorMora = $qKey['valor_mora_cuota_recaudo'];
            $valorCuota = $qKey['total_cuota_plan_pago'];
            $valorRecaudado = $qKey['total_valor_recaudado_estacuota'];
            $valorFaltanteCuota = $qKey['valor_faltante_cuota'];
            $valorRecalculado = $qKey['valor_cuota_recaulcaldo_recaudos'];
            $fechaMaxRecaudo = $qKey['fecha_max_recaudo'];
            $fechaRecaudado = $qKey['fecha_recaudo_realizado'];
            $comentarioCuota = $qKey['comentarios_recaudo'];

            //SOBRE EL LAS CUOTAS
            $dataCredito = queryCreditosDetalles($consecutivo); //$dateFormatDB

            $dataQuery[] = array(   
                'idacreedor' => $idAcreedor,
                'idcobrador' => $idCobrador,
                'idrecaudo' => $idRecaudo,
                'idplanpago' => $idPlanPago,
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
                //'totalrecaudos' => $dataValorTotalRecaudos
            );

        }    
        return $dataQuery;
    }
    
}



/*
*========================
*INFORMACION LISTA RUTAS
*========================
*/


//TOTAL DINERO A RECAUDAR
function queryValorRecaudosRutaHoy($fechaRecaudo_, $idCobrador_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_cobrador', $idCobrador_);  
    $db->where('fecha_max_recaudo', $fechaRecaudo_);        
    $db->where('id_status_recaudo', '1', '!='); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->get ("recaudos_tbl", null, "total_cuota_plan_pago, valor_cuota_recaulcaldo_recaudos, valor_faltante_cuota");
    
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
       
}


//TOTAL DINERO RECAUDADO
function queryValorRecibidoRutaHoy($fechaRecaudo_, $idCobrador_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_cobrador', $idCobrador_);  
    $db->where('fecha_max_recaudo', $fechaRecaudo_);        
    $db->where('id_status_recaudo', '3', '!='); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->get ("recaudos_tbl", null, "total_valor_recaudado_estacuota");
    
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
       
}


//RECAUDO RUTA
function queryRecaudosRuta($idRecaudo){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_recaudo', $idRecaudo);    
    $queryTbl = $db->get ("recaudos_tbl", 1, "id_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
    
}


//ESPECIFICACIONES RUTAS
//SELECT `id_especifica_ruta`, `id_ruta`, `id_creditos`, `id_deudor`, `id_plan_pago`, `id_recaudo`, `id_cobrador`, `id_status_recaudo` FROM `especifica_ruta_tbl` WHERE 1
function queryEspecificaRutas($idRuta_){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
                    
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
            
                        
            $dataSumatoriaRuta = queryRecaudosRuta($idRecaudo);
            
            $dataQuery[] = array(
                'idespecificaruta' => $idEspecificaRuta,
                'idruta' => $idRuta,
                'idcreditos' => $idCreditoRuta,
                'iddeudor' => $idDeudor,
                'idplan_pago' => $idPlanPago,
                'idrecaudo' => $idRecaudo,
                'idcobrador' => $idCobrador,
                'datasrecaudo' => $dataSumatoriaRuta
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
                        
            $dataEspecificaRuta = queryEspecificaRutas($idRuta);
                
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
*========================
*INFORMACION DETALLES RUTA
*========================
*/


//SOBRE LA RUTA
function queryRutasDetalle($idRuta_){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
    $datasCobrador = array();
                    
    $db->where('id_ruta', $idRuta_);    
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
                        
            $dataEspecificaRuta = queryEspecificaRutas($idRuta);
            
            //info cobrador
            $datasCobrador = queryCobrador_FULL($idCobradorRuta);
                
            $dataQuery[] = array(
                'idRuta' => $idRuta,
                'idAcedorRuta' => $idAcedorRuta,
                'idCobradorRuta' => $idCobradorRuta,
                'statusRuta' => $statusRuta,
                'consecutivoRuta' => $consecutivoRuta,
                'nombreCobradorRuta' => $nombreCobradorRuta,
                'fechaRegistroRuta' => $fechaRegistroRuta,
                'datasespecificacionesruta' => $dataEspecificaRuta,
                'datascobrador' => $datasCobrador,
            );
 
        }    
        return $dataQuery;
    }
}



/*
*========================
*INFORMACION DETALLES RECAUDOS ESPECIFICOS DE LA RUTA
*========================
*/


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
            
            $db->where("id_creditos", $idCreditoRuta);
            $queryRefCredito = $db->getOne("creditos_tbl", "code_consecutivo_credito");
            $refCredito = $queryRefCredito['code_consecutivo_credito'];
                        
            $dataQuery[] = array(
                'idespecificaruta' => $idEspecificaRuta,
                'idruta' => $idRuta,
                'idcreditos' => $idCreditoRuta,
                'refcredito' => $refCredito,
                'iddeudor' => $idDeudor,
                'idplan_pago' => $idPlanPago,
                'idrecaudo' => $idRecaudo,
                'idcobrador' => $idCobrador,
                'datasrecaudo' => $datasCuota,                
            );
            
        }    
        return $dataQuery;
    }
}



/*
*========================
*MAPA PAYIN
*========================
*/

//QUERY RUTAS
function queryMapaRutas($idCobrador_, $fechaHoy_){
    global $db;    
    global $dateFormatDB;
    $dataQuery = array();
                    
    $db->where('id_cobrador', $idCobrador_);    
    $db->where('fecha_creacion_ruta', $fechaHoy_);        
    $queryTbl = $db->getOne ("rutas_tbl", "consecutivo_ruta");
    
    $dataQuery = $queryTbl['consecutivo_ruta'];
    
    return $dataQuery;
}



/*
*========================
*CALCULO BALANCE GENERAL
*========================
*/
//SELECT `id_recaudo`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recaudo`, `ref_recaudo`, `numero_cuota_recaudos`, `metodo_pago_recaudo`, `capital_cuota_recaudo`, `interes_cuota_recaudo`, `valor_mora_cuota_recaudo`, `sobrecargo_cuota_recaudo`, `total_cuota_plan_pago`, `total_valor_recaudado_estacuota`, `valor_faltante_cuota`, `valor_cuota_recaulcaldo_recaudos`, `fecha_max_recaudo`, `fecha_recaudo_realizado`, `comentarios_recaudo` FROM `recaudos_tbl` WHERE 1


/*//--VALOR CREDITO--//*/
function queryValorCreditos($concecutivo){
    global $db;  
    $dataQuery = array();
    
    $db->where('ref_recaudo', $concecutivo);        
    //$db->where('id_status_recaudo', '3'); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_cuota_plan_pago) AS valorcredito");
    
    $valorcredito = $queryTbl['valorcredito'];
    return $valorcredito;
       
}


/*//--DINERO PRESTADO--//*/
function queryDineroCreditos($concecutivo){
    global $db;  
    $dataQuery = array();
    
    $db->where('ref_recaudo', $concecutivo);        
    $db->where('id_status_recaudo', '3'); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(capital_cuota_recaudo) AS capitalprestado");
        
    $capitalprestado = $queryTbl['capitalprestado'];
    return $capitalprestado;
    /*$rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }*/
    
}

/*//--DINERO RECAUDADO--//*/
function queryDineroRecaudado($concecutivo){
    global $db;  
    $dataQuery = array();
    
    $db->where('ref_recaudo', $concecutivo);         
    $db->where('id_status_recaudo', '3', '!=');//ESTADO QUE INDICA QUE LA CUOTA FUE PAGADA COMPLETA O ABONADA  
    //$db->where('id_status_recaudo', '1');
    //$db->orWhere('id_status_recaudo', '2');
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_valor_recaudado_estacuota) AS valorrecaudado");
    
    $valorrecaudado = $queryTbl['valorrecaudado'];
    return $valorrecaudado;
    /*$rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }*/
    
}


/*//--DINERO POR RECAUDAR--//*/
function queryDineroPorCobrar($concecutivo){
    global $db;  
    $dataQuery = array();
    
    $db->where('ref_recaudo', $concecutivo);        
    //$db->where('id_status_recaudo', '3'); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_cuota_plan_pago) AS totalcuotas, SUM(total_valor_recaudado_estacuota) AS totalrecaudos");
    
    $totalCuotas = $queryTbl['totalcuotas'];
    $totalRecaudos = $queryTbl['totalrecaudos'];
    
    $dineroporcobrar = $totalCuotas - $totalRecaudos;
    return $dineroporcobrar;
}

//QUERY BALANCE GLOBAL
function queryBalanceGlobal($idAcreedor_){
    global $db;    
    $dataQuery = array();
    
    $dataValorCredito = array();
    $dataDineroCreditos = array();
    $dataDineroRecaudado = array();
    $porCobrar = array();
        
    
    $db->where('id_acreedor', $idAcreedor_);        
    $db->where('id_status_credito', '1');     
    $db->where('code_consecutivo_credito', '' , '!='); 
    $queryTbl = $db->get ("creditos_tbl", null, "id_creditos, code_consecutivo_credito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {            
            $idCredito = $qKey['id_creditos'];
            $concecutivo = $qKey['code_consecutivo_credito'];
            
            //VALOR CREDITO
            $dataValorCredito = queryValorCreditos($concecutivo);            
            
            //CAPITAL PRESTADO
            $dataDineroCreditos = queryDineroCreditos($concecutivo);
            
            //DINERO RECAUDADO
            $dataDineroRecaudado = queryDineroRecaudado($concecutivo);
            
            //POR COBRAR
            $porCobrar = queryDineroPorCobrar($concecutivo);
                                    
            $dataQuery[] = array(
                'idcredito' => $idCredito,
                'consecutivo' =>  $concecutivo,
                'datavalorcredito' => $dataValorCredito,
                'datadineroencredito' => $dataDineroCreditos,
                'datadinerorecaudado' => $dataDineroRecaudado,
                'dataporcobrar' => $porCobrar             
            );
            
            //$dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}