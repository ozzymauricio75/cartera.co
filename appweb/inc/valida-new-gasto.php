<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once 'sessionvars-payin.php';
require_once 'query-custom-user.php';
require_once "../lib/gump.class.php";
require_once "site-tools.php"; 

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
    $idAcreedor = (empty($_POST['prestavarpost']))? "" : $_POST['prestavarpost']; 
    $idCobradorPost = (empty($_POST['uservarpost']))? "" : $_POST['uservarpost'];     
    $refRutaPost = (empty($_POST['refrutapost']))? "" : $_POST['refrutapost'];    
                                
    /*
    *VALOR RECIBIDO
    */
    $valorGastosPost = (empty($_POST['valorgastospost']))? "" : $_POST['valorgastospost']; //VALOR RECIBIDO EN EL PAGO
    $comentariosPost = empty($_POST['descrigastospost'])? "" : $_POST['descrigastospost'];                                  
                
    /*
    *VALIDA DATOS POST
    */
    
    $_POST = array( 
        'idacreedor' => $idAcreedor,
        'idCobradorPost' => $idCobradorPost,        
        'refRutaPost' => $refRutaPost,        
        'valorGastosPost' => $valorGastosPost,
        'comentariospost' => $comentariosPost
    );
                
	$rules = array(
        'idacreedor' => 'required|integer',	        
        'idCobradorPost' => 'required|integer',	        
        'refRutaPost' => 'required|alpha_space',        
        'valorGastosPost' => 'required|float',
        'comentariospost' => 'alpha_space'
    );
        
    
	$filters = array(
        'idacreedor' => 'trim|sanitize_string',
        'idCobradorPost' => 'trim|sanitize_string',        
        'refRutaPost' => 'trim|sanitize_string',        
        'valorGastosPost' => 'trim|sanitize_string',
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
        *GENERA ARRAY PARA INGRESAR GASTOS
        */
        $nuevoPost = array();
        $nuevoPost = $_POST;
       
        //foreach($nuevoPost as $valInsert => $valPost){
        foreach($nuevoPost as $valInsert){
            $dataInsert = array(  
                'id_acreedor'=> $nuevoPost['idacreedor'],
                'id_cobrador' =>  $nuevoPost['idCobradorPost'],
                'consecutivo_ruta' => $nuevoPost['refRutaPost'],
                'total_valor_gastos' =>$nuevoPost['valorGastosPost'],
                'comentarios_gastos'=>  $nuevoPost['comentariospost'],
                'fecha_gastos'=>  $dateFormatDB
            );                          
        }
        
        $insertaGasto = $db->insert('gastos_tbl', $dataInsert);

        if(!$insertaGasto){
            $queryUpdate_err = $db->getLastErrno();

            //$errQueryTmpl_Foto ="<ul class='list-group text-left'>";
            $errQuery_lyt[] = "<p class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No fue posible guardar el registro de gastos</b>
                <br>Erro: ".$queryUpdate_err."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</p>";
            //$errQueryTmpl_Foto .="</ul>";

            $response['error'] = $errQuery_lyt;
        }else{
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
                        <br>No logramos identificar tu usuario, intenta recargar la pagina </li>";
                    break;                        
                        
                    case 'idacreedor' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Acreedor</b>
                        <br>".$usertyped."
                        <br>No logramos identificar el acreedor, intenta recargar la pagina </li>";
                    break;                        
                        
                    case 'refRutaPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Ruta</b>       
                        <br>".$usertyped."
                        <br>No logramos identificar la ruta que estas realizando recaudos, intenta recargar la pagina</li>";
                    break;
                                                                    
                    case 'valorGastosPost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Valo gastos</b>
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
                        <br>Escribe observaciones o anotaciones importantes o que detallen los gastos que tuviste en este día
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
