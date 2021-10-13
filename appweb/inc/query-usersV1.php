queryCuotas<?php
//QUERY DOCUMENTOS
function queryDocumentos($idAcreedor = null, $idUser_ = null, $idCodeudor_ = null, $idRefPerso_ = null, $idRefFami_ = null, $idRefComer_ = null){
    global $db;  
    $dataQuery = array();
    
    
    if(isset($idUser_) && $idUser_ != null){
        $db->where('id_deudor', $idUser_);            
    }
    if(isset($idCodeudor_) && $idCodeudor_ != null){
        $db->where('id_codeudor', $idCodeudor_);            
    }
    if(isset($idRefPerso_) && $idRefPerso_ != null){
        $db->where('id_referencia_personal', $idRefPerso_);            
    }
    if(isset($idRefFami_) && $idRefFami_ != null){
        $db->where('id_referencia_familiar', $idRefFami_);            
    }
    if(isset($idRefComer_) && $idRefComer_ != null){
        $db->where('id_referencia_comercial', $idRefComer_);            
    }
    if(isset($idAcreedor) && $idAcreedor != null){
        $db->where('id_acreedor', $idAcreedor);            
    }
    
    $queryTbl = $db->get ("documentos_requisitos_tbl", null, "referencia_documento_requisito, nombre_documento_requisito, file_documento_requisito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}


//QUERY ACREEDOR
function queryAcreedor($idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);        
    $queryTbl = $db->get ("acreedor_tbl", 1, "nickname_acreedor, codigo_geo_ciudad_acreedor, codigo_geo_estado_acreedor, codigo_geo_pais_acreedor, fecha_alta_acreedor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
}

//QUERY DEUDOR
function queryDeudor($idDeudor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_deudor', $idDeudor_);        
    $queryTbl = $db->get ("deudor_tbl", 1, "id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, ciudad_domicilio_deudor, estado_domicilio_deudor, dir_geo_deudor, cedula_deudor, email_deudor, tel_uno_deudor, tel_dos_deudor");
        
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
    $queryTbl = $db->get ("recaudos_tbl", null, "id_recaudo, id_status_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, activa_mora, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, comentarios_recaudo");
        
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
    $dataQuery = array();
    
    $db->where('id_status', $idStatusCredito_);        
    $queryTbl = $db->get ("status_credito_tbl", 1, "nombre_status");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery = $qKey;
        }    
        return $dataQuery;
    }
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


//DETALLES CRFEDITOS DEL USUARIO DEUDOR
function queryDeudorCreditos($idDeudor_){
    global $db;    
    $dataQuery = array();
        
    $dataDeudor = array();    
    $dataStatus = array();
    $dataTipoCredito = array();
    $datasAcreedor = array();
    $dataCuotas = array();
    
    
    $db->where('id_deudor', $idDeudor_);    
    $queryTbl = $db->get ("creditos_tbl", null, "id_creditos, id_acreedor, id_deudor, id_status_credito, code_consecutivo_credito, tipo_credito, fecha_apertura_credito, fecha_cierre_definitivo_credito");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){
        
        foreach ($queryTbl as $qKey) {
            
            $idCredito = $qKey['id_creditos'];
            $idAcreedor = $qKey['id_acreedor'];
            $idDeudor = $qKey['id_deudor'];
            $idStatusCredito = $qKey['id_status_credito'];
            $concecutivo = $qKey['code_consecutivo_credito'];
            $idtipoCredito = $qKey['tipo_credito'];
            $fechaAbreCredito = $qKey['fecha_apertura_credito'];
            $fechaCierraCredito = $qKey['fecha_cierre_definitivo_credito'];
                                    
            //SOBRE EL ACREEDOR
            $datasAcreedor = queryAcreedor($idAcreedor);
                                                            
            //SOBRE LAS CUOTAS / RECAUDOS
            $dataCuotas = queryCuotas($concecutivo);
            
            $dataQuery[] = array(
                'idcredito' => $idCredito,
                'consecutivo' =>  $concecutivo,
                'dataAcreedor' => $datasAcreedor,                
                'datastatus' => $idStatusCredito,
                'datatipocredito' => $idtipoCredito,
                'fechaabre' => $fechaAbreCredito,                
                'fechacierra' => $fechaCierraCredito,
                'datascuotas' => $dataCuotas
            );
            
            //$dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}


//DETALLES DEL USUARIO DEUDOR
function queryDeudorDetalles($idDeudor_){
    global $db;  
    $dataQuery = array();
    $datasCreditos = array();
    $datasDocumentos = array();
    
    $db->where('id_deudor', $idDeudor_);        
//    $db->where('id_status_perfil_deduor', "5", "!=");        
    $queryTbl = $db->get("deudor_tbl");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) { 
            
            //CREDITOS DE ESTE DEUDOR
            //$id_item = $qKey['id_deudor'];
            $datasCreditos = queryDeudorCreditos($qKey['id_deudor']);
            
            //DOCUMENTOS
            $datasDocumentos = queryDocumentos(null, $qKey['id_deudor']);
                                    
            $dataQuery = $qKey;
            $dataQuery["datascreditos"] = $datasCreditos;
            $dataQuery["datasdocumentos"] = $datasDocumentos;
        }    
        return $dataQuery;
    }
}

//QUERY CREDITOS DETALLES
function queryCreditosDetalles($idAcreedor_, $idCredito_){
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
    $db->where('id_creditos', $idCredito_);                   
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
            $dataCoDeudor = queryCoDeudor($idCoDeudor);
            
            //SOBRE REFERENCIA FAMILIAR
            $dataRefFami = queryRefFami($idRefFami);
            
            //SOBRE REFERENCIA PERSONAL
            $dataRefPer = queryRefPer($idRefPerso);
            
            //SOBRE REFERENCIA COMERCIAL
            $dataRefComer = queryRefComer($idRefComer);
            
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