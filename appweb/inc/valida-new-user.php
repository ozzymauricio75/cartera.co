<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once "../lib/gump.class.php";
require_once "../lib/password.php";
require_once "site-tools.php"; 

/*$_POST['fieldedit']= "additem";
$_POST['codeitemform'] ='Cheo feliciano';

$_POST['company'] ='';
$_POST['nomeuser'] =' El gran combo';        
$_POST['cedulauser'] ='12312312-12';
$_POST['tel1user'] ='4360766';
$_POST['tel2user'] ='3101234333434';
$_POST['emailuser'] ='contacto@gramcombo.com';//
$_POST['cityuser'] ='calle 5ta No. 43A-12 Barrio departamental';
$_POST['userstore'] ='Cali colombia';
$_POST['pass'] ='';

$_POST['adduserkit'] ='c.feliciano';
$_POST['zapuserkit'] ='3421%&(asASD';                            
$_POST['ropuserkit'] ='3421%&(asASD'; */ 

$fieldPost = $_POST['fieldedit'];
$response = array();

$idUser = "";
$kitAddUser = "";
$kitZapatoUser = "";
$kitRopaUser = "";
$companyUser = "";
$nomeUser = "";
$cedulaUser = "";
$fijoUser = "";
$celUser = "";
$emailUser = "";
$dirUser = "";
$cityUser = "";
$userUser = "";
$passUser = "";

