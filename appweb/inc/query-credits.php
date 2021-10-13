<?php


//QUERY DEUDOR
function queryDeudor($idDeudor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_deudor', $idDeudor_);        
    $queryTbl = $db->get ("deudor_tbl", 1, "id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, ciudad_domicilio_deudor, estado_domicilio_deudor, dir_geo_deudor, cedula_deudor, email_deudor, tel_uno_deudor, tel_dos_deudor, nit_referencia_comercial, nombre_contato_referencia_comercial, nombre_empresa_deudor, cargo_empresa_deudor, tel_empresa_deudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}

//QUERY CODEUDOR
function queryCoDeudor($idCoDeudor){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_codeudor', $idCoDeudor);        
    $db->where('id_status_perfil_codeudor', '5', '!=');            
    $queryTbl = $db->get ("codeudor_tbl", 1, "id_codeudor, primer_nombre_codeudor, primer_apellido_codeudor, cedula_codeudor, tel_uno_codeudor, tel_dos_codeudor, email_codeudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}

//QUERY REF PERSONAL
function queryRefPer($idRefPerso){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_referencia_personal', $idRefPerso);
    $db->where('id_status_perfil_referencia_personal', '5', '!=');        
    $queryTbl = $db->get ("referencia_personal_tbl", 1, "id_referencia_personal, primer_nombre_referencia_personal, primer_apellido_referencia_personal, cedula_referencia_personal, tel_uno_referencia_personal, tel_dos_referencia_personal, email_referencia_personal");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}

//QUERY REF FAMILIAR
function queryRefFami($idRefFami){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_referencia_familiar', $idRefFami);
    $db->where('id_status_perfil_referencia_familiar', '5', '!=');
    $queryTbl = $db->get ("referencia_familiar_tbl", 1, "id_referencia_familiar, primer_nombre_referencia_familiar, primer_apellido_referencia_familiar, cedula_referencia_familiar, tel_uno_referencia_familiar, tel_dos_referencia_familiar, email_referencia_familiar");
        
    $rowQueryTbl = count($queryTbl); 
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}

//QUERY REF COMERCIAL
function queryRefComer($idRefComer){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_referencia_comercial', $idRefComer); 
    $db->where('id_status_perfil_referencia_comercial', '5', '!=');
    $queryTbl = $db->get ("referencia_comercial_tbl", 1, "id_referencia_comercial, primer_nombre_referencia_comercial, primer_apellido_referencia_comercial, cedula_referencia_comercial, tel_uno_referencia_comercial, tel_dos_referencia_comercial, email_referencia_comercial, nit_referencia_comercial, nombre_empresa_referencia_comercial, nombre_contato_referencia_comercial, cargo_empresa_referencia_comercial tel_empresa_referencia_comercial");
        
    $rowQueryTbl = count($queryTbl); 
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}

//QUERY PLAN DE PAGOS
function queryPlanPago($idCredito_){
    global $db;  
    $dataQuery = array();
        
    $db->where('id_credito', $idCredito_);        
    $queryTbl = $db->get ("planes_pago_tbl", 1, "periocidad_plan_pago, valor_credito_plan_pago, valor_pagar_credito, utilidad_credito, numero_cuotas_plan_pago, plazocredito_plan_pago, fecha_inicio_plan_pago, fecha_fin_plan_pago, capital_cuota_plan_pago, valor_cuota_plan_pago, valor_mora_plan_pago");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}

//SELECT `id_recaudo`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recaudo`, `ref_recaudo`, `numero_cuota_recaudos`, `metodo_pago_recaudo`, `capital_cuota_recaudo`, `interes_cuota_recaudo`, `valor_mora_cuota_recaudo`, `sobrecargo_cuota_recaudo`, `total_cuota_plan_pago`, `total_valor_recaudado_estacuota`, `valor_faltante_cuota`, `valor_cuota_recaulcaldo_recaudos`, `fecha_max_recaudo`, `fecha_recaudo_realizado`, `comentarios_recaudo` FROM `recaudos_tbl` WHERE 1
//QUERY PLAN DE PAGOS
function queryCuotas($concecutivo_){
    global $db;  
    $dataQuery = array();
    
    $db->where('ref_recaudo', $concecutivo_);
    $db->orderBy("fecha_max_recaudo", "asc");
    $queryTbl = $db->get ("recaudos_tbl", null, "id_recaudo, activa_mora, id_status_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, comentarios_recaudo");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}


//QUERY PLAN DE COBRADOR
function queryCobrador($idCobrador_){
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
function queryStatus($idStatusCredito_){
    global $db;  
    $dataQuery = "";//array();
    
    $db->where('id_status', $idStatusCredito_);        
    $queryTbl = $db->getOne ("status_credito_tbl", "id_status, nombre_status");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        /*foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }*/
        $dataQuery = $queryTbl;
    }
    return $dataQuery;
}

//QUERY TIPO CREDITO
function queryTipoCredito($idtipoCredito_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_tipo_credito', $idtipoCredito_);        
    $queryTbl = $db->get ("tipo_credito_tbl", 1, "nombre_tipo_credito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}


//QUERY CREDITOS GLOBALES
function queryCreditos($idAcreedor_){
    global $db;    
    $dataQuery = array();
    
    $dataPlanPago = array();
    $dataDeudor = array();
    $dataCobrador = array();
    $dataStatus = array();
    $dataTipoCredito = array();
    
    
    $db->where('id_acreedor', $idAcreedor_);                   
    $db->where('code_consecutivo_credito', '' , '!=');
    $queryTbl = $db->get ("creditos_tbl", null, "id_creditos, id_acreedor, id_deudor, id_cobrador, id_status_credito, id_plan_pago, code_consecutivo_credito, tipo_credito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
            
            $idCredito = $qKey['id_creditos'];
            $idAcreedor = $qKey['id_acreedor'];
            $idDeudor = $qKey['id_deudor'];
            $idCobrador = $qKey['id_cobrador'];
            $idStatusCredito = $qKey['id_status_credito'];
            //$idPlanPago = $qKey['id_plan_pago'];
            $concecutivo = $qKey['code_consecutivo_credito'];
            $idtipoCredito = $qKey['tipo_credito'];
            
            //SOBRE EL PLAN DE PAGO
            $dataPlanPago = queryPlanPago($idCredito);
            
            //SOBRE EL DEUDOR
            $dataDeudor = queryDeudor($idDeudor);
            
            //SOBRE EL COBRADOR
            $dataCobrador = queryCobrador($idCobrador);
            
            //SOBRE EL STATUS CREDITO
            $dataStatus = queryStatus($idStatusCredito);
            
            //SOBRE EL TIPO DE CREDITO
            $dataTipoCredito = queryTipoCredito($idtipoCredito);
            
            $dataQuery[] = array(
                'idcredito' => $idCredito,
                'consecutivo' =>  $concecutivo,
                'dataplanpago' => $dataPlanPago,
                'datadeudor' => $dataDeudor,
                'datacobrador' => $dataCobrador,
                'datastatus' => $dataStatus,
                'datatipocredito' => $dataTipoCredito                
            );
            
            //$dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}


//QUERY CREDITOS DETALLES
function queryCreditosDetalles($idAcreedor_, $idCredito_ = null){
    global $db;    
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
            
    $db->where('id_acreedor', $idAcreedor_);      
    if(isset($idCredito_) && $idCredito_ != null){
        $db->where('id_creditos', $idCredito_);    
    }    
    $db->where('code_consecutivo_credito', '' , '!=');
    $queryTbl = $db->get ("creditos_tbl", null, "id_creditos, id_acreedor, id_deudor, id_codeudor, id_referencia_personal, id_referencia_familiar, id_referencia_comercial, id_cobrador, id_status_credito, id_plan_pago, code_consecutivo_credito, tipo_credito, fecha_apertura_credito, hora_apertura_credito, fecha_cierre_definitivo_credito, descripcion_credito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
            
            $idCredito = $qKey['id_creditos'];
            $idAcreedor = $qKey['id_acreedor'];
            $idDeudor = $qKey['id_deudor'];
            
            $idCoDeudor = $qKey['id_codeudor'];
            $idRefPerso = $qKey['id_referencia_personal'];
            $idRefFami = $qKey['id_referencia_familiar'];
            $idRefComer = $qKey['id_referencia_comercial'];
            $fechaAbreCredito = $qKey['fecha_apertura_credito'];
            $horaAbreCredito = $qKey['hora_apertura_credito'];
            $fechaCierreCredito = $qKey['fecha_cierre_definitivo_credito'];
            $descripcionCredito = $qKey['descripcion_credito'];
            
            $idCobrador = $qKey['id_cobrador'];
            $idStatusCredito = $qKey['id_status_credito'];
            //$idPlanPago = $qKey['id_plan_pago'];
            $concecutivo = $qKey['code_consecutivo_credito'];
            $idtipoCredito = $qKey['tipo_credito'];
            
            //SOBRE EL PLAN DE PAGO
            $dataPlanPago = queryPlanPago($idCredito);
            
            //SOBRE EL LAS CUOTAS
            $dataCuotas = queryCuotas($concecutivo);
            
            //SOBRE EL DEUDOR
            $dataDeudor = queryDeudor($idDeudor);
            
            //SOBRE EL CODEUDOR
            $dataCoDeudor = queryDeudor($idCoDeudor);
            
            //SOBRE REFERENCIA FAMILIAR
            $dataRefFami = queryDeudor($idRefFami);
            
            //SOBRE REFERENCIA PERSONAL
            $dataRefPer = queryDeudor($idRefPerso);
            
            //SOBRE REFERENCIA COMERCIAL
            $dataRefComer = queryDeudor($idRefComer);
            
            //SOBRE EL COBRADOR
            $dataCobrador = queryCobrador($idCobrador);
            
            //SOBRE EL STATUS CREDITO
            $dataStatus = queryStatus($idStatusCredito);
            
            //SOBRE EL TIPO DE CREDITO
            $dataTipoCredito = queryTipoCredito($idtipoCredito);
                        
            $dataQuery = array(                
                'idcredito' => $idCredito,
                'consecutivo' =>  $concecutivo,
                'dataplanpago' => $dataPlanPago,
                'datacuotas' => $dataCuotas,
                'datadeudor' => $dataDeudor,
                'datacodeudor' => $dataCoDeudor,
                'datacobrador' => $dataCobrador,
                'datasrefperso' => $dataRefPer,
                'datasreffami' => $dataRefFami,
                'datasrefcomer' => $dataRefComer,
                'datastatus' => $dataStatus,
                'datatipocredito' => $dataTipoCredito,
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


/*
*========================
*CALCULO BALANCE GENERAL
*========================
*/
//SELECT `id_recaudo`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recaudo`, `ref_recaudo`, `numero_cuota_recaudos`, `metodo_pago_recaudo`, `capital_cuota_recaudo`, `interes_cuota_recaudo`, `valor_mora_cuota_recaudo`, `sobrecargo_cuota_recaudo`, `total_cuota_plan_pago`, `total_valor_recaudado_estacuota`, `valor_faltante_cuota`, `valor_cuota_recaulcaldo_recaudos`, `fecha_max_recaudo`, `fecha_recaudo_realizado`, `comentarios_recaudo` FROM `recaudos_tbl` WHERE 1


//QUERY STATUS CREDITO
function queryStatusCredito($refcredito_){
    global $db;  
    $status_credito = "";
    
    $db->where("code_consecutivo_credito",$refcredito_);           
    $queryTbl = $db->getOne ("creditos_tbl", "id_status_credito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        $status_credito = $queryTbl["id_status_credito"];    
    }
    return $status_credito;
}


//QUERY STATUS CREDITO
function queryDineroPerdido(){
    global $db;  
    global $idSSUser;
        
    $credito = $db->subQuery('cre');
    $credito->where('id_status_credito', array(5,6), "IN");
    $credito->get('creditos_tbl', null, 'code_consecutivo_credito');

    $db->join($credito, 'cre.code_consecutivo_credito = rec.ref_recaudo');
    $db->where('id_acreedor', $idSSUser);       
    $db->where('id_status_recaudo', '1', "!="); 
    $quer_recaudos = $db->get("recaudos_tbl rec", null, "rec.ref_recaudo, rec.capital_cuota_recaudo, rec.total_cuota_plan_pago, rec.activa_mora, rec.valor_mora_cuota_recaudo, rec.total_valor_recaudado_estacuota, rec.valor_faltante_cuota, rec.valor_cuota_recaulcaldo_recaudos, rec.fecha_max_recaudo, rec.fecha_recaudo_realizado");
    
    $total_dinero_perdido = 0;
    $total_dinero_perdido_Format = 0;
    if(is_array($quer_recaudos) && !empty($quer_recaudos)){
        foreach($quer_recaudos as $qrecKey){
            $valorCuota = $qrecKey['total_cuota_plan_pago'];               
            $valorCuotaRecaudado = $qrecKey['total_valor_recaudado_estacuota'];            
            $activaMoraCuota = $qrecKey['activa_mora'];
            $valorMoraCuota = $qrecKey['valor_mora_cuota_recaudo'];

            $cuotaCobraMora = ($activaMoraCuota == 1)? $valorMoraCuota : 0;
            
            $total_dinero_perdido = $total_dinero_perdido + $valorCuota + $cuotaCobraMora - $valorCuotaRecaudado;
            //$total_dinero_perdido_Format = number_format($total_dinero_perdido, 0, ',', '.');    
        }
    }
    return $total_dinero_perdido;
}


/*//--VALOR CREDITO--//*/
function queryValorCreditos($concecutivo){
    global $db;  
    $dataQuery = array();
    $valorcredito = 0;
    
    /*$db->where('id_credito', $concecutivo);        
    //$db->where('id_status_recaudo', '3'); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->getOne ("planes_pago_tbl", "valor_pagar_credito AS valorcredito");*/
    
    $credito = $db->subQuery('cre');
    $credito->where('id_status_credito', array(1,3), "IN");    
    $credito->where('id_creditos', $concecutivo);
    $credito->get('creditos_tbl', 1,'code_consecutivo_credito');

    //VALOR DINERO CREDITOS
    $db->join($credito, 'cre.code_consecutivo_credito = rec.ref_recaudo');       
    $queryTbl = $db->getOne ("recaudos_tbl rec", "SUM(rec.total_cuota_plan_pago) AS valorcredito");
    
    $valorcredito = $queryTbl['valorcredito'];
    return $valorcredito;
    
       
}


/*//--DINERO PRESTADO--//*/
function queryDineroCreditos($concecutivo){
    global $db;  
    $dataQuery = array();
    
    /*$db->where('id_credito', $concecutivo);            
    $queryTbl = $db->getOne ("planes_pago_tbl", "valor_credito_plan_pago AS capitalprestado");*/
    
    $credito = $db->subQuery('cre');
    //$credito->where('id_status_credito', array(1,3), "IN");    
    $credito->where('id_status_credito', "7", "!=");
    $credito->where('id_creditos', $concecutivo);
    $credito->getOne('creditos_tbl', 'id_creditos');

    //VALOR DINERO PRESTADO
    $db->join($credito, 'cre.id_creditos = pp.id_credito');             
    $queryTbl = $db->getOne ("planes_pago_tbl pp", "pp.valor_credito_plan_pago AS capitalprestado");
        
    $capitalprestado = $queryTbl['capitalprestado'];
    return $capitalprestado;
    
    
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

/*//--DINERO CUOTAS POR RECAUDAR CON MORA--//*/
function queryDineroCuotasMora($concecutivo){
    global $db;  
    $dataQuery = array();
    $totalCuotasMora = "";
    
    $credito = $db->subQuery('cre');
    $credito->where('id_status_credito', array(1,3), "IN");    
    $credito->where('code_consecutivo_credito', $concecutivo);
    $credito->get('creditos_tbl', null, 'code_consecutivo_credito');

    //VALORES SIN MORA
    $db->join($credito, 'cre.code_consecutivo_credito = rec.ref_recaudo');     
    $db->where('id_status_recaudo', '1', '!=');     
    $db->where('activa_mora', '1');         
    $queryTbl = $db->getOne ("recaudos_tbl rec", "SUM(rec.valor_mora_cuota_recaudo) AS totalcuotasmora");
            
    //CALCULO DE VALOR TOTAL A RECAUDAR
    $totalCuotasMora = $queryTbl['totalcuotasmora'];            
    return $totalCuotasMora;
}

/*//--DINERO POR RECAUDAR SIN MORA--//*/
function queryDineroPorCobrar($concecutivo){
    global $db;  
    $dataQuery = array();
    $dineroporcobrar = "";
    /*$db->where('ref_recaudo', $concecutivo);        
    //$db->where('id_status_recaudo', '3'); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_cuota_plan_pago) AS totalcuotas, SUM(total_valor_recaudado_estacuota) AS totalrecaudos");
    
    $totalCuotas = $queryTbl['totalcuotas'];
    $totalRecaudos = $queryTbl['totalrecaudos'];
    
    $dineroporcobrar = $totalCuotas - $totalRecaudos;
    return $dineroporcobrar;*/
    $credito = $db->subQuery('cre');
    $credito->where('id_status_credito', array(1,3), "IN");    
    $credito->where('code_consecutivo_credito', $concecutivo);
    $credito->get('creditos_tbl', null, 'code_consecutivo_credito');

    //VALORES SIN MORA
    $db->join($credito, 'cre.code_consecutivo_credito = rec.ref_recaudo');     
    $db->where('id_status_recaudo', '1', '!=');     
    $queryTbl = $db->getOne ("recaudos_tbl rec", "SUM(rec.total_cuota_plan_pago) AS totalcuotas, SUM(rec.total_valor_recaudado_estacuota) AS totalrecaudos");
            
    //CALCULO DE VALOR TOTAL A RECAUDAR
    $totalCuotas = $queryTbl['totalcuotas'];
    $totalRecaudos = $queryTbl['totalrecaudos'];
    
    $dineroporcobrar = $totalCuotas - $totalRecaudos;
    return $dineroporcobrar;
}


/*//--GASTOS--//*/
function queryGastos($idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    $totalGastos = 0;
    
    $db->where('id_acreedor', $idAcreedor_);    
    $queryTbl = $db->getOne ("gastos_tbl", "SUM(total_valor_gastos) AS totalgastos");
    
    $totalGastos = $queryTbl['totalgastos'];    
    
    //$dineroporcobrar = $totalCuotas - $totalRecaudos;
    return $totalGastos;
}

//QUERY BALANCE GLOBAL
function queryBalanceGlobal($idAcreedor_){
    global $db;    
    $dataQuery = array();
    
    $dataValorCredito = array();
    $dataDineroCreditos = array();
    $dataDineroRecaudado = array();
    $porCobrar = array();
    $dataCuotasMora = array();
    $gastos = array();
        
    
    $db->where('id_acreedor', $idAcreedor_);        
    //$db->where('id_status_credito', '1');     
    $db->where('code_consecutivo_credito', '' , '!='); 
    $queryTbl = $db->get ("creditos_tbl", null, "id_creditos, id_status_credito, code_consecutivo_credito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {            
            $idCredito = $qKey['id_creditos'];
            $status_credito = $qKey['id_status_credito'];
            $concecutivo = $qKey['code_consecutivo_credito'];
            
            //CAPITAL PRESTADO
            $dataDineroCreditos = queryDineroCreditos($idCredito);
            
            //VALOR CREDITO
            $dataValorCredito = queryValorCreditos($idCredito);                                    
            //DINERO RECAUDADO
            $dataDineroRecaudado = queryDineroRecaudado($concecutivo);
            
            //POR COBRAR VALORES SIN MORA
            $porCobrar = queryDineroPorCobrar($concecutivo);
            
            //CUOTAS EN VALORES CON MORA
            $dataCuotasMora = queryDineroCuotasMora($concecutivo);
            
            //GASTOS 
            $gastos = queryGastos($idAcreedor_);
            
            $dataQuery[] = array(
                'idcredito' => $idCredito,
                'statuscredito' => $status_credito,
                'consecutivo' =>  $concecutivo,
                'datavalorcredito' => $dataValorCredito,
                'datadineroencredito' => $dataDineroCreditos,
                'datadinerorecaudado' => $dataDineroRecaudado,
                'dataporcobrar' => $porCobrar,
                'datacuotasmora' => $dataCuotasMora,
                'gastos' => $gastos
            );
            
            //$dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
    
    
}