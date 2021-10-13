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
//UPLOAD NEW MULTI FOTOS REF
//========================================
//========================================


$fileValida = "";
$successUpload = null;
$response = [];
    
if(!empty($_FILES['multifileimg'])){
    
        
    //recibe datas post file
    $filePost = $_FILES['multifileimg']; 
            
    //name file
    $nameFile = "IMG_".$dateFormatPost;
    $nameFileTBL = $nameFile.".jpg";
    
    //id post
    //$idPost = empty($_POST['codeitem']) ? "" : $_POST['codeitem'];
    //valor edit post
    //$fielEdit = $nameFileTBL;
    
    //""""""devuelve un array file organizado
    $img_desc = reArrayFiles($filePost); 
        
    $filteredArr = $img_desc;

    //"""""Elimina arrays vacios
    foreach($filteredArr as $key => $link){ 
        if($link === '' || $link === 0 || $link['error'] == 4){ 
            unset($filteredArr[$key]); 
        } 
    } 
    
    //"""""guarda datas file para subir al servidor    
    $filesUpload = $filteredArr;   
    
    //"""""verifica posibles errores en img upload
    $fileCheck = array();    
    foreach($filteredArr as $val){
        validafile($val['tmp_name'], $val['name'], $val['tmp_name'], $val['type'], $val['size'], $val['error']);
        if(isset($err) && $err != ""){                
            $fileValida = "error";
            $fileCheck[] = $err;  //aqui no necesite aplicar la funcion de organizar array reArrayFiles(), pq el array va mostrando la forma posicion y para cada opsicion su respectivo archivo con lista de errores                  
        }            
    }  
            
    //SI TODO SALIO BN CON LA VALIDACION           
    if($fileValida != "error"){
        
        foreach($filesUpload as $fuKey){

            if(upimgfile($fuKey['name'], $fuKey['tmp_name'])){

                /*$patFileEnd = "tienda/img200/"; //define el directorio final de la imagen   
                $squareFile = "200";  //define dimensiones de imagen

                if(redimensionaImgFile($filePostName, $filePostType, $nameFile, $patFileEnd, $squareFile)){
                    $response = ['uploaded'=>'se redimensiono correctamente'];

                    $idRow = $idPost;
                    $fieldRow = "foto_producto";
                    $fieldRowEdit = $fielEdit;
                    $idFieldTbl = "id_producto";
                    $tbl = "productos";
                    $tituSqlERR = "FOTO PORTADA";


                    //actualizar campo en base de datos
                    $goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR);

                    if($goEdit === true){
                        //$statusEdit = "ok";
                        $response = $fieldRowEdit;                        
                        //return true;
                    }else{
                        //return false;
                        $response['error']= $goEdit;
                        //echo $data;
                    }

                }else{//si redimension fue positivo
                    $errRedesize = 1;       
                    $response = ['error'=>'no se pudo redimensionar'];
                }//si redimension fue negativo*/
                $response = ['uploaded'=>'Subio correctamente'];

            }else{//si upload fue positivo
                $errUpLoad = 1; 
                $response = ['error'=>'Fallo subir las imágenes al servidor'];
            }//si upload fue negativo
        }//fin foreach
    
    //IMPRIME ERRORES DE FILES IMG
    }else{
                        
        if(is_array($fileCheck)){            
            $erroFileULLayout = "<ul class='list-group text-left box75'>";
            
            foreach($fileCheck as $itemFile => $ifKey){
                $givErr[] = $ifKey;
                $erroFileULLayout .= printFileErr($givErr);    
            }            
            $erroFileULLayout .= "</ul>";            
        }
        
        $response = ['error'=>$erroFileULLayout];            
    }
    echo json_encode($response);
}//fin $_FILES[]



    



