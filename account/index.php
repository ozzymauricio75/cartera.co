<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-tablas-complementarias.php'; ?>
<?php require_once '../i18n-textsite.php'; ?>
<?php 

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
    <link rel="stylesheet" href="../appweb/plugins/form-validator/theme-default.css">     
    <!---switchmaster--->    
    <link rel="stylesheet" href="../appweb/plugins/switchmaster/css/bootstrap3/bootstrap-switch.min.css">                        
</head>
    
<?php echo LAYOUTOPTION ?><!---//print body tag--->    
    
<?php include '../appweb/tmplt/loadevent.php';  ?>   
 
<div class="wrapper">            
    <?php
    /*
    /
    ////HEADER
    /
    */
    ?>
    <?php include '../appweb/tmplt/header.php';  ?>           
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php include '../appweb/tmplt/side-mm.php';  ?>
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
                    <li role="presentation" class="active">
                        <a href="#" class="text-center">
                            <i class="fa fa-toggle-on fa-2x"></i>
                            <span style="display:block;">Plataforma</span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="profile/" class="text-center">
                            <i class="fa fa-user fa-2x"></i>
                            <span style="display:block;">Perfil</span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="security/" class="text-center">
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
                                <h3 class="no-padmarg">Jornada laboral</h3>
                                <p class="help-block">Define los días laborales de tu negocio. Así podrás gestionar las fechas de pago cuando realices el plan de pago para tus clientes. </p>
                                <p class="text-warning"><i class="fa fa-warning margin-right-xs"></i>Estos cambios no afectarán a planes de pago ya realizados</p>
                            </div>
                            <div class="col-xs-12 col-sm-8 ">                             
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <h5 class="text-primary padd-bottom-xs">Fechas de cobro diarias</h5>

                                        <div class=" margin-top-xs">

                                            <p>
                                                <span class="pull-right">
                                                    <input type="checkbox" data-size="mini" class="opcicustom " checked disabled>
                                                </span>
                                                <label>Lunes a Viernes</label>

                                            </p>
                                            <p>
                                                <span class="pull-right">
                                                    <input id="sabadodiaswitch" type="checkbox" data-size="mini" class="opcicustom " <?php echo $checkbox_sabado_dia; ?> />
                                                </span>
                                                <label>Sabados</label>

                                            </p>
                                            <p>
                                                <span class="pull-right">
                                                    <input id="domingodiaswitch" type="checkbox" data-size="mini" class="opcicustom " <?php echo $checkbox_domingo_dia; ?> />
                                                </span>
                                                <label>Domingos</label>

                                            </p>
                                            <p>
                                                <span class="pull-right">
                                                    <input id="festivodiaswitch" type="checkbox" data-size="mini" class="opcicustom " <?php echo $checkbox_festivo_dia; ?> />
                                                </span>
                                                <label>Festivos</label>

                                            </p>
                                        </div>
                                    </div>


                                    <div class="col-xs-12 col-sm-12">
                                        <h5 class="text-primary padd-bottom-xs hidden-xs">Fechas de cobro Semanales/Quincenales/Mensuales</h5>
                                        <h5 class="text-primary padd-bottom-xs visible-xs">fechas de cobro Semana<br>Quincena/Mes</h5>
                                        <div class=" margin-top-xs">

                                            <p>
                                                <span class="pull-right">
                                                    <input type="checkbox" data-size="mini" class="opcicustom " checked disabled>
                                                </span>
                                                <label>Lunes a Viernes</label>

                                            </p>
                                            <p>
                                                <span class="pull-right">
                                                    <input id="sabadoswitch" type="checkbox" data-size="mini" class="opcicustom " <?php echo $checkbox_sabado; ?> />
                                                </span>
                                                <label>Sabados</label>

                                            </p>
                                            <p>
                                                <span class="pull-right">
                                                    <input id="domingoswitch" type="checkbox" data-size="mini" class="opcicustom " <?php echo $checkbox_domingo; ?> />
                                                </span>
                                                <label>Domingos</label>

                                            </p>
                                            <p>
                                                <span class="pull-right">
                                                    <input id="festivoswitch" type="checkbox" data-size="mini" class="opcicustom " <?php echo $checkbox_festivos; ?> />
                                                </span>
                                                <label>Festivos</label>

                                            </p>
                                        </div>
                                    </div>
                                </div>      

                            </div>      
                            <hr class="linesection"/>
                        </div>

                    </div><!--//FIN[.wrapinfoform]//-->



                    <div class="wrapinfoform margin-bottom-xs">
                        <div class="row wrapdivsection">   
                            <div class="col-xs-12 col-sm-4">                        
                                <h3 class="no-padmarg">Caja menor</h3>
                                <p class="help-block">Permitirá que  cada día lleves control obligatorio sobre la gestion de tus prestamos y dinero disponible</p>
                            </div>
                            <div class="col-xs-12 col-sm-8 "> 

                                <div class="form-group">               
                                    <p>
                                        <span class="pull-right">
                                            <input id="cajaswitch" type="checkbox" data-size="mini" class="opcicustom " <?php echo $checkbox_caja; ?> />
                                        </span>
                                        <label>Cuadrar caja diariamente</label>        
                                    </p>
                                </div>                                                        
                            </div>      
                            <hr class="linesection"/>
                        </div>

                    </div><!--//FIN[.wrapinfoform]//-->



                    <div class="wrapinfoform ">
                        <div class="row wrapdivsection">
                            <div class="col-xs-12 col-sm-4">                        
                                <h3>Capital inicial</h3>
                                <p class="help-block">Definir este valor, ayudará al sistema a configurar el dinero disponible para realizar prestamos por primera vez</p>
                                <p class="text-warning"><i class="fa fa-warning margin-right-xs"></i>Este valor es un indicador inicial, no podrá volver a modificarce</p>
                            </div>
                            <div class="col-xs-12 col-sm-8 ">                        

                                <div class="form-group">
                                    <div class="form-group form-group-lg has-feedback">
                                        <input type="number" class="form-control" id="capitalinicialform" name="capitalinicialform" value="<?php if($define_user_capital_inicial>0){ echo $define_user_capital_inicial; } ?>" placeholder="0" <?php echo $activa_capital_input; ?>>
                                        <span class="form-control-feedback fa fa-dollar fa-lg"></span>
                                    </div>
                                </div>     

                            </div>  
                        </div>

                    </div><!--FIN[.wrapinfoform]-->


                    <div class="wrapinfoform margin-bottom-xs">
                        <div class="row wrapdivsection">   
                            <div class="col-xs-12">
                                <div class="form-group text-center" id="customuser">                    
                                    <button id="customuserbtn" type="button" class="btn btn-flat btn-primary btn-sm" data-field="customuser">
                                        Guardar cambios
                                        <i class="fa fa-save fa-lg margin-left-xs"></i>
                                    </button>
                                </div>
                                <div id="responcecustomuser"></div>                      
                                <div id="errcustomuser"></div>  
                            </div>
                        </div>                
                    </div><!--//FIN[.wrapinfoform]//-->


                    <div class="row">                              
                        <input type="hidden" name="codeuserform" id="codeuserform" value="<?php echo $idSSUser; ?>">                                        
                        <input id="introcustomform" name="introcustomform" value="intocustomuser" type="hidden"/>
                        <input id="sabadodiaform" name="sabadodiaform" type="hidden" value="<?php echo $input_sabado_dia; ?>"/>    
                        <input id="domingodiaform" name="domingodiaform" type="hidden" value="<?php echo $input_domingo_dia; ?>"/>    
                        <input id="festivodiaform" name="festivodiaform" type="hidden" value="<?php echo $input_festivo_dia; ?>"/>    
                        <input id="sabadoform" name="sabadoform" type="hidden" value="<?php echo $input_sabado; ?>"/>    
                        <input id="domingoform" name="domingoform" type="hidden" value="<?php echo $input_domingo; ?>"/>    
                        <input id="festivoform" name="festivoform" type="hidden" value="<?php echo $input_festivos; ?>"/>    
                        <input id="cajaform" name="cajaform" type="hidden" value="<?php echo $input_caja; ?>"/>

                    </div>

                </div>
                </form>
            </section> 
            
            
            <section class="content maxwidth-usersforms margin-bottom-lg ">            

                <div class="wrapinfoform  margin-bottom-xs">

                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">                        
                            <h3 class="no-padmarg">Restablecer cuenta</h3>
                            
                            <p class="text-warning"><i class="fa fa-warning margin-right-xs"></i>Esta acción no puede cancelarce, <B>SE ELIMINARÁN</B> todos los datos que hayas creado desde tu cuenta de usuario</p>
                        </div>
                        <div class="col-xs-12 col-sm-8 ">                  
                            <div class="box50">                                
                                <div id="resetuser" class=" text-center">
                                    <button class="btn btn-app btn-flat bg-red btnreset" type="button" data-field="resetuser" id="resetuserbtn" title="Restablecer cuenta" data-msj="Si restableces tu cuenta vas a perder todos los registros que has creado, estas seguro que deseas continuar?">
                                        <i class="fa fa-history fa-4x"></i>
                                        Restablecer cuenta
                                    </button>
                                </div>
                                <div id="responseresetuser"></div>
                                <div id="errresetuser"></div>
                                <div>
                                    <p class="help-block">Si deseas restaurar tu cuenta a su configuración inicial. Esta opcion puede ayudarte. Eliminará todos los registros que hayas creado y dejara tu cuenta como la primera vez que entraste</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            
            <section class="content maxwidth-usersforms margin-bottom-lg ">            

                <div class="wrapinfoform  margin-bottom-xs">

                    <div class="row wrapdivsection">
                        <div class="col-xs-12 col-sm-4">                        
                            <h3 class="no-padmarg">Restablecer sistema</h3>
                            <p class="text-warning"><i class="fa fa-warning margin-right-xs"></i>Esta acción no puede cancelarce, <B>SE ELIMINARÁN</B> todos los registros de la base de datos por completo</p>
                        </div>
                        <div class="col-xs-12 col-sm-8 ">                  
                            <div class="box50">
                                
                                <div id="resetsystem" class=" text-center">
                                    <button class="btn btn-app btn-flat bg-red btnreset" type="button" id="resetsystembtn" title="Restablecer sistema" data-msj="Esta accion, eliminará todos los datos de la base de datos del sistema, dejandolo con los valores iniciales de fabrica. Estas seguro que deseas continuar?" data-field="resetsystem" disabled="true">
                                        <i class="fa fa-power-off fa-4x"></i>
                                        Restablecer sistema
                                    </button>
                                </div>
                                <div id="responseresetsystem"></div>
                                <div id="errresetsystem"></div>
                                <div>
                                    <p class="help-block">Si deseas reiniciar el sistema a su valores de fabrica. Esta opcion puede ayudarte. Las configuraciones de la base de datos se reestableceran a sus valores de fabrica.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <?php include '../appweb/tmplt/footer.php';  ?>
    
            
    <?php
    /*
    /
    ////RIGHT BAR
    /
    */
    ?>
    <?php //include '../appweb/tmplt/right-side.php';  ?>
    
    
