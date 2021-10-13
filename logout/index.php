<?php require_once '../cxconfig/global-settings.php'; ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$logoutGoTo = $pathmm;

if(isset($_SESSION['cartera_user_account'])){
    $_SESSION['cartera_user_account'] = NULL;
    unset($_SESSION['cartera_user_account']);

    header("Location: $logoutGoTo");
    exit;    
}