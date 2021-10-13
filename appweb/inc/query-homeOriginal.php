<?php 
/*
*CONSULTA GLOBAL DE CREDITOS 
 'consulta los creditos generales en su estado POR PAGAR Y MORA
 'solo estos estados son los que se tienen en cuenta en todas las operaciones de REPORTE Y VALORES GLOBALES
*/

/*
*DETALLE OPERACIONES DEPENDIENDO ESTADO DE CREDITO
 'ESTADO 1 (POR COBRAR)
 'suma prstado, suma valor credito, suma por pagar, suma recaudad
**********
**********
 'ESTADO 3 (MORA)
 'suma prstado, suma valor credito, suma por pagar, suma recaudad
**********
**********
 'ESTADO 2 (PAGADO)
 'no suma nada
**********
**********
 'ESTADO 5 (DIFICIL CARTERA)
 'suma prestado, suma valor credito, no suma por pagar no suma recaudado, define un VALOR PERDIDO si existe un valor faltante del credito total
**********
**********
 'ESTADO 6 (CANCELADO)
 'suma prestado, suma valor credito, no suma por pagar no suma recaudado, define un VALOR PERDIDO si existe un valor faltante del credito total
**********
**********
 'ESTADO 7 (REFINANCIADO)
 'suma prestado, suma valor credito, no suma por pagar no suma recaudado
*/

/*$credito = $db->subQuery('cre');
$credito->where('id_status_credito', array(1,3), "IN");
$credito->get('creditos_tbl', null, 'code_consecutivo_credito');

$db->join($credito, 'cre.code_consecutivo_credito = rec.ref_recaudo');
$db->where('id_acreedor', $idSSUser);  
$db->where('fecha_max_recaudo', $dateFormatDB, "<="); 
$db->where('id_status_recaudo', '1', "!="); 
$quer_recaudos = $db->get("recaudos_tbl rec", null, "rec.ref_recaudo, rec.capital_cuota_recaudo, rec.total_cuota_plan_pago, rec.activa_mora, rec.valor_mora_cuota_recaudo, rec.total_valor_recaudado_estacuota, rec.valor_faltante_cuota, rec.valor_cuota_recaulcaldo_recaudos, rec.fecha_max_recaudo, rec.fecha_recaudo_realizado");
//echo "<pre>";
//print_r($quer_recaudos);
$total_recaudar_hoy = 0;
$total_recaudar_hoy_Format = 0;
if(is_array($quer_recaudos) && !empty($quer_recaudos)){
    foreach($quer_recaudos as $qrecKey){
        $valorCuotaHoy = $qrecKey['total_cuota_plan_pago'];   
        $valorCuotaRecalculadoHoy = $qrecKey['valor_cuota_recaulcaldo_recaudos'];
        $valorCuotaRecaudadoHoy = $qrecKey['total_valor_recaudado_estacuota'];
        $valorFaltanteCuotaHoy = $qrecKey['valor_faltante_cuota'];
        $activaMoraCuotaHoy = $qrecKey['activa_mora'];
        $valorMoraCuotaHoy = $qrecKey['valor_mora_cuota_recaudo'];
        
        $cuotaCobraMora = ($activaMoraCuotaHoy == 1)? $valorMoraCuotaHoy : 0;
                
        //RECALCULO PROXIMA CUOTA 
        $total_recaudar_hoy = $total_recaudar_hoy + $valorCuotaHoy + $cuotaCobraMora - $valorCuotaRecaudadoHoy;
        $total_recaudar_hoy_Format = number_format($total_recaudar_hoy, 0, ',', '.');    
    }
}*/
//echo $total_recaudar_hoy_Format;

/*
*++++++++++++++++++++++++++++++++++++++
*VERIFICAR CREDITOS EN MORA
*++++++++++++++++++++++++++++++++++++++
*/