//IMG SINGLE
/*if(empty($_FILES['fotoprod'])){
    
    echo json_encode(['error'=>'Debes seleccionar una imágen para publicar']); 
    // or you can throw an exception 
    return; // terminate
    
}else{*/
if(!empty($_FILES['fotoprod'])){    
    //recibe datas post file
    $filePost = $_FILES['fotoprod']; //empty($_POST['fotoprod'])? "" : $_POST['fotoprod'];  
    $filePostName = $_FILES['fotoprod']['name'];
    $filePostTmpName = $_FILES['fotoprod']['tmp_name'];
    $filePostType = $_FILES['fotoprod']['type'];
    $filePostSize = $_FILES['fotoprod']['size'];
    $filePostErro = $_FILES['fotoprod']['error'];
                
    //name portada
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
    }
    
    //id post
    $idPost = empty($_POST['labelalbumform']) ? "" : $_POST['labelalbumform'];
    //valor edit post
    $fielEdit = $nameFileTBL;



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
            
            $rutaLAbelExist = "../../../files-display/album/labels/".$fielEdit;	
            
            if (file_exists($rutaLAbelExist)){
                unlink($rutaLAbelExist);
            }
            //if(unlink($rutaLAbelExist)){
                        
            $patFileEnd = "album/labels/"; //define el directorio final de la imagen   
            $squareFile = "600";  //define dimensiones de imagen

            if(redimensionaImgFile($filePostName, $filePostType, $nameFile, $patFileEnd, $squareFile)){

               /*if(redimensionaImgFile($filePostName, $filePostType, $nameFile, $patFileEnd400, $squareFile400) && redimensionaImgFile($filePostName, $filePostType, $nameFile, $patFileEnd800, $squareFile800) && redimensionaImgFile($filePostName, $filePostType, $nameFile, $patFileEnd1200, $squareFile1200)){*/

                    //if($nameLabelPost == ""){ 

                        $idRow = $idPost;
                        $fieldRow = "portada_album";
                        $fieldRowEdit = $fielEdit;
                        $idFieldTbl = "ref_album";
                        $tbl = "albun_db";
                        $tituSqlERR = "FOTO PORTADA";

                        //actualizar campo en base de datos
                        $goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR);

                        if($goEdit === true){

                            $response = $fieldRowEdit;     


                        }else{

                            $response = ['error'=> $goEdit];

                        }
                    //}

                    $reloadDir = "files-display/album/labels/";
                    gotoRedirect($reloadDir);

                    //$response = ['uploaded'=>'subio correctamente'];

                /*}else{//si redimension fue positivo
                    $errRedesize = 1;       
                    $response = ['error'=>'no se pudo crear as copias'];
                }//si redimension fue negativo
                //$response = ['uploaded'=>'subio correctamente'];*/

            }else{//si redimension fue positivo
                $errRedesize = 1;       
                $response = ['error'=>'no se pudo crear el thumb'];
            }//si redimension fue negativo
            //$response = ['uploaded'=>'subio correctamente'];
            //}else{
                //$response = ['error'=>'no se pudo eliminar la portada actual'];                
            //}

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
    
    echo json_encode($response);
}//fin $_FILES[]







//========================================
//========================================
//UPLOAD EDIT MULTI FOTOS REF
//========================================
//========================================

