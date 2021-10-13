<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>
<?php 

//CONFIGURACIONES

$statusCancel = "";
$errTmpl_ins = "";

$statusCapitalInicial = "";
if($capital_inicial == "off"){
    $statusCapitalInicial = 1;
}


$loockVar = "false";
//SI DEDUOR FUE SELECCIONADO EXISTE ID DEUDOR
$idDeudor = "";
$nombreDeudor= "";
$nombre2Deudor= "";
$apellidoDeudor= "";
$apellido2Deudor= "";
$cedulaDeudor= "";
$nombreCompleto = "";
$deduor_lyt ="";
if(isset($_POST['creditevent']) && $_POST['creditevent'] =="refinanciarcredit"){
    
    $valida_idItemPOST = validaInteger($_POST['deudorcod'], "1");
    if($valida_idItemPOST === true){   
        $loockVar = "true";
        //credito
        $idCredito = $_POST['creditcod'];
        $idCredito = (int)$idCredito;
        $idCredito = $db->escape($idCredito);
        //deudor
        $idDeudor = $_POST['deudorcod'];
        $idDeudor = (int)$idDeudor;
        $idDeudor = $db->escape($idDeudor);

        /*
        *SOBRE EL DEUDOR
        */
        //query deudor
        $db->where("id_deudor", $idDeudor);
        $queryDeudor = $db->getOne("deudor_tbl","primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, cedula_deudor, dir_geo_deudor");

        $nombreDeudor= $queryDeudor['primer_nombre_deudor'];
        $nombre2Deudor= $queryDeudor['segundo_nombre_deudor'];
        $apellidoDeudor= $queryDeudor['primer_apellido_deudor'];
        $apellido2Deudor= $queryDeudor['segundo_apellido_deudor'];
        $cedulaDeudor= $queryDeudor['cedula_deudor'];
        $direccionDeudor= $queryDeudor['dir_geo_deudor'];

        $nombreCompleto = $nombreDeudor." ".$nombre2Deudor." ".$apellidoDeudor." ".$apellido2Deudor;

        $deduor_lyt .= "<div class='alert bg-gray text-black box50'>";
        $deduor_lyt .= "<div class='media'>";
        $deduor_lyt .= "<div class='media-left'>";
        $deduor_lyt .= "<i class='fa fa-user-circle fa-4x'></i>";
        $deduor_lyt .= "</div>";

        $deduor_lyt .= "<div class='media-body'>";
        $deduor_lyt .= "<h4>Beneficiario</h4>";
        $deduor_lyt .= "<h5 class='no-padmarg'>".$nombreCompleto."</h5>";
        $deduor_lyt .= "<p class='text-muted no-padmarg'>C.C. No. ".$cedulaDeudor."</p>";            
        $deduor_lyt .= "<p class='text-muted'><i class='fa fa-map-marker margin-right-xs'></i> ".$direccionDeudor."</p>"; 
        $deduor_lyt .= "</div>";
        $deduor_lyt .= "</div>";//media
        $deduor_lyt .= "</div>";//alert

        /*
        *SOBRE EL CREDITO
        */
        //DATOS CREDITO ACTUAL
        //SELECT `id_creditos`, `id_acreedor`, `id_deudor`, `id_codeudor`, `id_referencia_personal`, `id_referencia_familiar`, `id_referencia_comercial`, `id_cobrador`, `id_status_credito`, `id_plan_pago`, `code_consecutivo_credito`, `tipo_credito`, `descripcion_credito`, `comentarios_credito`, `fecha_apertura_credito`, `hora_apertura_credito`, `fecha_cierre_definitivo_credito` FROM `creditos_tbl` WHERE 1
        $qcredito_actual = array();
        $db->where("id_creditos", $idCredito);
        $qcredito_actual = $db->getOne("creditos_tbl", "id_creditos, id_deudor, id_codeudor, id_referencia_personal, id_referencia_familiar, id_referencia_comercial, id_cobrador, code_consecutivo_credito, tipo_credito, descripcion_credito" );

        $id_credito_actual = "";
        $id_ref_credito_actual = "";
        $id_deudor_credito_actual = "";
        $id_codeudor_credito_actual = "";
        $id_refperso_credito_actual = "";
        $id_reffami_credito_actual = "";
        $id_refcomer_credito_actual = "";
        $id_cobrador_credito_actual = "";
        $id_tipo_credito_actual = "";
        $descri_credito_actual = "";
        if(count($qcredito_actual)>0){
            $id_credito_actual = $qcredito_actual['id_creditos'];
            $ref_credito_actual = $qcredito_actual['code_consecutivo_credito'];
            $id_deudor_credito_actual = $qcredito_actual['id_deudor'];
            $id_codeudor_credito_actual = $qcredito_actual['id_codeudor'];
            $id_refperso_credito_actual = $qcredito_actual['id_referencia_personal'];
            $id_reffami_credito_actual = $qcredito_actual['id_referencia_familiar'];
            $id_refcomer_credito_actual = $qcredito_actual['id_referencia_comercial'];
            $id_cobrador_credito_actual = $qcredito_actual['id_cobrador'];
            $id_tipo_credito_actual = $qcredito_actual['tipo_credito'];
            $descri_credito_actual = $qcredito_actual['descripcion_credito'];

            //DEFINIR EL VALOR DE LA DEUDA ACTUAL
            //SELECT `id_recaudo`, `id_acreedor`, `id_plan_pago`, `id_cobrador`, `id_status_recaudo`, `ref_recaudo`, `numero_cuota_recaudos`, `metodo_pago_recaudo`, `capital_cuota_recaudo`, `interes_cuota_recaudo`, `activa_mora`, `valor_mora_cuota_recaudo`, `sobrecargo_cuota_recaudo`, `total_cuota_plan_pago`, `total_valor_recaudado_estacuota`, `valor_faltante_cuota`, `valor_cuota_recaulcaldo_recaudos`, `fecha_max_recaudo`, `fecha_recaudo_realizado`, `hora_recaudo_realizado`, `comentarios_recaudo` FROM `recaudos_tbl` WHERE 1        
            $qvalordeuda = array();
            $db->where("ref_recaudo", $ref_credito_actual);
            $db->where("id_status_recaudo", "1", "!=");
            $qvalordeuda = $db->get("recaudos_tbl", null,"activa_mora, valor_mora_cuota_recaudo, total_cuota_plan_pago, total_valor_recaudado_estacuota");

            $valor_total_deuda = 0;
            $valor_total_deuda_format = 0;
            if(is_array($qvalordeuda) && !empty($qvalordeuda)){
                foreach($qvalordeuda as $qvdKey){
                    $activamora_cuota_credito_actual = $qvdKey['activa_mora'];
                    $valoramora_cuota_credito_actual = $qvdKey['valor_mora_cuota_recaudo'];
                    $valor_cuota_credito_actual = $qvdKey['total_cuota_plan_pago'];
                    $recaudado_cuota_credito_actual = $qvdKey['total_valor_recaudado_estacuota'];

                    $cuotamora = ($activamora_cuota_credito_actual == 1)? $valoramora_cuota_credito_actual : 0;

                    $valor_total_deuda += ($valor_cuota_credito_actual + $cuotamora) - $recaudado_cuota_credito_actual;
                    $valor_total_deuda_format = number_format($valor_total_deuda, 0, ',', '.'); 

                }
            }
        }
    }
    
} 
//print_r($qvalordeuda);
//CANCELAR CREDITO
$statusCancel = "";
if(isset($_GET['trash']) && $_GET['trash'] == "ok"){ 
    $statusCancel = 1;   
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
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="../../appweb/plugins/datepicker/datepicker3.css">
    <!---switchmaster--->    
    <link rel="stylesheet" href="../../appweb/plugins/switchmaster/css/bootstrap3/bootstrap-switch.min.css">
        
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
                <span class="text-size-x6">Credito</span> / Refinanciar Credito
            </h1>                  
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
                
        <?php
        /*
        /*****************************//*****************************
        /IDENTIFICA USUARIO DEUDOR
        /*****************************//*****************************
        */                
        ?>
        <section class="maxwidth-usersforms  padd-verti-md">    
            <div class="btn-toolbar maxwidth-usersforms margin-bottom-xs" role="toolbar" aria-label="">                
                <div class="btn-group pull-right" role="group" aria-label="">
                    <button data-href="<?php echo $pathmm."/credits/details/"; ?>" type="button" class="btn btn-info godetails" data-id='<?php echo $id_credito_actual; ?>'>
                        <i class='fa fa-chevron-left fa-lg margin-right-xs'></i>
                        <span>Regresar al credito actual</span>                     
                    </button>
                </div>
            </div>
            <div class="box50 bg-gray">
                <?php if($deduor_lyt != ""){ echo $deduor_lyt; }else{  ?>
                <div class="alert alert-dismissible">
                    <!--<button type='button' class='close' data-dismiss='alert' aria-label="Close">
                        <i class='fa fa-times fa-1x margin-right-xs'></i>Continuar aqui
                    </button> -->
                    <div class="media">
                        <div class=" media-left">
                            <i class="fa fa-bell-o fa-4x text-muted"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Hola!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                Antes de crear este credito, recuerda buscar y seleccionar un deudor del listado de usuarios <i class="fa fa-user-circle fa-lg margin-hori-xs"></i> Si es un usuario nuevo, podrás crearlo cuando termines de publicar el credito.
                            </p>              
                            <p style="font-size:1.232em; line-height:1;"> Qué deseas hacer ahora?</p>
                        </div>

                    </div>                                                         
                    <div class="text-center padd-verti-xs">
                        <div class="btn-group">
                            <a href="<?php echo $pathmm."/users/"; ?>" type="button" class="btn btn-success" style="text-decoration:none;">
                                <i class='fa fa-search fa-lg margin-right-xs'></i>
                                Buscar usuario           
                            </a> 
                            <button type='button' class='btn close' data-dismiss='alert' aria-label="Close" style="margin-top:7px; font-size:17px;">
                                Crear credito                            
                            </button> 
                        </div>
                    </div>
                </div>
                
                <?php } ?>
            </div>
        </section>
        
        
        <section class="content maxwidth-usersforms margin-bottom-lg">
            <form action="" id="creditoform" autocomplete="off">
            <div class="">
                                                               
                <div class="row wrapdivsection">
                    <div class="col-xs-12 col-sm-3">
                        <h3>Credito actual</h3>
                        <p class="help-block"></p>
                    </div>
                    <div class="col-xs-12 col-sm-9 ">
                        
                        <p>
                            <stong>Ref. Credito</stong>
                            <a class="pull-right"><?php echo $ref_credito_actual; ?></a>
                        </p>
                        
                        <p class="bg-danger text-danger" style="display:table; width:100%;">
                            <stong>Total deuda</stong>
                            <span class="pull-right text-size-x4"><span class="margin-right-xs">$</span><?php echo $valor_total_deuda_format; ?></span>
                        </p>                                                           
                    </div>  
                    <hr class="linesection"/>
                </div>

                <div class="row wrapdivsection">
                    <div class="col-xs-12 col-sm-3">
                        <h3>Plan de pagos</h3>
                        <p class="help-block"></p>
                    </div>                                           
                    <div class="col-xs-12 col-sm-9 newplanpago ">
                         
                        <div class="row unlateralmargin unlateralpadding">
                            <div class="col-xs-12 col-sm-4">
                                <h4>Valor refinanciar</h4>                                                                           
                                <div style="position:relative; display:block; overflow:hidden;">
                                    <small class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                </div>
                                <div class="form-group">                             
                                    <div class="input-group">        
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" id="montoinput" name="montoinput" class="form-control monedaval " value ="" placeholder="0">
                                        <input type="hidden" id="deudainput" name="deudainput" value ="<?php echo $valor_total_deuda; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-4">
                                <h4>Valor total a pagar</h4>
                                <div style="position:relative; display:block; overflow:hidden;">
                                    <small class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">                                            
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" id="valtotalpagarinput" name="valtotalpagarinput" class="form-control  monedaval " value ="" placeholder="0" >                                
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4">
                                <h4>Utilidad</h4>
                                <div style="position:relative; display:block; overflow:hidden;">&nbsp;</div>
                                <div class="form-group">
                                    <div class="input-group">                                            
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" name="valutilidadinput" class="form-control monedaval " value ="0" placeholder="0" disabled>                                
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        <?php
                        /*
                        *CALCULAR TOTAL CUOTAS
                        */
                        ?>
                        
                        <h4>Calcular total cuotas plan de pagos</h4>
                        <div style="position:relative; display:block; overflow:hidden; ">
                            <small for="montoinput" class="text-muted  pull-right"><i class="fa fa-exclamation-circle fa-lg"></i>  Obligatorio calcular el plan de pagos</small>
                        </div>
                        <div class="well well-xs blue-grey lighten-3">
                            <div class="row">                                
                                <div class="col-xs-12 col-sm-6">
                                    <h4>Fecha inicio</h4>
                                    <div class="form-group">                                   
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right datepicker" name="fechainiciocreditoinput">
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <h4>Plazo <small class="black-text">(Total días)</small></h4>
                                    <div class="form-group">
                                        <input type="number" class="form-control justnumber" name="plazoinput" placeholder="0">
                                    </div>
                                    <!--<p class="help-block">
                                        Escribe el total de días del plazo para el pago total del credito
                                    </p>-->
                                </div>
                            </div>

                            <h4>Periocidad</h4>
                            <div class="form-group clearfix">
                            <?php 
                            $periocidadLyt = "";

                            $db->orderBy("id_periocidad","Asc");			
                            $periocidadQuery = $db->get('periocidad_tbl');                        
                            if(is_array($periocidadQuery)) {
                                foreach($periocidadQuery as $periKey){
                                    $idPeriocidadTbl = $periKey['id_periocidad'];
                                    $nombrePeriocidadTbl = $periKey['nombre_periocidad'];
                                    $cuotasPeriocidadTbl = $periKey['numcuota_periocidad_tbl'];
                                    $tagPeriocidadTbl = $periKey['tag_periocidad'];

                                    $periocidadLyt .= "<p class='col-xs-6 col-sm-3'>";
                                    $periocidadLyt .= "<label>";
                                    $periocidadLyt .= "<input type='radio' name='periocidadcuotainput' value='".$idPeriocidadTbl."' data-numcuota='".$cuotasPeriocidadTbl."' class='periocidadcheck flat-red' data-tagperio='".$tagPeriocidadTbl."'>";//flat-red 
                                    $periocidadLyt .= "<span class=' margin-left-md'>".$nombrePeriocidadTbl."</span>";
                                    $periocidadLyt .= "</label>";
                                    $periocidadLyt .= "</p>"; 
                                }                                                    
                            }   
                            echo $periocidadLyt; 
                            ?>                            
                            </div>

                            <div class="margin-verti-xs">
                                <h4>
                                    Total cuotas
                                    <button type="button" id="calplanpago" class="btn btn-info btn-md margin-left-md ">
                                        <i class="fa fa-calculator fa-lg margin-right-xs"></i>
                                        Calcular
                                    </button>  
                                </h4>  
                                <div id="resplanpago"></div>
                                <div id="err_resplanpago"></div>
                                <!--<div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <h4>número de cuotas</h4>
                                            <input type="text" name="numecuotasinput" class="form-control col-xs-6" value="0" placeholder="0" disabled>
                                        </div>    
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <h4>Fecha final de pago</h4>
                                            <input type="text" name="numecuotasinput" class="form-control col-xs-6" placeholder="dd/mm/yyyy" disabled>
                                        </div>    
                                    </div>
                                </div>
                                <h4>Capital</h4>
                                <div class="form-group">
                                    <div class="input-group">                                            
                                        <div class="input-group-addon"><i class="fa fa-dollar"></i></div>
                                        <input type="text" name="capitalcuotainput" class="form-control justnumber" value ="0"  placeholder="0" disabled>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                                                                        
                        <?php
                        /*
                        *SOBRE EL VALOR DE LA CUOTA
                        */
                        ?>
                        <input type="hidden" id="capitalcuotahidden" name="capitalcuotahidden">
                        <input type="hidden" id="valorcuotahidden" name="valorcuotahidden">
                                                                        
                        <div class="row unlateralmargin unlateralpadding">
                            <div class="col-xs-12 col-sm-6">
                                <h4>
                                    <span class="margin-right-md">Mora</span>
                                    <input id="switchmora" type="checkbox" name="" data-size="mini" class="opcicredit ">
                                </h4>
                                <p class="help-block">En el caso, el deudor se atrase en el pago de la cuota </p>
                                <div class="form-group">
                                    <div class="input-group">                                            
                                        <div class="input-group-addon"><i class="fa fa-dollar"></i></div>
                                        <input type="text" id="moraval" name="moracutoainput" class="form-control monedaval" value =""  placeholder="0">
                                    </div>
                                </div>    
                            </div>                                                       
                        </div>
                                                                                                                     
                    </div>      
                    <hr class="linesection"/>
                </div>
                
                
                <div class="row wrapdivsection">
                    <div class="col-xs-12 col-sm-4">
                        <h3>Cobranza</h3>
                        <p class="help-block">Selecciona el cobrador al que se le asignará el recaudo de este credito</p>
                    </div>
                    <div class="col-xs-12 col-sm-8">
                        <?php 
                            $cobradorLyt = "";
                                
                            $db->where("id_acreedor", $idSSUser );
                            $db->orderBy("nombre_cobrador","Asc");			
                            $cobradorQuery = $db->get('cobrador_tbl', null, 'id_cobrador, nombre_cobrador');                        
                            if(is_array($cobradorQuery)) {
                                foreach($cobradorQuery as $cobraKey){
                                    $idCobradorTbl = $cobraKey['id_cobrador'];
                                    $nameCobradorTbl = $cobraKey['nombre_cobrador'];           
                                    
                                    if($id_cobrador_credito_actual == $idCobradorTbl){
                                        $cobradorLyt .= "<option value='".$idCobradorTbl."' selected>";
                                        $cobradorLyt .= $nameCobradorTbl;
                                        $cobradorLyt .= "</option>";                                                                    
                                    }

                                    $cobradorLyt .= "<option value='".$idCobradorTbl."'>";
                                    $cobradorLyt .= $nameCobradorTbl;
                                    $cobradorLyt .= "</option>";                                                                
                                }                                                    
                            }   
                            
                            if($cobradorLyt != ""){
                        ?>
                        <div class="form-group">
                            <small for="cobradorinput" class="text-muted pull-right"><i class="fa fa-exclamation-circle fa-lg"></i> Campo obligatorio</small>
                            <select id="cobradorinput" class="form-control " name="cobradorinput">
                                <option value="">Selecciona un cobrador</option>
                                <?php echo $cobradorLyt;  ?>
                            </select>
                           
                        </div>    

                        <?php }else{ ?>


                        <div class="box50">
                            <div class="callout  margin-verti-md">
                                <div class="media">
                                    <div class=" media-left padd-hori-xs">
                                        <i class="fa fa-motorcycle fa-3x shades-text text-muted"></i>
                                    </div>
                                    <div class="media-body">
                                        <h3 class="no-padmarg">No has creado cobradores</h3>
                                        <p style="font-size:1.232em; line-height:1;">
                                            Registra almenos un cobrador en tu sistema
                                        </p>
                                        <a href="<?php echo $pathmm."/collect/new/"; ?>" class="btn btn-success btn-sm pull-right" style="text-decoration:none; ">Nuevo cobrador <i class="fa fa-plus fa-lg margin-left-xs"></i></a>
                                    </div>
                                </div>                    
                                
                            </div>   
                            
                        </div>

                        <?php } ?>
                        
                        
                    </div>
                    <hr class="linesection"/>
                </div>
                <?php if($cobradorLyt != ""){ ?>                                
                <div class="row ">
                    <div class="col-xs-12">
                        <div id="wrapadditem"></div>
                        <div id="erradditem"></div>       
                        <nav class="">
                            <div id="left-barbtn" class="nav navbar-nav padd-verti-xs" style="display:none;"></div>
                            <div id="right-bartbtn" class="row margin-hori-xs padd-verti-xs">
                                <div class="btn-toolbar" role="toolbar" aria-label="">
                                    <div class="btn-group pull-right" role="group" aria-label="">                                        
                                        <button data-href="<?php echo $pathmm."/credits/details/"; ?>" class="btn btn-default goback" title="Cancelar refinanciación" data-msj="Este credito no tendrá refinanciación y continuará con el plan de pagos actual. Deseas continuar?" data-id='<?php echo $id_credito_actual; ?>'>
                                            <i class='fa fa-times fa-lg margin-right-xs'></i>
                                            <span>Cancelar</span>
                                        </button>
                                        
                                        <button type="button" class="btn btn-info margin-hori-xs " id="additembtn">                                    
                                            <i class='fa fa-save fa-1x margin-right-xs'></i>
                                            <span>Guardar y terminar </span>                     
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                <?php } ?>
                <div class="row ">                    
                    
                    <input type="hidden" name="codeuserform" id="codeuserform" value="<?php echo $idSSUser; ?>"> 
                    <input type="hidden" name="pseudouser" id="pseudouser" value="<?php echo $pseudoSSUser; ?>"> 
                    <input type="hidden" name="deudorform" id="deudorform" value="<?php echo $idDeudor; ?>"> 
                    <input type="hidden" name="codeudorform" id="codeudorform" value="<?php echo $id_codeudor_credito_actual; ?>">
                    <input type="hidden" name="refpersoform" id="refpersoform" value="<?php echo $id_refperso_credito_actual; ?>">
                    <input type="hidden" name="reffamiform" id="reffamiform" value="<?php echo $id_reffami_credito_actual; ?>">
                    <input type="hidden" name="refcomerform" id="refcomerform" value="<?php echo $id_refcomer_credito_actual; ?>">
                    <input type="hidden" name="tipocreditoinput" id="tipocreditoinput" value="<?php echo $id_tipo_credito_actual; ?>">
                    <input type="hidden" name="descricreditoinput" id="descricreditoinput" value="<?php echo $descri_credito_actual; ?>">
                    <input type="hidden" name="creditactualform" id="creditactualform" value="<?php echo $id_credito_actual; ?>">
                                                            
                    <?php echo "<input id='pathfile' type='hidden' value='".$pathmm."'/>"; ?>
                    <?php echo "<input id='pathdir' type='hidden' value='".$creditsDir."'/>"; ?>
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
                                No encontramos el credito que buscas
                            </p>              
                            <p style="font-size:1.232em; line-height:1;">Regresa al listado de creditos registrados e intentalo de nuevo</p>
                        </div>
                    </div>                        
                </div>
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">                                           
                        <div class="btn-group text-center">                            
                            <a href="<?php echo $pathmm."/credits/"; ?>" type="button" class="btn btn-info">
                                <i class='fa fa-chevron-left fa-lg margin-right-xs'></i>
                                <span>Lista de creditos</span>                     
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
<script type="text/javascript" src="calculoplanpago.js"></script>
<script type="text/javascript" src="crud-new-credito.js"></script> 

<!-- InputMask -->
<script src="../../appweb/plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../appweb/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../appweb/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../appweb/plugins/iCheck/icheck.min.js"></script>
<!-- bootstrap datepicker -->
<script src="../../appweb/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- validacion datos -->      
<script type="text/javascript" src="../../appweb/plugins/form-validator/jquery.form-validator.min.js"></script>    
<script type="text/javascript" src="../../appweb/js/to-creditoform.js"></script>    

<!---switchmaster---> 
<script src="../../appweb/plugins/switchmaster/js/bootstrap-switch.min.js" type="text/javascript"></script>
    
<script type="text/javascript" src="../../appweb/plugins/jquery.number.js"></script>     
    
<script src="../../appweb/plugins/misc/jquery.redirect.js"></script>    
<script type="text/javascript">
$(document).ready(function() { 
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
            
            $(this).click(function() {
                //e.preventDefault();                            
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
    
    
    $('#howwork').modal({
        'show': true,
        'backdrop': 'static',
        'keyboard': false
    });
    
    $('#planpagohelp').popover();
    
    $('.monedaval').number( true, 0 );    
    
    /*$("#calplanpago").on(function(){
       $(this).addClass("disabled");
        
    });*/
    //$("#calplanpago").attr("disabled", true);
    /*var valmontocredito = $('#montoinput').val();        
    var fechainicial = $("input[name='fechainiciocreditoinput']").val();        
    var plazo = $("input[name='plazoinput']").val();        
    var periocidad;
    $("input[name='periocidadcuotainput']").each(function(){
        if($(this).is(":checked")){
            periocidad = $(this).attr("data-tagperio");                 
        }
    });
    
    if(valmontocredito != "" && fechainicial != "" && plazo != "" && periocidad != ""){
      $("#calplanpago").attr("disabled", false);  
    }else{
        $("#calplanpago").attr("disabled", true);
    }*/
    
    
    
    
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });   
    
    //Date picker
    $('.datepicker').datepicker({
        autoclose: true,        
        format: "dd/mm/yyyy"
    });

    $('.justnumber').keyup(function (){ 
        this.value = (this.value + '').replace(/[^0-9]/g, '');        
    });
    /*$('.justnumber').inputmask({
        mask: "[9][9]9999999",
        greedy: false,
        skipOptionalPartCharacter: " ",
    });*/

    /*$('input[name="fechainiciocreditoinput"]').inputmask({
        mask: "yyyy-mm-dd",
        //alias: "yyyy-mm-dd"
    });
    $('input[name="fechafincreditoinput"]').inputmask({
        mask: "yyyy-mm-dd",
        //alias: "yyyy-mm-dd"
    });*/

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
    
    /*
    *CALCULA NUMERO DE CUOTAS
    */
    
    //VALOPR TOTAL CUOTA
    $(".newplanpago").keyup(function(){
        //var capitalinput = $(this).find('input[name="capitalcuotahidden"]').val(); 
        //var interesinput = $(this).find('input[name="interescuotainput"]').val(); 
        //var morainput = $(this).find('input[name="moracutoainput"]').val(); 
        //var sobcargoinput = $(this).find('input[name="sobcargocuotainput"]').val(); 
        //var numecuotas = $(this).find('input[name="numecuotasinput"]').val(); 
        var valorpresatdo = $(this).find('input[name="montoinput"]').val();
        var totalpagar = $(this).find('input[name="valtotalpagarinput"]').val();
        
        //var valtotalcuota = parseFloat(capitalinput) + parseFloat(interesinput);//+parseFloat(morainput)+parseFloat(sobcargoinput);
        
        /*if(morainput != ""){
            valtotalcuota += parseFloat(morainput) 
        }
        
        if(sobcargoinput != ""){
            valtotalcuota += parseFloat(sobcargoinput) 
        }*/
        
        //var totalpagar =  parseFloat(valtotalcuota) * parseInt(numecuotas);
        var utilidad = parseFloat(totalpagar) - parseFloat(valorpresatdo);
        
        //$(this).find('input[name="valorcuotainput"]').val(valtotalcuota).number( true, 2 );
        //$(this).find('input[name="valtotalcuotainput"]').val(valtotalcuota).number( true, 2 );
        //$(this).find('input[name="valtotalcuotafullinput"]').val(valtotalcuota);
        //$(this).find('input[name="valtotalcuotafullinput"]').val(valtotalcuota);
        
        //$(this).find('input[name="valtotalpagarinput"]').val(totalpagar).number( true, 2 );
        if(utilidad > 0){
            $(this).find('input[name="valutilidadinput"]').val(utilidad).number( true, 0 );    
        }
        
        
        
    });
    
    /*//NUMERO DE CUOTAS
    
    var periocidad;
    $(".periocidadcheck").change(function(){        
       $(this).each(function(){
            if($(this).is(":checked")){
                periocidad = $(this).attr("data-numcuota");            
                //periocidad = $(this).val();            
                //console.log(periocidad);  
            }  
       });
    });
    
    $(".newplanpago").change(function(){
        
        var fechainicioinput = $(this).find('input[name="fechainiciocreditoinput"]').val();
        var fechafininput = $(this).find('input[name="fechafincreditoinput"]').val();
        //var periocidad = $('input[name="periocidadcuotainput"]').attr("data-numecuotas");
//        /var periocidad;
//        $(this).find("input[name='periocidadcuotainput']").change(function(){        
//            $(this).each(function(){
//                if($(this).is(":checked")){
//                    periocidad = $(this).attr("data-numcuota");            
//                     
//                }
//            });
//        });/
        //alert(periocidad);

        var fechaInicio = new Date(fechainicioinput).getTime();
        var fechaFin    = new Date(fechafininput).getTime();

        var diff = fechaFin - fechaInicio;

        var totaldias = diff/(1000*60*60*24);
        var numecuota;        
        //para cuotas diarias
        if(periocidad == 1){
            numecuota = totaldias;
        }

        //para cuotas semanales
        if(periocidad == 7){
            numecuota = Math.ceil(totaldias/periocidad);
        }

        //para cuotas quincenales
        if(periocidad == 15){
            numecuota = Math.ceil(totaldias/periocidad);
        }

        //para cuotas mensualidad
        if(periocidad == 30){
            numecuota = Math.ceil(totaldias/30);
        }

        $(this).find('input[name="numecuotasinput"]').val(numecuota);

       //console.log(numecuota);    
    });*/
    
            
    

});       
    
$(".opcicredit").bootstrapSwitch(); 

$("#moraval").attr("disabled", "disabled");
$("#sobcargoval").attr("disabled", "disabled");

$("#switchmora").on('switchChange.bootstrapSwitch', function(event, state) {
    if($(this).is(':checked')) {
        $("#moraval").removeAttr("disabled"); 
    }else{
        $("#moraval").attr("disabled", "disabled");           
    }
});
    
$("#switchsobcargo").on('switchChange.bootstrapSwitch', function(event, state) {
    if($(this).is(':checked')) {
        $("#sobcargoval").removeAttr("disabled"); 
    }else{
        $("#sobcargoval").attr("disabled", "disabled");           
    }
});

    
</script>       
</body>
</html>

