<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-credits.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>

<?php 
//recibe datos de PEDIDOS

$loockVar = "false";
$idItemPOST = "";
$datasCredito = array();
//definir estrado
$statusLyt = "";
if(isset($_POST['itemid_var']) && $_POST['itemid_var'] != ""){
    
    $idItemPOST = $_POST['itemid_var'];
    //$idItemPOST = int($idItemPOST);
    //$idItemPOST = $db->escape($idItemPOST);
    
    $valida_idItemPOST = validaInteger($idItemPOST, "1");
    
    if($valida_idItemPOST === true){   
        $loockVar = "true";      
        $ref_deudor= "";
        $ref_codeudor= "";
        $ref_perso= "";
        $ref_familiar= "";
        $ref_comercial= "";
        $ref_cobranza= "";
        
        $datasCredito = queryCreditosDetalles($idSSUser, $idItemPOST);
        
        if(is_array($datasCredito) && !empty($datasCredito)){
            foreach($datasCredito as $dcKey){
                $id_Credito = $datasCredito['idcredito'];
                $consecutivo_Credito = $datasCredito['consecutivo'];
                $datas_PlanPago = $datasCredito['dataplanpago'];
                $datas_Cuotas = $datasCredito['datacuotas'];
                $datas_Recaudos = $datasCredito['datacuotas']; 
                $datas_Deudor = $datasCredito['datadeudor'];
                $datas_Cobrador = $datasCredito['datacobrador'];
                $datas_CoDeudor = $datasCredito['datacodeudor'];                
                $datas_RefPerso = $datasCredito['datasrefperso'];
                $datas_RefFami = $datasCredito['datasreffami'];
                $datas_RefComer = $datasCredito['datasrefcomer'];
                $datas_Status = $datasCredito['datastatus'];
                $datas_TipoCredito = $datasCredito['datatipocredito'];
                $fecha_InicioCredito = date("d/m/y", strtotime($datasCredito['fechaabrecredito']));
                $hora_InicioCredito = $datasCredito['horaabrecredito'];
                $fecha_FinCredito = $datasCredito['fechaterminacredito'];
                $descri_Credito = $datasCredito['descricredito'];
                
                //DATAS STATUS
                if(is_array($datas_Status) && !empty($datas_Status)){
                    foreach($datas_Status as $dsKey){
                        $statusId = $datas_Status["id_status"];        
                        $statusNombre = $datas_Status["nombre_status"];        
                    }
                }
                
                //$statusNombre = queryStatusGB($idStatusCobrador);
                
                

                
                //PLAN DE PAGOS
                if(is_array($datas_PlanPago) && !empty($datas_PlanPago)){
                    foreach($datas_PlanPago as $dppKey){                        
                        $periocidadPlazo = $datas_PlanPago['periocidad_plan_pago'];
                        $valorPrestado = $datas_PlanPago['valor_credito_plan_pago'];
                        $valorPagar = $datas_PlanPago['valor_pagar_credito'];
                        $utilidad = number_format($datas_PlanPago['utilidad_credito'],0, ',', '.');
                        $numeroCuotas = $datas_PlanPago['numero_cuotas_plan_pago'];
                        $plazoDias = $datas_PlanPago['plazocredito_plan_pago'];
                        $fechaPrimeraCuota = date("d/m/Y",strtotime($datas_PlanPago['fecha_inicio_plan_pago']));
                        $fechaUltimaCuota = date("d/m/Y",strtotime($datas_PlanPago['fecha_fin_plan_pago']));
                        $capitalCuota = $datas_PlanPago['capital_cuota_plan_pago'];
                        $valorCuota = number_format($datas_PlanPago['valor_cuota_plan_pago'],0, ',', '.');  
                        $valorMoraCuota = number_format($datas_PlanPago['valor_mora_plan_pago'],0, ',', '.');  
                        
                    }
                }
                
                //CUOTAS  -/RECAUDOS/-
                $calculo_valor_prestado = 0;
                $calculo_valor_credito = 0;
                $calculo_valor_recaudado = 0;
                $calculo_valor_porcobrar = 0;
                
                
                
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
                        
                        //CALCULO DINERO PRESTADO
                        $calculo_valor_prestado += $cuota_capital;
                        
                        //CALCULO VALOR CREDITO
                        $calculo_valor_credito += $cuota_valor_cuota;
                        
                        //CALCULO VALOR RECAUDADO
                        if($cuota_status != "3"){
                            $calculo_valor_recaudado += $cuota_valor_recaudado;
                        }
                    }
                    
                    $calculo_valor_porcobrar = $valorPagar - $calculo_valor_recaudado;
                    
                    $calculo_valor_prestado_format = number_format($valorPrestado, 2, '.', ','); 
                    $calculo_valor_credito_format = number_format($valorPagar, 2, '.', ','); 
                    $calculo_valor_recaudado_format = number_format($calculo_valor_recaudado, 2, '.', ','); 
                    $calculo_valor_porcobrar_format = number_format($calculo_valor_porcobrar, 2, '.', ','); 
                }
                
                //TIPO DE CREDITO
                if(is_array($datas_TipoCredito) && !empty($datas_TipoCredito)){
                    foreach($datas_TipoCredito as $tcKey){
                        $tipo_credito = $datas_TipoCredito['nombre_tipo_credito'];
                    }
                }
                
                //SOBRE EL DEUDOR
                
                if(is_array($datas_Deudor) && !empty($datas_Deudor)){
                    foreach($datas_Deudor as $deuKey){
                        $id_deudor = isset($datas_Deudor['id_deudor'])? $datas_Deudor['id_deudor'] : "";    
                        $primernombre_deudor = isset($datas_Deudor['primer_nombre_deudor'])? $datas_Deudor['primer_nombre_deudor'] : "";                
                        $segundonombre_deudor = isset($datas_Deudor['segundo_nombre_deudor'])? $datas_Deudor['segundo_nombre_deudor'] : "";   
                        $primerapellido_deudor = isset($datas_Deudor['primer_apellido_deudor'])? $datas_Deudor['primer_apellido_deudor'] : "";    
                        $segundoapellido_deudor = isset($datas_Deudor['segundo_apellido_deudor'])? $datas_Deudor['segundo_apellido_deudor'] : "";
                        $nombrecompleto_deudor = $primernombre_deudor." ".$segundonombre_deudor." ".$primerapellido_deudor." ".$segundoapellido_deudor;                       
                        $dirgeo_deudor = isset($datas_Deudor['dir_geo_deudor'])? $datas_Deudor['dir_geo_deudor'] : "";
                        $email_deudor = isset($datas_Deudor['email_deudor'])? $datas_Deudor['email_deudor'] : "";
                        $cedula_deudor = isset($datas_Deudor['cedula_deudor'])? $datas_Deudor['cedula_deudor'] : "";   
                        $tel1_deudor = isset($datas_Deudor['tel_uno_deudor'])? $datas_Deudor['tel_uno_deudor'] : "";
                        $tel2_deudor = isset($datas_Deudor['tel_uno_deudor'])? $datas_Deudor['tel_dos_deudor'] : "";
                    }
                    
                    $ref_deudor = "1";
                    
                }else{
                    $id_deudor = "";
                    $primernombre_deudor = "";                
                    $segundonombre_deudor = "";
                    $primerapellido_deudor = "";
                    $segundoapellido_deudor = "";
                    $nombrecompleto_deudor = "<span class='badge bg-black'>Pendiente</span>";                   
                    $dirgeo_deudor = "";
                    $email_deudor = "";
                    $cedula_deudor = "-";
                    $tel1_deudor = "";
                    $tel2_deudor = "";                                                            
                }
                
                //SOBRE EL CODEUDOR
                if(is_array($datas_CoDeudor) && !empty($datas_CoDeudor)){
                    foreach($datas_CoDeudor as $codeuKey){
                        $primernombre_codeudor = isset($datas_CoDeudor['primer_nombre_deudor'])? $datas_CoDeudor['primer_nombre_deudor'] : "";                    
                        $primerapellido_codeudor = isset($datas_CoDeudor['primer_apellido_deudor'])? $datas_CoDeudor['primer_apellido_deudor'] : "";
                        $nombrecompleto_codeudor = $primernombre_codeudor." ".$primerapellido_codeudor;
                        $email_codeudor = isset($datas_CoDeudor['email_deudor'])? $datas_CoDeudor['email_deudor'] : "";
                        $cedula_codeudor = isset($datas_CoDeudor['cedula_deudor'])? $datas_CoDeudor['cedula_deudor'] : "";
                        $tel1_codeudor = isset($datas_CoDeudor['tel_uno_deudor'])? $datas_CoDeudor['tel_uno_deudor'] : "";
                        $tel2_codeudor = isset($datas_CoDeudor['tel_dos_deudor'])? $datas_CoDeudor['tel_dos_deudor'] : "";   
                    }
                    $ref_codeudor = "1";
                }else{
                    $primernombre_codeudor = "";                    
                    $primerapellido_codeudor = "";
                    $nombrecompleto_codeudor = "";
                    $email_codeudor = "";
                    $cedula_codeudor = "";
                    $tel1_codeudor = "";
                    $tel2_codeudor = "";
                    
                    
                }
                
                //REFERENCIA PERSONAL
                if(is_array($datas_RefPerso) && !empty($datas_RefPerso)){
                    foreach($datas_RefPerso as $rpKey){
                        $primernombre_refper = isset($datas_RefPerso['primer_nombre_deudor'])? $datas_RefPerso['primer_nombre_deudor'] : "";                    
                        $primerapellido_refper = isset($datas_RefPerso['primer_apellido_deudor'])? $datas_RefPerso['primer_apellido_deudor'] : "";
                        $nombrecompleto_refper = $primernombre_refper." ".$primerapellido_refper;
                        $email_refper = isset($datas_RefPerso['email_deudor'])? $datas_RefPerso['email_deudor'] : "";
                        $cedula_refper = isset($datas_RefPerso['cedula_deudor'])? $datas_RefPerso['cedula_deudor'] : "";
                        $tel1_refper = isset($datas_RefPerso['tel_uno_deudor'])? $datas_RefPerso['tel_uno_deudor'] : "";
                        $tel2_refper = isset($datas_RefPerso['tel_dos_deudor'])? $datas_RefPerso['tel_dos_deudor'] : "";   
                    }
                    $ref_perso = "1";
                }else{
                    $primernombre_refper = "";                    
                    $primerapellido_refper = "";
                    $nombrecompleto_refper = "";
                    $email_refper = "";
                    $cedula_refper = "";
                    $tel1_refper = "";
                    $tel2_refper = "";
                }
                
                
                //REFERENCIA FAMILIAR
                if(is_array($datas_RefFami) && !empty($datas_RefFami)){
                    foreach($datas_RefFami as $rpKey){
                        $primernombre_reffami = isset($datas_RefFami['primer_nombre_deudor'])? $datas_RefFami['primer_nombre_deudor'] : "";                    
                        $primerapellido_reffami = isset($datas_RefFami['primer_apellido_deudor'])? $datas_RefFami['primer_apellido_deudor'] : "";
                        $nombrecompleto_reffami = $primernombre_reffami." ".$primerapellido_reffami;
                        $email_reffami = isset($datas_RefFami['email_deudor'])? $datas_RefFami['email_deudor'] : "";
                        $cedula_reffami = isset($datas_RefFami['cedula_deudor'])? $datas_RefFami['cedula_deudor'] : "";
                        $tel1_reffami = isset($datas_RefFami['tel_uno_deudor'])? $datas_RefFami['tel_uno_deudor'] : "";
                        $tel2_reffami = isset($datas_RefFami['tel_dos_deudor'])? $datas_RefFami['tel_dos_deudor'] : "";   
                    }
                    $ref_familiar = "1";
                }else{
                    $primernombre_reffami = "";                    
                    $primerapellido_reffami = "";
                    $nombrecompleto_reffami = "";
                    $email_reffami = "";
                    $cedula_reffami = "";
                    $tel1_reffami = "";
                    $tel2_reffami = "";
                }
                
                //REFERENCIA COMERCIAL
                if(is_array($datas_RefComer) && !empty($datas_RefComer)){
                    foreach($datas_RefComer as $rcKey){
                        $primernombre_refcomer = isset($datas_RefComer['primer_nombre_deudor'])? $datas_RefComer['primer_nombre_deudor'] : "";                    
                        $primerapellido_refcomer = isset($datas_RefComer['primer_apellido_deudor'])? $datas_RefComer['primer_apellido_deudor'] : "";
                        $nombrecompleto_refcomer = $primernombre_refcomer." ".$primerapellido_refcomer;
                        $email_refcomer = isset($datas_RefComer['email_deudor'])? $datas_RefComer['email_deudor'] : "";
                        $cedula_refcomer = isset($datas_RefComer['cedula_deudor'])? $datas_RefComer['cedula_deudor'] : "";
                        $tel1_refcomer = isset($datas_RefComer['tel_uno_deudor'])? $datas_RefComer['tel_uno_deudor'] : "";
                        $tel2_refcomer = isset($datas_RefComer['tel_dos_deudor'])? $datas_RefComer['tel_dos_deudor'] : "";   
                        $nit_refcomer = isset($datas_RefComer['nit_referencia_comercial'])? $datas_RefComer['nit_referencia_comercial'] : "";   
                        $razonsocial_refcomer = isset($datas_RefComer['nombre_empresa_deudor'])? $datas_RefComer['nombre_empresa_deudor'] : "";   
                        $contacto_refcomer = isset($datas_RefComer['nombre_contato_referencia_comercial'])? $datas_RefComer['nombre_contato_referencia_comercial'] : "";   
                        $cargo_refcomer = isset($datas_RefComer['cargo_empresa_deudor'])? $datas_RefComer['cargo_empresa_deudor'] : "";  
                        $telempresa_refcomer = isset($datas_RefComer['tel_empresa_deudor'])? $datas_RefComer['tel_empresa_deudor'] : "";  
                    }
                    $ref_comercial = "1";
                }else{
                    $primernombre_refcomer = "";                    
                    $primerapellido_refcomer = "";
                    $nombrecompleto_refcomer= "";
                    $email_refcomer = "";
                    $cedula_refcomer = "";
                    $tel1_refcomer = "";
                    $tel2_refcomer = "";
                }
                
                //COBRADOR                
                if(is_array($datas_Cobrador) && !empty($datas_Cobrador)){
                    foreach($datas_Cobrador as $dcKey){
                        $nombre_cobrador = isset($datas_Cobrador['nombre_cobrador'])? $datas_Cobrador['nombre_cobrador'] : "";                                            
                        $email_cobrador = isset($datas_Cobrador['mail_cobrador'])? $datas_Cobrador['mail_cobrador'] : "";
                        //$cedula_refcomer = isset($datas_Cobrador['cedula_referencia_comercial'])? $datas_Cobrador['cedula_referencia_comercial'] : "";
                        $tel1_cobrador = isset($datas_Cobrador['tel_uno_cobrador'])? $datas_Cobrador['tel_uno_cobrador'] : "";
                        $tel2_cobrador = isset($datas_Cobrador['tel_dos_cobrador'])? $datas_Cobrador['tel_dos_cobrador'] : "";   
                         
                    }
                    $ref_cobranza= "1";
                }
                
                
                
                
            }//FIN[$datasCredito]
        }//FIN ARRAY [$datasCredito]
    }//FIN VALIDA [$valida_idItemPOST]           
}

