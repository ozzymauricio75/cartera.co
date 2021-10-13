<?php

//define datos
$useraccount = "";
$passaccount = "";
$errValidaTmpl = "";
$status = 0;

if(isset($_POST['tologin']) && $_POST['tologin'] == "ok"){
	$validator = new GUMP();
    //$passhash = new PassHash();
    
	//recibe datos    
    $useraccount = empty($_POST['username'])? "" : $_POST['username'];        
    $passaccount = empty($_POST['passuser'])? "" : $_POST['passuser'];    
        
	$_POST = array(        
        'userlogin' => $useraccount,
        'passlogin' => $passaccount        
	);		
	
	$_POST = $validator->sanitize($_POST); 
    
    //$validator->validation_rules(array(
	$rules = array(        
        'userlogin' =>'required|user|max_len,12|min_len,4', 
        'passlogin' => 'required|pass|max_len,12|min_len,4'        
	);
    //$validator->filter_rules(array(
	$filters = array(        
        'userlogin' => 'trim|sanitize_string',
        'passlogin' => 'trim|sanitize_string'        
	);
	
    $validated = $validator->validate($_POST, $rules);
    $_POST = $validator->filter($_POST, $filters);
    
    
    // Check if validation was successful
	if($validated === TRUE){
                                		
        //valida info user
        $userDB = $db->escape($useraccount);
        $passaDB = $db->escape($passaccount);
                
        $db->where ('nickname_acreedor', $userDB);
        //SELECT `id_manager`, `nome_manager`, `apellido_manager`, `usuario_manager`, `clave_manager` id_estado FROM `manager_account` WHERE 1   
        //pass_acreedor`, `pass_humano_acreedor
        $db->where ('id_status_perfil_acreedor', 1);        
        $loginUser = $db->getOne('acreedor_tbl', 'id_acreedor, primer_nombre_acreedor, primer_apellido_acreedor, nickname_acreedor, pass_acreedor, email_acreedor, logo_acreedor'); 
        
        if($loginUser){
                        
            $idDBUser = $loginUser['id_acreedor'];                        
            $pseudoDBUser = $loginUser['nickname_acreedor'];
            $passUser = $loginUser['pass_acreedor'];            
            $nameDBUser = $loginUser['primer_nombre_acreedor'];
            $lastnameDBUser = $loginUser['primer_apellido_acreedor'];            
            $emailDBUser = $loginUser['email_acreedor'];
            //$companyDBUser = $loginUser['email_acreedor'];
            $imgDBUser = $loginUser['logo_acreedor'];
                                    
            if($pseudoDBUser == $userDB && password_verify($passaDB, $passUser)){
                
                $_SESSION['acreedor_user_account']=array(
                    'iduser' => $idDBUser,                    
                    'spseudouser' => $pseudoDBUser,
                    'nameuser' => $nameDBUser,
                    'lastnameuser' => $lastnameDBUser,
                    //'companyuser' => $companyDBUser,
                    'emailuser' => $emailDBUser,
                    'imguser' => $imgDBUser
                );

                //acti order

                //$lastOrderTemp = actiOrder($idDBUser, $nameDBUser);
                
                //if($lastOrderTemp !== false){
                if(is_array($_SESSION['acreedor_user_account'])){
                    $fileDestiny = "home/";
                    gotoRedirect($fileDestiny);   
                }else{
                    $errValidaTmpl .= "<section class='box50 padd-verti-xs'>";
                    $errValidaTmpl .= "<ul class='list-group text-left'>";
                    $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Algo salio mal</b>
                        <br>Opciones:
                        <br>Erro: ".$lastOrderTemp."
                        <br>Puedes intentar nuevamente
                        <br>Si este error continua, por favor comunicate con soporte</li>";
                    $errValidaTmpl .= "</ul>";
                    $errValidaTmpl .= "</section>";    
                }
               
                exit;
            }else{
                $errValidaTmpl .= "<section class='box50 padd-verti-xs'>";
                $errValidaTmpl .= "<ul class='list-group text-left'>";
                $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>LogIn</b>
                    <br>Por favor verifica tu usuario y contraseña.</li>";
                $errValidaTmpl .= "</ul>";
                $errValidaTmpl .= "</section>";                
            }
            //fin login user
                
        }else{//sino login

            $erroQuery = $db->getLastError();   

            if($erroQuery){            
                $errValidaTmpl .= "<section class='box50 padd-verti-xs'>";
                $errValidaTmpl .= "<ul class='list-group text-left'>";
                $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Algo salio mal</b>
                    <br>Opciones:
                    <br>Erro: ".$erroQuery."
                    <br>Puedes intentar nuevamente
                    <br>Si este error continua, por favor comunicate con soporte</li>";
                $errValidaTmpl .= "</ul>";
                $errValidaTmpl .= "</section>";
            }

            $errValidaTmpl .= "<section class='box50 padd-verti-xs'>";
            $errValidaTmpl .= "<ul class='list-group text-left'>";
            $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>LogIn</b>
                <br>Por favor verifica tu usuario y contraseña.</li>";
            $errValidaTmpl .= "</ul>";
            $errValidaTmpl .= "</section>";
        }

    }else{//si existen errores validacion de datos post

        $errValidaTmpl .= "<section class='box50 padd-verti-xs'>";
        $errValidaTmpl .= "<ul class='list-group text-left'>";

        //errores de validacion
        $valRules = array();
        $recibeRules = array();
        $recibeRules[] = $validated;

        $resuValidate = count($recibeRules);
        if($resuValidate>0){
            foreach($recibeRules as $keyRules => $valRules){     
                if (is_array($valRules)) {
                    foreach($valRules as $key => $v){

                        $errFiel = $v['field'];
                        $errValue = $v['value'];
                        $errRule = $v['rule'];
                        $errParam = $v['param'];

                        switch($errFiel){                            
                            case 'userlogin' :
                                $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Usuario</b>
                                <br>Escribe el nombre de usuario de tu cuenta</li>";

                            break;
                            case 'passlogin' :
                                $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Contraseña</b>
                                <br>Por favor verifica tu contraseña e intentalo de nuevo</li>";
                            break;                            
                        }//fin switch
                    }//fin foreach valores errores
                }//comprueba si existe un array o  tiene elementos el array

            }//fin foreach recibe erreres
        }//fin count errores

        $errValidaTmpl .= "</ul>";
        $errValidaTmpl .= "</section>";
    }//fin valida campos post
              
}//fin post formulario new registro

