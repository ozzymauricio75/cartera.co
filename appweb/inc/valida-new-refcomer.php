<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once 'sessionvars.php';
require_once 'query-custom-user.php';
require_once "../lib/gump.class.php";
require_once "site-tools.php"; 


$fieldPost = $_POST['fieldedit'];
$response = array();
$fileValida = "";

//if(isset($_POST['newstore']) && $_POST['newstore'] == "ok"){
if(isset($fieldPost) && $fieldPost == "additem"){    
            
    //***********
    //RECIBE DATOS 
    //***********
                
    /*
    **INFO PERSONAL
    */
    $id_acreedor = (empty($_POST['codeuserform']))? "" : $_POST['codeuserform'];
    //$id_refcomer = (empty($_POST['codeitemform']))? "" : $_POST['codeitemform'];
    $id_ref_deudor = (empty($_POST['codeitemform']))? "" : $_POST['codeitemform'];
    $id_credito = (empty($_POST['creditoform']))? "" : $_POST['creditoform'];
    
    $Primer_Nombre = (empty($_POST['nombre1form']))? "" : $_POST['nombre1form'];   
    $Segundo_Nombre = (empty($_POST['nombre2form']))? "" : $_POST['nombre2form'];
    $Primer_Apellido = (empty($_POST['apellido1form']))? "" : $_POST['apellido1form'];
    $Segundo_Apellido = (empty($_POST['apellido2form']))? "" : $_POST['apellido2form'];
    $Numero_Documento = (empty($_POST['cedulaform']))? "" : $_POST['cedulaform'];
    $Fecha_Nacimiento = (empty($_POST['Nacimientoform']))? "" : date("Y-m-d", strtotime(str_replace('/', '-', $_POST['Nacimientoform']))); ;
    $Lugar_Nacimiento = (empty($_POST['LugarNacimientoform']))? "" : $_POST['LugarNacimientoform'];
   
    
    /*
    **ESCOLARIDAD
    */   
    $Profesion = (empty($_POST['Profesionform']))? "" : $_POST['Profesionform'];
    $Oficio = (empty($_POST['Oficioform']))? "" : $_POST['Oficioform'];
    
    /*
    **INFORMACION LABORAL
    */
    $nit_empresa = (empty($_POST['nitform']))? "" : $_POST['nitform'];
    $nombre_contacto = (empty($_POST['contatoform']))? "" : $_POST['contatoform'];
    $Nombre_Empresa = (empty($_POST['NombreEmpresaform']))? "" : $_POST['NombreEmpresaform'];
    $Cargo = (empty($_POST['Cargoform']))? "" : $_POST['Cargoform'];
    $Telefono = (empty($_POST['Telefonoform']))? "" : $_POST['Telefonoform'];
    $Direccion = (empty($_POST['Direccionform']))? "" : $_POST['Direccionform'];
    $Ciudad_Empresa = (empty($_POST['CiudadEmpresaform']))? "" : $_POST['CiudadEmpresaform'];
    
    /*
    **INFORMACION CONTACTO  
    */ 
    $Email = (empty($_POST['Emailform']))? "" : $_POST['Emailform'];
    $Telefono1 = (empty($_POST['Telefono1form']))? "" : $_POST['Telefono1form'];
    $Telefono2 = (empty($_POST['Telefono2form']))? "" : $_POST['Telefono2form'];
    $Direccion_Residencia = (empty($_POST['DireccionResidenciaform']))? "" : $_POST['DireccionResidenciaform'];
    $Complemento = (empty($_POST['Complementoform']))? "" : $_POST['Complementoform'];
    $Barrio = (empty($_POST['Barrioform']))? "" : $_POST['Barrioform'];    
    $Estrato = (empty($_POST['Estratoform']))? "" : $_POST['Estratoform'];
    
    $Comentarios = (empty($_POST['comentariosform']))? "" : $_POST['comentariosform'];
    
    
    /*
    **SELCTS - CHECKBOX - OPTIONGROUP
    */

    //GENERO    
    //$kitRopaUserJson = $_POST['Generoform'];
    //$kitRopaUser = json_decode($kitRopaUserJson, true);
    $Genero = (empty($_POST['Generoform']))? "" : $_POST['Generoform'];
        
    //ESTADO CIVIL
    $Estado_Civil = (empty($_POST['EstadoCivilform']))? "" : $_POST['EstadoCivilform'];
    
    //ESCOLARIDAD
    $escolaridad = (empty($_POST['escolaridadform']))? "" : $_POST['escolaridadform'];
    
    //TIPO DE VIVIENDA
    $Tipo_Vivienda = (empty($_POST['TipoViviendaform']))? "" : $_POST['TipoViviendaform'];
    
    
    //TIPO DE IDENTIDAD PARA REFERENCIA COMERCIAL
    $opciidentiform =(empty($_POST['opciidentiform']))? "" : $_POST['opciidentiform']; 
    
    //DOCUMENTOS DEUDOR
    //$Documentos = (empty($_POST['Documentosform']))? "" : $_POST['Documentosform'];
    //$DocumentosJsonDecode = json_decode($Documentos, true);
    
    //STATUS ITEM
    //$statusItem = (empty($_POST['statusitem']))? "" : $_POST['statusitem'];
    
    /*
    **DOCUMENTOS IMAGENES
    */
    $fotosAlbum = empty($_FILES['multifileimg'])? "" : $_FILES['multifileimg'];
        
    
    /*
    **GEOPOSICIONAMIENTO
    */                         
    /*$direccionFullPost = empty($_POST['txtEndereco']) ? "" : $_POST['txtEndereco'];
    $paisCodPost = empty($_POST['countrycod']) ? "" : $_POST['countrycod'];
    $paisPost = empty($_POST['country']) ? "" : $_POST['country'];
    $idMapPost = empty($_POST['idplacegeomap']) ? "" : $_POST['idplacegeomap'];
    $usercity1 = empty($_POST['usercity1']) ? "" : $_POST['usercity1'];
    $usercity2 = empty($_POST['usercity2']) ? "" : $_POST['usercity2'];
    $usercity3 = empty($_POST['usercity3']) ? "" : $_POST['usercity3'];
    $userstate = empty($_POST['userstate']) ? "" : $_POST['userstate'];
    $userstateshort = empty($_POST['userstateshort']) ? "" : $_POST['userstateshort'];
    $userzip = empty($_POST['userzip']) ? "" : $_POST['userzip'];
    $latstore = empty($_POST['txtLatitude']) ? "" : $_POST['txtLatitude'];
    $lngstore = empty($_POST['txtLongitude']) ? "" : $_POST['txtLongitude'];
    
    $checkAdressPost = "";
    if($direccionFullPost == ""){
        $checkAdressPost = "error";
    }*/
    
 
    $_POST = array( 
        //'idrefcomer'=> $id_refcomer,
        'primernombre' => $Primer_Nombre,        
        'segundonombre'=>$Segundo_Nombre ,
        'PrimerApellido' => $Primer_Apellido,
        'segundoapellido'=>$Segundo_Apellido,
        'NumeroDocumento'=> $Numero_Documento, 
        'Fecha_Nacimiento'=>$Fecha_Nacimiento,
        'Lugar_Nacimiento'=>$Lugar_Nacimiento,
        'Genero'=>$Genero,  
        'Estado_Civil'=>$Estado_Civil,
        'escolaridad' => $escolaridad,
        'Profesion'=>$Profesion, 
        'Oficio'=>$Oficio,  
        'nitempresa' => $nit_empresa,
        'Nombre_Empresa'=>$Nombre_Empresa,
        'Nombre_contacto'=> $nombre_contacto,
        'Cargo'=>$Cargo ,
        'Telefono'=>$Telefono, 
        'Direccion'=>$Direccion,
        'Ciudad_Empresa'=>$Ciudad_Empresa, 
        'Email'=>$Email ,
        'Telefono1'=>$Telefono1, 
        'Telefono2'=>$Telefono2,
        'Direccion_Residencia'=>$Direccion_Residencia,
        'Complemento'=>$Complemento,
        'Barrio'=>$Barrio,
        'Tipo_Vivienda'=>$Tipo_Vivienda, 
        'Estrato' =>$Estrato,        
        'comentarios'=> $Comentarios, 
        //'statusitem'=> $statusItem 
    );
    
    //DEFINO VAIDACICIONES SEGUN TIPO DE IDENTIDAD REFERENCIA COMERCIAL
    $rules = array();
    $check_Nit = "";
    $check_Cedula = "";
    $checkTels = ""; 
    
    if($opciidentiform == "cedula"){
        if($Telefono1 == "" && $Telefono2 ==""){
            $checkTels = "error";
        }
        if($Numero_Documento ==""){
            $check_Cedula = "error";  
        }
        
        $rules = array(
            //'idrefcomer'=> 'required|integer',        
            'primernombre'=> 'required|valid_name|max_len,25',
            'segundonombre'=> 'valid_name|max_len,25',
            'PrimerApellido'=> 'required|valid_name|max_len,25',
            'segundoapellido'=> 'valid_name|max_len,25',
            'NumeroDocumento' => 'required|numeric|max_len,12',
            'Fecha_Nacimiento'=>'date',
            'Lugar_Nacimiento'=>'alpha_space|max_len,100',
            'Genero'=>'integer',  
            'Estado_Civil'=>'integer',
            'escolaridad' => 'integer',
            'Profesion'=>'alpha_space|max_len,50', 
            'Oficio'=>'alpha_space|max_len,50',      
            'nitempresa'=>'alpha_space|max_len,13',      
            'Nombre_Empresa'=>'alpha_space|max_len,60',
            'Nombre_contacto'=>'alpha_space|max_len,50',
            'Cargo'=>'alpha_space|max_len,50',
            //'Telefono'=>'phone_number|max_len,15',
            'Telefono'=>'alpha_space|max_len,13',
            //'Direccion'=>'street_address|max_len,80' ,
            'Direccion'=>'alpha_space|max_len,80' ,
            'Ciudad_Empresa'=>'alpha_space|max_len,100', 
            'Email'=>'valid_email' ,
            //'Telefono1'=>'phone_number|max_len,15', 
            //'Telefono2'=>'phone_number|max_len,12',      
            'Telefono1'=>'alpha_space|max_len,13', 
            'Telefono2'=>'alpha_space|max_len,12',     //'Direccion_Residencia'=>'required|street_address|max_len,80',
            'Direccion_Residencia'=>'alpha_space|max_len,80',
            'Complemento'=>'alpha_space|max_len,80',
            'Barrio'=>'alpha_space|max_len,50',
            'Tipo_Vivienda'=>'integer', 
            'Estrato' =>'integer',        
            'comentarios'=> 'alpha_space|max_len,200',        
        );
        
    }else if($opciidentiform == "nit"){
        
        if($nit_empresa == ""){
            $check_Nit = "error";
        }
        
        $rules = array(
            //'idrefcomer'=> 'required|integer',        
            'primernombre'=> 'valid_name|max_len,25',
            'segundonombre'=> 'valid_name|max_len,25',
            'PrimerApellido'=> 'valid_name|max_len,25',
            'segundoapellido'=> 'valid_name|max_len,25',
            'NumeroDocumento' => 'numeric|max_len,12',
            'Fecha_Nacimiento'=>'date',
            'Lugar_Nacimiento'=>'alpha_space|max_len,100',
            'Genero'=>'integer',  
            'Estado_Civil'=>'integer',
            'escolaridad' => 'integer',
            'Profesion'=>'alpha_space|max_len,50', 
            'Oficio'=>'alpha_space|max_len,50',       
            'nitempresa'=>'required|alpha_space|max_len,13',      
            'Nombre_Empresa'=>'required|alpha_space|max_len,60',
            'Nombre_contacto'=>'required|alpha_space|max_len,50',
            'Cargo'=>'alpha_space|max_len,50',
            //'Telefono'=>'phone_number|max_len,15',
            'Telefono'=>'required|alpha_space|max_len,13',
            //'Direccion'=>'street_address|max_len,80' ,
            'Direccion'=>'alpha_space|max_len,80' ,
            'Ciudad_Empresa'=>'alpha_space|max_len,100', 
            'Email'=>'valid_email' ,
            //'Telefono1'=>'phone_number|max_len,15', 
            //'Telefono2'=>'phone_number|max_len,12',      
            'Telefono1'=>'alpha_space|max_len,13', 
            'Telefono2'=>'alpha_space|max_len,12',     //'Direccion_Residencia'=>'required|street_address|max_len,80',
            'Direccion_Residencia'=>'alpha_space|max_len,80',
            'Complemento'=>'alpha_space|max_len,80',
            'Barrio'=>'alpha_space|max_len,50',
            'Tipo_Vivienda'=>'integer', 
            'Estrato' =>'integer',        
            'comentarios'=> 'alpha_space|max_len,200',        
        );
        
        
    }
    
	    
    
    $filters = array(
        //'idrefcomer'=> 'trim|sanitize_string',
        'primernombre'=> 'trim|sanitize_string',
        'segundonombre'=> 'trim|sanitize_string',
        'PrimerApellido'=> 'trim|sanitize_string',
        'segundoapellido'=> 'trim|sanitize_string',
        'NumeroDocumento' => 'trim|sanitize_numbers',
        'Fecha_Nacimiento'=>'trim|sanitize_string',
        'Lugar_Nacimiento'=>'trim|sanitize_string',
        'Genero'=>'trim|sanitize_string',  
        'Estado_Civil'=>'trim|sanitize_string',
        'escolaridad' => 'trim|sanitize_string', 
        'Profesion'=>'trim|sanitize_string', 
        'Oficio'=>'trim|sanitize_string',
        'urlmapdeu'=>'trim|sanitize_string', 
        'nitempresa'=>'trim|sanitize_string', 
        'Nombre_Empresa'=>'trim|sanitize_string', 
        'Nombre_contacto'=>'trim|sanitize_string', 
        'Cargo'=>'trim|sanitize_string',
        'Telefono'=>'trim|sanitize_string', 
        'Direccion'=>'trim|sanitize_string' ,
        'Ciudad_Empresa'=>'trim|sanitize_string', 
        'Email'=>'trim|sanitize_email',
        'Telefono1'=>'trim|sanitize_string', 
        'Telefono2'=>'trim|sanitize_string' ,
        'Direccion_Residencia'=>'trim|sanitize_string',
        'Complemento'=>'trim|sanitize_string',
        'Barrio'=>'trim|sanitize_string',
        'Tipo_Vivienda'=>'trim|sanitize_string', 
        'Estrato' =>'trim|sanitize_string',        
        'comentarios'=> 'trim|htmlencode',
        //'statusitem'=>'trim|sanitize_string'
	);
	
    $_POST = $validfield->sanitize($_POST); 
    $validated = $validfield->validate($_POST, $rules);
    $_POST = $validfield->filter($_POST, $filters);
    
    //echo "<pre>";
    ///print_r($validated);
    // Check if validation was successful
            
    
	if($validated === TRUE && $checkTels != "error" && $check_Cedula != "error" &&  $check_Nit != "error"){
        
        /*//***********
        //EXISTE EMAIL REGISTRADO?
        //*************
        */
        $db->where('email_referencia_comercial', $Email);
        $db->where('email_referencia_comercial', "", "!=");
        $emailExist = $db->getOne('referencia_comercial_tbl');
        
        if(count($emailExist)>0){ 
            $errEmailTxt = "Ya existe un usuario registrado con esta cuenta de email <b>".$Email."</b><br>Por favor verifiquelo e intentelo de nuevo";
            
            $errQueryTmpl_email ="<ul class='list-group text-left'>";
            $errQueryTmpl_email .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>Email ya existe</b>
                <br>Erro: ".$errEmailTxt."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl_email .="</ul>";
            
            $response['error'] = $errQueryTmpl_email;
            
            echo json_encode($response);
            
            //$response['error']= "Ya existe un usuario registrado con este número de cedula <b>".$Numero_Documento."</b><br>Por favor verifiquelo e intentelo de nuevo";
            return;
        }
        /*//***********
        //EXISTE DEUDOR REGISTRADO?
        //***********
        $tablaExiQ = "referencia_comercial_tbl";
        $campoExiQ = "cedula_referencia_comercial";
        $colValExiQ = $Numero_Documento;
        $deudorExiste = existPost($tablaExiQ, $campoExiQ, $colValExiQ);

        if($deudorExiste == false){//SI EXISTE EL REGISTRO
            $response['error']= "Ya existe un usuario registrado con este número de cedula <b>".$Numero_Documento."</b><br>Por favor verifiquelo e intentelo de nuevo";
            
            echo json_encode($response);
            
            //$response['error']= "Ya existe un usuario registrado con este número de cedula <b>".$Numero_Documento."</b><br>Por favor verifiquelo e intentelo de nuevo";
            return;
            
        }else{//SI NO EXISTE EL REGISTRO*/
            
            //***********
            //CALIDACION Y EXISTENCIA DE CIUDAD
            //***********
            
            /*$selectCity = array();
    
            $selectCity[] = $usercity1;
            $selectCity[] = $usercity2;
            $selectCity[] = $usercity3;
            $countSelectCity = count($selectCity);
            
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
            }*/
            
            //***********
            //PROCESO NUEVO REGISTRO (DEUDOR)
            //***********
                                        
            /*!!!
            OBSERVACION
            guardar en un array todas los datos que ya pasaron el proceso de validacion y seran insertados en la base de datos       
            !!!*/
            
            $nuevoPost = array();
            $nuevoPost = $_POST;
            //foreach($nuevoPost as $valInsert => $valPost){
            foreach($nuevoPost as $valInsert){
                $dataInsert = array(           
                    'id_deudor' => $id_ref_deudor,
                    'id_status_perfil_referencia_comercial'=> 1,
                    'primer_nombre_referencia_comercial' => $nuevoPost['primernombre'],
                    'segundo_nombre_referencia_comercial' =>$nuevoPost['segundonombre'],
                    'primer_apellido_referencia_comercial'=>  $nuevoPost['PrimerApellido'],    
                    'segundo_apellido_referencia_comercial'=> $nuevoPost['segundoapellido'],
                    'cedula_referencia_comercial' =>$nuevoPost['NumeroDocumento'],
                    'fecha_nacimiento_referencia_comercial'=> $nuevoPost['Fecha_Nacimiento'],  
                    'lugar_naciminto_referencia_comercial'=> $nuevoPost['Lugar_Nacimiento'], 
                    'genero_referencia_comercial'=> $nuevoPost['Genero'],  
                    'estado_civil_referencia_comercial'=> $nuevoPost['Estado_Civil'],  
                    'email_referencia_comercial' =>  $nuevoPost['Email'],
                    'tel_uno_referencia_comercial' => $nuevoPost['Telefono1'],
                    'tel_dos_referencia_comercial' =>$nuevoPost['Telefono2'],
                    'dir_domicilio_referencia_comercial'=>  $nuevoPost['Direccion_Residencia'],
                    'complemento_dir_referencia_comercial'=> $nuevoPost['Complemento'],
                    'barrio_domicilio_referencia_comercial' =>$nuevoPost['Barrio'],
                    'tipo_vivienda_referencia_comercial'=> $nuevoPost['Tipo_Vivienda'],       
                    'estrato_social_referencia_comercial'=> $nuevoPost['Estrato'],            
                    //'dir_geo_referencia_comercial' => $direccionFullPost,
                    //'latitud_geo_referencia_comercial' => $latstore,
                    //'longitud_geo_referencia_comercial'=>$lngstore,
                    //'url_maps_referencia_comercial'=> $idMapPost,
                    //'codigo_geo_ciudad_referencia_comercial' => $usercityUser,
                    //'codigo_geo_estado_referencia_comercial' => $userstate,
                    //'codigo_geo_pais_referencia_comercial'=> $paisPost,
                    //'codigo_postal_geo_referencia_comercial' =>$userzip,                    
                    'nivel_escolaridad_referencia_comercial' => $nuevoPost['escolaridad'],    
                    'oficio_referencia_comercial' => $nuevoPost['Oficio'],
                    'profesion_referencia_comercial' =>$nuevoPost['Profesion'],
                    'nombre_empresa_referencia_comercial'=>$nuevoPost['Nombre_Empresa'],
                    'nit_referencia_comercial' =>$nuevoPost['nitempresa'],
                    'nombre_contato_referencia_comercial'=>$nuevoPost['Nombre_contacto'],
                    'cargo_empresa_referencia_comercial'=>$nuevoPost['Cargo'],
                    'tel_empresa_referencia_comercial'=>$nuevoPost['Telefono'],
                    'dir_empresa_referencia_comercial'=>$nuevoPost['Direccion'],
                    'ciudad_empresa_referencia_comercial'=>$nuevoPost['Ciudad_Empresa'],      
                    'comentarios_referencia_comercial'=>$nuevoPost['comentarios'],
                    'fecha_alta_referencia_comercial'=> $dateFormatDB
                );  
            }
            //echo "<pre>";
            ///print_r($dataPackDotacion);
            //$idStore_order = $db->insert('account_empresa', $dataInsert);
            //if($idStore_order == true){ 
            //$id= $db -> insert('deudor_tbl, $dataInsert');
            //if (!$id){
            //$db->where ('id_referencia_comercial',$id_refcomer); 
            //if ($db->update ('referencia_comercial_tbl', $dataInsert)){  
            $newRefComerINS = $db->insert ('referencia_comercial_tbl', $dataInsert);
            if ($newRefComerINS){  
                //***********
                //DATOS REF
                //***********


                //LAST ITEM DOCUMENTOS
                /*$tablaDocumentoQ = "documentos_requisitos_tbl";
                $campoDocumentoQ = "cedula_deudor";    
                $lastItemDocumentoDB = lastIDRegis($tablaDocumentoQ, $campoDocumentoQ);
                $lastItemDocumentoDB = $lastItemDocumentoDB + 1; */   

                $refFull = $Primer_Nombre.$Primer_Apellido.$Numero_Documento;
                $refAlbum_dash =  format_uri($refFull);
                $nameFotoPortada = $refAlbum_dash;

                //***********
                //FILES IMG
                //***********


                if(!empty($fotosAlbum)){

                    //recibe datas post file
                    //$filePost = $fotosAlbum; 

                    //""""""devuelve un array file organizado
                    $img_desc = reArrayFiles($fotosAlbum); 

                    $filteredArr = $img_desc;


                    //"""""Elimina arrays vacios
                    foreach($filteredArr as $key => $link){ 
                        if($link === '' || $link === 0 || $link['error'] == 4){ 
                            unset($filteredArr[$key]); 
                        } 
                    } 

                    //ELMINAR FILES REPETIDOS QUE LLEGA DESDE EL AJAX
                    //foreach($img_desc as $imgKey){
                        $filteredArr = unique_multidim_array($filteredArr, 'name');
                    //}

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

                    //$response = $filesUpload;
                    //print_r($filesUpload);

                    //SI TODO SALIO BN CON LA VALIDACION           
                    if($fileValida != "error"){


                        $countFotos = 0;//count($filesUpload);
                        //$itemPortada = 0;

                        foreach($filesUpload as $valF){

                            if(upimgfile($valF['name'], $valF['tmp_name'])){

                                //img full  
                                $patFileEnd = "documentos/1200/"; //define el directorio final de la imagen    
                                $squareFile = "1200";  //define dimensiones de imagen

                                //img COPIAS REDIMENSIONADAS                         
                                $patFileEndThumb = "documentos/200/"; //define el directorio final de la imagen   
                                $squareFileThumb = "200";  //define dimensiones de imagen


                                $refAlbumInsert = $refAlbum_dash;  
                                //$urldb = $valF['urlinst'];
                                $nameFileSponsorF = date('YmdHis',time()).mt_rand(); 
                                $nameFileSponsorFArr[] = $nameFileSponsorF;  

                                if(redimensionaImgFile($valF['name'], $valF['type'], $nameFileSponsorF, $patFileEnd, $squareFile)){ 

                                    //crea thumb
                                    if(redimensionaImgFile($valF['name'], $valF['type'], $nameFileSponsorF, $patFileEndThumb, $squareFileThumb)){

                                        $idSponsor = $db->rawQuery("INSERT INTO documentos_requisitos_tbl (id_acreedor, id_referencia_comercial, referencia_documento_requisito, file_documento_requisito) VALUES('".$id_reffami."', '".$newRefComerINS."','".$refAlbumInsert."',  '".$nameFileSponsorF.".jpg')");
                                        if(!isset($idSponsor)){ 
                                            $response['error'] = "Ocurrio un error, en el momento de guardar el documento:\n Error:". $db->getLastError();  
                                        }else{
                                            $statusEdit = "ok";       
                                        }    
                                    }else{
                                        $errCopySponsor[] =array($valF['name']);

                                        $errQueryTmpl_Foto ="<ul class='list-group text-left'>";
                                        $errQueryTmpl_Foto .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                                            <br>Wrong: <b>No se pudo crear la miniatura del documento</b>
                                            <br>Erro: ".$errCopySponsor."
                                            <br>Puedes intentar de nuevo
                                            <br>Si el error continua, por favor entre en contacto con soporte</li>";
                                        $errQueryTmpl_Foto .="</ul>";

                                        $response['error']=$errQueryTmpl_Foto;
                                    }                        
                                }else{
                                    $errCopySponsor[] =array($valF['name']);

                                    $errQueryTmpl_Foto ="<ul class='list-group text-left'>";
                                    $errQueryTmpl_Foto .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                                        <br>Wrong: <b>No se pudo publicar el documento</b>
                                        <br>Erro: ".$errCopySponsor."
                                        <br>Puedes intentar de nuevo
                                        <br>Si el error continua, por favor entre en contacto con soporte</li>";
                                    $errQueryTmpl_Foto .="</ul>";

                                    $response['error']=$errQueryTmpl_Foto;
                                }

                            //$response = "Subio correctamente";

                            }else{//si upload fue positivo
                                $errUpLoad = 1; 
                                $response['error']= "Fallo subir las imágenes al servidor";
                            }//si upload fue negativo
                        }//fin foreach -> INSERTA FOTOS

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

                        $response['error']= $erroFileULLayout;            
                    }

                }//fin $_FILES[]
                
                //ACTUALIZA CREDITO CON AL NUEVA REFRENCIA
                $dataUpdateCredito = array("id_referencia_comercial"=>$newRefComerINS);
                $db->where("id_creditos", $id_credito);
                $actualizaCredito = $db->update("creditos_tbl", $dataUpdateCredito);
                
                if($actualizaCredito){
                    $response=$id_credito;     
                }else{
                    $errInsertDatas = $db->getLastErrno();
                    $errQueryTmpl_ins ="<ul class='list-group text-left'>";
                    $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                        <br>Wrong: <b>No se pudo crear esta referencia personal</b>
                        <br>Erro: ".$errInsertDatas."
                        <br>Puedes intentar de nuevo
                        <br>Si el error continua, por favor entre en contacto con soporte</li>";
                    $errQueryTmpl_ins .="</ul>";

                    $response['error']= $db->escape($errQueryTmpl_ins);  
                }
                
                //$response=true;

            }else{
                
                //$response['error'] = "Error al insertar el deudor: ".$db->getLastQuery() ."\n". $db->getLastError();
                $errInsertDatas = $db->getLastErrno();

                $errQueryTmpl_ins ="<ul class='list-group text-left'>";
                $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No se pudo crear esta referencia comercial</b>
                    <br>Erro: ".$errInsertDatas."
                    <br>Puedes intentar de nuevo
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";
                $errQueryTmpl_ins .="</ul>";


                $response['error']= $errQueryTmpl_ins;

            }
            
        //}//FIN VERIFICA SI REGISTRO EXISTE
                
    }else{
        
        $errValidaTmpl = "";
                
        $errValidaTmpl .= "<ul class='list-group text-left box75'>";
                                           
        //ERRORES VALIDACION DATOS
        $recibeRules = array();
        $recibeRules[] = $validated;
                                
        foreach($recibeRules as $keyRules => $valRules){
            foreach($valRules as $key => $v){
                                
                $errFiel = $v['field'];
                $errValue = $v['value'];
                $errRule = $v['rule'];
                $errParam = $v['param'];
                
                if(empty($errValue)){
                    $usertyped = "Por favor completa este campo";                    
                }else{
                    $usertyped = "Escribiste&nbsp;&nbsp;<b>" .$errValue ."</b>";
                }
                
                switch($errFiel){
                    /*case 'idrefcomer' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Ref. comercial</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error en el momento de crear este registro, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;*/                        
                    case 'primernombre' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Primer nombre</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un nombre de persona valido
                        <br>Max. 15 carácteres</li>";
                    break;                        
                    case 'segundonombre' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Segundo nombre</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Escribe un nombre de persona valido
                        <br>Max. 15 carácteres</li>";
                    break;                        
                    case 'PrimerApellido' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Primer apellido</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un apellido de persona valido
                        <br>Max. 15 carácteres</li>";
                    break;
                    case 'segundoapellido' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Segundo apellido</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Escribe un apellido de persona valido
                        <br>Max. 15 carácteres</li>";
                    break;
                    case 'NumeroDocumento' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>No. Cedula</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatorio
                        <br>Debes escribir un número de cedula valido
                        <br>No uses puntos, simbolos o espacios</li>";
                    break;
                    case 'Fecha_Nacimiento' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Fecha nacimiento</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatorio
                        <br>Escribe la fecha de nacimiento
                        <br>Formato: dd/mm/yyyy</li>";
                    break;
                    case 'Lugar_Nacimiento' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Lugar nacimiento</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Escribe una ciudad o municipio de nacimiento valido</li>";
                    break;
                    case 'Genero' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Genero</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Campo obligartorio
                        <br>Selecciona Masculino o Femenino</li>";
                    break;
                    case 'Estado_Civil' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Estado Civil</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Campo obligartorio
                        <br>Selecciona una de las opciones del menu select</li>";
                    break;
                    case 'escolaridad' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Escolaridad</b>
                        <br>".$usertyped."
                        <br>Reglas:           
                        <br>Campo obligartorio
                        <br>Selecciona una de las opciones del menu select</li>";
                    break;
                    case 'Profesion' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Profesion</b>
                        <br>".$usertyped."
                        <br>Reglas:                                   
                        <br>Escribe una profesion valida, puedes usar letras y números</li>";
                    break;    
                    case 'Oficio' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Profesion</b>
                        <br>".$usertyped."
                        <br>Reglas:                                   
                        <br>Escribe un oficio valido, puedes usar letras y números</li>";
                    break;    
                        
                    case 'Nombre_Empresa' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Nombre empresa</b>
                        <br>".$usertyped."
                        <br>Reglas: 
                        <br>Campo obligatrorio
                        <br>Escribe un nombre de empresa valido, puedes usar letras y números</li>";
                    break;
                        
                    case 'nitempresa' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>NIT</b>
                        <br>".$usertyped."
                        <br>Reglas: 
                        <br>Campo obligatrorio
                        <br>Escribe un NIT valido, puedes usa números, punto (.) y el guion (-)
                        <br>Ej: ###.###.###-#</li>";
                    break;
                        
                    case 'Nombre_contacto' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Nombre contacto</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un nombre de persona valido
                        <br>Max. 50 carácteres</li>";
                    break; 
                    
                    case 'Cargo' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Cargo</b>
                        <br>".$usertyped."
                        <br>Reglas:                                   
                        <br>Escribe el cargo del deudor en la empresa que trabaja, puedes usar letras y números</li>";
                    break;                        
                    case 'Telefono' :
                        $errValidaTmpl .=  "<li class='list-group-item list-group-item-danger'><b>Teléfono de la empresa</b>
                        <br>".$usertyped."
                        <br>Reglas:    
                        <br>Campo obligatrorio
                        <br>Escribe un número de teléfono fijo
                        <br>Maximo 15 carácters
                        <br>Ej. (5) 555 5555</li>";                       
                    break;
                    case 'Direccion' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Dirección empresa</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Escribe una dirección
                        <br>Ej: Calle 55 # 55-55 Barrio</li>";
                    break;
                    case 'Ciudad_Empresa' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Ciudad empresa</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Escribe un nombre de ciudad valido
                        <br>Max. 15 carácteres</li>";
                    break;
                    case 'Email' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Email deudor</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Escribe una cuenta de email valida
                        <br>Ej. nombredeusuario@sitioweb.com</li>";
                    break; 
                    case 'Telefono1' :
                        $errValidaTmpl .=  "<li class='list-group-item list-group-item-danger'><b>Teléfono 1 deudor</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Max. 15 carácters
                        <br>Escrebe un número de telefono fijo valido
                        <br>Ej. (5) 555 5555</li>";                       
                    break;
                    case 'Telefono2' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Otro Teléfono</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Max. 12 carácters
                        <br>Escrebe un número de telefono fijo valido
                        <br>Ej. 315 555 5555</li>";
                    break;                    
                    case 'Direccion_Residencia' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Drección domicilio</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatorio
                        <br>Escribe una dirección
                        <br>Ej: Calle 55 # 55-55 Barrio</li>";
                        
                    break;                     
                    case 'Complemento' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Complemento</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Puedes especificar el nombre del edificio, unidad, número de apto
                        <br>Puedes usar letras y números</li>";
                        
                    break;                     
                    case 'Barrio' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Barrio Deudor</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>En que barrio se encuentra el deudor
                        <br>Puedes usar letras y números</li>";
                    break;
                    case 'Tipo_Vivienda' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Tipo de vivienda</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatorio
                        <br>Selecciona una de las opciones del menu</li>";
                    break;
                    case 'comentarios' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Tipo de vivienda</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Escribe una pequeña reseña sobre este deudor
                        <br>Max. 200 carácteres
                        <br>Letras, números, signos de puntuación son permitidos</li>";
                    break;
                  
                }
            }
            
        }
        
        /*switch($check_Cedula){ 
            case 'error' :
                $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Cedula</b>                
                <br>Escribe un número de cedula</li>";
            break;
        }
        
        switch($check_Nit){ 
            case 'error' :
                $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>NIT</b>                
                <br>Escribe un NIT</li>";
            break;
        }*/
        
        switch($checkTels){  
            case 'error' :
                $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Número de teléfono</b>                
                <br>Debes escribir al menos un número de teléfono</li>";
            break;
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    echo json_encode($response);
    
}


/*
*ADICIONAR REFERENCIA PERSONAL EXISTENTE A CREIDTO
*/
if(isset($fieldPost) && $fieldPost == "addrefcredito"){    
            
    //***********
    //RECIBE DATOS 
    //***********
    
    $id_ref_credito = (empty($_POST['itemcodeudor']))? "" : $_POST['itemcodeudor'];
    $id_credito = (empty($_POST['itemcredito']))? "" : $_POST['itemcredito'];
    
    
    //ACTUALIZA CREDITO CON AL NUEVA REFRENCIA
    $dataUpdateCredito = array("id_referencia_comercial"=>$id_ref_credito);
    $db->where("id_creditos", $id_credito);
    $actualizaCredito = $db->update("creditos_tbl", $dataUpdateCredito);

    if($actualizaCredito){
        $response=$id_credito;     
    }
    
    echo json_encode($response);
}