$statusLyt .="<p class='bg-info text-info padd-verti-xs padd-hori-md'>";
//$statusLyt .="<i class='fa fa-info-circle fa-2x'></i>";
$statusLyt .="<span class='margin-hori-xs fa-2x'>Status Credito</span>";
$statusLyt .="<span class='label label-info text-size-x4'>".$statusNombre."</span>";
$statusLyt .="</p>";
//echo "<pre>";
//print_r($datasCredito);

//echo $id_Credito;
//***********
//SITE MAP
//***********


$rootLevel = "creditos";
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
                <span class="text-size-x6">Credito</span> / Detalles
            </h1>      
            <a href="<?php echo $pathmm."/credits/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de creditos
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
        
        <div class="row clearfix margin-bottom maxwidth-layout" id="wrappadminevent">
            
            <div class="btn-toolbar pull-right" role="toolbar">
                <?php 
                //status btns opciones de cerdito
                $status_refinanciar_btn = "";
                $status_lost_btn = "";
                $status_cancel_btn = "";
                $activa_btn_lty = '<button type="button" class="btn btn-app bg-green admincreditbtn" id="acticredit" data-name="" data-title="Reactivar credito" data-msj="Estas a punto de reactivar este credito, dejandolo en estado POR COBRAR. Estas seguro que deseas continuar?" data-post="'.$id_Credito.'" data-field="acticredit">';
                $activa_btn_lty .= '<i class="fa fa-check fa-lg"></i>';
                $activa_btn_lty .= 'Reactivar';
                $activa_btn_lty .= "</button>";
                                                                       
                if($statusId == "7"){
                    $status_refinanciar_btn = "disabled";
                    $status_lost_btn = "disabled";
                    $status_cancel_btn = "disabled";                    
                    $activa_btn_lty = "";
                }elseif($statusId == "5" || $statusId == "6"){
                    $status_refinanciar_btn = "disabled";
                    $status_lost_btn = "disabled";
                    $status_cancel_btn = "disabled";                                        
                    
                }elseif($statusId == "1" || $statusId == "3"){
                    $activa_btn_lty = "";
                }elseif($statusId == "2"){
                    $status_refinanciar_btn = "disabled";
                    $status_lost_btn = "disabled";
                    $status_cancel_btn = "disabled";
                    $activa_btn_lty = "";
                }
                ?>
                <div class="btn-group btn-group-xs" role="group">
                    <button type="button" class="btn btn-app btn-default refinanciarcredits" id="refinanciarcredit" data-name="" data-title="Refinanciar credito" data-msj="Estas a punto de refinanciar este credito. Serás redireccionado a la sección de crear nuevo credito, ahí el sistema asumirá el credito actual como cancelado. Y las cuotas faltantes del credito actual se sumarán al nuevo plan de pagos que vas a crear. Estas seguro que deseas continuar?" data-post="<?php echo $id_Credito; ?>" data-deudor="<?php echo $id_deudor; ?>" data-field="refinanciarcredit" <?php echo $status_refinanciar_btn; ?>>
                        <i class="fa fa-dollar fa-lg"></i>
                        Refinanciar
                    </button >
                </div>
                    
                <div class="btn-group btn-group-xs" role="group">
                    
                    <button type="button" class="btn btn-app btn-default admincreditbtn" id="lostcredit" data-name="" data-title="Dificil cartera" data-msj="Si pones este credito como dificil cartera, el sistema asume el credito como perdido. Estas seguro que deseas continuar?" data-post="<?php echo $id_Credito; ?>" data-field="lostcredit" <?php echo $status_lost_btn; ?>>
                        <i class="fa fa-calendar-minus-o fa-lg"></i>
                        Dificil cartera
                    </button>
                   
                    <button type="button" class="btn btn-app btn-default admincreditbtn" id="cancelcredit" data-name="" data-title="Cancelar credito" data-msj="Estas a punto de cancelar este credito. Esto hará que el sistema asuma el credito como pagado, Si aun existen cuotas o dinero por cobrar, se definirá como dinero perdido. Estas seguro que deseas continuar?" data-field="cancelcredit" data-post="<?php echo $id_Credito; ?>" <?php echo $status_cancel_btn; ?>>
                        <i class="fa fa-times fa-lg"></i>
                        Cancelar
                    </button> 
                    <?php echo $activa_btn_lty; ?>
                </div>
            </div>
            
            <div id="erradmincredit" class=" clearfix"></div>
            <div id="successadmincredit" class=" clearfix"></div>
            
        </div>
        <section class="content">
            <div class="row ">                
                <div class="col-xs-12 text-center">                             
                <?php echo $statusLyt; ?>                               
                </div>      
                
            </div>
            <div class="row text-center">
                
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 grey white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x">
                                <span class="margin-right-xs">$</span> 
                                <?php echo $calculo_valor_prestado_format; ?>
                            </strong>
                        </div>
                    </a>
                    <h3>Val. Prestado</h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3"> 
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x">
                                <span class="margin-right-xs">$</span> 
                                <?php echo $calculo_valor_credito_format; ?>
                            </strong>
                        </div>
                    </a>
                    <h3>Val. a pagar</h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 green accent-3 white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x">
                                <span class="margin-right-xs">$</span> 
                                <?php echo $calculo_valor_recaudado_format; ?>
                            </strong>
                        </div>
                    </a>
                    <h3>Recaudado</h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 deep-orange white-text padd-verti-xs img-rounded">
                            <strong class="fa-2x">
                                <span class="margin-right-xs">$</span> 
                                <?php echo $calculo_valor_porcobrar_format; ?>
                            </strong>
                        </div>
                    </a>
                    <h3>Por cobrar</h3>
                </div>
            </div>
        </section>
        
        <section class="content ">
            
            <!---
                DETALLES CREDITO
            -->
            <div class="row margin-bottom-xs">
                <div class="col-xs-12 col-sm-6">
                    <h2 class="page-header">Sobre el prestamo</h2>
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <p class="box-title">
                                <small class="margin-right-xs text-red">Ref:</small>
                                <?php echo $consecutivo_Credito; ?>
                            </p>
                        </div>
                        <div class="box-body">
                            <p > 
                                <label>
                                    <i class="fa fa-user margin-right-xs"></i>
                                    Deudor
                                </label>
                                <span class="text-size-x3 margin-left-xs">
                                    <?php echo $nombrecompleto_deudor; ?>
                                    <br/>
                                    <small class="text-muted margin-left-md untopdowpadding untopdowlmargin" >
                                        C.C. No.&nbsp;
                                        <?php echo $cedula_deudor; ?>
                                    </small>
                                </span>
                                
                            </p>
                            <p>
                                <label>
                                    <i class="fa fa-calendar margin-right-xs"></i>
                                    Fecha Registro
                                </label>
                                <span class="text-size-x3 margin-left-xs"><?php echo $fecha_InicioCredito; ?></span>
                            </p>
                            <p>
                                <label>
                                    <i class="fa fa-calendar margin-right-xs"></i>
                                    Fecha Inicio
                                </label>
                                <span class="text-size-x3 margin-left-xs"><?php echo $fechaPrimeraCuota; ?></span>
                            </p>
                            <p>
                                <label>
                                    <i class="fa fa-calendar margin-right-xs"></i>
                                    Fecha Fin
                                </label>
                                <span class="text-size-x3 margin-left-xs"><?php echo $fechaUltimaCuota; ?></span>
                            </p>
                            <p>
                                <label>
                                    <i class="fa fa-tags margin-right-xs"></i>
                                    Tipo credito
                                </label>
                                <span class="text-size-x3 margin-left-xs"><?php echo $tipo_credito; ?></span>
                            </p>
                            <?php if(isset($descri_Credito) && $descri_Credito != "" ){ ?>
                            <p>
                                <?php echo $descri_Credito; ?>
                            </p>
                            <?php } ?>                                                        
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-6">
                    <h2 class="page-header">Plan de pagos</h2>
                    <div class="callout callout-default">
                        
                        <p>
                            <label>                                
                                Número de Cuotas
                            </label>
                            <span class="text-size-x2 margin-left-xs badge bg-blue"><?php echo $numeroCuotas; ?></span>
                        </p>
                        <p>
                            <label>                                
                                Plazo
                            </label>
                            <span class="text-size-x2 margin-left-xs badge bg-blue"><?php echo $plazoDias; ?> Días</span>
                        </p>
                        <p>
                            <label>                                
                                Periocidad
                            </label>
                            <span class="text-size-x2 margin-left-xs badge bg-blue txtCapitalice"><?php echo $periocidadPlazo; ?></span>
                        </p>
                        <p>
                            <label>                                
                                Valor Cuota
                            </label>
                            <span class="text-size-x2 margin-left-xs badge bg-blue txtCapitalice"><span class="text-size-x3 margin-right-xs">$</span><?php echo $valorCuota; ?></span>
                        </p>
                        <?php if(isset($valorMoraCuota) && $valorMoraCuota != ""){ ?>
                        <p>
                            <label>                                
                                Valor Mora
                            </label>
                            <span class="text-size-x2 margin-left-xs badge bg-blue txtCapitalice"><span class="text-size-x3 margin-right-xs">$</span><?php echo $valorMoraCuota; ?></span>
                        </p>
                        <?php } ?>
                        <p>
                            <label>                                
                                Utilidad Credito
                            </label>
                            <span class="text-size-x2 margin-left-xs badge bg-blue txtCapitalice"><span class="text-size-x3 margin-right-xs">$</span><?php echo $utilidad; ?></span>
                        </p>
                        
                    </div>                                        
                </div>
            </div>
                                    
        </section>
        
        
        <section class="content ">
                        
            <h2 class="page-header">Referencias</h2>
            <?php
            /*
            *ESTADOS REFERENCIAS
            */
            ?>
            <div class="row">
                <div class="col-md-12">
                <?php
                    $statusReferencias_lyt = "";
                    $stepsCreditsStatus_1 = "disabled";
                    $stepsCreditsStatus_2 = "disabled";
                    $stepsCreditsStatus_3 = "disabled";
                    $stepsCreditsStatus_4 = "disabled";
                    $stepsCreditsStatus_5 = "disabled";
                    $stepsCreditsStatus_6 = "disabled";
                                       
                    $ref1_link="";
                    $ref2_link="";
                    $ref3_link="";
                    $ref4_link="";
                    $ref5_link="";
                    
                    //esta clase le indicara al usuario que primero debera crear el deudor en caso de no existir
                    //para poder crear las referencias
                    $class_link = "warningmsg";
                                       
                    if($ref_deudor== "1"){
                        $stepsCreditsStatus_1 = "active";
                        $ref1_link = "disabled";
                        $class_link = "goref";
                    }
                    if($ref_codeudor== "1"){
                        $stepsCreditsStatus_2 = "active";
                        $ref2_link = "disabled";
                    }
                    if($ref_perso== "1"){
                        $stepsCreditsStatus_3 = "active";
                        $ref3_link = "disabled";
                    }
                    if($ref_familiar== "1"){
                        $stepsCreditsStatus_4 = "active";
                        $ref4_link = "disabled";
                    }
                    if($ref_comercial== "1"){
                        $stepsCreditsStatus_5 = "active";
                        $ref5_link = "disabled";
                    }
                    if($ref_cobranza== "1"){
                        $stepsCreditsStatus_6 = "active";                        
                    }
                    
                    

                    $statusReferencias_lyt .= "<section class='clearfix margin-bottom-md'>";
                    $statusReferencias_lyt .= "<div class='row'>";
                    //DEUDOR***********    
                    $statusReferencias_lyt .= "<div class='col-xs-4 col-sm-2'>";
                    $statusReferencias_lyt .= "<div class='wrapp-ref-circle  text-center'>";
                    $statusReferencias_lyt .= "<div data-href='".$pathmm."/credits/details/usuario.php' class='goref ".$stepsCreditsStatus_1."' data-item='".$id_Credito."' data-type='deudor' type='button' >";
                    $statusReferencias_lyt .= "<span class='ref-circle ".$stepsCreditsStatus_1."'><i class='fa fa-check'></i></span>";
                    $statusReferencias_lyt .= "<p>Deudor</p>";
                    $statusReferencias_lyt .= "</div>"; 
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                    //CODEUDOR**************    
                    $statusReferencias_lyt .= "<div class='col-xs-4 col-sm-2'>";
                    $statusReferencias_lyt .= "<div class='wrapp-ref-circle'>";
                    $statusReferencias_lyt .= "<div data-href='".$pathmm."/credits/details/usuario.php' class='".$class_link." ".$stepsCreditsStatus_2."' data-item='".$id_Credito."' data-ref='".$id_deudor."' data-type='codeudor' type='button'>";
                    $statusReferencias_lyt .= "<span class='ref-circle ".$stepsCreditsStatus_2."'><i class='fa fa-check'></i></span>";
                    $statusReferencias_lyt .= "<p>Codeudor</p>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                    //REFERENCIA PERSONAL**********        
                    $statusReferencias_lyt .= "<div class='col-xs-4 col-sm-2'>";
                    $statusReferencias_lyt .= "<div class='wrapp-ref-circle  text-center'>";
                    $statusReferencias_lyt .= "<div data-href='".$pathmm."/credits/details/usuario.php' class='".$class_link." ".$stepsCreditsStatus_3."' data-item='".$id_Credito."' data-ref='".$id_deudor."' data-type='ref_personal' type='button' >";
                    $statusReferencias_lyt .= "<span class='ref-circle ".$stepsCreditsStatus_3."'><i class='fa fa-check'></i></span>";
                    $statusReferencias_lyt .= "<p>Referencia personal</p>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "<div class='clearfix visible-xs'></div>";
                    //REFERENCIA FAMILIAR***********   
                    $statusReferencias_lyt .= "<div class='col-xs-4 col-sm-2'>";
                    $statusReferencias_lyt .= "<div class='wrapp-ref-circle text-center'>";
                    $statusReferencias_lyt .= "<div data-href='".$pathmm."/credits/details/usuario.php' class='".$class_link." ".$stepsCreditsStatus_4."' data-item='".$id_Credito."' data-ref='".$id_deudor."' data-type='ref_familiar' type='button'>";
                    $statusReferencias_lyt .= "<span class='ref-circle ".$stepsCreditsStatus_4."'><i class='fa fa-check'></i></span>";
                    $statusReferencias_lyt .= "<p>Referencia familiar</p>"; 
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";

                    //REFERECIA COMERCIAL    
                    $statusReferencias_lyt .= "<div class='col-xs-4 col-sm-2'>";
                    $statusReferencias_lyt .= "<div class='wrapp-ref-circle text-center'>";
                    $statusReferencias_lyt .= "<div data-href='".$pathmm."/credits/details/usuario.php' class='".$class_link." ".$stepsCreditsStatus_5."' data-item='".$id_Credito."' data-ref='".$id_deudor."' data-type='ref_comercial' type='button'>";
                    $statusReferencias_lyt .= "<span class='ref-circle ".$stepsCreditsStatus_5."'><i class='fa fa-check'></i></span>";
                    $statusReferencias_lyt .= "<p>Referencia comercial</p>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                                       
                    //COBRANZA    
                    $statusReferencias_lyt .= "<div class='col-xs-4 col-sm-2'>";
                    $statusReferencias_lyt .= "<div class='wrapp-ref-circle'>";
                    $statusReferencias_lyt .= "<span class='ref-circle ".$stepsCreditsStatus_6."'><i class='fa fa-check'></i></span>";
                    $statusReferencias_lyt .= "<p>Cobranza</p>";
                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</div>";
                    

                    $statusReferencias_lyt .= "</div>";
                    $statusReferencias_lyt .= "</section>";

                    echo $statusReferencias_lyt;    
                ?>
                
                </div>            
            </div>
            
            <?php
            /*
            *INFO REFERENCIAS
            */
            ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#deudor" data-toggle="tab">Deudor</a></li>
                            <?php if(is_array($datas_CoDeudor) && !empty($datas_CoDeudor)){ ?>
                            <li><a href="#codeudor" data-toggle="tab">Codeudor</a></li>
                            <?php } ?>
                            <?php  if(is_array($datas_RefPerso) && !empty($datas_RefPerso)){ ?>
                            <li><a href="#refperso" data-toggle="tab">Referencia Personal</a></li>
                            <?php } ?>
                            <?php if(is_array($datas_RefFami) && !empty($datas_RefFami)){ ?>
                            <li><a href="#reffami" data-toggle="tab">Referencia Familiar</a></li>
                            <?php } ?>
                            <?php if(is_array($datas_RefComer) && !empty($datas_RefComer)){ ?>
                            <li><a href="#refcomer" data-toggle="tab">Referencia Comercial</a></li>
                            <?php } ?>
                            <?php if(is_array($datas_Cobrador) && !empty($datas_Cobrador)){ ?>
                            <li><a href="#cobranza" data-toggle="tab">Cobranza</a></li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content">
                            <?php 
                            /*
                            *DETALLES DEUDOR
                            */
                            ?>
                            <div class="tab-pane active" id="deudor">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-user-circle fa-5x margin-top-md padd-hori-md"></i>
                                    </div>
                                    <div class="media-body">
                                        <h2>
                                            <?php echo $nombrecompleto_deudor; ?>
                                            <p class="text-size-x4" style="display:block;">
                                                <span>C.C.</span>
                                                <?php echo $cedula_deudor; ?>
                                            </p>
                                        </h2>
                                        <dl class="dl-horizontal-custom">  

                                            <?php 
                                                $infoDeudorLyt = "";
                                                if($tel1_deudor !=""){
                                                    $infoDeudorLyt .= "<dt>Tel fijo:</dt>";
                                                    $infoDeudorLyt .= "<dd>".$tel1_deudor."</dd>";
                                                }
                                                if($tel2_deudor !=""){
                                                    $infoDeudorLyt .= "<dt>Tel celular:</dt>";
                                                    $infoDeudorLyt .= "<dd>".$tel2_deudor."</dd>";
                                                }
                                                if($email_deudor !=""){
                                                    $infoDeudorLyt .= "<dt>Email:</dt>";
                                                    $infoDeudorLyt .= "<dd>".$email_deudor."</dd>";
                                                }                                                
                                                if($dirgeo_deudor !=""){
                                                    $infoDeudorLyt .= "<dt>Recidencia:</dt>";
                                                    $infoDeudorLyt .= "<dd>".$dirgeo_deudor."</dd>";
                                                }                                                
                                                echo $infoDeudorLyt;
                                            ?>                                            
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            
                            <?php 
                            /*
                            *DETALLES CODEUDOR
                            */
                            ?>
                            <?php  if(is_array($datas_CoDeudor) && !empty($datas_CoDeudor)){ ?>
                            <div class="tab-pane" id="codeudor">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-user-circle fa-5x margin-top-md padd-hori-md"></i>
                                    </div>
                                    <div class="media-body">
                                        <h2>
                                            <?php echo $nombrecompleto_codeudor; ?>
                                            <p class="text-size-x4" style="display:block;">
                                                <span>C.C.</span>
                                                <?php echo $cedula_codeudor; ?>
                                            </p>
                                        </h2>
                                        <dl class="dl-horizontal-custom">  

                                            <?php 
                                       
                                                $infoCoDeudorLyt = "";
                                                if($tel1_codeudor !=""){
                                                    $infoCoDeudorLyt .= "<dt>Tel fijo:</dt>";
                                                    $infoCoDeudorLyt .= "<dd>".$tel1_codeudor."</dd>";
                                                }
                                                if($tel2_codeudor !=""){
                                                    $infoCoDeudorLyt .= "<dt>Tel celular:</dt>";
                                                    $infoCoDeudorLyt .= "<dd>".$tel2_codeudor."</dd>";
                                                }
                                                if($email_codeudor !=""){
                                                    $infoCoDeudorLyt .= "<dt>Email:</dt>";
                                                    $infoCoDeudorLyt .= "<dd>".$email_codeudor."</dd>";
                                                }                                                
                                                /*if($dirgeo_deudor !=""){
                                                    $infoCoDeudorLyt .= "<dt>Recidencia:</dt>";
                                                    $infoCoDeudorLyt .= "<dd>".$dirgeo_deudor."</dd>";
                                                }*/                                                
                                                echo $infoCoDeudorLyt;
                                            ?>                                            
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            
                            <?php 
                            /*
                            *DETALLES REFRENCIA PERSONAL
                            */
                            ?>
                            <?php  if(is_array($datas_RefPerso) && !empty($datas_RefPerso)){ ?>
                            <div class="tab-pane" id="refperso">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-user-circle fa-5x margin-top-md padd-hori-md"></i>
                                    </div>
                                    <div class="media-body">
                                        <h2>
                                            <?php echo $nombrecompleto_refper; ?>
                                            <p class="text-size-x4" style="display:block;">
                                                <span>C.C.</span>
                                                <?php echo $cedula_refper; ?>
                                            </p>
                                        </h2>
                                        <dl class="dl-horizontal-custom">  

                                            <?php 
                                       
                                                $infoRefPerLyt = "";
                                                if($tel1_refper !=""){
                                                    $infoRefPerLyt .= "<dt>Tel fijo:</dt>";
                                                    $infoRefPerLyt .= "<dd>".$tel1_refper."</dd>";
                                                }
                                                if($tel2_refper !=""){
                                                    $infoRefPerLyt .= "<dt>Tel celular:</dt>";
                                                    $infoRefPerLyt .= "<dd>".$tel2_refper."</dd>";
                                                }
                                                if($email_refper !=""){
                                                    $infoRefPerLyt .= "<dt>Email:</dt>";
                                                    $infoRefPerLyt .= "<dd>".$email_refper."</dd>";
                                                }
                                                                                               
                                                echo $infoRefPerLyt;
                                            ?>                                            
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php 
                            /*
                            *DETALLES REFRENCIA FAMILIAR
                            */
                            ?>
                            <?php if(is_array($datas_RefFami) && !empty($datas_RefFami)){ ?>
                            <div class="tab-pane" id="reffami">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-user-circle fa-5x margin-top-md padd-hori-md"></i>
                                    </div>
                                    <div class="media-body">
                                        <h2>
                                            <?php echo $nombrecompleto_reffami; ?>
                                            <p class="text-size-x4" style="display:block;">
                                                <span>C.C.</span>
                                                <?php echo $cedula_reffami; ?>
                                            </p>
                                        </h2>
                                        <dl class="dl-horizontal-custom">  

                                            <?php 

                                                $infoRefFamiLyt = "";
                                                if($tel1_reffami !=""){
                                                    $infoRefFamiLyt .= "<dt>Tel fijo:</dt>";
                                                    $infoRefFamiLyt .= "<dd>".$tel1_reffami."</dd>";
                                                }
                                                if($tel2_reffami !=""){
                                                    $infoRefFamiLyt .= "<dt>Tel celular:</dt>";
                                                    $infoRefFamiLyt .= "<dd>".$tel2_reffami."</dd>";
                                                }
                                                if($email_reffami !=""){
                                                    $infoRefFamiLyt .= "<dt>Email:</dt>";
                                                    $infoRefFamiLyt .= "<dd>".$email_reffami."</dd>";
                                                }
                                                                                               
                                                echo $infoRefFamiLyt;
                                            ?>                                            
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php 
                            /*
                            *DETALLES REFRENCIA COMERCIAL
                            */
                            ?>
                            <?php if(is_array($datas_RefComer) && !empty($datas_RefComer)){ ?>
                            <div class="tab-pane" id="refcomer">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-user-circle fa-5x margin-top-md padd-hori-md"></i>
                                    </div>
                                    <div class="media-body">
                                        <h2>
                                            <?php
                                                if($razonsocial_refcomer != ""){
                                                    echo $razonsocial_refcomer;
                                                }else{
                                                    echo $nombrecompleto_refcomer;     
                                                }
                                                                                           
                                                if($nit_refcomer != ""){
                                                    echo "<p class='text-size-x4' style='display:block;'><span>NIT</span>".$nit_refcomer."</p>";
                                                }else{
                                                    echo "<p class='text-size-x4' style='display:block;'><span>C.C.</span>".$cedula_refcomer."</p>";
                                                }
                                                
                                            ?>                                            
                                        </h2>
                                        <dl class="dl-horizontal-custom">  

                                            <?php 

                                                $infoRefComerLyt = "";                                                
                                                if($tel1_refcomer !=""){
                                                    $infoRefComerLyt .= "<dt>Tel fijo:</dt>";
                                                    $infoRefComerLyt .= "<dd>".$tel1_refcomer."</dd>";
                                                }
                                                if($tel2_refcomer !=""){
                                                    $infoRefComerLyt .= "<dt>Tel celular:</dt>";
                                                    $infoRefComerLyt .= "<dd>".$tel2_refcomer."</dd>";
                                                }
                                                if($email_refcomer !=""){
                                                    $infoRefComerLyt .= "<dt>Email:</dt>";
                                                    $infoRefComerLyt .= "<dd>".$email_refcomer."</dd>";
                                                }
                                                                                                
                                                if($contacto_refcomer !=""){
                                                    $infoRefComerLyt .= "<dt>Contacto:</dt>";
                                                    $infoRefComerLyt .= "<dd>".$contacto_refcomer."</dd>";
                                                }
                                                if($cargo_refcomer !=""){
                                                    $infoRefComerLyt .= "<dt>Cargo:</dt>";
                                                    $infoRefComerLyt .= "<dd>".$cargo_refcomer."</dd>";
                                                }
                                                if($telempresa_refcomer !=""){
                                                    $infoRefComerLyt .= "<dt>Cargo:</dt>";
                                                    $infoRefComerLyt .= "<dd>".$telempresa_refcomer."</dd>";
                                                }
                                
                                
                                                                                               
                                                echo $infoRefComerLyt;
                                            ?>                                            
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            
                            <?php 
                            /*
                            *DETALLES COBRADOR
                            */
                            ?>
                            <?php if(is_array($datas_Cobrador) && !empty($datas_Cobrador)){ ?>
                            <div class="tab-pane" id="cobranza">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-user-circle fa-5x margin-top-md padd-hori-md"></i>
                                    </div>
                                    <div class="media-body">
                                        <h2>
                                            <?php echo $nombre_cobrador; ?>                                            
                                        </h2>
                                        <dl class="dl-horizontal-custom">  

                                            <?php 

                                                $infoCobraLyt = "";
                                                if($tel1_cobrador !=""){
                                                    $infoCobraLyt .= "<dt>Tel fijo:</dt>";
                                                    $infoCobraLyt .= "<dd>".$tel1_cobrador."</dd>";
                                                }
                                                if($tel2_cobrador !=""){
                                                    $infoCobraLyt .= "<dt>Tel celular:</dt>";
                                                    $infoCobraLyt .= "<dd>".$tel2_cobrador."</dd>";
                                                }
                                                if($email_cobrador !=""){
                                                    $infoCobraLyt .= "<dt>Email:</dt>";
                                                    $infoCobraLyt .= "<dd>".$email_cobrador."</dd>";
                                                }
                                                                                               
                                                echo $infoCobraLyt;
                                            ?>                                            
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            
                        </div>
                        <!-- /.tab-content -->
                    </div>
                  <!-- nav-tabs-custom -->
                </div>
                <!-- /.col -->    
            </div>
            
        </section>
            
        <section class="content">    
            <h2 class="page-header">Recaudos</h2>    
            <div class="row">                
                <div class="col-xs-12 ">
                    <div class="box">                    
                        <div class="box-body table-responsive">
                            <table id="printdatatbl" class="table table-striped" style="width:100%;">     
                                <thead>                                
                                    <tr>
                                        <th></th>
                                        <th >Fecha cobrar</th>
                                        <th >No. Cuota</th>
                                        <th >Valor pagar</th>
                                        <th >Status mora</th>
                                        <th >Fecha pagó</th>
                                        <th >Status Cuota</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>            
                        </div>                    
                    </div>                                               
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
                                No encontramos o fue eliminado el CREDITO que deseas visualizar
                            </p>              
                            <p style="font-size:1.232em; line-height:1;"> Asegurate que seleccionaste el credito correcto, e intentalo de nuevo</p>
                        </div>

                    </div>                    
                </div>
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group text-center">
                            <a href="<?php echo $pathmm."/credits/"; ?>" type="button" class="btn btn-default">
                                <i class='fa fa-th-list fa-lg margin-right-xs'></i>
                                <span>lista de creditos</span>                        
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
    <?php //include 'appweb/tmplt/footer.php';  ?>
    <?php
    /*
    /
    ////RIGHT BAR
    /
    */
    ?>
    <?php //include '../../appweb/tmplt/right-side.php';  ?>
    <?php echo "<input type='hidden' id='creditvar' value='".$consecutivo_Credito."' />";  ?>
    <?php echo "<input id='pathnewcredit' type='hidden' value='".$pathmm."/credits/refinance/'/>"; ?>
    
</div>
<?php echo _JSFILESLAYOUT_ ?>
<script src="../../appweb/plugins/misc/jquery.redirect.js"></script>    
<!-- DataTables -->
<script src="../../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>
    
<script src="crud-credit.js"></script>    
<script>
    $(document).ready(function(){
        $(".warningmsg").each(function(){
            $(this).click(function(e) {
                e.preventDefault();      
                swal({
                  title: "Advertencia",
                  text: "Para crear esta referencia, es necesario que primero asignes un deudor para este credito",
                  type: "warning",
                  showCancelButton: false,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Entendido",
                  closeOnConfirm: true
                }); 
            });
        });

        $(".goref").each(function(){ 
            
            var gourl = $(this).attr("data-href");
            var itemid = $(this).attr("data-item");
            var itemtype = $(this).attr("data-type");
            var itemref = $(this).attr("data-ref");
                        
            $(this).click(function(){        
                //alert("me dieron click "+itemid);
                $.redirect(gourl,{ itemid_var: itemid, itemref_var: itemref, itemtype_var: itemtype}); 
            });
        });

    });
    /* Formatting function for row details - modify as you need */
    function format(d) {
        // `d` is the original data object for the row
        return '<table cellspacing="0" class="table table-bordered" >'+
            '<tr>'+
                '<td class="col-xs-4">Capital cuota:</td>'+
                '<td class="col-xs-8"><span class="margin-right-xs">$</span>'+d.cuotacapital+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td class="col-xs-4">Valor mora:</td>'+
                '<td class="col-xs-8"><span class="margin-right-xs">$</span>'+d.cuotamora+'</td>'+
            '</tr>'+
            '<tr >'+  
                '<td class="col-xs-4">Valor abonado:</td>'+
                '<td class="col-xs-8"><span class="margin-right-xs">$</span>'+d.cuotarecaudado+'</td>'+
            '</tr>'+
            '<tr class="danger">'+
                '<td class="col-xs-4">Pendiente por pagar:</td>'+
                '<td class="col-xs-8"><span class="margin-right-xs">$</span>'+d.cuotafaltante+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td class="col-xs-4">Comentarios:</td>'+
                '<td class="col-xs-8"><p>'+d.cuotacoment+'</p></td>'+
            '</tr>'+
        '</table>';
    }
    
    
    $(function(){

        //var locacionvar = $('#locacionvar').val();     
        //console.log(locacionvar);
    /*$('#printdatatbl').DataTable({        
        "scrollX": false,
        "ordering": false,
        "autoWidth": false
    });*/

        var table = $('#printdatatbl').DataTable( {	
            //"responsive": true,
            
            "order": [[ 2, "asc" ]],
            "processing": true,
            //"serverSide": true,
            "bDeferRender": true,			
            "sPaginationType": "full_numbers",
            //"ajax": "querylocaciones.php",
            "ajax": {
                "url": "query-recaudos.php?",//?qloc="+locacionvar,
                //"async": false,
                //"dataType": "json",
                "type": "POST",
                "data": {
                    "qvar" : $("#creditvar").val()
                }/*,
                "success" : function(data){
                     console.log(data);
                    //callback(data);
                    // Do whatever you want.
                  }*/

            },
            //"deferLoading": [ 57, 100 ],

            "columns": [
                //{ "data": "foto" },
                //{ "data": "<img src='"+data.foto+"' class='img-responsive' style='height:50px; width:auto;'/>"  },

                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
                { "data": "cuotafecha"},
                { "data": "numecuota"},
                /*{ "data": "cuotacapital"},
                { "data": "cuotamora" },*/
                { "data": "cuotafinal" },
                /*{ "data": "cuotarecaudado"},
                { "data": "cuotafaltante"},
                { "data": "cuotafecha"},

                { "data": "cuotacoment"},*/
                { "data": "statusmora"},
                { "data": "fechapago"},                    
                { "data": "cuotastatus"},
            ],
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

        // Add event listener for opening and closing details
        $('#printdatatbl tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });


    });


</script>       
</body>
</html>
