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

/*--//PARA EL DEUDOR//--*/

if(isset($fieldEditPost) && $fieldEditPost == "deudordb"){
    
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
        //SELECT `id_deudor`, `id_acreedor`, `id_status_perfil_deduor`, `primer_nombre_deudor`, `segundo_nombre_deudor`, `primer_apellido_deudor`, `segundo_apellido_deudor`, `cedula_deudor`, `fecha_nacimiento_deudor`, `lugar_naciminto_deudor`, `genero_deudor`, `estado_civil_deudor`, `personas_dependientes_deudor`, `email_deudor`, `tel_uno_deudor`, `tel_dos_deudor`, `dir_domicilio_deudor`, `complemento_dir_deudor`, `barrio_domicilio_deudor`, `tipo_vivienda_deudor`, `estrato_social_deudor`, `dir_geo_deudor`, `latitud_geo_deudor`, `longitud_geo_deudor`, `url_maps_deudor`, `codigo_geo_ciudad_deudor`, `codigo_geo_estado_deudor`, `codigo_geo_pais_deudor`, `ciudad_domicilio_deudor`, `estado_domicilio_deudor`, `pais_domicilio_deudor`, `codigo_postal_geo_deudor`, `nivel_escolaridad_deudor`, `oficio_deudor`, `profesion_deudor`, `nombre_empresa_deudor`, `cargo_empresa_deudor`, `tel_empresa_deudor`, `dir_empresa_deudor`, `ciudad_empresa_deudor`, `comentarios_deudor`, `fecha_alta_deudor`, `fecha_edita_deudor` FROM `deudor_tbl` WHERE 1
        $db->where('cedula_deudor', $fieldValue);        
        $goExist = $db->getOne('deudor_tbl', 'id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, cedula_deudor, email_deudor, tel_uno_deudor, fecha_alta_deudor, tel_dos_deudor, dir_domicilio_deudor, barrio_domicilio_deudor, dir_geo_deudor, fecha_edita_deudor, nit_referencia_comercial, nombre_contato_referencia_comercial	');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_deudor']);
            
            $nombrecontacto = $db->escape($goExist['nombre_contato_referencia_comercial']);            
            $nitempresa = $db->escape($goExist['nit_referencia_comercial']);
            
            $deudornombre = $db->escape($goExist['primer_nombre_deudor']);
            $deudorapellido = $db->escape($goExist['primer_apellido_deudor']);
            $deudorsegundonombre = $db->escape($goExist['segundo_nombre_deudor']);
            $deudorsegundoapellido = $db->escape($goExist['segundo_apellido_deudor']);
            $deudorcedula = $db->escape($goExist['cedula_deudor']);
            $deudoremail = $db->escape($goExist['email_deudor']);
            $deudortel1 = $db->escape($goExist['tel_uno_deudor']);
            $deudortel2 = $db->escape($goExist['tel_dos_deudor']); 
            $deudordirdomicilio = $db->escape($goExist['dir_domicilio_deudor']);
            $deudorbarrio = $db->escape($goExist['barrio_domicilio_deudor']);
            $deudordirgeo = $db->escape($goExist['dir_geo_deudor']);            
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_deudor']));
            $deudorfechaEdita = $goExist['fecha_edita_deudor']; //date("d/m/Y", strtotime($goExist['fecha_edita_deudor']));
            
            $ultimaFechaEdita = "false";
            if($deudorfechaEdita != "0000-00-00"){
                $ultimaFechaEdita = date("d/m/Y", strtotime($deudorfechaEdita));
            }
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'nombrecontacto' => $nombrecontacto,
                'nitempresa' => $nitempresa,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
                'deudorsegundonombre' =>$deudorsegundonombre,                 
                'deudorsegundoapellido' => $deudorsegundoapellido, 
                'deudorcedula' => $deudorcedula,                
                'deudoremail' => $deudoremail, 
                'deudortel1'=> $deudortel1, 
                'deudortel2' => $deudortel2, 
                'deudordirdomicilio' => $deudordirdomicilio,
                'deudorbarrio' => $deudorbarrio, 
                'deudordirgeo' => $deudordirgeo,                 
                'deudorfecharegistro' => $deudorfecharegistro,
                'deudorfechaedita' => $ultimaFechaEdita
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



/*--//PARA EL CO-DEUDOR//--*/

if(isset($fieldEditPost) && $fieldEditPost == "codeudordb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'numeric|min_len,7|max_len,12'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
        
    if($fielvalid === true){
                
        $db->where('cedula_codeudor', $fieldValue);
        $goExist = $db->getOne('codeudor_tbl', 'id_codeudor, primer_nombre_codeudor, primer_apellido_codeudor, cedula_codeudor, tel_uno_codeudor, tel_dos_codeudor, dir_domicilio_codeudor, fecha_alta_codeudor, fecha_edita_codeudor');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_codeudor']);
            $deudornombre = $db->escape($goExist['primer_nombre_codeudor']);
            $deudorapellido = $db->escape($goExist['primer_apellido_codeudor']);
            $deudorcedula = $db->escape($goExist['cedula_codeudor']);
            $deudortel1 = $db->escape($goExist['tel_uno_codeudor']);
            $deudortel2 = $db->escape($goExist['tel_dos_codeudor']); 
            $deudordirdomicilio = $db->escape($goExist['dir_domicilio_codeudor']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_codeudor']));
            $deudorfechaEdita = $goExist['fecha_edita_codeudor'];
            
            $ultimaFechaEdita = "false";
            if($deudorfechaEdita != "0000-00-00"){
                $ultimaFechaEdita = date("d/m/Y", strtotime($deudorfechaEdita));
            }
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
                'deudorcedula' => $deudorcedula,
                'deudorfecharegistro' => $deudorfecharegistro,
                'deudordirdomicilio' => $deudordirdomicilio,
                'deudortel1'=> $deudortel1, 
                'deudortel2' => $deudortel2,                 
                'deudorfechaedita' => $ultimaFechaEdita
                
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


/*--//PARA REFERENCIA PERSONAL//--*/

if(isset($fieldEditPost) && $fieldEditPost == "refpersodb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'numeric|min_len,7|max_len,12'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
        
    if($fielvalid === true){
                
        $db->where('cedula_referencia_personal', $fieldValue);
        $goExist = $db->getOne('referencia_personal_tbl', 'id_referencia_personal, primer_nombre_referencia_personal, primer_apellido_referencia_personal, cedula_referencia_personal, tel_uno_referencia_personal, tel_dos_referencia_personal, dir_domicilio_referencia_personal, fecha_alta_referencia_personal, fecha_edita_referencia_personal');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_referencia_personal']);
            $deudornombre = $db->escape($goExist['primer_nombre_referencia_personal']);
            $deudorapellido = $db->escape($goExist['primer_apellido_referencia_personal']);
            $deudorcedula = $db->escape($goExist['cedula_referencia_personal']);
            $deudortel1 = $db->escape($goExist['tel_uno_referencia_personal']);
            $deudortel2 = $db->escape($goExist['tel_dos_referencia_personal']); 
            $deudordirdomicilio = $db->escape($goExist['dir_domicilio_referencia_personal']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_referencia_personal']));
            $deudorfechaEdita = $goExist['fecha_edita_referencia_personal'];
            
            $ultimaFechaEdita = "false";
            if($deudorfechaEdita != "0000-00-00"){
                $ultimaFechaEdita = date("d/m/Y", strtotime($deudorfechaEdita));
            }
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
                'deudorcedula' => $deudorcedula,
                'deudorfecharegistro' => $deudorfecharegistro,
                'deudordirdomicilio' => $deudordirdomicilio,
                'deudortel1'=> $deudortel1, 
                'deudortel2' => $deudortel2,                 
                'deudorfechaedita' => $ultimaFechaEdita
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


/*--//PARA REFERENCIA FAMILIAR//--*/

if(isset($fieldEditPost) && $fieldEditPost == "reffamidb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'numeric|min_len,7|max_len,12'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
        
    if($fielvalid === true){
                
        $db->where('cedula_referencia_familiar', $fieldValue);
        $goExist = $db->getOne('referencia_familiar_tbl', 'id_referencia_familiar, primer_nombre_referencia_familiar, primer_apellido_referencia_familiar, cedula_referencia_familiar, tel_uno_referencia_familiar, tel_dos_referencia_familiar, dir_domicilio_referencia_familiar, fecha_alta_referencia_familiar, fecha_edita_referencia_familiar');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_referencia_familiar']);
            $deudornombre = $db->escape($goExist['primer_nombre_referencia_familiar']);
            $deudorapellido = $db->escape($goExist['primer_apellido_referencia_familiar']);
            $deudorcedula = $db->escape($goExist['cedula_referencia_familiar']);
            $deudortel1 = $db->escape($goExist['tel_uno_referencia_familiar']);
            $deudortel2 = $db->escape($goExist['tel_dos_referencia_familiar']); 
            $deudordirdomicilio = $db->escape($goExist['dir_domicilio_referencia_familiar']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_referencia_familiar']));
            $deudorfechaEdita = $goExist['fecha_edita_referencia_familiar'];
            
            $ultimaFechaEdita = "false";
            if($deudorfechaEdita != "0000-00-00"){
                $ultimaFechaEdita = date("d/m/Y", strtotime($deudorfechaEdita));
            }
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
                'deudorcedula' => $deudorcedula,
                'deudorfecharegistro' => $deudorfecharegistro,
                'deudordirdomicilio' => $deudordirdomicilio,
                'deudortel1'=> $deudortel1, 
                'deudortel2' => $deudortel2,                 
                'deudorfechaedita' => $ultimaFechaEdita
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


/*--//PARA REFERENCIA COMERCIAL//--*/

if(isset($fieldEditPost) && $fieldEditPost == "refcomerdb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'numeric|min_len,7|max_len,12'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
        
    if($fielvalid === true){
                
        $db->where('cedula_deudor', $fieldValue);        
        $goExist = $db->getOne('deudor_tbl', 'id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, cedula_deudor, email_deudor, tel_uno_deudor, fecha_alta_deudor, tel_dos_deudor, dir_domicilio_deudor, barrio_domicilio_deudor, dir_geo_deudor, fecha_edita_deudor, nit_referencia_comercial, nombre_contato_referencia_comercial');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_deudor']);
            
            $nombrecontacto = $db->escape($goExist['nombre_contato_referencia_comercial']);            
            $nitempresa = $db->escape($goExist['nit_referencia_comercial']);
            
            $deudornombre = $db->escape($goExist['primer_nombre_deudor']);
            $deudorapellido = $db->escape($goExist['primer_apellido_deudor']);
            $deudorsegundonombre = $db->escape($goExist['segundo_nombre_deudor']);
            $deudorsegundoapellido = $db->escape($goExist['segundo_apellido_deudor']);
            $deudorcedula = $db->escape($goExist['cedula_deudor']);
            $deudoremail = $db->escape($goExist['email_deudor']);
            $deudortel1 = $db->escape($goExist['tel_uno_deudor']);
            $deudortel2 = $db->escape($goExist['tel_dos_deudor']); 
            $deudordirdomicilio = $db->escape($goExist['dir_domicilio_deudor']);
            $deudorbarrio = $db->escape($goExist['barrio_domicilio_deudor']);
            $deudordirgeo = $db->escape($goExist['dir_geo_deudor']);            
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_deudor']));
            $deudorfechaEdita = $goExist['fecha_edita_deudor']; //date("d/m/Y", strtotime($goExist['fecha_edita_deudor']));
            
            $ultimaFechaEdita = "false";
            if($deudorfechaEdita != "0000-00-00"){
                $ultimaFechaEdita = date("d/m/Y", strtotime($deudorfechaEdita));
            }
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'nombrecontacto' => $nombrecontacto,
                'nitempresa' => $nitempresa,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
                'deudorsegundonombre' =>$deudorsegundonombre,                 
                'deudorsegundoapellido' => $deudorsegundoapellido, 
                'deudorcedula' => $deudorcedula,                
                'deudoremail' => $deudoremail, 
                'deudortel1'=> $deudortel1, 
                'deudortel2' => $deudortel2, 
                'deudordirdomicilio' => $deudordirdomicilio,
                'deudorbarrio' => $deudorbarrio, 
                'deudordirgeo' => $deudordirgeo,                 
                'deudorfecharegistro' => $deudorfecharegistro,
                'deudorfechaedita' => $ultimaFechaEdita
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
//CHECK NIT REFERENCIA COMERCIAL
//========================================
//========================================


/*--//PARA REFERENCIA COMERCIAL//--*/

if(isset($fieldEditPost) && $fieldEditPost == "nitrefcomerdb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'alpha_space'//'user|min_len,7|max_len,15'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
        
    if($fielvalid === true){
        //SELECT `id_deudor`, `id_acreedor`, `id_status_perfil_deduor`, `primer_nombre_deudor`, `segundo_nombre_deudor`, `primer_apellido_deudor`, `segundo_apellido_deudor`, `cedula_deudor`, `fecha_nacimiento_deudor`, `lugar_naciminto_deudor`, `genero_deudor`, `estado_civil_deudor`, `personas_dependientes_deudor`, `email_deudor`, `tel_uno_deudor`, `tel_dos_deudor`, `dir_domicilio_deudor`, `complemento_dir_deudor`, `barrio_domicilio_deudor`, `tipo_vivienda_deudor`, `estrato_social_deudor`, `dir_geo_deudor`, `latitud_geo_deudor`, `longitud_geo_deudor`, `url_maps_deudor`, `codigo_geo_ciudad_deudor`, `codigo_geo_estado_deudor`, `codigo_geo_pais_deudor`, `ciudad_domicilio_deudor`, `estado_domicilio_deudor`, `pais_domicilio_deudor`, `codigo_postal_geo_deudor`, `nivel_escolaridad_deudor`, `oficio_deudor`, `profesion_deudor`, `nombre_empresa_deudor`, `cargo_empresa_deudor`, `tel_empresa_deudor`, `dir_empresa_deudor`, `ciudad_empresa_deudor`, `comentarios_deudor`, `fecha_alta_deudor`, `fecha_edita_deudor` FROM `deudor_tbl` WHERE 1        
        $db->where('nit_referencia_comercial', $fieldValue);
        $goExist = $db->getOne('deudor_tbl', 'id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, cedula_deudor, email_deudor, tel_uno_deudor, fecha_alta_deudor, tel_dos_deudor, dir_domicilio_deudor, barrio_domicilio_deudor, dir_geo_deudor, fecha_edita_deudor, nit_referencia_comercial, nombre_contato_referencia_comercial');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_deudor']);
            $razonsocial = $db->escape($goExist['nombre_empresa_deudor']);
            $nombrecontacto = $db->escape($goExist['nombre_contato_referencia_comercial']);
            //$deudorcedula = $db->escape($goExist['cedula_referencia_comercial']);
            $nitempresa = $db->escape($goExist['nit_referencia_comercial']);
            $telempresa = $db->escape($goExist['tel_empresa_deudor']);
            //$deudortel2 = $db->escape($goExist['tel_dos_referencia_comercial']); 
            $dirempresa = $db->escape($goExist['dir_empresa_deudor']);
            $ciudadempresa = $db->escape($goExist['ciudad_empresa_deudor']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_deudor']));
            $deudorfechaEdita = $goExist['fecha_edita_deudor'];
            
            $ultimaFechaEdita = "false";
            if($deudorfechaEdita != "0000-00-00"){
                $ultimaFechaEdita = date("d/m/Y", strtotime($deudorfechaEdita));
            }
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'razonsocial' => $razonsocial,
                'nombrecontacto' => $nombrecontacto,
                'nitempresa' => $nitempresa,
                'empresafecharegistro' => $deudorfecharegistro,
                'empresadir' => $dirempresa,
                'empresatel'=> $telempresa, 
                'empresaciudad' => $ciudadempresa,                 
                'empresafechaedita' => $ultimaFechaEdita
            );                                   
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
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Numero NIT</b>
                        <br>".$usertyped." 
                        <br>Escribe un numero NIT valido
                        <br>Debes usar entre 7 y 13 caracteres
                        <br>Solo puedes usar numeros punto y guion (.-)
                        <br>Ej: ###.###.###-#</li>";
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

if(isset($fieldEditPost) && $fieldEditPost == "emailrefcomerdb" /*"emaildeudordb"*/){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'valid_email'
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
        
        $db->where('email_deudor', $fieldValue);
        $db->where('email_deudor', "", "!=");
        $goExist = $db->getOne('deudor_tbl', 'id_deudor, primer_nombre_deudor, primer_apellido_deudor, cedula_deudor, fecha_alta_deudor');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_deudor']);
            $deudornombre = $db->escape($goExist['primer_nombre_deudor']);
            $deudorapellido = $db->escape($goExist['primer_apellido_deudor']);
            $deudorcedula = $db->escape($goExist['cedula_deudor']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_deudor']));
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
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
                    
                }
            //}
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    exit(json_encode($response));
}


/*--//PARA EL CODEUDOR//--*/

if(isset($fieldEditPost) && $fieldEditPost == "emailcodeudordb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'valid_email'
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
        
        $db->where('email_codeudor', $fieldValue);
        $db->where('email_codeudor', "", "!=");
        $goExist = $db->getOne('codeudor_tbl', 'id_codeudor, primer_nombre_codeudor, primer_apellido_codeudor, cedula_codeudor, fecha_alta_codeudor');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_codeudor']);
            $deudornombre = $db->escape($goExist['primer_nombre_codeudor']);
            $deudorapellido = $db->escape($goExist['primer_apellido_codeudor']);
            $deudorcedula = $db->escape($goExist['cedula_codeudor']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_codeudor']));
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
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
                    
                }
            //}
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    exit(json_encode($response));
}


/*--//PARA LA REFERENCIA PERSONAL//--*/

if(isset($fieldEditPost) && $fieldEditPost == "emailrefpersodb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'valid_email'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
    //echo "<pre>";
    //print_r($fielvalid);
    
    if($fielvalid === true){
        $db->where('email_referencia_personal', $fieldValue);
        $db->where('email_referencia_personal', "", "!=");
        $goExist = $db->getOne('referencia_personal_tbl', 'id_referencia_personal, primer_nombre_referencia_personal, primer_apellido_referencia_personal, cedula_referencia_personal, fecha_alta_referencia_personal');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_referencia_personal']);
            $deudornombre = $db->escape($goExist['primer_nombre_referencia_personal']);
            $deudorapellido = $db->escape($goExist['primer_apellido_referencia_personal']);
            $deudorcedula = $db->escape($goExist['cedula_referencia_personal']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_referencia_personal']));
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
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
                    
                }
            //}
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    exit(json_encode($response));
}


    
    
/*--//PARA LA REFERENCIA FAMILIAR//--*/

if(isset($fieldEditPost) && $fieldEditPost == "emailreffamidb"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'valid_email'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
    //echo "<pre>";
    //print_r($fielvalid);
    
    if($fielvalid === true){
        
        $db->where('email_referencia_familiar', $fieldValue);
        $db->where('email_referencia_familiar', "", "!=");
        $goExist = $db->getOne('referencia_familiar_tbl', 'id_referencia_familiar, primer_nombre_referencia_familiar, primer_apellido_referencia_familiar, cedula_referencia_familiar, fecha_alta_referencia_familiar');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_referencia_familiar']);
            $deudornombre = $db->escape($goExist['primer_nombre_referencia_familiar']);
            $deudorapellido = $db->escape($goExist['primer_apellido_referencia_familiar']);
            $deudorcedula = $db->escape($goExist['cedula_referencia_familiar']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_referencia_familiar']));
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
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
                    
                }
            //}
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    exit(json_encode($response));
}



/*--//PARA LA REFERENCIA COMERCIAL//--*/

if(isset($fieldEditPost) && $fieldEditPost == "emailrefcomerdb_V1"){
    
    $postDatas = array(
        'fieldpost' => $fieldValue
    );
    
    $rules = array(
        'fieldpost' => 'valid_email'
    );
        
    $fielvalid = $validfield->validate($postDatas, $rules);
    //echo "<pre>";
    //print_r($fielvalid);
    
    if($fielvalid === true){
        
        $db->where('email_referencia_comercial', $fieldValue);
        $db->where('email_referencia_comercial', "", "!=");
        $goExist = $db->getOne('referencia_comercial_tbl', 'id_referencia_comercial, primer_nombre_referencia_comercial, primer_apellido_referencia_comercial, cedula_referencia_comercial, fecha_alta_referencia_comercial');
        
        if(count($goExist)>0){  
            $deudorid = $db->escape($goExist['id_referencia_comercial']);
            $deudornombre = $db->escape($goExist['primer_nombre_referencia_comercial']);
            $deudorapellido = $db->escape($goExist['primer_apellido_referencia_comercial']);
            $deudorcedula = $db->escape($goExist['cedula_referencia_comercial']);
            $deudorfecharegistro = date("d/m/Y", strtotime($goExist['fecha_alta_referencia_comercial']));
            
            
            $response = array(
                'existe' => 'true',
                'deudorid' => $deudorid,
                'deudornombre' => $deudornombre,
                'deudorapellido' => $deudorapellido,
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
                    
                }
            //}
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    exit(json_encode($response));
}