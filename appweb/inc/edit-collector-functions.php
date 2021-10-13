<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once 'sessionvars.php';
require_once 'query-custom-user.php';
require_once "../lib/gump.class.php";
require_once "site-tools.php"; 
require_once "../lib/password.php";


//========================================
//========================================
//CRUD ITEM EDIT
//========================================
//========================================
    
$response = array();

/////////////////////////
//RECIBE DATOS A EDITAR
/////////////////////////

$valuePost = $_POST['value'];
$idPost = $_POST['post'];
$fieldPost = $_POST['fieldedit'];


/*
*NOMBRE ITEM
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editnombre"){
        
    $fielvalid = validaHumanName($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "nombre_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "NOMBRE COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Nombre cobrador";
        $ruleERR = "Parece, que estas usando caracteres prohibidos. Escribe un nombre de persona real"; 
        $exERR = "Pedro Gutierrez"; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}



/*
*EMAIL
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editaemail"){
        
    $fielvalid = validaEmail($valuePost, $idPost);
    
    if($fielvalid === true){
        
        //VERIFICAR SI EMAIL YA EXISTE
        $db->where('mail_cobrador', $valuePost);
        $db->where('mail_cobrador', "", "!=");
        $db->where('id_cobrador', $idPost, "!=");
        $goExist = $db->getOne('cobrador_tbl');
        
        if(count($goExist)>0){  
            return false;
            exit();
        }
                                        
        $idRow = $idPost;
        $fieldRow = "mail_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "EMAIL COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Email cobrador";
        $ruleERR = "Escribe una cuenta de Email valido"; 
        $exERR = "usuario@sitioweb.com"; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}


/*
*TELEFONO 1
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editatel1"){
        
    $fielvalid = validaPhoneNumber($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "tel_uno_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "TELEFONO COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Telefono cobrador";
        $ruleERR = "Escribe un número de telefono valido"; 
        $exERR = "(55) 111 2233"; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}


    
/*
*TELEFONO 2
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editatel2"){
        
    $fielvalid = validaPhoneNumber($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "tel_dos_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "TELEFONO COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Telefono cobrador";
        $ruleERR = "Escribe un número de telefono valido"; 
        $exERR = "315 111 2233"; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}



/*
*DIRECCION
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editadireccion"){
        
    $fielvalid = validaAddress($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "direccion_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "DIRECCION COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Dirección cobrador";
        $ruleERR = "Escribe una dirección recidencial valida"; 
        $exERR = "Calle 55 # 11-22"; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}


    
/*
*BARRIO
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editabarrio"){
        
    $fielvalid = validaAlphaSpace($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "barrio_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "BARRIO COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Barrio cobrador";
        $ruleERR = "Escribe un nombre de barrio valido"; 
        $exERR = "Altos del jardin"; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}


    
/*
*CIUDAD
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editaciudad"){
    
    //$valueCiudadEdit = $_POST['ciudadedit'];
        
    $fielvalid = validaAlphaSpace($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "ciudad_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "CIUDAD COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $db->escape($valueRowEdit);                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Ciudad cobrador";
        $ruleERR = "Selecciona una de las ciudades del menu"; 
        $exERR = ""; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}

/*
*PASSWORD
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editapass"){
    
    //$valueCiudadEdit = $_POST['ciudadedit'];
        
    $fielvalid = validaPassUser($valuePost, $idPost);
    
    if($fielvalid === true){
        /*$idRow = $idPost;
        $fieldRow = "ciudad_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "CIUDAD COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);*/
        
        
        $dataEdit = array(
            'pass_usuario' => password_hash($valuePost, PASSWORD_BCRYPT, array("cost" => 10)), 
            'pass_humano_usuario' => $valuePost,
        );
        
        $db->where("id_usuario_plataforma", $idPost);
        $db->where("tag_seccion_plataforma", "cobrador");
        $goEdit = $db->update("usuario_tbl", $dataEdit);
        
        if($goEdit === true){            
            $response = $valuePost;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Contraseña";
        $ruleERR = "Escribe una contraseña entre 4 y 12 caracteres. Puedes usar letras, números y los simbolos (!@#$%&()+.-_)"; 
        $exERR = ""; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}



/*
*GESTIONAR / ADMINISTRAR USUARIO
*===========================
*/

/*
*ELIMNAR USUARIO
*/

if(isset($fieldPost) && $fieldPost == "deleteitem"){
                
    $fielvalid = validaInteger($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "id_status_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "STATUS COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
                        
        if($goEdit === true){            
            $response = $valuePost;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Status";
        $ruleERR = "Sólo debes presionar el boton Eliminar"; 
        $exERR = ""; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}

/*
*SUSPENDER USUARIO
*/

    
if(isset($fieldPost) && $fieldPost == "stopitem"){
                
    $fielvalid = validaInteger($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "id_status_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "STATUS COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
                        
        if($goEdit === true){            
            $response = $valuePost;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Status";
        $ruleERR = "Sólo debes presionar el boton Suspender"; 
        $exERR = ""; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}

/*
*ACTIVAR USUARIO
*/

    
if(isset($fieldPost) && $fieldPost == "activaitem"){
                
    $fielvalid = validaInteger($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "id_status_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "STATUS COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
                        
        if($goEdit === true){            
            $response = $valuePost;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Status";
        $ruleERR = "Sólo debes presionar el boton Activar"; 
        $exERR = ""; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}