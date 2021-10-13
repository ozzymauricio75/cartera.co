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

$valuePost = !isset($_POST['value'])? "" : $_POST['value'];
$idPost = !isset($_POST['post'])? "" : $_POST['post'];
$fieldPost = !isset($_POST['fieldedit']) ? "" : $_POST['fieldedit'];


/*
*NOMBRE ITEM
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editnombre"){
        
    $fielvalid = validaHumanName($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "primer_nombre_acreedor";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_acreedor";
        $tbl = "acreedor_tbl";
        $tituSqlERR = "NOMBRE USUARIO";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Nombre usuario";
        $ruleERR = "Parece, que estas usando caracteres prohibidos. Escribe un nombre de persona real"; 
        $exERR = "Pedro, Juanm Guillermo"; 
        
        $response['error']= printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}



/*
*APELLIDO ITEM
*===========================
*/

if(isset($fieldPost) && $fieldPost == "editapellido"){
        
    $fielvalid = validaHumanName($valuePost, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRow = "primer_apellido_acreedor";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_acreedor";
        $tbl = "acreedor_tbl";
        $tituSqlERR = "APELLIDO USUARIO";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Apellido usuario";
        $ruleERR = "Parece, que estas usando caracteres prohibidos. Escribe un apellido de persona real"; 
        $exERR = "Gutierrez, Muños, Rodriguez"; 
        
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
        $db->where('email_acreedor', $valuePost);
        $db->where('email_acreedor', "", "!=");
        $db->where('id_acreedor', $idPost, "!=");
        $goExist = $db->getOne('acreedor_tbl');
        
        if(count($goExist)>0){  
            return false;
            exit();
        }
                                        
        $idRow = $idPost;
        $fieldRow = "email_acreedor";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_acreedor";
        $tbl = "acreedor_tbl";
        $tituSqlERR = "EMAIL USUARIO";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Email usuario";
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
        $fieldRow = "tel_uno_acreedor";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_acreedor";
        $tbl = "acreedor_tbl";
        $tituSqlERR = "TELEFONO USUARIO";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Telefono usuario";
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
        $fieldRow = "tel_dos_acreedor";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_acreedor";
        $tbl = "acreedor_tbl";
        $tituSqlERR = "TELEFONO USUARIO";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Telefono usuario";
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
        $fieldRow = "dir_establecimiento_acreedor";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_acreedor";
        $tbl = "acreedor_tbl";
        $tituSqlERR = "DIRECCION USUARIO";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $valueRowEdit;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Dirección usuario";
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
        $fieldRow = "ciudad_establecimiento_acreedor";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_acreedor";
        $tbl = "acreedor_tbl";
        $tituSqlERR = "CIUDAD USUARIO";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);
        
        if($goEdit === true){            
            $response = $db->escape($valueRowEdit);                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Ciudad usuario";
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
    
    $replyPassPost = $_POST['replypass'];

    if($replyPassPost == "" || $valuePost == ""){
        $response['error']= "<div class='alert alert-default bg-danger alert-dismissible text-red'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p style='display:block;'>Debes escribir una contraseña para continuar</p></div>";
        exit(json_encode($response));
    }
    
    //identificar si las contraseñas son iguales
    if($replyPassPost != $valuePost){
        $response['error']= "<div class='alert alert-default bg-danger alert-dismissible text-red'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p style='display:block;'>Las contrtaseñas no coinciden, verificalas e intentalo de nuevo</p></div>";
        exit(json_encode($response));
    }
    
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
        $db->where("tag_seccion_plataforma", "acreedor");
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


//========================================
//========================================
//OPCIONES PERSONALIZACION
//========================================
//========================================

if(isset($fieldPost) && $fieldPost == "intocustomuser"){
    
    //$valueCiudadEdit = $_POST['ciudadedit'];
    $capitalInicioPost = ($_POST['capitalinicio'] == "")? 0 : $_POST['capitalinicio'];
    $sabadoDiaPost = ($_POST['sabadodia'] == "ok")? 1 : 0;
    $domingoDiaPost = ($_POST['domingodia'] == "ok")? 1 : 0;
    $festivoDiaPost = ($_POST['festivodia'] == "ok" )? 1 : 0;
    $sabadoPost = ($_POST['sabado'] == "ok")? 1 : 0;
    $domingoPost = ($_POST['domingo'] == "ok")? 1 : 0;
    $festivoPost = ($_POST['festivo'] == "ok")? 1 : 0;
    $cajaPost = ($_POST['caja'] == "ok")? 1 : 0;
        
        
    $fielvalid = validaMoneda($capitalInicioPost, $idPost);
    $maxLengValid = validaMaxLenght($capitalInicioPost, "max_len,9");
    
    
    if($fielvalid === true /*&& $maxLengValid === true*/){
        /*$idRow = $idPost;
        $fieldRow = "ciudad_cobrador";
        $valueRowEdit = $valuePost;
        $idFieldTbl = "id_cobrador";
        $tbl = "cobrador_tbl";
        $tituSqlERR = "CIUDAD COBRADOR";
        
        //actualizar campo en base de datos
        $goEdit = editFielDB($idRow, $fieldRow, $valueRowEdit, $idFieldTbl, $tbl, $tituSqlERR);*/
        
//SELECT `id_usuario_confi`, `id_usuario_plataforma`, `status_configuraciones`, `define_idioma`, `define_zona_horaria`, `define_moneda`, `define_sabados`, `define_domingos`, `define_festivos`, `define_sabado_diaria`, `define_domingo_diaria`, `define_festivos_diaria`, `define_cuadre_caja_diario`, `define_capital_inicial` FROM `usuario_configuraciones_tbl` WHERE 1        
        $dataEdit = array(
            'status_configuraciones' => '1', 
            'define_sabados' => $sabadoPost,
            'define_domingos' => $domingoPost,
            'define_festivos' => $festivoPost,
            'define_sabado_diaria' => $sabadoDiaPost,
            'define_domingo_diaria' => $domingoDiaPost,
            'define_festivos_diaria' => $festivoDiaPost,
            'define_cuadre_caja_diario' => $cajaPost,
            'define_capital_inicial' => $capitalInicioPost
        );
        
        $db->where("id_usuario_plataforma", $idPost);        
        $goEdit = $db->update("usuario_configuraciones_tbl", $dataEdit);
        
        //SE INSERTA VALOR CAPITAL INICIAL EN CUADRE DE CAJA TABLA
        $idStatusCajaMenorHoy = "";
        $statusCajaMenorHoy = "";
        $db->where("id_acreedor",$idPost);
        //$db->where("fecha_cuadre_caja_menor",date("Y-m-d"));
        $db->where("actividad_caja_menor", "inicial");
        //$db->orderBy("id_caja_meno", "desc");
        $queryCuadreHoy = $db->getOne("caja_menor_tbl", "status_caja_menor, id_caja_meno");
        $statusCajaMenorHoy = $queryCuadreHoy['status_caja_menor'];
        $idStatusCajaMenorHoy = $queryCuadreHoy['id_caja_meno'];
        
        //if($capitalInicioPost != 0){
        if(empty($statusCajaMenorHoy) || $statusCajaMenorHoy == 0){
            $dataInsertCaja = array(
                'id_acreedor'=> $idPost,
                'status_caja_menor'=> 1,
                'valor_disponible_caja_menor'=> $capitalInicioPost,
                //'fecha_cuadre_caja_menor'=> date("Y-m-d"),
                'fecha_registro_cuadre_caja' => date("Y-m-d"),
                'actividad_caja_menor' => "inicial"
            );
            
            $valorDisponibleINS = $db->insert("caja_menor_tbl", $dataInsertCaja);
            
        }elseif(isset($statusCajaMenorHoy) || $statusCajaMenorHoy == 1){
            $dataUpdateCaja = array(                
                //'fecha_cuadre_caja_menor' => date("Y-m-d"),
                'fecha_registro_cuadre_caja' => date("Y-m-d"),
                'valor_disponible_caja_menor'=> $capitalInicioPost                
            );
            $db->where("id_caja_meno", $idStatusCajaMenorHoy);   
            $valorDisponibleUPD = $db->update("caja_menor_tbl", $dataUpdateCaja);    
        }
        
        if($goEdit === true){            
            $response = $valuePost;                                    
        }else{            
            $response['error']= $goEdit;            
        }                
    }else{
        $tituERR = "Capital Inicial";
        $ruleERR = "Escribe un valor númerico. No uses puntos, ni simbolos, MAX 9 digitos"; 
        $exERR = "1000000"; 
        
        $error_array = $fielvalid;
        
        /*if(!empty($fielvalid)){
            $error_array = $fielvalid;
        }else if(!empty($maxLengValid)){
            $error_array = $maxLengValid;
        }*/
        
        $response['error']= printErrValida($error_array, $tituERR, $ruleERR, $exERR);            
        
    }
    
    exit(json_encode($response));
}