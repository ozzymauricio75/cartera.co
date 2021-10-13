<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php //require_once '../appweb/inc/query-credits.php'; ?>
<?php require_once '../i18n-textsite.php'; ?>

<?php

//dinero prestado


//***********
//SITE MAP
//***********

$rootLevel = "reportes";
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

    <?php echo _JSFILESLAYOUT_ ?>
    <script src="../appweb/plugins/misc/jquery.redirect.js"></script>
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
                <span class="text-size-x6">Reportes</span>
            </h1>
        </section>



        <?php
        /*
        /*****************************//*****************************
        /MAIN WRAPPER CONTEN
        /*****************************//*****************************
        */
        ?>

        <section class="content">
            <div class="box50">
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a href="<?php echo $pathFile."/_gastos/"; ?>">
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Gastos</span>
                                <p class="description">Listar los gastos de un rango de fechas detallando valores</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $pathFile."/_recaudos/"; ?>">
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Recaudos</span>
                                <p class="description">Listar los recaudos del dia para cada cobrador</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?php //echo $pathFile."/_acreedores/"; ?>">                                                          
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Acreedores</span>
                                <p class="description">Listar todos los acreedores y mostrar el número de créditos activos que tengan cada uno</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $pathFile."/_cuadrecaja/"; ?>">
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Cuadre de caja</span>
                                <p class="description">Listar detalles del cuadre diario</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $pathFile."/_planespago/"; ?>">
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Planes de pago</span>
                                <p class="description">Listar detalles de plan de pago por deudor</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $pathFile."/_credito/"; ?>">
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Creditos</span>
                                <p class="description">Listar la historia de un crédito en específico</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $pathFile."/_historico-recaudos/"; ?>">
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Historico recaudos</span>
                                <p class="description">Listar los recaudos que un cobrador halla realizado en un rango de fechas definido</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $pathFile."/_escapados/"; ?>">
                            <i class="fa fa-chevron-right pull-right text-blue margin-top-xs"></i>
                            <div class="user-block">
                                <i class="fa fa-paste fa-3x"></i>
                                <span class="username">Deudores escapados</span>
                                <p class="description">Deudores dificil cartera</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

        </section>


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

</body>
</html>
