<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once "../lib/gump.class.php";
require_once "site-tools.php"; 
require_once '../lib/password.php';


//========================================
//========================================
//CRUD ACCOUNT PROFILE
//========================================
//========================================
    
$fieldPost = empty($_POST['fieldedit']) ? "" : $_POST['fieldedit'];    
$response = array();

$errpass= "";
$erralias= "";

$validated_pass = "";
$validated_alias = "";

$personal_POST = "";
    
//////////////////////////////////
//================EDIT ITEM
//////////////////////////////////
    
if(isset($fieldPost) && $fieldPost == "edititem"){
    
    //***********
    //RECIBE DATOS A EDITAR
    //***********
    
    //"nameuser_data="+nameuser+"&lastnameuser_data="+lastnameuser+"&companyuser_data="+companyuser+"&pseudouser_data="+pseudouser+"&passuser_data="+passuser+"&replypassuser_data="+replypassuser+"&datapass_data="+datapass+"&datapost_data="+datapost+"&fieldedit=edititem"+"&editalias="+editpseudouser;
    
    
    $idItemPOST = empty($_POST['datapost_data'])? "" : $_POST['datapost_data'];
    $nameItemPOST = empty($_POST['nameuser_data'])? "" : $_POST['nameuser_data'];
    $lastNamePOST = empty($_POST['lastnameuser_data'])? "" : $_POST['lastnameuser_data'];
    $companyuserPOST = empty($_POST['companyuser_data'])? "" : $_POST['companyuser_data'];
    
    
    $pseudouserPOST = empty($_POST['pseudouser_data'])? "" : $_POST['pseudouser_data'];
    $editAliasPOST = empty($_POST['editalias'])? "" : $_POST['editalias'];
    
    $passuserPOST = empty($_POST['passuser_data'])? "" : $_POST['passuser_data'];
    $replypassuserPOST = empty($_POST['replypassuser_data'])? "" : $_POST['replypassuser_data'];
    $datapassPOST = empty($_POST['datapass_data'])? "" : $_POST['datapass_data'];
    
    //***********
    //VALIDACION CONTRASEÑA
    //***********
    if($passuserPOST != "" || $replypassuserPOST !="" /*|| $datapassPOST == "editpass"*/){    
        //echo "<br>entro en pass";
        $passInput = array(      
            'iditem'=> $idItemPOST,
            'passone'=> $passuserPOST,
            'passtwo' => $replypassuserPOST          
        );		
        
        $rules_pass = array(           
            'iditem'=> 'required|integer',
            'passone' => 'required|pass|max_len,12|min_len,5',
            'passtwo' => 'required|equalsfield,passone'
        );

        $filters_pass = array(
            'iditem'=> 'trim|sanitize_string',
            'passone' => 'trim|sanitize_string',
            'passtwo' => 'trim|sanitize_string'            
        );
        $passInput = $validfield->sanitize($passInput); 
        $validated_pass = $validfield->validate($passInput, $rules_pass);
        $passInput = $validfield->filter($passInput, $filters_pass);
        
        //$passUserIns = $passuserPOST;
        
        if($validated_pass === TRUE){
            //$errpass= "error";
            
            //***********
            //INSERTAR DATAS NUEVO ITEN
            //***********
                        
            foreach($passInput as $passKey){
                $dataPassItem = array(                                                
                    'clave_manager'=> password_hash($passInput['passone'], PASSWORD_BCRYPT, array("cost" => 10))
                );                                
            }

            $db->where ('id_manager', $idItemPOST ); 
            $update_pass = $db->update ('manager_account', $dataPassItem);
            if (!$update_pass){     
                /*$response = $idItemPOST;                
            }else{ */           
                $erroQuery_pass = $db->getLastErrno();   

                $errQueryTmpl_pass ="<ul class='list-group text-left'>";
                $errQueryTmpl_pass .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No se pudo modificar la contraseña</b>
                    <br>Erro: ".$erroQuery_pass."
                    <br>Puedes intentar de nuevo
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";
                $errQueryTmpl_pass .="</ul>";

                $response['error']= $errQueryTmpl_pass;
            }
        }else{
            
            $errQueryTmpl_pass ="<ul class='list-group text-left'>";

            //errores de validacion
            $recibeRules = array();
            $recibeRules[] = $validated_pass;

            foreach($recibeRules as $keyRules => $valRules){
                if(is_array($valRules)){
                    foreach($valRules as $key => $v){

                        $errFiel = $v['field'];
                        $errValue = $v['value'];
                        $errRule = $v['rule'];
                        $errParam = $v['param'];

                        switch($errFiel){
                            case 'iditem' :
                                $errQueryTmpl_pass .="<li class='list-group-item list-group-item-danger'><b>No existe el ID del item que deseas publicar</b></li>";
                            break;                        
                            case 'passone' :
                                $errQueryTmpl_pass .="<li class='list-group-item list-group-item-danger'><b>Nueva contraseña</b>
                                <br>Regras:
                                <br>Escribe una contraseña valida
                                <br>Puedes usar letras, números, y los caracteres (!@#$%&()+.-_)
                                <br>Min. 5 caracteres
                                <br>Max. 12 caracteres</li>";
                            break;
                            case 'passtwo' :
                                $errQueryTmpl_pass .="<li class='list-group-item list-group-item-danger'><b>Confirmar contraseña</b>
                                <br>Regras:
                                <br>Debes confirmar tu contraseña
                                <br>Las contraseñas deben ser iguales</li>";
                            break;

                        }
                    }
                }

            }

            $errQueryTmpl_pass .="</ul>";  

            $response['error']= $errQueryTmpl_pass;
            //print_r($validated_pass);
        }

        //echo json_encode($response);
        
    }//FIN VALIDACION CONTRASEÑA
    
    
    //***********
    //VALIDACION PSEUDO USER
    //***********
    if(isset($editAliasPOST) && $editAliasPOST == "ok"){    
        //echo "<br>entro en user";
        $aliasInput = array( 
            'iditem'=> $idItemPOST,
            'userpost'=> $pseudouserPOST            
        );		
        
        $rules_alias = array(   
            'iditem'=> 'required|integer',
            'userpost' => 'required|user|max_len,12|min_len,3',
        );

        $filters_alias = array(
            'iditem'=> 'trim|sanitize_string',
            'userpost' => 'trim|sanitize_string'              
        );
        $aliasInput = $validfield->sanitize($aliasInput); 
        $validated_alias = $validfield->validate($aliasInput, $rules_alias);
        $aliasInput = $validfield->filter($aliasInput, $filters_alias);
        
        //$aliasUserIns = $pseudouserPOST;
        if($validated_alias === TRUE){
            //$erralias= "error";
            //***********
            //INSERTAR DATAS NUEVO ITEN
            //***********
                        
            foreach($aliasInput as $aliasKey){
                $dataAliasItem = array(                                                
                    'usuario_manager'=> $aliasInput['userpost']
                );                                
            }

            $db->where ('id_manager', $idItemPOST ); 
            $update_alias = $db->update ('manager_account', $dataAliasItem);
            if (!$update_alias){     
                /*$response = $idItemPOST;                
            }else{ */           
                $erroQuery_alias = $db->getLastErrno();   

                $errQueryTmpl_alias ="<ul class='list-group text-left'>";
                $errQueryTmpl_alias .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No se pudo modificar el Alias o nombre de usuario</b>
                    <br>Erro: ".$erroQuery_alias."
                    <br>Puedes intentar de nuevo
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";
                $errQueryTmpl_alias .="</ul>";

                $response['error']= $errQueryTmpl_alias;
            }
        }else{
            
            $errQueryTmpl_alias ="<ul class='list-group text-left'>";

            //errores de validacion
            $recibeRules = array(); 
            $recibeRules[] = $validated_alias;

            foreach($recibeRules as $keyRules => $valRules){
                if(is_array($valRules)){
                    foreach($valRules as $key => $v){

                        $errFiel = $v['field'];
                        $errValue = $v['value'];
                        $errRule = $v['rule'];
                        $errParam = $v['param'];

                        switch($errFiel){
                            case 'iditem' :
                                $errQueryTmpl_alias .="<li class='list-group-item list-group-item-danger'><b>No existe el ID del item que deseas publicar</b></li>";
                            break;                        
                            case 'userpost' :
                                $errQueryTmpl_alias .="<li class='list-group-item list-group-item-danger'><b>Alias</b>
                                <br>Regras:
                                <br>Escribe un nombre de usuario valido
                                <br>Puedes usar letras, números, y los caracteres (_ - .)
                                <br>Min. 3 caracteres</li>
                                <br>Max. 12 caracteres</li>";
                            break;
                            
                        }
                    }
                }

            }

            $errQueryTmpl_alias .="</ul>";    

            $response['error']= $errQueryTmpl_alias;
        }

        //echo json_encode($response);
    }
    
            
    //***********
    //VALIDACION INFO PERSONAL
    //***********
    
    $personal_POST = array(      
        'iditem'=> $idItemPOST,
        'nome_manager' => $nameItemPOST,
        'apellido_manager' => $lastNamePOST,
        'name_company_account' => $companyuserPOST
	);		
		        
	$rules = array(
        'iditem'=> 'integer',        
        'nome_manager' => 'alpha_space|max_len,40',
        'apellido_manager' => 'alpha_space|max_len,40',
        'name_company_account' => 'alpha_space|max_len,40'
	);
    
	$filters = array(
        'iditem'=> 'trim|sanitize_string',
        'nome_manager' => 'trim|sanitize_string',
        'apellido_manager' => 'trim|sanitize_string',
        'name_company_account' => 'trim|sanitize_string'
	);
    
	$personal_POST = $validfield->sanitize($personal_POST); 
    $validated = $validfield->validate($personal_POST, $rules);
    $personal_POST = $validfield->filter($personal_POST, $filters);
    
    
    
    
    
    /*echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "===============================+++++++++++++++++++++++====================================";
    echo "<pre>";
    print_r($validated);
    echo "</pre>";*/
    
    //if($editAliasPOST =="" && $passuserPOST == "" || $replypassuserPOST ==""){
        
        //echo "entro en personal";
    
        //"""""""""" SI LOS DATOS ESTAN LIMPIOS
        if($validated === TRUE && $erralias !="error" && $errpass !="error"){

            //***********
            //INSERTAR DATAS NUEVO ITEN
            //***********
            $datasPOST = array();
            $datasPOST = $personal_POST;
            
            //foreach($datasPOST as $dpKey =>  $dpVal){
            foreach($datasPOST as $dpKey){
                $dataNewItem = array(                                                
                    'nome_manager'=> $datasPOST['nome_manager'],
                    'apellido_manager' => $datasPOST['apellido_manager'],
                    'name_company_account' =>$datasPOST['name_company_account']
                );                                
            }

            $db->where ('id_manager', $idItemPOST );
            $update_basic = $db->update ('manager_account', $dataNewItem);
            if (!$update_basic){                       
                /*//$response = true;                                                                    
                $response = $idItemPOST;

            }else{*/            
                $erroQuery_NI = $db->getLastErrno();   

                $errQueryTmpl_NI ="<ul class='list-group text-left'>";
                $errQueryTmpl_NI .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No se pudo guardar este item</b>
                    <br>Erro: ".$erroQuery_NI."
                    <br>Puedes intentar de nuevo
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";
                $errQueryTmpl_NI .="</ul>";


                $response['error']= $errQueryTmpl_NI;

            }

        //"""""""""" SI LOS DATOS ESTAN CORROMPIDOS
        }else{
                    
            $errQueryTmpl ="<ul class='list-group text-left'>";

            //errores de validacion
            //$recibeRules = array();
            $recibeRules[] = $validated;
            //$recibeRules[] = $validated_pass;
            //$recibeRules[] = $validated_alias;

            foreach($recibeRules as $keyRules => $valRules){
                if(is_array($valRules)){
                    foreach($valRules as $key => $v){

                        $errFiel = $v['field'];
                        $errValue = $v['value'];
                        $errRule = $v['rule'];
                        $errParam = $v['param'];

                        switch($errFiel){
                            case 'iditem' :
                                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>No existe el ID del item que deseas publicar</b></li>";
                            break;                        
                            case 'nome_manager' :
                                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>Nombre</b>
                                <br>Regras:
                                <br>Escribe letras y números
                                <br>Max. 40 caracteres</li>";

                            break;
                            case 'apellido_manager' :
                                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>Apellido</b>
                                <br>Regras:
                                <br>Escribe letras y números
                                <br>Max. 40 caracteres</li>";

                            break;
                            case 'name_company_account' :
                                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>Nombre empresa</b>
                                <br>Regras:
                                <br>Escribe letras y números
                                <br>Max. 40 caracteres</li>";

                            break;
                                
                            case 'userpost' :
                                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>Alias</b>
                                <br>Regras:
                                <br>Escribe un nombre de usuario valido
                                <br>Puedes usar letras, números, y los caracteres (_ - .)
                                <br>Min. 3 caracteres</li>
                                <br>Max. 12 caracteres</li>";
                            break;
                                
                            case 'passone' :
                                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>Nueva contraseña</b>
                                <br>Regras:
                                <br>Escribe una contraseña valida
                                <br>Puedes usar letras, números, y los caracteres (!@#$%&()+.-_)
                                <br>Min. 5 caracteres
                                <br>Max. 12 caracteres</li>";
                            break;
                            case 'passtwo' :
                                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>Confirmar contraseña</b>
                                <br>Regras:
                                <br>Debes confirmar tu contraseña
                                <br>Las contraseñas deben ser iguales</li>";
                            break;

                        }
                    }
                }

            }

            $errQueryTmpl .="</ul>";   

            //$response['error']= $errQueryTmpl;
            //print_r($recibeRules);
        }
    //}
    
    
    
        
    echo json_encode($response);
    //exit(json_encode($response));
        
}//=========================EDIT ITEM