function queryCuotasSeguimiento($refcredito_, $date_){
    global $db;
    $datasQuery = array();
    
    $db->where('ref_recaudo', $refcredito_);                
    $db->where('fecha_max_recaudo', $date_, "<=");
    $db->where('id_status_recaudo', "1", "!=");
    $queryTbl = $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, id_status_recaudo, ref_recaudo, numero_cuota_recaudos, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, activa_mora, valor_mora_cuota_recaudo, fecha_max_recaudo");/*, MAX(fecha_max_recaudo) as fechaultimacuota*/
    
    if(count($queryTbl) > 0){
        foreach($queryTbl as $qKey){
            $datasQuery[] = $qKey;
        }
    }
    return $datasQuery;
}

function queryFechaUltimoRecaudo($refcredito_, $date_){
    global $db;
    $datasQuery = "";//array();
    
    $db->where('ref_recaudo', $refcredito_);                
    $db->where('fecha_max_recaudo', $date_, "<=");
    $db->where('id_status_recaudo', "1", "!=");
    //$db->where('fecha_recaudo_realizado', "0000-00-00", "!=");
    $queryTbl = $db->getOne ("recaudos_tbl", "MAX(fecha_max_recaudo) as fechaultimacuota");
    
    if(count($queryTbl) > 0){
        $datasQuery = $queryTbl["fechaultimacuota"];
        /*foreach($queryTbl as $qKey){
            $datasQuery[] = $qKey;
        }*/
    }
    return $datasQuery;
}

