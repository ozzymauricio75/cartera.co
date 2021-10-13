<?php

//define datos
$sectionDestiny = "";
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
        'userlogin' =>'required|user|max_len,15|min_len,4', 
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
        
        
        //SELECT `id_usuario`, `id_usuario_plataforma`, `id_status_usuario`, `nombre_usuario`, `cedula_usuario`, `mail_usuario`, `nickname_usuario`, `pass_usuario`, `pass_humano_usuario`, `tel_uno_usuario`, `tel_dos_usuario`, `direccion_usuario`, `ciudad_usuario`, `estado_usuario`, `pais_usuario`, `foto_usuario`, `fecha_alta_usuario`, `tag_seccion_plataforma` FROM `usuario_tbl` WHERE 1
        
        $db->where ('nickname_usuario', $userDB);       
        $loginUser = $db->getOne('usuario_tbl', 'id_usuario, id_usuario_plataforma, id_status_usuario, nickname_usuario, pass_usuario, pass_humano_usuario, tag_seccion_plataforma'); 
        
        if($loginUser){
            
            //DATOS CUENTA USUARIO
            $idDBUser = $loginUser['id_usuario'];            
            $idPlataformaDBUser = $loginUser['id_usuario_plataforma'];
            $pseudoDBUser = $loginUser['nickname_usuario'];                       
            $passUser = $loginUser['pass_usuario'];
            $passUserHuman = $loginUser['pass_humano_usuario'];  
            $estadoDBUser = $loginUser['id_status_usuario'];
            $tagUsuarioDBUser = $loginUser['tag_seccion_plataforma'];
            
            //DATOS USUARIO ACREEDOR
            $nameDBUser = "";
            $emailDBUser = ""; 
            
            if($tagUsuarioDBUser == "cobrador"){
                $db->where ('id_cobrador', $idPlataformaDBUser);                          
                $datasUser = $db->getOne('cobrador_tbl', 'id_cobrador, nombre_cobrador, mail_cobrador');
                
                $nameDBUser = $datasUser['nombre_cobrador'];
                $emailDBUser = $datasUser['mail_cobrador'];
                
            }elseif($tagUsuarioDBUser == "acreedor"){
                $db->where ('id_acreedor', $idPlataformaDBUser);                          
                $datasUser = $db->getOne('acreedor_tbl', 'id_acreedor, primer_nombre_acreedor, primer_apellido_acreedor, email_acreedor'); 
                
                $nameDBUser = $datasUser['primer_nombre_acreedor']." ".$datasUser['primer_apellido_acreedor'];
                $emailDBUser = $datasUser['email_acreedor'];
            }
            
            
            switch($estadoDBUser){
                case "1":
                                        
                    //if($pseudoDBUser == $userDB && password_verify($passaDB, $passUser)){
                    if($userDB == $pseudoDBUser && $passaDB ==  $passUserHuman){

                        $_SESSION['cartera_user_account']=array(
                            'iduser' => $idDBUser,
                            'idplataformauser' => $idPlataformaDBUser,
                            'nicknameuser' => $pseudoDBUser,
                            'nameuser' => $nameDBUser,
                            'mailuser' => $emailDBUser,
                            'permisousuario' => $tagUsuarioDBUser
                        );
                        
                        switch($tagUsuarioDBUser){
                            case "cobrador":
                                $sectionDestiny = "/".$payinDir."/home/";       
                            break;
                            case "acreedor":
                                $sectionDestiny = "/home/";       
                            break;
                        }
                        
                        gotoRedirect($sectionDestiny);                         
                        exit();
                        
                    }else{
                        $errValidaTmpl .= "<section class='box50 padd-verti-xs'>";
                        $errValidaTmpl .= "<ul class='list-group text-left'>";
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>LogIn</b>
                            <br>Por favor verifica tu usuario y contraseña.</li>";
                        $errValidaTmpl .= "</ul>";
                        $errValidaTmpl .= "</section>";                
                    }
                    //fin login user                                        
                break;
                case "2":
                    $errValidaTmpl .= "<section class='box50 padd-verti-xs'>";
                    $errValidaTmpl .= "<ul class='list-group text-left'>";
                    $errValidaTmpl .= "<li class='list-group-item list-group-item-info'><b>LogIn</b>
                        <br>Esta cuenta de usuario ha sido cancelada o suspendida
                        <br>Si piensas que se trata de un error, por favor comunicate con soporte. </li>";
                    $errValidaTmpl .= "</ul>";
                    $errValidaTmpl .= "</section>";    
                break;
            }
            
            
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

