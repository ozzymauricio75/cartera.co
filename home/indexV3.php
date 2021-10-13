<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-home.php'; ?>
<?php require_once '../i18n-textsite.php'; ?>

<?php

//recibe datos de PEDIDOS
$idAcreedor = $idSSUser;





/**
 **DEFINE ACTIVIDAD CAJA MENOR
 ' esto permite determinar los valores de dinero disponible
 ' diferencia si se va a cuadrar caja por primera vez
 ' si es cuadre de caja por primera vez, toma el valor disponible del capital inicial - los valores prestados + los ingresos - los gastos
 'si el cuadre de caja es del día dos en adelante, el valor disponible es definido por el valor disponible en caja menor + los ingresos - los gastos
*/
$queryActividadCaja = array();
$actividadCaja = "";
$db->where('id_acreedor', $idAcreedor);
$db->orderBy("id_caja_meno", "desc");
$queryActividadCaja = $db->getOne ("caja_menor_tbl", "actividad_caja_menor");

if(count($queryActividadCaja > 0)){
    $actividadCaja = (string)$queryActividadCaja['actividad_caja_menor'];
}


/*
*ULTIMO CUADRE DE CAJA REALIZADO
*/
$fechaActivaCuadreCaja_diario = "";
$fechaActivaCuadreCaja_diario = queryFechaCuadreDiario($idAcreedor);

//echo "cuadre mas un dia" .$fechaActivaCuadreCaja_diario;



/*//--DIFERENCIA ENTRE ULTIMO CUADRE Y FECHA ACTUAL--//*/
$ultimoCuadre = queryUltimoCuadre($idSSUser);

if($ultimoCuadre == "0000-00-00" || empty($ultimoCuadre)){
    $ultimoCuadre = $dateFormatDB;
}


/*
*INGRESOS DIARIOS GRID
*/
//echo "<br>ultimo cuadre " .$ultimoCuadre;
$total_diff = 0;
$datetime1 = new DateTime($dateFormatDB);  //-->fecha hoy 
$datetime2 = new DateTime($ultimoCuadre);   //-->fecha ultimo cuadre de caja menor
$interval = $datetime1->diff($datetime2);  //-->diferencia entre las dos fechas
$total_diff =  $interval->format('%a');

//echo "<br>diferencia " .$total_diff;

//layout ingresos diarios
$cuadra_menor_lyt = "";
$ingresostatus_disabled_array = array();
$ingresostatus_enabled_array = array();
//$status_input_cuadre = "<script>$('.ingresocheckbox).iCheck('disable');</script>";
$status_input_cuadre = "";

if($fechaMiPrimerCredito != "0000-00-00"){
    $dataCuadreCaja = array();
    $dataCuadreCaja = queryIngresosDiarios();
    
    
    //print_r($dataCuadreCaja);
    //sksort($grupoCategoriasUsuario, "fecha", false);

    if(is_array($dataCuadreCaja) && !empty($dataCuadreCaja)){
        foreach($dataCuadreCaja as $cuadreKey){
            $numeDia_Cuadre = $cuadreKey['numedias'];
            $dia_Cuadre = $cuadreKey['dia'];
            $fecha_ingreso = $cuadreKey['fecha'];
            $fecha_Cuadre = date("d/m/Y", strtotime($cuadreKey['fecha']));
            $datasStatus_cuadre = $cuadreKey['datasstatuscuadre'];
            //$datasRecaudo_NoCuadre = $cuadreKey['datasrecaudo'];
            $valor_NoCuadre = $cuadreKey['valortotalingresos'];
            $valor_NoCuadre_format = number_format($valor_NoCuadre, 0,",","."); 

            $valor_noCuadre_gastos_ruta = $cuadreKey['valortotalgastosruta'];
            $valor_noCuadre_gastos_ruta_format = number_format($valor_noCuadre_gastos_ruta, 0,",","."); 

            //IDENTIFICA STATUS DE CUADRE CAJA
            //*muestra solo aquellas fechas que no se realizo cuadre
            $statusCuadreIngreos = 0;
            $actividad_cuadre = "";
            if(is_array($datasStatus_cuadre) && !empty($datasStatus_cuadre)){
                foreach($datasStatus_cuadre as $scKey){
                    $fechaStatus_cuadre = $datasStatus_cuadre['fecha_cuadre_caja_menor'];
                    $idStatus_cuadre = $datasStatus_cuadre['status_caja_menor']; 
                    $actividad_cuadre = $datasStatus_cuadre['actividad_caja_menor'];
                    $statusCuadreIngreos = $idStatus_cuadre;

                }
            }

            //echo "estado cuadre caja " .$idStatus_cuadre;

            /*
            *DEJAR TODOS LOS INPUTS[RADIO] DESACTIVADOS
            *CONSULTAR Y TRAER LA ULTIMA FECHA CON CUADRE REALIZADO
            *A ESA FECHA SUMARLE UN DIA
            *COMPARAR LA FECHA RESULTANTE CON LAS DEL ACTUAL FOREACH (FECHAS DE CUADRECAJAS NO REALIZAADAS)
            *LA QUE SEA IGUAL PONERLE ESTADO ACTIVADO
            */
            //echo "<br>fecha ingreso =".$fecha_ingreso;

            $ingresoCheck = "#ingresocheck".$numeDia_Cuadre;
            
            
            //si esta es la primera vez que se cuadra caja sólo activamos el primer dia de ingresos                    
            if($actividadCaja == "inicial" && $cuadre_diario == "on"){
                
                if($numeDia_Cuadre == 1){
                    array_push($ingresostatus_enabled_array, $ingresoCheck);     
                }else{
                    array_push($ingresostatus_disabled_array, $ingresoCheck);     
                }
                //array_push($ingresostatus_enabled_array, "#ingresocheck1"); 
                //$ingresostatus_enabled_array = array("#ingresocheck1");
            }else if($cuadre_diario == "on" /*&& $actividad_cuadre == "cuadre"*/){
                /*if(is_array($datasStatus_cuadre) && !empty($datasStatus_cuadre)){
                    foreach($datasStatus_cuadre as $scKey){
                        $fechaStatus_cuadre = $datasStatus_cuadre['fecha_cuadre_caja_menor'];
                        $idStatus_cuadre = $datasStatus_cuadre['status_caja_menor'];


                        if($fechaActivaCuadreCaja_diario == $fechaStatus_cuadre ){ $status_input_cuadre == ""; }
                    }
                }*/
                //$status_input_cuadre = "disabled";


                //fecha actual
                if($total_diff == 0){
                    $status_input_cuadre = "enable";  
                    //array_push($ingresostatus_array, $ingresoCheck);         
                    //$icheck_ingreso = $ingresoCheck;
                    if($fecha_ingreso == $fechaActivaCuadreCaja_diario){
                        $status_input_cuadre = "enable";    
                        //array_push($ingresostatus_array, $ingresoCheck);   
                        //$icheck_ingreso = $ingresoCheck;
                        array_push($ingresostatus_enabled_array, $ingresoCheck); 
                    }else{
                        array_push($ingresostatus_disabled_array, $ingresoCheck); 
                    }                    
                }

                //fecha de ultimo cuadre fue ayer
                elseif($total_diff == 1){
                    $status_input_cuadre = "enable";
                    //array_push($ingresostatus_array, $ingresoCheck); 
                    //$icheck_ingreso = $ingresoCheck;
                    if($fecha_ingreso == $fechaActivaCuadreCaja_diario){
                        $status_input_cuadre = "enable";    
                        //array_push($ingresostatus_array, $ingresoCheck);   
                        //$icheck_ingreso = $ingresoCheck;
                        array_push($ingresostatus_enabled_array, $ingresoCheck); 
                    }else{
                        array_push($ingresostatus_disabled_array, $ingresoCheck); 
                    }                    
                }

                //tiene mas de un dia atrasado sin realizar cuadre de caja
                elseif($total_diff > 1){

                    if($fecha_ingreso == $fechaActivaCuadreCaja_diario){
                        $status_input_cuadre = "enable";    
                        //array_push($ingresostatus_array, $ingresoCheck);   
                        //$icheck_ingreso = $ingresoCheck;
                        array_push($ingresostatus_enabled_array, $ingresoCheck); 
                    }else{
                        array_push($ingresostatus_disabled_array, $ingresoCheck); 
                    }                    

                }


            }else if($cuadre_diario == "off"){

                array_push($ingresostatus_enabled_array, $ingresoCheck);     

            }

            $class_ingreso = "";
            if($statusCuadreIngreos == 0){ 
                $class_ingreso = "show"; 
            }elseif($statusCuadreIngreos == 1){ 
                $class_ingreso = "hide"; 
            }
            //if($idStatus_cuadre == 0 || empty($idStatus_cuadre)){

            $cuadra_menor_lyt .='<div class="col-xs-6 margin-bottom-xs '.$class_ingreso.'">
                <div class="box box-info">                    
                    <div class="box-body box-profile">
                        <div class="margin-bottom-xs">
                            <p >
                                Ingresos
                                <span class="badge bg-blue text-size-x4 pull-right">
                                    <span class="margin-right-xs">$</span> 
                                    '.$valor_NoCuadre_format.'
                                </span>
                            </p>
                            <p >
                                Gastos
                                <span class="badge bg-red text-size-x4 pull-right">
                                    <span class="margin-right-xs">$</span> 
                                    '.$valor_noCuadre_gastos_ruta_format.'
                                </span>
                            </p>
                            <input type="hidden" name="recaudoacumulado" value="'.$valor_NoCuadre.'">
                        </div>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <p>Día
                                    <strong class="pull-right">'.$dia_Cuadre.'</strong>
                                </p>
                            </li>
                            <li class="list-group-item">
                                <p>Fecha
                                    <strong class="pull-right">'.$fecha_Cuadre.'</strong>
                                </p>
                            </li>                            
                        </ul>
                        <div class="text-center">
                            <label>
                                <input type="radio" name="fechacuadrecaja" value="ok" class="flat-red ingresocheckbox" id="ingresocheck'.$numeDia_Cuadre.'" data-valoracumulado="'.$valor_NoCuadre.'" data-valoracumuladoformat="'.$valor_NoCuadre_format.'" data-valorgastosrutas="'.$valor_noCuadre_gastos_ruta.'" data-fechaingreso="'.$fecha_ingreso.'" '.$status_input_cuadre.' >
                                <span class="margin-left-xs text-size-x3">Seleccionar</span>
                            </label>
                        </div>
                    </div>                    
                </div>
                </div>';

            //}//[FIN $idStatus_cuadre]  flat-red

        }
    }
    

}