//if(isset($_POST['newstore']) && $_POST['newstore'] == "ok"){
if(isset($fieldPost) && $fieldPost == "additem"){    
    
    //***********
    //RECIBE DATOS 
    //***********
    $idUser = (empty($_POST['codeitemform']))? "" : $_POST['codeitemform'];
            
    $companyUser = (empty($_POST['company']))? "" : $_POST['company'];  
        
    $nomeUser = (empty($_POST['nomeuser']))? "" : $_POST['nomeuser'];        
    $cedulaUser = (empty($_POST['cedulauser']))? "" : $_POST['cedulauser'];
    $fijoUser = (empty($_POST['tel1user']))? "" : $_POST['tel1user'];
    $celUser = (empty($_POST['tel2user']))? "" : $_POST['tel2user'];
    $emailUser = (empty($_POST['emailuser']))? "" : $_POST['emailuser']; 
    $dirUser = (empty($_POST['adressuser']))? "" : $_POST['adressuser']; 
    $cityUser = (empty($_POST['cityuser']))? "" : $_POST['cityuser'];
        
    $userUser = (empty($_POST['userstore']))? "" : $_POST['userstore'];
    $passUser = (empty($_POST['pass']))? "" : $_POST['pass'];
            
    /*
        formData.append("kitcheck_ropa", tipokitRopa); 
        formData.append("tipocoleccicheck_ropa", tipoColeccionRopa); 
        formData.append("cantpzakitcheck_ropa", cantkitRopa); 
        
        formData.append("kitcheck_zapt", tipokitZapato); 
        formData.append("tipocoleccicheck_zapt", tipoColeccionZapato); 
        formData.append("cantpzakitcheck_zapt", cantkitZapato); 
        formData.append("subcatkitcheck_zapt", subcatekitZapato); 
        
        formData.append("kitcheck_add", tipokitAdd); 
        formData.append("tipocoleccicheck_add", tipoColeccionAdd); 
        formData.append("cantpzakitcheck_add", cantkitAdd); 
        formData.append("subcatkitcheck_add", subcatekitAdd);
        
        $kitAddUser = (empty($_POST['adduserkit']))? "" : $_POST['adduserkit'];
        $kitZapatoUser = (empty($_POST['zapuserkit']))? "" : $_POST['zapuserkit'];
        $kitRopaUser = (empty($_POST['ropuserkit']))? "" : $_POST['ropuserkit'];
    */
    
    //SOBRE LOS KIT ASIGNADOS
    
    //=======KIT DE ROPA
    $tipokitRopa = (empty($_POST['kitcheck_ropa']))? "" : $_POST['kitcheck_ropa']; 
    $tipoColeccionRopa = (empty($_POST['tipocoleccicheck_ropa']))? "" : $_POST['tipocoleccicheck_ropa'];
    $cantkitRopa = (empty($_POST['cantpzakitcheck_ropa']))? "" : $_POST['cantpzakitcheck_ropa'];
    
    //=======KIT DE ZAPATOS
    $tipokitZapato = (empty($_POST['kitcheck_zapt']))? "" : $_POST['kitcheck_zapt']; 
    $tipoColeccionZapato = (empty($_POST['tipocoleccicheck_zapt']))? "" : $_POST['tipocoleccicheck_zapt'];
    $cantkitZapato = (empty($_POST['cantpzakitcheck_zapt']))? "" : $_POST['cantpzakitcheck_zapt'];
    $subcatekitZapato = (empty($_POST['subcatkitcheck_zapt']))? "" : $_POST['subcatkitcheck_zapt'];
    
    //=======KIT DE ADICIONAL
    $tipokitAdd = (empty($_POST['kitcheck_add']))? "" : $_POST['kitcheck_add']; 
    $tipoColeccionAdd = (empty($_POST['tipocoleccicheck_add']))? "" : $_POST['tipocoleccicheck_add'];
    $cantkitAdd = (empty($_POST['cantpzakitcheck_add']))? "" : $_POST['cantpzakitcheck_add'];
    $subcatekitAdd = (empty($_POST['subcatkitcheck_add']))? "" : $_POST['subcatkitcheck_add'];                    
    
    
    $_POST = array( 
        'iduser'=> $idUser,
        'companyuser'=> $companyUser,
        'nameuser'=> $nomeUser,
        'ceduuser' => $cedulaUser,
        'fijouser' => $fijoUser,
        'celuser' => $celUser,
        'emailuser' => $emailUser,
        'diruser' => $dirUser,
        'cityuser' => $cityUser,                
        'useraccount' => $userUser,
        'passaccount' => $passUser,                
        'kitropa' => $tipokitRopa,        
        'coleropa' => $tipoColeccionRopa,
        'pzaropa' => $cantkitRopa,        
        'kitzapto' => $tipokitZapato,        
        'colezapto' => $tipoColeccionZapato,
        'subcatzapto' => $subcatekitZapato,
        'pzazapto' => $cantkitZapato,        
        'kitadd' => $tipokitAdd,        
        'coleadd' => $tipoColeccionAdd,
        'subcatadd' => $subcatekitAdd,
        'pzaadd' => $cantkitAdd
	);		
	
	$_POST = $validfield->sanitize($_POST); 
    
    //$validfield->validation_rules(array(
	$rules = array(
        'iduser'=> 'integer',
        'companyuser'=> 'integer',
        'nameuser'=> 'required|valid_name|max_len,40',
        'ceduuser' => 'required|alpha_dash|max_len,20',
        'fijouser' => 'required|phone_number|max_len,18',
        'celuser' => 'phone_number|max_len,18',
        'emailuser' => 'required|valid_email',
        'diruser' => 'required|street_address',
        'cityuser' => 'required|alpha_space|max_len,30',           
        'useraccount' => 'required|user|min_len,4|max_len,12',
        'passaccount' => 'required|pass|min_len,4|max_len,12',                
        'kitropa' => 'alpha_dash',      
        'coleropa' => 'alpha_dash',
        'pzaropa' => 'integer',       
        'kitzapto' => 'alpha_dash',       
        'colezapto' => 'alpha_dash',
        'subcatzapto' => 'integer', 
        'pzazapto' => 'integer',        
        'kitadd' => 'alpha_dash',      
        'coleadd' => 'alpha_dash', 
        'subcatadd' => 'integer',  
        'pzaadd' => 'integer'       
	);
    //$validfield->filter_rules(array(  
	$filters = array(
        'iduser'=> 'trim|sanitize_string',
        'companyuser'=> 'trim|sanitize_string',
        'nameuser'=> 'trim|sanitize_string',
        'ceduuser' => 'trim|sanitize_string',
        'fijouser' => 'trim|sanitize_string',
        'celuser' => 'trim|sanitize_string',
        'emailuser' => 'trim|sanitize_email',
        'diruser' => 'trim|sanitize_string',
        'cityuser' => 'trim|sanitize_string',      
        'useraccount' => 'trim|sanitize_string',
        'passaccount' => 'trim|sanitize_string',           
        'kitropa' => 'trim|sanitize_string',   
        'coleropa' => 'trim|sanitize_string',
        'pzaropa' => 'trim|sanitize_string',
        'kitzapto' => 'trim|sanitize_string', 
        'colezapto' => 'trim|sanitize_string',
        'subcatzapto' => 'trim|sanitize_string',
        'pzazapto' => 'trim|sanitize_string',       
        'kitadd' => 'trim|sanitize_string',  
        'coleadd' => 'trim|sanitize_string',
        'subcatadd' => 'trim|sanitize_string',
        'pzaadd' => 'trim|sanitize_string'
	);
	
    $validated = $validfield->validate($_POST, $rules);
    $_POST = $validfield->filter($_POST, $filters);
    //echo "<pre>";
    ///print_r($validated);
    // Check if validation was successful
	if($validated === TRUE){
        
        $nuevoPost = array();
        $nuevoPost = $_POST;
       
        //foreach($nuevoPost as $valInsert => $valPost){
        foreach($nuevoPost as $valInsert){
            $dataInsert = array(                                              
                'id_account_empre' =>  $nuevoPost['companyuser'],
                'nombre_account_user' => $nuevoPost['nameuser'],
                'cedula_user' =>$nuevoPost['ceduuser'],
                'mail_account_user'=>  $nuevoPost['emailuser'],                
                'tel_account_user'=> $nuevoPost['fijouser'],                                                             
                'tel_account_user2' =>$nuevoPost['celuser'],
                'dir_account_user'=> $nuevoPost['diruser'],               
                'ciudad_account_user'=> $nuevoPost['cityuser'],               
                'account_pseudo_user'=> $nuevoPost['useraccount'],                
                'pass_account_user'=> password_hash($nuevoPost['passaccount'], PASSWORD_BCRYPT, array("cost" => 10)), 
                'pass_human' => $nuevoPost['passaccount'],
                'estado_cuenta' => '1',
                'fecha_alta_account_user'=> $dateFormatDB
            );  
            
            $dataPackDotacion = array('0'=>array(
                    'id_account_user' => $nuevoPost['iduser'],
                    'kit' => $nuevoPost['kitropa'],
                    'id_catego_product' => NULL,
                    'id_subcatego_producto' => NULL,
                    'cant_pz_kit' => $nuevoPost['pzaropa'],
                    'tags_depatament_produsts' => $nuevoPost['coleropa'],
                ),
                '1'=>array(
                    'id_account_user' => $nuevoPost['iduser'],
                    'kit' => $nuevoPost['kitzapto'],
                    'id_catego_product' => NULL,
                    'id_subcatego_producto' => $nuevoPost['subcatzapto'],
                    'cant_pz_kit' => $nuevoPost['pzazapto'],
                    'tags_depatament_produsts' => $nuevoPost['colezapto'],
                ),
                '2'=>array(
                    'id_account_user' => $nuevoPost['iduser'],
                    'kit' => $nuevoPost['kitadd'],
                    'id_catego_product' => NULL,
                    'id_subcatego_producto' => $nuevoPost['subcatadd'],
                    'cant_pz_kit' => $nuevoPost['pzaadd'],
                    'tags_depatament_produsts' => $nuevoPost['coleadd'],
                )                                
            );
        }
        //echo "<pre>";
        ///print_r($dataPackDotacion);
        //$idStore_order = $db->insert('account_empresa', $dataInsert);
        //if($idStore_order == true){ 
        $db->where ('id_account_user', $idUser); 
        if ($db->update ('account_user', $dataInsert)){
            if(is_array($dataPackDotacion) && count($dataPackDotacion)>0){
                foreach ($dataPackDotacion as $name => $datas) {
                    $idPDU = $db->insert('pack_dotacion_user', $datas);
                    if (!$idPDU){
                        /*$response = $idPDU;    
                    }else{*/
                        $response['error'] = "Error al insertar el paquete de dotación: ".$db->getLastQuery() ."\n". $db->getLastError();    
                    }                    
                }
            }
            
            $response = $idUser;
            $_SESSION['newitem'] = NULL;
            unset($_SESSION['newitem']);
                                                        
        }else{
            $errInsertDatas = $db->getLastErrno();
                
            $errQueryTmpl_ins ="<ul class='list-group text-left'>";
            $errQueryTmpl_ins .="<li class='list-group-item list-group-item-danger'><b>*** Algo salio mal ***</b><br>
                <br>Wrong: <b>No se pudo crear esta empresa</b>
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
                    case 'companyuser' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Empresa</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Selecciona una de las empresas del menú disponible</li>";
                    break;                        
                    case 'nameuser' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Nombre usuario</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Debes escribir el nombre del representante
                        <br>Escribe un nombre de persona real</li>";
                    break;
                    case 'ceduuser' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>No. Cedula</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Escribe un número de cedula o identificación valido</li>";
                    break;                        
                    case 'fijouser' :
                        $errValidaTmpl .=  "<li class='list-group-item list-group-item-danger'><b>Teléfono</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Regras:
                        <br>Escrebe un número de telefono valido
                        <br>Ej. (5) 555 5555</li>";
                        
                    break;
                    case 'celuser' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Otro Teléfono</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Escrebe un número de telefono valido
                        <br>Ej. (5) 555 5555</li>";
                        
                    break;                    
                    case 'emailuser' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Email</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Escribe una cuenta de email valida
                        <br>Ej. nombredeusuario@sitioweb.com</li>";
                        
                    break; 
                    case 'cityuser' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Ciudad</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Escribe una ciudad valida
                        <br>Cali - Valle</li>";
                        
                    break;                     
                    case 'useraccount' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Usuario</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Escribe un nombre de usuario
                        <br>Debe tener entre 4 y 12 caracrteres
                        <br>Puedes usar letras, números y los caracteres (._-)</li>";
                        
                    break;
                    case 'passaccount' :
                        $errValidaTmpl .= "<li class='list-group-item list-group-item-danger'><b>Contraseña</b>
                        <br>".$usertyped."
                        <br>Regras:
                        <br>Escribe una contraseña valida
                        <br>Debe tener entre 4 y 12 caracrteres
                        <br>Puedes usar letras, números y los caracteres (!@#$%&()+.-_)</li>";
                        
                    break;
                }
            }
            
        }
        
        $errValidaTmpl .= "</ul>";
        $response['error']= $errValidaTmpl;
        
    }
    
    echo json_encode($response);
    
}
