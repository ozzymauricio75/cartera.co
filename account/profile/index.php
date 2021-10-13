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
        
        $db->where ('id_acreedor', $idItemPOST);                          
        $queryUsuario = $db->getOne('acreedor_tbl', 'id_acreedor, primer_nombre_acreedor, primer_apellido_acreedor, cedula_acreedor, email_acreedor, tel_uno_acreedor, tel_dos_acreedor, dir_establecimiento_acreedor, ciudad_establecimiento_acreedor, fecha_alta_acreedor'); 
        
        //informacion personal
        $idStatusUsuario = empty($queryUsuario['id_status_perfil_acreedor'])? "" : $queryUsuario['id_status_perfil_acreedor']; 
        $idUsuario = empty($queryUsuario['id_acreedor'])? "" : $queryUsuario['id_acreedor'];
        $nombreUsuario = empty($queryUsuario['primer_nombre_acreedor'])? "" : $queryUsuario['primer_nombre_acreedor'];
        $apellidoUsuario = empty($queryUsuario['primer_apellido_acreedor'])? "" : $queryUsuario['primer_apellido_acreedor'];
        $cedulaUsuario = empty($queryUsuario['cedula_acreedor'])? "" : $queryUsuario['cedula_acreedor'];                
        
        //informacion de contacto
        $mailUsuario = empty($queryUsuario['email_acreedor'])? "" : $queryUsuario['email_acreedor'];
        $tel1Usuario = empty($queryUsuario['tel_uno_acreedor'])? "" : $queryUsuario['tel_uno_acreedor'];
        $tel2Usuario = empty($queryUsuario['tel_dos_acreedor'])? "" : $queryUsuario['tel_dos_acreedor'];
        $dirUsuario = empty($queryUsuario['dir_establecimiento_acreedor'])? "" : $queryUsuario['dir_establecimiento_acreedor'];        
        $ciudadUsuario = empty($queryUsuario['ciudad_establecimiento_acreedor'])? "" : $queryUsuario['ciudad_establecimiento_acreedor'];
        
        //informacion cuenta                        
        $fechaAltaUsuario = empty($queryUsuario['fecha_alta_acreedor'])? "" : date("d/m/Y", strtotime($queryUsuario['fecha_alta_acreedor']));
        
        
        
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
                    <li role="presentation" class="active">
                        <a href="#" class="text-center">
                            <i class="fa fa-user fa-2x"></i>
                            <span style="display:block;">Perfil</span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="../security/" class="text-center">
                            <i class="fa fa-lock fa-2x"></i>
                            <span style="display:block;">Seguridad</span>
                        </a>
                    </li>
                </ul>

            </div>

            <section class="content maxwidth-usersforms margin-bottom-lg ">            

                <form action="" id="userform" autocomplete="off" >
                <div class="">

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
                                    <input type="text" id="nombre1form" name="nombre1form" class="form-control" value ="<?php echo $nombreUsuario; ?>" placeholder="Nombre completo Usuario" data-post="<?php echo $idUsuario; ?>" data-field="editnombre">
                                </div>
                                <div id="erreditnombre"></div>

                                <div class="form-group" id="editapellido">
                                    <small for="apellido1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                    <label>Apellido </label>
                                    <input type="text" id="apellido1form" name="apellido1form" class="form-control" value ="<?php echo $apellidoUsuario; ?>" placeholder="Apellido" data-post="<?php echo $idUsuario; ?>" data-field="editapellido">
                                </div>
                                <div id="erreditapellido"></div>
                                
                                
                                <div class="form-group" id="usuariodb">
                                    <label>Cedula</label>
                                    <input type="number" id="cedulaform" name="cedulaform" class="form-control " value ="<?php echo $cedulaUsuario; ?>" disabled>     
                                </div>

                            </div>      
                            <hr class="linesection"/>
                        </div>

                    </div><!--//FIN[.wrapinfoform]//-->



                    <div class="wrapinfoform margin-bottom-xs">
                        <div class="row wrapdivsection">

                            <div class="col-xs-12 col-sm-4">                        
                                <h3>Información de contacto</h3>
                                <p class="help-block"></p>
                            </div>
                            <div class="col-xs-12 col-sm-8 "> 

                                <div class="form-group">               
                                    <div class="form-group" id="editaemail">
                                        <label>Email </label>
                                        <input type="email" id="Emailform" name="Emailform" class="form-control checkemailuser" value ="<?php echo $mailUsuario; ?>" placeholder="Email" data-post="<?php echo $idUsuario; ?>" data-field="editaemail" >     
                                    </div>
                                    <div id="responseeditaemail"></div>
                                    <div id="erreditaemail"></div>

                                </div>

                                <div class="form-group" id="editatel1">
                                    <small for="Telefono1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Obligatorio al menos uno de los dos teléfonos</small>
                                    <label>Teléfono </label>
                                    <input type="tel" id="Telefono1form" name="Telefono1form" class="form-control" value ="<?php echo $tel1Usuario; ?>" placeholder="Telefono Fijo" data-post="<?php echo $idUsuario; ?>" data-field="editatel1">     
                                </div>
                                <div id="erreditatel1"></div>


                                <div class="form-group" id="editatel2">
                                    <label>Teléfono </label>
                                    <input type="tel" name="Telefono2form" class="form-control" value ="<?php echo $tel2Usuario; ?>" placeholder="Teléfono Celular" data-post="<?php echo $idUsuario; ?>" data-field="editatel2">     
                                </div>
                                <div id="erreditatel2"></div>

                                <div class="form-group" id="editadireccion">
                                    <label>Dirección </label>
                                    <input type="text" name="DireccionResidenciaform" class="form-control" value ="<?php echo $dirUsuario; ?>" placeholder="Dirección" data-post="<?php echo $idUsuario; ?>" data-field="editadireccion">
                                </div>
                                <div id="erreditadireccion"></div>

                                <div class="form-group"  id="wpeditaciudad">
                                    <label>Ciudad </label>
                                    <div class="input-group">

                                        <input type="text" id="ciudadform" name="ciudadform" class="form-control" value ="<?php echo $ciudadUsuario; ?>" placeholder="Ciudad" disabled>     
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
                                            <select class="ciudadtrabajalist form-control" name="CiudadEmpresaform" data-post="<?php echo $idUsuario; ?>" data-field="editaciudad"></select>
                                        </div>   
                                        <div id="erreditaciudad"></div>
                                    </div>

                                </div> 
                            </div>      
                            <hr class="linesection"/>
                        </div>
                    </div>

                    <div class="wrapinfoform  margin-bottom-xs">
                        <div class="row wrapdivsection">                            
                            <div class="col-xs-12 col-sm-4">                        
                                <h3>Activo desde</h3>
                                <p class="help-block"></p>
                            </div>
                            <div class="col-xs-12 col-sm-8 ">    

                                <p class="bg-success text-success padd-verti-xs padd-hori-md">
                                    <i class="fa fa-calendar fa-2x margin-right-xs"></i>
                                    <span class="text-size-x4"><?php echo $fechaAltaUsuario; ?></span>
                                </p>

                            </div>      
                       
                        </div>
                    </div><!--//FIN[.wrapinfoform]//-->

                    <div class="row">           
                        <input type="hidden" name="codeuserform" id="codeuserform" value="<?php echo $idSSUser; ?>">
                    </div>

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
<!---fileimput---->    
<script src="../../appweb/plugins/fileimput/plugins/sortable.js" type="text/javascript"></script>        
<script src="../../appweb/plugins/fileimput/fileinput.js" type="text/javascript"></script>        
<script src="../../appweb/plugins/fileimput/themes/fa/theme.js"></script>    
<script src="../../appweb/plugins/fileimput/locales/es.js"></script> 

<!-- validacion datos -->      
<script type="text/javascript" src="../../appweb/plugins/form-validator/jquery.form-validator.min.js"></script>    
<script type="text/javascript" src="../../appweb/js/to-userform.js"></script>
<script type="text/javascript" src="edit-profile-functions.js"></script>
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
