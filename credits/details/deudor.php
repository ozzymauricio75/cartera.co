<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php //require_once '../../appweb/inc/query-prods.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>
<?php 

$errTmpl_ins = "";
//***********
//DEFINE CANCEL - TRASH EVENT
//***********
$statusCancel = "";
if(isset($_GET['trash']) && $_GET['trash'] == "ok"){ 

    $statusCancel = 1;
    
    
}

//***********
//CREA NUEVO CODEUDOR
//***********
$codeNewProd ="";
$loockVar = "false";      
if(isset($_POST['itemid_var']) && $_POST['itemid_var'] != ""){
            
    $valida_idItemPOST = validaInteger($_POST['itemid_var'], "1");
    //$valida_idRefPOST = validaInteger($_POST['itemref_var'], "1");
    
    if($valida_idItemPOST === true /*&& $valida_idRefPOST ===true*/){   
        $loockVar = "true";      
            
        //if($typeItemPOST == "refdb"){ 
        //}
        $idCreditoPOST = $_POST['itemid_var'];
        //$idDeudorPOST = $_POST['itemref_var'];
        //$typeItemPOST = (string)$_POST['itemtype_var'];
        //$typeItemPOST = $db->escape($_POST['itemtype_var']);
        //$idItemPOST = int($idItemPOST);
        //$idItemPOST = $db->escape($idItemPOST);
    }
}

//***********
//SITE MAP
//***********

