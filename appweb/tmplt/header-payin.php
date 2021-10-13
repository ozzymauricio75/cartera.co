<?php
$home_root = "";
$mapa_root = "";
$recaudos_root = "";
$gastos_root = "";
$abonos_root = "";

switch($rootLevel){
    case "home":
        $home_root = "active";
    break;

    case "mapa":
        $mapa_root = "active";
    break;

     case "recaudos":
        $recaudos_root = "active";
    break;

    case "gastos":
        $gastos_root = "active";
    break;
    case "abonos":
        $abonos_root = "active";
    break;
}


$headerPayin_lyt ='<header class="headerpayin row">';
$headerPayin_lyt .='<div class="col-xs-10 text-center">';
$headerPayin_lyt .='<p style="margin-top:11px;">
            <span class="margin-right-xs txtCapitalice text-shadow" style="font-size:12px; color:#fff;">Hola, </span>
            <span class="margin-right-xs txtCapitalice text-shadow" style="font-size:14px; color:#fff;">'.$nameSSUser.'</span>
        </p>';
$headerPayin_lyt .='</div>';
$headerPayin_lyt .='<div class="col-xs-2">
        <a href="'.$pathmm.'/logout/" class="btnout">
            <i class="fa fa-sign-out"></i>
        </a>
    </div>';

$headerPayin_lyt .='<div class="col-xs-12"><hr style="border-color:#525252; margin-top:2px; margin-bottom:2px;"/></div>';
$headerPayin_lyt .='<div class="col-xs-12 text-center">
        <span class="" style="">
            <span class="margin-right-xs txtCapitalice text-shadow" style="font-size:22px; color:#fff;">'.$nombre_dia.'</span>
            <span class="margin-right-xs txtCapitalice text-shadow" style="font-size:15px; color:#fff;">'.$dateFormatHuman.'</span>
        </span>
    </div>';

$headerPayin_lyt .='</header>';//[FIN | header]

$headerPayin_lyt .='<div class="mmpayin">
    <div class="mmp-item '.$home_root.'">
        <a href="'.$pathmm.'/'.$payinDir.'/home/">
            <i class="fa fa-home"></i>
            <p>Inicio</p>
        </a>
    </div>';
$headerPayin_lyt .='<div class="mmp-item '.$mapa_root.'">
        <a href="'.$pathmm.'/'.$payinDir.'/mapa/">
            <i class="fa fa-map-marker"></i>
            <p>Mapa</p>
        </a>
    </div>';
$headerPayin_lyt .='<div class="mmp-item '.$recaudos_root.'">
        <a href="'.$pathmm.'/'.$payinDir.'/recaudos/">
            <i class="fa fa-money"></i>
            <p>Recaudos</p>
        </a>
    </div>';

$headerPayin_lyt .='<div class="mmp-item '.$gastos_root.'">
        <a href="'.$pathmm.'/'.$payinDir.'/expense/">
            <i class="fa fa-dollar"></i>
            <i class="fa fa-long-arrow-down"></i>
            <p>Gastos</p>
        </a>
    </div>';
    $headerPayin_lyt .='<div class="mmp-item '.$abonos_root.'">
            <a href="'.$pathmm.'/'.$payinDir.'/abonos/">
                <i class="fa fa-dollar"></i>
                <i class="fa fa-money"></i>
                <p>Abonos</p>
            </a>
        </div>';
$headerPayin_lyt .='</div>';//[FIN | .mmpayin]

echo $headerPayin_lyt;
