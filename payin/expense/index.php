<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars-payin.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php require_once '../../appweb/inc/query-payin.php'; ?>
<?php require_once '../../i18n-textsite.php'; ?>

<?php 

//***********
//DEFINE CANCEL - TRASH EVENT
//***********
$statusCancel = "";
if(isset($_GET['trash']) && $_GET['trash'] == "ok"){ 
    $statusCancel = 1;
}

//definimos id del acreedor
$db->where("id_cobrador",$idSSUser);
$queryAcreedor = $db->getOne("cobrador_tbl", "id_acreedor");
$idAcreedor = $queryAcreedor['id_acreedor'];
        
//define id de la ruta
$codruta = "";
$db->where("id_cobrador",$idSSUser);
$db->where("fecha_creacion_ruta",$dateFormatDB);
$queryRuta = $db->getOne("rutas_tbl", "consecutivo_ruta");
$codruta = $queryRuta['consecutivo_ruta'];




//***********
//SITE MAP
//***********

$rootLevel = "gastos";
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
    <?php include '../../appweb/tmplt/header-payin.php';  ?>           
    
    <?php
    /*
    /
    ////SIDEBAR
    /
    */
    ?>
    <?php //include '../../appweb/tmplt/side-mm.php';  ?>
    <?php
    /*
    /
    ////WRAP CONTENT
    /
    */
    ?>        
    <div class="content-wrapper box50" style="padding-top:20px; margin-left: auto; margin-right:auto;">
        <?php
        /*
        /*****************************//*****************************
        /HEADER CONTENT
        /*****************************//*****************************
        */
        ?>
        <section class="content-header bg-content-header">
            <h1>
                <span class="text-size-x6">Gastos</span> / Declarar gastos
            </h1>      
            <a href="<?php echo $pathmm."/".$payinDir."/home/"; ?>" class="ch-backbtn">
                <i class="fa fa-arrow-left"></i>
                Volver a Inicio
            </a> 
        </section>
        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>
        
        <?php if($codruta != ""){ ?>
                
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <form method="post" id="expenseform" autocomplete="off" >
                        
                        <div class="form-group">
                            <label>Valor gastos</label>
                            <div class="input-group">                                            
                                <div class="input-group-addon"><i class="fa fa-dollar"></i></div>
                                <input type="text" class="form-control monedaval" name="valorgastos"/>
                            </div>
                        </div>
                        
                        <div class="margin-bottom-xs">
                            <label class="margin-right-md">Descripción</label>
                            <textarea id="descrigastosinput" name="descrigastosinput" class="form-control" placeholder="Lista los items que te generaron gastos en este día" style="width: 100%; height: 120px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize:none;"></textarea>
                        </div>
                                                
                        <button id="addpay" type="button" class="btn btn-info btn-flat btn-block">
                            <i class="fa fa-save fa-lg margin-right-xs"></i>
                            Guardar
                        </button>
                                                
                        <input type="hidden" name="prestavar" value="<?php echo $idAcreedor; ?>">
                        <input type="hidden" name="cobradorvar" value="<?php echo $idSSUser; ?>">                        
                        <input type="hidden" name="refruta" value="<?php echo $codruta; ?>">
                        
                    </form> 
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12">
                    <div id="wrapadditem"></div>
                    <div id="erradditem"></div>                        
                </div>
            </div>
        </section>
        
        
        <?php }else{ ?>
        
        <section class="content ">                    
            <div class="box50">
                <div class="alert">
                    <div class="media text-muted">
                        <div class=" media-left">
                            <i class="fa fa-bell fa-4x"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="no-padmarg">Oops!</h3>
                            <p style="font-size:1.232em; line-height:1;">
                                Para poder ingrezar tu registro de gastos para este día. Es necesario que tengas una ruta asignada.
                            </p>                                          
                        </div>
                    </div>                    
                </div>
                
                <div class="margin-verti-xs">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group text-center">
                            <a href="<?php echo $pathmm."/payin/home/"; ?>" type="button" class="btn btn-default">
                                <i class='fa fa-home fa-lg margin-right-xs'></i>
                                <span>Volver al inicio</span>                        
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
</div>
<?php echo _JSFILESLAYOUT_ ?>
<script src="creud-new-expense.js" type="text/javascript"></script>
<script type="text/javascript" src="../../appweb/plugins/jquery.number.js"></script>      
<script>
$(document).ready(function(){     
    $('.monedaval').number( true, 0 );
});    

</script>      
</body>
</html>