$statusCreditoArray = array();    
$querySeguimientoCredito = array();
$db->where ("id_status_credito", array(2,5,6,7), "NOT IN");
$querySeguimientoCredito=$db->get("creditos_tbl", null, "code_consecutivo_credito, id_status_credito");
//print_r($querySeguimientoCredito);
if(is_array($querySeguimientoCredito) && !empty($querySeguimientoCredito)){
    foreach($querySeguimientoCredito as $qscKey){ 
        $consecutivo_credito = $qscKey['code_consecutivo_credito']; 
        $status_credito = $qscKey['id_status_credito'];
        
        $datasCuotas = queryCuotasSeguimiento($consecutivo_credito, $dateFormatDB);
        $real_fecha_ultimacuota = queryFechaUltimoRecaudo($consecutivo_credito, $dateFormatDB);
        
        $ultimacuotaarr = array();
        $real_valor_credito = 0;
        $real_valor_pagado_credito = 0;
        $real_numero_cuotas_debe = 0;
        $real_deuda_actual = 0;
        $real_max_acumlado_cuotas = 0; 
        $real_valor_acumulado_debe = 0;
        $numerodias = 0;
        $real_valor_cuota = 0;
        
        if(is_array($datasCuotas) && !empty($datasCuotas)){
            foreach($datasCuotas as $dcsKey){
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
        }
        
        /*
        *DEFNIICIONES DE UN CREDITO EN MORA
         ' por la fecha de la ultima cuota que debe supera dos dias calendario
         ' por el monto total que debe supera al valor de dos cuotas del credito
        */
                
        //diferencia de fechas         
        //permite conocer la fecha de la ultima cuota que debe, y si essa fecha supera DOS (2) dias, entrará en mora
        $fecha1 = new DateTime($real_fecha_ultimacuota);
        $fecha2 = new DateTime($dateFormatDB);
        $difffechas = $fecha1->diff($fecha2);
        $numerodias = $difffechas->d;        
        //echo "<br>\n".$numerodias;
        
        //define valor total del credito , que aun falta por pagar
        //$real_deuda_actual = $real_valor_credito - $real_valor_pagado_credito;
        
        //valor acumulado maximo de cuotas antes de mora
        $real_max_acumlado_cuotas = $real_valor_cuota*2;
        
        if(($real_valor_acumulado_debe > $real_max_acumlado_cuotas) || ($numerodias >2)){
            $nuevo_status_credito = 3;
        }else{
            $nuevo_status_credito = $status_credito;
        }
        
        //generamos el array para actualizar el status del credito
        $statusCreditoArray[] = array(
            "refcredito" => $consecutivo_credito,
            "statuscredito" => $nuevo_status_credito
        );
        
    }
}
//print_r($statusCreditoArray);
$updateStatusCredito = array();
if(is_array($statusCreditoArray) && !empty($statusCreditoArray)){
    foreach($statusCreditoArray as $uscKey){
        
        $ref_credito_tbl = $uscKey['refcredito'];
        
        $updateStatusCredito = array(
            'id_status_credito' => $uscKey['statuscredito']
        );

        $db->where("code_consecutivo_credito", $ref_credito_tbl);                             
        $updateCredito = $db->update('creditos_tbl', $updateStatusCredito);    
    }
}



/*
*++++++++++++++++++++++++++++++++++++++
*QUERY ESTADO CAJA -> TOTAL RECAUDO
*++++++++++++++++++++++++++++++++++++++
*/

/*//--ULTIMO RECAUDO--//*/
/*function queryUltimoRecaudo(){
    global $db;  
    $dataQuery = array();
         
    $db->where('id_status_recaudo', '3', '!=');//ESTADO QUE INDICA QUE LA CUOTA FUE PAGADA COMPLETA O ABONADA   MAX(id_recaudo) AS ultimorecaudo,  
    $db->where('fecha_recaudo_realizado', '0000-00-00', "!=");
    //$db->orWhere('id_recaudo', 'desc');
    $queryTbl = $db->getOne ("recaudos_tbl", "MAX(fecha_recaudo_realizado) AS fecharecaudo");
    
    $fechaRecaudo = $queryTbl['fecharecaudo'];
    return $fechaRecaudo;
       
}

$ultimoRecaudo = queryUltimoRecaudo();*/



/*//--VALOR DISPONIBLE--//*/
function queryValorDisponible($idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);
    $db->orderBy('fecha_cuadre_caja_menor', 'desc');
    $queryTbl = $db->get ("caja_menor_tbl", 1, "valor_disponible_caja_menor, fecha_cuadre_caja_menor, fecha_registro_cuadre_caja, actividad_caja_menor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
    
}

/*//--VALOR CAPITAL INYECTADO--//*/
function queryCapitalInyectado($idAcreedor_, $fechaValorDisponible_base_ = null){
    global $db;  
    $dataQuery = 0;//array();
    
    if($fechaValorDisponible_base_ != null){
        $db->where('fecha_inyeccion_capital', $fechaValorDisponible_base_, ">=");    
    }
    $db->where('id_acreedor', $idAcreedor_);
    //$db->orderBy('id_inyeccion_capital', 'desc');
    $queryTbl = $db->getOne ("inyeccion_capital_tbl", "SUM(valor_inyeccion_capital) AS valorinyectadoactual");
    
    if(count($queryTbl)>0){
        $dataQuery = $queryTbl["valorinyectadoactual"];
    }
    
    return $dataQuery;
    
    /*$rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }*/
    
}


/*//--FECHA CUADRE CAJA DIARIO OBLIGADO--//*/
function queryFechaCuadreDiario($idAcreedor_){
    global $db;  
    $fechaFinal = "0000-00-00";//array();
    
    $db->where('id_acreedor', $idAcreedor_);
    $db->where('status_caja_menor', '1');
    //$db->where('actividad_caja_menor', 'cuadre');
    $db->orderBy('fecha_cuadre_caja_menor', 'desc');      
    $queryTbl = $db->getOne ("caja_menor_tbl", "fecha_cuadre_caja_menor");
    
    if($queryTbl){
        $dataQuery = $queryTbl['fecha_cuadre_caja_menor'];
        //$masUnDia = strtotime ( '+1 day' , strtotime ( $dataQuery ) ) ;
        //$fechaFinal = date("Y-m-d", $masUnDia);        
        $fechaFinal = date("Y-m-d", strtotime ( $dataQuery .'+1 day'));        
    }    
    return $fechaFinal;    
}


/*//--FECHA PRIMER CREDITO CREADO EN EL SISTEMA--//*/
function queryFechaPrimerCredito(){
    global $db;  
    global $idSSUser;
    $dataQuery = "0000-00-00";//array();
    
    $db->where('id_acreedor', $idSSUser);    
    $db->orderBy('fecha_apertura_credito', 'asc');    
    $queryTbl = $db->getOne ("creditos_tbl", "fecha_apertura_credito");
    
    if($queryTbl){        
        $dataQuery = $queryTbl['fecha_apertura_credito'];        
    }    
    return $dataQuery;    
}

$fechaMiPrimerCredito = "";
$fechaMiPrimerCredito = queryFechaPrimerCredito();


//SELECT `id_recaudo`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recaudo`, `ref_recaudo`, `numero_cuota_recaudos`, `metodo_pago_recaudo`, `capital_cuota_recaudo`, `interes_cuota_recaudo`, `valor_mora_cuota_recaudo`, `sobrecargo_cuota_recaudo`, `total_cuota_plan_pago`, `total_valor_recaudado_estacuota`, `valor_faltante_cuota`, `valor_cuota_recaulcaldo_recaudos`, `fecha_max_recaudo`, `fecha_recaudo_realizado`, `comentarios_recaudo` FROM `recaudos_tbl` WHERE 1
//QUERY PLAN DE PAGOS

/*
*INGRESOS DIA
*/
function queryValorRecaudosFaltantes($fechaRecaudo_, $idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);
    $db->where('fecha_recaudo_realizado', $fechaRecaudo_);       
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

/*
*GASTOS DIA DE LA RUTA
 'indica los gastos que reportan los cobradores al realizar la ruta de recaudos
*/
    
function queryGastosRuta($fechaRecaudo_, $idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);
    $db->where('fecha_gastos', $fechaRecaudo_);
    $queryTbl = $db->getOne ("gastos_tbl", "SUM(total_valor_gastos) AS valorgastosruta");
    
    $valorGastosRuta = $queryTbl['valorgastosruta'];
    return $valorGastosRuta;
    
}
    