/*print_r($ingresostatus_disabled_array);
print_r($ingresostatus_enabled_array);

if(is_array($ingresostatus_array) && !empty($ingresostatus_array)){
    foreach($ingresostatus_array as $isKey){
        echo $isKey.", ";
    }
}*/


/*
*BALANCE GENERAL
*/
$dataBalanceGeneral = array();
$dataBalanceGeneral = queryBalanceGlobal($idAcreedor);

$valorCreditoArr = 0;
$dineroPrestadoArr =0;
$dineroRecaudadoArr =0;
$dineroCobrarArr =0;
$dineroCuotasMoraArr = 0;

$valorCredito = 0;
$totalDineroPrestado = 0;
$totalDineroRecaudado = 0;
$totalDineroCobrar = 0;
$dineroPrestadoArr = 0;
$gastosArr = 0;
$total_gastos = 0;
//echo "<pre>";
//print_r($dataBalanceGeneral);

if(is_array($dataBalanceGeneral) && !empty($dataBalanceGeneral)){
    foreach($dataBalanceGeneral as $bgKey){
        $dineroPrestadoArr += $bgKey['datadineroencredito'];
        $valorCreditoArr +=  $bgKey['datavalorcredito'];        
        $dineroRecaudadoArr += $bgKey['datadinerorecaudado'];
        $dineroCobrarArr += $bgKey['dataporcobrar'];
        $dineroCuotasMoraArr += $bgKey['datacuotasmora'];
        $gastosArr = $bgKey['gastos'];
        
        $valorCredito = number_format($valorCreditoArr, 0,",","."); 
        $totalDineroPrestado = number_format($dineroPrestadoArr, 0,",","."); 
        $totalDineroRecaudado = number_format($dineroRecaudadoArr, 0,",","."); 
        //$totalDineroCobrar = number_format($dineroCobrarArr, 0,",","."); 
        $valorACobrar = $dineroCobrarArr + $dineroCuotasMoraArr;//($valorCreditoArr + $dineroCuotasMoraArr) - $dineroRecaudadoArr;
        $totalDineroCobrar = number_format($valorACobrar, 0,",","."); 
        $total_gastos = number_format($gastosArr, 0,",",".");         
    }
}


/*
*VALOR DISPONIBLE CAJA MENOR
*/
$valorDisponible = 0;
$valorDisponibleCajaMenor = 0;
$fechaValorDisponible = "0000-00-00";
$datasValoDisponible = array();
$datasValoDisponible = queryValorDisponible($idAcreedor);

$queryPrestadoActual = array();
$valor_prestado_actual=0;
$capitalInyectado = 0;
    
if(is_array($datasValoDisponible) && !empty($datasValoDisponible)){
    foreach($datasValoDisponible as $vdKey){
        $valorDisponibleCajaMenor = $vdKey['valor_disponible_caja_menor'];
        $valorDisponible = number_format($vdKey['valor_disponible_caja_menor'], 0,",","."); 
        $fechaValorDisponible_base = $vdKey['fecha_registro_cuadre_caja'];
        $actividad_caja_menor = $vdKey['actividad_caja_menor'];
        $fechaValorDisponible = date("d/m/Y", strtotime($vdKey['fecha_registro_cuadre_caja']));
    }
    
    if($actividad_caja_menor == "cuadre"){
        /*
        *DINERO PRESTADO DESPUES FECHA CUADRE
        */

        if($fechaValorDisponible_base != "0000-00-00"){

            $db->where("id_acreedor",$idAcreedor);
            $db->where("fecha_inicio_plan_pago",$fechaValorDisponible_base, ">=");
            $queryPrestadoActual = $db->getOne("planes_pago_tbl", "SUM(valor_credito_plan_pago) AS valorprestadoactual");
            if(count($queryPrestadoActual)>0){
                $valor_prestado_actual=$queryPrestadoActual['valorprestadoactual'];
            }
        }

        /*
        *VALOR CAPITAL INYECTADO DESPUES FECHA CUADRE
        */

        $capitalInyectado = queryCapitalInyectado($idAcreedor, $fechaValorDisponible_base);
        
    }else{
        
        $valor_prestado_actual = $dineroPrestadoArr;
        $capitalInyectado = queryCapitalInyectado($idAcreedor);    
    }

}

/*
*TOTAL DINERO DISPONIBLE PARA PRESTAR
*/
$totalDisponiblePrestar = 0;
$totalDisponiblePrestar = ($valorDisponibleCajaMenor - $valor_prestado_actual/*$dineroPrestadoArr*/) + $capitalInyectado;
$totalDisponiblePrestarFormat = number_format($totalDisponiblePrestar, 0,",","."); 

/*
*DEFINE VALOR DISPONIBLE PARA CUADRAR CAJA
*/
$valor_disponible_cuadre_caja = $totalDisponiblePrestar;

/*
*TOTAL DINERO A RECAUDAR HOY
*/
//TOTAL DINERO A RECAUDAR
function queryValorRecaudosRutaHoy($idAcreedor_, $fechaRecaudo_){
    global $db;  
    $dataQuery = array();
    
    $db->where('id_acreedor', $idAcreedor_);    
    $db->where('fecha_max_recaudo', $fechaRecaudo_, "<=");    
    $db->where('id_status_recaudo', '1', "!="); //ESTADO QUE INDICA QUE LA CUOTA AUN NO SE HA PAGADO     
    $queryTbl = $db->get ("recaudos_tbl", null, "ref_recaudo, capital_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota, activa_mora, valor_mora_cuota_recaudo");
    
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
       
}

$dineroRutaHoy = array();
$dineroRutaHoy = queryValorRecaudosRutaHoy($idSSUser, $dateFormatDB);

$total_capital_ruta_hoy = 0;
$total_utilidad_ruta_hoy = 0;
$totalDineroRutaHoy = 0;
$totalDineroRutaHoyFormat = 0;
$cuotaCobraMora = 0;
if(is_array($dineroRutaHoy) && !empty($dineroRutaHoy)){
    foreach($dineroRutaHoy as $drhKey){ 
        $valorCapitalCuotaHoy = $drhKey['capital_cuota_recaudo']; 
        $valorCuotaHoy = $drhKey['total_cuota_plan_pago'];           
        $valorCuotaRecaudadoHoy = $drhKey['total_valor_recaudado_estacuota'];
        $activaMoraCuotaHoy = $drhKey['activa_mora'];
        $valorMoraCuotaHoy = $drhKey['valor_mora_cuota_recaudo'];
        $refcredito = $drhKey['ref_recaudo'];
        
        $status_credito = queryStatusCredito($refcredito);
                        
        if($status_credito == 1 || $status_credito == 3){
            $cuotaCobraMora = ($activaMoraCuotaHoy == 1)? $valorMoraCuotaHoy : 0;                
            //calculo dinero a cobrar hoy
            $totalDineroRutaHoy = $totalDineroRutaHoy + $valorCuotaHoy + $cuotaCobraMora - $valorCuotaRecaudadoHoy;
            $totalDineroRutaHoyFormat = number_format($totalDineroRutaHoy, 0, ',', '.');    
            
            //calculo dinero abonar capital para hoy
            $total_capital_ruta_hoy = $total_capital_ruta_hoy + $valorCapitalCuotaHoy;
            
            //calculo dinero abonar a utilidad/interes para hoy
            $total_utilidad_ruta_hoy = ($totalDineroRutaHoy - $total_capital_ruta_hoy);
            
        }
        
    }
}

