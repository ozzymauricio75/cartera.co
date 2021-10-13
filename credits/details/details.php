<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php require_once '../appweb/inc/query-orders.php'; ?>
<?php require_once '../i18n-textsite.php'; ?>

<?php 
//recibe datos de PEDIDOS
$idOrder = "";
$dataOrders = array();
//$dataOrders = ordersQuery($idOrder);
//$dataOrders = unique_multidim_array($dataOrders, 'cod_orden_compra');

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
    <link rel="stylesheet" href="../appweb/plugins/datatables/dataTables.bootstrap.css">
    <?php echo _FAVICON_TOUCH_ ?>    
</head>
    
<?php echo LAYOUTOPTION ?><!---//print body tag--->    

    
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
                <span class="text-size-x6">Credito</span> / Detalles
            </h1>      
            <a href="<?php echo $pathmm."/credits/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Lista de creditos
            </a> 
        </section>
        <section class="content">
            <div class="row text-center">
                <div class="col-xs-12 col-sm-4">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 light-blue lighten-1 white-text padd-verti-xs img-rounded">
                            <strong class="fa-3x">$5.00</strong>
                        </div>
                    </a>
                    <h3>Monto</h3>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 green accent-3 white-text padd-verti-xs img-rounded">
                            <strong class="fa-3x">$5.00</strong>
                        </div>
                    </a>
                    <h3>Recaudado</h3>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <a href="#" type="button" data-toggle="modal" data-target="#detallevalores">
                        <div class="box25 deep-orange white-text padd-verti-xs img-rounded">
                            <strong class="fa-3x">$5.00</strong>
                        </div>
                    </a>
                    <h3>Debe</h3>
                </div>
            </div>
        </section>


        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <section class="content ">
            <h3 class="padd-left-xs">Sobre el prestamo</h3>
            <!---
                DETALLES CREDITO
            -->
            <div class="row margin-bottom-xs">
                <div class="col-xs-12 ">
                    <span class="text-blue text-size-x5">Ref. CRE-000001</span>
                </div>
                <div class="clearfix visible-xs hidden-sm hidden-md hidden-lg"></div>
                <div class="col-xs-6">
                    <h4>
                        Fecha inicio:
                        <span class="text-size-x3 badge bg-black">01/01/2017</span>
                    </h4>
                </div>
                <div class="col-xs-6">
                    <h4>
                        Fecha fin:
                        <span class="text-size-x3 badge bg-black">01/15/2017</span>
                    </h4>
                </div>
                <div class="clearfix visible-xs hidden-sm hidden-md hidden-lg"></div>
                <div class="col-xs-12 col-sm-6">
                    <h3>
                        Monto
                        <span class="text-size-x5 badge bg-blue padd-hori-xs padd-verti-xs" style="display:block;">$ 5.00</span>
                    </h3>
                
                   <h3>
                        Tipo de credito
                        <span class="text-size-x5 badge bg-blue padd-hori-xs padd-verti-xs" style="display:block;">Financiero</span>
                    </h3>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <h3>Descripción</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eu faucibus augue, dapibus volutpat eros. Maecenas massa neque, vehicula a finibus at, rutrum in est. </p>
                </div>

            </div>
            
        </section>
        
        
        <section class="content ">
            <h3 class="padd-left-xs">Plan de pagos</h3>
            <div class="row">
                <div class="col-xs-12">
                    <ul class="list-unstyled list-inline text-size-x4">
                        <li>
                            <p>Cuotas: <strong class="margin-left-xs">15</strong></p>
                        </li>
                        <li>
                            <p class="margin-left-xs">
                                Periocidad: <strong class="margin-left-xs">Dia</strong> 
                            </p>
                        </li>
                    </ul>
                    
                    
                    <ul class="list-unstyled list-inline text-size-x4">
                        <li>
                            <p>Valor cuota: <strong class="margin-left-xs badge bg-black">$ 5.00</strong></p>
                        </li>
                        <li>
                            <p>Intereses: <strong class="margin-left-xs badge bg-black">$ 5.00</strong></p>
                        </li>
                        <li>
                            <p>Mora: <strong class="margin-left-xs badge bg-black">$ 5.00</strong></p>
                        </li>
                        <li>
                            <p>Dias: <strong class="margin-left-xs badge bg-black">3</strong></p>
                        </li>
                    </ul> 
                    
                    <ul class="list-unstyled list-inline text-size-x4">
                        <li>
                            <p>Sobrecargo: <strong class="margin-left-xs badge bg-black">$ 5.00</strong></p>
                        </li>
                        <li>
                            <p>Total cuota: <strong class="margin-left-xs badge bg-black">$ 5.00</strong></p>
                        </li>
                    </ul> 
                </div>
            </div>
            
            <h3 class="padd-left-xs">Sobre el deudor</h3>
            <div class="row margin-bottom-md">
                <div class="col-xs-12">
                    <!---
                        DETALLES DEUDOR
                    -->
                    <div class="media">
                        <div class="media-left">
                            <i class="fa fa-user fa-5x margin-top-md padd-hori-md"></i>
                        </div>
                        <div class="media-body">
                            <h2>
                                Guillermo Jimenez
                                <span class="text-size-x4" style="display:block;">CC. 14.555.555</span>
                            </h2>
                            <dl class="dl-horizontal-custom">                
                                <dt>Tel 1:</dt>
                                <dd>585 5555</dd>
                                <dt>Tel 2:</dt>
                                <dd>351 555 5555</dd>
                                <dt>Email:</dt>
                                <dd>guillermo@hotmail.com</dd>
                                <dt>Recidencia:</dt>
                                <dd>Cra. 55 # 55-55 </dd>
                                <dt>Ciudad:</dt>
                                <dd>Cali</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <h3 class="padd-left-xs">Sobre el cobrador</h3>
            <div class="row margin-bottom-md">
                <div class="col-xs-12">
                    <!---
                        DETALLES DEUDOR
                    -->
                    <div class="media">
                        <div class="media-left">
                            <i class="fa fa-motorcycle fa-5x margin-top-md padd-hori-md"></i>
                        </div>
                        <div class="media-body">
                            <h2>
                                Guillermo Jimenez
                                <span class="text-size-x4" style="display:block;">CC. 14.555.555</span>
                            </h2>
                            <dl class="dl-horizontal-custom">                
                                <dt>Tel 1:</dt>
                                <dd>585 5555</dd>
                                <dt>Tel 2:</dt>
                                <dd>351 555 5555</dd>
                                <dt>Email:</dt>
                                <dd>guillermo@hotmail.com</dd>
                                <dt>Recidencia:</dt>
                                <dd>Cra. 55 # 55-55 </dd>
                                <dt>Ciudad:</dt>
                                <dd>Cali</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <h3 class="padd-left-xs">Recaudos</h3>    
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    
                    <!-- Box Comment -->
                    <div class="box box-widget">
                        <div class="box-header with-border">
                            <div class="user-block">
                                <i class="fa fa-motorcycle fa-lg margin-top-xs"></i>
                                <span class="username"><a href="#">Jonathan Burke Jr.</a></span>
                                <span class="description">dd/mm/aa</span>
                            </div>
                            
                        </div>
                        <!-- /.box-header -->
                        
                        <!-- /.box-body -->
                        <div class="box-body box-comments">
                          <div class="box-comment">                       
                            <i class="fa fa-archive fa-lg" ></i>
                            <div class="comment-text">
                                <!---
                                    DETALLES CREDITO
                                -->
                                <div class="row margin-bottom-xs">
                                    <div class="col-xs-4 col-sm-3 ">
                                        Ref. CRE-000001
                                    </div>
                                    <div class="clearfix visible-xs hidden-sm hidden-md hidden-lg"></div>
                                    <div class="col-xs-4 col-sm-3">
                                        Cuota: 05
                                    </div>
                                    <div class="col-xs-4 col-sm-3">
                                        Status: <span class="badge bg-green">Pago</span>
                                    </div>
                                </div>
                                
                                <!---
                                    DETALLES CUOTA
                                -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">                                            
                                            <label>
                                                <span class="badge bg-green padd-hori-xs">&nbsp;</span>
                                                <span class="margin-left-xs">Capital:</span> <span class="margin-left-xs">$5.0</span>
                                            </label>
                                        </div>
                                        <div class="form-group">                                            
                                            <label>
                                                <span class="badge bg-gray padd-hori-xs">&nbsp;</span>
                                                <span class="margin-left-xs">Interes:</span> <span class="margin-left-xs">$5.0</span>
                                            </label>
                                        </div>
                                        <div class="form-group">                                            
                                            <label>
                                                <span class="badge bg-gray padd-hori-xs">&nbsp;</span>
                                                <span class="margin-left-xs">Sobrecargo:</span> <span class="margin-left-xs">$5.0</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!---
                                    DETALLES DEUDOR
                                -->
                                
                                <dl class="dl-horizontal-custom">
                                    <dt>Deudor:</dt>
                                    <dd>Nombre del dudor</dd>
                                    <dt>Tel 1:</dt>
                                    <dd>585 5555</dd>
                                    <dt>Tel 2:</dt>
                                    <dd>351 555 5555</dd>
                                </dl>
                                
                                <!---
                                    COMENTARIOS
                                -->
                                
                                <span class="username">
                                    Comentarios                                       
                                </span><!-- /.username -->
                                    It is a long established fact that a reader will be distracted
                                    by the readable content of a page when looking at its layout.
                                <span class="text-muted pull-right margin-top-xs">8:03 PM Today</span>
                            </div>
                            <!-- /.comment-text -->
                          </div>                          
                        </div>

                    </div>
                    <!-- /.box -->
                </div><!---/WRAPP STREAM--->
                
                <div class="col-xs-12 col-sm-6">
                    
                    <!-- Box Comment -->
                    <div class="box box-widget">
                        <div class="box-header with-border">
                            <div class="user-block">
                                <i class="fa fa-motorcycle fa-lg margin-top-xs"></i>
                                <span class="username"><a href="#">Jonathan Burke Jr.</a></span>
                                <span class="description">dd/mm/aa</span>
                            </div>
                            
                        </div>
                        <!-- /.box-header -->
                        
                        <!-- /.box-body -->
                        <div class="box-body box-comments">
                          <div class="box-comment">                       
                            <i class="fa fa-archive fa-lg" ></i>
                            <div class="comment-text">
                                <!---
                                    DETALLES CREDITO
                                -->
                                <div class="row margin-bottom-xs">
                                    <div class="col-xs-4 col-sm-3 ">
                                        Ref. CRE-000001
                                    </div>
                                    <div class="clearfix visible-xs hidden-sm hidden-md hidden-lg"></div>
                                    <div class="col-xs-4 col-sm-3">
                                        Cuota: 05
                                    </div>
                                    <div class="col-xs-4 col-sm-3">
                                        Status: <span class="badge bg-green">Pago</span>
                                    </div>
                                </div>
                                
                                <!---
                                    DETALLES CUOTA
                                -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">                                            
                                            <label>
                                                <span class="badge bg-green padd-hori-xs">&nbsp;</span>
                                                <span class="margin-left-xs">Capital:</span> <span class="margin-left-xs">$5.0</span>
                                            </label>
                                        </div>
                                        <div class="form-group">                                            
                                            <label>
                                                <span class="badge bg-gray padd-hori-xs">&nbsp;</span>
                                                <span class="margin-left-xs">Interes:</span> <span class="margin-left-xs">$5.0</span>
                                            </label>
                                        </div>
                                        <div class="form-group">                                            
                                            <label>
                                                <span class="badge bg-gray padd-hori-xs">&nbsp;</span>
                                                <span class="margin-left-xs">Sobrecargo:</span> <span class="margin-left-xs">$5.0</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!---
                                    DETALLES DEUDOR
                                -->
                                
                                <dl class="dl-horizontal-custom">
                                    <dt>Deudor:</dt>
                                    <dd>Nombre del dudor</dd>
                                    <dt>Tel 1:</dt>
                                    <dd>585 5555</dd>
                                    <dt>Tel 2:</dt>
                                    <dd>351 555 5555</dd>
                                </dl>
                                
                                <!---
                                    COMENTARIOS
                                -->
                                
                                <span class="username">
                                    Comentarios                                       
                                </span><!-- /.username -->
                                    It is a long established fact that a reader will be distracted
                                    by the readable content of a page when looking at its layout.
                                <span class="text-muted pull-right margin-top-xs">8:03 PM Today</span>
                            </div>
                            <!-- /.comment-text -->
                          </div>                          
                        </div>

                    </div>
                    <!-- /.box -->
                </div><!---/WRAPP STREAM--->
            
            </div><!--/WRAPP MAIN--->   
        </section>
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
    <?php include '../appweb/tmplt/right-side.php';  ?>
</div>
<?php echo _JSFILESLAYOUT_ ?>
<!-- DataTables -->
<script src="../appweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../appweb/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    
    $('#printdatatbl').DataTable({        
        "scrollX": false,
        "ordering": true,
        "autoWidth": false
    });
  });
</script>    
</body>
</html>
