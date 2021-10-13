<?php require_once 'appweb/lib/MysqliDb.php'; ?>
<?php require_once 'cxconfig/config.inc.php'; ?>
<?php require_once 'cxconfig/global-settings.php'; ?>
<?php require_once 'appweb/lib/gump.class.php'; ?>
<?php require_once 'appweb/inc/site-tools.php'; ?>
<?php require_once "appweb/lib/password.php"; ?>
<?php require_once 'login/login.inc.php'; ?>

<!DOCTYPE html>
<html class="bg-login" lang="<?php echo LANG ?>">
<head>
    <meta charset="utf-8">
    <title>Prestamos - LogIn</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="author" content="massin">    
    
    <?php echo _CSSFILESLAYOUT_ ?> 
    <?php echo _FAVICON_TOUCH_ ?> 
    
</head>
<body class="bglight">
    
    <div class="wrapplabel" style="">
        <div class="labelcaption">
            <i class="fa fa-shekel"></i>
            <p>
                Bienvenido a Cartera
            </p>
        </div>
    </div>
    
    <div class="container-fluid wraplogin" >
                
        <div class=" wrapform " >
            <?php 
            /*
                *
                *---------------------------
                *IMPRIME ERRORES VALIDACION
                *---------------------------
                *    
            */            
            if(isset($errValidaTmpl) && $errValidaTmpl != ""){ echo $errValidaTmpl; }            
            ?>
            <form action="<?php echo $pathFile; ?>/" method="post" id="to_loginform" autocomplete="off"> 
                <div class="form-group form-group-lg has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="Usuario">
                    <span class="form-control-feedback fa fa-user"></span>
                </div>
                
                <div class="form-group form-group-lg has-feedback">
                    <input type="password" class="form-control" name="passuser" placeholder="Contraseña">
                    <span class="form-control-feedback fa fa-lock"></span>
                </div>
                                             
                <button type="submit" class="btn btn-flat btn-primary btn-lg btn-block">Entrar</button>
                <input name="tologin" value="ok" type="hidden"/>
            </form>
            
        </div>
        
    </div>
    <?php echo _JSFILESLAYOUT_ ?>   
    <script type="text/javascript" src="appweb/plugins/form-validator/jquery.form-validator.min.js"></script>            
    <script type="text/javascript" src="appweb/js/to-loginform.js"></script>
    
    
</body>
</html> 