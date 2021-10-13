<?php
require_once '../../appweb/lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once "../../appweb/lib/gump.class.php";
//require_once "../appweb/inc/site-tools.php"; 


//========================================
//========================================
//CHECK CEDULA USUARIO
//========================================
//========================================

$validfield = new GUMP();
$response = array();


/////////////////////////
//RECIBE DATOS 
/////////////////////////

$fieldValue = $_POST['value'];
$fieldEditPost = $_POST['fieldedit'];
$idPost = $_POST['post'];

/*--//PARA EL DEUDOR//--*/

if(isset($fieldEditPost) && $fieldEditPost == "cobradordb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'numeric|min_len,7|max_len,12'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
    //echo "<pre>";
    //print_r($fielvalid);
    
    if($fielvalid === true){
        /*$tablaExiQ ="deudor_tbl";
        $campoExiQ ="cedula_deudor";
        $colValExiQ = $fieldValue;
        
        //verifica si cedula ya esta registrada
        $goExist = existPost($tablaExiQ_, $campoExiQ_, $colValExiQ_);*/
        //SELECT `id_cobrador`, `id_acreedor`, `id_status_cobrador`, `nombre_cobrador`, `cedula_cobrador`, `mail_cobrador`, `nickname_cobrador`, `pass_cobrador`, `pass_humano_cobrador`, `tel_uno_cobrador`, `tel_dos_cobrador`, `direccion_cobrador`, `barrio_cobrador`, `ciudad_cobrador`, `estado_cobrador`, `pais_cobrador`, `foto_cobrador`, `fecha_alta_cobrador`, `tag_seccion_plataforma` FROM `cobrador_tbl` WHERE 1
        $db->where('cedula_cobrador', $fieldValue);
        $goExist = $db->getOne('cobrador_tbl', 'id_cobrador, nombre_cobrador, nickname_cobrador, tel_uno_cobrador, tel_dos_cobrador, cedula_cobrador, ciudad_cobrador, fecha_alta_cobrador');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_cobrador']);
            $deudornombre = $db->escape($goExist['nombre_cobrador']);
            $deudornickname = $db->escape($goExist['nickname_cobrador']);
            $deudorcedula = $db->escape($goExist['cedula_cobrador']);;
            $deudortel1 = $db->escape($goExist['tel_uno_cobrador']);
            $deudortel2 = $db->escape($goExist['tel_dos_cobrador']); 
            $deudorciudad = $db->escape($goExist['ciudad_cobrador']);     
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_cobrador']));
            //$deudorfechaEdita = $goExist['fecha_alta_cobrador']; //date("d/m/Y", strtotime($goExist['fecha_edita_deudor']));
            
            //$ultimaFechaEdita = "false";
            //if($deudorfechaEdita != "0000-00-00"){
            //    $ultimaFechaEdita = date("d/m/Y", strtotime($deudorfechaEdita));
            //}
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudornickname' => $deudornickname,                
                'deudorcedula' => $deudorcedula,                                
                'deudortel1'=> $deudortel1, 
                'deudortel2' => $deudortel2, 
                'deudorciudad' => $deudorciudad,                
                'deudorfecharegistro' => $deudorfecharegistro
            );
            //$response = $fieldRowEdit;                                    
        }else{            
            $response = array(
                'existe' => 'false',
                'deudorcedula' => $fieldValue
            );            
        }                
    }else{
        $errValidaTmpl = "";
                
        $errValidaTmpl .= "<ul class='list-group text-left'>";
                                           
        //ERRORES VALIDACION DATOS
        $recibeRules = array();
        $recibeRules = $fielvalid;
        
        
                                
        foreach($recibeRules as $keyRules ){
            //foreach($valRules as $key => $v){
                                
                $errFiel = $keyRules['field'];
                $errValue = $keyRules['value'];
                $errRule = $keyRules['rule'];
                $errParam = $keyRules['param'];
                
                if(empty($errValue)){
                    $usertyped = "Por favor completa este campo";                    
                }else{
                    $usertyped = "Escribiste&nbsp;&nbsp;<b>" .$errValue ."</b>";
                }
                
                switch($errFiel){
                    case 'fieldpost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Numero cedula</b>
                        <br>".$usertyped." 
                        <br>Escribe un numero de cedula valido
                        <br>Debes usar entre 7 y 12 caracteres
                        <br>Solo puedes usar numeros, no uses espacios ni simbolos
                        <br>Ej: 1222333</li>";
                    break;
                    
                }
            //}
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    exit(json_encode($response));
}


    
//========================================
//========================================
//CHECK EMAIL USUARIO
//========================================
//========================================


/*--//PARA EL DEUDOR//--*/

if(isset($fieldEditPost) && $fieldEditPost == "editaemail"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue,
        'idpost' => $idPost
    );
    
    $rules = array(
        'fieldpost' => 'valid_email',
        'idpost' => 'integer'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
    //echo "<pre>";
    //print_r($fielvalid);
    
    if($fielvalid === true){
        /*$tablaExiQ ="deudor_tbl";
        $campoExiQ ="cedula_deudor";
        $colValExiQ = $fieldValue;
        
        //verifica si cedula ya esta registrada
        $goExist = existPost($tablaExiQ_, $campoExiQ_, $colValExiQ_);*/
        
        $db->where('mail_cobrador', $fieldValue);
        $db->where('mail_cobrador', "", "!=");
        $db->where('id_cobrador', $idPost, "!=");
        $goExist = $db->getOne('cobrador_tbl', 'id_cobrador, nombre_cobrador, nickname_cobrador, tel_uno_cobrador, tel_dos_cobrador, cedula_cobrador, ciudad_cobrador, fecha_alta_cobrador');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_cobrador']);
            $deudornombre = $db->escape($goExist['nombre_cobrador']);
            //$deudorapellido = $db->escape($goExist['primer_apellido_deudor']);
            $deudorcedula = $db->escape($goExist['cedula_cobrador']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_cobrador']));
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                //'deudorapellido' => $deudorapellido,
                'deudorcedula' => $deudorcedula,
                'deudorfecharegistro' => $deudorfecharegistro,
            );
            //$response = $fieldRowEdit;                                    
        }else{            
            $response = array(
                'existe' => 'false',
                'deudorcedula' => $fieldValue
            );            
        }                
    }else{
        $errValidaTmpl = "";
                
        $errValidaTmpl .= "<ul class='list-group text-left'>";
                                           
        //ERRORES VALIDACION DATOS
        $recibeRules = array();
        $recibeRules = $fielvalid;
        
        
                                
        foreach($recibeRules as $keyRules ){
            //foreach($valRules as $key => $v){
                                
                $errFiel = $keyRules['field'];
                $errValue = $keyRules['value'];
                $errRule = $keyRules['rule'];
                $errParam = $keyRules['param'];
                
                if(empty($errValue)){
                    $usertyped = "Por favor completa este campo";                    
                }else{
                    $usertyped = "Escribiste&nbsp;&nbsp;<b>" .$errValue ."</b>";
                }
                
                switch($errFiel){
                    case 'fieldpost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Email de usuario</b>
                        <br>".$usertyped." 
                        <br>Escribe una cuenta email valida
                        <br>Ej: usuario@sitioweb.vom</li>";
                    break; 
                    case 'idpost' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Cobrador</b>
                        <br>".$usertyped." 
                        <br>El usuario que estas intentando modificar no existe</li>";
                    break;
                }
            //}
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    exit(json_encode($response));
}