//guardar valores del recaudo actual en tabla valores cobrar dia (item informativo para reporte cuadre de caja)
$datainsert_valorescobrartbl = array(
    "id_acreedor" =>$idSSUser,    
    "dinero_recaudar_hoy" => $totalDineroRutaHoy,
    "dinero_capital_hoy" => $total_capital_ruta_hoy,
    "dinero_interes_hoy" => $total_utilidad_ruta_hoy,
    "fecha_valores_cobrar_dia" => $dateFormatDB
);
//echo "<pre>";
//print_r($datainsert_valorescobrartbl);
//SELECT `id_valores_cobrar_dia`, `id_acreedor`, `dinero_recaudar_hoy`, `dinero_capital_hoy`, `dinero_interes_hoy`, `fecha_valores_cobrar_dia` FROM `valores_cobrar_dia` WHERE 1

$errInsertDatas_valcobrar = "";
$err_insert_valcobrar_lyt = "";        
//consultamos si ya fue insertado este registro el dia actual
$db->where("fecha_valores_cobrar_dia", $dateFormatDB);
$qvalorescobrar = $db->getOne("valores_cobrar_dia", "id_valores_cobrar_dia");
if(empty($qvalorescobrar)){
    $db->insert("valores_cobrar_dia", $datainsert_valorescobrartbl);
    $errInsertDatas_valcobrar = $db->getLastErrno();
}

if($errInsertDatas_valcobrar != ""){    
    $err_insert_valcobrar_lyt .="<p><b>*** Algo salio mal ***</b>
        <br>Wrong: <b>Ocurrio un error cuando se intentaba guardar los registros de cobros para el día de hoy</b>
        <br>Erro: ".$errInsertDatas_valcobrar."
        <br>Verifica la conexion de internet,
        <br>Intenta nuevamente recargando esta página (presiona F5), o dale click al boton <b><i class='fa fa-home'></i> HOME</b>, intenta cerrar session e ingresar a tu cuenta nuevamente
        <br>Si este error continua, por favor entre en contacto con soporte</p>";    
}

/*
*TOTAL DINERO PERDIDO
 'dinero de cuotas pertenecientes a creditos q han sido definidos como CANCELADOS o DIFICIL CARTERA, las cuotas faltantes a estos creditos se totalizan y se definin como dinero perdido
*/
$total_dinero_perdido = 0;
$total_dinero_perdido = queryDineroPerdido();
$total_dinero_perdido_format = number_format($total_dinero_perdido, 0, ',', '.');    

/*
*LAYOUT ACTIVIDAD DIARIA RECAUDOS
*/
$datasCredito = array();
$datasCredito = queryCreditosDetalles($idAcreedor);
$datas_Cuotas = array();
$actividad_lyt = "";
//echo "<pre>";
//print_r($datasCredito);

