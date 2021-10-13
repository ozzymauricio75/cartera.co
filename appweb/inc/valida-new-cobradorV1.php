<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once 'sessionvars.php';
require_once 'query-custom-user.php';
require_once "../lib/gump.class.php";
require_once "../lib/password.php";
require_once "site-tools.php"; 


$fieldPost = $_POST['fieldedit'];
$response = array();
$fileValida = "";

/*
*SI EL USUARIO ES NUEVO
*/
//if(isset($_POST['newstore']) && $_POST['newstore'] == "ok"){
if(isset($fieldPost) && $fieldPost == "additem"){    
            
    //***********
    //RECIBE DATOS 
    //***********
                
    /*
    **INFO PERSONAL
    */
    $pseudo_acreedor = (empty($_POST['pseudouserpost']))? "" : $_POST['pseudouserpost'];
    $id_acreedor = (empty($_POST['codeuserform']))? "" : $_POST['codeuserform'];
    //$id_cobrador = (empty($_POST['codeitemform']))? "" : $_POST['codeitemform'];
    
    $Primer_Nombre = (empty($_POST['nombre1form']))? "" : $_POST['nombre1form'];   
    $Segundo_Nombre = (empty($_POST['nombre2form']))? "" : $_POST['nombre2form'];
    $Primer_Apellido = (empty($_POST['apellido1form']))? "" : $_POST['apellido1form'];
    $Segundo_Apellido = (empty($_POST['apellido2form']))? "" : $_POST['apellido2form'];
    $Numero_Documento = (empty($_POST['cedulaform']))? "" : $_POST['cedulaform'];
    $Fecha_Nacimiento = (empty($_POST['Nacimientoform']))? "" : date("Y-m-d", strtotime(str_replace('/', '-', $_POST['Nacimientoform']))); ;
    $Lugar_Nacimiento = (empty($_POST['LugarNacimientoform']))? "" : $_POST['LugarNacimientoform'];
   
    /*
    **INFORMACION CUENTA
    */   
    $userUser = (empty($_POST['userform']))? "" : $_POST['userform'];
    $passUser = (empty($_POST['passform']))? "" : $_POST['passform'];
    
    /*
    **ESCOLARIDAD
    */   
    $Profesion = (empty($_POST['Profesionform']))? "" : $_POST['Profesionform'];
    $Oficio = (empty($_POST['Oficioform']))? "" : $_POST['Oficioform'];
    
    /*
    **INFORMACION LABORAL
    */
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
    
    $checkTels = ""; 
    if($Telefono1 == "" && $Telefono2 ==""){
        $checkTels = "error";
    }
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
    $direccionFullPost = empty($_POST['txtEndereco']) ? "" : $_POST['txtEndereco'];
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
    }
    
 
    $_POST = array( 
        //'id_acreedor' =>$id_acreedor,
        //'idcobrador'=> $id_cobrador,
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
        'Nombre_Empresa'=>$Nombre_Empresa, 
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
        'geodireccion'=> $direccionFullPost,
        'useraccount' => $userUser,
        'passaccount' => $passUser
    );
       		        
	$rules = array(
        //'id_acreedor' => 'required|integer',
        //'idcobrador'=> 'required|integer',        
        'primernombre'=> 'required|valid_name|max_len,60',
        'segundonombre'=> 'valid_name|max_len,25',
        'PrimerApellido'=> 'valid_name|max_len,25',
        'segundoapellido'=> 'valid_name|max_len,25',
        'NumeroDocumento' => 'required|numeric|max_len,12',
        'Fecha_Nacimiento'=>'date',
        'Lugar_Nacimiento'=>'alpha_space|max_len,100',
        'Genero'=>'integer',  
        'Estado_Civil'=>'integer',
        'escolaridad' => 'integer',
        'Profesion'=>'alpha_space|max_len,50', 
        'Oficio'=>'alpha_space|max_len,50',         
        'Nombre_Empresa'=>'alpha_space|max_len,60',
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
        'geodireccion'=> 'alpha_space', 
        'useraccount' => 'required|user|min_len,4|max_len,15',
        'passaccount' => 'required|pass|min_len,4|max_len,12' 
    );    
    
    $filters = array(
        //'id_acreedor'=> 'trim|sanitize_string',
        //'idcobrador'=> 'trim|sanitize_string',
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
        'Nombre_Empresa'=>'trim|sanitize_string', 
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
        'geodireccion'=>'trim|sanitize_string',
        'useraccount' => 'trim|sanitize_string',
        'passaccount' => 'trim|sanitize_string'
	);
	
    $_POST = $validfield->sanitize($_POST); 
    $validated = $validfield->validate($_POST, $rules);
    $_POST = $validfield->filter($_POST, $filters);
    
    //echo "<pre>";
    //print_r($validated);
    // Check if validation was successful
            
    
	if($validated === TRUE && $checkTels != "error"){
        
        //***********
        //EXISTE CEDULA COBRADOR
        //***********
        $db->where('cedula_cobrador', $Numero_Documento);
        $db->where('cedula_cobrador', "", "!=");
        $emailExist = $db->getOne('cobrador_tbl');
        
        if(count($emailExist)>0){
            $errEmailTxt = "Ya existe un usuario registrado con este numero de cedula <b>".$Numero_Documento."</b><br>Por favor verifiquelo e intentelo de nuevo";
            
            $errQueryTmpl_email ="<ul class='list-group text-left'>";
            $errQueryTmpl_email .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>Cedula ya existe</b>
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
        //EXISTE EMAIL REGISTRADO?
        //*************
        */
        $db->where('mail_cobrador', $Email);
        $db->where('mail_cobrador', "", "!=");
        $emailExist = $db->getOne('cobrador_tbl');
        
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
        //EXISTE NICKNAME
        //*************
        */
        $db->where('nickname_usuario', $userUser);
        $db->where('nickname_usuario', "", "!=");
        $nicknameExist = $db->getOne('usuario_tbl');
        
        if(count($nicknameExist)>0){ 
            $errEmailTxt = "Ya existe un usuario registrado con este nombre de usuario <b>".$userUser."</b><br>Por favor verifiquelo e intentelo de nuevo";
            
            $errQueryTmpl_email ="<ul class='list-group text-left'>";
            $errQueryTmpl_email .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>Nombre de usuario</b>
                <br>Erro: ".$errEmailTxt."
                <br>Intenta adicionar alguna letra o número
                <br>Puedes intentar crear otro nombre de usuario
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl_email .="</ul>";
            
            $response['error'] = $errQueryTmpl_email;
            
            echo json_encode($response);
            
            //$response['error']= "Ya existe un usuario registrado con este número de cedula <b>".$Numero_Documento."</b><br>Por favor verifiquelo e intentelo de nuevo";
            return;
        }

                        
            
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
                //SELECT `id_cobrador`, `id_acreedor`, `id_status_cobrador`, `nombre_cobrador`, `cedula_cobrador`, `mail_cobrador`, `nickname_cobrador`, `pass_cobrador`, `pass_humano_cobrador`, `tel_uno_cobrador`, `tel_dos_cobrador`, `direccion_cobrador`, `ciudad_cobrador`, `estado_cobrador`, `pais_cobrador`, `foto_cobrador`, `fecha_alta_cobrador`, `tag_seccion_plataforma` FROM `cobrador_tbl` WHERE 1
                'id_status_cobrador' => 1,
                'id_acreedor' => $id_acreedor,
                'nombre_cobrador' => $nuevoPost['primernombre'],
                'cedula_cobrador' =>$nuevoPost['NumeroDocumento'],                
                'mail_cobrador' =>  $nuevoPost['Email'],
                'tel_uno_cobrador' => $nuevoPost['Telefono1'],
                'tel_dos_cobrador' =>$nuevoPost['Telefono2'],
                'direccion_cobrador'=>  $nuevoPost['Direccion_Residencia'],
                'ciudad_cobrador'=> $nuevoPost['Ciudad_Empresa'],
                'barrio_cobrador' =>$nuevoPost['Barrio'],                                
                //'nickname_cobrador'=> $nuevoPost['useraccount'],                
                //'pass_cobrador'=> password_hash($nuevoPost['passaccount'], PASSWORD_BCRYPT, array("cost" => 10)), 
                //'pass_humano_cobrador' => $nuevoPost['passaccount'],
                'fecha_alta_cobrador'=> $dateFormatDB,
            );  
            
            //SELECT `id_usuario`, `id_usuario_plataforma`, `id_status_usuario`, `nombre_usuario`, `cedula_usuario`, `mail_usuario`, `nickname_usuario`, `pass_usuario`, `pass_humano_usuario`, `tel_uno_usuario`, `tel_dos_usuario`, `direccion_usuario`, `ciudad_usuario`, `estado_usuario`, `pais_usuario`, `foto_usuario`, `fecha_alta_usuario`, `tag_seccion_plataforma` FROM `usuario_tbl` WHERE 1
            $dataInsertUsuario = array(                                               
                'id_usuario_plataforma' => NULL,
                'nombre_usuario' =>$nuevoPost['primernombre'],                
                'cedula_usuario' =>  $nuevoPost['NumeroDocumento'],
                'mail_usuario' => $nuevoPost['Email'],
                'tel_uno_usuario' =>$nuevoPost['Telefono1'],
                'tel_dos_usuario'=>  $nuevoPost['Telefono2'],
                'direccion_usuario'=> $nuevoPost['Direccion_Residencia'],
                'ciudad_usuario' =>$nuevoPost['Ciudad_Empresa'],                                
                //'estado_usuario'=> $nuevoPost['useraccount'],                
                //'pais_usuario'=> password_hash($nuevoPost['passaccount'], PASSWORD_BCRYPT, array("cost" => 10)), 
                'nickname_usuario' => $nuevoPost['useraccount'],
                'pass_usuario' => password_hash($nuevoPost['passaccount'], PASSWORD_BCRYPT, array("cost" => 10)), 
                'pass_humano_usuario' => $nuevoPost['passaccount'],
                'fecha_alta_usuario'=> $dateFormatDB,
                'tag_seccion_plataforma'=> 'cobrador',
            );  
            
            
        }
        //echo "<pre>";
        //print_r($dataInsert);
        //$idStore_order = $db->insert('account_empresa', $dataInsert);
        //if($idStore_order == true){ 
        //$id= $db -> insert('deudor_tbl, $dataInsert');
        //if (!$id){
        //$db->where ('id_cobrador',$id_cobrador); 
        //if ($db->update ('cobrador_tbl', $dataInsert)){  
        
        $newCobradorINS = $db->insert('cobrador_tbl', $dataInsert);
        if ($newCobradorINS){ 
            
            $dataInsertUsuario['id_usuario_plataforma'] = $newCobradorINS;
            if($db->insert('usuario_tbl', $dataInsertUsuario)){
                                                
                $response=true;     
                
            }else{
                $errInsertDatas = $db->getLastErrno();

                $errQueryTmpl_ins ="<ul class='list-group text-left'>";
                $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>Cuenta de usuario cobrador</b>
                    <br>Erro: ".$errInsertDatas."
                    <br>No fue posible crear la cuenta de usuario para este cobrador
                    <br>Puedes intentar de nuevo
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";
                $errQueryTmpl_ins .="</ul>";


                $response['error']= $errQueryTmpl_ins;
                
            }
                        
        }else{

            //$response['error'] = "Error al insertar el deudor: ".$db->getLastQuery() ."\n". $db->getLastError();
            $errInsertDatas = $db->getLastErrno();

            $errQueryTmpl_ins ="<ul class='list-group text-left'>";
            $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo crear este cobrador</b>
                <br>Erro: ".$errInsertDatas."
                <br>Puedes intentar de nuevo
                <br>Si el error continua, por favor entre en contacto con soporte</li>";
            $errQueryTmpl_ins .="</ul>";


            $response['error']= $errQueryTmpl_ins;

        }
                
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
                    /*case 'id_acreedor' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Acreedor</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error con la identificación de tu cuenta de usuario, por favor, intenta crear un nuevo Credito, en la opción CREAR / NUEVO CREDITO</li>";
                    break;
                    case 'idcobrador' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Cobrador</b>
                        <br>".$usertyped."
                        <br>Parece que ha ocurrido un error en el momento de crear este Cobrador, por favor, intenta crearlo de nuevo. En el menu lateral presiona COBRANZA > COBRADORES y luego presiona Agregar Cobrador</li>";
                    break; */                       
                    case 'primernombre' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Nombre</b>
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
                        <br>Escribe un nombre de empresa valido, puedes usar letras y números</li>";
                    break;
                    case 'Cargo' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Cargo</b>
                        <br>".$usertyped."
                        <br>Reglas:                                   
                        <br>Escribe el cargo del cobrador en la empresa que trabaja, puedes usar letras y números</li>";
                    break;                        
                    case 'Telefono' :
                        $errValidaTmpl .=  "<li class='list-group-item list-group-item-danger'><b>Teléfono de la empresa</b>
                        <br>".$usertyped."
                        <br>Reglas:                        
                        <br>Escrebe un número de teléfono valido
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
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Email</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Escribe una cuenta de email valida
                        <br>Ej. nombredeusuario@sitioweb.com</li>";
                    break; 
                    case 'Telefono1' :
                        $errValidaTmpl .=  "<li class='list-group-item list-group-item-danger'><b>Teléfono 1 </b>
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
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Barrio </b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Nombre del barrio
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
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Comentarios</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Escribe una pequeña reseña sobre este cobrador
                        <br>Max. 200 carácteres
                        <br>Letras, números, signos de puntuación son permitidos</li>";
                    break;
                    case 'geodireccion' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Geoposición</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Escribe y selecciona la dirección de domicilio</li>";
                    break;
                    case 'useraccount' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Usuario</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe un nombre de usuario, puedes suar letras, números y los caracteres <b>.-_</b>
                        <br>Entre 4 y 15 carácteres</li>";
                    break;
                    case 'passaccount' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Contraseña</b>
                        <br>".$usertyped."
                        <br>Reglas:
                        <br>Campo obligatrorio
                        <br>Escribe una contraseña, puedes suar letras, números y los caracteres <b>!@#$%&()+.-_</b>
                        <br>Entre 4 y 12 carácteres</li>";
                    break;
                        
                }
                
                
                
            }
            
        }
        
        /*switch($checkAdressPost){  //$checkAdressPost $checkTels
            case 'error' :
                $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Geoposicionamiento</b>                
                <br>Escribe y selecciona la dirección para ubicar en el mapa</li>";
            break;
        }*/
        
        switch($checkTels){  //$checkAdressPost $checkTels
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