<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-users.php'; ?>
<?php require_once '../../appweb/inc/query-tablas-complementarias.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>

<?php 

//recibe datos de PEDIDOS
$loockVar = "false";
$idItemPOST = "";
$datasCredito = array();
if(isset($_POST['itemid_var']) && $_POST['itemid_var'] != ""){
    
    $idItemPOST = $_POST['itemid_var'];
    $typeItemPOST = (string)$_POST['itemtype_var'];
    $typeItemPOST = $db->escape($_POST['itemtype_var']);
    //$idItemPOST = int($idItemPOST);
    //$idItemPOST = $db->escape($idItemPOST);
    
    $valida_idItemPOST = validaInteger($idItemPOST, "1");
    
    if($valida_idItemPOST === true){   
        $loockVar = "true";      
        
        /*
        *PARA EL DEUDOR
        */
        $datas_Deudor = array();
        $datas_Deudor = queryDeudorDetalles($idItemPOST);
            
        if($typeItemPOST == "deudordb"){            
            if(is_array($datas_Deudor) && !empty($datas_Deudor)){
                foreach($datas_Deudor as $deuKey){
                    $primernombre_deudor = isset($datas_Deudor['primer_nombre_deudor'])? $datas_Deudor['primer_nombre_deudor'] : "";                
                    $segundonombre_deudor = isset($datas_Deudor['segundo_nombre_deudor'])? $datas_Deudor['segundo_nombre_deudor'] : "";   
                    $primerapellido_deudor = isset($datas_Deudor['primer_apellido_deudor'])? $datas_Deudor['primer_apellido_deudor'] : "";    
                    $segundoapellido_deudor = isset($datas_Deudor['segundo_apellido_deudor'])? $datas_Deudor['segundo_apellido_deudor'] : "";
                    
                    $nombrecompleto_deudor = $primernombre_deudor." ".$segundonombre_deudor." ".$primerapellido_deudor." ".$segundoapellido_deudor;                       
                    
                    $cedula_deudor = isset($datas_Deudor['cedula_deudor'])? $datas_Deudor['cedula_deudor'] : "";   
                    $fechanace_deudor = ($datas_Deudor['fecha_nacimiento_deudor'] != "0000-00-00")? date("d/m/Y", strtotime($datas_Deudor['fecha_nacimiento_deudor'])) : "";
                    $lugarnace_deudor = isset($datas_Deudor['lugar_naciminto_deudor'])? $datas_Deudor['lugar_naciminto_deudor'] : "";
                    $idgenero_deudor = isset($datas_Deudor['genero_deudor'])? $datas_Deudor['genero_deudor'] : "";
                    $idestadoCivil_deudor = isset($datas_Deudor['estado_civil_deudor'])? $datas_Deudor['estado_civil_deudor'] : "";
                    $idEscolaridad_deudor = isset($datas_Deudor['nivel_escolaridad_deudor'])? $datas_Deudor['nivel_escolaridad_deudor'] : "";
                    $oficio_deudor = isset($datas_Deudor['oficio_deudor'])? $datas_Deudor['oficio_deudor'] : "";
                    $profesion_deudor = isset($datas_Deudor['profesion_deudor'])? $datas_Deudor['profesion_deudor'] : "";
                    
                    $dirgeo_deudor = isset($datas_Deudor['dir_geo_deudor'])? $datas_Deudor['dir_geo_deudor'] : "";
                    $idestrato_deudor = isset($datas_Deudor['estrato_social_deudor'])? $datas_Deudor['estrato_social_deudor'] : "";
                    $idtipovivienda_deudor = isset($datas_Deudor['tipo_vivienda_deudor'])? $datas_Deudor['tipo_vivienda_deudor'] : "";
                    $barrio_deudor = isset($datas_Deudor['barrio_domicilio_deudor'])? $datas_Deudor['barrio_domicilio_deudor'] : "";
                    
                    $email_deudor = isset($datas_Deudor['email_deudor'])? $datas_Deudor['email_deudor'] : "";                    
                    $tel1_deudor = isset($datas_Deudor['tel_uno_deudor'])? $datas_Deudor['tel_uno_deudor'] : "";
                    $tel2_deudor = isset($datas_Deudor['tel_uno_deudor'])? $datas_Deudor['tel_dos_deudor'] : "";                    
                    $placeid_deudor = isset($datas_Deudor['url_maps_deudor'])? $datas_Deudor['url_maps_deudor'] : "";
                                                        
                    $empresa_deudor = isset($datas_Deudor['nombre_empresa_deudor'])? $datas_Deudor['nombre_empresa_deudor'] : "";
                    $cargoempresa_deudor = isset($datas_Deudor['cargo_empresa_deudor'])? $datas_Deudor['cargo_empresa_deudor'] : "";
                    $telempresa_deudor = isset($datas_Deudor['tel_empresa_deudor'])? $datas_Deudor['tel_empresa_deudor'] : "";
                    $dirempresa_deudor = isset($datas_Deudor['dir_empresa_deudor'])? $datas_Deudor['dir_empresa_deudor'] : "";
                    $ciudadempresa_deudor = isset($datas_Deudor['ciudad_empresa_deudor'])? $datas_Deudor['ciudad_empresa_deudor'] : "";
                    
                    $comentarios_deudor = isset($datas_Deudor['comentarios_deudor'])? $datas_Deudor['comentarios_deudor'] : "";
                    $fecharegistro_deudor = isset($datas_Deudor['fecha_alta_deudor'])? date("d/m/Y", strtotime($datas_Deudor['fecha_alta_deudor'])) : "";
                    
                                                                                
                    /*---//QUERYS COMPLEMENTARIAS//---*/
                    //GENERO
                    $generoDeudor = "";
                    if(isset($idgenero_deudor) && $idgenero_deudor != ""){
                        $generoDeudor = queryGenero($idgenero_deudor);
                    }
                    //ESTADO CIVIL
                    $estadoCivilDeudor = "";
                    if(isset($idestadoCivil_deudor) && $idestadoCivil_deudor != ""){
                        $estadoCivilDeudor = queryEstadoCivil($idestadoCivil_deudor);
                    }
                    //ESCOLARIDAD 
                    $escolaridadDeudor = "";
                    if(isset($idEscolaridad_deudor) && $idEscolaridad_deudor != ""){
                        $escolaridadDeudor = queryEscolaridad($idEscolaridad_deudor);
                    }                    
                    //ESTRATO SOCIAL
                    $estratoSocialDeudor = "";
                    if(isset($idestrato_deudor) && $idestrato_deudor != ""){
                        $estratoSocialDeudor = queryEstratoSocial($idestrato_deudor);
                    }
                    //TIPO VIVIENDA
                    $tipoViviendaDeudor = "";
                    if(isset($idtipovivienda_deudor) && $idtipovivienda_deudor != ""){
                        $tipoViviendaDeudor = queryTipoVivienda($idtipovivienda_deudor);
                    }
                    
                    
                    /*---//QUERYS SOBRE CREDITOS DEL USUARIO//---*/
                    $datasCreditos_deduor = $datas_Deudor['datascreditos'];
                    $total_creditos_historico = count($datasCreditos_deduor);
                    $consecutivo_Credito = "";
                    
                    
                }/*FIN FOREACH[array_usuarios]*/

            }    
        }
        //echo "<pre>";
        // print_r($datas_Deudor);
       
        /*
        *ACTIVIDAD USUARIO
        */
        
        $queryActividad = array();
        $db->where("id_usuario_credito", $idItemPOST);
        $queryActividad = $db->get("usuario_asignado_credito");
        
        
    }//FIN VALIDA [$valida_idItemPOST]           
}
//echo "<pre>";
//print_r($datasCredito);

