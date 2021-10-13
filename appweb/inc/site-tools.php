<?php
//////////////////////////////////////
//ACTIVAR ORDER
//////////////////////////////////////
function actiOrder($idCompanyDbUser_, $idDBUser_, $nameDBUser_){
    global $db;
    global $timeStamp;
    global $dateFormatDB;
    global $horaFormatDB;
                    
    $idUser_order = (int)$idDBUser_;
    $idUser_order = $db->escape($idUser_order);
    
    $idStore_order = (int)$idCompanyDbUser_;//$_GET['codter'];
    $idStore_order = $db->escape($idStore_order);
        
    $repreStore_order = (string)$nameDBUser_;//$_GET['ter'];
    $repreStore_order = $db->escape($repreStore_order);
    
    $tablaQ = "solicitud_pedido_temp"; 
    $campoQ = "id_solici_promo";
    $clave = lastIDRegis($tablaQ, $campoQ);                
    $clave = $clave + 1;
    $lastOrderDB = $clave;
    switch($clave) {
		
		case ($clave < 10):
			$prefijo = "00000";
			$clave = $prefijo.$clave;
		break;	
		
		case ($clave < 100):
			$prefijo = "0000";
			$clave = $prefijo.$clave;
		break;
		
		case ($clave < 1000):
			$prefijo = "000";
			$clave = $prefijo.$clave;
		break;	
	
		case ($clave < 10000):
			$prefijo = "00";
			$clave = $prefijo.$clave;
		break;	
		
		case ($clave < 100000):
			$prefijo = "0";
			$clave = $prefijo.$clave;
		break;
	
		case ($clave >= 100000):
			$clave = $clave;
		break;
	}
    $fecha_order = date("Y-m-d");
	$cod_order = "INT-$clave";
    
    
    
    $newOrderTempData = array(
        /*'id_solici_promo' => $lastOrderDB,*/
        'id_account_empre' => $idStore_order,
        'representante_empresa' => $repreStore_order,
        'cod_orden_compra' => $cod_order,
        'fecha_solicitud' => $dateFormatDB,
        'hora_solicitud' => $horaFormatDB,
        'datetime_publi' => $timeStamp,
        'id_account_user' => $idUser_order
    );
                      
    $idNewOrderTemp = $db->insert ('solicitud_pedido_temp', $newOrderTempData);
                            
    if($idNewOrderTemp){
         
        //$jumpNewOrder = $takeOrderDir."/inicio/?otmp=".$lastOrderDB;
        //gotoRedirect($jumpNewOrder);
        
        return $idNewOrderTemp;//$lastOrderDB;
    }else{    
        $errsponsordb = "Failed to insert new ORDER:\n Erro:". $db->getLastErrno();
        $errDBSponsorArr[] = array($errsponsordb);
        return $errDBSponsorArr;
    }
}//fin acti order


//////////////////////////////////////
//OBTENER URL ACTUAL EN EL EXPLORADOR
//////////////////////////////////////
function obtenerURL() {
  $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
  $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
  $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
  return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}
 
function strleft($s1, $s2) {
  return substr($s1, 0, strpos($s1, $s2));
}

$urlBrowser = obtenerUrl();
$datosUrlBrowser = parse_url($urlBrowser);
$quqerySR = "";
if(is_array($datosUrlBrowser)){
    foreach ($datosUrlBrowser as $keyURLBrow=>$valURLBrow) {
        //KEYS URL ARRAY
        //['scheme']  =>   scheme: http 
        //['host']    =>   host: 127.0.0.1 
        //['path']    =>   path: /projects/arizul/takeorder/browse/ 
        //['query']   =>   query: search=producto+importado&sb=ok 
        //echo "$key: $value <br  >";
      $quqerySR  = (isset($datosUrlBrowser['query']))? $datosUrlBrowser['query'] : "" ;
    }
}

//print_r($datosUrlBrowser);
/*
echo "?".$quqerySR."<br><br>";
 
$url_actual = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
echo "<b>$url_actual</b>";
*/



//////////////////////////////////////
//REDIRECT FUNCTION
//////////////////////////////////////

function gotoRedirect($fileDestiny_){
    global $pathmm;
    //header("Location: $host$uri/$extra");
    //header( "refresh:0;url=$host_/$extra_" ); 
	//exit;
    
    //$newRedirect = $pathmm."/".$fileDestiny_;
    $newRedirect = $pathmm.$fileDestiny_;
    
    //return $newRedirect;
    //echo $newRedirect;
    if (!headers_sent($filename, $linenum)) {
        header('Location: ' .$newRedirect);
        exit;

    // Lo más probable es generar un error aquí.
    } else {

//        echo "Headers already sent in $filename on line $linenum\n" .
//              "Cannot redirect, for now please click this <a " .
//              "href=\"http://www.example.com\">link</a> instead\n";
//        exit;
        
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$newRedirect.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$newRedirect.'" />';
        echo '</noscript>';
        
        exit;
    }
     
}

/////////////////////////////////////////////////
// TRATAMIENTO SEARCH BOX
/////////////////////////////////////////////////

function formatConsuString($string, $separator = ' '){
    $special_cases = array( '&' => 'and');
	$string = trim( $string );
	$string = strtolower($string);
	$string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
//	$string = htmlspecialchars($string, ENT_QUOTES);
	$string = str_replace('+', "$separator", $string);
	return $string;	
}

/////////////////////////////////////////////////
// EMAIL ACCOUNT EXISTENTE
/////////////////////////////////////////////////

function emailUnico($useremail_) { 
    global $db; 
    
    $emailPost = (string)$useremail_; 
    $emailPost = $db->escape($emailPost);
    //$db->where ("customerId",1); 
    $db->where ("mail_accountusu", $emailPost); 
    //$db->update ('users', $data);         
    $usersEmail = $db->get ("account_usu"); 
    $emailExist = count($usersEmail);
    if ($emailExist > 0) {             
        return false; 
    }else{
        return true;
    }                    
}     