if(is_array($datasCredito) && !empty($datasCredito)){
    foreach($datasCredito as $dcKey){
        $id_Credito = $dcKey['idcredito'];
        $consecutivo_Credito = $dcKey['consecutivo'];
        $datas_PlanPago = $dcKey['dataplanpago'];
        $datas_Cuotas = $dcKey['datacuotas'];
        //$datas_Recaudos = $dcKey['datacuotas']; 
        $datas_Deudor = $dcKey['datadeudor'];
        $datas_Cobrador = $dcKey['datacobrador'];
        /*$datas_CoDeudor = $dcKey['datacodeudor'];                
        $datas_RefPerso = $dcKey['datasrefperso'];
        $datas_RefFami = $dcKey['datasreffami'];
        $datas_RefComer = $dcKey['datasrefcomer'];*/
        $datas_Status = $dcKey['datastatus'];
        $datas_TipoCredito = $dcKey['datatipocredito'];
        $fecha_InicioCredito = date("d/m/y", strtotime($dcKey['fechaabrecredito']));
        $hora_InicioCredito = $dcKey['horaabrecredito'];
        $fecha_FinCredito = $dcKey['fechaterminacredito'];
        $descri_Credito = $dcKey['descricredito'];
                
        //TIPO DE CREDITO
        if(is_array($datas_TipoCredito) && !empty($datas_TipoCredito)){
            foreach($datas_TipoCredito as $tcKey){
                $tipo_credito = $datas_TipoCredito['nombre_tipo_credito'];
            }
        }

        //SOBRE EL DEUDOR
        if(is_array($datas_Deudor) && !empty($datas_Deudor)){
            foreach($datas_Deudor as $deuKey){
                $primernombre_deudor = isset($datas_Deudor['primer_nombre_deudor'])? $datas_Deudor['primer_nombre_deudor'] : "";                
                $segundonombre_deudor = isset($datas_Deudor['segundo_nombre_deudor'])? $datas_Deudor['segundo_nombre_deudor'] : "";   
                $primerapellido_deudor = isset($datas_Deudor['primer_apellido_deudor'])? $datas_Deudor['primer_apellido_deudor'] : "";    
                $segundoapellido_deudor = isset($datas_Deudor['segundo_apellido_deudor'])? $datas_Deudor['segundo_apellido_deudor'] : "";
                $nombrecompleto_deudor = $primernombre_deudor."&nbsp;".$segundonombre_deudor."&nbsp;".$primerapellido_deudor."&nbsp;".$segundoapellido_deudor;                       
                $dirgeo_deudor = isset($datas_Deudor['dir_geo_deudor'])? $datas_Deudor['dir_geo_deudor'] : "";
                $email_deudor = isset($datas_Deudor['email_deudor'])? $datas_Deudor['email_deudor'] : "";
                $cedula_deudor = isset($datas_Deudor['cedula_deudor'])? $datas_Deudor['cedula_deudor'] : "";   
                $tel1_deudor = isset($datas_Deudor['tel_uno_deudor'])? $datas_Deudor['tel_uno_deudor'] : "";
                $tel2_deudor = isset($datas_Deudor['tel_uno_deudor'])? $datas_Deudor['tel_dos_deudor'] : "";
            }

        }


        //COBRADOR                
        if(is_array($datas_Cobrador) && !empty($datas_Cobrador)){
            foreach($datas_Cobrador as $dcKey){
                $nombre_cobrador = isset($datas_Cobrador['nombre_cobrador'])? $datas_Cobrador['nombre_cobrador'] : "";                                            
                $email_cobrador = isset($datas_Cobrador['mail_cobrador'])? $datas_Cobrador['mail_cobrador'] : "";
                //$cedula_refcomer = isset($datas_Cobrador['cedula_referencia_comercial'])? $datas_Cobrador['cedula_referencia_comercial'] : "";
                $tel1_cobrador = isset($datas_Cobrador['tel_uno_cobrador'])? $datas_Cobrador['tel_uno_cobrador'] : "";
                $tel2_cobrador = isset($datas_Cobrador['tel_dos_cobrador'])? $datas_Cobrador['tel_dos_cobrador'] : "";   

            }
        }

        //PLAN DE PAGOS
        if(is_array($datas_PlanPago) && !empty($datas_PlanPago)){
            foreach($datas_PlanPago as $dppKey){                        
                $periocidadPlazo = $datas_PlanPago['periocidad_plan_pago'];
                $valorPrestado = $datas_PlanPago['valor_credito_plan_pago'];
                $valorPagar = $datas_PlanPago['valor_pagar_credito'];
                $utilidad = number_format($datas_PlanPago['utilidad_credito'],0,",",".");
                $numeroCuotas = $datas_PlanPago['numero_cuotas_plan_pago'];
                $plazoDias = $datas_PlanPago['plazocredito_plan_pago'];
                $fechaPrimeraCuota = date("d/m/Y",strtotime($datas_PlanPago['fecha_inicio_plan_pago']));
                $fechaUltimaCuota = date("d/m/Y",strtotime($datas_PlanPago['fecha_fin_plan_pago']));
                $capitalCuota = $datas_PlanPago['capital_cuota_plan_pago'];
                $valorCuota = number_format($datas_PlanPago['valor_cuota_plan_pago'],0,",",".");  
                $valorMoraCuota = number_format($datas_PlanPago['valor_mora_plan_pago'],0,",",".");  

            }
        }
        
        //SOBRE EL RECAUDO DE CADA CUOTA
        if(is_array($datas_Cuotas) && !empty($datas_Cuotas)){ 
            foreach($datas_Cuotas as $dcKey){                                   
                $cuota_status = $dcKey['id_status_recaudo'];
                $cuota_activamora = $dcKey['activa_mora'];
                $cuota_numero = $dcKey['numero_cuota_recaudos'];
                $cuota_capital = number_format($dcKey['capital_cuota_recaudo'],0,",","."); 
                $cuota_interes = $dcKey['interes_cuota_recaudo'];
                $cuota_mora_base = $dcKey['valor_mora_cuota_recaudo'];                        
                $cuota_mora = number_format($dcKey['valor_mora_cuota_recaudo'],0,",",".");                        
                $cuota_sobrecargo = $dcKey['sobrecargo_cuota_recaudo'];
                $cuota_valor_cuota_base = $dcKey['total_cuota_plan_pago'];                        
                $cuota_valor_cuota = number_format($dcKey['total_cuota_plan_pago'],0,",",".");                 
                $cuota_valor_recaudado_base = $dcKey['total_valor_recaudado_estacuota'];
                $cuota_valor_recaudado = number_format($dcKey['total_valor_recaudado_estacuota'],0,",",".");
                $cuota_valor_faltante = $dcKey['valor_faltante_cuota'];//number_format($dcKey['valor_faltante_cuota'] , 0,",",".");
                $cuota_valor_recaulculado = number_format($dcKey['valor_cuota_recaulcaldo_recaudos'],0,",",".");
                $cuota_fecha_recaudo = date("d/m/Y",strtotime($dcKey['fecha_max_recaudo']));
                $cuota_fecha_recaudo_realizado = date("d/m/Y",strtotime($dcKey['fecha_recaudo_realizado']));
                $cuota_hora_recaudo_realizado = date("h:i a", strtotime($dcKey['hora_recaudo_realizado']));
                $cuota_comentarios = $dcKey['comentarios_recaudo']; 
                
                //valor faltante de la cuota
                $valor_faltante = $cuota_valor_cuota_base - $cuota_valor_recaudado_base;
                $valor_faltanteFormat = number_format($valor_faltante,0,",",".");
                
                //valor faltante acumulado cuota + mora
                $acumulado_faltante = 0;
                if($cuota_activamora == 1){
                    $acumulado_faltante = $cuota_valor_cuota_base - $cuota_valor_recaudado_base + $cuota_mora_base;
                }else{
                    $acumulado_faltante = $cuota_valor_cuota_base - $cuota_valor_recaudado_base;
                }
                $acumulado_faltanteFormat = number_format($acumulado_faltante,0,",",".");
                
                //FECHA PAGO CUOTA RECIBIDO
                $fecha_pago_recibido = "" ;
                if($cuota_fecha_recaudo_realizado == "0000-00-00"){
                    $fecha_pago_recibido = "-";
                }else{
                    $fecha_pago_recibido = date("d/m/Y",strtotime($cuota_fecha_recaudo_realizado));
                }
                
                //RECALCULO PROXIMA CUOTA
                $valor_cuota_final = 0;
                if($cuota_valor_recaulculado != 0){
                    $valor_cuota_final = $cuota_valor_recaulculado;    
                }else{
                    $valor_cuota_final = $cuota_valor_cuota; 
                }


                //DEFINE STATUS CUOTA
                $status_lyt = "";
                switch($cuota_status){
                    case "1":
                        $status_lyt = "<span class='badge bg-green padd-hori-xs text-size-x2'>Pagado</span>";
                    break;
                    case "2":
                        $status_lyt ="<span class='badge bg-orange padd-hori-xs text-size-x2'>Abono</span>";
                    break;
                    case "3":
                        $status_lyt ="<span class='badge bg-gray padd-hori-xs text-size-x2'>Por cobrar</span>";
                    break;
                }

                //DEFINE STATUS MORA
                $mora_lyt = "";
                $valor_mora = 0;
                switch($cuota_activamora){
                    case "1":
                        $mora_lyt ="<span class='badge bg-green padd-hori-xs text-size-x2'>On</span>";
                        $valor_mora = $cuota_mora;
                    break;            
                    case "0":
                        $mora_lyt ="<span class='badge bg-gray padd-hori-xs text-size-x2'>Off</span>";
                        $valor_mora = 0;
                    break;
                }
                
                //LATOUT ACTIVIDAD
                
                
                $actividad_lyt .= '<div class="col-xs-12 col-sm-12 margin-bottom-xs">';
                //<!-- Box Comment -->
                $actividad_lyt .= '<div class="box box-widget">';
                
                $actividad_lyt .= '<div class="box-header with-border">';
                
                $actividad_lyt .= '<div class="user-block">';
                $actividad_lyt .= '        <i class="fa fa-motorcycle fa-2x margin-top-xs"></i>';
                $actividad_lyt .= '        <span class="username"><a href="#">'.$nombre_cobrador.'</a></span>';
                //$actividad_lyt .= '        <span class="description">'.$cuota_fecha_recaudo_realizado.'</span>';
                $actividad_lyt .= '        <span class="description">'.$cuota_fecha_recaudo_realizado.' - '.$cuota_hora_recaudo_realizado.'</span>';
                $actividad_lyt .= '    </div>';
                //<!-- /.user-block -->
                $actividad_lyt .= '    <div class="box-tools">';
                $actividad_lyt .= '        <button type="button" class="btn btn-info godetails" data-id="'.$id_Credito.'">';
                $actividad_lyt .= '            <i class="fa fa-external-link margin-right-xs"></i>';
                $actividad_lyt .= '            Más detalles';
                $actividad_lyt .= '        </button>';  
                
                //$layoutDataItem .= "<button type='button' class='btn btn-info godetails' data-id='".$idItem."'>Más detalles</button>";
                
                $actividad_lyt .= '    </div>';
                    //<!-- /.box-tools -->
                $actividad_lyt .= '</div>';
                //<!-- /.box-header -->

                //<!-- /.box-body -->
                $actividad_lyt .= '<div class="box-body box-comments">';
                $actividad_lyt .= '  <div class="box-comment">';        
                $actividad_lyt .= '    <i class="fa fa-archive fa-lg" ></i>';
                $actividad_lyt .= '    <div class="comment-text">';
                
                //        <!---
                //            DETALLES CREDITO
                //        -->
                
                $actividad_lyt .= '        <div class="row margin-bottom-xs">';
                //lado izquierdo
                $actividad_lyt .= '            <div class="col-xs-12 col-sm-6 ">';
                $actividad_lyt .= '             <p class="text-size-x5">'.$consecutivo_Credito.'</p>';
                $actividad_lyt .= '        <dl class="dl-horizontal-custom">';
                $actividad_lyt .= '            <dt>Deudor:</dt>';
                $actividad_lyt .= '            <dd class="text-size-x4">'.$nombrecompleto_deudor.'</dd>';
                $actividad_lyt .= '            <dt>Tel 1:</dt>';
                $actividad_lyt .= '            <dd class="text-size-x4">'.$tel1_deudor.'</dd>';
                $actividad_lyt .= '            <dt>Tel 2:</dt>';
                $actividad_lyt .= '            <dd class="text-size-x4">'.$tel2_deudor.'</dd>';
                $actividad_lyt .= '        </dl>';
                $actividad_lyt .= '            </div>';
                //[fin | lado izquierdo]
                
                
                //lado derecho
                $actividad_lyt .= '            <div class="col-xs-12 col-sm-6 ">';
                $actividad_lyt .= '             <p>';
                $actividad_lyt .= '             <strong class="margin-right-xs"># cuota</strong>';
                $actividad_lyt .= '             <span class="text-size-x4 pull-right">'.$cuota_numero.'</span>';
                $actividad_lyt .= '             </p>';
                
                $actividad_lyt .= '             <p>';
                $actividad_lyt .= '             <strong class="margin-right-xs">Status cuota</strong>';
                $actividad_lyt .= '             <span class="text-size-x4 pull-right">'.$status_lyt.'</span>';
                $actividad_lyt .= '             </p>';
                
                $actividad_lyt .= '             <p>';
                $actividad_lyt .= '             <strong class="margin-right-xs">Mora</strong>';
                $actividad_lyt .= '             <span class="text-size-x4 pull-right">'.$mora_lyt.'</span>';
                $actividad_lyt .= '             </p>';
                
                $actividad_lyt .= '             <p>';
                $actividad_lyt .= '             <strong class="margin-right-xs">Valor cuota pagar:</strong>';
                $actividad_lyt .= '             <span class="text-size-x4 pull-right">$&nbsp;&nbsp;'.$valor_cuota_final.'</span>';
                $actividad_lyt .= '             </p>';
                
                $actividad_lyt .= '             <p>';
                $actividad_lyt .= '             <strong class="margin-right-xs">Valor mora:</strong>';
                $actividad_lyt .= '             <span class="text-size-x4 pull-right">$&nbsp;&nbsp;'.$valor_mora.'</span>';
                $actividad_lyt .= '             </p>';
                
                $actividad_lyt .= '             <p>';
                $actividad_lyt .= '             <strong class="margin-right-xs">Valor recaudado:</strong>';
                $actividad_lyt .= '             <span class="text-size-x4 pull-right">$&nbsp;&nbsp;'.$cuota_valor_recaudado.'</span>';
                $actividad_lyt .= '             </p>';
                
                $actividad_lyt .= '             <p class="bg-danger">';
                $actividad_lyt .= '             <strong class="margin-right-xs">Faltante cuota:</strong>';
                $actividad_lyt .= '             <span class="text-size-x4 pull-right">$&nbsp;&nbsp;'.$valor_faltanteFormat.'</span>';
                $actividad_lyt .= '             </p>';
                
                if($cuota_activamora == 1){
                $actividad_lyt .= '             <p class="bg-danger">';
                $actividad_lyt .= '             <strong class="margin-right-xs">Faltante total:</strong>';
                $actividad_lyt .= '             <span class="text-size-x4 pull-right">$&nbsp;&nbsp;'.$acumulado_faltanteFormat.'</span>';
                $actividad_lyt .= '             </p>';
                }
                $actividad_lyt .= '            </div>';
                //[fin | lado derecho]
                $actividad_lyt .= '            </div>';
                
                //        <!---
                //            COMENTARIOS
                //        -->
                $actividad_lyt .= '        <div class="row margin-bottom-xs">';
                $actividad_lyt .= '            <div class="col-xs-12 col-sm-12">';
                $actividad_lyt .= '        <span class="username">';
                $actividad_lyt .= '            Comentarios recaudo';
                $actividad_lyt .= '        </span>';//<!-- /.username -->
                $actividad_lyt .= '          <p class="text-size-x3">'.$cuota_comentarios.'</p>';
                //$actividad_lyt .= '        <span class="text-muted pull-right margin-top-xs">8:03 PM Today</span>';
                $actividad_lyt .= '    </div>';
                $actividad_lyt .= '            </div>';
                $actividad_lyt .= '        </div>';
                //    <!-- /.comment-text -->
                $actividad_lyt .= '  </div>';                          
                $actividad_lyt .= '</div>';

                $actividad_lyt .= '</div>';
                //<!-- /.box -->
                $actividad_lyt .= '</div>';//col                                                                       
            }
        }


    }//FIN[$datasCredito]
            
}//FIN ARRAY [$datasCredito]



