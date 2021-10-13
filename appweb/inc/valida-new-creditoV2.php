<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once 'sessionvars.php';
require_once 'query-custom-user.php';
require_once "../lib/gump.class.php";
require_once "site-tools.php"; 
require_once "query-tablas-complementarias.php";


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


//CHECK DIAS FESTIVOS
function diasFestivos($esfestivo){
    global $festivos;
    
    $festivodia = "habil";
    foreach($festivos as $fKey){
        
        if($esfestivo == $fKey){
            //$ajuste = date("d-m-Y", strtotime($esfestivo . " + 1 day"));    
            $festivodia = "festivo";
        }
        
    }
    
    return $festivodia;
}


//GENERAR ARRAY DIAS HABILES ENTRE DOS FECHAS
function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array()) {
    global $array_fines_semana;
    global $activarFestivos;
    
    // Convirtiendo en timestamp las fechas
    $fechainicio = strtotime($fechainicio);
    $fechafin = strtotime($fechafin);

    // Incremento en 1 dia
    $diainc = 24*60*60;

    // Arreglo de dias habiles, inicianlizacion
    $diashabiles = array();

    // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
    for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
        // Si el dia indicado, no es sabado o domingo es habil
        //array(6,7)  ->  array(sabado, domingo) //lo que quiero es solamente domingo -> array(7)
        if (!in_array(date('N', $midia), $array_fines_semana)) { // DOC: http://www.php.net/manual/es/function.date.php
            // Si no es un dia feriado entonces es habil
            if($activarFestivos == "off"){
                if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
                    array_push($diashabiles, date('Y-m-d', $midia));
                }
            }else{
                array_push($diashabiles, date('Y-m-d', $midia));
            }
        }
    }

    return $diashabiles;
}


//ARRAY FESTIVOS
$festivos = array();
$festivosYMD = array();
$queryFestivos = array();
$queryFestivos = queryDiasFestivos();
if(is_array($queryFestivos) && !empty($queryFestivos)){
    foreach($queryFestivos as $qfKey){
        $festivosYMD[] = $qfKey["fecha_dia_festivo"];
        $festivos[] = date("m-d", strtotime($qfKey["fecha_dia_festivo"]));
    }
}


$fieldPost = $_POST['fieldedit'];
$response = array();
$fileValida = "";

