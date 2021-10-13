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

if(isset($fieldPost) && $fieldPost == "newcuadrecaja"){   

    //***********
    //RECIBE DATOS 
    //***********
    
    /*
    *IDS USUARIOS
    */
    $idAcreadorPost = (empty($_POST['codeuserpost']))? "" : $_POST['codeuserpost'];
        
    $recaudosPost = ($_POST['totalrecaudopost'] != "")? $_POST['totalrecaudopost'] : "";
    $gastosRecaudosPost = ($_POST['totalgastopost'] != "")? $_POST['totalgastopost'] : "";
    $descriRecaudosPost = ($_POST['descrirecaudopost'] != "")? $_POST['descrirecaudopost'] : "" ;
    $fechaRecaudosPost = ($_POST['fechacuadrepost'] != "0000-00-00")? $_POST['fechacuadrepost'] : "" ;
    
    $valorDisponibleActualPost = ($_POST['valdisponibleactualpost'] != "")? $_POST['valdisponibleactualpost'] : "";
    //=="undefined"
    
    //nuevo valor disponible
    $valorDisponible = $valorDisponibleActualPost;//$valorDisponibleActualPost + $recaudosPost - $gastosRecaudosPost;
    /*
    *VALIDA DATOS POST
    */
    
    $_POST = array( 
        'id_acreedor' => $idAcreadorPost,		
        'totalrecaudos' => $recaudosPost,
        'totalgastosrecaudos' => $gastosRecaudosPost,
        'descrirecaudos' => $descriRecaudosPost,
        'fecharecaudo' => $fechaRecaudosPost
    );
                
	$rules = array(
        'id_acreedor' => 'required|integer',		        
        'totalrecaudos' => 'required|float|max_len,9',		
        'totalgastosrecaudos' => 'required|float|max_len,9',	
        'descrirecaudos' => 'alpha_space',
        'fecharecaudo' => 'required|date'
    );
        
    
	$filters = array(
        'id_acreedor' => 'trim|sanitize_string',
        'totalrecaudos' => 'trim|sanitize_string',
        'totalgastosrecaudos' => 'trim|sanitize_string',        
        'descrirecaudos' => 'trim|sanitize_string',
        'fecharecaudo' => 'trim|sanitize_string'
    ); 
	
    
    $validated = $validfield->validate($_POST, $rules);            
    $_POST = $validfield->filter($_POST, $filters);
    $_POST = $validfield->sanitize($_POST); 
    
    //echo "<pre>";
    //print_r($_POST);
    //Check if validation was successful
            
    $candado = "on";
	if($validated === TRUE && $candado == "on"){
        
        $nuevoPost = array();
        $nuevoPost = $_POST;
        //foreach($nuevoPost as $valInsert => $valPost){
        foreach($nuevoPost as $valInsert){
            //CREDITO
            $dataCuadreIns = array(                                    
                'id_acreedor' => $nuevoPost['id_acreedor'],
                'status_caja_menor' => 1,
                'recaudo_total_caja_menor'=>  $nuevoPost['totalrecaudos'],    
                'gastos_total_caja_menor'=> $nuevoPost['totalgastosrecaudos'],
                'valor_disponible_caja_menor' => $valorDisponible,
                'descripcion_caja_menor'=> $nuevoPost['descrirecaudos'],  
                'fecha_cuadre_caja_menor' => $nuevoPost['fecharecaudo'],  
                'fecha_registro_cuadre_caja'=> $dateFormatDB, 
                'hora_cuadre_caja_menor'=> $horaFormatDB,
                'actividad_caja_menor' => "cuadre"
            );  
        }
        
        $newCuadreCaja = $db -> insert('caja_menor_tbl', $dataCuadreIns);
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
                        <br>Parece que ha ocurrido un error en el momento de cuadrar caja, por favor, da click en HOME del menu lateral e intentalo de nuevo</li>";
                    break;                        
                        
                    case 'totalrecaudos' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Ingresos</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Selecciona uno de los ingresos disponibles</li>";
                    break;                        
                        
                    case 'totalgastosrecaudos' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Gastos</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe el total de gastos que tuviste el dia de hoy
                        <br>Escribe un valor numerico</li>";
                    break;
                        
                    case 'descrirecaudos' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Descripción</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Parece que estas escribiendo caracteres prohibidos. Puedes usar letras, números, y signos de puntuación</li>";
                    break;
                    
                    case 'fecharecaudo' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Fecha ingresos</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatorio
                        <br>Selecciona una de las opciones disponibles de resumen de ingresos</li>";
                    break;
                        
                        
                }
            }            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    echo json_encode($response);    
}