//***********
//SITE MAP
//***********

$rootLevel = "home";
$sectionLevel = "";
$subSectionLevel = "";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo METATITLE ?></title>    
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="author" content="massin">
    <?php echo _CSSFILESLAYOUT_ ?>    
    <link rel="stylesheet" href="../appweb/plugins/form-validator/theme-default.css"> 
    <link rel="stylesheet" type="text/css" href="../appweb/plugins/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="../appweb/plugins/slick/slick-theme.css"/>
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../appweb/plugins/iCheck/all.css">
    <!---switchmaster--->    
    <link rel="stylesheet" href="../appweb/plugins/switchmaster/css/bootstrap3/bootstrap-switch.min.css">
    <?php echo _FAVICON_TOUCH_ ?>    
</head>
    
<?php echo LAYOUTOPTION ?><!---//print body tag--->    
<?php include '../appweb/tmplt/loadevent.php';  ?>
    
<div class="wrapper">            
    <?php
    /*
    /
    ////HEADER
    /
    */
    ?>
    <?php include '../appweb/tmplt/header.php';  ?>           
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php include '../appweb/tmplt/side-mm.php';  ?>
    <?php
    /*
    /
    ////WRAP CONTENT
    /
    */
    ?>        
    <div class="content-wrapper">
        
        
        
        <?php
        /*
        /*****************************//*****************************
        /HEADER SECCION
        /*****************************//*****************************
        */
        ?>
        <div class="content-header header-multi-seccion">
            <h2>
                Estado de caja
                <small class="margin-left-xs">
                    Actualizado
                    <span class="badge bg-green margin-left-xs"><?php echo $fechaValorDisponible;  ?></span>
                </small>
                <div class="pull-right">                    
                    <button class="btn btn-info" type="button" data-toggle="modal" data-target="#cuadracaja">
                        <i class="fa fa-calculator fa-lg margin-right-xs"></i>
                        Calcular
                    </button>
                </div>
            </h2>
        </div>
        <?php
        /*
        *SI EXISTE ERROR CUANDO SE INSERTA LOS VALORES DE RECAUDOS PARA HOY
        */
        if($err_insert_valcobrar_lyt != ""){ ?>
        <section class="maxwidth-layout">
            <div class="box50">
                <div class="alert bg-danger text-danger"><?php echo $err_insert_valcobrar_lyt; ?></div>
            </div>
        </section>
            
        <?php } ?>
        <section class="maxwidth-layout">            
            <div class="row">
                 
                <div class="col-xs-12 col-sm-6">
                    <div class="info-box untopdowlmargin">
                        <span class="info-box-icon bg-green inyectabtn" data-toggle="modal" data-target="#inyectmodal">
                            <i class="fa fa-thermometer-half"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Disponibles</span>
                            <span class="info-box-number text-size-x7">
                                <span class=" text-size-x8 text-green" >$</span>
                                <?php echo $totalDisponiblePrestarFormat;//$valorDisponible;  ?>
                            </span>
                            
                        </div>   
                        
                    </div>
                </div> 
                <div class="col-xs-12 col-sm-6">
                    <div class="info-box untopdowlmargin">
                        <span class="info-box-icon bg-red">
                            <i class="fa fa-motorcycle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Cobrar hoy</span>
                            <span class="info-box-number text-size-x7  text-red">
                                <span class=" text-size-x8" >$</span>
                                <?php echo $totalDineroRutaHoyFormat;//$valorDisponible;  ?>
                            </span>
                            
                        </div>   
                        
                    </div>
                </div> 
            </div>
        </section>
                
        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        <div class="content-header header-multi-seccion">
            <h2>Balance global</h2>
        </div>
        <section class="maxwidth-layout margin-bottom-md">
            
            <div class="row text-center">
                
                <div class="col-xs-12 col-sm-6">
                    <div class=" box box-info">
                        <div class="box-body">
                            <a href="<?php echo $pathmm."/report/_planespago/"; ?>" type="button" >
                                <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                                    <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$totalDineroPrestado; ?></strong>
                                </div>
                            </a>
                            <h3>Prestado</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class=" box box-info">
                        <div class="box-body">
                            <a href="<?php echo $pathmm."/report/_credito/"; ?>" type="button" >
                                <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                                    <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$valorCredito; ?></strong>
                                </div>
                            </a>
                            <h3>Creditos</h3>
                        </div>
                    </div>
                </div>
                <div class="clearfix margin-verti-md hidden-xs"></div>
                <div class="col-xs-12 col-sm-6">
                    <div class=" box box-info">
                        <div class="box-body">
                            <a href="<?php echo $pathmm."/report/_historico-recaudos/"; ?>" type="button" >
                                <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                                    <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$totalDineroRecaudado; ?></strong>
                                </div>
                            </a>
                            <h3>Recaudado</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class=" box box-info">
                        <div class="box-body">
                            <a href="<?php echo $pathmm."/report/_recaudos/"; ?>" type="button" >
                                <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                                    <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$totalDineroCobrar; ?></strong>
                                </div>
                            </a>
                            <h3>Por cobrar</h3>
                        </div>
                    </div>
                </div>
                
                <div class="clearfix margin-verti-md hidden-xs"></div>
                
                <div class="col-xs-6">
                    <div class=" box box-info box50">
                        <div class="box-body">
                            <a href="<?php echo $pathmm."/report/_escapados/"; ?>" type="button" >
                                <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                                    <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$total_dinero_perdido_format; ?></strong>
                                </div>
                            </a>
                            <h3>Dinero perdido</h3>
                        </div>
                    </div>
                </div>                
                
                <div class="col-xs-6">
                    <div class=" box box-info box50">
                        <div class="box-body">
                            <a href="<?php echo $pathmm."/report/_gastos/"; ?>" type="button" >
                                <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                                    <strong class="fa-2x"><?php echo "<span class='margin-right-xs'>$</span>".$total_gastos; ?></strong>
                                </div>
                            </a>
                            <h3>Gastos</h3>
                        </div>
                    </div>
                </div>                
            </div>
        </section>
        
        
        <?php
        /*
        /*****************************//*****************************
        /HEADER SECCION
        /*****************************//*****************************
        */
        ?>
        <div class="content-header header-multi-seccion">                       
            <h2>Actividad</h2>                        
            <p class="help-block no-padmarg">Te permite dar seguimiento de los recaudos realizados para la fecha actual</p>
        </div>

        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <?php if($actividad_lyt!= ""){ ?>
        <section class="maxwidth-layout padd-bottom-md">
            <div class="row">
                <?php echo $actividad_lyt; ?>
            </div>
        </section>    
        
        <?php }else{ ?>
        
        <section class="content ">                    
            <div class="box50">
                <div class="callout">
                    <div class="media">
                        <div class=" media-left padd-hori-xs">
                            <i class="fa fa-list-alt fa-4x text-muted"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Oops!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                No encontramos acividad el día de hoy
                            </p>
                        </div>
                    </div>                    
                </div>                
            </div>
        </section>
        
        <?php } ?>
        
        
        
        
    </div>
    <?php 
    /*
    *FIN CONTENT-WRAPPER
    */
    ?>
    
    <!-- Modal -->
    <div class="modal fade" id="inyectmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                
                <div class="modal-body no-padmarg bg-gray">
                    <form id="newinyeccionform" autocomplete="off" >
                        <div class="wrapp-introslider">
                            <div class=" wrapitemhw padd-verti-md padd-hori-md bg-gray">                                    
                                <div class="headeritemhw">
                                    <h4>Inyeccion de capital</h4>                                        
                                </div>
                                <div class="bodyitemhw  ">                                        
                                    <p>                                        
                                        Escribe el capital que deseas inyectarle a tu negocio. Este valor se reflejará en el total disponible para tu actividad financiera
                                    </p>
                                    
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon" >$</span>
                                            <input type="number" id="inyectacapitalinput" name="inyectacapitalinput" class="form-control" value ="" placeholder="0" >                                                 
                                        </div>
                                    </div>
                                    <div class="form-group text-center" id="newinyeccion">
                                        <button id="" type="button" class="btn btn-flat btn-default btn-sm" data-dismiss="modal">
                                            <i class="fa fa-times fa-lg margin-right-xs"></i>
                                            Cancelar                                            
                                        </button>
                                        <button id="newinyeccionbtn" type="button" class="btn btn-flat btn-primary btn-sm" data-field="newinyeccion" data-user="<?php echo $idSSUser; ?>">
                                            <i class="fa fa-save fa-lg margin-right-xs"></i>
                                            Guardar
                                        </button>
                                    </div>
                                    <div id="responcenewinyeccion"></div>                      
                                    <div id="errnewinyeccion"></div>                      
                                </div>                               
                            </div>
                        </div>
                    </form>
                </div>
               
            </div>
        </div>
    </div>
    
    
    
    
    
    <div class="modal fade" id="cuadracaja" tabindex="-1" role="dialog" aria-labelledby="cuadracajaLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="cuadracajaLabel">Cuadrar caja</h4>
                </div>
                <?php if($cuadra_menor_lyt != ""){ ?> 
                <div class="modal-body grey lighten-4">
                                 
                    <form id="cuadreform" method="post">
                        <div class="form-group">
                            <div class="row pre-scrollable-md"><?php echo $cuadra_menor_lyt; ?></div>     
                        </div>
                        <!--<div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="recaudoscaja">$</span>
                                <input id="totalrecaudosformat" name="totalrecaudosformat" type="text" class="form-control" placeholder="Recaudos" aria-describedby="recaudoscaja" disabled>
                                <input id="totalingresosinput" name="totalingresosinput" type="hidden">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="gastoscaja">$</span>
                                <input id="cantgastosinput" name="cantgastosinput" type="number" class="form-control justnumber" placeholder="Gastos" aria-describedby="gastoscaja">
                            </div>
                        </div>
                        
                        <!--<div class="form-group"> 
                            <label for="message-text" class="control-label">Descripción:</label>
                            <textarea class="form-control" id="message-text" rows="10"></textarea>
                        </div>-->
                        <div class="form-group">
                            <label for="message-text" class="control-label">Descripción:</label>
                            <textarea id="descricuadreinput" name="descricuadreinput" class="form-control" placeholder="Detalles o comentarios para este cuadre de caja" style="width: 100%; height: 120px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize:none;"></textarea>
                        </div>
                        <input id="codeusercaja" name="codeusercaja" value="<?php echo $idSSUser; ?>" type="hidden"/>  
                        <input id="valordisponibleactual" name="valordisponibleactual" value="<?php echo $valor_disponible_cuadre_caja; ?>" type="hidden"/>  
                        <input id="totalingresosinput" name="totalingresosinput" type="hidden">
                        <input id="totalgastosinput" name="totalgastosinput" type="hidden">
                        <input id="fechacuadreinput" name="fechacuadreinput" type="hidden">
                    </form>
                </div>
                <div id="errnewcuadrecaja"></div>
                <div class="modal-footer" id="wrapadditem">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="guardacuadre" type="button" class="btn btn-primary"><i class="fa fa-save fa-lg margin-right-xs"></i>Guardar</button>
                </div>
                
                <?php }else{ ?> 
                <div class="modal-body no-padmarg bg-info">
                    
                    <div class="padd-verti-md">
                        <div class="media">
                            <div class=" media-left padd-hori-md">
                                <i class="fa fa-info-circle fa-4x text-blue"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="no-padmarg">Hola</h3>
                                <p style="font-size:1.232em; line-height:1;">
                                    Podrás cuadrar caja, a partir del momento que realices tu primer credito
                                </p>                                                  
                            </div>
                        </div>                    
                    </div>                        
                                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>                    
                </div>
                <?php } ?> 
            </div>
        </div>
    </div>
    
    <?php if($status_confi_user == 0){ ?>
                          
    <div class="modal" id="howwork" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                
                <div class="modal-body no-padmarg bg-gray">
                    <form id="userform" autocomplete="off" >
                    <div class="wrapp-introslider">   
                        <div class="intro-slider">
                        
                            <div>                                        
                                <div class=" wrapitemhw padd-verti-md  bg-light-blue">
                                    <div class="bodyitemhw welcomeitem">
                                        <div class="row text-center">
                                            <div class="col-xs-12  margin-bottom-xs">
                                                <p class="text-center">
                                                    <i class="fa fa-shekel"  style="font-size:120px;"></i>    
                                                </p>
                                                <h3 class="welcome-title">Bienvenido a CARTERA</h3>              
                                                <p class="welcome-subtitle">Conoce la vida crediticia de tus clientes desde cualquier dispositivo</p>
                                            </div>
                                            <div class="crearfix"></div>
                                            <div class="col-xs-12 col-sm-4">
                                                <i class="fa fa-archive "></i>
                                                <h4 class="">Crea y administra prestamos</h4>                    
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <i class="fa fa-search "></i>
                                                <h4 class="">Consulta el perfil de deudores</h4>
                                                <p></p>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <i class="fa fa-address-book-o "></i>
                                                <h4 class="">Seguimiento y control de recaudos</h4>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wrapparrows text-center margin-verti-md">
                                        <button class="prev hide" type="button">
                                            <i class="fa fa-chevron-left"></i>
                                        </button>
                                        <button class="next" type="button">
                                            <i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                            <div>
                                <div class=" wrapitemhw padd-verti-md bg-gray">
                                    <div class="headeritemhw">
                                        <h4>Ya casi estamos listos!</h4>
                                        <small class="callout call-default" style="color:#888888;">Necesitamos algunos datos básicos para configurar tu sistema. 
                                            No te preocupes, esta información la puedes editar desde tu área de preferencias, presionando 
                                            <i class="fa fa-gear fa-lg margin-hori-xs"></i> en el menú lateral 
                                        </small>
                                    </div>
                                    <div class="bodyitemhw  "> 
                                        <p><i class="fa fa-gear fa-lg margin-right-xs"></i>Indica tu jornada laboral. Esto permitirá configurar las fechas de cobro a tus clientes</p>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class=" item-habiles">
                                                    <strong class="text-primary padd-bottom-xs">Fechas de cobro diarias</strong>
                                                    <p>
                                                        <span class="pull-right">
                                                            <input type="checkbox" data-size="mini" class="opcicustom " checked disabled>
                                                        </span>
                                                        <label>Lunes a Viernes</label>
                                                            
                                                    </p>
                                                    <p>
                                                        <span class="pull-right">
                                                            <input type="checkbox" data-size="mini" class="opcicustom " id="sabadodiaswitch" >
                                                        </span>
                                                        <label>Sabados</label>
                                                        
                                                    </p>
                                                    <p>
                                                        <span class="pull-right">
                                                            <input type="checkbox" data-size="mini" class="opcicustom " id="domingodiaswitch">
                                                        </span>
                                                        <label>Domingos</label>
                                                        
                                                    </p>
                                                    <p>
                                                        <span class="pull-right">
                                                            <input type="checkbox" data-size="mini" class="opcicustom " id="festivodiaswitch">
                                                        </span>
                                                        <label>Festivos</label>
                                                        
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xs-6">
                                                <div class="  item-habiles">
                                                    <strong class="text-primary padd-bottom-xs hidden-xs">Fechas de cobro Semanales/Quincenales/Mensuales</strong>
                                                    <strong class="text-primary padd-bottom-xs visible-xs">fechas de cobro Semana<br>Quincena/Mes</strong>
                                                    <p>
                                                        <span class="pull-right">
                                                            <input type="checkbox" data-size="mini" class="opcicustom " checked disabled>
                                                        </span>
                                                        <label>Lunes a Viernes</label>
                                                            
                                                    </p>
                                                    <p>
                                                        <span class="pull-right">
                                                            <input type="checkbox" data-size="mini" class="opcicustom " id="sabadoswitch">
                                                        </span>
                                                        <label>Sabados</label>
                                                        
                                                    </p>
                                                    <p>
                                                        <span class="pull-right">
                                                            <input type="checkbox" data-size="mini" class="opcicustom " id="domingoswitch">
                                                        </span>
                                                        <label>Domingos</label>
                                                        
                                                    </p>
                                                    <p>
                                                        <span class="pull-right">
                                                            <input type="checkbox" data-size="mini" class="opcicustom " id="festivoswitch">
                                                        </span>
                                                        <label>Festivos</label>
                                                        
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                    <div class="wrapparrows text-center margin-verti-md bg-gray">
                                        <button class="prev" type="button">
                                            <i class="fa fa-chevron-left"></i>
                                        </button>
                                        <button class="next" type="button">
                                            <i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div>
                                <div class=" wrapitemhw padd-verti-md bg-gray">                                    
                                    <div class="headeritemhw">
                                        <h4>Caja menor</h4>                                        
                                    </div>
                                    <div class="bodyitemhw  ">                                        
                                        <p><i class="fa fa-gear fa-lg margin-right-xs"></i><strong>Recomendado!</strong> Permitirá llevar un control diario y obligatorio sobre la gestion de tus prestamos y dinero disponible</p>
                                        <p>
                                            <span class="pull-right">
                                                <input type="checkbox" data-size="mini" class="opcicustom " id="cajaswitch" checked>
                                            </span>
                                            <label>Cuadrar caja diariamente</label>                                            
                                        </p>
                                    </div>
                                    <div class="wrapparrows text-center margin-verti-md">
                                        <button class="prev" type="button">
                                            <i class="fa fa-chevron-left"></i>
                                        </button>
                                        <button class="next" type="button">
                                            <i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div>
                                <div class=" wrapitemhw padd-verti-md bg-gray">                                    
                                    <div class="headeritemhw">
                                        <h4>Capital inicial</h4>                                        
                                    </div>
                                    <div class="bodyitemhw  ">                                        
                                        <p><i class="fa fa-gear fa-lg margin-right-xs"></i>Indica el capital que destinarás para empezar tu negocio. Esto permitirá al sistema definir de cuanto dinero dispones para prestar a tus clientes</p>
                                        <p class="row">
                                        <small for="nombre1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Puedes hacerlo luego desde tu area de preferencias</small>
                                        </p>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon" >$</span>
                                                <input type="number" id="capitalinicialform" name="capitalinicialform" class="form-control" value ="" placeholder="0" >                                                 
                                            </div>
                                        </div>
                                        <div class="form-group text-right" id="customuser">
                                            <button id="closecustombtn" type="button" class="btn btn-flat bg-green btn-sm" data-dismiss="modal">Ir a plataforma<i class="fa fa-chevron-right fa-1x margin-left-xs"></i></button>
                                            <button id="customuserbtn" type="button" class="btn btn-flat btn-primary btn-lg btn-block" data-field="customuser">Guardar</button>
                                        </div>
                                        <div id="responcecustomuser"></div>                      
                                        <div id="errcustomuser"></div>                      
                                    </div>
                                    <div id="lastarrowshw" class="wrapparrows text-center margin-verti-md">
                                        <button class="prev" type="button">
                                            <i class="fa fa-chevron-left"></i>
                                        </button>
                                        <button class="next  hide" type="button">
                                            <i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        
                            
                        </div>
                        
                    </div>
                    
                    <input id="codeuserform" name="codeuserform" value="<?php echo $idSSUser; ?>" type="hidden"/>    
                    <input id="introcustomform" name="introcustomform" value="intocustomuser" type="hidden"/>
                    <input id="sabadodiaform" name="sabadodiaform" type="hidden"/>    
                    <input id="domingodiaform" name="domingodiaform" type="hidden"/>    
                    <input id="festivodiaform" name="festivodiaform" type="hidden"/>    
                    <input id="sabadoform" name="sabadoform" type="hidden"/>    
                    <input id="domingoform" name="domingoform" type="hidden"/>    
                    <input id="festivoform" name="festivoform" type="hidden"/>    
                    <input id="cajaform" name="cajaform" type="hidden" value="ok"/>    
                    
                    </form>
                </div>
               
            </div>
        </div>
    </div>
    
    <?php }//[FIN | capital inciial] ?>
    
    <?php echo "<input id='paththisfile' type='hidden' value='".$pathmm."/credits/details/'/>"; ?>               
    <?php
    /*
    /
    ////FOOTER
    /
    */
    ?>
    <?php include '../appweb/tmplt/footer.php';  ?>
    <?php
    /*
    /
    ////RIGHT BAR
    /
    */
    ?>
    <?php //include '../appweb/tmplt/right-side.php';  ?>
