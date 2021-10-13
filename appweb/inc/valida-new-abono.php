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

$fieldPost  = $_POST['fieldedit'];
$response   = array();
$fileValida = "";

if(isset($fieldPost) && $fieldPost == "additem"){

    /*
    *RECIBE DATOS
    *================
    */

    /*
    *IDS USUARIOS
    */
    $idAcreedor     = (empty($_POST['prestavarabonopost']))? "" : $_POST['prestavarabonopost'];
    $idCobradorPost = (empty($_POST['uservarabonopost']))? "" : $_POST['uservarabonopost'];
    $idRecaudoPost  = (empty($_POST['recaudovarabonopost']))? "" : $_POST['recaudovarabonopost'];
    $refCreditoPost = (empty($_POST['refcreditoabonopost']))? "" : $_POST['refcreditoabonopost'];
    $numeCuotaPost  = (empty($_POST['numecuotaabonopost']))? "" : $_POST['numecuotaabonopost'];
    /*
    *VALORES RECAUDO
    */
    $valorAcumuladoCuotaPost = (empty($_POST['valoracumladocuotaabonopost']))? "" : $_POST['valoracumladocuotaabonopost'];
    $valorPagarCuotaPost     = (empty($_POST['valorcuotaabonopost']))? "" : $_POST['valorcuotaabonopost'];
    $valorFinalCuotaPost     = (empty($_POST['valorfinalcuotaabonopost']))? "" : $_POST['valorfinalcuotaabonopost'];
    //$valorMoraPost           = (empty($_POST['valormoraabonopost']))? "" : $_POST['valormoraabonopost'];
    //$actiMoraPost            = (empty($_POST['actimoraabonopost']))? "" : $_POST['actimoraabonopost'];

    /*
    *VALOR RECIBIDO
    */
    $valorRecibidoPost = (empty($_POST['valorrecibidoabonopost']))? "" : $_POST['valorrecibidoabonopost']; //VALOR RECIBIDO EN EL PAGO
    $comentariosPost   = empty($_POST['comentapost'])? "" : $_POST['comentapost'];

    /*
    *VALIDA DATOS POST
    */

    $_POST = array(
        'idCobradorAbonoPost'      => $idCobradorPost,
        'idRecaudoAbonoPost'       => $idRecaudoPost,
        'refCreditoAbonoPost'      => $refCreditoPost,
        'numeCuotaAbonoPost'       => $numeCuotaPost,
        'valorPagarCuotaAbonoPost' => $valorFinalCuotaPost,
        //'valorMoraAbonoPost'       => $valorMoraPost,
        //'actiMoraAbonoPost'        => $actiMoraPost,
        'valorRecibidoAbonoPost'   => $valorRecibidoPost,
        'comentariosAbonoPost'     => $comentariosPost
    );

	$rules = array(
        'idCobradorAbonoPost'      => 'required|integer',
        'idRecaudoAbonoPost'       => 'required|integer',
        'refCreditoAbonoPost'      => 'required|alpha_space',
        'numeCuotaAbonoPost'       => 'required|integer',
        'valorPagarCuotaAbonoPost' => 'required|float',
        //'valorMoraAbonoPost'       => 'float',
        //'actiMoraAbonoPost'        => 'alpha',
        'valorRecibidoAbonoPost'   => 'required|float',
        'comentariosAbonoPost'     => 'alpha_space'
    );

	$filters = array(
        'idCobradorAbonoPost'      => 'trim|sanitize_string',
        'idRecaudoAbonoPost'       => 'trim|sanitize_string',
        'refCreditoAbonoPost'      => 'trim|sanitize_string',
        'numeCuotaAbonoPost'       => 'trim|sanitize_string',
        'valorPagarCuotaAbonoPost' => 'trim|sanitize_string',
        //'valorMoraAbonoPost'       => 'trim|sanitize_string',
        //'actiMoraAbonoPost'        => 'trim|sanitize_string',
        'valorRecibidoAbonoPost'   => 'trim|sanitize_string',
        'comentariosAbonoPost'     => 'trim|sanitize_string'
    );

    $_POST     = $validfield->sanitize($_POST);
    $validated = $validfield->validate($_POST, $rules);
    $_POST     = $validfield->filter($_POST, $filters);

    //echo "<pre>";
    //print_r($_POST);
    // Check if validation was successful

    $candado = "on";
	if($validated === TRUE && $candado == "on"){

        //INFO CREDITO - PLAN PAGOS
        $credito_sq = $db->subQuery('csq');
        $credito_sq->where('code_consecutivo_credito', $refCreditoPost);
        $credito_sq->getOne('creditos_tbl', 'id_creditos, id_status_credito');

        $db->join($credito_sq, 'csq.id_creditos=ppsq.id_credito');
        $ppago_sq = $db->getOne('planes_pago_tbl ppsq', 'csq.id_status_credito, ppsq.id_credito, ppsq.valor_credito_plan_pago, ppsq.valor_pagar_credito, ppsq.numero_cuotas_plan_pago, ppsq.id_credito');
        //echo "ARRAY PLAN PAGOS<pre>";
        //print_r($ppago_sq);

        $real_status_credito     = $ppago_sq['id_status_credito'];
        $real_numero_cuotas_plan = $ppago_sq['numero_cuotas_plan_pago'];

        //echo $numero_total_cuotas_credito."\ntotal cuotas credito\n";

        /*
        *CONOCER REALIDAD DEL CREDITO
         'muestra el comportamiento del credito, sus cuotas, valores en mora, valor por pagar
         'permite recalcular el valor real final del credito a pagar vs el valor a pagar inicial del credito
         'nos inidca si estos valores son iguales o por el contrario el valor final del credito va a ser mayor, debido al cobro de valores mora
         'al final comparamos estos valores para determi9nar si el credito se a cumplido en su totalidad
        */
        $db->where('ref_recaudo', $refCreditoPost);
        $querySeguimientoCredito = $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, id_status_recaudo, numero_cuota_recaudos, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, activa_mora, valor_mora_cuota_recaudo");

        $real_valor_credito        = 0;
        $real_valor_pagado_credito = 0;

        if(is_array($querySeguimientoCredito) && !empty($querySeguimientoCredito)){
            foreach($querySeguimientoCredito as $qscKey){
                //$real_cuota_mora_activa    = $qscKey['activa_mora'];
                $real_valor_mora           = $qscKey['valor_mora_cuota_recaudo'];
                $real_valor_cuota          = $qscKey['total_cuota_plan_pago'];
                $real_valor_recaudado      = $qscKey['total_valor_recaudado_estacuota'];
                //$real_numero_cuotas_plan = $qscKey['numero_cuota_recaudos'];

                //defino las cuotas que se les hayan aplicado mora
                //$real_aplica_mora          = ($real_cuota_mora_activa == 1)? $real_valor_mora : 0;

                //calcula el valor recaudado
                $real_valor_pagado_credito = $real_valor_pagado_credito + $real_valor_recaudado;

                //calcula el valor final real del credito
                $real_valor_credito        = $real_valor_credito + ($real_valor_cuota );//+ $real_aplica_mora);
            }
        }
        //$real_valor_credito = $real_valor_credito + $valorAcumuladoCuotaPost;
        //echo $real_valor_credito."valor pagar credito REAL\n\n";

        /*
        *PROGRESO CREDITO
        */
        //calcula el progreso de pago del credito hasta el momento
        $real_progreso_credito = $real_valor_pagado_credito + $valorRecibidoPost;
        //echo $real_valor_pagado_credito."valor pagado hasta ahora\n\n";


        //define valor total del credito , que aun falta por pagar
        $real_deuda_actual     = $real_valor_credito - $real_valor_pagado_credito;
        //echo $real_deuda_actual."VALOR TODAVIA DEBE\n\n";


        /*
        *DEFINE STATUS CREDITO
        */
        $status_credito = $real_status_credito;//1;

        //cuando el valor real de la deuda es mayor que el acumulado en cuotas, pero aun es menor que el valor total del credito
        if(($real_progreso_credito >= $valorAcumuladoCuotaPost) && ($real_progreso_credito < $real_valor_credito)){
            $status_credito = 1;
        }

        //cuando el valor real de la deuda es completado
        //define credito pagado (2) o por pagar (1)

        if($real_progreso_credito >= $real_valor_credito){
            //echo "CREDITO PAGADO COMPLETGAMENTE\n\n";
            $status_credito = 2;
        }


        /*
        *CALCULAR VALORES RECAUDO RESPECTO CUOTA
        *===========================================
        */

        /*
        *MORA
        */
        $checkMora = 0;
        $addMora   = 0;

        /*if($actiMoraPost == "ok"){
            $checkMora = 1;
            $addMora   = $valorMoraPost;
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
        /*$db->where('ref_recaudo', $refCreditoPost);
        $db->where('id_status_recaudo', '1', "!=");
        $db->where('fecha_max_recaudo', $dateFormatDB, '<=');
        $queryCuotasDebe= $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, id_status_recaudo, numero_cuota_recaudos, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, activa_mora, valor_mora_cuota_recaudo");

        echo "ARRAY CONSULTA<pre>";
        print_r($queryCuotasDebe);

        $totalCuotasDebe = count($queryCuotasDebe);*/
        //echo "</pre>\n #cuotas->" .$totalCuotasDebe;
        //$idAcreedor = $queryCuotasDebe[0]['id_acreedor'];

        $arrayRecaudos           = array();
        $updateRecaudos          = array();
        $valor_cuota_recalculado = 0;
        $modCuota                = 0;
        //$diferenciaPrevia = 0;
        //$diferenciaPrevia = $valorRecibidoPost - $valorFinalCuotaPost;
        $diferenciaCuota           = 0;
        $residuoNumeCuotas         = 0;
        $numeroCuotasPagoCompletas = 0;
        $valorCuotaResiduo         = 0;


        /*
        000000000000000000000000000000000000000
        */
        /*
        *ESCENARIOS
        //permite definir el numero de cuotas posibles a pagar segun el monto recibido en comparacion al monto acumulado fraccionado y el monto total del credito
        *==================
         'EL DINERO RECIBIDO ES MENOR DEL TOTAL ACUMULADO
         'EL DINERO RECIBIDO ES IGUAL AL TOTAL ACUMULADO
         'EL DINERO RECIBIDO ES MAYOR DEL TOTAL ACUMULADO
        */

        $recibido_actual = $valorRecibidoPost;
        //echo $recibido_actual."acumulado\n";

        //CALCULAMOS DIFERENCIA DEL RECIBIDO CON EL ACUMULADO
        $diferencia_recibido_acumulado = $valorRecibidoPost - $valorAcumuladoCuotaPost;

        //numero de cuotas posibles a pagar respecto al valor acumulado hasta el momento
        if(($valorAcumuladoCuotaPost== 0) || ($valorAcumuladoCuotaPost== "")) {
          $numero_cuotas = 0;
        } else {
          $numero_cuotas = $valorRecibidoPost/$valorAcumuladoCuotaPost;
        }

        //EN CASO DE RECIBIR MAS DINERO DEL ACUMULADO, CALUCALAMOS LAS POSIBLES CUOTAS PAGO ADELANTADO
        if($numero_cuotas == 0){
          $cuotas_adelantado = 0;
        }else {
          $cuotas_adelantado = ceil($valorRecibidoPost/$valorAcumuladoCuotaPost);
        }


        //calculo de numero de cuotas posibles a pagar respecto al NUMERO TOTAL DE CUOTAS del credito
        $mediacuotas      = ceil($valorRecibidoPost/$real_valor_cuota);
        $comparacuotareal = $mediacuotas%$real_numero_cuotas_plan;
        //echo $comparacuotareal."  media cuotas\n";
        //echo $numero_cuotas." nume cuotas\n";
        //echo $cuotas_adelantado." nume cuotas ceil\n";

        $total_cuotas_recaudo = 0;

        if($diferencia_recibido_acumulado > 0){
        //if($numero_cuotas > 1){
            //echo "estoy en cuopta oprcion 1\n\n";
            if($comparacuotareal<=0){
                $total_cuotas_recaudo = $comparacuotareal + $cuotas_adelantado;
            }else{
                $total_cuotas_recaudo = $real_valor_cuota;
            }
        }
        elseif($diferencia_recibido_acumulado == 0){
            //echo "estoy en cuopta oprcion 2\n\n";
            $total_cuotas_recaudo = $cuotas_adelantado + $numeCuotaPost;
        }
        elseif($diferencia_recibido_acumulado < 0){
            //echo "estoy en cuopta oprcion 3\n\n";
            $total_cuotas_recaudo = $numeCuotaPost;
        }


        //CONSULTA CUOTAS SEGUN DINERO RECIBIDO vs MONTO ACUMULADO
        $db->where('ref_recaudo', $refCreditoPost);
        $db->where('id_status_recaudo', '1', "!=");
        $db->where('numero_cuota_recaudos', $total_cuotas_recaudo, '<=');
        $queryCuotasRecaudo = $db->get ("recaudos_tbl", null, "id_acreedor, id_recaudo, id_status_recaudo, numero_cuota_recaudos, total_cuota_plan_pago, total_valor_recaudado_estacuota, valor_faltante_cuota, activa_mora, valor_mora_cuota_recaudo");

        //echo "ARRAY CONSULTA<pre>";
        //print_r($queryCuotasRecaudo);



        if(is_array($queryCuotasRecaudo) && !empty($queryCuotasRecaudo)){
            foreach($queryCuotasRecaudo as $qcmKey){
                /*
                *datos cuotas
                */
                $id_recaudo_debe     = $qcmKey['id_recaudo'];
                //$actimora_debe       = $qcmKey['activa_mora'];
                //$valormora_debe      = $qcmKey['valor_mora_cuota_recaudo'];
                $statusrecaudo_debe  = $qcmKey['id_status_recaudo'];
                $numerorecaudo_debe  = $qcmKey['numero_cuota_recaudos'];
                $valorcuota_debe     = $qcmKey['total_cuota_plan_pago'];
                $valorrecaudado_debe = $qcmKey['total_valor_recaudado_estacuota'];
                $valorfaltante_debe  = $qcmKey['valor_faltante_cuota'];

                //definimos valor mora para las cuotas que ya o tenian habilitado
                //$valor_mora_activado = ($actimora_debe == 1)? $valormora_debe : 0;

                //definimos valor mora para la cuota actual en caso de tenerlo activado
                //ADVERTENCIA> este valor no se lo debo invcrementar a la cuota dentro del capo VALOR_FALTANTE porque el acumulado pregunta si ACTIVA_MORA es 1 le suma el valor mora si es 0 no lo suma
                /*if(($numeCuotaPost == $numerorecaudo_debe) && $checkMora == 1){
                    $valor_mora_activado = $valormora_debe;
                }*/

                $valor_ingrezar             = 0;
                $valor_recaudar             = 0;
                $diferencia_valor_ingrezar  = 0;
                $valorFaltante_ingrezar     = 0;
                $statusRecaudo_ingrezar     = 3;
                $porcentaje_recaudar        = 100;
                $dinero_recibido_disponible = 0;

                //el dinero recibido se distribuyer entre las cuotas que alcance a cubrir
                if($recibido_actual > 0){

                    //dinero total correspondiente a cada cuota
                    $valor_ingrezar = ($valorcuota_debe /*+ $valor_mora_activado)*/) - $valorrecaudado_debe;

                    //seguimiento al dinero que se recibio y lo que va sobrando despues de cada cuota
                    $dinero_recibido_disponible = fmod($recibido_actual,$valorRecibidoPost);

                    //x = i*y + r

                    //echo "\nINICIO CUOTA================\n";
                    //echo "\n".$dinero_recibido_disponible." Disponible para recaudar\n";
                    //echo "\n".$valor_ingrezar." a recger en la cuota ".$numerorecaudo_debe." AUX\n";

                    /*
                    *define valor a recaudar para esta cuota
                    */

                    /*
                    *CUANDO $dinero_recibido_disponible == 0
                     'me indica que el valor recibido actual esta completo o es igual al valor recibido
                    */
                    //PARA LA PRIMERA CUOTA EN CASO DE TENER VARIAS ACUMULADAS
                    //PARA LA UNICA CUOTA EN CASO DE ESTAR AL DIA (debe sólo una cuota)
                    if($dinero_recibido_disponible == 0){

                        //si el dinero recibido es menor o igual al valor de la cuota
                        if($valorRecibidoPost<=$valor_ingrezar){
                            $valor_recaudar   = $valorRecibidoPost;

                        //si el dinero recibido es mayor al valor de la cuota
                        }else{
                            $valor_recaudar = $valor_ingrezar;
                        }

                    //A PARTIR DE LA SEGUNDA CUOTA
                    }else{
                        //valor que tomo, saca del acumulado restante el valor correspondiente a la cuota
                        $valor_que_tomo = fmod($valor_ingrezar,$dinero_recibido_disponible);
                        //65600 - (50600*1) = r
                        //echo "\n".$valor_que_tomo." VALOR TOMO\n";


                        if($checkMora == 1 && $numerorecaudo_debe == $numeCuotaPost && ($dinero_recibido_disponible <= $valor_ingrezar)){
                           //echo "tomo opcion 1\n";
                            $valor_recaudar = $dinero_recibido_disponible;
                        }
                        else if($checkMora == 1 && $numerorecaudo_debe == $numeCuotaPost && ($dinero_recibido_disponible > $valor_ingrezar)){
                            //echo "tomo opcion 2\n";
                            $valor_recaudar = $valor_que_tomo;
                        }
                        else if($dinero_recibido_disponible <= $valor_ingrezar){
                            //echo "tomo opcion 3\n";
                            $valor_recaudar = $dinero_recibido_disponible;
                        }
                        else if($dinero_recibido_disponible > $valor_ingrezar){
                            //echo "tomo opcion 4\n";
                            $valor_recaudar = $valor_que_tomo;
                        }

                    }
                    //echo "\n".$valor_recaudar." vaslor recaudar\n";
                    //definimos si queda debiendo dinero para esta cuota
                    $diferencia_valor_ingrezar = $valor_ingrezar - $valor_recaudar;
                    //echo "\n".$diferencia_valor_ingrezar." diferencia\n";
                    /*if($actimora_debe == 1){
                        $diferencia_valor_ingrezar = ($valorcuota_debe + $valormora_debe) - $valorrecaudado_debe - $valor_recaudar;
                    }else{
                        $diferencia_valor_ingrezar = $valorcuota_debe - $valorrecaudado_debe - $valor_recaudar;
                    }*/

                    //definimos el status de la cuota y si queda debiendo dinero
                    if($diferencia_valor_ingrezar == 0){
                        $statusRecaudo_ingrezar    = 1;
                        $valorFaltante_ingrezar    = 0;
                    }else{
                        $statusRecaudo_ingrezar    = 2;
                        $valorFaltante_ingrezar    = $diferencia_valor_ingrezar;
                    }


                    //restamos al valor recibido el valor que le ingrezamos a esta cuota
                    $recibido_actual = $recibido_actual - $valor_ingrezar;
                    //echo "\n".$recibido_actual." acumulado\n";

                }//[FIN|$recibido_actual]



                //genera array para las cuotas a realizar recaudo
                $arrayRecaudos[] = array(
                    'refcredito'       => $refCreditoPost,
                    //'actimora'         => ($numerorecaudo_debe == $numeCuotaPost && $checkMora == 1) ? 1 : $actimora_debe,
                    'statusrecaudo'    => $statusRecaudo_ingrezar,
                    'numerorecaudo'    => $numerorecaudo_debe,
                    'valorrecaudado'   => $valor_recaudar + $valorrecaudado_debe,
                    'valorfaltante'    => $valorFaltante_ingrezar,
                    'valorrecalculado' => 0,
                    'fechapago'        => ($statusRecaudo_ingrezar == 3)? "0000-00-00" : $dateFormatDB,
                    'horapago'         => ($statusRecaudo_ingrezar == 3)? "00:00:00" : $horaFormatDB,
                    'comentarecaudo'   => ($numerorecaudo_debe == $numeCuotaPost)? $comentariosPost : NULL,
                );
            }
        }//[FIN | $queryCuotasRecaudo]


        /*
        000000000000000000000000000000000000000
        */



        //echo "ARRAY CUOTAS GENERADAS<pre>";
        //print_r($arrayRecaudos);



        $registroCheck_1 = "off";
        $registroCheck_2 = "off";
        $registroCheck_3 = "off";

        /*
        *GENERA ARRAY PARA INGRESAR RECAUDOS
        */
        $update_err = array();
        if(is_array($arrayRecaudos) && $arrayRecaudos){
            foreach($arrayRecaudos as $arKey){

                $updateRecaudos = array(
                    'id_status_recaudo'                => $arKey['statusrecaudo'],
                    //'activa_mora'                      => $arKey['actimora'],
                    'total_valor_recaudado_estacuota'  => $arKey['valorrecaudado'],
                    'valor_faltante_cuota'             => $arKey['valorfaltante'],
                    'valor_cuota_recaulcaldo_recaudos' => $arKey['valorrecalculado'],
                    'fecha_recaudo_realizado'          => $arKey['fechapago'],
                    'hora_recaudo_realizado'           => $arKey['horapago'],
                    'comentarios_recaudo'              => $arKey['comentarecaudo']
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
                    $registroCheck_1 = "on";
                }

            }//[FIN FOREACH| $arrayRecaudos]
        }//[FIN IF| $arrayRecaudos]

        //echo "ARRAY INSERTA<pre>";
        //print_r($updateRecaudos);

        /*
        *GENERA ARRAY PARA TABAL DINERO RECIBIDO
        */
        //SELECT `id_dinero_recibido`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recibido`, `consecutivo_credito`, `numero_cuota_recaudos`, `metodo_pago_dinero_recibido`, `total_valor_recibido`, `fecha_dinero_recibido`, `comentarios_dinero_recibido` FROM `dinero_recibido_tbl` WHERE 1
        $datasDineroRecibido = array(
            'id_acreedor'           => $idAcreedor,
            'id_cobrador'           => $idCobradorPost,
            'consecutivo_credito'   => $refCreditoPost,
            'numero_cuota_recaudos' => $numeCuotaPost,
            'total_valor_recibido'  => $valorRecibidoPost,
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
            $registroCheck_2   = "on";
        }

        /*
        *GENERA ARRAY PARA TABLA CREDITO
        */
        //if($status_credito == 2){
            $datasCredito = array(
                'id_status_credito'               => $status_credito,
                'fecha_cierre_definitivo_credito' => ($status_credito == 2)? $dateFormatDB : "0000-00-00"
            );

            $db->where('code_consecutivo_credito', $refCreditoPost);
            $updateCredito = $db->update("creditos_tbl", $datasCredito);

            if(!$updateCredito){

                $queryInsert_err = $db->getLastErrno();

                $errQuery_lyt = "<p class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible actualizar el status del credito</b>
                    <br>Erro: ".$queryInsert_err."
                    <br>Puedes intentar de nuevo
                    <br>Si el error continua, por favor entre en contacto con soporte</p>";
                //$errQueryTmpl_Foto .="</ul>";

                $response['error'] = $errQuery_lyt;
            }else{
                $registroCheck_3 = "on";
            }
        //}

        /*
        *CONFIRMA QUE LOS REGISTROS FUERON REALIZADOS
        */
        if($registroCheck_1 == "on" && $registroCheck_2 == "on"){
            $response=true;
        }

    }else{

        $errValidaTmpl = "";

        $errValidaTmpl .= "<ul class='list-group text-left box75'>";

        //ERRORES VALIDACION DATOS
        $recibeRules   = array();
        $recibeRules[] = $validated;

        foreach($recibeRules as $keyRules => $valRules){
            foreach($valRules as $key => $v){

                $errFiel  = $v['field'];
                $errValue = $v['value'];
                $errRule  = $v['rule'];
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

                    /*case 'valorMoraPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Valor mora</b>
                        <br>".$usertyped."
                        <br>El valor mora no fue definido correctamente, intenta recargar la pagina, o regresa a la lista de recaudos y seleccionalo nuevamente </li>";
                    break;

                    case 'actiMoraPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Aplicar valor mora</b>
                        <br>".$usertyped."
                        <br>Si deseas aplicar el cobro de mora para esta cuota, sólo debes activarlo</li>";
                    break;*/

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
