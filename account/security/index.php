<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-tablas-complementarias.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>
<?php 

$idItemPOST = "";

if(isset($idSSUser) && $idSSUser != ""){
    
    //Validacion variable POST
    $idItemPOST = $idSSUser;
    $valida_idItemPOST = validaInteger($idItemPOST, "1");
    
    //Validacion TRUE
    if($valida_idItemPOST === true){   
        $db->where ('id_usuario_plataforma', $idItemPOST);                          
        $db->where ('tag_seccion_plataforma', "acreedor");    
        $queryUsuario = $db->getOne('usuario_tbl', 'id_usuario_plataforma, nickname_usuario, tag_seccion_plataforma, id_status_usuario'); 
                
        //informacion cuenta
        $idStatusUsuario = $queryUsuario['id_status_usuario'];
        $nicknameCobrador = empty($queryUsuario['nickname_usuario'])? "" : $queryUsuario['nickname_usuario'];
                
        //definir estrado
        $statusLyt = "";
        $statusNombre = "";
        $statusNombre = queryStatusGB($idStatusUsuario);
        
        switch($idStatusUsuario){
            case "1":
                $statusLyt .="<p class='bg-success text-success padd-verti-xs padd-hori-md'>";
                $statusLyt .="<i class='fa fa-check-circle fa-2x margin-right-xs'></i>";
                $statusLyt .="<span class='text-size-x4'>".$statusNombre."</span>";
                $statusLyt .="</p>";
            break;
            case "2":
                $statusLyt .="<p class='bg-danger text-danger padd-verti-xs padd-hori-md'>";
                $statusLyt .="<i class='fa fa-ban fa-2x margin-right-xs'></i>";
                $statusLyt .="<span class='text-size-x4'>".$statusNombre."</span>";
                $statusLyt .="</p>";
            break;
        }
    }
}


//***********
//SITE MAP
//***********

$rootLevel = "account";
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
    <?php echo _FAVICON_TOUCH_ ?>    
    <link rel="stylesheet" href="../../appweb/plugins/form-validator/theme-default.css"> 
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
                <span class="text-size-x6">Usuario</span> / Detalles
            </h1>
            <a href="<?php echo $pathmm."/home/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Volver
            </a>  
            
            
        </section>
        
               
        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
                       
        <div id="wrappcontent">
        
            <div class="row clearfix maxwidth-usersforms">

                <ul class="nav nav-tabs">
                    <li role="presentation">
                        <a href="../" class="text-center">
                            <i class="fa fa-toggle-on fa-2x"></i>
                            <span style="display:block;">Plataforma</span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="../profile/" class="text-center">
                            <i class="fa fa-user fa-2x"></i>
                            <span style="display:block;">Perfil</span>
                        </a>
                    </li>
                    <li role="presentation" class="active">
                        <a href="#" class="text-center">
                            <i class="fa fa-lock fa-2x"></i>
                            <span style="display:block;">Seguridad</span>
                        </a>
                    </li>
                </ul>

            </div>

            <section class="content maxwidth-usersforms margin-bottom-lg ">            

                <form id="userform" autocomplete="off" >
                <div class="">

                    <div class="wrapinfoform  margin-bottom-xs">

                        <div class="row wrapdivsection">
                            <div class="col-xs-12 col-sm-4">                        
                                <h3>Status</h3>
                                <p class="help-block"></p>
                            </div>
                            <div class="col-xs-12 col-sm-8 ">                             
                            <?php echo $statusLyt; ?>                               
                            </div>      
                            <hr class="linesection"/>
                        </div>

                    </div><!--//FIN[.wrapinfoform]//-->


                    <div class="wrapinfoform  margin-bottom-xs">
                        <div class="row wrapdivsection">
                            <div class="col-xs-12 col-sm-4">                        
                                    <h3>Usuario</h3>
                                    <p class="help-block"></p>
                            </div>
                            <div class="col-xs-12 col-sm-8 ">                        

                                <div class="form-group">                                
                                    <strong>Nombre de suario</strong>
                                    <input type="text" id="userform" name="userform" class="form-control" value ="<?php echo $nicknameCobrador; ?>" placeholder="Usuario"  disabled>
                                </div>

                            </div>  
                            <hr class="linesection"/>
                        </div>
                    </div><!--FIN[.wrapinfoform]-->

                    <div class="wrapinfoform">
                        <div class="row wrapdivsection">
                            <div class="col-xs-12 col-sm-4">                        
                                <h3>Cambiar contraseña</h3>
                                <p class="help-block"></p>
                            </div>
                            <div class="col-xs-12 col-sm-8 "> 

                                <div class="form-group">
                                    <small for="passform_confirmation" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                    <strong>Nueva contraseña</strong>
                                    <input type="text" id="passform_confirmation" name="passform_confirmation" class="form-control" value ="" placeholder="Escribe una contraseña">
                                </div>
                                <div class="form-group">
                                    <small for="passform" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                    <strong>Repetir Contraseña</strong>
                                    <input type="text" id="passform" name="passform" class="form-control" value ="" placeholder="Repite la contraseña"  >
                                </div>
                                <div class="form-group text-center" id="editapass">
                                    <button type="button" id="editpassbtn" class="btn btn-flat  bg-blue btn-sm" data-post="<?php echo $idSSUser; ?>" data-field="editapass">
                                        Guardar
                                        <i class="fa fa-save fa-lg margin-left-xs"></i>    
                                    </button>
                                </div>
                                <div id="erreditapass"></div>
                                <div id="responceeditapass"></div>
                            </div>
                        </div>      

                    </div><!--//FIN[.wrapinfoform]//-->                   
                </div>
                </form>
            </section> 
    
        </div>                               
        
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

<!-- validacion datos -->      
<script type="text/javascript" src="../../appweb/plugins/form-validator/jquery.form-validator.min.js"></script>    
<script type="text/javascript" src="../../appweb/js/to-userform.js"></script>
<script type="text/javascript" src="edit-security-functions.js"></script>

</body>
</html>
