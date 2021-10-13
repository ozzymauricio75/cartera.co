<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-users.php'; ?>
<?php require_once '../appweb/inc/query-tablas-complementarias.php'; ?>
<?php require_once '../i18n-textsite.php'; ?>

<?php 
/*
*CONSULTA USUARIO DEUDOR
****************************************
*/
$datasCreditos_deduor = array();
$not_found = 0;
$loockVar = "false";
$error_lyt  = "";
$valueInput = "";

//RECIBE VALORES FORMULARIO
if(isset($_GET['quservar']) && $_GET['quservar'] != ""){
    
    if($_GET['quservar'] == "ok"){
        
        $valueInput = $_GET['searchuser'];        
        
        $valida_qval = validaNumeric($_GET['searchuser'], "1");
        
        if($valida_qval === true){
            
            $loockVar = "true";
            
            $qval = $db->escape($_GET['searchuser']);
            
            $db->where("cedula_deudor", $qval);
            $querydeudor = $db->getOne("deudor_tbl", "id_deudor");
            $id_deudor = $querydeudor['id_deudor'];
                                                
            /*
            *PARA EL DEUDOR
            */
            $datas_Deudor = array();
            $datas_Deudor = queryDeudorDetalles($id_deudor);
                                  
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
                
                /*
                *ACTIVIDAD USUARIO
                */

                $queryActividad = array();
                $db->where("id_usuario_credito", $id_deudor);
                $queryActividad = $db->get("usuario_asignado_credito");
                
                /*
                *INSERTA CONSULTA DEUDOR
                */
                
                //consultar credito dificil cartera
                $queryCreditoEscapados = array();
                $db->where("id_deudor", $id_deudor);
                $db->where("id_status_credito", "5");
                $queryCreditoEscapados = $db->getOne("creditos_tbl", "code_consecutivo_credito");
                
                if(count($queryCreditoEscapados)>0){                    
                    $referencia_credito = $queryCreditoEscapados["code_consecutivo_credito"];
                    
                    //query deuda
                    $queryDeuda = array();
                    $db->where('id_status_recaudo', "1", "!=");
                    $db->where('ref_recaudo', $referencia_credito);        
                    $queryDeuda = $db->get ("recaudos_tbl", null, "activa_mora, total_cuota_plan_pago, valor_mora_cuota_recaudo, total_valor_recaudado_estacuota");
                    
                    $valor_deuda = 0;
                    if(is_array($queryDeuda) && !empty($queryDeuda)){
                        foreach($queryDeuda as $qdKey){
                            $activamora = isset($qdKey['activa_mora'])? $db->escape($qdKey['activa_mora']) : ""; 
                            $valormora = isset($qdKey['valor_mora_cuota_recaudo'])? $db->escape($qdKey['valor_mora_cuota_recaudo']) : "";         
                            $valorbasecuota = isset($qdKey['total_cuota_plan_pago'])? $db->escape($qdKey['total_cuota_plan_pago']) : "";        
                            $valorrecaudadocuota = isset($qdKey['total_valor_recaudado_estacuota'])? $db->escape($qdKey['total_valor_recaudado_estacuota']) : "";
                            
                            $cuota_con_mora = ($activamora == 1)? $valormora : 0;

                            //define valor deuda
                            $valor_deuda = $valor_deuda + ($valorbasecuota + $cuota_con_mora) - $valorrecaudadocuota;
                            
                        }
                    }
                    

                    //datos del acreedor
                    $queryAcreedor = array();
                    $db->where("id_acreedor", $idSSUser);
                    $queryAcreedor = $db->getOne("acreedor_tbl", "nickname_acreedor, codigo_geo_ciudad_acreedor, codigo_geo_estado_acreedor");
                    
                    if(count($queryAcreedor)  > 0 ){
                        $nickname_acreedor = $queryAcreedor['nickname_acreedor'];
                        $estado_acreedor = $queryAcreedor['codigo_geo_estado_acreedor'];
                        $ciudad_acreedor = $queryAcreedor['codigo_geo_ciudad_acreedor'];
                    }
                    
                    //array escapados
                    $arrayEscapados = array(
                        "consecutivo_credito" => $referencia_credito,
                        "valor_deuda_credito" => $valor_deuda,
                        "id_deudor" =>$id_deudor,
                        "nombre_deudor" =>$nombrecompleto_deudor,
                        "cedula_deudor" =>$cedula_deudor,
                        "usuario_acreedor" =>$nickname_acreedor,
                        "estado_acreedor" =>$estado_acreedor,
                        "ciudad_acreedor" =>$ciudad_acreedor,
                        "hora_consulta_usuario_escapado" =>$horaFormatDB,
                        "fecha_consulta_uuario_escapado" =>$dateFormatDB,
                    );
                    $newUsuarioEscapado = $db->insert("usuario_escapado", $arrayEscapados);
                    
                }
                
            //SI LA CONSULTA NO ARROJO RESULTADOS    
            }else{
                                
                $not_found = 1;
                                
            }   
            
        //SI EL STRING ENVIADO NO FUE VALIDADO    
        }else{
            
            
            if(empty($valueInput)){
                $usertyped = "Por favor escribe un número de cédula para consultar";                    
            }else{
                $usertyped = "Escribiste&nbsp;&nbsp;\" <b>" .$valueInput ."</b> \"";
            }
            
            $error_lyt .= "<div class='maxwidth-layout margin-bottom-xs '>";            
            $error_lyt .= "<div class='box50 alert alert-dismissible bg-danger text-danger'>";
            $error_lyt .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
            $error_lyt .= "<h4 class='text-danger'><b>Cédula</h4>";
            $error_lyt .= "<p>
            <br>".$usertyped."
            <br>Parece que estas utilizando caracteres no permitidos.
            <br>Escribe un número de cédula valido
            <br>Sólo puedes usar números</p>";
            $error_lyt .= "</div>";
            $error_lyt .= "</div>";
            
        }
            
    }
}