$rootLevel = "creditos";
$sectionLevel = "new";
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
    <?php echo _FAVICON_TOUCH_ ?>    
    <link rel="stylesheet" href="../../appweb/plugins/form-validator/theme-default.css"> 
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../../appweb/plugins/iCheck/all.css"> 
    <!--fileimput /css---->
    <link rel="stylesheet" href="../../appweb/plugins/fileimput/fileimput.css">
    <style>
        .kv-avatar .file-preview-frame,.kv-avatar .file-preview-frame:hover {
            margin: 0 auto;            
            padding: 0;
            border: none;
            box-shadow: none;                       
        }
        .kv-avatar .file-input {
            display: table;
            max-width: 160px;            
            margin: 0 auto;
            border: 1px dashed #c4c4c4;
            text-align: center;
            padding-bottom: 7px;
        }
        .kv-avatar .file-input .file-preview,
        .kv-avatar .file-input .file-drop-zone{
            border: 0px solid transparent;            
        }
    </style>
    
    <style>          
          #mapa {
            height: 350px;
          }
        .controls {
          margin-top: 10px;
          border: 1px solid transparent;
          border-radius: 2px 0 0 2px;
          box-sizing: border-box;
          -moz-box-sizing: border-box;
          /*height: 32px;*/
          outline: none;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        #txtEndereco {
          background-color: #fff;
          font-family: Roboto;
          font-size: 15px;
          font-weight: 300;
          /*margin-left: 12px;*/
          padding: 0 11px 0 13px;
          text-overflow: ellipsis;
          /*width: 300px;*/
        }

        #txtEndereco:focus {
          border-color: #4d90fe;
        }

        .pac-container {
          font-family: Roboto;
        }

        #type-selector {
          color: #fff;
          background-color: #4d90fe;
          padding: 5px 11px 0px 11px;
        }

        #type-selector label {
          font-family: Roboto;
          font-size: 13px;
          font-weight: 300;
        }


        #target {
            width: 345px;
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
                <span class="text-size-x6">Credito</span> / Nuevo Deudor
            </h1>
            <a href="<?php echo $pathmm."/credits/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de creditos
            </a> 
        </section>
        
        <?php
        /*
        /*****************************//*****************************
        /PASOS REGISTRO CREDITO
        /*****************************//*****************************
        */                
        ?>
        <?php //include '../../appweb/tmplt/new-credits-steps.php';  ?>
       
        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <?php if($loockVar == "true"){ ?>
        
        <div class="btn-toolbar maxwidth-usersforms margin-bottom-xs" role="toolbar" aria-label="">                
            <div class="btn-group pull-right" role="group" aria-label="">
                
                <button data-href="<?php echo $pathmm."/credits/details/"; ?>" type="button" class="btn btn-info godetails" data-id='<?php echo $idCreditoPOST; ?>'>
                    <i class='fa fa-chevron-left fa-lg margin-right-xs'></i>
                    <span>Volver al credito actual</span>                     
                </button>
                
            </div>
        </div>
        
        <section class="content maxwidth-usersforms margin-bottom-lg">            
            <form action="" id="deudorform" autocomplete="off" >
            <div class="">
                <div class="row wrapdivsection">
                    <div class="col-xs-12 col-sm-4">                        
                        <h3>Cédula</h3>
                        <p class="help-block"></p>
                    </div>
                    <div class="col-xs-12 col-sm-8 ">
                        <small for="cedulaform" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                        <div class="form-group" id="deudordb">
                            
                            <input type="number" id="cedulaform" name="cedulaform" class="form-control checkuser" value ="" placeholder="Número identidad" data-field="deudordb" >     
                        </div>
                        <div id="responsedeudordb"></div>
                        <div id="errdeudordb"></div>
                    </div>
                    <hr class="linesection"/>
                </div>
                
                <?php 
                /*
                *SI EXISTE
                *$(.userexist)
                */
                ?>
                <div class="userexiste" >
                
                
                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">                        
                            <h3>Información personal</h3>
                            <p class="help-block"></p>
                        </div>
                        <div class="col-xs-12 col-sm-8 ">                        
                            <h4>Nombre</h4>
                            <div class="form-group">                                
                                <input type="text" id="existnombreform" name="existnombre1form" class="form-control" value ="" placeholder="Primer nombre"  disabled>
                            </div>                        
                            <div class="form-group">                                
                                <input type="text" name="existapellido1form" class="form-control" value ="" placeholder="Primer apellido" disabled>     
                            </div>
                        </div>  
                        <hr class="linesection"/>
                    </div>

                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">
                            <h3>Información contácto</h3>
                            <p class="help-block"></p>
                        </div>                                           
                        <div class="col-xs-12 col-sm-8">                                    
                            <div class="form-group" id="tel1deudordb">                                
                                <input type="tel" id="existTelefono1form" name="existTelefono1form" class="form-control editexist" value ="" placeholder="Telefono Fijo" data-field="tel1deudordb">    
                                <div id="errtel1deudordb"></div>
                            </div>
                            <div class="form-group" id="tel2deudordb">
                                <input type="tel" name="existTelefono2form" class="form-control editexist" value ="" placeholder="Teléfono Celular" data-field="tel2deudordb">  
                                <div id="errtel2deudordb"></div>
                            </div> 
                            
                            <div class="form-group">
                                <h4>
                                    <button id="editgeodomicilio" class="btn btn-default btn-sm pull-right" type="button">
                                        <i class="fa fa-pencil margin-right-xs"></i>
                                        Editar
                                    </button>
                                    <button id="canceleditgeodomicilio" class="btn btn-default btn-sm pull-right" type="button">
                                        <i class="fa fa-times margin-right-xs"></i>
                                        Cancelar
                                    </button>
                                    Dirección
                                </h4>
                                <p id="geodomicilio"></p>                                
                            </div> 
                        </div>      
                        <hr class="linesection"/>
                    </div>
                    
                
                </div><!--//FIN[.userexist]//-->
                <?php 
                /*
                * FIN[.userexist]
                */
                ?>
                
                                                           
                <div class="wrapinfoform">
                
                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">                        
                            <h3>Información personal</h3>
                            <p class="help-block"></p>
                        </div>
                        <div class="col-xs-12 col-sm-8 ">                        
                            <h4>Nombre</h4>
                            <div class="form-group">
                                <small for="nombre1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                <input type="text" id="nombre1form" name="nombre1form" class="form-control" value ="" placeholder="Primer nombre"  >

                                <!--data-toggle="tooltip" data-placement="left" title="Escribe tu primer nombre, usa sólo letras"-->

                            </div>
                            <div class="form-group">
                                <input type="text" name="nombre2form" class="form-control" value ="" placeholder="Segundo nombre" >
                            </div>
                            <div class="form-group">
                                <small for="apellido1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                <input type="text" name="apellido1form" class="form-control" value ="" placeholder="Primer apellido" >     
                            </div>
                            <div class="form-group">
                                <input type="text" name="apellido2form" class="form-control" value ="" placeholder="Segundo apellido" >     
                            </div>
                            
                            <h4>Nacimiento</h4>
                            <div class="form-group">                            
                                <input type="text" id="Nacimientoform" name="Nacimientoform" class="form-control" value ="" placeholder="Fecha nacimiento" >     
                            </div>

                            <div class="">
                                <div class="form-group" id="selectciudadnace">
                                    <select class="deptonacelist form-control" name="deptonace">
                                        <option value="" selected>Selecciona un Departamento</option>
                                        <?php

                                            $db->orderBy("nombre_estado_colombia","Asc");           
                                            $queryDeptoNace = $db->get("estados_colombia_tbl");

                                            if ($db->count > 0){
                                                foreach ($queryDeptoNace as $dnKey) { 
                                                    $idDptoNace = $dnKey['id_estado_rel'];
                                                    $nombreDptoNace = $dnKey['nombre_estado_colombia'];

                                                    echo "<option value='".$idDptoNace."'>".$nombreDptoNace."</option>";	
                                                }
                                            }	
                                        ?>                                        
                                    </select>                        
                                </div>
                                <div  id="ciudadnace">
                                    <div class="form-group">
                                        <select class="ciudadnacelist form-control" name="LugarNacimientoform"></select>
                                    </div>                                
                                </div>                     
                            </div> 


                            <h4>Genero</h4>                        
                            <div class="form-group"  id='Generoform'>

                            <?php 
                            $generoLyt = "";

                            $db->orderBy("nombre_genero","Asc");			
                            $generoQuery = $db->get('genero_tbl');                        
                            if(is_array($generoQuery)) {
                                foreach($generoQuery as $gqKey){
                                    $idGeneroTbl = $gqKey['id_genero'];
                                    $nameGeneroTbl = $gqKey['nombre_genero'];                                

                                    $generoLyt .= "<p>";
                                    $generoLyt .= "<label>";
                                    $generoLyt .= "<input type='radio' name='Generoform' value='".$idGeneroTbl."' class='flat-red'>";
                                    $generoLyt .= "<span class=' margin-left-md'>".$nameGeneroTbl."</span>";
                                    $generoLyt .= "</label>";
                                    $generoLyt .= "</p>";                                                                
                                }                                                    
                            }   
                            echo $generoLyt; 
                            ?>  
                            </div>
                            <h4>Estado civil</h4>                        
                            <div class="form-group"  id='EstadoCivilform'>

                            <?php 
                            $estadoCivilLyt = "";

                            $db->orderBy("nombre_estado_civil","Asc");			
                            $estadoCivilQuery = $db->get('estado_civil_tbl');                        
                            if(is_array($estadoCivilQuery)) {
                                foreach($estadoCivilQuery as $ecqKey){
                                    $idEstadoCivilTbl = $ecqKey['id_estado_civil'];
                                    $nameEstadoCivilTbl = $ecqKey['nombre_estado_civil'];                                

                                    $estadoCivilLyt .= "<p>";
                                    $estadoCivilLyt .= "<label>";
                                    $estadoCivilLyt .= "<input type='radio' name='EstadoCivilform' value='".$idEstadoCivilTbl."' class='flat-red'>";
                                    $estadoCivilLyt .= "<span class=' margin-left-md'>".$nameEstadoCivilTbl."</span>";
                                    $estadoCivilLyt .= "</label>";
                                    $estadoCivilLyt .= "</p>";                                                                
                                }                                                    
                            }   
                            echo $estadoCivilLyt; 
                            ?>                            
                            </div>
 
                        </div>  
                        <hr class="linesection"/>
                    </div>

                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">
                            <h3>Escolaridad</h3>
                            <p class="help-block"></p>
                        </div>                                           
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group">

                                <select class="form-control" id="escolaridadform" name="escolaridadform">
                                    <option value="">Selecciona una opción</option>
                                    <?php 
                                    $escolaridadLyt = "";

                                    $db->orderBy("nombre_escolaridad","Asc");			
                                    $escolaridadQuery = $db->get('escolaridad_tbl');                        
                                    if(is_array($escolaridadQuery)) {
                                        foreach($escolaridadQuery as $escqKey){
                                            $idEscolaridadlTbl = $escqKey['id_escolaridad'];
                                            $nameEscolaridadTbl = $escqKey['nombre_escolaridad'];                                

                                            $escolaridadLyt .= "<option value='".$idEscolaridadlTbl."'>";
                                            $escolaridadLyt .= $nameEscolaridadTbl;
                                            $escolaridadLyt .= "</option>";                                                                
                                        }                                                    
                                    }   
                                    echo $escolaridadLyt; 
                                    ?>
                                </select>                            
                            </div>
                            <div class="form-group">
                                <input type="text" name="Profesionform" class="form-control" value ="" placeholder="Profesión">     
                            </div>
                            <div class="form-group">
                                <input type="text" name="Oficioform" class="form-control" value ="" placeholder="Oficio">     
                            </div>
                        </div>      
                        <hr class="linesection"/>
                    </div>

                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">
                            <h3>Información laboral</h3>
                            <p class="help-block"></p>
                        </div>                                           
                        <div class="col-xs-12 col-sm-8">        
                            <div class="form-group">
                                <input type="text" name="NombreEmpresaform" class="form-control" value ="" placeholder="Empresa">     
                            </div>
                            <div class="form-group">
                                <input type="text" name="Cargoform" class="form-control" value ="" placeholder="Cargo">     
                            </div>
                            <div class="form-group">
                                <input type="tel" name="Telefonoform" class="form-control" value ="" placeholder="Teléfono">     
                            </div>
                            <div class="form-group">
                                <input type="text" name="Direccionform" class="form-control" value ="" placeholder="Dirección">     
                            </div>

                            <div class="">
                                <div class="form-group" id="selectciudadtrabaja">
                                    <select class="deptotrabajalist form-control" name="deptotrabaja">
                                        <option value="" selected>Selecciona un Departamento</option>
                                        <?php

                                            $db->orderBy("nombre_estado_colombia","Asc");           
                                            $queryDeptoTrabaja = $db->get("estados_colombia_tbl");

                                            if ($db->count > 0){
                                                foreach ($queryDeptoTrabaja as $dtKey) { 
                                                    $idDptoTrabaja = $dtKey['id_estado_rel'];
                                                    $nombreDptoTrabaja = $dtKey['nombre_estado_colombia'];

                                                    echo "<option value='".$idDptoTrabaja."'>".$nombreDptoTrabaja."</option>";	
                                                }
                                            }	
                                        ?>                                        
                                    </select>                        
                                </div>
                                <div  id="ciudadtrabaja">
                                    <div class="form-group">
                                        <select class="ciudadtrabajalist form-control" name="CiudadEmpresaform"></select>
                                    </div>                                
                                </div>                     
                            </div> 

                        </div>      
                        <hr class="linesection"/>
                    </div>
                    
                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">
                            <h3>Información contácto</h3>
                            <p class="help-block"></p>
                        </div>                                           
                        <div class="col-xs-12 col-sm-8">        
                            <div class="form-group">               
                                <div class="form-group" id="emaildeudordb">
                                    <input type="email" id="Emailform" name="Emailform" class="form-control checkemailuser" value ="" placeholder="Email" data-field="emaildeudordb" >     
                                </div>
                                <div id="responseemaildeudordb"></div>
                                <div id="erremaildeudordb"></div>

                            </div>
                            <div class="form-group">
                                <small for="Telefono1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Obligatorio al menos uno de los dos teléfonos</small>
                                <input type="tel" id="Telefono1form" name="Telefono1form" class="form-control" value ="" placeholder="Telefono Fijo">     
                            </div>
                            <div class="form-group">
                                <input type="tel" name="Telefono2form" class="form-control" value ="" placeholder="Teléfono Celular">     
                            </div>

                            <div class="form-group">
                                <input type="text" name="DireccionResidenciaform" class="form-control" value ="" placeholder="Dirección">
                            </div>

                            <div class="form-group">
                                <input type="text" name="Barrioform" class="form-control" value ="" placeholder="Barrio">     
                            </div>

                            <div class="form-group">      
                                <select class="form-control" name="TipoViviendaform">
                                    <option value="">Selecciona un tipo de vivienda</option>
                                    <?php 
                                    $tipoViviendaLyt = "";

                                    $db->orderBy("nombre_tipo_vivienda","Asc");			
                                    $tipoViviendaQuery = $db->get('tipo_vivienda_tbl');                        
                                    if(is_array($tipoViviendaQuery)) {
                                        foreach($tipoViviendaQuery as $tvqKey){
                                            $idTipoViviendaTbl = $tvqKey['id_tipo_vivienda'];
                                            $nameTipoViviendaTbl = $tvqKey['nombre_tipo_vivienda'];                                

                                            $tipoViviendaLyt .= "<option value='".$idTipoViviendaTbl."'>";
                                            $tipoViviendaLyt .= $nameTipoViviendaTbl;
                                            $tipoViviendaLyt .= "</option>";                                                                
                                        }                                                    
                                    }   
                                    echo $tipoViviendaLyt; 
                                    ?>
                                </select>  
                            </div>

                            <div class="form-group">

                                <select class="form-control"  name="Estratoform">
                                    <option value="">Estrato social</option>
                                    <?php 
                                    $estratoSocialLyt = "";

                                    $db->orderBy("nombre_estrato_social_tbl","Asc");			
                                    $estratoSocialQuery = $db->get('estrato_social_tbl');                        
                                    if(is_array($estratoSocialQuery)) {
                                        foreach($estratoSocialQuery as $esKey){
                                            $idEstratoSocialTbl = $esKey['id_estrato_social_tbl'];
                                            $nameEstratoSocialTbl = $esKey['nombre_estrato_social_tbl'];                                

                                            $estratoSocialLyt .= "<option value='".$idEstratoSocialTbl."'>";
                                            $estratoSocialLyt .= $nameEstratoSocialTbl;
                                            $estratoSocialLyt .= "</option>";                                                                
                                        }                                                    
                                    }   
                                    echo $estratoSocialLyt; 
                                    ?>
                                </select>                            
                            </div>
                        </div>      
                        <hr class="linesection"/>
                    </div>
                
                </div><!--//FIN[.wrapinfoform]//-->
                
                <div class="wrapinfoform" id="wrappgeo">
                
                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">
                            <h3>Geoposición</h3>
                            <p class="help-block"></p>
                        </div>                                           
                        <div class="col-xs-12 col-sm-8">                       
                            <div class="form-group" id="geodirdeudordb">
                                <small for="txtEndereco" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                <input id="txtEndereco" name="txtEndereco" class="controls form-control editexistgeodir" type="text" placeholder="Escribe y selecciona una dirección" data-field="geodirdeudordb">
                                <div id="mapa"></div>

                                <input type="hidden" id="txtLatitude" name="txtLatitude" value=""/>
                                <input type="hidden" id="txtLongitude" name="txtLongitude" value=""/>

                                <input type="hidden" name="countrycod" id="countrycod" value=""/>
                                <input type="hidden" name="country" id="country" value=""/>
                                <input type="hidden" name="usercity1" id="locality" value=""/>
                                <input type="hidden" name="usercity2" id="administrative_area_level_2" value=""/>
                                <input type="hidden" name="usercity3" id="administrative_area_level_3" value=""/>
                                <input type="hidden" name="userstate" id="administrative_area_level_1" value=""/>
                                <input type="hidden" name="userstateshort" id="administrative_area_short_level_1" value=""/>
                                <input type="hidden" name="userzip" id="postal_code" value=""/>
                                <input type="hidden" id="idplacegeomap" name="mapstore" value=""/> 

                            </div>
                            <div id="errgeodirdeudordb"></div>
                        </div>      
                        <hr class="linesection"/>
                    </div>
                </div><!--FIN[.wrapinfoform]-->
                
                <div class="wrapinfoform">
                    
                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">
                            <h3>Documentos</h3>
                            <p class="help-block"></p>
                        </div>                                           
                        <div class="col-xs-12 col-sm-8">        
                            <div class="form-group">
                                <?php 
                                /*$tipoDocumentoLyt = "";

                                $db->orderBy("descripcion_documento","Asc");			
                                $tipoDocumentoQuery = $db->get('tipo_documentos_tbl');                        
                                if(is_array($tipoDocumentoQuery)) {
                                    foreach($tipoDocumentoQuery as $tdqKey){
                                        $idTipoDocumentoTbl = $tdqKey['id_tipo_documento'];
                                        $nameTipoDocumentoTbl = $tdqKey['descripcion_documento'];                                

                                        $tipoDocumentoLyt .= "<p>";
                                        $tipoDocumentoLyt .= "<label>";
                                        $tipoDocumentoLyt .= "<input type='checkbox' name='Documentosform' value='".$idTipoDocumentoTbl."' class='flat-red'>";
                                        $tipoDocumentoLyt .= "<span class=' margin-left-md'>".$nameTipoDocumentoTbl."</span>";
                                        $tipoDocumentoLyt .= "</label>";
                                        $tipoDocumentoLyt .= "</p>";                                                                
                                    }                                                    
                                }   
                                echo $tipoDocumentoLyt; */
                                ?>   
                            </div>
                            <div class="form-group">

                                <input id="imgmutifile" name="multifileimg[]" type="file" class="file-loading" multiple>
                                <div id="errorBlock" class="help-block"></div>  

                            </div>                        
                        </div>      
                        <hr class="linesection"/>
                    </div>
                                                                                                  
                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">
                            <h3>Comentarios</h3>
                            <p class="help-block"></p>
                        </div>
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group">
                                <textarea id="descri-prod" name="comentariosform" class="form-control" placeholder="Comentarios" style="width: 100%; height: 240px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: none; overflow: auto;"></textarea>
                            </div>
                        </div>
                        <hr class="linesection"/>
                    </div>
                                
                </div><!--FIN[.wrapinfoform]-->
                    
                <div class="row">
                    <div class="col-xs-12">
                        <div id="wrapadditem"></div>
                        <div id="erradditem"></div>       
                        <nav class="box100">
                            <div id="left-barbtn" class="nav navbar-nav padd-verti-xs" style="display:none;">
                                <button data-href="<?php echo $pathmm."/credits/details/"; ?>" type="button" class="btn btn-default godetails margin-top-xs" data-id='<?php echo $idCreditoPOST; ?>'>
                                    <i class='fa fa-chevron-left fa-lg margin-right-xs'></i>
                                    <span>Volver al credito actual</span>                     
                                </button>
                            </div>
                            <div id="right-bartbtn" class="row margin-hori-xs padd-verti-xs">
                                <div class="btn-toolbar pull-right" role="toolbar" aria-label="">
                                    <div class="btn-group margin-bottom-md" role="group" aria-label="">
                                        <button data-href="<?php echo $pathmm."/credits/details/"; ?>" class="btn btn-default goback" title="Eliminar Deudor" data-msj="Recuerda que es obligatorio asignarle un Deudor a cada credito cobrado. Esta referencia nó será incluida para este credito. Deseas continuar?" data-id='<?php echo $idCreditoPOST; ?>'>
                                            <i class='fa fa-times fa-lg margin-right-xs'></i>
                                            <span>Cancelar</span>                        
                                        </button> 
                                        <button type="button" class="btn btn-info margin-hori-xs " id="additembtn">                                    
                                            <i class='fa fa-save fa-lg margin-right-xs'></i>
                                            <span>Guardar</span>                     
                                        </button>                                            
                                    </div>    
                                    
                                </div>
                            </div>
                            <div id="userexist-bartbtn" class="row margin-hori-xs padd-verti-xs text-right" style="display:none;">
                                <button id="addrefexist" class="btn btn-default" type="button" data-field="addrefcredito">
                                    Agregar referencia
                                    <i class="fa fa-save fa-1x margin-left-xs"></i>
                                </button>
                            </div>
                            <div id="erraddrefcredito" style="display:none;"></div>
                            <div id="responseaddrefcredito" style="display:none;">
                                <div class="alert bg-success text-green ">
                                    <h3>
                                        <i class="icon fa fa-check fa-lg margin-right-xs"></i>
                                        Referencia credito
                                    </h3>
                                    <p style="display:block;">Este deudor fue asignado al credito con exito</p>
                                    <button data-href="<?php echo $pathmm."/credits/details/"; ?>" type="button" class="btn btn-default godetails margin-top-xs" data-id='<?php echo $idCreditoPOST; ?>'>
                                        <i class='fa fa-chevron-left fa-lg margin-right-xs'></i>
                                        <span>Volver al credito actual</span>                     
                                    </button> 
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                
                <div class="row">                      
                    <input type="hidden" name="itemexistform" id="itemexistform" value="">  
                    <input type="hidden" name="creditoform" id="creditoform" value="<?php echo $idCreditoPOST; ?>"> 
                    <input type="hidden" name="codeuserform" id="codeuserform" value="<?php echo $idSSUser; ?>">  
                    <?php echo "<input id='pathfile' type='hidden' value='".$pathmm."'/>"; ?>
                    <?php echo "<input id='pathdir' type='hidden' value='".$creditsDir."'/>"; ?>
                    <?php echo "<input id='paththisfile' type='hidden' value='".$pathFile."/deudor.php'/>"; ?>
                </div>
                
                                    
            </div>
            </form>
        </section>    
                        

        <?php }else{ ?>
        
        <section class="content ">                    
            <div class="box50  padd-verti-xs">
                <div class="alert alert-dismissible">
                    <div class="media">
                        <div class=" media-left">
                            <i class="fa fa-unlink fa-4x text-red"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Oops!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                Algo salio mal cuando intentamos crear un nuevo deudor
                            </p>              
                            <p style="font-size:1.232em; line-height:1;">Regresa al credito que estas creando, e intentalo de nuevo</p>
                        </div>
                    </div>                        
                </div>
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">                                           
                        <div class="btn-group text-center">                            
                            <button data-href="<?php echo $pathmm."/credits/details/"; ?>" type="button" class="btn btn-info godetails" data-id='<?php echo $idCreditoPOST; ?>'>
                                <i class='fa fa-chevron-left fa-lg margin-right-xs'></i>
                                <span>Volver al credito actual</span>                     
                            </button>                                                               
                        </div>
                    </div>

                </div>
            </div>
        </section>
        
        
        <?php } ?>
        
    </div>
    
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
    
    
</div>

