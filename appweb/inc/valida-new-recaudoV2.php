<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once 'sessionvars-payin.php';
require_once 'query-custom-user.php';
require_once "../lib/gump.class.php";
require_once "site-tools.php"; 


//FUNCIONES REDONDEAR
function redondear_valor($valor) { 

    // Convertimos $valor a entero 
    $valor = intval($valor); 

    // Redondeamos al múltiplo de 10 más cercano 
    $n = round($valor, -2); 

    // Si el resultado $n es menor, quiere decir que redondeo hacia abajo 
    // por lo tanto sumamos 50. Si no, lo devolvemos así. 
    return $n < $valor ? $n + 50 : $n; 
    //return $n < $valor ? $n : $n + 50; 
} 

$fieldPost = $_POST['fieldedit'];
$response = array();
$fileValida = "";

if(isset($fieldPost) && $fieldPost == "additem"){    
            
    /*
    *RECIBE DATOS 
    *================
    */
    
    /*
    *IDS USUARIOS
    */
    $idCobradorPost = (empty($_POST['uservarpost']))? "" : $_POST['uservarpost']; 
    $idRecaudoPost = (empty($_POST['recaudovarpost']))? "" : $_POST['recaudovarpost'];
    $refCreditoPost = (empty($_POST['refcreditopost']))? "" : $_POST['refcreditopost'];    
    $numeCuotaPost = (empty($_POST['numecuotapost']))? "" : $_POST['numecuotapost'];    
    /*
    *VALORES RECAUDO
    */
    $valorAcumuladoCuotaPost = (empty($_POST['valoracumladocuotapost']))? "" : $_POST['valoracumladocuotapost'];
    $valorPagarCuotaPost = (empty($_POST['valorcuotapost']))? "" : $_POST['valorcuotapost'];
    $valorFinalCuotaPost = (empty($_POST['valorfinalcuotapost']))? "" : $_POST['valorfinalcuotapost'];
    $valorMoraPost = (empty($_POST['valormorapost']))? "" : $_POST['valormorapost'];
    $actiMoraPost = (empty($_POST['actimorapost']))? "" : $_POST['actimorapost'];
    
                        
    /*
    *VALOR RECIBIDO
    */
    $valorRecibidoPost = (empty($_POST['valorrecibidopost']))? "" : $_POST['valorrecibidopost']; //VALOR RECIBIDO EN EL PAGO
    $comentariosPost = empty($_POST['comentapost'])? "" : $_POST['comentapost'];                                  
                
    /*
    *VALIDA DATOS POST
    */
    
    $_POST = array( 
        'idCobradorPost' => $idCobradorPost,		
        'idRecaudoPost' => $idRecaudoPost,
        'refCreditoPost' => $refCreditoPost,
        'numeCuotaPost' => $numeCuotaPost,
        'valorPagarCuotaPost' => $valorFinalCuotaPost,
        'valorMoraPost'=> $valorMoraPost,
        'actiMoraPost'=> $actiMoraPost,
        'valorRecibidoPost' => $valorRecibidoPost,
        'comentariospost' => $comentariosPost
    );
                
	$rules = array(
        'idCobradorPost' => 'required|integer',	
        'idRecaudoPost' => 'required|integer',	
        'refCreditoPost' => 'required|alpha_space',	
        'numeCuotaPost' => 'required|integer',	
        'valorPagarCuotaPost' => 'required|float',	
        'valorMoraPost'=> 'float',	
        'actiMoraPost'=> 'alpha',	
        'valorRecibidoPost' => 'required|float',
        'comentariospost' => 'alpha_space'
    );
        
    
	$filters = array(
        'idCobradorPost' => 'trim|sanitize_string',
        'idRecaudoPost' => 'trim|sanitize_string',
        'refCreditoPost' => 'trim|sanitize_string',
        'numeCuotaPost' => 'trim|sanitize_string',
        'valorPagarCuotaPost' => 'trim|sanitize_string',
        'valorMoraPost'=> 'trim|sanitize_string',
        'actiMoraPost'=> 'trim|sanitize_string',
        'valorRecibidoPost' => 'trim|sanitize_string',
        'comentariospost' => 'trim|sanitize_string'
    ); 
	
    $_POST = $validfield->sanitize($_POST); 
    $validated = $validfield->validate($_POST, $rules);
    $_POST = $validfield->filter($_POST, $filters);
    
    //echo "<pre>";
    //print_r($_POST);
    // Check if validation was successful
            
    $candado = "on";
	if($validated === TRUE && $candado == "on"){
        /*
        *CALCULAR VALORES RECAUDO RESPECTO CUOTA
        *===========================================
        */

        /*
        *MORA
        */
        $checkMora = 0;
        $addMora = 0;

        if($actiMoraPost == "ok"){
            $checkMora = 1;  
            $addMora = $valorMoraPost;
        }

        
        /*
        *ESCENARIOS PLAN DE PAGOS
        *El deudor debe alguna cuota
           *Se consulta las cuotas del plan de pagos
           *Se determina cuantas y cuales cuotas debe
           *Se deposita a esas cuotas el valor recibido
        *El deudor esta al dia        
           *Se deposita el dinero a la cuota actual
        */
        
        /*
         *ESCENARIOS DE PAGO CUOTA
         *El deudor pago exacto del valor de lacuota (igual)         
         *El Deudor pago menos del valor de la cuota (debe)
         *El deudor esta al dia y paga por adelantado varias cuotas (credito a favor)
        */
        
        
        //CONSULTAR SI EL DEUDRO DEBE CUOTAS 
        $db->where('ref_recaudo', $refCreditoPost);        
        $db->where('id_status_recaudo', '1', "!=");
        $db->where('fecha_max_recaudo', $dateFormatDB, '<=');
        $queryCuotasMora= $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, id_status_recaudo, numero_cuota_recaudos, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, activa_mora, valor_mora_cuota_recaudo");
        
        $totalCuotasDebe = count($queryCuotasMora);
        
        $idAcreedor = $queryCuotasMora[0]['id_acreedor'];
        
        $arrayRecaudos = array();
        $updateRecaudos = array();
        $valor_cuota_recalculado = 0;
        $modCuota = 0;
        //$diferenciaPrevia = 0;
        //$diferenciaPrevia = $valorRecibidoPost - $valorFinalCuotaPost; 
        $diferenciaCuota = 0;
        $residuoNumeCuotas = 0;
        $numeroCuotasPagoCompletas = 0;
        $valorCuotaResiduo = 0;
        
        
        /*
        *DEBE VARIAS CUOTAS
        *=======================
        */
        //if($totalCuotasDebe>1){
        if($totalCuotasDebe>0){
            
            $diferenciaCuota = round($valorRecibidoPost - $valorAcumuladoCuotaPost);
            
            //define si los valores a pagar son iguales al acumulado
            $residuoNumeCuotas = fmod($valorRecibidoPost,$valorPagarCuotaPost);
            
            //numero cuotas que cubre dinero recibido
            $numeroCuotasPagoCompletas_float = $valorRecibidoPost/$valorPagarCuotaPost;
            
            //cuotas completas
            $cuotasPagoCompletas_int = intval($numeroCuotasPagoCompletas_float);
            
            /*//fraccion o residuo de cuota
            $cuotaAux = (string)$cuotasPagoCompletas_float;
            $cuotaDecimales = substr( $cuotaAux, strpos( $cuotaAux, "." ));
            
            //valor cuota fraccion
            $cuotaResiduo = ($cuotaDecimales)*$valorPagarCuotaPost;
            $cuotaResiduo_redondeo = redondear_valor($cuotaResiduo);*/
            
            //valor recaudado fraccion o residuo de cuota
            $valorCuotaResiduo = redondear_valor($valorRecibidoPost%$valorPagarCuotaPost);
            
            //valor faltante cuota residuo
            $valorFaltanteResiduo = $valorPagarCuotaPost - $valorCuotaResiduo;
            
            //cuando el dienro recibido es mayor al valor acumulado
            $cuotasCreditoFavor_float = 0;
            $cuotasCreditoFavorCompletas_int = 0;
            if($numeroCuotasPagoCompletas_float > $numeCuotaPost){
                
                //numero cuotas credito a favor con residuo
                $cuotasCreditoFavor_float = $numeroCuotasPagoCompletas_float;
                //numero cuotas completas credito a favor
                $cuotasCreditoFavorCompletas_int = intval($numeroCuotasPagoCompletas_float);
                
            }
            
            //echo $numeroCuotasPagoCompletas_float;
            /*
            $valorAcumulado_debe = 0;
            $itemCuota = 0;
            foreach($queryCuotasMora as $qcmKey){
                
                $actimora_debe = $qcmKey['activa_mora'];
                $valormora_debe = $qcmKey['valor_mora_cuota_recaudo'];
                $statusrecaudo_debe = $qcmKey['id_status_recaudo'];
                $numerorecaudo_debe = $qcmKey['numero_cuota_recaudos'];
                $valorcuota_debe = $qcmKey['total_cuota_plan_pago'];
                $valorrecaudado_debe = $qcmKey['total_valor_recaudado_estacuota'];
                $valorfaltante_debe = $qcmKey['valor_faltante_cuota'];
                
             $itemCuota++; 
                
            }//[FIN | $queryCuotasMora]
            */
            $primeraCuote_debe = $queryCuotasMora[0]['numero_cuota_recaudos'];
            //echo "<br>lacuota".$primeraCuote_debe;
            //valor total acumulado cuotas debes                
            /*if($valorfaltante_debe == 0){
                $valorAcumulado_debe = $valorAcumulado_debe + $valorcuota_debe;
            }else{
                $valorAcumulado_debe = $valorAcumulado_debe + $valorfaltante_debe;
            }*/

            /*
            *cuando el dinero recibido es menor que el acumulado
            */
            if($diferenciaCuota < 0){

                /*
                *generamos el array con las cuotas a pagar
                */

                //pago cuotas completas
                for($miPago=$primeraCuote_debe; $miPago<= $cuotasPagoCompletas_int; $miPago++){
                //if($numeCuotaPost != $numerorecaudo_debe){
                    $arrayRecaudos[] = array(
                        'refcredito' => $refCreditoPost,
                        'actimora' => ($miPago == $numeCuotaPost && $checkMora == 1) ? 1 : 0,
                        'statusrecaudo' => 1,
                        'numerorecaudo'=> $miPago,
                        'valorrecaudado'=> $valorPagarCuotaPost,
                        'valorfaltante'=> 0,
                        'valorrecalculado'=> 0,
                        'fechapago'=>$dateFormatDB               
                    ); 
                    //$numeCuotaPost = $numerorecaudo_debe;
                }
                
                //abono cuota adicional si existe residuo
                if($valorCuotaResiduo != 0){
                    $cuotasPagoCompletas_int+=1;
                    $arrayRecaudos[] = array(
                        'refcredito' => $refCreditoPost,
                        'actimora' => ($numeCuotaPost == $cuotasPagoCompletas_int && $checkMora == 1) ? 1 : 0,
                        'statusrecaudo' => 2,
                        'numerorecaudo'=> $cuotasPagoCompletas_int,
                        'valorrecaudado'=> $valorCuotaResiduo,
                        'valorfaltante'=> $valorFaltanteResiduo,
                        'valorrecalculado'=> 0,
                        'fechapago'=>$dateFormatDB               
                    );     
                }

            /*
            *cuando el dinero recibido es igual al acumulado
            */
            }elseif($diferenciaCuota == 0){
                
                /*
                *generamos el array con las cuotas a pagar
                */                
                for($miPago=$primeraCuote_debe; $miPago<= $cuotasPagoCompletas_int; $miPago++){                
                    $arrayRecaudos[] = array(
                        'refcredito' => $refCreditoPost,
                        'actimora' => ($miPago == $numeCuotaPost && $checkMora == 1) ? 1 : 0,
                        'statusrecaudo' => 1,
                        'numerorecaudo'=> $miPago,
                        'valorrecaudado'=> $valorPagarCuotaPost,
                        'valorfaltante'=> 0,
                        'valorrecalculado'=> 0,
                        'fechapago'=>$dateFormatDB               
                    );                     
                } 

            /*
            *cuando el dinero recibido es mayor al valor acumulado (credito a favor)                
            */
            }elseif($diferenciaCuota > 0){
                
                //CONSULTAR SI EL DEUDRO DEBE CUOTAS 
                $db->where('ref_recaudo', $refCreditoPost);        
                $db->where('id_status_recaudo', '1', "!=");
                $db->where('fecha_max_recaudo', $dateFormatDB, '>=');
                $db->where('numero_cuota_recaudos', $numeCuotaPost, '>=');
                $db->where('numero_cuota_recaudos', $cuotasCreditoFavorCompletas_int, '<=');
                $queryCuotasFavor= $db->get ("recaudos_tbl", null, "id_recaudo");

                //$totalCuotasFavor = count($queryCuotasFavor);    

                
                /*
                *si el numero de cuotas para credito a favor es sólo para la siguiente
                */
                //if($cuotasCreditoFavorCompletas_int > 0 && $cuotasCreditoFavorCompletas_int < 1 ){
                
                    
                /*
                *credito a favor para cuotas mayores a uno
                */
                //}elseif($cuotasCreditoFavorCompletas_int >= 1 ){
                    
                    
                    /*
                    *generamos el array con las cuotas a pagar
                    */                
                    for($miPagoFavor=$primeraCuote_debe; $miPagoFavor<= $cuotasCreditoFavorCompletas_int; $miPagoFavor++){                
                        $arrayRecaudos[] = array(
                            'refcredito' => $refCreditoPost,
                            'actimora' => 0,
                            'statusrecaudo' => 1,
                            'numerorecaudo'=> $miPagoFavor,
                            'valorrecaudado'=> $valorPagarCuotaPost,
                            'valorfaltante'=> 0,
                            'valorrecalculado'=> 0,
                            'fechapago'=>$dateFormatDB               
                        );                     
                    }
                    
                    //abono cuota adicional si existe residuo
                    if($valorCuotaResiduo != 0){
                        $cuotasPagoCompletas_int+=1;
                        $arrayRecaudos[] = array(
                            'refcredito' => $refCreditoPost,
                            'actimora' => 0,
                            'statusrecaudo' => 2,
                            'numerorecaudo'=> $cuotasPagoCompletas_int,
                            'valorrecaudado'=> $valorCuotaResiduo,
                            'valorfaltante'=> $valorFaltanteResiduo,
                            'valorrecalculado'=> 0,
                            'fechapago'=>$dateFormatDB               
                        );     
                    }
                    
                //}
                

            }
                
                                                                       
        /*
        *DEBE UNA SOLA CUOTA -> ESTA AL DIA
        *=======================
        */
        }/*elseif($totalCuotasDebe==1){
        
            
        }*/

        //echo "<pre>";
        //print_r($arrayRecaudos);

        
        
        $registroCheck_1 = "off";
        $registroCheck_2 = "off";
                
        /*
        *GENERA ARRAY PARA INGRESAR RECAUDOS
        */
        $update_err = array();
        if(is_array($arrayRecaudos) && $arrayRecaudos){
            foreach($arrayRecaudos as $arKey){

                $updateRecaudos = array(
                    'id_status_recaudo' => $arKey['statusrecaudo'],
                    'activa_mora' =>  $arKey['actimora'],
                    'total_valor_recaudado_estacuota' =>  $arKey['valorrecaudado'],
                    'valor_faltante_cuota' => $arKey['valorfaltante'],
                    'valor_cuota_recaulcaldo_recaudos' =>  $arKey['valorrecalculado'],                
                    'fecha_recaudo_realizado' => $arKey['fechapago'],
                    'hora_recaudo_realizado' => $horaFormatDB,
                    'comentarios_recaudo' => $comentariosPost
                );

                $db->where("ref_recaudo", $arKey['refcredito']);
                $db->where("numero_cuota_recaudos", $arKey['numerorecaudo']);                        
                $updateRecaudo = $db->update('recaudos_tbl', $updateRecaudos);

                if(!$updateRecaudo){
                    $queryUpdate_err = $db->getLastErrno();

                    //$errQueryTmpl_Foto ="<ul class='list-group text-left'>";
                    $errQuery_lyt[] = "<p class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                        <br>Wrong: <b>No fue posible guardar el recaudo</b>
                        <br>Erro: ".$queryUpdate_err."
                        <br>Puedes intentar de nuevo
                        <br>Si el error continua, por favor entre en contacto con soporte</p>";
                    //$errQueryTmpl_Foto .="</ul>";

                    $response['error'] = $errQuery_lyt;
                }else{
                    //$response=true;       
                    $registroCheck_2 = "on";
                }

            }//[FIN FOREACH| $arrayRecaudos]
        }//[FIN IF| $arrayRecaudos]
            
        //echo "<pre>";
        //print_r($arrayRecaudos);
        
        /*
        *GENERA ARRAY PARA TABAL DINERO RECIBIDO
        */        
        //SELECT `id_dinero_recibido`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recibido`, `consecutivo_credito`, `numero_cuota_recaudos`, `metodo_pago_dinero_recibido`, `total_valor_recibido`, `fecha_dinero_recibido`, `comentarios_dinero_recibido` FROM `dinero_recibido_tbl` WHERE 1
        $datasDineroRecibido = array(
            'id_acreedor' => $idAcreedor,
            'id_cobrador' => $idCobradorPost,
            'consecutivo_credito' => $refCreditoPost,
            'numero_cuota_recaudos' => $numeCuotaPost,
            'total_valor_recibido' => $valorRecibidoPost,
            'fecha_dinero_recibido' => $dateFormatDB,
        );
        //echo "<pre>";
        //print_r($datasDineroRecibido);      
        $newDineroRecibido = $db->insert("dinero_recibido_tbl", $datasDineroRecibido);
                
        if(!$newDineroRecibido){
            
            $queryInsert_err = $db->getLastErrno();        
            
            $errQuery_lyt = "<p class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No fue posible guardar el dinero recibido</b>
                <br>Erro: ".$queryInsert_err."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</p>";
            //$errQueryTmpl_Foto .="</ul>";

            $response['error'] = $errQuery_lyt;    
        }else{
            $registroCheck_1 = "on";
        }
        
        
        /*
        *CONFIRMA QUE LOS REGISTROS FUERON REALIZADOS
        */
        if($registroCheck_1 =="on" && $registroCheck_2 == "on"){
            $response=true;
        }
        
    }else{
        
        $errValidaTmpl = "";
                
        $errValidaTmpl .= "<ul class='list-group text-left box75'>";
                                           
        //ERRORES VALIDACION DATOS
        $recibeRules = array();
        $recibeRules[] = $validated;
                                
        foreach($recibeRules as $keyRules => $valRules){
            foreach($valRules as $key => $v){
                                
                $errFiel = $v['field'];
                $errValue = $v['value'];
                $errRule = $v['rule'];
                $errParam = $v['param'];
                
                if(empty($errValue)){
                    $usertyped = "Por favor completa este campo";                    
                }else{
                    $usertyped = "Escribiste&nbsp;&nbsp;<b>" .$errValue ."</b>";
                }
                
                switch($errFiel){    
                    case 'idCobradorPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Cobrador</b>
                        <br>".$usertyped."
                        <br>No logramos identificar tu usuario, intenta recargar la pagina, o regresa a la lista de recaudos y seleccionalo nuevamente</li>";
                    break;                        
                        
                    case 'idRecaudoPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Recaudo</b>
                        <br>".$usertyped."
                        <br>El ID de este recaudo no fue encontrado, intenta recargar la pagina, o regresa a la lista de recaudos y seleccionalo nuevamente</li>";
                    break;                        
                        
                    case 'refCreditoPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Crdito</b>
                        <br>".$usertyped."
                        <br>El credito que seleccionaste no fue encontrado, intenta recargar la pagina, o regresa a la lista de recaudos y seleccionalo nuevamente</li>";
                    break;
                        
                    case 'numeCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Numero cuota</b>
                        <br>".$usertyped."
                        <br>No fue posible reconocer la cuota de este recaudo, intenta recargar la pagina, o regresa a la lista de recaudos y seleccionalo nuevamente</li>";
                    break;
                        
                    case 'valorPagarCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Valor recaudo</b>
                        <br>".$usertyped."
                        <br>El valor del recaudo no fue definido, intenta recargar la pagina, o regresa a la lista de recaudos y seleccionalo nuevamente</li>";
                    break;
                        
                    case 'valorMoraPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Valor mora</b>
                        <br>".$usertyped."
                        <br>El valor mora no fue definido correctamente, intenta recargar la pagina, o regresa a la lista de recaudos y seleccionalo nuevamente </li>";
                    break;
                    
                    case 'actiMoraPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Aplicar valor mora</b>
                        <br>".$usertyped."
                        <br>Si deseas aplicar el cobro de mora para esta cuota, sólo debes activarlo</li>";
                    break;
                        
                    case 'valorRecibidoPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Valo recibido</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break;    
                        
                    case 'comentariospost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Comentarios</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Escribe observaciones o anotaciones importantes sobre este recaudo
                        <br>puedes usar letras, números y signos de puntuación</li>";
                    break;    
                        
                }
            }
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    echo json_encode($response);
    
}
