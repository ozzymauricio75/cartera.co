<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once 'sessionvars.php';
require_once 'query-custom-user.php';
require_once "../lib/gump.class.php";
require_once "site-tools.php"; 

//========================================
//========================================
//UPLOAD PORTADA NEW PRODUCTO
//========================================
//========================================


$fileValida = "";
$successUpload = null;
$response = [];
    
if(empty($_FILES['fotoprod'])){
    
    echo json_encode(['error'=>'Debes seleccionar una imágen para publicar la categoría']); 
    // or you can throw an exception 
    return; // terminate
    
}else{
//if(!empty($_FILES['fotoprod'])){    
    //recibe datas post file
    $filePost = $_FILES['fotoprod']; //empty($_POST['fotoprod'])? "" : $_POST['fotoprod'];  
    $filePostName = $_FILES['fotoprod']['name'];
    $filePostTmpName = $_FILES['fotoprod']['tmp_name'];
    $filePostType = $_FILES['fotoprod']['type'];
    $filePostSize = $_FILES['fotoprod']['size'];
    $filePostErro = $_FILES['fotoprod']['error'];
                
    /*//name portada
    $nameLabelPost = empty($_POST['nameportadaitem']) ? "" : $_POST['nameportadaitem'];
    

    if(isset($nameLabelPost) && $nameLabelPost != ""){    
        //name file
        $nameFileGet = explode(".", $nameLabelPost); 
        $nameFile = $nameFileGet[0];    
        $nameFileTBL = $nameLabelPost;    
    }else{
        //name file
        $nameFile = "IMG_".$dateFormatPost;
        $nameFileTBL = $nameFile.".jpg";    
    }*/
    
    //tratar file    
    $fileCheck = array();
    if(!empty($filePostName)){
        validafile($filePost, $filePostName, $filePostTmpName, $filePostType, $filePostSize, $filePostErro);
        if(isset($err) && $err != ""){
            //print_r($err);
            $fileValida = "error";
            $fileCheck[] = $err;            
        }
    }
    
    if($fileValida != "error"){

        if(upimgfile($filePostName, $filePostTmpName)){
                                    
            $response = ['uploaded'=>'subio correctamente'];

        }else{//si upload fue positivo
            $errUpLoad = 1; 
            $response = ['error'=>'no se pudo subir'];
        }//si upload fue negativo
        
    }else{
        $erroFileUL = printFileErr($fileCheck);
        //$success = false;    
        //$erroFileULLayout = "<section class='box50 padd-verti-xs'>";
        $erroFileULLayout = "<ul class='list-group box75 text-left'>";
        $erroFileULLayout .= $erroFileUL;
        $erroFileULLayout .= "</ul>";
        //$erroFileULLayout .= "</section>";
        
        $response = ['error'=>$erroFileULLayout];
    }
    
}//fin $_FILES[]
echo json_encode($response);