</div>

<?php echo _JSFILESLAYOUT_ ?>
<!-- validacion datos -->      
<script type="text/javascript" src="../appweb/plugins/form-validator/jquery.form-validator.min.js"></script> 
<script type="text/javascript" src="../appweb/js/to-userform.js"></script>
<script type="text/javascript" src="edit-custom-functions.js"></script>

<!---switchmaster---> 
<script src="../appweb/plugins/switchmaster/js/bootstrap-switch.min.js" type="text/javascript"></script>

<script type="text/javascript">

    $('input[name="capitalinicialform"]').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    
    $(".opcicustom").bootstrapSwitch(); 

    $("#sabadodiaswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#sabadodiaform").val("ok"); 
        }else{            
            $("#sabadodiaform").val(""); 
        }
    });
    
    $("#domingodiaswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#domingodiaform").val("ok"); 
        }else{            
            $("#domingodiaform").val(""); 
        }
    });
    
    $("#festivodiaswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#festivodiaform").val("ok"); 
        }else{            
            $("#festivodiaform").val(""); 
        }
    });
    
    $("#sabadoswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#sabadoform").val("ok"); 
        }else{            
            $("#sabadoform").val(""); 
        }
    });
    
    $("#domingoswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#domingoform").val("ok"); 
        }else{            
            $("#domingoform").val(""); 
        }
    });
    
    $("#festivoswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#festivoform").val("ok"); 
        }else{            
            $("#festivoform").val(""); 
        }
    });
    
    $("#cajaswitch").on('switchChange.bootstrapSwitch', function(event, state) {
        if($(this).is(':checked')) {            
            $("#cajaform").val("ok"); 
        }else{            
            $("#cajaform").val(""); 
        }
    });
                        
    $('#userform').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
      }
    });
    
    
    //reset btn    
    $('.btnreset').each(function(){
        
        var datafield = $(this).attr('data-field'); 
        var title = $(this).attr('title'); 
        var msjsistem = $(this).attr('data-msj'); 
        
        $(this).click(function(e) {
            e.preventDefault();                    
            swal({
                title: title, 
                text: '<span style=font-weight:bold;>' +msjsistem + '</span>', 
                type: 'warning',
                //showConfirmButton: false,
                showCancelButton: true,
                closeOnConfirm: true,
                closeOnCancel: true,
                animation: false,
                html: true
            }, function(isConfirm){
                  if (isConfirm) {
                    //$.redirect(linkURL,{ itemid_var: itemid}); 
                    
                    if($('#'+datafield).find(".loader").length){            
                        $('#'+datafield+" .loader").remove();            
                    }else{
                        $('#'+datafield).append("<div class='loader text-center'><img src='../appweb/img/loadbg.gif'/></div>").fadeIn("slow");
                    }

                    var dataString = 'datafield='+datafield;

                    console.log(dataString);

                    $.ajax({
                        type: "POST",
                        url: "../appweb/inc/refresh-system-functions.php",
                        data: dataString,
                        success: function(data) {
                            //field.val(data);
                            var response = JSON.parse(data);

                            //console.log(existeJSON);
                            if (response['error']) {

                                $("#response"+datafield).fadeOut();
                                $('#'+datafield+' .loader').fadeOut();

                                var errresponse = response["error"];
                                $("#err"+datafield).html("<div class='alert alert-default alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><ul class='list-group text-left'>"+errresponse+"</ul></div>").fadeIn("slow"); 

                            }else{

                                $('#'+datafield+' .loader').fadeOut(function(){
                                    
                                    $("#err"+datafield).fadeOut();
                                    $("#response"+datafield).html("<div class='alert bg-success alert-dismissible text-green'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-check margin-right-xs'></i> Plataforma </h4><p style='display:block;'>Tus cambios fueron realizados correctamente</p></div>").fadeIn();

                                });
                            }
                        }
                    }); 
                      
                      
                  } else {
                    return false;	
                  }
            });
        });
    });
    
                        
         
</script>     
</body>
</html>