function queryRecaudosFaltantes($fechaRecaudo_, $idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);
    $db->where('fecha_recaudo_realizado', $fechaRecaudo_);
    $db->where('id_status_recaudo', "3", "!=");
    $db->orderBy("fecha_max_recaudo", "asc");
    $queryTbl = $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, id_status_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, comentarios_recaudo");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {  
            if(is_array($queryTbl) && !empty($queryTbl)){
                $dataQuery[] = $qKey;    
            }
            
        }    
        return $dataQuery;
    }
}

/*//--HITORICO STATUS DE CAJA--//*/
function queryStatusCuadre($idAcreedor_, $fechaCuadre_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);
    $db->where('fecha_cuadre_caja_menor', $fechaCuadre_);
    $queryTbl = $db->get ("caja_menor_tbl", 1, "fecha_cuadre_caja_menor, status_caja_menor, actividad_caja_menor");
    
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {  
            if(is_array($queryTbl) && !empty($queryTbl)){
                $dataQuery = $qKey;    
            }
            
        }    
        return $dataQuery;
    }
}



/*//--ULTIMO CUADRE DE CAJA--//*/
function queryUltimoCuadre($idAcreedor_){
    global $db;  
    $ultimoCuadre = "0000-00-00";
    
    $db->where('id_acreedor', $idAcreedor_);
    //$db->where('actividad_caja_menor', 'cuadre');
    $queryTbl = $db->getOne ("caja_menor_tbl", "MAX(fecha_cuadre_caja_menor) AS ultimocuadre");
    
    //if(count($queryTbl)>0 && $queryTbl != NULL){
    if(is_array($queryTbl) && !empty($queryTbl)){
        $ultimoCuadre = $queryTbl['ultimocuadre'];
        
   /* }else{
        return $ultimoCuadre;*/
    }
    return $ultimoCuadre;
}

