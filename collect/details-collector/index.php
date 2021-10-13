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


$loockVar = "false";
$idItemPOST = "";
$datasCuotasRuta = array();
if(isset($_GET['itemid_var']) && $_GET['itemid_var'] != ""){
    
    //Validacion variable POST
    $idItemPOST = $_GET['itemid_var'];
    $valida_idItemPOST = validaInteger($idItemPOST, "1");
    
    //Validacion TRUE
    if($valida_idItemPOST === true){   
        $loockVar = "true";  
        //SELECT `id_cobrador`, `id_acreedor`, `id_status_cobrador`, `nombre_cobrador`, `cedula_cobrador`, `mail_cobrador`, `nickname_cobrador`, `pass_cobrador`, `pass_humano_cobrador`, `tel_uno_cobrador`, `tel_dos_cobrador`, `direccion_cobrador`, `barrio_cobrador`, `ciudad_cobrador`, `estado_cobrador`, `pais_cobrador`, `foto_cobrador`, `fecha_alta_cobrador`, `tag_seccion_plataforma` FROM `cobrador_tbl` WHERE 1
        $db->where("id_cobrador", $idItemPOST);
        $queryCobrador = $db->getOne("cobrador_tbl");
        
        //informacion personal
        $idStatusCobrador = empty($queryCobrador['id_status_cobrador'])? "" : $queryCobrador['id_status_cobrador']; 
        $idCobrador = empty($queryCobrador['id_cobrador'])? "" : $queryCobrador['id_cobrador'];
        $nombreCobrador = empty($queryCobrador['nombre_cobrador'])? "" : $queryCobrador['nombre_cobrador'];
        $cedulaCobrador = empty($queryCobrador['cedula_cobrador'])? "" : $queryCobrador['cedula_cobrador'];                
        
        //informacion de contacto
        $mailCobrador = empty($queryCobrador['mail_cobrador'])? "" : $queryCobrador['mail_cobrador'];
        $tel1Cobrador = empty($queryCobrador['tel_uno_cobrador'])? "" : $queryCobrador['tel_uno_cobrador'];
        $tel2Cobrador = empty($queryCobrador['tel_dos_cobrador'])? "" : $queryCobrador['tel_dos_cobrador'];
        $dirCobrador = empty($queryCobrador['direccion_cobrador'])? "" : $queryCobrador['direccion_cobrador'];
        $barrioCobrador = empty($queryCobrador['barrio_cobrador'])? "" : $queryCobrador['barrio_cobrador'];
        $ciudadCobrador = empty($queryCobrador['ciudad_cobrador'])? "" : $queryCobrador['ciudad_cobrador'];
        
        //informacion cuenta
        $db->where("id_usuario_plataforma", $idItemPOST);
        $db->where("tag_seccion_plataforma", "cobrador");
        $queryUser = $db->getOne("usuario_tbl");
        
        $nicknameCobrador = empty($queryUser['nickname_usuario'])? "" : $queryUser['nickname_usuario'];
        $passCobrador = empty($queryUser['pass_humano_usuario'])? "" : $queryUser['pass_humano_usuario'];
        
        //definir estrado
        $statusLyt = "";
        $statusNombre = "";
        $statusNombre = queryStatusGB($idStatusCobrador);
        
        switch($idStatusCobrador){
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

$rootLevel = "cobrar";
$sectionLevel = "cobradores";
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
                <span class="text-size-x6">Cobradores</span> / Detalles
            </h1>
            <a href="<?php echo $pathmm."/collect/collectors.php"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de cobradores
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
        
        <section class="content " id="confirmdelete" style="display:none;">                    
            <div class="box50">
                <div class="alert">
                    <div class="media text-muted">
                        <div class=" media-left">
                            <i class="fa fa-info-circle fa-4x"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Hola!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                Este cobrador fue eliminado correctamente
                            </p>              
                            <p style="font-size:1.232em; line-height:1;"> ¿Qué deseas hacer ahora?</p>
                        </div>
                    </div>                    
                </div>
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group">
                            <a href="<?php echo $pathmm."/collect/collectors.php"; ?>" type="button" class="btn btn-default">
                                <i class='fa fa-th-list fa-lg margin-right-xs'></i>
                                <span>Lista de cobradores</span>        
                            </a>                         
                            <a href="<?php echo $pathmm."/collect/new/"; ?>" type="button" class="btn btn-success">
                                <i class='fa fa-plus fa-lg margin-right-xs'></i>
                                <span>Crear cobrador</span>        
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <div id="wrappcontent">
        
        <div class="row clearfix maxwidth-usersforms margin-bottom">
            
            <div class="btn-toolbar" role="toolbar" aria-label="...">
                <div class="btn-group btn-group-xs pull-right" role="group" aria-label="...">

                    <?php if($idStatusCobrador == "2"){ ?>

                    <button type="button" class="btn btn-app bg-teal adminuserbtn" id="suspendeuserbtn" data-name="<?php echo $nombreCobrador; ?>" data-title="Activar cobrador" data-msj="Estas a punto de activar este cobrador, deseas continuar?" data-post="<?php echo $idCobrador; ?>" data-field="activaitem" data-value="1">
                        <i class="fa fa-check fa-lg"></i>
                        Activar
                    </button>

                    <?php }else if($idStatusCobrador == "1"){ ?>

                    <button type="button" class="btn btn-app bg-red-active adminuserbtn" id="suspendeuserbtn" data-name="<?php echo $nombreCobrador; ?>" data-title="Suspender cobrador" data-msj="Estas a punto de suspender este cobrador. No podra acceder a su area de usuario, deseas continuar?" data-post="<?php echo $idCobrador; ?>" data-field="stopitem" data-value="2">
                        <i class="fa fa-ban fa-lg"></i>
                        Suspender
                    </button>

                    <?php } ?>

                    <button type="button" class="btn btn-app btn-default adminuserbtn" id="deleteuserbtn" data-name="<?php echo $nombreCobrador; ?>" data-title="Elimnar cobrador" data-msj="Estas a punto de eliminar este cobrador, deseas continuar?" data-post="<?php echo $idCobrador; ?>" data-field="deleteitem" data-value="3">
                        <i class="fa fa-trash fa-lg"></i>
                        Eliminar
                    </button>                        
                </div>
            </div>
            
            <div id="erradminuser" class=" clearfix"></div>
            <div id="successadminuser" class=" clearfix"></div>
            
        </div>
                                
        <section class="content maxwidth-usersforms margin-bottom-lg ">            
            
            <form action="" id="cobradorform" autocomplete="off" >
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
                            <h3>Información personal</h3>
                            <p class="help-block"></p>
                        </div>
                        <div class="col-xs-12 col-sm-8 ">                             
                            <div class="form-group" id="editnombre">
                                <small for="nombre1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                <label>Nombre </label>
                                <input type="text" id="nombre1form" name="nombre1form" class="form-control" value ="<?php echo $nombreCobrador; ?>" placeholder="Nombre completo cobrador" data-post="<?php echo $idCobrador; ?>" data-field="editnombre">
                            </div>
                            <div id="erreditnombre"></div>
                            
                            <div class="form-group" id="cobradordb">
                                <label>Cedula</label>
                                <input type="number" id="cedulaform" name="cedulaform" class="form-control " value ="<?php echo $cedulaCobrador; ?>" disabled>     
                            </div>
                            
                        </div>      
                        <hr class="linesection"/>
                    </div>
                
                </div><!--//FIN[.wrapinfoform]//-->
                
                
                
                <div class="wrapinfoform margin-bottom-xs">
                    
                    <div class="col-xs-12 col-sm-4">                        
                            <h3>Información de contacto</h3>
                            <p class="help-block"></p>
                        </div>
                        <div class="col-xs-12 col-sm-8 "> 
                            
                            <div class="form-group">               
                                <div class="form-group" id="editaemail">
                                    <label>Email </label>
                                    <input type="email" id="Emailform" name="Emailform" class="form-control checkemailuser" value ="<?php echo $mailCobrador; ?>" placeholder="Email" data-post="<?php echo $idCobrador; ?>" data-field="editaemail" >     
                                </div>
                                <div id="responseeditaemail"></div>
                                <div id="erreditaemail"></div>

                            </div>
                            
                            <div class="form-group" id="editatel1">
                                <small for="Telefono1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Obligatorio al menos uno de los dos teléfonos</small>
                                <label>Teléfono </label>
                                <input type="tel" id="Telefono1form" name="Telefono1form" class="form-control" value ="<?php echo $tel1Cobrador; ?>" placeholder="Telefono Fijo" data-post="<?php echo $idCobrador; ?>" data-field="editatel1">     
                            </div>
                            <div id="erreditatel1"></div>
                            
                            
                            <div class="form-group" id="editatel2">
                                <label>Teléfono </label>
                                <input type="tel" name="Telefono2form" class="form-control" value ="<?php echo $tel2Cobrador; ?>" placeholder="Teléfono Celular" data-post="<?php echo $idCobrador; ?>" data-field="editatel2">     
                            </div>
                            <div id="erreditatel2"></div>
                            
                            <div class="form-group" id="editadireccion">
                                <label>Dirección </label>
                                <input type="text" name="DireccionResidenciaform" class="form-control" value ="<?php echo $dirCobrador; ?>" placeholder="Dirección" data-post="<?php echo $idCobrador; ?>" data-field="editadireccion">
                            </div>
                            <div id="erreditadireccion"></div>

                            <div class="form-group" id="editabarrio">
                                <label>Barrio </label>
                                <input type="text" name="Barrioform" class="form-control" value ="<?php echo $barrioCobrador; ?>" placeholder="Barrio" data-post="<?php echo $idCobrador; ?>" data-field="editabarrio">     
                            </div>
                            <div id="erreditabarrio"></div>
                            
                            <div class="form-group"  id="wpeditaciudad">
                                <label>Ciudad </label>
                                <div class="input-group">

                                    <input type="text" id="ciudadform" name="ciudadform" class="form-control" value ="<?php echo $ciudadCobrador; ?>" placeholder="Ciudad" disabled>     
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-lg editfieldbtn" type="button" data-this="editaciudad"><i class="fa fa-pencil fa-lg" ></i></button>
                                    </span>
                                </div>
                            </div>
                                                                                                                                  
                            <div class="wefield" id="weeditaciudad">
                                
                                <h4>
                                    <button type="button" class="btn btn-default pull-right btn-sm margin-bottom-xs canceleditfieldbtn" data-this="editaciudad"> 
                                        <i class="fa fa-times"></i> Cancelar
                                    </button>
                                    Editar Ciudad
                                </h4>
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
                                    <div class="form-group" id="editaciudad">
                                        <select class="ciudadtrabajalist form-control" name="CiudadEmpresaform" data-post="<?php echo $idCobrador; ?>" data-field="editaciudad"></select>
                                    </div>   
                                    <div id="erreditaciudad"></div>
                                </div>
                                
                            </div> 
                        </div>      
                        <hr class="linesection"/>
                    </div>
                
                </div><!--//FIN[.wrapinfoform]//-->
                
                
                
                <div class="wrapinfoform ">
                    
                    <div class="col-xs-12 col-sm-4">                        
                            <h3>Información de cuenta</h3>
                            <p class="help-block"></p>
                    </div>
                    <div class="col-xs-12 col-sm-8 ">                        

                        <div class="form-group">
                            <small for="userform" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                            <label>Nombre de usuario</label>
                            <input type="text" id="userform" name="userform" class="form-control" value ="<?php echo $nicknameCobrador; ?>" placeholder="Usuario"  disabled>
                        </div>
                        
                        <div class="form-group" id="editapass">
                            <small for="passform" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                            <label>Contraseña</label>
                            <input type="text" id="passform" name="passform" class="form-control" value ="<?php echo $passCobrador; ?>" placeholder="Contraseña"  data-post="<?php echo $idCobrador; ?>" data-field="editapass">
                        </div>
                        <div id="erreditapass"></div>

                    </div>  
                    
                </div><!--FIN[.wrapinfoform]-->
                                                    
                <div class="row">                              
                    <input type="hidden" name="itemexistform" id="itemexistform" value="">
                    <input type="hidden" name="codeitemform" id="codeitemform" value="<?php echo $codeNewProd; ?>">  
                    <input type="hidden" name="codeuserform" id="codeuserform" value="<?php echo $idSSUser; ?>">
                    <input type="hidden" name="pseudouser" id="pseudouser" value="<?php echo $pseudoSSUser; ?>">
                    <?php echo "<input id='pathfile' type='hidden' value='".$pathmm."'/>"; ?>
                    <?php echo "<input id='pathdir' type='hidden' value='".$collectDir."'/>"; ?>
                    <?php echo "<input id='paththisfile' type='hidden' value='".$pathFile."/'/>"; ?>
                </div>
                
                    
                
            </div>
            </form>
        </section> 
    
        </div>
                        

        <?php }else{ ?>
        
        <section class="content ">                    
            <div class="box50">
                <div class="alert">
                    <div class="media text-muted">
                        <div class=" media-left">
                            <i class="fa fa-unlink fa-4x"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Oops!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                No encontramos o fue eliminada el COBRADOR que deseas visualizar
                            </p>              
                            <p style="font-size:1.232em; line-height:1;"> Asegurate que seleccionaste el cobrador correcto, e intentalo de nuevo</p>
                        </div>

                    </div>                    
                </div>
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group text-center">
                            <a href="<?php echo $pathmm."/collect/collectors.php"; ?>" type="button" class="btn btn-default">
                                <i class='fa fa-th-list fa-lg margin-right-xs'></i>
                                <span>Lista de cobradores</span>                        
                            </a> 
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
<!---fileimput---->    
<script src="../../appweb/plugins/fileimput/plugins/sortable.js" type="text/javascript"></script>        
<script src="../../appweb/plugins/fileimput/fileinput.js" type="text/javascript"></script>        
<script src="../../appweb/plugins/fileimput/themes/fa/theme.js"></script>    
<script src="../../appweb/plugins/fileimput/locales/es.js"></script> 

<!-- validacion datos -->      
<script type="text/javascript" src="../../appweb/plugins/form-validator/jquery.form-validator.min.js"></script>    
<script type="text/javascript" src="../../appweb/js/to-cobrador.js"></script>
<script type="text/javascript" src="edit-collector-functions.js"></script>
<script type="text/javascript" src="checkuser.js"></script>      
    
<!-- InputMask -->
<script src="../../appweb/plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../appweb/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../appweb/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../appweb/plugins/iCheck/icheck.min.js"></script>

<script type="text/javascript">
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
                
                            
        //CIUDAD DONDE TRABAJA
        $("#ciudadtrabaja").hide();
        $(".deptotrabajalist").change(function(){
            var id=$(this).val();
            var dataString = 'depto='+ id;

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
        
        
        //EDITAR CIUDAD
        $(".wefield").hide();
    
        $('button.editfieldbtn').each(function(){
            var field = $(this).attr("data-this");  
            //var parent = field.parent().attr("id");


            var wrapprint = $("#wp"+field).show(); 
            var wrapedit = $("#we"+field).hide();

            $(this).click(function(){
                wrapprint.hide();
                wrapedit.show();
            });


        });

        $('button.canceleditfieldbtn').each(function(){
            var field = $(this).attr("data-this");  
            //var parent = field.parent().attr("id");


            var wrapprint = $("#wp"+field).show(); 
            var wrapedit = $("#we"+field).hide();

            $(this).click(function(){
                wrapprint.show();
                wrapedit.hide();
            });


        });
        
        
    });       
    
    
    
                        
         
</script>     
</body>
</html>
