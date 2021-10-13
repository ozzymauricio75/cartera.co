<?php 
//QUERY CONFIGURACIONES DE USUARIO
$define_user_idioma = "";
$define_user_timezone ="";
$define_user_moneda = "";
$define_user_sabados = "";
$define_user_domingos = ""; 
$define_user_festivos = "";
$define_user_sabados_dia = "";
$define_user_domingos_dia = "";
$define_user_festivos_dia = "";
$define_user_cuadrediario = "";
$define_user_capital_inicial = "";

$db->where("id_usuario_plataforma", $idSSUser);
$queryTbl = $db->get("usuario_configuraciones_tbl");

$rowQueryTbl = count($queryTbl);
if ($rowQueryTbl > 0){        
    foreach ($queryTbl as $qKey) {             
        $status_confi_user = ($qKey["status_configuraciones"] == 0)? 0 : $qKey["status_configuraciones"];
        $define_user_idioma = empty($qKey["define_idioma"])? "" : $qKey["define_idioma"];
        $define_user_timezone = empty($qKey["define_zona_horaria"])? "" : $qKey["define_zona_horaria"];
        $define_user_moneda = empty($qKey["define_moneda"])? "" : $qKey["define_moneda"];
        $define_user_sabados_dia = ($qKey["define_sabado_diaria"] == 0)? 0 : $qKey["define_sabado_diaria"];
        $define_user_domingos_dia = ($qKey["define_domingo_diaria"] == 0)? 0 : $qKey["define_domingo_diaria"]; 
        $define_user_festivos_dia = ($qKey["define_festivos_diaria"] == 0)? 0 : $qKey["define_festivos_diaria"]; 
        $define_user_sabados = ($qKey["define_sabados"] == 0)? 0 : $qKey["define_sabados"];
        $define_user_domingos = ($qKey["define_domingos"] == 0)? 0 : $qKey["define_domingos"]; 
        $define_user_festivos = ($qKey["define_festivos"] == 0)? 0 : $qKey["define_festivos"]; 
        $define_user_cuadrediario = ($qKey["define_cuadre_caja_diario"] == 0)? 0 : $qKey["define_cuadre_caja_diario"];
        $define_user_capital_inicial = ($qKey["define_capital_inicial"] == 0)? 0 : $qKey["define_capital_inicial"];
    }    
}


//////////////////////////////////////
//DEFINE CONFIGURACONES DE USUARIO
//////////////////////////////////////

//USER OPTIONS -> CURRENCY | LANG | DATE
define('COUNTRY', "Colombia");
define('REGION', "CO");
define('LANG',"es_419");  // es_419 | pt_BR | en_US
date_default_timezone_set('America/Bogota'); // 
setlocale (LC_ALL,'es_419');
$defMoneda = "$"; //$ peso | U$ dollar | R$ Real | &pound; libra esterlina  | &euro; Euro  
define('CURRENCYSITE', $defMoneda);	

//DEFINE fechas y horas
$dateTime = new DateTime();
$timeStamp = $dateTime->getTimestamp();
$dateFormatDB = $dateTime->format('Y-m-d');
$horaFormatDB = $dateTime->format('H:i:s');
$dateFormatHuman = $dateTime->format('d/m/Y');
$dateFormatPost = $dateTime->format('YmdHis');

// DEFINE DIA
$dias_es_ES = array("domingo","lunes","martes","mi&eacute;rcoles","jueves","viernes","s&aacute;bado");

$dias_pt_PT = array("domingo","segunda-feira","terça-feira","quarta-feira","quinta-feira","sexta-feira","s&aacute;bado");

$dias_en_EN = array("sunday","monday","tuesday","wednesday","thursday","friday","saturday");



$nombre_dia = $dias_es_ES[date("w")];//date("l");

//ACTIVA <--> DESACTIVA OPCIONES PERSONALIZADAS

//checkbox
$checkbox_sabado = "";
$checkbox_domingo = "";
$checkbox_festivos = "";
$checkbox_sabado_dia = "";
$checkbox_domingo_dia = "";
$checkbox_festivo_dia = "";
$checkbox_caja = "";
$activa_capital_input = "";

//inputs hidden
$input_sabado = "";
$input_domingo = "";
$input_festivos = "";
$input_sabado_dia = "";
$input_domingo_dia = "";
$input_festivo_dia = "";
$input_caja = "";


//DIAS HABILES
$activarSabado = "off";//true | false
$activarDomingos = "off";//true | false
$activarFestivos = "off";//true | false

$activarSabado_dia = "off";//true | false
$activarDomingos_dia = "off";//true | false
$activarFestivos_dia = "off";//true | false

$array_fines_semana = array(6,7);

if($define_user_sabados == "1"){
    $activarSabado = "on";    
    $checkbox_sabado = "checked";
    $input_sabado = "ok";
    //$array_fines_semana = $activarSabado;
    //array_push($array_fines_semana, 6);
    $array_fines_semana = array(7);
}
if($define_user_domingos == "1"){
    $activarDomingos = "on";
    $checkbox_domingo = "checked";
    $input_domingo = "ok";
    //$array_fines_semana = $activarDomingos;
    //array_push($array_fines_semana, 7);
    $array_fines_semana = array(6);
}
if($define_user_festivos == "1"){
    $activarFestivos = "on";
    $input_festivos = "ok";
    $checkbox_festivos = "checked";
}


if($define_user_sabados_dia == "1"){
    $activarSabado_dia = "on";
    $checkbox_sabado_dia = "checked";
    $input_sabado_dia = "ok";
    //array_push($array_fines_semana, 6);
}
if($define_user_domingos_dia == "1"){
    $activarDomingos_dia = "on";
    $checkbox_domingo_dia = "checked";
    $input_domingo_dia = "ok";
    //array_push($array_fines_semana, 7);
}
if($define_user_festivos_dia == "1"){
    
    $activarFestivos_dia = "on";
    $input_festivo_dia = "ok";
    $checkbox_festivo_dia = "checked";
}

//DEFINE CUADRE DE CAJA
$cuadre_diario = "off";
if($define_user_cuadrediario == "1"){
    $cuadre_diario = "on";
    $input_caja = "ok";
    $checkbox_caja = "checked";
}

//DEFINE CAPITAL INCIIAL
$capital_inicial = "off";
$capital_inicial_val = 0;
if($define_user_capital_inicial != 0){
    $capital_inicial = "on";
    $activa_capital_input = "disabled";
    $capital_inicial_val =  number_format($define_user_capital_inicial, 0,",","."); 
    
}