/*
*INGRESOS DIARIOS DESDE MI PRIMER CREDITO A LA FECHA ACTUAL
*/
if($fechaMiPrimerCredito != "0000-00-00"){
    function queryIngresosDiarios(){
        //global $db;  
        global $idSSUser;
        global $dateFormatDB;
        global $fechaMiPrimerCredito;


        // Convirtiendo en timestamp las fechas
        $fechainicio = strtotime($fechaMiPrimerCredito);
        $fechafin = strtotime($dateFormatDB);

        // Incremento en 1 dia
        $diainc = 24*60*60;

        $dataIngresosDiarios = array();

        $numeDia = 1;
        // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
        for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {

            //NOMBRE DIA
            $diaFecha = date("l", $midia);

            if ($diaFecha=="Monday") $diaFecha="Lunes";
            if ($diaFecha=="Tuesday") $diaFecha="Martes";
            if ($diaFecha=="Wednesday") $diaFecha="Miércoles";
            if ($diaFecha=="Thursday") $diaFecha="Jueves";
            if ($diaFecha=="Friday") $diaFecha="Viernes";
            if ($diaFecha=="Saturday") $diaFecha="Sabado";
            if ($diaFecha=="Sunday") $diaFecha="Domingo";
                
            //STATUS CUADRE CAJA
            $arrayStatusCuadre = queryStatusCuadre($idSSUser, date('Y-m-d', $midia));

            //SE GENERA CONSULTA PARA RECAUDOS
            //$arrayRecaudosCuadrar = queryRecaudosFaltantes($fecha_falta_cuadre, $idSSUser);

            //VALOR DINERO RECAUDADO
            $arrayValorRecaudosFaltaCuadre = queryValorRecaudosFaltantes(date('Y-m-d', $midia), $idSSUser);
            
            //VALOR DINERO GASTADO EN LAS RUTAS
            $arrayValorGastosRutaFaltaCuadre = queryGastosRuta(date('Y-m-d', $midia), $idSSUser);

            //if(is_array($arrayRecaudosCuadrar) && !empty($arrayRecaudosCuadrar)){//-->**solo me mostraria los dias que tengan recaudos registrados
                $dataIngresosDiarios[] = array(         
                    'numedias' => $numeDia,
                    'dia' =>$diaFecha,
                    'fecha' => date('Y-m-d', $midia),
                    'datasstatuscuadre' => empty($arrayStatusCuadre) ? 0 : $arrayStatusCuadre,
                    //'datasrecaudo' =>$arrayRecaudosCuadrar,
                    'valortotalingresos' => empty($arrayValorRecaudosFaltaCuadre)? 0 : $arrayValorRecaudosFaltaCuadre,
                    'valortotalgastosruta' => empty($arrayValorGastosRutaFaltaCuadre)? 0 : $arrayValorGastosRutaFaltaCuadre,

                );
            //}
            $numeDia++;    
        }

        return $dataIngresosDiarios;
    }
}



//$ingresosDiarios = queryIngresosDiarios();
//print_r($ingresosDiarios);








////////////////////////////



//ESTABA ARRAY FECHAS RECAUDOS POR CUADRAR



/////////////////////////////////////