/////////////////////////////////////////////////
// VALIDAR REGISTRO - POST EXISTENTE
/////////////////////////////////////////////////

function existPost($tablaExiQ_, $campoExiQ_, $colValExiQ_) { 
    global $db; 
    
    $tbl = (string)$tablaExiQ_; 
    $col = (string)$campoExiQ_; 
    $colVal = (string)$colValExiQ_;
    $tbl = $db->escape($tbl);
    $col = $db->escape($col);
    $colVal = $db->escape($colVal);
       
    $db->where ($col, $colVal);
    //$db->where ($col, NULL, 'IS NOT');
    //if($idPostDif_){ $db->where (idPostDif_); }
    $regDB = $db->get ($tbl, 1, $col); 
    $regDBExist = count($regDB);
    if ($regDBExist > 0) {             
        return true; 
    }else{
        return false;
    }                    
} 

/////////////////////////////////////////////////
// ARRAY AGRUPAR CLAVES REPETIDOS
/////////////////////////////////////////////////
//http://php.net/manual/es/function.array-unique.php
function unique_multidim_array($array, $key) { 
	$temp_array = array(); 
	$i = 0; 
	$key_array = array(); 
	
	foreach($array as $val) { 
		if (!in_array($val[$key], $key_array)) { 
			$key_array[$i] = $val[$key]; 
			$temp_array[$i] = $val; 
		} 
		$i++; 
	} 
	return $temp_array; 
}


/////////////////////////////////////////////////
// ORDENAR ARRAY 
/////////////////////////////////////////////////
//http://php.net/manual/es/function.ksort.php
function sksort(&$array, $subkey="id", $sort_ascending=false) {
    $temp_array = array();
    if (count($array))
        $temp_array[key($array)] = array_shift($array);

    foreach($array as $key => $val){
        $offset = 0;
        $found = false;
        foreach($temp_array as $tmp_key => $tmp_val)
        {
            if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
            {
                $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                                            array($key => $val),
                                            array_slice($temp_array,$offset)
                                          );
                $found = true;
            }
            $offset++;
        }
        if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
    }

    if ($sort_ascending) $array = array_reverse($temp_array);

    else $array = $temp_array;
}



//////////////////////////////////////////////////////
//TRATAMIENTO FILE IMAGE
/////////////////////////////////////////////////////

//organiza MULTIPLES archivos de imagenes 
function reArrayFiles(&$file_post)
    {
        $file_ary = array();
        $multiple = is_array($file_post['name']);

        $file_count = $multiple ? count($file_post['name']) : 1;
        $file_keys = array_keys($file_post);
        
        for ($i=0; $i<$file_count; $i++)
        {
            foreach ($file_keys as $key)
            {
                $file_ary[$i][$key] = $multiple ? $file_post[$key][$i] : $file_post[$key];                
            }
        }
                        
        return $file_ary;
    }

function reArrayErr(&$file_post)
    {
        $file_ary = array();
        $multiple = is_array($file_post['nomefile']);

        $file_count = $multiple ? count($file_post['nomefile']) : 1;
        $file_keys = array_keys($file_post);
        
        for ($i=0; $i<$file_count; $i++)
        {
            foreach ($file_keys as $key)
            {
                $file_ary[$i][$key] = $multiple ? $file_post[$key][$i] : $file_post[$key];                
            }
        }
                        
        return $file_ary;
    }


//validar file typo imagen
function validafile($fotoProdPost_, $fotoProdName_, $fotoProdTmpName_, $fotoProdType_, $fotoProdSize_, $filePostErro_){
	global $err;
		
	$max_size = 1700; 
	$alwidth = 1600; 
	$alheight = 1600; 
	$allowtype = array('jpg', 'jpeg', 'png');
    $err = '';
    
    if(isset($fotoProdPost_) && strlen($fotoProdName_) > 1 && $filePostErro_ == 0) {
        $sepext = explode('.', strtolower($fotoProdName_));
        $type = end($sepext);  
        list($width, $height) = getimagesize($fotoProdTmpName_); 
                
        if(!in_array($type, $allowtype)) $err["tipefile"] = "jpg, jpeg, png";
        if($fotoProdSize_ > $max_size*1000) $err["sizefile"] = $max_size."KB";        
        if(isset($width) && isset($height) && ($width > $alwidth || $height > $alheight)) $err["squarefile"] = $alwidth."px/".$alheight."px";

        if($err == ''){
            return true;		
        }else{
            $err["nomefile" ] = $fotoProdName_;
            return $err;			
        }
	}
    
}

//muestra errores
function printFileErr($fileCheck_){

    //foreach ($fileCheck_ as $namesErr => $valCheck) {     
        foreach ($fileCheck_ as $keysErr => $valErr) { 
            $nomeFile = (empty($valErr['nomefile']))?"":$valErr['nomefile'];//"foto.sql";//$u['nomefile'];
            $typeFile = (empty($valErr['tipefile']))?"":$valErr['tipefile'];//"jog, opng";//$u['tipefile'];
            $sizeFile = (empty($valErr['sizefile']))?"":$valErr['sizefile'];//"";//$u['sizefile'];
            $squareFile = (empty($valErr['squarefile']))?"":$valErr['squarefile'];//"";//$u['squarefile'];

            $errTmpl="<li class='list-group-item list-group-item-danger'><b>{$nomeFile}</b>";
            $errTmpl.="<br>Erros:";
            if(!empty($typeFile)){ $errTmpl.="<br>Só pode usar fotos ou arquivos de imagem:&nbsp;&nbsp;" .$typeFile; }
            if(!empty($sizeFile)){ $errTmpl.="<br>A sua foto é muito pesada, o limite é&nbsp;&nbsp;" .$sizeFile; }
            if(!empty($squareFile)){ $errTmpl.="<br>A sua foto é muito grande, o limite é&nbsp;&nbsp;" .$squareFile; }
            $errTmpl.="</li>";
        }
    //}
  
    //echo $errTmpl; 
    return $errTmpl; 
}

