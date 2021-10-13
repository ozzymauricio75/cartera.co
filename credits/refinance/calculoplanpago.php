<?php
require_once '../../appweb/lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once '../../appweb/inc/sessionvars.php';
require_once '../../appweb/inc/query-custom-user.php';
require_once "../../appweb/lib/gump.class.php";
require_once "../../appweb/inc/query-tablas-complementarias.php";

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
            if($activarFestivos == "on"){
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

/*$festivosYMD = array(
    '0'=>'2017-01-01',
    '1'=>'2017-01-09',
    '2'=>'2017-03-20',
    '3'=>'2017-04-13',
    '4'=>'2017-04-14',
    '5'=>'2017-05-01',
    '6'=>'2017-05-29',
    '7'=>'2017-06-19',
    '8'=>'2017-06-26',
    '9'=>'2017-07-03',
    '10'=>'2017-07-20',
    '11'=>'2017-08-07',
    '12'=>'2017-08-21',
    '13'=>'2017-10-16',
    '14'=>'2017-11-06',
    '15'=>'2017-12-13',
    '16'=>'2017-12-08',
    '17'=>'2017-12-25'
);
$festivos = array(
    '0'=>'01-01',
    '1'=>'01-09',
    '2'=>'03-20',
    '3'=>'04-13',
    '4'=>'04-14',
    '5'=>'05-01',
    '6'=>'05-29',
    '7'=>'06-19',
    '8'=>'06-26',
    '9'=>'07-03',
    '10'=>'07-20',
    '11'=>'08-07',
    '12'=>'08-21',
    '13'=>'10-16',
    '14'=>'11-06',
    '15'=>'12-13',
    '16'=>'12-08',
    '17'=>'12-25'
);*/



$response = array();

$valmontocreditoPost = (empty($_POST['variable1']))? "" : $_POST['variable1'];  
$fechainicialPost = (empty($_POST['variable2']))? "" : date("Y-m-d", strtotime(str_replace('/', '-', $_POST['variable2'])));
$plazoPost = (empty($_POST['variable3']))? "" : $_POST['variable3'];
$periocidadPost = (empty($_POST['variable4']))? "" : $_POST['variable4'];
$valorPagarPost = (empty($_POST['variable5']))? "" : $_POST['variable5'];


/*
$valmontocreditoPost = "1500000";  
$fechainicialPost = "2017-05-22";//"2017-04-15";
$plazoPost = "30";
$periocidadPost = "semana";
$valorPagarPost = "2350000";*/

if($valmontocreditoPost == "" || $fechainicialPost == "" || $plazoPost == "" || $periocidadPost == ""){
    //exit();
    $response['error']= "Por favor completa la información solicitada para realizar el calculo";
    echo json_encode($response);
    return;
}else{
    /*
    *GENERA ARRAYS CUOTAS
    */

    //DECLARA VARIABLES
    $fechaPost = $fechainicialPost;//**fecha incio credito
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
        }else if ($inhabil == "festivo"/* && $activarFestivos == "on"*/){
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

    //GENERA LAS CUOTAS RESPECTO A LA PERIOCIDAD SELECCIONADA
    $periocidad = $periocidadPost;    
    $arrayCuotas = array();
    $arrayCuotasDiarias = array();
    $posiArrayFecha = 1;
    
    
    foreach($arrayTotalFechas as $aqKey){
        foreach($aqKey as $aqVal){
            $fechaVal[] = $aqKey['fecha'];        
        }
    }
    
    $ultimaFechaPlazo = end($fechaVal);
    
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
    
    
    /*
    *CALCULO VALORES
    */
    
    
    //ULTIMA POSICION DE UN ARRAY
    /*
    $stack = array("naranja", "plátano", "manzana", "frambuesa");
    $fruit = array_pop($stack);
    */
    
    $numCuotas = 0;
    $numCuotas = count($diasdePago);
    
    $totalCuotas = 0;
    $flechaPlazo = ""; 
    $totalCapital = 0;
    $totalValorCuota = 0;
    
    if(is_array($diasdePago) && !empty($diasdePago)){
        /*foreach($diasdePago as $aqKey){
            foreach($aqKey as $aqVal){
                $fechaDiaPago[] = $aqKey['fechacuota'];
            }
        }*/
        
        $totalCuotas = $numCuotas;
        $flechaPlazo = array_pop($diasdePago); 
        $totalCapital = $valmontocreditoPost/$numCuotas;
        $totalValorCuota = $valorPagarPost/$numCuotas;
    }
    
    
    //FORMAT PRECIOS
    $flechaPlazoFormat = date("d/m/Y", strtotime($flechaPlazo));
    $totalCapitalFormat = redondear_valor($totalCapital); //number_format($totalCapital,2,".",","); 
    $totalValorCuotaRedonde = redondear_valor($totalValorCuota); //number_format($totalValorCuota,2,".",","); 
    $totalValorCuotaFormat = number_format($totalValorCuotaRedonde,0,".",","); 
    
    
    //DATA ARRAY RESPUESTA                              
    $dataResp = array(  
        'totalcuotas' => $totalCuotas,
        'fechaplazo' => $flechaPlazoFormat,
        'totalcapital' => $totalCapitalFormat,
        'totalcuotaval' => $totalValorCuotaFormat,
        'valorcapitalhidden'=> $totalCapital,
        'valorcuotahidden'=> $totalValorCuota
    );
        
    echo json_encode($dataResp);
}