/*
*++++++++++++++++++++++++++++++++++++++
*QUERY DETALLES ACTIVIDAD
*++++++++++++++++++++++++++++++++++++++
*/


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
function queryCuotas($concecutivo_, $fechaActividad_){
    global $db;  
    $dataQuery = array();
    
    $db->where('ref_recaudo', $concecutivo_);
    $db->where('fecha_recaudo_realizado', $fechaActividad_);  /*$fechaActividad_*/
    //$db->orderBy("id_recaudo", "desc");
    $queryTbl = $db->get ("recaudos_tbl", null, "id_recaudo, activa_mora, id_status_recaudo, ref_recaudo, numero_cuota_recaudos, capital_cuota_recaudo, interes_cuota_recaudo, valor_mora_cuota_recaudo, sobrecargo_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, valor_cuota_recaulcaldo_recaudos, fecha_max_recaudo, fecha_recaudo_realizado, hora_recaudo_realizado, comentarios_recaudo");
        
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
function queryCreditosDetalles($idAcreedor_){
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
            
    $db->where('id_acreedor', $idAcreedor_);       
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
            $dataCuotas = queryCuotas($concecutivo, $dateFormatDB/*"2017-04-19"*/ ); // $dateFormatDB 
            
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
                'datacuotas' => $dataCuotas,
                'datadeudor' => $dataDeudor,
                //'datacodeudor' => $dataCoDeudor,
                'datacobrador' => $dataCobrador,
                /*'datasrefperso' => $dataRefPer,
                'datasreffami' => $dataRefFami,
                'datasrefcomer' => $dataRefComer,*/
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
    $dineroCreditos = 0;
    //$db->where('id_credito', $concecutivo);        
    //$db->where('id_status_recaudo', '3'); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    //$queryTbl = $db->getOne ("planes_pago_tbl", "valor_pagar_credito AS valorcredito");*/
    
    $credito = $db->subQuery('cre');
    //$planpago = $db->subQuery('pp');
    
    $credito->where('id_status_credito', array(1,3), "IN");    
    $credito->where('id_creditos', $concecutivo);
    $credito->get('creditos_tbl', 1, 'code_consecutivo_credito');
    
    //$planpago->join($credito, 'cre.id_creditos = pp.id_credito');   
    //$planpago->get("planes_pago_tbl pp", 1, "pp.id_plan_pago");
        
    $db->join($credito, 'cre.code_consecutivo_credito = rec.ref_recaudo');       
    $queryTbl = $db->getOne ("recaudos_tbl rec", "SUM(rec.total_cuota_plan_pago) AS totalcuotas");
    
    if(count($queryTbl)>0){
        $dineroCreditos = $queryTbl['totalcuotas'];
    }
        
    return $dineroCreditos; 
    
    
    /*global $db;  
    $dataQuery = array();
    $dineroCreditos = 0;
    $db->where('ref_recaudo', $concecutivo);
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_cuota_plan_pago) AS totalcuotas");
    
    if(count($queryTbl)>0){
        $dineroCreditos = $queryTbl['totalcuotas'];
    }
        
    return $dineroCreditos; */   
       
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
function queryDineroRecaudado(){//$concecutivo
    global $db;  
    global $idSSUser;
    $dataQuery = array();
    
    $valorrecaudado = 0;
    
    //$db->where('ref_recaudo', $concecutivo);         
    $db->where('id_acreedor', $idSSUser); 
    //$db->where('id_status_recaudo', '3', '!=');//ESTADO QUE INDICA QUE LA CUOTA FUE PAGADA COMPLETA O ABONADA  
    //$db->where('id_status_recaudo', '1');
    //$db->orWhere('id_status_recaudo', '2');
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_valor_recaudado_estacuota) AS valorrecaudado");
    
    if(count($queryTbl)>0){
        $valorrecaudado = $queryTbl['valorrecaudado'];    
    }
    
    return $valorrecaudado;
    /*$rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }*/
    
}

function queryDineroRecaudadoCredito($concecutivo){
    global $db;  
    global $idSSUser;
    $dataQuery = array();
    
    $valorrecaudado = 0;
    
    $db->where('ref_recaudo', $concecutivo);         
    $db->where('id_acreedor', $idSSUser); 
    $db->where('id_status_recaudo', '3', '!=');//ESTADO QUE INDICA QUE LA CUOTA FUE PAGADA COMPLETA O ABONADA  
    //$db->where('id_status_recaudo', '1');
    //$db->orWhere('id_status_recaudo', '2');
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_valor_recaudado_estacuota) AS valorrecaudado");
    
    if(count($queryTbl)>0){
        $valorrecaudado = $queryTbl['valorrecaudado'];    
    }
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
    $totalCuotasMora = 0;
        
    /*$db->where('ref_recaudo', $concecutivo);        
    $db->where('id_status_recaudo', '2');
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(valor_mora_cuota_recaudo) AS totalcuotasmora");
    
    if(count($queryTbl)>0){
        $totalCuotasMora = $queryTbl['totalcuotasmora'];    
    }
    
    return $totalCuotasMora;*/
    
    //global $db;  
    //$dataQuery = array();
    //$totalCuotasMora = "";
    
    $credito = $db->subQuery('cre');
    $credito->where('id_status_credito', array(1,3), "IN");    
    $credito->where('id_creditos', $concecutivo);
    $credito->get('creditos_tbl', 1, 'code_consecutivo_credito');

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
    $dineroporcobrar = 0;
    /*$db->where('ref_recaudo', $concecutivo);        
    //$db->where('id_status_recaudo', "2", "!="); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->getOne ("recaudos_tbl", "SUM(total_cuota_plan_pago) AS totalcuotas, SUM(total_valor_recaudado_estacuota) AS totalrecaudos");
    
    $totalCuotas = $queryTbl['totalcuotas'];
    $totalRecaudos = $queryTbl['totalrecaudos'];
    
    $dineroporcobrar = $totalCuotas - $totalRecaudos;
    return $dineroporcobrar;*/
    
    $credito = $db->subQuery('cre');
    $credito->where('id_status_credito', array(1,3), "IN");    
    $credito->where('id_creditos', $concecutivo);
    $credito->get('creditos_tbl', 1, 'code_consecutivo_credito');

    //VALORES SIN MORA
    $db->join($credito, 'cre.code_consecutivo_credito = rec.ref_recaudo');     
    $db->where('id_status_recaudo', '1', '!=');     
    $queryTbl = $db->getOne ("recaudos_tbl rec", "SUM(rec.total_cuota_plan_pago) AS totalcuotas, SUM(rec.total_valor_recaudado_estacuota) AS totalrecaudos");
    //$queryTbl = $db->get ("recaudos_tbl rec", null, "SUM(rec.total_cuota_plan_pago) AS totalcuotas, SUM(rec.total_valor_recaudado_estacuota) AS totalrecaudos");
            
    //CALCULO DE VALOR TOTAL A RECAUDAR
    $totalCuotas = 0;
    $totalRecaudos = 0;  
    /*if(is_array($queryTbl) && !empty($queryTbl)){
        foreach($queryTbl as $qkey){
            $totalCuotas = $qkey['totalcuotas'];
            $totalRecaudos = $qkey['totalrecaudos'];   
        }
    }    */
    
    $totalCuotas = $queryTbl['totalcuotas'];
    $totalRecaudos = $queryTbl['totalrecaudos'];
    
    $dineroporcobrar = $totalCuotas - $totalRecaudos;
    return $dineroporcobrar;
}