if(!empty($_FILES['editmultifileimg'])){
            
    //recibe datas post file
    $filePost = $_FILES['editmultifileimg']; 
    $namePortadaAlbum = $_POST['nameportadaitem']; 
    $refAlbumEdit = $_POST['labelalbumform']; 
    $codeAlbumEdit = empty($_POST['codealbumform']) ? "" : $_POST['codealbumform'];     
    
    if($codeAlbumEdit == ""){
        //crea album         
        //SELECT `id_albun`, `nome_albun`, `portada_album`, `ref_album`, `ruta_publicacion` FROM `albun_db` WHERE 1
        $datasNewAlbum = array(
            'nome_albun' => $refAlbumEdit, 
            'portada_album' => $namePortadaAlbum,
            'ref_album' => $refAlbumEdit,
            'ruta_publicacion' => NULL
        );
        $newAlbum = $db->insert('albun_db', $datasNewAlbum);
        if($newAlbum){
            $codeAlbumEdit = $newAlbum;            
        }else{
            $erroQuery_Album = $db->getLastErrno();    
            $errQueryTmpl_Album ="<ul class='list-group text-left'>";
            $errQueryTmpl_Album .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo crear el album</b>
                <br>Erro: ".$erroQuery_Album."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl_Album .="</ul>";

            $response = ['error'=> $errQueryTmpl_Album]; 
        }
    }
                
    //""""""devuelve un array file organizado
    $img_desc = reArrayFiles($filePost); 
        
    $filteredArr = $img_desc;

    //"""""Elimina arrays vacios
    foreach($filteredArr as $key => $link){ 
        if($link === '' || $link === 0 || $link['error'] == 4){ 
            unset($filteredArr[$key]); 
        } 
    } 
    
    //"""""guarda datas file para subir al servidor    
    $filesUpload = $filteredArr;   
    
    //"""""verifica posibles errores en img upload
    $fileCheck = array();    
    foreach($filteredArr as $val){
        validafile($val['tmp_name'], $val['name'], $val['tmp_name'], $val['type'], $val['size'], $val['error']);
        if(isset($err) && $err != ""){                
            $fileValida = "error";
            $fileCheck[] = $err;  //aqui no necesite aplicar la funcion de organizar array reArrayFiles(), pq el array va mostrando la forma posicion y para cada opsicion su respectivo archivo con lista de errores                  
        }            
    }  
            
    //SI TODO SALIO BN CON LA VALIDACION           
    if($fileValida != "error"){
        
        foreach($filesUpload as $fuKey){

            if(upimgfile($fuKey['name'], $fuKey['tmp_name'])){

                //img full  
                $patFileEnd = "album/1200/"; //define el directorio final de la imagen    
                $squareFile = "1200";  //define dimensiones de imagen

                //img COPIAS REDIMENSIONADAS 
                $patFileEndThumb800 = "album/800/"; //define el directorio final de la imagen   
                $squareFileThumb800 = "800";  //define dimensiones de imagen
                $patFileEndThumb400 = "album/400/"; //define el directorio final de la imagen   
                $squareFileThumb400 = "400";  //define dimensiones de imagen
                $patFileEndThumb = "album/200/"; //define el directorio final de la imagen   
                $squareFileThumb = "200";  //define dimensiones de imagen


                //$refAlbumInsert = $refAlbumEdit;  
                //$urldb = $fuKey['urlinst'];
                $nameFileSponsorF = date('YmdHis',time()).mt_rand(); 
                $nameFileSponsorFArr[] = $nameFileSponsorF;  
                
                if(redimensionaImgFile($fuKey['name'], $fuKey['type'], $nameFileSponsorF, $patFileEnd, $squareFile)){ 

                    //crea thumb
                    if((redimensionaImgFile($fuKey['name'], $fuKey['type'], $nameFileSponsorF, $patFileEndThumb, $squareFileThumb)) && (redimensionaImgFile($fuKey['name'], $fuKey['type'], $nameFileSponsorF, $patFileEndThumb400, $squareFileThumb400)) && (redimensionaImgFile($fuKey['name'], $fuKey['type'], $nameFileSponsorF, $patFileEndThumb800, $squareFileThumb800))){

                        $idSponsor = $db->rawQuery("INSERT INTO fotos_albun (id_albun, img_foto) VALUES('".$codeAlbumEdit."', '".$nameFileSponsorF.".jpg')");
                        if(!$idSponsor){ 

                            $response = "Failed to insert new FOTO ALBUM:\n Erro:". $db->getLastErrno();

                        }else{
                            $statusEdit = "ok";
                        }    
                    }else{
                        $errCopySponsor[] =array($fuKey['name']);
                        $errQueryTmpl_Foto ="<ul class='list-group text-left'>";
                        
                        if(is_array($errCopySponsor)){
                            foreach($errCopySponsor as $errRed){
                                $errQueryTmpl_Foto .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                                <br>Wrong: <b>No se pudo publicar la foto</b>
                                <br>Erro: ".$errRed."
                                <br>Puedes intentar de nuevo
                                <br>Si el error continua, por favor entre en contacto con soporte</li>";    
                            }
                        }                                                
                        $errQueryTmpl_Foto .="</ul>";
                        $response = ['error'=>$errQueryTmpl_Foto];

                    }                        
                }else{
                    $errCopySponsor[] =array($fuKey['name']);
                    $errQueryTmpl_Foto ="<ul class='list-group text-left'>";
                    
                    if(is_array($errCopySponsor)){
                        foreach($errCopySponsor as $fullRed){
                            $errQueryTmpl_Foto .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                            <br>Wrong: <b>No se pudo publicar la foto</b>
                            <br>Erro: ".$fullRed."
                            <br>Puedes intentar de nuevo
                            <br>Si el error continua, por favor entre en contacto con soporte</li>";        
                        }                            
                    }                
                    $errQueryTmpl_Foto .="</ul>";
                    $response = ['error'=>$errQueryTmpl_Foto];
                }
                
                $response = ['uploaded'=>'Subio correctamente'];

            }else{//si upload fue positivo
                $errUpLoad = 1; 
                $response = ['error'=>'Fallo subir las imágenes al servidor'];
            }//si upload fue negativo
        }//fin foreach
    
    //IMPRIME ERRORES DE FILES IMG
    }else{
                        
        if(is_array($fileCheck)){            
            $erroFileULLayout = "<ul class='list-group text-left box75'>";
            
            foreach($fileCheck as $itemFile => $ifKey){
                $givErr[] = $ifKey;
                $erroFileULLayout .= printFileErr($givErr);    
            }            
            $erroFileULLayout .= "</ul>";            
        }
        
        $response = ['error'=>$erroFileULLayout];            
    }
    //echo json_encode($response);
    exit(json_encode($response));
}//fin $_FILESEDIT[]