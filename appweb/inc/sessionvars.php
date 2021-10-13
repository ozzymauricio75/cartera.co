<?php
$sessionArr = array();
$MM_restrictGoTo = $pathmm;

if (!((isset($_SESSION['cartera_user_account'])) && (is_array($_SESSION['cartera_user_account'])))) {   
    	
    $_SESSION['cartera_user_account'] = NULL;
    unset($_SESSION['cartera_user_account']);
      
    $jumpLook = '<script type="text/javascript">';
    $jumpLook .='window.location.href="'.$MM_restrictGoTo.'";';
    $jumpLook .='</script>';
    $jumpLook .='<noscript>';
    $jumpLook .='<meta http-equiv="refresh" content="0;url='.$MM_restrictGoTo.'" />';
    $jumpLook .='</noscript>';
    echo $jumpLook;
    exit;            
                        
}else{
          
    $sessionArr[] = $_SESSION['cartera_user_account'];
    foreach($sessionArr as $userKey){
        $idSSUserCurrent = $userKey['iduser'];        
        $idSSUser = $userKey['idplataformauser'];
        $permisoSSUser = $userKey['permisousuario'];
        $pseudoSSUser = $userKey['nicknameuser'];
        $nameSSUser = $userKey['nameuser'];    
        $lnameSSUser ="";
        $emailSSUser = $userKey['mailuser'];   
        $imgSSUser ="";
    }
	$userSessionArea = "ok";
    $actisession = "ok";    
    
    
    //PATH FOTO DEFAULT
    $imgSSUserDefault = $pathmm."img/nopicture.png";
    //PORTADA
    $pathImgSSUser = "../../../files-display/acreedor/".$imgSSUser;

    if (file_exists($pathImgSSUser)) {
    $portadaImgSSUser = $pathImgSSUser;
        } else {
    $portadaImgSSUser = $imgSSUserDefault;
    }
    
    //VERIFICA NIVEL USUARIO
    if($permisoSSUser != "acreedor"){
        
        $_SESSION['cartera_user_account'] = NULL;
        unset($_SESSION['cartera_user_account']);

        $jumpLook = '<script type="text/javascript">';
        $jumpLook .='window.location.href="'.$MM_restrictGoTo.'";';
        $jumpLook .='</script>';
        $jumpLook .='<noscript>';
        $jumpLook .='<meta http-equiv="refresh" content="0;url='.$MM_restrictGoTo.'" />';
        $jumpLook .='</noscript>';
        echo $jumpLook;
        exit();         
    }
    
}

/*$idSSUser = "1";
$pseudoSSUser = "user";
$nameSSUser = "name";
$lnameSSUser = "user";     
$emailSSUser = "user@sitiocuenta.com";
$portadaImgSSUser ="";*/