/*//--GASTOS--//*/
function queryGastos(){
    global $db;  
    global $idSSUser;
    $dataQuery = array();
    
    $totalGastos = 0;
    
    $db->where('id_acreedor', $idSSUser);    
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
    //$db->where('id_status_credito', array(1,3), "IN");    
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
            
            //DINERO RECAUDADO CREDITO
            //$dataDineroRecaudado = queryDineroRecaudadoCredito($concecutivo);
            
            //POR COBRAR VALORES SIN MORA
            $porCobrar = queryDineroPorCobrar($idCredito);
            
            //CUOTAS EN VALORES CON MORA
            $dataCuotasMora = queryDineroCuotasMora($idCredito);
            
            //GASTOS 
            //$gastos = queryGastos($idAcreedor_);
            
            $dataQuery[] = array(
                'idcredito' => $idCredito,
                'statuscredito' => $status_credito,
                'consecutivo' =>  $concecutivo,
                'datavalorcredito' => $dataValorCredito,
                'datadineroencredito' => $dataDineroCreditos,
                //'datadinerorecaudado' => $dataDineroRecaudado,
                'dataporcobrar' => $porCobrar,
                'datacuotasmora' => $dataCuotasMora,
                //'gastos' => $gastos
            );
            
            //$dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
    
    
}





