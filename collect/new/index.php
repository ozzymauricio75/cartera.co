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
                <span class="text-size-x6">Cobradores</span> / Nuevo Cobrador
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
        
        <?php if($statusCancel == ""){ ?>
        
        
        <section class="content maxwidth-usersforms margin-bottom-lg">            
            <form action="" id="cobradorform" autocomplete="off" >
            <div class="">
                <div class="row wrapdivsection">
                    <div class="col-xs-12 col-sm-4">                        
                        <h3>Cédula</h3>
                        <p class="help-block"></p>
                    </div>
                    <div class="col-xs-12 col-sm-8 ">
                        <small for="cedulaform" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                        <div class="form-group" id="cobradordb">
                            
                            <input type="number" id="cedulaform" name="cedulaform" class="form-control checkuser" value ="" placeholder="Número identidad" data-field="cobradordb" data-user="<?php echo $idSSUser; ?>">     
                        </div>
                        <div id="responsecobradordb"></div>
                        <div id="errcobradordb"></div>
                    </div>
                    <hr class="linesection"/>
                </div>
                
                        
                                                           
                <div class="wrapinfoform">
                
                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">                        
                            <h3>Información personal</h3>
                            <p class="help-block"></p>
                        </div>
                        <div class="col-xs-12 col-sm-8 ">                             
                            <div class="form-group">
                                <small for="nombre1form" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                <input type="text" id="nombre1form" name="nombre1form" class="form-control" value ="" placeholder="Nombre completo cobrador"  >

                                <!--data-toggle="tooltip" data-placement="left" title="Escribe tu primer nombre, usa sólo letras"-->

                            </div>
                            
                            <div class="form-group">               
                                <div class="form-group" id="emailcobradordb">
                                    <input type="email" id="Emailform" name="Emailform" class="form-control checkemailuser" value ="" placeholder="Email" data-field="emailcobradordb" data-user="<?php echo $idSSUser; ?>">     
                                </div>
                                <div id="responseemailcobradordb"></div>
                                <div id="erremailcobradordb"></div>

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
                            
                            <h4>Ubicación residencial</h4>
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
                
                </div><!--//FIN[.wrapinfoform]//-->
                
                
                
                <div class="wrapinfoform">
                    
                    <div class="col-xs-12 col-sm-4">                        
                            <h3>Información de cuenta</h3>
                            <p class="help-block"></p>
                        </div>
                        <div class="col-xs-12 col-sm-8 ">                        
                            <h4>Nombre de usuario</h4>
                            <div class="form-group" id="nicknamecobradordb">
                                <small for="userform" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                <input type="text" id="userform" name="userform" class="form-control checknickname" value ="" placeholder="Usuario"  data-field="nicknamecobradordb" data-user="<?php echo $idSSUser; ?>">
                                
                            </div>
                            <div id="responsenicknamecobradordb"></div>
                            <div id="errnicknamecobradordb"></div>
                            
                            
                            <h4>Contraseña</h4>
                            <div class="form-group">
                                <small for="passform" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                <input type="text" id="passform" name="passform" class="form-control" value ="" placeholder="Contraseña"  >
                            </div>
                                                                                     
                        </div>  
                        <hr class="linesection"/>
                    </div>
           
                </div><!--FIN[.wrapinfoform]-->
                    
                <div class="row">
                    <div class="col-xs-12">
                        <div id="wrapadditem"></div>
                        <div id="erradditem"></div>       
                        <nav class="">
                            <div id="left-barbtn" class="nav navbar-nav padd-verti-xs" style="display:none;"></div>
                            <div id="right-bartbtn" class="nav navbar-nav navbar-right margin-right-md padd-verti-xs">
                                <a href="<?php echo $pathmm."/collect/new/?trash=ok"; ?>" class="btn btn-default trashtobtn" name="" title="Eliminar Cobrador" data-msj="Perderás toda la información que hayas creado para este cobrador. Deseas continuar?" data-remsj="">
                                    <i class='fa fa-times fa-lg margin-right-xs'></i>
                                    <span>Cancelar</span>                        
                                </a> 
                                <button type="button" class="btn btn-info margin-hori-xs " id="additembtn">                                    
                                    <i class='fa fa-save fa-lg margin-right-xs'></i>
                                    <span>Guardar</span>                     
                                </button>                                                               
                            </div>
                            <div id="userexist-bartbtn" class="nav navbar-nav navbar-right margin-right-md padd-verti-xs" style="display:none;"></div>
                        </nav>
                    </div>
                </div>
                
                <div class="row">                              
                    <input type="hidden" name="itemexistform" id="itemexistform" value="">                     
                    <input type="hidden" name="codeuserform" id="codeuserform" value="<?php echo $idSSUser; ?>">
                    <input type="hidden" name="pseudouser" id="pseudouser" value="<?php echo $pseudoSSUser; ?>">
                    <?php echo "<input id='pathfile' type='hidden' value='".$pathmm."'/>"; ?>
                    <?php echo "<input id='pathdir' type='hidden' value='".$collectDir."'/>"; ?>
                    <?php echo "<input id='paththisfile' type='hidden' value='".$pathFile."/'/>"; ?>
                </div>
                
                    
                
            </div>
            </form>
        </section>    
                                       
        <?php }else{ ?>
        
        <section class="content ">                    
            <div class="box50  padd-verti-lg">
                <div class="alert alert-dismissible ">
                    <div class="media">
                        <div class=" media-left">
                            <i class="fa fa-bell-o fa-4x text-muted"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="no-padmarg">Hola!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                El registro de este cobrador fue cancelado de tu sistema correctamente.
                            </p>              
                            <p style="font-size:1.232em; line-height:1;"> Qué deseas hacer ahora?</p>
                        </div>

                    </div>                    
                </div>
                <div class="margin-verti-xs text-center">
                    <div class="btn-toolbar" role="toolbar" style="display:table; margin:0 auto; text-center;">
                        <div class="btn-group margin-bottom-xs">
                            <a href="<?php echo $pathmm."/collect/collectors.php"; ?>" type="button" class="btn btn-default ">
                                <i class='fa fa-th-list fa-lg margin-right-xs'></i>
                                <span>lista de cobradores</span>                        
                            </a> 
                        </div>
                    
                        <div class="btn-group margin-bottom-xs">
                            <a href="<?php echo $pathmm."/collect/new/"; ?>" type="button" class="btn btn-info ">
                                <i class='fa fa-plus fa-lg margin-right-xs'></i>
                                <span>Agregar cobrador</span>                     
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
<script type="text/javascript" src="crud-newcobrador.js"></script>   
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

            $('#selectciudadnace').append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');

            $.ajax({
                type: "POST",
                url: "../../appweb/inc/select-ciudadescolombia.php",
                data: dataString,
                cache: false,
                success: function(html){  
                    $("#selectciudadnace"+" .loaderrow").fadeOut(function(){             
                        
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
     
</script>     
</body>
</html>