//***********
//SITE MAP
//***********

$rootLevel = "";
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
    <link rel="stylesheet" href="../appweb/plugins/datatables/dataTables.bootstrap.css">
    <?php echo _FAVICON_TOUCH_ ?>    
    <style type="text/css">
        
        input[type="search"]{
            
            border: 1px solid #8a8a8a;
            background-color: #d8d8d8;
            font-size: 13px;
            font-weight: bold;
        }
            
    </style>
    
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
                <span class="text-size-x6">Deudores</span> / Historial crediticio
            </h1>                  
        </section>
        


        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        
        <?php if($error_lyt != ""){ echo $error_lyt; } ?>
        <section class="maxwidth-layout margin-bottom-xs">                 
            <div class="box50 img-thumbnail">                
                
                <form id="quserform" action="<?php echo $pathFile."/"; ?>" method="get">
                    <div class="form-group has-feedback " style="margin-bottom:0px;">
                        <input type="text" id="searchuser" name="searchuser" class="form-control input-lg bg-white justnumber" placeholder="Escribe un numero de cedula">
                        <i class="fa fa-search form-control-feedback"></i>
                    </div>
                    <input type="hidden" name="quservar" value="ok" />
                </form>                                                                  
                
            </div>
        </section>
        
        <?php if($loockVar == "true"){ ?>
        
        <?php if(is_array($datasCreditos_deduor) && !empty($datasCreditos_deduor)){ ?>
        
        <div class=" padd-bottom-md maxwidth-layout">
        <div class="box50">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
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
                            <button type="button" class="btn btn-success btn-block gocredits" data-deudorvar="<?php echo $id_deudor; ?>">
                                <b>Crear credito</b>
                                <i class="fa fa-plus margin-left-xs"></i>
                            </button>
                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sobre este usuario</h3>
                        </div>
                        <div class="box-body pre-scrollable-md">
                            <strong>
                                <i class="fa fa-check-circle margin-r-5"></i> Actividad
                            </strong>
                            <ul class="list-group list-group-unbordered">
                                <?php 
                                //SELECT `id_usuario_asignado_credito`, `id_credito`, `id_usuario_credito`, `tipo_usuario_asignado`, `tag_tipo_usuario` FROM `usuario_asignado_credito` WHERE 1
                                if(!empty($queryActividad)){
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
                                        //$actividad_usuario_lyt .= "<button class='btn-link btn-xs pull-right godetails' data-id=".$idCredito_actividad.">";
                                        //$actividad_usuario_lyt .= "Ver más<i class='fa fa-chevron-right margin-left-xs'></i>";
                                        //$actividad_usuario_lyt .= "</button>";
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
            </div>
            
        </div>
        </div>
        <section class="content">    
            
            <h2 class="page-header">Historial crediticio</h2>    
            <div class="row">                
                <div class="col-xs-12 ">
                    
                    <div class="box">                    
                        <div class="box-body table-responsive">
                            <table id="printdatatbl" class="table table-hover" style="width:100%;">     
                                <thead>                                
                                    <tr>                                        
                                        <th >Ref. Credito</th>       
                                        <th >Detalles</th>
                                        <th >Ciudad</th>
                                        <th >Status</th>
                                        <th >Progreso de pago</th>                                        
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
                                        $fecha_InicioCredito = ($dcdKey['fechaabre'] != "0000-00-00")? date("d/m/Y", strtotime($dcdKey['fechaabre'])) : "-"; 
                                        $fecha_FinCredito = ($dcdKey['fechacierra'] == "0000-00-00")? "" : date("d/m/Y", strtotime($dcdKey['fechacierra'])); 

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
                                        
                                        //PLAN DE PAGOS
                                        $datas_planpago = array();
                                        $datas_planpago = queryPlanPago($id_Credito);
                                        $valorPagar = 0;
                                        $valorPagar_format = 0;
                                        $fecha_fincredito_pactado = "";
                                        if(is_array($datas_planpago) && !empty($datas_planpago)){
                                            foreach($datas_planpago as $dppKey){
                                                $valorPagar = $datas_planpago["valor_pagar_credito"];
                                                $valorPagar_format = number_format($valorPagar, 0, ',', '.');
                                                
                                                $fecha_fincredito_pactado = date("d/m/Y",strtotime($datas_planpago["fecha_fin_plan_pago"]));
                                            }
                                        }

                                        //CUOTAS  -/RECAUDOS/-
                                        $calculo_valor_prestado = 0;
                                        $calculo_valor_credito = 0;
                                        $calculo_valor_recaudado = 0;
                                        $calculo_valor_porcobrar = 0;

                                        $cuotas_pagadas = 0;
                                        $cuotas_pagadas_full = 0;
                                        $cuotas_enmora = 0;
                                        $total_numero_cuotas_credito = count($datas_Cuotas);
                                        $progress_bar_credito = 0;
                                            
                                        $fechaPrimerRecaudo ="";

                                        if(is_array($datas_Cuotas) && !empty($datas_Cuotas)){
                                            foreach($datas_Cuotas as $dcKey){                                   
                                                $cuota_status = $dcKey['id_status_recaudo'];
                                                $cuota_numero = $dcKey['numero_cuota_recaudos'];
                                                $cuota_capital = $dcKey['capital_cuota_recaudo'];
                                                $cuota_interes = $dcKey['interes_cuota_recaudo'];
                                                $cuota_activa_mora = $dcKey['activa_mora'];
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
                                                $calculo_valor_credito += $cuota_valor_cuota;*/

                                                //CALCULO VALOR RECAUDADO
                                                if($cuota_status != "3"){
                                                    $calculo_valor_recaudado += $cuota_valor_recaudado; 
                                                }

                                                //PROGRESO DE DINERO RECIBIDOS EN ESTE CREDITO
                                                if($cuota_status != "3"){
                                                    $cuotas_pagadas++; 
                                                }
                                                
                                                //DEIFINIR LA PRIMERA FECHA RECAUDO
                                                if($cuota_numero == "1"){
                                                    $fechaPrimerRecaudo =  date("d/m/Y",strtotime($dcKey['fecha_max_recaudo']));
                                                }
                                                
                                                //NUMERO CUOTAS PAGADAS COMPLETAS
                                                if($cuota_status == "1"){
                                                    $cuotas_pagadas_full++; 
                                                }
                                                
                                                //NUMERO CUOTAS EN MORA
                                                if($cuota_activa_mora == 1){
                                                    $cuotas_enmora++;
                                                }

                                            }/*FIN FOREACH[$datas_Cuotas]*/
                                            
                                            //PROCESO DE PAGO CREDITO
                                            $progress_bar_credito = ceil(($cuotas_pagadas/$total_numero_cuotas_credito)*100);
                                            
                                            //VALOR POR COBRAR
                                            $calculo_valor_porcobrar = $valorPagar - $calculo_valor_recaudado;
                                            $calculo_valor_porcobrar_format = number_format($calculo_valor_porcobrar, 0, ',', '.'); 
                                            
                                        }/*FIN EXISTE ARRAY[$datas_Cuotas]*/


                                        /*
                                        *LAYOUT CREDITOS
                                        */
                                        $historial_lyt = "";
                                        $historial_lyt .= "<tr>";

                                        $historial_lyt .= "<td>";/*--/REFERENCIA/--*/
                                        $historial_lyt .= $consecutivo_Credito;
                                        $historial_lyt .= "</td>";/*--/REFERENCIA/--*/
                                        
                                        
                                        $historial_lyt .= "<td>";/*--/DETALLES/--*/
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Tipo credito</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$tipoCredito."</a>";    
                                        $historial_lyt .= "</p>";
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Apertura credito</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$fechaPrimerRecaudo."</a>";    
                                        $historial_lyt .= "</p>";
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Cierre pactado</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$fecha_fincredito_pactado."</a>";    
                                        $historial_lyt .= "</p>";
                                        
                                        
                                        if($fecha_FinCredito != ""){
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Cierre definitivo</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$fecha_FinCredito."</a>";    
                                        $historial_lyt .= "</p>";
                                            
                                        }
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Valor credito</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'><span class='margin-right-xs'>$</span>".$valorPagar_format."</a>";    
                                        $historial_lyt .= "</p>";
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong># Cuotas credito</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$total_numero_cuotas_credito."</a>";    
                                        $historial_lyt .= "</p>";
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong># Cuotas pagadas</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$cuotas_pagadas_full."</a>";    
                                        $historial_lyt .= "</p>";                  
                                        
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong># Cuotas en mora</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'>".$cuotas_enmora."</a>";    
                                        $historial_lyt .= "</p>";                  
                                                                            
                                        $historial_lyt .= "<p class='text-muted'>"; 
                                        $historial_lyt .= "<strong>Saldo</strong>";                                            
                                        $historial_lyt .= "<a class='pull-right'><span class='margin-right-xs'>$</span>".$calculo_valor_porcobrar_format."</a>";    
                                        $historial_lyt .= "</p>";
                                        

                                        $historial_lyt .= "</td>";/*--/DETALLES/--*/
                                        
                                        $historial_lyt .= "<td>";/*--/CIUDAD/--*/
                                        $historial_lyt .= $ciudad_acreedor;
                                        $historial_lyt .= "</td>";/*--/CIUDAD/--*/
                                        
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
                    
                     
                </div>
            </div>
        </section>
        
        <?php }else{/*SI NO EXISTE ARRAY[$datasCreditos_deduor]*/      ?>  
                    
        <div class="box50">            
            <div class="alert alert-dismissible bg-gray">
                <div class="media">
                    <div class=" media-left">
                        <i class="fa fa-info-circle fa-4x text-blue margin-hori-xs"></i>
                    </div>
                    <div class="media-body">
                        <h3 class="no-padmarg">Sin registros</h3>
                        <p>
                            El número de cédula
                            <strong class="text-size-x4 margin-hori-xs"><?php echo $valueInput; ?></strong>
                            que deseas consultar no tiene registros en el sistema
                        </p>
                        
                    </div>

                </div>                    
            </div>
        </div>

        <?php }  /*FIN ARRAY[$datasCreditos_deduor]*/ ?>  
        
        <?php } ?>
                

    </div>
    <?php echo "<input id='paththisfile' type='hidden' value='".$pathFile."/details/'/>"; ?>
    <?php echo "<input id='pathnewcredit' type='hidden' value='".$pathmm."/credits/new/'/>"; ?>
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
<script src="../appweb/plugins/misc/jquery.redirect.js"></script>    
<!-- DataTables -->
<script src="../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    $('#quserform').keydown(function(e) {
        var qval = $('#searchuser').val();
        var code = (e.keyCode) ? e.keyCode : e.which;
        /*if(qval == ""){
            return false;    
        }*/
        
        if (code == 13) {
            if (qval == "") {
                return false;             
            } else {
                $('#quserform').submit();                
            }
            return false;
        }
        
    });
    
    $('.justnumber').keyup(function (){ 
        this.value = (this.value + '').replace(/[^0-9]/g, '');        
    });
    
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
    //click en el boton consultar
    /*$("#searchuser").keypress(function(){

        var qval = $(this).val();
        
        if(qval == ""){
            return false;    
        }
        
        $("#quserform").submit();*/
        
        /*$(function(){
            $('#printdatatbl').DataTable( {	
                "autoWidth": false,
                "order": [[ 1, "asc" ]],
                "processing": true,
                "bDeferRender": true,			
                "sPaginationType": "full_numbers",
                "ajax": {
                    "url": "query-recaudos.php?",
                    "type": "POST",                    
                    "data": {
                        "qval" : qval                        
                    }
                },
                "aoColumns": [
                    {
                       "mData": "documento", "aTargets": [0],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "nombre", "aTargets": [1],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "tel1", "aTargets": [2],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "tel2", "aTargets": [3],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "direccion", "aTargets": [4],
                       "render": function (data) {
                           return data;
                       }
                    },
                    {
                       "mData": "fecharegistro", "aTargets": [5],
                       "render": function (data) {
                           return data;
                       }
                    }
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
        });*/
   // });    
    
</script>       
</body>
</html>