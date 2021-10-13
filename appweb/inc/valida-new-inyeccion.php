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
    
$fieldPost = $_POST['fieldeditpost'];
$response = array();
$fileValida = "";

if(isset($fieldPost) && $fieldPost == "newinyeccion"){   

    //***********
    //RECIBE DATOS 
    //***********
    
    /*
    *IDS USUARIOS
    */
    
    $idAcreadorPost = (empty($_POST['codeuserpost']))? "" : $_POST['codeuserpost'];        
    $inyeccionPost = (empty($_POST['totalinyectapost']))? "" : redondear_valor($_POST['totalinyectapost']);
    
    /*
    *VALIDA DATOS POST
    */
    
    $_POST = array( 
        'id_acreedor' => $idAcreadorPost,		
        'totalinyeccion' => $inyeccionPost
    );
                
	$rules = array(
        'id_acreedor' => 'required|integer',		        
        'totalinyeccion' => 'required|float|max_len,9'
    );
        
    
	$filters = array(
        'id_acreedor' => 'trim|sanitize_string',
        'totalinyeccion' => 'trim|sanitize_string'
    ); 
	
    $_POST = $validfield->sanitize($_POST); 
    $validated = $validfield->validate($_POST, $rules);
    $_POST = $validfield->filter($_POST, $filters);
    
    //echo "<pre>";
    ///print_r($validated);
    // Check if validation was successful
            
    $candado = "on";
	if($validated === TRUE && $candado == "on"){
        
        $nuevoPost = array();
        $nuevoPost = $_POST;
        //foreach($nuevoPost as $valInsert => $valPost){
        foreach($nuevoPost as $valInsert){
            //CREDITO
            $dataCuadreIns = array(                                    
                'id_acreedor' => $nuevoPost['id_acreedor'],                
                'valor_inyeccion_capital'=>  $nuevoPost['totalinyeccion'],                    
                'fecha_inyeccion_capital'=> $dateFormatDB, 
                'hora_inyeccion_capital'=> $horaFormatDB                   
            );  
        }
        
        $newCuadreCaja = $db -> insert('inyeccion_capital_tbl', $dataCuadreIns);
        $errInsertDatas = $db->getLastErrno();
        
        if($newCuadreCaja){
            $response = true;
        }else{
            
            $errQueryTmpl_ins ="<ul class='list-group text-left'>";
            $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No fue posible guardar tu información</b>
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
                        
                    case 'id_acreedor' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Usuario</b>
                        <br>".$usertyped."
                        <br>No logramos identificar el usuario. Intenta iniciar session nuevamente</li>";
                    break;                        
                        
                    case 'totalinyeccion' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Capital</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe el valor del capital que deseas inyectar. Sólo puedes usar números</li>";
                    break;                         
                }
            }            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    echo json_encode($response);    
}
