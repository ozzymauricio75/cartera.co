<?php
require_once '../appweb/lib/MysqliDb.php';
require_once "../cxconfig/config.inc.php";
require_once '../cxconfig/global-settings.php';
require_once 'sessionvars.php';
require_once 'query-custom-user.php';
require_once "../appweb/lib/gump.class.php";
require_once "../appweb/inc/site-tools.php"; 


$response = array();

/*
***************************
***************************
*RECIBE DATOS A EDITAR
***************************
***************************
*/

$field = $_POST['field'];
$fieldVal = $_POST['value'];
$idPost = $_POST['post'];
$fieldPost = $_POST['fieldedit'];

/*
*==========================
*TABLA DEUDOR
*==========================
*/

/*--//TELEFONO FIJO//--*/

if(isset($fieldPost) && $fieldPost == "tel1deudordb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRowEdit = $fieldVal;
        
        $datasErr = "";
        $datasUpdate = array(
            'tel_uno_deudor' => $fieldRowEdit,
            'fecha_edita_deudor' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_deudor', $idRow);
        $goEdit = $db->update('deudor_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono fijo";
        $ruleERR = "Número teléfono fijo de la forma (##) ###-####. Sí el indicativo es de un digito escribe el primero y luego presiona [ESPACIO] en tu teclado"; 
        $exERR = "(5) 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}

/*--//TELEFONO CELULAR//--*/

if(isset($fieldPost) && $fieldPost == "tel2deudordb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'tel_dos_deudor' => $fieldRowEdit,
            'fecha_edita_deudor' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_deudor', $idRow);
        $goEdit = $db->update('deudor_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono Celular";
        $ruleERR = "Número teléfono celular de la forma ### ###-####"; 
        $exERR = "355 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}



/*--//GEO DOMICILIO DEUDOR//--*/
if(isset($fieldPost) && $fieldPost == "geodirdeudordb"){
            
    //recibe datos    
    $direccionFullPost = empty($_POST['addressfullvar']) ? "" : $_POST['addressfullvar'];
    
    $usercity1 = empty($_POST['citi1var']) ? "" : $_POST['citi1var'];
    $usercity2 = empty($_POST['citi2var']) ? "" : $_POST['citi2var'];
    $usercity3 = empty($_POST['citi3var']) ? "" : $_POST['citi3var'];
    $userstate = empty($_POST['statevar']) ? "" : $_POST['statevar'];
    $userstateshort = empty($_POST['shortStatevar']) ? "" : $_POST['shortStatevar'];    
    $paisPost = empty($_POST['countryvar']) ? "" : $_POST['countryvar'];
    $paisCodPost = empty($_POST['codecountryvar']) ? "" : $_POST['codecountryvar'];
    $latstore = empty($_POST['lativar']) ? "" : $_POST['lativar'];
    $lngstore = empty($_POST['longvar']) ? "" : $_POST['longvar'];
    $userzip = empty($_POST['zipvar']) ? "" : $_POST['zipvar'];
    $idMapPost = empty($_POST['idplacevar']) ? "" : $_POST['idplacevar'];
                                                                           
    $fielvalid = validaAlphaSpace($direccionFullPost, $idPost);
            
    if($fielvalid === true){
        
        //***********
        //VALIDACION Y EXISTENCIA DE CIUDAD
        //***********
        $usercityUser = "";
        $selectCity = array();

        $selectCity[] = $usercity1;
        $selectCity[] = $usercity2;
        $selectCity[] = $usercity3;
        $countSelectCity = count($selectCity);

        if(($countSelectCity > 0) && !empty($countSelectCity)){
            //verifica en cual variable se encuentra el nombre de la ciudad y la guardamos en $usercityUser
            for($i=0; $i<= $countSelectCity; $i++){    
                switch($selectCity){
                    case $selectCity[0] != "":
                        $usercityUser = $selectCity[0];
                    break;
                    case $selectCity[1] != "":
                        $usercityUser = $selectCity[1];
                    break;
                    case $selectCity[2] != "":
                        $usercityUser = $selectCity[2];
                    break;
                }
            }

            //verifica s la ciudad ya esta registada en la base de datos
            $goNewCity = "";
            $cityTag = format_uri($usercityUser);

            //si la ciudad no existe entoncs se registra
            if(!existCityUserDB($cityTag)){                
                $regionTag = format_uri($userstate);
                $countryDef = $paisPost;
                $countryCodDef = $paisCodPost;
                $goNewCity = "ok"; 

                if($goNewCity == "ok"){
                    if(insertNewCity($cityTag, $usercityUser, $userstateshort, $userstate, $regionTag, $countryDef, $countryCodDef)){
                        $statusNewCity = 1;
                    }else{
                        $response['error']= "Ocurrio un error en el momento de ingrezar el geoposicionamiento, por favor intentalo de nuevo";
                    }
                }
            }
        }
        
        //***********
        //ARRAY DATOS INSERTAR
        //***********
        
        
        $idRow = $idPost;        
        $fieldRowEdit = $direccionFullPost;
                                               
        //////////////////////////////////////
                
        $dataUpdate = array (
            'dir_geo_deudor' => $direccionFullPost,
            'latitud_geo_deudor' => $latstore,
            'longitud_geo_deudor'=>$lngstore,
            'url_maps_deudor'=> $idMapPost,
            'codigo_geo_ciudad_deudor' => $usercityUser,
            'codigo_geo_estado_deudor' => $userstate,
            'codigo_geo_pais_deudor'=> $paisPost,
            'codigo_postal_geo_deudor' =>$userzip, 
            'fecha_edita_deudor' => $dateFormatDB
        );   
        
        //echo "<pre>";
        //print_r($dataUpdate);
        
        $db->where('id_deudor', $idRow);
        $goEdit = $db->update('deudor_tbl', $dataUpdate);             
        /////////////////////////////////////
        
        if($goEdit === true){            
            $response = $fieldRowEdit;
        }else{
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
        }
        
    }else{
        $tituERR = "Geoposición";
        $ruleERR = "Escribe y selecciona la dirección de domicilio"; 
        $exERR = "Carrera 39 # 7-24, Cali, Colombia"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);  
    }
    exit(json_encode($response));
}    



/*
*==========================
*TABLA CODEUDOR
*==========================
*/

/*--//TELEFONO FIJO//--*/

if(isset($fieldPost) && $fieldPost == "tel1codeudordb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRowEdit = $fieldVal;
        
        $datasErr = "";
        $datasUpdate = array(
            'tel_uno_codeudor' => $fieldRowEdit,
            'fecha_edita_codeudor' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_codeudor', $idRow);
        $goEdit = $db->update('codeudor_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono fijo";
        $ruleERR = "Número teléfono fijo de la forma (##) ###-####. Sí el indicativo es de un digito escribe el primero y luego presiona [ESPACIO] en tu teclado"; 
        $exERR = "(5) 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}


/*--//TELEFONO CELULAR//--*/

if(isset($fieldPost) && $fieldPost == "tel1codeudordb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'tel_dos_codeudor' => $fieldRowEdit,
            'fecha_edita_codeudor' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_codeudor', $idRow);
        $goEdit = $db->update('codeudor_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono Celular";
        $ruleERR = "Número teléfono celular de la forma ### ###-####"; 
        $exERR = "355 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}    

/*--//DIRECCION RESIDENCIA//--*/

if(isset($fieldPost) && $fieldPost == "domiciliocodeudordb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'dir_domicilio_codeudor' => $fieldRowEdit,
            'fecha_edita_codeudor' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_codeudor', $idRow);
        $goEdit = $db->update('codeudor_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar la dirección</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Dirección domicilio";
        $ruleERR = "Escribe una dirección domiclio valida. [calle][#][55-55][Barrio][Ciudad]"; 
        $exERR = "Carrera 100 # 55-55 Mi barrio, Cali"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}  


/*
*==========================
*TABLA REFERENCIA PERSONAL
*==========================
*/

/*--//TELEFONO FIJO//--*/

if(isset($fieldPost) && $fieldPost == "tel1refpersodb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRowEdit = $fieldVal;
        
        $datasErr = "";
        $datasUpdate = array(
            'tel_uno_referencia_personal' => $fieldRowEdit,
            'fecha_edita_referencia_personal' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_personal', $idRow);
        $goEdit = $db->update('referencia_personal_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono fijo";
        $ruleERR = "Número teléfono fijo de la forma (##) ###-####. Sí el indicativo es de un digito escribe el primero y luego presiona [ESPACIO] en tu teclado"; 
        $exERR = "(5) 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}


/*--//TELEFONO CELULAR//--*/

if(isset($fieldPost) && $fieldPost == "tel2refpersodb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'tel_dos_referencia_personal' => $fieldRowEdit,
            'fecha_edita_referencia_personal' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_personal', $idRow);
        $goEdit = $db->update('referencia_personal_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono Celular";
        $ruleERR = "Número teléfono celular de la forma ### ###-####"; 
        $exERR = "355 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}    

/*--//DIRECCION RESIDENCIA//--*/

if(isset($fieldPost) && $fieldPost == "domiciliorefpersodb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'dir_domicilio_referencia_personal' => $fieldRowEdit,
            'fecha_edita_referencia_personal' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_personal', $idRow);
        $goEdit = $db->update('referencia_personal_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar la dirección</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Dirección domicilio";
        $ruleERR = "Escribe una dirección domiclio valida. [calle][#][55-55][Barrio][Ciudad]"; 
        $exERR = "Carrera 100 # 55-55 Mi barrio, Cali"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}  



/*
*==========================
*TABLA REFERENCIA FAMILIAR
*==========================
*/

/*--//TELEFONO FIJO//--*/

if(isset($fieldPost) && $fieldPost == "tel1reffamidb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRowEdit = $fieldVal;
        
        $datasErr = "";
        $datasUpdate = array(
            'tel_uno_referencia_familiar' => $fieldRowEdit,
            'fecha_edita_referencia_familiar' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_familiar', $idRow);
        $goEdit = $db->update('referencia_familiar_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono fijo";
        $ruleERR = "Número teléfono fijo de la forma (##) ###-####. Sí el indicativo es de un digito escribe el primero y luego presiona [ESPACIO] en tu teclado"; 
        $exERR = "(5) 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}


/*--//TELEFONO CELULAR//--*/

if(isset($fieldPost) && $fieldPost == "tel2reffamidb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'tel_dos_referencia_familiar' => $fieldRowEdit,
            'fecha_edita_referencia_familiar' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_familiar', $idRow);
        $goEdit = $db->update('referencia_familiar_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono Celular";
        $ruleERR = "Número teléfono celular de la forma ### ###-####"; 
        $exERR = "355 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}    

/*--//DIRECCION RESIDENCIA//--*/

if(isset($fieldPost) && $fieldPost == "domicilioreffamidb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'dir_domicilio_referencia_familiar' => $fieldRowEdit,
            'fecha_edita_referencia_familiar' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_familiar', $idRow);
        $goEdit = $db->update('referencia_familiar_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar la dirección</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Dirección domicilio";
        $ruleERR = "Escribe una dirección domiclio valida. [calle][#][55-55][Barrio][Ciudad]"; 
        $exERR = "Carrera 100 # 55-55 Mi barrio, Cali"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}



/*
*==========================
*TABLA REFERENCIA COMERCIAL
*==========================
*/

/*--//TELEFONO FIJO//--*/

if(isset($fieldPost) && $fieldPost == "tel1refcomerdb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRowEdit = $fieldVal;
        
        $datasErr = "";
        $datasUpdate = array(
            'tel_uno_referencia_comercial' => $fieldRowEdit,
            'fecha_edita_referencia_comercial' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_comercial', $idRow);
        $goEdit = $db->update('referencia_comercial_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono fijo";
        $ruleERR = "Número teléfono fijo de la forma (##) ###-####. Sí el indicativo es de un digito escribe el primero y luego presiona [ESPACIO] en tu teclado"; 
        $exERR = "(5) 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}


/*--//TELEFONO CELULAR//--*/

if(isset($fieldPost) && $fieldPost == "tel2refcomerdb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'tel_dos_referencia_comercial' => $fieldRowEdit,
            'fecha_edita_referencia_comercial' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_comercial', $idRow);
        $goEdit = $db->update('referencia_comercial_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono Celular";
        $ruleERR = "Número teléfono celular de la forma ### ###-####"; 
        $exERR = "355 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}    

/*--//DIRECCION RESIDENCIA//--*/

if(isset($fieldPost) && $fieldPost == "domiciliorefcomerdb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;        
        $fieldRowEdit = $fieldVal;
                
        $datasErr = "";
        $datasUpdate = array(
            'dir_domicilio_referencia_comercial' => $fieldRowEdit,
            'fecha_edita_referencia_comercial' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_comercial', $idRow);
        $goEdit = $db->update('referencia_comercial_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar la dirección</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Dirección domicilio";
        $ruleERR = "Escribe una dirección domiclio valida. [calle][#][55-55][Barrio][Ciudad]"; 
        $exERR = "Carrera 100 # 55-55 Mi barrio, Cali"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}


/*--//TELEFONO EMPRESA//--*/

if(isset($fieldPost) && $fieldPost == "telempresarefcomerdb" ){
    
    $fielvalid = validaAlphaSpace($fieldVal, $idPost);
    
    if($fielvalid === true){
        $idRow = $idPost;
        $fieldRowEdit = $fieldVal;
        
        $datasErr = "";
        $datasUpdate = array(
            'tel_empresa_referencia_comercial' => $fieldRowEdit,
            'fecha_edita_referencia_comercial' => $dateFormatDB
        );
                        
        //actualizar campo en base de datos
        //$goEdit = editFielDB($idRow, $fieldRow, $fieldRowEdit, $idFieldTbl, $tbl, $tituSqlERR); 
        $db->where('id_referencia_comercial', $idRow);
        $goEdit = $db->update('referencia_comercial_tbl', $datasUpdate);
        
        if($goEdit === true){
            $response = $fieldRowEdit;   
        }else{                       
            
            $datasErr = $db->getLastErrno();

            $errQueryTmpl ="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo guardar el número de teléfono</b>
                <br>Erro: ".$datasErr."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl .="</ul>";


            $response['error']= $errQueryTmpl;
            
        }    
    }else{
        $tituERR = "Telefono fijo";
        $ruleERR = "Número teléfono fijo de la forma (##) ###-####. Sí el indicativo es de un digito escribe el primero y luego presiona [ESPACIO] en tu teclado"; 
        $exERR = "(5) 555-5555"; 
        $response['error'] = printErrValida($fielvalid, $tituERR, $ruleERR, $exERR);            
    }
    exit(json_encode($response));
}