if(isset($fieldPost) && $fieldPost == "additem"){    
            
    //***********
    //RECIBE DATOS 
    //***********
    
    /*
    *IDS USUARIOS
    */
    $pseudoUserPost = (empty($_POST['pseudouserpost']))? "" : $_POST['pseudouserpost']; 
    $idAcreadorPost = (empty($_POST['codeuserform']))? "" : $_POST['codeuserform'];
    //$idCreditoPost = (empty($_POST['codeitemform']))? "" : $_POST['codeitemform'];    
    $idDeudorPost = (empty($_POST['deudorpost']))? "" : $_POST['deudorpost'];
    //$idCodeudorPost = (empty($_POST['codeudorpost']))? "" : $_POST['codeudorpost'];
    //$idRefPersoPost = (empty($_POST['refpersopost']))? "" : $_POST['refpersopost'];
    //$idRefFamiPost = (empty($_POST['reffamipost']))? "" : $_POST['reffamipost'];
    //$idRefComerPost = (empty($_POST['refcomerpost']))? "" : $_POST['refcomerpost'];
                        
    /*
    *SOBRE EL PRESTAMO
    */
    $montoPost = (empty($_POST['montopost']))? "" : $_POST['montopost']; //VALOR PRESTADO
    $valorPagarPost = (empty($_POST['valtotalpagarpost']))? "" : $_POST['valtotalpagarpost'];
    $utilidadPost = (empty($_POST['valutilidadpost']))? "" : $_POST['valutilidadpost'];
      
    $tipoCreditoPost = (empty($_POST['tipocreditopost']))? "" : $_POST['tipocreditopost'];
    $descriCreditoPost = (empty($_POST['descricreditopost']))? "" : $_POST['descricreditopost'];
    
    /*
    *PLAN DE PAGOS
    */
    //******************************
    //$valmontocreditoPost = (empty($_POST['variable1']))? "" : $_POST['variable1'];  
    //$fechainicialPost = (empty($_POST['variable2']))? "" : date("Y-m-d", strtotime(str_replace('/', '-', $_POST['variable2'])));
    $plazoPost = (empty($_POST['plazopost']))? "" : $_POST['plazopost'];
    //$periocidadPost = (empty($_POST['variable4']))? "" : $_POST['variable4'];
    //******************************
    
    //$capitalCuotaPost = (empty($_POST['capitalcuotapost']))? "" : $_POST['capitalcuotapost'];
    $interesCuotaPost = (empty($_POST['interescuotapost']))? "" : $_POST['interescuotapost'];
    $moraCuotaPost = (empty($_POST['moracutoapost']))? "" : $_POST['moracutoapost'];
    $sobCargoCuotaPost = (empty($_POST['sobcargocuotapost']))? "" : $_POST['sobcargocuotapost'];
    
    //$numeCuotaPost = (empty($_POST['numecuotaspost']))? "" : $_POST['numecuotaspost'];
    $fechaInicioCuotaPost = (empty($_POST['fechainiciocreditopost']))? "" : date("Y-m-d", strtotime(str_replace('/', '-', $_POST['fechainiciocreditopost'])));//(empty($_POST['fechainiciocreditopost']))? "" : $_POST['fechainiciocreditopost'];
    //$fechaFinCuotaPost = (empty($_POST['fechafincreditopost']))? "" : date("Y-m-d", strtotime(str_replace('/', '-', $_POST['fechafincreditopost'])));//(empty($_POST['fechafincreditopost']))? "" : $_POST['fechafincreditopost'];
    
    $periocidadCuotaPost = (empty($_POST['periocidadcuotapost']))? "" : $_POST['periocidadcuotapost'];
    //$valTotalCuotaPost = (empty($_POST['valtotalcuotapost']))? "" : $_POST['valtotalcuotapost'];
    
    $statusItemPost = (empty($_POST['statusitem']))? "" : $_POST['statusitem'];
    
    /*
    *COBRADOR
    */
    //$cobradorPost = (empty($_POST['cobradorpost']))? "" : $_POST['cobradorpost'];
    $cobradorPost = (empty($_POST['cobradorinput']))? "" : $_POST['cobradorinput'];
    
    /*
    *CONSECUTVO CREDITO
    */
        
    //LAST ITEM TABLE
    //$tablaQ = "creditos_tbl";
    //$campoQ = "id_creditos";    
    //$lastItemDB = lastIDRegis($tablaQ, $campoQ);
    //$lastItemDB = $lastItemDB + 1;    
    
    $idAcreadorPost = (int)$idAcreadorPost;
    $idAcreadorPost = $db->escape($idAcreadorPost);
    
    $db->where('id_acreedor', $idAcreadorPost);        
    $queryLastCreditUserSSTbl = $db->get ("creditos_tbl", null, "id_creditos");
        
    $lastItemDB = count($queryLastCreditUserSSTbl);
    $lastItemDB = $lastItemDB + 1;    
            
    //DEFINE CODIGO NUMERICO DEL REGISTRO
    switch($lastItemDB) {
		
		case ($lastItemDB < 10):
			$prefijo = "00000";
			$lastItemDB = $prefijo.$lastItemDB;
		break;	
		
		case ($lastItemDB < 100):
			$prefijo = "0000";
			$lastItemDB = $prefijo.$lastItemDB;
		break;
		
		case ($lastItemDB < 1000):
			$prefijo = "000";
			$lastItemDB = $prefijo.$lastItemDB;
		break;	
	
		case ($lastItemDB < 10000):
			$prefijo = "00";
			$lastItemDB = $prefijo.$lastItemDB;
		break;	
		
		case ($lastItemDB < 100000):
			$prefijo = "0";
			$lastItemDB = $prefijo.$lastItemDB;
		break;
	
		case ($lastItemDB >= 100000):
			$lastItemDB = $lastItemDB;
		break;
	}
    
    //CREA CONSECUTIVO CREDITO
    $consecutivoCredito = $pseudoUserPost."_".$lastItemDB;
    
    /*
    *GENERA ARRAYS CUOTAS
    */
    
    //DECLARA VARIABLES
    $fechaPost = $fechaInicioCuotaPost;//**fecha incio credito
    $MaxDias = $plazoPost; //Cantidad de dias maximo para el prestamo, este sera util para crear el for
    $Segundos = 0;
    $arrayTotalFechas = array();
    
    //GENERA LAS POSIBLES FECHAS HABILES PARA CUOTAS 
    for ($i=0; $i<$MaxDias; $i++){

        //Obtenemos los dias el dia de la fecha, aumentando el tiempo en N cantidad de dias, segun la vuelta en la que estemos        
        $caduca = date("D", strtotime($fechaPost)+$Segundos);
        
        $esfestivo = date("m-d", strtotime($fechaPost)+$Segundos);
        
        $inhabil = diasFestivos($esfestivo);

        //Comparamos las fechas que coincidan con sabado y domingo para eliminarlas de los dias habiles de cobro de cuota
        if ($caduca == "Sat" /*&& $activarSabado == "on"*/){
          //  $i--;
            $arrayTotalFechas[] = array(
                'tipodia' => "Sabado",
                'diafecha' => date("D", strtotime($fechaPost)+$Segundos),
                'fecha' => date("Y-m-d", strtotime($fechaPost)+$Segundos),
            );
        }else if ($caduca == "Sun" /*&& $activarDomingos == "on"*/){
          //  $i--;
            $arrayTotalFechas[] = array(
                'tipodia' => "Domingo",
                'diafecha' => date("D", strtotime($fechaPost)+$Segundos),
                'fecha' => date("Y-m-d", strtotime($fechaPost)+$Segundos),
            );
        }else if ($inhabil == "festivo" /*&& $activarFestivos == "on"*/){
        
            //$i--;
            $arrayTotalFechas[] = array(
                'tipodia' => "Festivo",
                'diafecha' => date("D", strtotime($fechaPost)+$Segundos),
                'fecha' => date("Y-m-d", strtotime($fechaPost)+$Segundos),
            );
            
        }else{
            //CREAMOS EL DIA CON SU RESPECTIVA FECHA
            $FechaFinal = date("Y-m-d", strtotime($fechaPost)+$Segundos);
            $diaFecha = date("D", strtotime($fechaPost)+$Segundos);
            
            //CREAMOS EL ARRAY CON LAS POSIBLES FECHAS
            $arrayTotalFechas[] = array(
                'tipodia' => "Habil",
                'diafecha' => $diaFecha,
                'fecha' => $FechaFinal
            );

        }

        //Acumulamos la cantidad de segundos que tiene un dia en cada vuelta del for
        $Segundos = $Segundos + 86400;

    }
    /*
    ARRAY POSIBLES FECHAS
    Array(
        [0] => Array
            (
                [diafecha] => Mon
                [fecha] => 2017-05-15
            )

        [1] => Array
            (
                [diafecha] => Tue
                [fecha] => 2017-05-16
            )

        [2] => Array
            (
                [diafecha] => Wed
                [fecha] => 2017-05-17
            )

        [3] => Array
            (
                [diafecha] => Thu
                [fecha] => 2017-05-18
            )
    );
    */
    
    //GENERA LAS CUOTAS RESPECTO A LA PERIOCIDAD SELECCIONADA
    $periocidad = $periocidadCuotaPost;    
    $arrayCuotas = array();
    $arrayCuotasDiarias = array();
    $posiArrayFecha = 1;
    $fechaVal = array();
    foreach($arrayTotalFechas as $aqKey){
        foreach($aqKey as $aqVal){
            $fechaVal[] = $aqKey['fecha'];        
        }
    }
    
    $ultimaFechaPlazo = "";
    if(is_array($fechaVal) && !empty($fechaVal)){
        $ultimaFechaPlazo = end($fechaVal);    
    }
    
    
    $diasHabilesPlazo = array();
    $diasHabilesPlazo = getDiasHabiles($fechaPost, $ultimaFechaPlazo, $festivosYMD);
    
    $primerDiaHabil = $diasHabilesPlazo[0];
    $ultimoDiaHabil = end($diasHabilesPlazo);
    
    //GENERA ARRAYS FECHAS PERIOCIDAD
    foreach($arrayTotalFechas as $fKey){
        
        foreach($fKey as $cuotaK){
            
            $tipoDiaCuota = $fKey['tipodia'];   
            $diaCuota = $fKey['diafecha'];
            $fechaCuota = $fKey['fecha'];
        }
        
        /*if($activarFestivos == "off"){
            unset($fKey);
        }
        
        if($activarFestivos_dia == "off"){
            unset($fKey);
        }*/

        switch($periocidad){
            case "dia":
                if(fmod($posiArrayFecha,1) == 0){
                    $arrayCuotasDiarias[] = array(
                        'tipodia' => $tipoDiaCuota,
                        'diacuota' => $diaCuota,
                        'fechacuota' => $fechaCuota
                    );
                }
            break;

            case "semana":
                if(fmod($posiArrayFecha,7) == 0){

                     $arrayCuotas[] = array(
                        'tipodia' => $tipoDiaCuota,
                        'diacuota' => $diaCuota,
                        'fechacuota' => $fechaCuota
                    );   
                }
            break;

            case "quincena":
                if(fmod($posiArrayFecha,15) == 0){

                     $arrayCuotas[] = array(
                        'tipodia' => $tipoDiaCuota,
                        'diacuota' => $diaCuota,
                        'fechacuota' => $fechaCuota
                    );     
                }
            break;

            case "mes":
                if(fmod($posiArrayFecha,30) == 0){

                    $arrayCuotas[] = array(
                        'tipodia' => $tipoDiaCuota,
                        'diacuota' => $diaCuota,
                        'fechacuota' => $fechaCuota
                    );     
                }
            break;
        }
        
        //POSICION ARRAY
        $posiArrayFecha++;
    }
    /*
    ARRAY CUOTAS
    Array(
        [0] => Array
            (
                [diacuota] => Mon
                [fechacuota] => 2017-05-15
            )

        [1] => Array
            (
                [diacuota] => Mon
                [fechacuota] => 2017-06-05
            )

        [2] => Array
            (
                [diacuota] => Mon
                [fechacuota] => 2017-06-26
            )
    );
    
    /*
    *GENERA LAS FECHAS EXACTAS DE PAGO
    */
    
    $diasdePago = array();
    $maxFechaPos = strtotime($primerDiaHabil);    
    $diainc = 60*60*24;
    
    /*
    *DIAS DE PAGO CUANDO PERIOCIDAD SEMANAL / QUINCENAL /MENSUAL
    */
    if(is_array($arrayCuotas) && !empty($arrayCuotas)){
        foreach($arrayCuotas as $acKey){

            //convertimos a valor numerioco la fecha
            $fechaPos = strtotime($acKey['fechacuota']);

            $fechaAux = $fechaPos;

            //si la fecha de la cuota calendario existe en el grupo de fechas habiles entonces lo adiciona al array dias de pago
            if (in_array(date('Y-m-d', $fechaAux), $diasHabilesPlazo)) { 

                array_push($diasdePago, date('Y-m-d', $fechaPos));    

            //si la fecha calendario no existe en las fechas habiles, buscamos la fecha mas proxima a $fechaPos dentro de este grupo y la asignamos en el array dias de pago
            }else{

                for ($midia = $fechaPos; $midia >= $maxFechaPos; $midia -= $diainc) {

                    if (in_array(date('Y-m-d', $midia), $diasHabilesPlazo)) {
                        array_push($diasdePago, date('Y-m-d', $midia));
                        break;
                    }

                }


            }
        }
    }//[fin|$arrayCuotas]
    
    /*
    *DIAS DE PAGO CUANDO PERIOCIDAD DIARIA
    */
    if(is_array($arrayCuotasDiarias) && !empty($arrayCuotasDiarias)){
        
        foreach($arrayCuotasDiarias as $acdKey){
            //foreach($acdKey as $acdVal){
                //$fechaVal[] = $aqKey['fecha'];        
                //$diasdePago[] = $acdKey['fechacuota'];
            //}
            //define tipo dia 
            $tipoDia_diaria = $acdKey['tipodia'];
            
            //foreach($acdKey as $acdVal){
                //$fechaVal[] = $aqKey['fecha'];        
                //$diasdePago[] = $acdKey['fechacuota'];
            //}
            
            
            if ($tipoDia_diaria == "Sabado" && $activarSabado_dia == "off"){                
                
                unset($acdKey['fechacuota']);
                
            }else if ($tipoDia_diaria == "Domingo" && $activarDomingos_dia == "off"){
              
                unset($acdKey['fechacuota']);
                
            }else if ($tipoDia_diaria == "Festivo" && $activarFestivos_dia == "off"){

                unset($acdKey['fechacuota']);

            }else{
                
                $diasdePago[] = $acdKey['fechacuota'];

            } 
            
            
        }
    }
    /*echo "<pre>array cuotas diarias";
    print_r($arrayCuotasDiarias);
    echo "</pre>";
    echo "<pre>array dias pago";
    print_r($diasdePago);
    echo "</pre>";*/
    /*
    *CALCULO VALORES
    */
    
    
    //ULTIMA POSICION DE UN ARRAY
    /*
    $stack = array("naranja", "plátano", "manzana", "frambuesa");
    $fruit = array_pop($stack);
    */
    
    $fechaVal = array();
    $numCuotas = count($diasdePago);
    $totalCuotas = 0;
    $flechaPlazo = ""; 
    $totalCapital = 0;
    $totalValorCuota = 0;
    /*foreach($diasdePago as $aqKey){
        foreach($aqKey as $aqVal){
            $fechaVal[] = $aqKey['fechacuota'];
        }
    }*/
      
    $totalCuotas = empty($numCuotas)? "0" : $numCuotas;
    
    if(is_array($diasdePago) && !empty($diasdePago)){
        $flechaPlazo = end($diasdePago); 
    }
    
    $totalCapital = "";
    if($totalCuotas > 0){
        $totalCapital = $montoPost/$numCuotas;  
        $totalCapital = redondear_valor($totalCapital);
    }
            
    //$valorTotalCuota = $totalCapital + $interesCuotaPost;// + $moraCuotaPost + $sobCargoCuotaPost;
    $valorTotalCuota = "";
    if($numCuotas > 0){
        $valorTotalCuota = $valorPagarPost/$numCuotas; 
        $valorTotalCuota = redondear_valor($valorTotalCuota);
    }
    
    
    
    //FORMAT PRECIOS
    if($flechaPlazo != ""){
        $flechaPlazoFormat = date("d/m/Y", strtotime($flechaPlazo));    
    }
    
    if($totalCapital != ""){
        $totalCapitalFormat = number_format($totalCapital,2,".",",");     
    }
    
    if($valorTotalCuota > 0){
        $valorTotalCuotaFormat = number_format($valorTotalCuota,2,".",",");   
    }
    
            
    /*
    *VALIDA DATOS POST
    */
    
    $_POST = array( 
        'id_acreedor' => $idAcreadorPost,		
        'id_deudor' => $idDeudorPost,
        //'id_credito' => $idCreditoPost,
        //'id_codeudor' => $idCodeudorPost,
        //'id_refperso' => $idRefPersoPost,
        //'id_reffami'=> $idRefFamiPost,
        //'id_refcomer'=> $idRefComerPost,
        'montoPost' => $montoPost,
        'valorPagarPost' => $valorPagarPost,  
        'utilidadPost' => $utilidadPost,
        'tipoCreditoPost' => $tipoCreditoPost ,
        'descriCreditoPost' => $descriCreditoPost,	
        'capitalCuotaPost' =>$totalCapital,
        'interesCuotaPost' =>$interesCuotaPost,
        'moraCuotaPost' => $moraCuotaPost,
        'sobCargoCuotaPost' => $sobCargoCuotaPost,
        'numeCuotaPost' => $totalCuotas,
        'fechaInicioCuotaPost' => $fechaInicioCuotaPost,
        'fechaFinCuotaPost' => $flechaPlazo,
        'plazoCredito' => $plazoPost,
        'periocidadCuotaPost' => $periocidadCuotaPost,
        'valTotalCuotaPost' => $valorTotalCuota,
        'statusItemPost' => $statusItemPost,
        'cobradorPost' => $cobradorPost
    );
                
	$rules = array(
        'id_acreedor' => 'required|integer',		
        'id_deudor' => 'integer',		
        //'id_credito' => 'required|integer',		
        //'id_codeudor' => 'integer',		
        //'id_refperso' => 'integer',		
        //'id_reffami'=> 'integer',		
        //'id_refcomer'=> 'integer',		
        'montoPost' => 'required|float|max_len,9',	
        'valorPagarPost' => 'required|float|max_len,9',	
        'utilidadPost' => 'required|float|max_len,9',	
        'tipoCreditoPost' => 'required|integer',
        'descriCreditoPost' => 'alpha_space|max_len,200',
        'capitalCuotaPost' =>'float',		
        'interesCuotaPost' =>'float|max_len,9',		
        'moraCuotaPost' => 'float|max_len,9',		
        'sobCargoCuotaPost' => 'float|max_len,9',		
        'numeCuotaPost' => 'required|integer',	
        'fechaInicioCuotaPost' => 'required|date',
        'fechaFinCuotaPost' => 'required|date',
        'plazoCredito' => 'required|integer',	
        'periocidadCuotaPost' => 'required|alpha',	
        'valTotalCuotaPost' => 'required|float',	
        'statusItemPost' => 'integer',
        'cobradorPost' => 'required|integer'
    );
        
    
	$filters = array(
        'id_acreedor' => 'trim|sanitize_string',
        'id_deudor' => 'trim|sanitize_string',
        //'id_credito' => 'trim|sanitize_string',
        //'id_codeudor' => 'trim|sanitize_string',
        //'id_refperso' => 'trim|sanitize_string',	
        //'id_reffami'=> 'trim|sanitize_string',
        //'id_refcomer'=> 'trim|sanitize_string',
        'montoPost' => 'trim|sanitize_string',
        'valorPagarPost' => 'trim|sanitize_string',
        'utilidadPost' => 'trim|sanitize_string',
        'tipoCreditoPost' => 'trim|sanitize_string',
        'descriCreditoPost' => 'trim|sanitize_string',
        'capitalCuotaPost' =>'trim|sanitize_string',
        'interesCuotaPost' =>'trim|sanitize_string',	
        'moraCuotaPost' => 'trim|sanitize_string',
        'sobCargoCuotaPost' => 'trim|sanitize_string',
        'numeCuotaPost' => 'trim|sanitize_string',
        'fechaInicioCuotaPost' => 'trim|sanitize_string',
        'fechaFinCuotaPost' => 'trim|sanitize_string',
        'plazoCredito' =>  'trim|sanitize_string',
        'periocidadCuotaPost' => 'trim|sanitize_string',
        'valTotalCuotaPost' => 'trim|sanitize_string',
        'statusItemPost' => 'trim|sanitize_string',
        'cobradorPost' => 'trim|sanitize_string'
    ); 
	
    $_POST = $validfield->sanitize($_POST); 
    $validated = $validfield->validate($_POST, $rules);
    $_POST = $validfield->filter($_POST, $filters);
    
    //echo "<pre>";
    ///print_r($validated);
    // Check if validation was successful
            
    $candado = "on";
	if($validated === TRUE && $candado == "on"){

        //***********
        //PROCESO NUEVO REGISTRO (DEUDOR)
        //***********

        /*!!!
        OBSERVACION
        se crean los arrays para insertar las tablas CREDITO - PLANES DE PAGO- RECAUDOS
        !!!*/

        $nuevoPost = array();
        $nuevoPost = $_POST;
        //foreach($nuevoPost as $valInsert => $valPost){
        foreach($nuevoPost as $valInsert){
            //CREDITO
            $dataCreditoInsert = array(                                    
                'id_acreedor' => $nuevoPost['id_acreedor'],
                'id_deudor' =>$nuevoPost['id_deudor'],
                //'id_codeudor'=>  $nuevoPost['id_codeudor'],    
                //'id_referencia_personal'=> $nuevoPost['id_refperso'],
                //'id_referencia_familiar' =>$nuevoPost['id_reffami'],
                //'id_referencia_comercial'=> $nuevoPost['id_refcomer'],  
                'id_cobrador'=> $nuevoPost['cobradorPost'], 
                //'id_status_credito'=> $nuevoPost['statusItemPost'],  
                'code_consecutivo_credito'=> $consecutivoCredito,  
                'tipo_credito' =>  $nuevoPost['tipoCreditoPost'],
                'descripcion_credito' => $nuevoPost['descriCreditoPost'],
                'fecha_apertura_credito' =>$dateFormatDB,
                'hora_apertura_credito'=> $horaFormatDB                    
            );  

            //PLAN DE PAGOS
            $dataPlanPagoInsert = array(
                'id_credito' => NULL,
                'id_deudor' => $nuevoPost['id_deudor'],
                'valor_credito_plan_pago' => $nuevoPost['montoPost'],
                'valor_pagar_credito' => $nuevoPost['valorPagarPost'],
                'utilidad_credito' => $nuevoPost['utilidadPost'],
                'numero_cuotas_plan_pago' => $nuevoPost['numeCuotaPost'],
                'periocidad_plan_pago' => $nuevoPost['periocidadCuotaPost'],
                'plazocredito_plan_pago' => $nuevoPost['plazoCredito'],
                'fecha_inicio_plan_pago' => $nuevoPost['fechaInicioCuotaPost'],
                'fecha_fin_plan_pago' => $nuevoPost['fechaFinCuotaPost'],
                'capital_cuota_plan_pago' => $nuevoPost['capitalCuotaPost'],
                'interes_valor_plan_pago' => $nuevoPost['interesCuotaPost'],
                'valor_mora_plan_pago' => $nuevoPost['moraCuotaPost'],
                'sobrecargo_cuota_plan_pago' => $nuevoPost['sobCargoCuotaPost'],
                'valor_cuota_plan_pago' => $nuevoPost['valTotalCuotaPost']
            );

            //RECAUDOS
            $dataRecaudosInsert = array(
                'id_acreedor' => $nuevoPost['id_acreedor'],
                'id_plan_pago' => NULL,
                'id_cobrador' => $nuevoPost['cobradorPost'],
                'ref_recaudo'=> $consecutivoCredito,
                'numero_cuota_recaudos' => NULL,
                'capital_cuota_recaudo' => $nuevoPost['capitalCuotaPost'],
                'interes_cuota_recaudo' => $nuevoPost['interesCuotaPost'],
                'valor_mora_cuota_recaudo' => $nuevoPost['moraCuotaPost'],
                'sobrecargo_cuota_recaudo' => $nuevoPost['sobCargoCuotaPost'],
                'total_cuota_plan_pago' => $nuevoPost['valTotalCuotaPost'],
                'fecha_max_recaudo' => NULL,
            );

            /*//DEUDOR
            $dataUploadDeudor = array(
                'id_status_perfil_deduor' => '1',
            );
            //CODEUDOR
            $dataUploadCodeudor = array(
                'id_status_perfil_codeudor' => '1',
            );
            //REF PERSONAL
            $dataUploadRefPerso = array(
                'id_status_perfil_referencia_personal' => '1',
            );
            //REF FAMILIAR
            $dataUploadRefFami = array(
                'id_status_perfil_referencia_familiar' => '1',
            );
            //REF COMERCIAL
            $dataUploadRefComer = array(
                'id_status_perfil_referencia_comercial' => '1',
            );*/
        }
        //echo "<pre>";
        ///print_r($dataPackDotacion);
        //$idStore_order = $db->insert('account_empresa', $dataInsert);
        //if($idStore_order == true){ 
        //$id= $db -> insert('deudor_tbl, $dataInsert');
        //if (!$id){

        $errInsertDatasRecaudos = array();
        //$db->where ('id_creditos',$idCreditoPost); 
        
        $ultimoCredito = $db->insert ('creditos_tbl', $dataCreditoInsert);
        if ($ultimoCredito){  


            /*--//ACTIVAR ESTADO DE DEUDOR Y REFERENCIAS//--*/
            /*//deudor
            if($idDeudorPost != ""){
                $db->where ('id_deudor',$idDeudorPost); 
                $db->update ('deudor_tbl', $dataUploadDeudor);                    
            }
            //codeudor
            if($idCodeudorPost != ""){
                $db->where ('id_codeudor',$idCodeudorPost); 
                $db->update ('codeudor_tbl', $dataUploadCodeudor);                    
            }
            //ref personal
            if($idRefPersoPost != ""){
                $db->where ('id_referencia_personal',$idRefPersoPost); 
                $db->update ('referencia_personal_tbl', $dataUploadRefPerso);                    
            }
            //ref familiar
            if($idRefFamiPost != ""){
                $db->where ('id_referencia_familiar',$idRefFamiPost); 
                $db->update ('referencia_familiar_tbl', $dataUploadRefFami);                    
            }

            //ref comercial
            if($idRefComerPost != ""){
                $db->where ('id_referencia_comercial',$idRefComerPost); 
                $db->update ('referencia_comercial_tbl', $dataUploadRefComer);                    
            }*/


            /*--//CREAR PLAN DE PAGOS//--*/
            //PLAN PAGOS
            $dataPlanPagoInsert['id_credito'] = $ultimoCredito;
            $newPlanPagos = $db -> insert('planes_pago_tbl', $dataPlanPagoInsert);

            if($newPlanPagos){

                $cuotasInsertArr = array();
                $cuotasInsertArr = $diasdePago;
                $posiCuota = 1;
                foreach($diasdePago as $cinsKey){

                    //foreach($cinsKey as $cinsVal){

                        //$fechaCuotaIns = $cinsKey['fechacuota'];
                        $fechaCuotaIns = $cinsKey;
                    //}

                    $dataRecaudosInsert['id_plan_pago'] = $newPlanPagos;
                    $dataRecaudosInsert['numero_cuota_recaudos'] = $posiCuota;
                    $dataRecaudosInsert['fecha_max_recaudo'] = $fechaCuotaIns;

                    //RECAUDOS
                    $newRecaudo = $db -> insert('recaudos_tbl', $dataRecaudosInsert);

                    if(!$newRecaudo){

                        $errInsertDatasRecaudos[] = $db->getLastErrno();

                    }

                    $posiCuota++;
                }

                if(is_array($errInsertDatasRecaudos) && count($errInsertDatasRecaudos) > 0){
                    $errQueryTmpl_ins ="<ul class='list-group text-left'>";
                    $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                        <br>Wrong: <b>No fue posible crear estos recaudos</b>";
                    foreach($errInsertDatasRecaudos as $errRKey){
                        $errQueryTmpl_ins .="<br>Erro: ".$errRKey;
                    }
                    $errQueryTmpl_ins .="<br>Puedes intentar de nuevo
                        <br>Si el error continua, por favor entre en contacto con soporte</li>";
                    $errQueryTmpl_ins .="</ul>";

                    $response['error']= $errQueryTmpl_ins;    
                }else{

                    unset($_SESSION['newitem']);
                    $_SESSION['newitem'] = NULL; 
                    $response=$ultimoCredito;   
                }


            }else{
                //$response['error'] = "Error al insertar el deudor: ".$db->getLastQuery() ."\n". $db->getLastError();
                $errInsertDatas = $db->getLastErrno();

                $errQueryTmpl_ins ="<ul class='list-group text-left'>";
                $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible crear el plan de pagos</b>
                    <br>Erro: ".$errInsertDatas."
                    <br>Puedes intentar de nuevo
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";
                $errQueryTmpl_ins .="</ul>";

                $response['error']= $errQueryTmpl_ins;    
            }



        }else{

            //$response['error'] = "Error al insertar el deudor: ".$db->getLastQuery() ."\n". $db->getLastError();
            $errInsertDatas = $db->getLastErrno();

            $errQueryTmpl_ins ="<ul class='list-group text-left'>";
            $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No fue posible crear el credito</b>
                <br>Erro: ".$errInsertDatas."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl_ins .="</ul>";

            $response['error']= $errQueryTmpl_ins;
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
                    case 'id_credito' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Credito</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error en el momento de crear este credito, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;                        
                        
                    case 'id_deudor' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Deudor</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error con el deudor, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;                        
                        
                    case 'id_acreedor' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Acreedor</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error con la identificación de  tu cuenta de usuario, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;
                        
                    case 'id_codeudor' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Codeudor</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error con la identificación del codeudor, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;
                        
                    case 'id_refperso' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Ref. Personal</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error con la identificación de la referencia personal, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;
                        
                    case 'id_reffami' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Ref.Familiar</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error con la identificación de la referencia familiar, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;
                    
                    case 'id_refcomer' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Ref.Comercial</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error con la identificación de la referencia comercial, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;
                        
                    case 'montoPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Valor a prestar</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break;      
                    case 'valorPagarPost' :  
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Valor a pagar</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break;      
                    case 'utilidadPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Utilidad</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break;
                        
                    case 'tipoCreditoPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Tipo credito</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Campo obligatorio                        
                        <br>Selecciona una de las opciones de tipo de credito</li>";
                    break;                        
                    case 'descriCreditoPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Descripción de credito</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Escribe un texto descriptivo para este credito, puedes usar letras, números y signos de puntuación
                        <br>Max. 200 carácteres</li>";
                    break;
                    case 'capitalCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Capital a la cuota</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break;
                    case 'interesCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Interes de la cuota</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break;
                    case 'moraCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Mora de la cuota</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break;  
                    case 'sobCargoCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Sobre cargo de la cuota</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break; 
                    case 'numeCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>No. cuotas del credito</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break; 
                    case 'fechaInicioCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Fecha inicio credito</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatorio
                        <br>Escribe la fecha de inicio del credito
                        <br>Formato: dd/mm/yyyy</li>";
                    break;
                    case 'fechaFinCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Fecha fin credito</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatorio
                        <br>Escribe la fecha final del credito
                        <br>Formato: dd/mm/yyyy</li>";
                    break;
                    case 'periocidadCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Periocidad pago de cuotas</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Selecciona una de las opciones de la periocidad de pago</li>";
                    break;
                    case 'valTotalCuotaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Valor total de la cuota</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un valor numerico, no uses puntuación 
                        <br>Max. 9 carácteres</li>";
                    break;
                    case 'statusItemPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Status del credito</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Selecciona una de las opciones disponibles del status del credito</li>";
                    break;
                    case 'cobradorPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Cobrador</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escoge y asigna un cobrador para este credito</li>";
                    break;
                    
                }
            }
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    echo json_encode($response);
    
}