//sube archivo imagen
function upimgfile($fotoProdName_, $fotoProdTmpName_){    
    $uploadpath = "../files-display/temp/";
	$uploadpath = $uploadpath . basename( $fotoProdName_);     
	
	if(move_uploaded_file($fotoProdTmpName_, $uploadpath)){
		return true;
	}else{
	   return false;
	}
}

//redimensiona file imagen portada - label
function redimensionaImgFile($fotoProdName_, $fotoProdType_, $urlCleanProdInsert_, $pathFileEnd_, $squareFile_){
    ob_start(); 
    //global $pathmm;
    $uploadpath = "../files-display/temp/";
    $uploadpath = $uploadpath . basename( $fotoProdName_); 
    
    $newpath = "../files-display/".$pathFileEnd_;
        
    $medida = $squareFile_;
    $qualityFile = 75;

    $nomePortadaRecibe = $urlCleanProdInsert_;
    $nombrePortadaProd = $nomePortadaRecibe.".jpg";
    
    $filePathEnd = $newpath.$nombrePortadaProd;
    
    switch($fotoProdType_){
        case "image/jpg":
            $imagen =  @imagecreatefromjpeg($uploadpath); 
            
            $ancho = @imagesx ($imagen);
            $alto = @imagesy ($imagen);
            
            if($ancho>=$alto){
                $nuevo_alto = round($alto * $medida / $ancho,0);   
                $nuevo_ancho=$medida;
            }else{
                $nuevo_ancho = round($ancho * $medida / $alto,0);
                $nuevo_alto =$medida;   
            }            
                                    
            $imagen_nueva = @imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
            @imagecopyresampled($imagen_nueva, $imagen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
            if(@imagejpeg($imagen_nueva, $filePathEnd, $qualityFile)){
                return true;
            }
            @imagedestroy($imagen_nueva);
            @imagedestroy($imagen);
        break;
        case "image/jpeg":
            $imagen =  @imagecreatefromjpeg($uploadpath); 
            
            $ancho = @imagesx ($imagen);
            $alto = @imagesy ($imagen);
            
            if($ancho>=$alto){
                $nuevo_alto = round($alto * $medida / $ancho,0);   
                $nuevo_ancho=$medida;
            }else{
                $nuevo_ancho = round($ancho * $medida / $alto,0);
                $nuevo_alto =$medida;   
            }            
                                    
            $imagen_nueva = @imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
            @imagecopyresampled($imagen_nueva, $imagen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
            if(@imagejpeg($imagen_nueva, $filePathEnd, $qualityFile)){
                return true;
            }
            @imagedestroy($imagen_nueva);
            @imagedestroy($imagen);
        break;
        case "image/png":
            $imagen =  @imagecreatefrompng($uploadpath); 
            
            $ancho = @imagesx ($imagen);
            $alto = @imagesy ($imagen);
            
            if($ancho>=$alto){
                $nuevo_alto = round($alto * $medida / $ancho,0);   
                $nuevo_ancho=$medida;
            }else{
                $nuevo_ancho = round($ancho * $medida / $alto,0);
                $nuevo_alto =$medida;   
            }  
            
            $imagen_nueva = @imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
            
            imagefill($imagen_nueva, 0, 0, imagecolorallocate($imagen_nueva, 255, 255, 255));
            imagealphablending($imagen_nueva, TRUE);
            imagecopy($imagen_nueva, $imagen, 0, 0, 0, 0, $ancho, $alto);
                                
            @imagecopyresampled($imagen_nueva, $imagen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
            
            if(@imagejpeg($imagen_nueva, $filePathEnd, $qualityFile)){
                return true;
            }
            @imagedestroy($imagen_nueva);
            @imagedestroy($imagen);
        break;              
    }
    
    ob_clean(); 
}


/////////////////////////////////////////////////
///NOME CLEAN - URL AMIGABLE
/////////////////////////////////////////////////


//---------------url clean

function format_uri( $string, $separator = '-' )
{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and');
	$string = trim( $string );
	$string = strtolower($string);
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
}


///////////////////////////////////////////////////
/// TEXTO ALEATORIO - COD. PUBLICACION - COD. USUARIO
///////////////////////////////////////////////////

function generar_txtAct($longitud,$especiales){ 

    $clave = "";
            // Array con los valores a escojer
    $semilla = array(); 
    $semilla[] = array('a','e','i','o','u');  
    $semilla[] = array('b','c','d','f','g','h','j','k','l','m','n','p','q','r','s','t','v','w','x','y','z'); 
    //$semilla[] = array('0','1','2','3','4','5','6','7','8','9'); 
    $semilla[] = array('A','E','I','O','U');  
    $semilla[] = array('B','C','D','F','G','H','J','K','L','M','N','P','Q','R','S','T','V','W','X','Y','Z'); 
    $semilla[] = array('0','1','2','3','4','5','6','7','8','9'); 

    // si puede contener caracteres especiales, aumentamos el array $semilla 
    if ($especiales) { $semilla[] = array('$','#','%','&','@','-','?','¿','!','¡','+','-','*'); } 

    // creamos la clave con la longitud indicada 
    for ($bucle=0; $bucle < $longitud; $bucle++)  
    { 
        // seleccionamos un subarray al azar 
        $valor = mt_rand(0, count($semilla)-1); 
        // selecccionamos una posicion al azar dentro del subarray 
        $posicion = mt_rand(0,count($semilla[$valor])-1); 
        // cojemos el caracter y lo agregamos a la clave 
        $clave .= $semilla[$valor][$posicion]; 						
    } 
    // devolvemos la clave 
    return $clave; 
}

////////////////////////////////////////////////////
// CONSULTA ULTIMO REGISTRO DE UNA TABLA
///////////////////////////////////////////////////

function lastIDRegis($tablaQ_, $campoQ_){
    global $db;
	
    $tbl = (string)$tablaQ_; 
    $col = (string)$campoQ_; 
    $tbl = $db->escape($tbl);
    $col = $db->escape($col);
    
    $select = "MAX(".$col.") as idLast";            
    $maxReg = $db->getOne ($tbl, $select); 
    $resuReg = $maxReg['idLast'];                            
    return $resuReg;
}

////////////////////////////////////////////////////
// VALIDA - ADD CITY TO DATA BASE
///////////////////////////////////////////////////

function existCityUserDB($cityTag_){	    
    global $db;    
    $cityPost = (string)$cityTag_; 
    $cityPost = $db->escape($cityPost);    
    $db->where ("tag_ciudad_usuario", $cityPost);            
    $rowCity = $db->get ("ciudad_usuario_tbl", 1, "id_ciudad_usuario, tag_ciudad_usuario"); 
    $cityExist = count($rowCity);
    if ($cityExist > 0) {             
        return true; 
    }else{
        return false;
    } 
}

function insertNewCity($cityTag_, $usercityUser_, $userstateshort_, $userstate_, $regionTag_, $countryDef_, $countryCodDef_){
        
    global $db;  
        
    $cityTagPost = (string)$cityTag_;
    $cityUserPost = (string)$usercityUser_;
    $stateShortUserPost = (string)$userstateshort_;
    $stateUserPost = (string)$userstate_;
    $regionTagPost = (string)$regionTag_; 
    $countryUserPost = (string)$countryDef_;
    $countryCodUserPost = (string)$countryCodDef_;
    
    $cityTagPost = $db->escape($cityTagPost);  
    $cityUserPost = $db->escape($cityUserPost);      
    $stateShortUserPost = $db->escape($stateShortUserPost);  
    $stateUserPost = $db->escape($stateUserPost);  
    $regionTagPost = $db->escape($regionTagPost);  
    $nombrePaisPost = $db->escape($countryUserPost);  
    $codPaisPost = $db->escape($countryCodUserPost);  
    
    //consulta si region existe
    $db->where ("estado_usuario_tag", $regionTagPost);            
    $rowRegion = $db->getOne ("estado_usuario_tbl", "id_estado_usuario, estado_usuario_tag"); 
    $regionExist = count($rowRegion);    
    if ($regionExist > 0) {//si region existe inserta ciudad para esa region          
        $idRegion = $rowRegion['id_estado_usuario'];
        
        $citydata = array(
            'id_estado_usuario' => $idRegion,
            'nombre_ciudad_usuario' => $cityUserPost,
            'tag_ciudad_usuario' => $cityTagPost            
        );
                
        $cityInsert = $db->insert ('ciudad_usuario_tbl', $citydata);
        if(!$cityInsert){
            $erroQuery = $db->getLastError();    
        }
        
    }else{//si region no existe crea region y luego ciudad
        
        /***************************************/
        $idPaisInsert = "";
        //consulta si pais existe
        $db->where ("cod_pais", $codPaisPost);            
        $rowPais = $db->getOne ("pais_usuario_tbl", "id_pais_usuario, cod_pais"); 
        $paisExist = count($rowPais);    
        if ($paisExist > 0) {
            $idPaisExist = $rowPais['id_pais_usuario'];
            $idPaisInsert = $idPaisExist;
        }else{
            $paisdata = array(
                'nombre_pais_usuario' => $nombrePaisPost,
                'cod_pais' => $codPaisPost
            );    
            $newPais = $db->insert ('pais_usuario_tbl', $paisdata);
            $idPaisInsert = $newPais;
        }
        
        /****************************************/
        
        $regiondata = array(            
            'id_pais_usuario' => $idPaisInsert,
            'nombre_estado_usuario' => $stateUserPost,
            'cod_estado' => $stateShortUserPost,
            'estado_usuario_tag' => $regionTagPost            
        );
        $regionInsert = $db->insert ('estado_usuario_tbl', $regiondata);
        if($regionInsert){//si la region fue insertada inserta ciudad
            $idLastRegion = $regionInsert;
            $citydataLR = array(                               
                'id_estado_usuario' => $idLastRegion,
                'nombre_ciudad_usuario' => $cityUserPost,
                'tag_ciudad_usuario' => $cityTagPost                
            );
            $citiLRInsert = $db->insert ('ciudad_usuario_tbl', $citydataLR);
            if(!$citiLRInsert){ $erroQuery = $db->getLastError(); }
        }else{
            $erroQuery = $db->getLastError();
        }
    }                
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//+++++++++++++++++++++++++++++++++++
//+++++++++++++++++++++++++++++++++++
// CRUD FUNCIONES  - (mostrar, registrar, actualizar, eliminar)
//+++++++++++++++++++++++++++++++++++
//+++++++++++++++++++++++++++++++++++
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
$validfield = new GUMP();

/////////////////////////
//FUNCIONES DE VALIDACION
/////////////////////////

//CAMPO NUMERICO
function validaNumeric($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => $idPostEdit_
    );
    
    $rules = array(
        'fieldpost' => 'numeric',
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
    
}

//MAXIMO CARACTERES
function validaMaxLenght($field_, $cant_){
    global $validfield;
    
    $reglaML = $cant_;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => 1
    );
    
    $rules = array(
        'fieldpost' => $reglaML,
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
    
}


//IDS
function validaInteger($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => $idPostEdit_
    );
    
    $rules = array(
        'fieldpost' => 'integer',
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
    
}

//MONEDA
function validaMoneda($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => $idPostEdit_
    );
    
    $rules = array(
        'fieldpost' => 'float',
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
    
}

//TEXTO SIN ESPACIOS DASH
function validaAlphaDash($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => $idPostEdit_
    );
    
    $rules = array(
        'fieldpost' => 'alpha_dash',
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
    /*if($isvalid === true) {
    //if($validfield->validate($postField, $rules)){
       return true;
    } else {        
        $err = $validfield->get_readable_errors(true);
        return $err;
    }*/
    
}

//TEXTO ESPACIOS
function validaAlphaSpace($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => $idPostEdit_
    );
    
    $rules = array(
        'fieldpost' => 'alpha_space',
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
    /*if($isvalid === true) {
    //if($validfield->validate($postField, $rules)){
       return true;
    } else {        
        $err = $validfield->get_readable_errors(true);
        return $err;
    }*/
    
}

//FECHA Y HORA
function validaDateTime($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => $idPostEdit_
    );
    
    $filters = array(
        'fieldpost' => 'date',
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $filters);
    
    return $resuvalid;
}

//EMAIL
function validaEmail($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => $idPostEdit_
    );
    
    $filters = array(
        'fieldpost' => 'valid_email',
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $filters);
    
    return $resuvalid;
}

//URL 
function validaURL($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(
        'fieldpost' => $field_,
        'idpost' => $idPostEdit_
    );
    
    $rules = array(
        'fieldpost' => 'valid_url',
        'idpost' => 'integer'
    );
        
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
    /*if($isvalid === true) {
    //if($validfield->validate($postField, $rules)){
       return true;
    } else {        
        $err = $validfield->get_readable_errors(true);
        return $err;
    }*/
    
}

//  REDES SOCIALES 
function validaRS($field_, $idPostEdit_){
    global $validfield;
    
    $_POST = array(
        'url' => $field_, // This url obviously does not exist
        'idpost' => $idPostEdit_
    );
    $rules = array(
        'url' => 'url_exists|valid_url',
        'idpost' => 'integer'
    );
    $is_valid = $validfield->validate($_POST, $rules);
    
    return $is_valid;
}

//NUMERO TELEFONO
function validaPhoneNumber($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(   
        'fieldpost' => $field_, // This url obviously does not exist
        'idpost' => $idPostEdit_
    );
    $rules = array(
        'fieldpost' => 'phone_number', //'street_address|max_len,80',
        'idpost' => 'integer'
    );
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
}

//  DIRECCION UBICACION
function validaAddress($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(   
        'fieldpost' => $field_, // This url obviously does not exist
        'idpost' => $idPostEdit_
    );
    $rules = array(
        'fieldpost' => 'street_address|max_len,80', //'street_address|max_len,80',
        'idpost' => 'integer'
    );
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
}

// NOMBE PERSONA
function validaHumanName($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(   
        'fieldpost' => $field_, // This url obviously does not exist
        'idpost' => $idPostEdit_
    );
    $rules = array(
        'fieldpost' => 'valid_name|max_len,60', //'street_address|max_len,80',
        'idpost' => 'integer'
    );
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
}

// VALIDA NOME USUARIOS
function validaUserName($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(   
        'fieldpost' => $field_, // This url obviously does not exist
        'idpost' => $idPostEdit_
    );
    $rules = array(
        'fieldpost' => 'user|max_len,12', 
        'idpost' => 'integer'
    );
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
}

// VALIDA CONTRASEÑA
function validaPassUser($field_, $idPostEdit_){
    global $validfield;
    
    $postField = array(   
        'fieldpost' => $field_, // This url obviously does not exist
        'idpost' => $idPostEdit_
    );
    $rules = array(
        'fieldpost' => 'pass|max_len,12', 
        'idpost' => 'integer'
    );
    $resuvalid = $validfield->validate($postField, $rules);
    
    return $resuvalid;
}


//PRINT ERRORES REDES SOCIALES
function printErrValidaRS($givErrValida_, $tituERR_, $ruleValidERR_, $ruleExistERR_, $exERR_){
    
    $errValida[] = $givErrValida_;

    //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
    $errValidaTmpl ="<ul class='list-group text-left box75'>";

    foreach($errValida as $keyRules => $valRules){
        foreach($valRules as $key => $v){

            $errFiel = $v['field'];
            $errValue = $v['value'];
            $errRule = $v['rule'];
            $errParam = $v['param'];

            switch($errFiel){
                case 'url' :
                    if($errRule == "validate_valid_url"){
                        $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>".$tituERR_."</b>
                        <br>Regras:
                        <br>Você escreveu <b>".$errValue."</b>
                        <br>".$ruleValidERR_."
                        <br>Ex. ".$exERR_."</li>";
                    }else if($errRule == "validate_url_exists"){
                        $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>".$tituERR_."</b>
                        <br>Regras:
                        <br>Você escreveu <b>".$errValue."</b>
                        <br>".$ruleExistERR_."
                        <br>Ex. ".$exERR_."</li>";
                    }
                break; 
                case 'idpost':
                    $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>NOT FOUND</b>
                    <br>O post que deseja editar nao foi encontrado ou nao existe. Se o erro continuar, por favor entre en contato. Obrigado</li>";
                break; 
            }
        }
    }

    $errValidaTmpl .="</ul>";
    //$errValidaTmpl .="</section>";
    
    return $errValidaTmpl;
}

//print errores validacion
function printErrValida($givErrValida_, $tituERR_, $ruleERR_, $exERR_){
    
    $errValida[] = $givErrValida_;

    //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
    $errValidaTmpl ="<ul class='list-group text-left box75'>";

    foreach($errValida as $keyRules => $valRules){
        foreach($valRules as $key => $v){

            $errFiel = $v['field'];
            $errValue = $v['value'];
            $errRule = $v['rule'];
            $errParam = $v['param'];

            switch($errFiel){
                case 'fieldpost' :
                    $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>".$tituERR_."</b>
                    <br>Reglas:
                    <br>Escribiste <b>".$errValue."</b>
                    <br>".$ruleERR_."
                    <br>Ej. ".$exERR_."</li>";
                break; 
                case 'idpost':
                    $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>NOT FOUND</b>
                    <br>El registro que intentas encontrar no existe. Si esto continua sucediendo, por favor comunicate con soporte.</li>";
                break; 
            }
        }
    }

    $errValidaTmpl .="</ul>";
    //$errValidaTmpl .="</section>";
    
    return $errValidaTmpl;
}

//ERRORES CAMPO PARALELO

function printErrValidaSub($givErrValida_, $tituERR_, $ruleERR_, $exERR_){
    
    $errValida[] = $givErrValida_;

    //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
    $errValidaTmpl ="<ul class='list-group text-left box75'>";

    foreach($errValida as $keyRules => $valRules){
        foreach($valRules as $key => $v){

            $errFiel = $v['field'];
            $errValue = $v['value'];
            $errRule = $v['rule'];
            $errParam = $v['param'];

            switch($errFiel){
                case 'fieldpost' :
                    $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>".$tituERR_."</b>
                    <br>Regras:
                    <br>Você escreveu <b>".$errValue."</b>
                    <br>".$ruleERR_."
                    <br>Ex. ".$exERR_."</li>";
                break; 
                case 'idpost':
                    $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>NOT FOUND</b>
                    <br>O post que deseja editar nao foi encontrado ou nao existe. Se o erro continuar, por favor entre en contato. Obrigado</li>";
                break; 
            }
        }
    }

    $errValidaTmpl .="</ul>";
    //$errValidaTmpl .="</section>";
    
    return $errValidaTmpl;
}
    

////////////////////////////////
//FUNCIONES EDICION
////////////////////////////////

function editFielDB($idRow_, $fieldRow_, $fieldRowEdit_,$idFieldTbl_, $tbl_, $tituSqlERR_){
    global $db;    
    $idRow_ = $db->escape($idRow_);     
    $dataEdit = Array (
        $fieldRow_ => $fieldRowEdit_,            
    );    
    //$idRow_ = (int)$idRow_;
    $db->where ($idFieldTbl_, $idRow_ );
    if ($db->update ($tbl_, $dataEdit)){
        //echo $db->count . ' records were updated';
        //$statusEdit = "ok";            
        return true;
    }else{
        //echo 'update failed: ' . $db->getLastError();
        //$statusEdit = "fail";    

        $erroQuery = $db->getLastErrno();   
        //$errQueryTmpl ="<section class='box50 padd-verti-xs'>";
        $errQueryTmpl ="<ul class='list-group text-left box75'>";
        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>".$tituSqlERR_."</b>
            <br>Erro: ".$erroQuery."
            <br>Puedes intentar de nuevo
            <br>Si el error continua, por favor entre en contacto con soporte</li>";
        $errQueryTmpl .="</ul>";
        //$errQueryTmpl .="</section>";
        
        return $errQueryTmpl;

    }    
}


function deleteFieldDB($idRow_, $idFieldTbl_, $tbl_){
    global $db;    
    
    $idRow_ = $db->escape($idRow_);   
    
    $db->where($idFieldTbl_, $idRow_);    
    
    if($db->delete($tbl_)){
        return true;
    }else{		
        //return false;
        $erroQuery = $db->getLastErrno();   
        
        $errQueryTmpl ="<ul class='list-group text-left box75'>";
        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>Cancelar - Eliminar</b>
            <br>Erro: ".$erroQuery."
            <br>Por favor intenta de nuevo
            <br>Si el error continua, por favor comunicate con soporte. Gracias</li>";
        $errQueryTmpl .="</ul>";
        
        return $errQueryTmpl;
    }
}



///////////////////////////////
// FUNCION ELIMNAR ARCHIVO FOTO
///////////////////////////////

function rename_portada($urlPortadaProd1200,$newRutaPortada1200){
	if(!rename($urlPortadaProd1200,$newRutaPortada1200)){
		if(copy($urlPortadaProd1200,$newRutaPortada1200)){
			@unlink('$urlPortadaProd1200');
			return TRUE;
		}
	return FALSE;
	}
	return TRUE;
}

function rrmdir($path)
{
  return is_file($path)?
    @unlink($path):
    array_map('rrmdir',glob($path.'/*'))==@rmdir($path)
  ;
}

function deleteDirectory($dir){
    $result = false;
    if ($handle = opendir($dir)){
        $result = true;
        while ((($file=readdir($handle))!==false) && ($result)){
            if ($file!='.' && $file!='..'){
                if (is_dir($dir.$file)){
                    $result = deleteDirectory($dir.$file);
                } else {
                    $result = unlink($dir.$file);
                }
            }
        }
        closedir($handle);
        if ($result){
            $result = rmdir($dir);
        }
    }
    return $result;
}

function deleteFile($idRegisEli_, $fileElimi_, $tituSqlERR_){

	global $db;
    global $pathmm;
	
	$rutaFileAlbum200 = "../files-display/album/200/".$fileElimi_;	
	$rutaFileAlbum400 = "../files-display/album/400/".$fileElimi_;
	$rutaFileAlbum800 = "../files-display/album/800/".$fileElimi_;
	$rutaFileAlbum1200 = "../files-display/album/1200/".$fileElimi_;
	$newRutaPortada1200 = "../files-display/eliminadas/album/".$fileElimi_;
	       
	/*@unlink('$rutaFileAlbum200');
	@unlink('$rutaFileAlbum500');
	@unlink('$rutaFileAlbum800');*/
    //$doRutaFileAlbum1200 = rename_portada($rutaFileAlbum1200,$newRutaPortada1200);

	//$deleteSQL = "DELETE FROM fotos_albun WHERE id_foto='{$idRegisEli_}'";
	//$Result1 = $cxadmisite->query($deleteSQL); 
    if(unlink($rutaFileAlbum200) && unlink($rutaFileAlbum400) && unlink($rutaFileAlbum800) && rename_portada($rutaFileAlbum1200,$newRutaPortada1200)){
        $db->where('id_foto', $idRegisEli_);
        //$deleteFoto = $db->delete('fotos_albun');

        if($db->delete('fotos_albun')){
            return true;
        }else{		
            //return false;
            $erroQuery = $db->getLastErrno();   
            //$errQueryTmpl ="<section class='box50 padd-verti-xs'>";
            $errQueryTmpl ="<ul class='list-group text-left box75'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo saio mal ***</b><br>
                <br>Wrong: <b>".$tituSqlERR_."</b>
                <br>Erro: ".$erroQuery."
                <br>Você pode tentar novamente prencher a website do Profile
                <br>Se o erro continuar, por favor entre em contato conosco. Obrigado</li>";
            $errQueryTmpl .="</ul>";
            //$errQueryTmpl .="</section>";

            return $errQueryTmpl;
        }		
    }else{ 
        //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
        $errValidaTmpl ="<ul class='list-group text-left box75'>";
        $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>Algo saio mal</b>
        <br>No momento de apagar a foto deu um erro no servidor. Por favor tente de novo mais tarde. Ou pode entrar em contato conosco
        <br>Obrigado</li>";                   
        $errValidaTmpl .="</ul>";
        //$errValidaTmpl .="</section>";
        return $errValidaTmpl;
    }
}

function deleteAlbum($albumid_, $portadaLabel_, $tituSqlAlbumERR_){
    global $db;
    $dataFotosGuia = array();
    $rutaLabel = "../files-display/album/labels/".$portadaLabel_;
    
    //BUSCA FOTOS DE ALBUM
    ///////////////////////////////////////////
    $guiaRaca = $db->subQuery ("a");
    $guiaRaca->where ("id_albun", $albumid_);
    $guiaRaca->get("albun_db");
    
    $db->join($guiaRaca, "f.id_albun=a.id_albun");        
    $db->orderBy("f.id_foto","desc");
    //$fotosGuia = $db->get ("fotos_albun f", null, "a.nome_albun, a.portada_album, a.ref_album, f.id_foto, f.img_foto, f.descri_foto");
    $fotosGuia = $db->get ("fotos_albun f", null, "f.id_foto, f.img_foto");
    $resuFotos = count($fotosGuia);
    $totalFotos = 0;
    
    /*if ($resuFotos > 0){// || is_array($fotosGuia)
        foreach ($fotosGuia as $imgkey) { 
            //$dataFotosGuia[] = $imgkey;    
            $idRow = $imgkey['id_foto'];
            $fileRow = $imgkey['img_foto'];
            print_r($fileRow);
        }
        //return $dataFotosGuia;
    }else{
        echo "NO HAY FOTOS";
    }*/
    
    
    
    ///////////////////////////////////////////
    
    //ELIMINA FOTOS
    ///////////////////////////////////////////        
    if ($resuFotos > 0){
        foreach ($fotosGuia as $imgkey) {                 
            $idRow = $imgkey['id_foto'];
            $fileRow = $imgkey['img_foto'];
            $tituSqlERR = "ELIMINAR FOTO";

            $deleteFoto = deleteFile($idRow, $fileRow, $tituSqlERR);
            
            if(!$deleteFoto){
                $errServerFile[] = $deleteFoto;
                return $errServerFile;
            }
                                    
            $totalFotos++;
        }
        //ELIMINA ALBUM
        if($totalFotos == $resuFotos){

            if(unlink($rutaLabel)){
                $albumid_ = $db->escape($albumid_);
                $db->where('id_albun', $albumid_);        
                if($db->delete('albun_db')){                    
                    return true;
                }else{
                    //return false;
                    $erroQuery = $db->getLastErrno();   
                    //$errQueryTmpl ="<section class='box50 padd-verti-xs'>";
                    $errQueryTmpl ="<ul class='list-group text-left box75'>";
                    $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo saio mal ***</b><br>
                        <br>Wrong: <b>".$tituSqlAlbumERR_."</b>
                        <br>Erro: ".$erroQuery."
                        <br>Você pode tentar novamente eliminar o Album
                        <br>Se o erro continuar, por favor entre em contato conosco. Obrigado</li>";
                    $errQueryTmpl .="</ul>";
                    //$errQueryTmpl .="</section>";

                    return $errQueryTmpl;
                }
            }else{
                //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
                $errValidaTmpl ="<ul class='list-group text-left box75'>";
                $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>Algo saio mal</b>
                <br>No momento de apagar a portada do album deu um erro no servidor. Por favor tente de novo mais tarde. Ou pode entrar em contato conosco
                <br>Obrigado</li>";                   
                $errValidaTmpl .="</ul>";
                //$errValidaTmpl .="</section>";
                return $errValidaTmpl;            
            }
        }

    }else{
        if(unlink($rutaLabel)){
            $albumid_ = $db->escape($albumid_);
            $db->where('id_albun', $albumid_);        
            if($db->delete('albun_db')){                    
                return true;
            }else{
                //return false;
                $erroQuery = $db->getLastErrno();   
                //$errQueryTmpl ="<section class='box50 padd-verti-xs'>";
                $errQueryTmpl ="<ul class='list-group text-left box75'>";
                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo saio mal ***</b><br>
                    <br>Wrong: <b>".$tituSqlAlbumERR_."</b>
                    <br>Erro: ".$erroQuery."
                    <br>Você pode tentar novamente eliminar o Album
                    <br>Se o erro continuar, por favor entre em contato conosco. Obrigado</li>";
                $errQueryTmpl .="</ul>";
                //$errQueryTmpl .="</section>";

                return $errQueryTmpl;
            }
        }else{
            //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
            $errValidaTmpl ="<ul class='list-group text-left box75'>";
            $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>Algo saio mal</b>
            <br>No momento de apagar a portada do album deu um erro no servidor. Por favor tente de novo mais tarde. Ou pode entrar em contato conosco
            <br>Obrigado</li>";                   
            $errValidaTmpl .="</ul>";
            //$errValidaTmpl .="</section>";
            return $errValidaTmpl;            
        }
    }
    ///////////////////////////////////////////            
}


function deleteSingleFile($idTbldb_, $tbldb_, $idRegisEli_, $pathFileBig_, $pathNewPathFile_, $fileElimi_, $tituSqlERR_){

	global $db;
    global $pathmm;
	
	//$rutaFileThumb = $pathFileThumb_.$fileElimi_;	
	$rutaFileBig = $pathFileBig_.$fileElimi_;
    $newRutaFile = $pathNewPathFile_.$fileElimi_;
    
    //"../files-display/eliminadas/album/"
	    
    if(rename_portada($rutaFileBig,$newRutaFile)){
        $db->where($idTbldb_, $idRegisEli_);
        //$deleteFoto = $db->delete('fotos_albun');

        if($db->delete($tbldb_)){
            return true;
        }else{		
            //return false;
            $erroQuery = $db->getLastErrno();   
            //$errQueryTmpl ="<section class='box50 padd-verti-xs'>";
            $errQueryTmpl ="<ul class='list-group text-left box75'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo saio mal ***</b><br>
                <br>Wrong: <b>".$tituSqlERR_."</b>
                <br>Erro: ".$erroQuery."
                <br>Você pode tentar novamente eliminar o arquivo
                <br>Se o erro continuar, por favor entre em contato conosco. Obrigado</li>";
            $errQueryTmpl .="</ul>";
            //$errQueryTmpl .="</section>";

            return $errQueryTmpl;
        }		
    }else{ 
        //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
        $errValidaTmpl ="<ul class='list-group text-left box75'>";
        $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>Algo saio mal</b>
        <br>No momento de apagar a foto deu um erro no servidor. Por favor tente de novo mais tarde. Ou pode entrar em contato conosco
        <br>Obrigado</li>";                   
        $errValidaTmpl .="</ul>";
        //$errValidaTmpl .="</section>";
        return $errValidaTmpl;
    }
}


function deleteFileBIGeTHUMB($idTbldb_, $tbldb_, $idRegisEli_, $pathFileThumb_, $pathFileBig_, $pathNewPathFile_, $fileElimi_, $tituSqlERR_){

	global $db;
    global $pathmm;
	
	$rutaFileThumb = $pathFileThumb_.$fileElimi_;	
	$rutaFileBig = $pathFileBig_.$fileElimi_;
    $newRutaFile = $pathNewPathFile_.$fileElimi_;
    
    //"../files-display/eliminadas/album/"
	    
    if(unlink($rutaFileThumb) && rename_portada($rutaFileBig,$newRutaFile)){
        $db->where($idTbldb_, $idRegisEli_);
        //$deleteFoto = $db->delete('fotos_albun');

        if($db->delete($tbldb_)){
            return true;
        }else{		
            //return false;
            $erroQuery = $db->getLastErrno();   
            //$errQueryTmpl ="<section class='box50 padd-verti-xs'>";
            $errQueryTmpl ="<ul class='list-group text-left box75'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo saio mal ***</b><br>
                <br>Wrong: <b>".$tituSqlERR_."</b>
                <br>Erro: ".$erroQuery."
                <br>Você pode tentar novamente eliminar o arquivo
                <br>Se o erro continuar, por favor entre em contato conosco. Obrigado</li>";
            $errQueryTmpl .="</ul>";
            //$errQueryTmpl .="</section>";

            return $errQueryTmpl;
        }		
    }else{ 
        //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
        $errValidaTmpl ="<ul class='list-group text-left box75'>";
        $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>Algo saio mal</b>
        <br>No momento de apagar a foto deu um erro no servidor. Por favor tente de novo mais tarde. Ou pode entrar em contato conosco
        <br>Obrigado</li>";                   
        $errValidaTmpl .="</ul>";
        //$errValidaTmpl .="</section>";
        return $errValidaTmpl;
    }
}


//deleteBanner($pathFileThumb, $pathFileBig, $pathNewPathFile, $fileRow, $tituSqlERR)

function deleteBanner($pathFileThumb_, $pathFileBig_, $pathNewPathFile_, $fileElimi_, $tituSqlERR_){

	global $db;
    global $pathmm;
	
	$rutaFileThumb = $pathFileThumb_.$fileElimi_;	
	$rutaFileBig = $pathFileBig_.$fileElimi_;
    $newRutaFile = $pathNewPathFile_.$fileElimi_;
    
    //"../files-display/eliminadas/album/"
	    
    if(unlink($rutaFileThumb) && rename_portada($rutaFileBig,$newRutaFile)){
        
        return true;
        
        /*$db->where($idTbldb_, $idRegisEli_);
        //$deleteFoto = $db->delete('fotos_albun');

        if($db->delete($tbldb_)){
            return true;
        }else{		
            //return false;
            $erroQuery = $db->getLastErrno();   
            $errQueryTmpl ="<section class='box50 padd-verti-xs'>";
            $errQueryTmpl .="<ul class='list-group text-left'>";
            $errQueryTmpl .="<li class='list-group-item list-group-item-danger'><b>*** Algo saio mal ***</b><br>
                <br>Wrong: <b>".$tituSqlERR_."</b>
                <br>Erro: ".$erroQuery."
                <br>Você pode tentar novamente eliminar o arquivo
                <br>Se o erro continuar, por favor entre em contato conosco. Obrigado</li>";
            $errQueryTmpl .="</ul>";
            $errQueryTmpl .="</section>";

            return $errQueryTmpl;
        }*/		
    }else{ 
        //$errValidaTmpl="<section class='box50 padd-verti-xs'>";
        $errValidaTmpl ="<ul class='list-group text-left box75'>";
        $errValidaTmpl .="<li class='list-group-item list-group-item-danger'><b>Algo saio mal</b>
        <br>No momento de apagar a foto deu um erro no servidor. Por favor tente de novo mais tarde. Ou pode entrar em contato conosco
        <br>Obrigado</li>";                   
        $errValidaTmpl .="</ul>";
        //$errValidaTmpl .="</section>";
        return $errValidaTmpl;
    }
}
    
////////////////////////////////
//OPCIONES DE EDICION PERFIL
////////////////////////////////

//STATUS
$statusEdit = "";
//TEMPLATES ERRORES
$errQueryTmplEdit="";
$errValidaTmplEdit = "";
$errValidaTmplEditSub = "";