</div>
<?php echo _JSFILESLAYOUT_ ?>
        
<!-- validacion datos -->      
<script type="text/javascript" src="../appweb/plugins/form-validator/jquery.form-validator.min.js"></script> 
<script type="text/javascript" src="../appweb/js/to-userform.js"></script>
<script type="text/javascript" src="edit-introcustom-functions.js"></script>
    
<script src="../appweb/plugins/misc/jquery.redirect.js"></script>   
<script type="text/javascript" src="../appweb/plugins/slick/slick.js" ></script>    
    
<!-- iCheck 1.0.1 -->
<script src="../appweb/plugins/iCheck/icheck.min.js"></script>

<!---switchmaster---> 
<script src="../appweb/plugins/switchmaster/js/bootstrap-switch.min.js" type="text/javascript"></script>
    
<script type="text/javascript">                                               
    //como funcona
    $(document).ready(function(){
        $('.intro-slider').slick({
            prevArrow: '.wrapp-introslider .prev',
            nextArrow: '.wrapp-introslider .next',            
            dots: false,
            infinite: false,
            speed: 500,
            fade: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: true,
        });
    });
</script>    
<script type="text/javascript">
    //Flat red color scheme for iCheck
    $(function () {                  
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-red',
          radioClass: 'iradio_flat-red'
        });
        
        //$('.ingresocheckbox').iCheck('disable');
    });
    
    $(function() { 
        $("#cuadracaja").modal({ 
            show: false 
        }); 
        setTimeout(function (){ 
            $("#cantgastos").focus(); 
        }, 1000); 
    });  
    
    /*$(function() { 
        $("#detallevalores").modal({ 
            show: false 
        }); 
    }); */
                  
    $(document).ready(function(){
        var detailurl = $("#paththisfile").val();

        $('button.godetails').each(function(){ 

            var itemid = $(this).attr("data-id");

            $(this).click(function(){                   
                //window.location = detailurl+"?itemid_var="+itemid;
                $.redirect(detailurl,{ itemid_var: itemid}); 
            });
        });
    
    });
                  
    $('#howwork').modal({
        'show': true,
        'backdrop': 'static',
        'keyboard': false
    });
    
    $('#howwork').on('hidden.bs.modal', function () {
        location.reload();
    })
    
    $('input[name="capitalinicialform"]').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
        $(this).prop('maxLength', 9);
    });
    
    $(".opcicustom").bootstrapSwitch(); 

    $("#sabadodiaswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#sabadodiaform").val("ok"); 
        }else{            
            $("#sabadodiaform").val(""); 
        }
    });
    
    $("#domingodiaswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#domingodiaform").val("ok"); 
        }else{            
            $("#domingodiaform").val(""); 
        }
    });
    
    $("#festivodiaswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#festivodiaform").val("ok"); 
        }else{            
            $("#festivodiaform").val(""); 
        }
    });
    
    $("#sabadoswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#sabadoform").val("ok"); 
        }else{            
            $("#sabadoform").val(""); 
        }
    });
    
    $("#domingoswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#domingoform").val("ok"); 
        }else{            
            $("#domingoform").val(""); 
        }
    });
    
    $("#festivoswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#festivoform").val("ok"); 
        }else{            
            $("#festivoform").val(""); 
        }
    });
    
    $("#cajaswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#cajaform").val("ok"); 
        }else{            
            $("#cajaform").val(""); 
        }
    });
                        
    $('#userform').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
      }
    });
    
    $('.justnumber').keyup(function (){
     this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    
    
    //activar cumulado ingreso del dia
    $('.ingresocheckbox').each(function(){
        
        //if($(this).is(":checked")){
        $(this).on('ifChecked', function(event){
            var valoringreso = $(this).attr("data-valoracumulado");
            var valoringresoformat = $(this).attr("data-valoracumuladoformat");
            var valorgastosruta = $(this).attr("data-valorgastosrutas");
            var fechaingreso = $(this).attr("data-fechaingreso");
            //console.log(valoringreso);
            $("#totalingresosinput").val(valoringreso);
            $("#totalrecaudosformat").val(valoringresoformat);
            $("#totalgastosinput").val(valorgastosruta);
            $("#fechacuadreinput").val(fechaingreso);
            
        });
    });
</script>
<script type="text/javascript">
<?php 
   // echo "<scrip>$('".$icheck_ingreso."').iCheck('enable');</script>";
    //checkbox desactivados
    //print_r($ingresostatus_disabled_array);
//print_r($ingresostatus_enabled_array);
    
    $numeIchecksDisabled = count($ingresostatus_disabled_array);
    //echo $numeIchecksDisabled;
    $auxIchekD = "";
    if(is_array($ingresostatus_disabled_array) && !empty($ingresostatus_disabled_array)){
        foreach($ingresostatus_disabled_array as $isKey){
            //echo $isKey.", ";
            
            echo "$('".$isKey."').iCheck('disable');";    
            
            /*if($numeIchecksDisabled > 1){
                $auxIchekD = $isKey;    
                echo "$('".$auxIchekD."').iCheck('disable');";    
            }elseif($numeIchecksDisabled == 1){
                $auxIchekD = $isKey;    
                echo "$('".$auxIchekD."').iCheck('disable');";    
            }*/
            
            
        }
        //echo $auxIchekD;
        //echo "$('".$auxIchekD."').iCheck('disable');";    
    }
    
    $numeIchecksEnabled = count($ingresostatus_enabled_array);
    $auxIchekE = "";
    if(is_array($ingresostatus_enabled_array) && !empty($ingresostatus_enabled_array)){
        foreach($ingresostatus_enabled_array as $isEKey){
            //echo $isEKey.", ";
            
            echo "$('".$isEKey."').iCheck('enable');";
            
            /*if($numeIchecksEnabled > 1){
                $auxIchekE = $isEKey;    
                //echo "$('".$auxIchekE."').iCheck('enable');";    
            }elseif($numeIchecksEnabled == 1){
                $auxIchekE = $isEKey;    
                //echo "$('".$auxIchekE."').iCheck('enable');";    
            }*/
            
            //echo "<scrip>$('".$isKey."').iCheck('disable');</script>";
        }
        //echo "$('".$auxIchekE."').iCheck('enable');";    
    }
   
    
?>
</script> 
<script type="text/javascript">
    
    $("#guardacuadre").click(function(){
        //event.preventDefault();
        
        //var pathfile = $("#pathfile").val();
        //var pathdir = $("#pathdir").val();
        
        var field = $(this);
        var parent = $("#wrapadditem");        
        var datafield = "newcuadrecaja";
        
        var iduser = $("input[name='codeusercaja']").val();
        var totalrecaudo = $("#totalingresosinput").val();//$("input[name='totalingresosinput']").val();
        var totalgasto = $("#totalgastosinput").val();//$("input[name='cantgastosinput']").val();
        var descrirecaudo = $("#descricuadreinput").val(); //$("input[name='descricuadreinput']").val(); 
        var fechacuadre =  $("#fechacuadreinput").val();
        var valdisponibleactual = $("input[name='valordisponibleactual']").val();
                                                         
        if($("#wrapadditem").find(".loader").length){
            $("#wrapadditem"+" .ok").remove();
            $("#wrapadditem"+" .loader").remove();
            $("#wrapadditem").append("<div class='loader text-center'><img src='../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }else{
            $("#wrapadditem").append("<div class='loader text-center'><img src='../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }
        
        //CREA OBJETO FORMDATA
        var form = document.getElementById('cuadreform');
        var formData = new FormData(form);                      
            
       
        /*
        **VARS USUARIO
        */
        formData.append("fieldeditpost", datafield);
        formData.append("codeuserpost", iduser);
        formData.append("totalrecaudopost", totalrecaudo);
        formData.append("totalgastopost", totalgasto);
        formData.append("descrirecaudopost", descrirecaudo);
        formData.append("valdisponibleactualpost", valdisponibleactual);
        formData.append("fechacuadrepost", fechacuadre);
                                                                                               
        /*for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        } */         
        if (formData) {
          $.ajax({
            url: "../appweb/inc/valida-new-cuadre.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                var response = JSON.parse(data);
                if (response["error"]) { 
                    $("#wrapadditem"+" .loader").fadeOut(function(){                         
                        var errresponse = response["error"];
                        $("#err"+datafield).html("<div class='alert alert-default alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+errresponse+"</div>").fadeIn("slow"); 
                    }); 
                     //console.log(response);
                }else{
                    //console.log(response);
                    
                    $("#wrapadditem"+" .loader").fadeOut(function(){
                        
                        $("#err"+datafield).fadeOut("slow"); 
                        
                        $("#wrapadditem").html("<div class='alert bg-success text-green'><h4><i class='icon fa fa-check margin-right-xs'></i> Cuadrar caja </h4><p style='display:block;'>El cuadre de caja se realizó correctamente</p></div>").fadeIn("slow");
                                                                        
                        $("#guardacuadre").fadeOut();                                                                   
                        location.reload();
                    });                    
                }              
            }
          });
        }                                
    });  
</script>
    
<script type="text/javascript">
    
    $("#newinyeccionbtn").click(function(){
        //event.preventDefault();
        
        //var pathfile = $("#pathfile").val();
        //var pathdir = $("#pathdir").val();
        
        var field = $(this);
        var parent = $("#newinyeccion");        
        var datafield = $(this).attr("data-field");
        var iduser = $(this).attr("data-user");        
        var valorinyeccion = $("input[name='inyectacapitalinput']").val();
                                  
        if($("#newinyeccion").find(".loader").length){
            //$("#newinyeccion"+" .ok").remove();
            $("#newinyeccion"+" .loader").remove();
            $("#newinyeccion").append("<div class='loader text-center'><img src='../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }else{
            $("#newinyeccion").append("<div class='loader text-center'><img src='../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }
        
        //CREA OBJETO FORMDATA
        var form = document.getElementById('newinyeccionform');
        var formData = new FormData(form);                      
            
       
        /*
        **VARS USUARIO
        */
        formData.append("fieldeditpost", datafield);
        formData.append("codeuserpost", iduser);        
        formData.append("totalinyectapost", valorinyeccion);
                                                                                               
        //console.log(formData);               
        if (formData) {
          $.ajax({
            url: "../appweb/inc/valida-new-inyeccion.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                var response = JSON.parse(data);
                if (response["error"]) { 
                    $("#newinyeccion"+" .loader").fadeOut(function(){                         
                        var errresponse = response["error"];
                        $("#err"+datafield).html("<div class='alert alert-default alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+errresponse+"</div>").fadeIn("slow"); 
                    }); 
                     //console.log(response);
                }else{
                    //console.log(response);
                    
                    $("#newinyeccion"+" .loader").fadeOut(function(){
                        
                        $("#err"+datafield).fadeOut("slow"); 
                        
                        $("#responce"+datafield).html("<div class='alert bg-success text-green'><h4><i class='icon fa fa-check margin-right-xs'></i> Inyección capital </h4><p style='display:block;'>Los cambios fueron guardados correctamente</p></div>").fadeIn("slow");
                                                                        
                        $("#newinyeccion").fadeOut();                                                                   
                        location.reload();
                    });                    
                }              
            }
          });
        }                               
    });  
</script>    
    
    
</body>
</html>