<?php echo _JSFILESLAYOUT_ ?>
<script src="../../appweb/plugins/misc/jquery.redirect.js"></script>    
<!---fileimput---->    
<script src="../../appweb/plugins/fileimput/plugins/sortable.js" type="text/javascript"></script>        
<script src="../../appweb/plugins/fileimput/fileinput.js" type="text/javascript"></script>        
<script src="../../appweb/plugins/fileimput/themes/fa/theme.js"></script>    
<script src="../../appweb/plugins/fileimput/locales/es.js"></script> 

<!-- validacion datos -->      
<script type="text/javascript" src="../../appweb/plugins/form-validator/jquery.form-validator.min.js"></script>    
<script type="text/javascript" src="../../appweb/js/to-deudorform.js"></script>
<script type="text/javascript" src="crud-newdeudor.js"></script>   
<script type="text/javascript" src="checkuser.js"></script>   
    
<script type="text/javascript" src="crud-user.js"></script>       
    
<!----maps---->
<script type="text/javascript" src="../../appweb/js/mapa.js"></script>
<script type="text/javascript" src="../../appweb/js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCgZFZPgdG2kRiFoE123zr-PA02WR_8yG4&sensor=true"></script>   
    
<!-- InputMask -->
<script src="../../appweb/plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../appweb/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../appweb/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../appweb/plugins/iCheck/icheck.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        
        //boton regresar
        $('button.godetails').each(function(){ 
            var detailurl = $(this).attr("data-href");
            var itemid = $(this).attr("data-id");

            $(this).click(function(){                   
                //window.location = detailurl+"?itemid_var="+itemid;
                $.redirect(detailurl,{ itemid_var: itemid}); 
            });
        });
        
        //boton cancelar
        $('.goback').each(function(){
            var linkURL = $(this).attr('data-href');            
            var titleEv = $(this).attr('title');
            var msjProd = $(this).attr('data-msj');            
            var itemid = $(this).attr('data-id'); 
            
            $(this).click(function(e) {
                e.preventDefault();                            
                //confiBack(linkURL, titleEv, msjProd, itemid);
                swal({
                    title: titleEv, 
                    text: '<span style=font-weight:bold;>' +msjProd + '</span>', 
                    type: 'warning',
                    //showConfirmButton: false,
                    showCancelButton: true,
                    closeOnConfirm: false,
                    closeOnCancel: true,
                    animation: false,
                    html: true
                }, function(isConfirm){
                      if (isConfirm) {
                        $.redirect(linkURL,{ itemid_var: itemid}); 
                      } else {
                        return false;	
                      }
                });
            });
        });
        
        
    });
    
    $(document).ready(function() {   
      
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });    
        /*$('input[name="cedulaform"]').inputmask({
            mask: "[9][9.]999.999[.999]",
            greedy: false,
            skipOptionalPartCharacter: " ",
        });*/
        
        $('input[name="cedulaform"]').keyup(function (){
         this.value = (this.value + '').replace(/[^0-9]/g, '');
        });
        
        $('input[name="Nacimientoform"]').inputmask({
            mask: "99/99/9999",
            alias: "dd/mm/yyyy"
        });
        
        $('input[name="Telefonoform"]').inputmask({
            mask: "(9[9]) 999-9999",
            greedy: false,
            skipOptionalPartCharacter: " ",
        });
        
        $('input[name="Telefono1form"]').inputmask({
            mask: "(9[9]) 999-9999",
            greedy: false,
            skipOptionalPartCharacter: " ",
        });
        $('input[name="Telefono2form"]').inputmask({
            mask: "999 999-9999",
            greedy: false,
            skipOptionalPartCharacter: " ",
        });
        $('input[name="existTelefono1form"]').inputmask({
             mask: "(9[9]) 999-9999",
            greedy: false,
            skipOptionalPartCharacter: " ",
        });
        $('input[name="existTelefono2form"]').inputmask({
            mask: "999 999-9999",
            greedy: false,
            skipOptionalPartCharacter: " ",
        });
        
        
        
        //CIUDAD DONDE NACIO
        $("#ciudadnace").hide();
        $(".deptonacelist").change(function(){
            var id=$(this).val();
            var dataString = 'depto='+ id;

            /*if($("#selectciudadnace").find(".ok").length){
                $("#selectciudadnace"+" .ok").remove();
                $("#selectciudadnace"+" .loader").remove();                    
                $("#selectciudadnace").append("<div class='loader'><img src='../../../../appweb/img/loadingcart.gif'/></div>").fadeIn("slow");
            }else{
                $("#selectciudadnace").append("<div class='loader'><img src='../../../../appweb/img/loadingcart.gif'/></div>").fadeIn("slow");                

            }*/
            $('#selectciudadnace').append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');

            $.ajax({
                type: "POST",
                url: "../../appweb/inc/select-ciudadescolombia.php",
                data: dataString,
                cache: false,
                success: function(html){  
                    $("#selectciudadnace"+" .loaderrow").fadeOut(function(){                            
                        //$("#ciudadnace").show();   
                        //$(".ciudadnacelist").html(html);
                        if($(".ciudadnacelist").html(html)){
                            $("#ciudadnace").fadeIn();   
                        }
                    });                                                                      
                } 
            });
        });
        
        
        //CIUDAD DONDE TRABAJA
        $("#ciudadtrabaja").hide();
        $(".deptotrabajalist").change(function(){
            var id=$(this).val();
            var dataString = 'depto='+ id;

            /*if($("#selectciudadnace").find(".ok").length){
                $("#selectciudadnace"+" .ok").remove();
                $("#selectciudadnace"+" .loader").remove();                    
                $("#selectciudadnace").append("<div class='loader'><img src='../../../../appweb/img/loadingcart.gif'/></div>").fadeIn("slow");
            }else{
                $("#selectciudadnace").append("<div class='loader'><img src='../../../../appweb/img/loadingcart.gif'/></div>").fadeIn("slow");                

            }*/
            $('#selectciudadtrabaja').append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');

            $.ajax({
                type: "POST",
                url: "../../appweb/inc/select-ciudadescolombia.php",
                data: dataString,
                cache: false,
                success: function(html){  
                    $("#selectciudadtrabaja"+" .loaderrow").fadeOut(function(){                            
                        //$("#ciudadnace").show();   
                        //$(".ciudadnacelist").html(html);
                        if($(".ciudadtrabajalist").html(html)){
                            $("#ciudadtrabaja").fadeIn();   
                        }
                    });                                                                      
                } 
            });
        });
        
        //EDITUSEREXISTE
        $(".userexiste").hide();
        
        //ACTIVA EDITA GEODOMICILIO        
        $("#canceleditgeodomicilio").hide();
        
        $("#editgeodomicilio").click(function(){
            $(this).hide();
            $("#canceleditgeodomicilio").show();
            $("#wrappgeo").fadeIn();    
        });
        
        $("#canceleditgeodomicilio").click(function(){
            $(this).hide();
            $("#editgeodomicilio").show();
            $("#wrappgeo").hide();    
        });  
    });  
    
    /*
    *SI EL USUARIO EXISTE
    *OPCION ADICIONAR REFERENCIA
    */
    $('#addrefexist').click(function(){  
        
        var field = $(this);
        var parent = "userexist-bartbtn";
        var datafield = $(this).attr('data-field');
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        var paththisfile = $("#paththisfile").val();
                                      
        var idref = $("input[name='itemexistform']").val();
        var creditoform = $("input[name='creditoform']").val();
                        
        //$('#'+parent).append('<div class="loader"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        if($('#'+parent).find(".loader").length){            
            $('#'+parent+" .loader").remove();            
        }else{
            $('#'+parent).append("<div class='loader text-center'><img src='../../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
        }
        
        var dataString = 'itemcodeudor='+idref+'&itemcredito='+creditoform+'&fieldedit='+$(this).attr('data-field');

        //console.log(dataString);

        $.ajax({
            type: "POST",
            url: "../../appweb/inc/valida-new-deudor.php",
            data: dataString,
            success: function(data) {
                //field.val(data);
                var response = JSON.parse(data);
                                
                //console.log(existeJSON);
                if (response['error']) {
                    
                    $("#response"+datafield).fadeOut();
                    $('#'+parent+' .loader').fadeOut();
                    
                    var errresponse = response["error"];
                    $("#err"+datafield).html("<div class='alert alert-default alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+errresponse+"</div>").fadeIn("slow"); 
                    
                }else{
                                        
                    $('#'+parent+' .loader').fadeOut(function(){
                        $("#addrefexist").fadeOut();
                        $("#err"+datafield).fadeOut();
                        $("#response"+datafield).fadeIn();

                    });
                }
            }
        });
    });
   
</script>     
</body>
</html>
