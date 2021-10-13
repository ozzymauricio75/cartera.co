<?php
require_once '../../appweb/lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once '../../appweb/inc/sessionvars.php';
require_once '../../appweb/inc/query-custom-user.php';
require_once "../../appweb/lib/gump.class.php";
require_once "../../appweb/inc/site-tools.php"; 


$response = array();

/*
***************************
***************************
*RECIBE DATOS A EDITAR
***************************
***************************
*/

/*$field = $_POST['field'];
$fieldVal = $_POST['value'];*/
$idPost = isset($_POST['post'])? $_POST['post'] : "";
$fieldPost = isset($_POST['fieldedit']) ? $_POST['fieldedit'] : "";

/*
*CANCELAR CREDITO
*/

if(isset($fieldPost) && $fieldPost == "cancelcredit" ){
    
    $status_val = "6";
    
    $fielvalid = validaInteger($status_val, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $status_val;
                
        $datasErr = "";
        $datasUpdate = array(
            'id_status_credito' => $fieldRowEdit,
            'fecha_cierre_definitivo_credito' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos        
        $db->where('id_creditos', $idRow);
        $goEdit = $db->update('creditos_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No fue posible cancelar este credito</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Cancelar credito";
        $ruleERR = "Parece que algunos datos estan corrompidos"; 
        $exERR = "Presiona el boton cancelar de este credito"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
       
    exit(json_encode($response));
}



/*
*DIFICIL CARTERA
*/   
if(isset($fieldPost) && $fieldPost == "lostcredit" ){
    
    $status_val = "5";
    
    $fielvalid = validaInteger($status_val, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $status_val;
                
        $datasErr = "";
        $datasUpdate = array(
            'id_status_credito' => $fieldRowEdit,
            'fecha_cierre_definitivo_credito' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos        
        $db->where('id_creditos', $idRow);
        $goEdit = $db->update('creditos_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No fue posible cambiar el estado de este credito, a Dificil cartera</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Dificil cartera";
        $ruleERR = "Parece que algunos datos estan corrompidos"; 
        $exERR = "Presiona el boton cancelar de este credito"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
       
    exit(json_encode($response));
}


/*
*REACTIVAR CREDITO
*/   
if(isset($fieldPost) && $fieldPost == "acticredit" ){
    
    $status_val = "1";
    
    $fielvalid = validaInteger($status_val, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $status_val;
                
        $datasErr = "";
        $datasUpdate = array(
            'id_status_credito' => $fieldRowEdit,
            'fecha_cierre_definitivo_credito' => "0000-00-00"
        );
                        
        //actualizar campo en base de datos        
        $db->where('id_creditos', $idRow);
        $goEdit = $db->update('creditos_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No fue posible reactivar este credito</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Reactivar credito";
        $ruleERR = "Parece que algunos datos estan corrompidos"; 
        $exERR = "Presiona el boton cancelar de este credito"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
       
    exit(json_encode($response));
}    