//echo $id_Credito;
//***********
//SITE MAP
//***********


$rootLevel = "usuarios";
$sectionLevel = "";
$subSectionLevel = "";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo METATITLE ?></title>    
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="author" content="massin">
    <?php echo _CSSFILESLAYOUT_ ?>    
    <link rel="stylesheet" href="../../appweb/plugins/datatables/dataTables.bootstrap.css">
    <?php echo _FAVICON_TOUCH_ ?>    
    <style type="text/css">
        td.details-control {
            background: url('details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('details_close.png') no-repeat center center;
        }
        input[type="search"]{
            border: 1px solid #8a8a8a;
            background-color: #d8d8d8;
            font-size: 13px;
            font-weight: bold;
            padding-left: 35px;
        }
        input[type="search"]:before{
            position: absolute;
            top: 3px;
            left: 10px;
            display: block;
            width: 30px;
            height: 30px;
            background-color: aqua;
            font-family:'FontAwesome';
            content:"@@";
            font-size: 20px;
            color: aquamarine;
            z-index: 99;
            
        }
    
    </style>
</head>
    
<?php echo LAYOUTOPTION ?><!---//print body tag--->    
<?php include '../../appweb/tmplt/loadevent.php';  ?> 
    
<div class="wrapper">            
    <?php
    /*
    /
    ////HEADER
    /
    */
    ?>
    <?php include '../../appweb/tmplt/header.php';  ?>           
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php include '../../appweb/tmplt/side-mm.php';  ?>
    <?php
    /*
    /
    ////WRAP CONTENT
    /
    */
    ?>        
    <div class="content-wrapper">
        <?php
        /*
        /*****************************//*****************************
        /HEADER CONTENT
        /*****************************//*****************************
        */
        ?>
        <section class="content-header bg-content-header">
            <h1>
                <span class="text-size-x6">Usuarios</span> / Detalles
            </h1>      
            <a href="<?php echo $pathmm."/users/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de usuarios
            </a> 
        </section>
        


        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <?php if($loockVar != "false"){ ?>
        
        <section class="content">
                        
            <div class="row">
                <div class="col-md-3">

                    <div class="box box-primary">
                        <div class="box-body box-profile">

                            <div class="text-center">
                                <i class="fa fa-user-circle fa-5x"></i>
                            </div>

                            <h3 class="profile-username text-center">
                                <?php echo $nombrecompleto_deudor; ?>    
                            </h3>

                            <p class="text-info text-center text-size-x3">
                                C.C&nbsp;<?php echo $cedula_deudor; ?>
                            </p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Registrado desde</b>
                                    <a class="pull-right">
                                        <?php echo $fecharegistro_deudor; ?>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Creditos solicitados</b> 
                                    <a class="pull-right">
                                        <?php echo $total_creditos_historico; ?>
                                    </a>
                                </li>                                
                            </ul>
                            <button type="button" class="btn btn-success btn-block gocredits" data-deudorvar="<?php echo $idItemPOST; ?>">
                                <b>Crear credito</b>
                                <i class="fa fa-plus margin-left-xs"></i>
                            </button>
                        </div>
                
                    </div>
                                
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sobre este usuario</h3>
                        </div>
                        <div class="box-body">
                            <strong>
                                <i class="fa fa-check-circle margin-r-5"></i> Actividad
                            </strong>
                            <ul class="list-group list-group-unbordered">
                                <?php 
                                //SELECT `id_usuario_asignado_credito`, `id_credito`, `id_usuario_credito`, `tipo_usuario_asignado`, `tag_tipo_usuario` FROM `usuario_asignado_credito` WHERE 1
                                if(count($queryActividad) > 0){
                                    foreach($queryActividad as $qaKey){
                                        $idCredito_actividad = $qaKey['id_credito'];
                                        $tipoUsuario_actividad = $qaKey['tipo_usuario_asignado'];
                                        
                                        /*
                                        *Ref credito
                                        */
                                        $db->where("id_creditos", $idCredito_actividad);
                                        $queryRefCredito = $db->getOne("creditos_tbl", "code_consecutivo_credito");
                                            
                                        $referencia_credito = $queryRefCredito["code_consecutivo_credito"];

                                        $actividad_usuario_lyt = "<li class='list-group-item'>";
                                        $actividad_usuario_lyt .= "<span class='text-primary'>".$referencia_credito."</span><br>";
                                        $actividad_usuario_lyt .= "<b class='text-muted'>".$tipoUsuario_actividad."</b>";                                        
                                        $actividad_usuario_lyt .= "<a href='!#' class='pull-right godetails' data-id=".$idCredito_actividad.">";
                                        $actividad_usuario_lyt .= "Ver más<i class='fa fa-chevron-right margin-left-xs'></i>";
                                        $actividad_usuario_lyt .= "</a>";
                                        $actividad_usuario_lyt .= "</li>";
                                        echo $actividad_usuario_lyt; 
                                    }
                                }
                                ?>                                
                            </ul>
                                                                                    
                            <strong>
                                <i class="fa fa-quote-left margin-r-5"></i> Comentarios
                            </strong>
                            
                            <?php 
                                if(isset($comentarios_deudor) && $comentarios_deudor != ""){
                                    echo "<p class='text-muted'>".$comentarios_deudor."</p>"; 
                                }else{
                                    echo "<p class='text-muted'>No se han publicado comentarios</p>"; 
                                }
                            ?>  
                            
                            
                            
                        </div>
                    </div>
                </div>
             
                <div class="col-md-4">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#infoperso" data-toggle="tab">Info. personal</a></li>
                            <li><a href="#infocont" data-toggle="tab">Info. Contacto</a></li>
                            <li><a href="#infolabo" data-toggle="tab">Info. Laboral</a></li>
                        </ul>
                        <div class="tab-content">
                            <?php 
                            /*
                            *INFORMACION PERSONAL
                            */
                            ?>
                            <div class="tab-pane active" id="infoperso"> 
                                <?php
                                $infoperso_lyt = "";
                                       
                                //FECHA NACIMIENTO                                
                                if($fechanace_deudor != ""){
                                    $infoperso_lyt .= "<strong>";    
                                    $infoperso_lyt .= "<i class='fa fa-calendar margin-r-5'></i> ";    
                                    $infoperso_lyt .= "Fecha nacimiento";
                                    $infoperso_lyt .= "</strong>";
                                    $infoperso_lyt .= "<p class='text-muted'>";    
                                    $infoperso_lyt .= $fechanace_deudor;    
                                    $infoperso_lyt .= "</p>";    
                                    $infoperso_lyt .= "<hr>";                                        
                                }
                                       
                                //LUGAR NACIMIENTO                                
                                if($lugarnace_deudor != ""){
                                    $infoperso_lyt .= "<strong>";    
                                    $infoperso_lyt .= "<i class='fa fa-map-marker margin-r-5'></i> ";    
                                    $infoperso_lyt .= "Lugar nacimiento";
                                    $infoperso_lyt .= "</strong>";
                                    $infoperso_lyt .= "<p class='text-muted'>";    
                                    $infoperso_lyt .= $lugarnace_deudor;    
                                    $infoperso_lyt .= "</p>";    
                                    $infoperso_lyt .= "<hr>";                                        
                                }
                                       
                                //GENERO                      
                                if($generoDeudor != ""){
                                    $infoperso_lyt .= "<strong>";    
                                    $infoperso_lyt .= "<i class='fa fa-male margin-r-5'></i> ";    
                                    $infoperso_lyt .= "Genero";
                                    $infoperso_lyt .= "</strong>";
                                    $infoperso_lyt .= "<p class='text-muted'>";    
                                    $infoperso_lyt .= $generoDeudor;    
                                    $infoperso_lyt .= "</p>";    
                                    $infoperso_lyt .= "<hr>";                                        
                                }
                                       
                                //ESTADO CIVIL                      
                                if($estadoCivilDeudor != ""){
                                    $infoperso_lyt .= "<strong>";    
                                    $infoperso_lyt .= "<i class='fa fa-venus-mars margin-r-5'></i> ";    
                                    $infoperso_lyt .= "Estado civil";
                                    $infoperso_lyt .= "</strong>";
                                    $infoperso_lyt .= "<p class='text-muted'>";    
                                    $infoperso_lyt .= $estadoCivilDeudor;    
                                    $infoperso_lyt .= "</p>";    
                                    $infoperso_lyt .= "<hr>";                                        
                                }
                                       
                                //ESCOLARIDAD                   
                                if($escolaridadDeudor != ""){
                                    $infoperso_lyt .= "<strong>";    
                                    $infoperso_lyt .= "<i class='fa fa-graduation-cap margin-r-5'></i> ";    
                                    $infoperso_lyt .= "Nivel estudios";
                                    $infoperso_lyt .= "</strong>";
                                    $infoperso_lyt .= "<p class='text-muted'>";    
                                    $infoperso_lyt .= $escolaridadDeudor;    
                                    $infoperso_lyt .= "</p>";    
                                    $infoperso_lyt .= "<hr>";                                        
                                }
                                
                                //OFICIO                   
                                if($oficio_deudor != ""){
                                    $infoperso_lyt .= "<strong>";    
                                    $infoperso_lyt .= "<i class='fa fa-wrench margin-r-5'></i> ";    
                                    $infoperso_lyt .= "Oficio";
                                    $infoperso_lyt .= "</strong>";
                                    $infoperso_lyt .= "<p class='text-muted'>";    
                                    $infoperso_lyt .= $oficio_deudor;    
                                    $infoperso_lyt .= "</p>";    
                                    $infoperso_lyt .= "<hr>";                                        
                                }
                                       
                                    
                                //PROFESION               
                                if($profesion_deudor != ""){
                                    $infoperso_lyt .= "<strong>";    
                                    $infoperso_lyt .= "<i class='fa fa-briefcase margin-r-5'></i> ";    
                                    $infoperso_lyt .= "Profesion";
                                    $infoperso_lyt .= "</strong>";
                                    $infoperso_lyt .= "<p class='text-muted'>";    
                                    $infoperso_lyt .= $profesion_deudor;    
                                    $infoperso_lyt .= "</p>";    
                                    $infoperso_lyt .= "<hr>";                                        
                                }
                                       
                                
                                echo $infoperso_lyt;
                                ?>                                                                
                            </div>
                            
                            <?php 
                            /*
                            *INFORMACION CONTACTO
                            */
                            ?>
                            <div class="tab-pane" id="infocont"> 
                                <?php
                                $infocont_lyt = "";
                                       
                                //DIRECCION               
                                if($dirgeo_deudor != ""){
                                    $infocont_lyt .= "<strong>";    
                                    $infocont_lyt .= "<i class='fa fa-map-marker margin-r-5'></i> ";    
                                    $infocont_lyt .= "Domicilio";
                                    $infocont_lyt .= "</strong>";
                                    $infocont_lyt .= "<p class='text-muted'>";    
                                    $infocont_lyt .= $dirgeo_deudor;    
                                    $infocont_lyt .= "</p>";    
                                    $infocont_lyt .= "<hr>";                                        
                                }
                                      
                                //BARRIO              
                                if($barrio_deudor != ""){
                                    $infocont_lyt .= "<strong>";    
                                    $infocont_lyt .= "<i class='fa fa-building-o margin-r-5'></i> ";    
                                    $infocont_lyt .= "Barrio";
                                    $infocont_lyt .= "</strong>";
                                    $infocont_lyt .= "<p class='text-muted'>";    
                                    $infocont_lyt .= $barrio_deudor;    
                                    $infocont_lyt .= "</p>";    
                                    $infocont_lyt .= "<hr>";                                        
                                }
                                       
                                //TIPO VIVIENDA            
                                if($tipoViviendaDeudor != ""){
                                    $infocont_lyt .= "<strong>";    
                                    $infocont_lyt .= "<i class='fa fa-home margin-r-5'></i> ";    
                                    $infocont_lyt .= "Tipo vivienda";
                                    $infocont_lyt .= "</strong>";
                                    $infocont_lyt .= "<p class='text-muted'>";    
                                    $infocont_lyt .= $tipoViviendaDeudor;    
                                    $infocont_lyt .= "</p>";    
                                    $infocont_lyt .= "<hr>";                                        
                                }
                                       
                                //ESTRATO SOCIAL           
                                if($estratoSocialDeudor != ""){
                                    $infocont_lyt .= "<strong>";    
                                    $infocont_lyt .= "<i class='fa fa-group margin-r-5'></i> ";    
                                    $infocont_lyt .= "Estrato social";
                                    $infocont_lyt .= "</strong>";
                                    $infocont_lyt .= "<p class='text-muted'>";    
                                    $infocont_lyt .= $estratoSocialDeudor;    
                                    $infocont_lyt .= "</p>";    
                                    $infocont_lyt .= "<hr>";                                        
                                }
                                       
                                //TEL FIJO                             
                                if($tel1_deudor != ""){
                                    $infocont_lyt .= "<strong>";    
                                    $infocont_lyt .= "<i class='fa fa-phone margin-r-5'></i> ";    
                                    $infocont_lyt .= "Número fijo";
                                    $infocont_lyt .= "</strong>";
                                    $infocont_lyt .= "<p class='text-muted'>";    
                                    $infocont_lyt .= $tel1_deudor;    
                                    $infocont_lyt .= "</p>";    
                                    $infocont_lyt .= "<hr>";                                        
                                }
                                       
                                //TEL MOVIL                
                                if($tel2_deudor != ""){
                                    $infocont_lyt .= "<strong>";    
                                    $infocont_lyt .= "<i class='fa fa-mobile-phone fa-lg margin-r-5'></i> ";    
                                    $infocont_lyt .= "Número celular";
                                    $infocont_lyt .= "</strong>";
                                    $infocont_lyt .= "<p class='text-muted'>";    
                                    $infocont_lyt .= $tel2_deudor;    
                                    $infocont_lyt .= "</p>";    
                                    $infocont_lyt .= "<hr>";                                        
                                }
                                       
                                //EMAIL                      
                                if($email_deudor != ""){
                                    $infocont_lyt .= "<strong>";    
                                    $infocont_lyt .= "<i class='fa fa-envelope-o margin-r-5'></i> ";    
                                    $infocont_lyt .= "Email";
                                    $infocont_lyt .= "</strong>";
                                    $infocont_lyt .= "<p class='text-muted'>";    
                                    $infocont_lyt .= $email_deudor;    
                                    $infocont_lyt .= "</p>";    
                                    $infocont_lyt .= "<hr>";                                        
                                }
                                
                                echo $infocont_lyt;
                                ?>                                                                
                            </div>
                            
                            
                            <?php 
                            /*
                            *INFORMACION LABORAL
                            */
                            ?>
                            <div class="tab-pane" id="infolabo"> 
                                <?php
                                $infolabo_lyt = "";
                                       
                                //EMPRESA             
                                if($empresa_deudor != ""){
                                    $infolabo_lyt .= "<strong>";    
                                    $infolabo_lyt .= "<i class='fa fa-bank margin-r-5'></i> ";    
                                    $infolabo_lyt .= "Empresa";
                                    $infolabo_lyt .= "</strong>";
                                    $infolabo_lyt .= "<p class='text-muted'>";    
                                    $infolabo_lyt .= $empresa_deudor;    
                                    $infolabo_lyt .= "</p>";    
                                    $infolabo_lyt .= "<hr>";                                        
                                }
                                       
                                //CARGO EMPRESA                            
                                if($cargoempresa_deudor != ""){
                                    $infolabo_lyt .= "<strong>";    
                                    $infolabo_lyt .= "<i class='fa fa-sitemap margin-r-5'></i> ";    
                                    $infolabo_lyt .= "Cargo";
                                    $infolabo_lyt .= "</strong>";
                                    $infolabo_lyt .= "<p class='text-muted'>";    
                                    $infolabo_lyt .= $cargoempresa_deudor;    
                                    $infolabo_lyt .= "</p>";    
                                    $infolabo_lyt .= "<hr>";                                        
                                }
                                       
                                //TEL EMPRESA    
                                if($telempresa_deudor != ""){
                                    $infolabo_lyt .= "<strong>";    
                                    $infolabo_lyt .= "<i class='fa fa-phone margin-r-5'></i> ";    
                                    $infolabo_lyt .= "Número teléfono";
                                    $infolabo_lyt .= "</strong>";
                                    $infolabo_lyt .= "<p class='text-muted'>";    
                                    $infolabo_lyt .= $telempresa_deudor;    
                                    $infolabo_lyt .= "</p>";    
                                    $infolabo_lyt .= "<hr>";                                        
                                }
                                       
                                //DIRECCION                     
                                if($dirempresa_deudor != ""){
                                    $infolabo_lyt .= "<strong>";    
                                    $infolabo_lyt .= "<i class='fa fa-map-signs margin-r-5'></i> ";    
                                    $infolabo_lyt .= "Dirección";
                                    $infolabo_lyt .= "</strong>";
                                    $infolabo_lyt .= "<p class='text-muted'>";    
                                    $infolabo_lyt .= $dirempresa_deudor;    
                                    $infolabo_lyt .= "</p>";    
                                    $infolabo_lyt .= "<hr>";                                        
                                }
                                       
                                //CIUDAD
                                if($ciudadempresa_deudor != ""){
                                    $infolabo_lyt .= "<strong>";    
                                    $infolabo_lyt .= "<i class='fa fa-map-marker margin-r-5'></i> ";    
                                    $infolabo_lyt .= "Ciudad";
                                    $infolabo_lyt .= "</strong>";
                                    $infolabo_lyt .= "<p class='text-muted'>";    
                                    $infolabo_lyt .= $ciudadempresa_deudor;    
                                    $infolabo_lyt .= "</p>";    
                                    $infolabo_lyt .= "<hr>";                                        
                                }
                                
                                echo $infolabo_lyt;
                                ?>                                                                
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Geoposición</h3>
                        </div>
                        <div class="box-body no-padmarg">                            
                            <?php echo "<div class='embed-responsive embed-responsive-4by3'><iframe class='embed-responsive-item' src='https://www.google.com/maps/embed/v1/place?q=place_id:".$placeid_deudor."&key=AIzaSyCgZFZPgdG2kRiFoE123zr-PA02WR_8yG4' width='100%' height='350px' frameborder='0' style='border:0' allowfullscreen></iframe></div>"; ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        
        </section>
        
            
        <section class="content">    
            <h2 class="page-header">Historial</h2>    
            <div class="row">                
                <div class="col-xs-12 ">
                    <?php if(is_array($datasCreditos_deduor) && !empty($datasCreditos_deduor)){ ?>
                    <div class="box">                    
                        <div class="box-body table-responsive">
                            <table id="printdatatbl" class="table table-hover" style="width:100%;">     
                                <thead>                                
                                    <tr>                                        
                                        <th >Referencia</th>                                        
                                        <th >Acreedor</th>
                                        <th >Ciudad</th>
                                        <th >Detalles</th>
                                        <th >Status</th>
                                        <th >Progreso</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    //if(is_array($datasCreditos_deduor) && !empty($datasCreditos_deduor)){ 
                                    foreach($datasCreditos_deduor as $dcdKey){
                                        $id_Credito = isset($dcdKey['idcredito'])? $dcdKey['idcredito'] : ""; 
                                        $consecutivo_Credito = isset($dcdKey['consecutivo'])? $dcdKey['consecutivo'] : "";   $dcdKey['consecutivo'];
                                        $datas_Acreedor = $dcdKey['dataAcreedor'];
                                        $datas_Cuotas = $dcdKey['datascuotas'];
                                        $idStatus_credito = $dcdKey['datastatus'];
                                        $idTipo_credito = $dcdKey['datatipocredito'];
                                        $fecha_InicioCredito = ($dcdKey['fechaabre'] != "0000-00-00")? date("d/m/y", strtotime($dcdKey['fechaabre'])) : "-"; 
                                        $fecha_FinCredito = ($dcdKey['fechacierra'] == "0000-00-00")? "En proceso" : date("d/m/y", strtotime($dcdKey['fechacierra'])); 

                                        /*---//QUERYS COMPLEMENTARIAS//---*/
                                        //STATUS CREDITO
                                        $statusCredito = "";
                                        if(isset($idStatus_credito) && $idStatus_credito != ""){

                                            switch($idStatus_credito){
                                                case "1":
                                                    $statusCredito = queryStatusdeCredito($idStatus_credito);        
                                                    $statusCredito = "<span class='badge bg-gray text-size-x3'>".$statusCredito."</span>";
                                                break;
                                                case "2":
                                                    $statusCredito = queryStatusdeCredito($idStatus_credito);        
                                                    $statusCredito = "<span class='badge bg-green text-size-x3'>".$statusCredito."</span>";
                                                break;
                                                case "3":
                                                    $statusCredito = queryStatusdeCredito($idStatus_credito);        
                                                    $statusCredito = "<span class='badge bg-danger text-size-x3'>".$statusCredito."</span>";
                                                break;                                                    
                                                case "5":
                                                    $statusCredito = queryStatusdeCredito($idStatus_credito);        
                                                    $statusCredito = "<span class='badge bg-danger text-size-x3'>".$statusCredito."</span>";
                                                break;
                                            }


                                        }
                                        //TIPO CREDITO
                                        $tipoCredito = "";
                                        if(isset($idTipo_credito) && $idTipo_credito != ""){
                                            $tipoCredito = queryTipodeCredito($idTipo_credito);
                                        }

                                        //SOBRE EL ACREEDOR
                                        if(is_array($datas_Acreedor) && !empty($datas_Acreedor)){
                                            foreach($datas_Acreedor as $dacreKey){                        
                                                $nickname_acreedor = isset($datas_Acreedor['nickname_acreedor'])? $datas_Acreedor['nickname_acreedor'] : "";
                                                $ciudad_acreedor = isset($datas_Acreedor['codigo_geo_ciudad_acreedor'])? $datas_Acreedor['codigo_geo_ciudad_acreedor'] : "";
                                                $estado_acreedor = isset($datas_Acreedor['codigo_geo_estado_acreedor'])? $datas_Acreedor['codigo_geo_estado_acreedor'] : "";
                                                $pais_acreedor = isset($datas_Acreedor['codigo_geo_pais_acreedor'])? $datas_Acreedor['codigo_geo_pais_acreedor'] : "";
                                                $fecharegistro_acreedor = ($datas_Acreedor['fecha_alta_acreedor'] != "0000-00-00")? date("d/m/y", strtotime($datas_Acreedor['fecha_alta_acreedor'])) : "-";
                                            }
                                        }

                                        //CUOTAS  -/RECAUDOS/-
                                        $calculo_valor_prestado = 0;
                                        $calculo_valor_credito = 0;
                                        $calculo_valor_recaudado = 0;
                                        $calculo_valor_porcobrar = 0;

                                        $cuotas_pagadas = 0;
                                        $total_numero_cuotas_credito = count($datas_Cuotas);
                                        
                                        $fechaPrimerRecaudo ="";

                                        if(is_array($datas_Cuotas) && !empty($datas_Cuotas)){
                                            foreach($datas_Cuotas as $dcKey){                                   
                                                $cuota_status = $dcKey['id_status_recaudo'];
                                                $cuota_numero = $dcKey['numero_cuota_recaudos'];
                                                $cuota_capital = $dcKey['capital_cuota_recaudo'];
                                                $cuota_interes = $dcKey['interes_cuota_recaudo'];
                                                $cuota_mora = $dcKey['valor_mora_cuota_recaudo'];                       
                                                $cuota_sobrecargo = $dcKey['sobrecargo_cuota_recaudo']; 
                                                $cuota_valor_cuota = $dcKey['total_cuota_plan_pago'];
                                                $cuota_valor_recaudado = $dcKey['total_valor_recaudado_estacuota'];
                                                $cuota_valor_faltante = $dcKey['valor_faltante_cuota'];
                                                $cuota_valor_recaulculado = $dcKey['valor_cuota_recaulcaldo_recaudos'];
                                                $cuota_fecha_recaudo = date("d/m/Y",strtotime($dcKey['fecha_max_recaudo']));
                                                $cuota_fecha_recaudo_realizado = date("d/m/Y",strtotime($dcKey['fecha_recaudo_realizado']));
                                                $cuota_comentarios = $dcKey['comentarios_recaudo']; 

                                                /*//CALCULO DINERO PRESTADO
                                                $calculo_valor_prestado += $cuota_capital;

                                                //CALCULO VALOR CREDITO
                                                $calculo_valor_credito += $cuota_valor_cuota;

                                                //CALCULO VALOR RECAUDADO
                                                if($cuota_status != "3"){
                                                    $calculo_valor_recaudado += $cuota_valor_recaudado; 
                                                }*/

                                                //CUOTAS PAGADAS EN ESTE CREDITO
                                                if($cuota_status != "3"){
                                                    $cuotas_pagadas++; 
                                                }
                                                
                                                //DEIFINIR LA PRIMERA FECHA RECAUDO
                                                if($cuota_numero == "1"){
                                                    $fechaPrimerRecaudo =  date("d/m/Y",strtotime($dcKey['fecha_max_recaudo']));
                                                }


                                            }/*FIN FOREACH[$datas_Cuotas]*/
                                            //PROCESO DE PAGO CREDITO
                                            $progress_bar_credito = ceil(($cuotas_pagadas/$total_numero_cuotas_credito)*100);


                                            /*$calculo_valor_porcobrar = $valorPagar - $calculo_valor_recaudado;

                                            $calculo_valor_prestado_format = number_format($valorPrestado, 2, '.', ','); 
                                            $calculo_valor_credito_format = number_format($valorPagar, 2, '.', ','); 
                                            $calculo_valor_recaudado_format = number_format($calculo_valor_recaudado, 2, '.', ','); 
                                            $calculo_valor_porcobrar_format = number_format($calculo_valor_porcobrar, 2, '.', ',');*/ 
                                        }/*FIN EXISTE ARRAY[$datas_Cuotas]*/


                                        /*
                                        *LAYOUT CREDITOS
                                        */
                                        $historial_lyt = "";
                                        $historial_lyt .= "<tr>";

                                        $historial_lyt .= "<td>";/*--/REFERENCIA/--*/
                                        $historial_lyt .= $consecutivo_Credito;
                                        $historial_lyt .= "</td>";/*--/REFERENCIA/--*/

                                        $historial_lyt .= "<td>";/*--/ACREEDOR/--*/
                                        $historial_lyt .= $nickname_acreedor;
                                        $historial_lyt .= "</td>";/*--/ACREEDOR/--*/

                                        $historial_lyt .= "<td>";/*--/CIUDAD/--*/
                                        $historial_lyt .= $ciudad_acreedor;
                                        $historial_lyt .= "</td>";/*--/CIUDAD/--*/

                                        $historial_lyt .= "<td>";/*--/DETALLES/--*/
                                        //$historial_lyt .= $total_numero_cuotas_credito; $fecha_InicioCredito $fecha_FinCredito $tipoCredito

                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Número Cuotas</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$total_numero_cuotas_credito."</a>";    
                                        $historial_lyt .= "</p>";

                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Fecha creación [Inicio]/[Fin]</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>[".$fecha_InicioCredito."] / [".$fecha_FinCredito."]</a>";    
                                        $historial_lyt .= "</p>";
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Fecha inicio credito</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$fechaPrimerRecaudo."</a>";    
                                        $historial_lyt .= "</p>";

                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Tipo credito</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$tipoCredito."</a>";    
                                        $historial_lyt .= "</p>";


                                        $historial_lyt .= "</td>";/*--/DETALLES/--*/

                                        $historial_lyt .= "<td>";/*--/STATUS/--*/
                                        $historial_lyt .= $statusCredito;
                                        $historial_lyt .= "</td>";/*--/STATUS/--*/

                                        $historial_lyt .= "<td>";/*--/PROGRESS/--*/

                                        $historial_lyt .= "<div class='clearfix'>";
                                        $historial_lyt .= "<span class='pull-left'>Pago credito</span>";
                                        $historial_lyt .= "<small class='pull-right'>".$progress_bar_credito."%</small>";
                                        $historial_lyt .= "</div>";
                                        $historial_lyt .= "<div class='progress sm'>";
                                        $historial_lyt .= "<div class='progress-bar progress-bar-green' style='width: ".$progress_bar_credito."%;'></div>";
                                        $historial_lyt .= "</div>";


                                        $historial_lyt .= "</td>";/*--/PROGRESS/--*/

                                        $historial_lyt .= "</tr>";

                                        echo $historial_lyt;

                                    }/*FIN FOREACH[$datasCreditos_deduor]*/
                                    //}/*FIN EXISTE ARRAY[$datasCreditos_deduor]*/    
                                    
                                    
                                ?>                                
                                </tbody>
                            </table>            
                        </div>                    
                    </div> 
                    <?php }else{/*SI NO EXISTE ARRAY[$datasCreditos_deduor]*/      ?>  
                    
                    <div class="box50  padd-verti-lg">
                        <div class="alert alert-dismissible bg-gray">
                            <div class="media">
                                <div class=" media-left">
                                    <i class="fa fa-info-circle fa-4x text-blue"></i>
                                </div>
                                <div class="media-body">
                                    <h3 class="no-padmarg">Sin registros</h3>
                                    <p style="font-size:1.232em; line-height:1;">
                                        Este usuario no posee historial de creditos
                                    </p>                                                  
                                </div>

                            </div>                    
                        </div>
                    </div>
                    <?php }/*FIN ARRAY[$datasCreditos_deduor]*/ ?>  
                </div>
            </div>
        </section>
        
        <?php }else{ ?>
        
        <section class="content ">                    
            <div class="box50  padd-verti-lg">
                <div class="alert alert-dismissible bg-gray">
                    <div class="media">
                        <div class=" media-left">
                            <i class="fa fa-unlink fa-4x text-red"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Oops!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                No encontramos o fue eliminado el DEUDOR que deseas visualizar
                            </p>              
                            <p style="font-size:1.232em; line-height:1;"> Asegurate que seleccionaste el usuario correcto, e intentalo de nuevo</p>
                        </div>

                    </div>                    
                </div>
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group text-center">
                            <a href="<?php echo $pathmm."/users/"; ?>" type="button" class="btn btn-default">
                                <i class='fa fa-th-list fa-lg margin-right-xs'></i>
                                <span>lista de usuarios</span>                        
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        
        <?php } ?>
        
        
    </div>
    <?php echo "<input id='pathnewcredit' type='hidden' value='".$pathmm."/credits/new/'/>"; ?>
    <?php echo "<input id='pathdetailcredit' type='hidden' value='".$pathmm."/credits/details/'/>"; ?>
    <?php
    /*
    /
    ////FOOTER
    /
    */
    ?>
    <?php include '../../appweb/tmplt/footer.php';  ?>
    <?php
    /*
    /
    ////RIGHT BAR
    /
    */
    ?>
    <?php //include '../../appweb/tmplt/right-side.php';  ?>
    <?php echo "<input type='hidden' id='creditvar' value='".$consecutivo_Credito."' />";  ?>
    
</div>
<?php echo _JSFILESLAYOUT_ ?>
<script src="../../appweb/plugins/misc/jquery.redirect.js"></script> 
<!-- DataTables -->
<script src="../../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>    
    $(document).ready(function(){
        var detailurl = $("#pathnewcredit").val();

        $(".gocredits").each(function(){ 
            
            var itemid = $(this).attr("data-deudorvar");
            
            $(this).click(function(){        
                //alert("me dieron click "+itemid);
                $.redirect(detailurl,{ deudorcod: itemid}); 
            });
        });

    });
    
    
    $(document).ready(function(){
        var detailurl = $("#pathdetailcredit").val();

        $('.godetails').each(function(){ 

            var itemid = $(this).attr("data-id");

            $(this).click(function(){                   
                //window.location = detailurl+"?itemid_var="+itemid;
                $.redirect(detailurl,{ itemid_var: itemid}); 
            });
        });

    });
    
    $(function(){

        //var locacionvar = $('#locacionvar').val();     
        //console.log(locacionvar);
    /*$('#printdatatbl').DataTable({        
        "scrollX": false,
        "ordering": false,
        "autoWidth": false
    });*/

        $('#printdatatbl').DataTable( {	            
            "order": [[ 0, "asc" ]],
            "processing": true,
            "bDeferRender": true,			
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sProcessing":     "Procesando...",
                "sLengthMenu": 'Mostrar&nbsp;&nbsp;<select>'+
                    '<option value="10">10</option>'+
                    '<option value="20">20</option>'+
                    '<option value="30">30</option>'+
                    '<option value="40">40</option>'+
                    '<option value="50">50</option>'+
                    '<option value="-1">All</option>'+
                    '</select>&nbsp;&nbsp;registros',    
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:&nbsp;&nbsp;",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Por favor espere - cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });

    });

</script>       
</body>
</html>
