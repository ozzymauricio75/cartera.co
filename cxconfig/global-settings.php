<?php
if (!isset($_SESSION)) {
  session_start();
}
//////////////////////////////////////
//DEFINE PATH SERVIDOR
//////////////////////////////////////
$protocol = "http://";
$httphost  = $_SERVER["HTTP_HOST"];
$servername = $_SERVER["SERVER_NAME"];
$uriFILE = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
//$uriDIR = "/projects/prestamos";
$uriDIR = "";  

//path global
$pathsite = $protocol.$httphost;

//path main menu
$pathmm = $protocol.$httphost.$uriDIR;

//path this file
$pathFile = $protocol.$httphost.$uriFILE;

//directorys name section web
$admiDir = "";
$collectDir = "collect";
$usersDir = "users";
$creditsDir = "credits";
$payinDir = "payin";

//METATAGS
define('METATITLE', 'Cartera');

//LAYOUT OPTIONS SKYN OPTION
//BOXED - FIXED - TOGLE SIDEBAR
$bodyLayoutCustom = "<body class='";
$bodyLayoutCustom .= "hold-transition";
$bodyLayoutCustom .= " skin-blue";   
$bodyLayoutCustom .= " fixed";
//$bodyLayoutCustom .= " sidebar-mini";
$bodyLayoutCustom .= "'>";
define('LAYOUTOPTION', $bodyLayoutCustom);

// Fav and touch icons 
$favicon_touch = "<link rel='apple-touch-icon' sizes='180x180' href='".$pathmm."/appweb/img/favicons/apple-touch-icon.png'>";
$favicon_touch .= "<link rel='icon' type='image/png' sizes='32x32' href='".$pathmm."/appweb/img/favicons/favicon-32x32.png'>";
$favicon_touch .= "<link rel='icon' type='image/png' sizes='16x16' href='".$pathmm."/appweb/img/favicons/favicon-16x16.png'>";
$favicon_touch .= "<link rel='manifest' href='".$pathmm."/appweb/img/favicons/manifest.json'>";
$favicon_touch .= "<link rel='mask-icon' href='".$pathmm."/appweb/img/favicons/safari-pinned-tab.svg' color='#008DC4'>";
$favicon_touch .= "<meta name='theme-color' content='#008DC4'>";
define('_FAVICON_TOUCH_', $favicon_touch);


//////////////////////////////////////
//DEFINE RECURSOS
//////////////////////////////////////
$fileCssLayout = "";
$fileCssLayoutPayin = "";
$fileJSLayout = "";

$fileCssPlugin_name = "";
$fileJSPlugin_name = "";

//ARCHIVOS CSS LAYOUT

//<!-- Bootstrap 3.3.6 -->
$fileCssLayout .= "<link rel='stylesheet' href='".$pathmm."/appweb/css/bootstrap.css'>";
//<!-- Font Awesome -->
$fileCssLayout .= "<link rel='stylesheet' href='".$pathmm."/appweb/css/font-awesome.min.css'>";
//<!-- Ionicons -->
//$fileCssLayout .= "<link rel='stylesheet' href='".$pathmm."appweb/css/ionicons.min.css'>";
//<!-- Theme style -->
$fileCssLayout .= "<link href='".$pathmm."/appweb/css/styles-site.css' rel='stylesheet' type='text/css' />";
//<!-- AdminLTE Skins. Choose a skin from the css/skins
//folder instead of downloading all of them to reduce the load. -->
$fileCssLayout .= "<link href='".$pathmm."/appweb/css/skin-blue.css' rel='stylesheet' type='text/css' />";
$fileCssLayout .= "<link href='".$pathmm."/appweb/css/applayouts.css' rel='stylesheet' type='text/css' />";
//sweet alert
$fileCssLayout .= "<link href='".$pathmm."/appweb/plugins/sweetalert/sweetalert.css' rel='stylesheet' type='text/css' />";

//<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
//<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
$fileCssLayout .= "<!--[if lt IE 9]>
    <script src='".$pathmm."/appweb/plugins/misc/html5shiv.js'></script>
    <script src='".$pathmm."/appweb/plugins/misc/respond.min.js'></script>
    <![endif]-->";


//ARCHIVOS CSS PLUGIN

//////////////////////////////////////////////////////////////////////////////////////////////

//ARCHIVOS JS LAYOUT

//<!-- jQuery 2.2.3 -->
$fileJSLayout .= "<script src='".$pathmm."/appweb/plugins/jQuery/jquery-2.2.3.min.js'></script>";
//<!-- Bootstrap 3.3.6 -->
$fileJSLayout .= "<script src='".$pathmm."/appweb/js/bootstrap.min.js'></script>";
//<!-- SlimScroll -->
$fileJSLayout .= "<script src='".$pathmm."/appweb/plugins/slimScroll/jquery.slimscroll.min.js'></script>";
//<!-- FastClick -->
$fileJSLayout .= "<script src='".$pathmm."/appweb/plugins/fastclick/fastclick.js'></script>";
//<!-- main App -->
$fileJSLayout .= "<script src='".$pathmm."/appweb/js/app.js'></script>";
//<!-- sweet alert -->
$fileJSLayout .= "<script src='".$pathmm."/appweb/plugins/sweetalert/sweetalert.min.js'></script>";
//<!-- acti submit searbox -->
$fileJSLayout .= "<script type='text/javascript'>
    $(document).ready(function() {   
        //PRELOAD
        $(window).on('load', function() { // makes sure the whole site is loaded 
          $('.wraploader').fadeOut(); // will first fade out the loading animation 
          $('#eventloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
          $('body').delay(350).css({'overflow':'visible'});
        });
    
        //SEARCH BTN
        $('#btn-search').on('click',function(e){
            e.preventDefault();
            var search_key = $('#txtkey').val(),
                formsb = $('#searchbox');
            if(search_key != ''){			  
              formsb.submit();
            }
        });        
    });                        
</script>";


$fileJSLayout .= "<script>
    $('a.trashtobtn').click(function(e) {
        e.preventDefault(); 
        var linkURL = $(this).attr('href');
        var nameProd = $(this).attr('name');
        var titleEv = $(this).attr('title');
        var msjProd = $(this).attr('data-msj');
        var reMsjProd = $(this).attr('data-remsj');
        confiElimiProd(linkURL, nameProd, titleEv, msjProd, reMsjProd);
      });

    function confiElimiProd(linkURL, nameProd, titleEv, msjProd, reMsjProd) {
        swal({
          title: titleEv, 
          text: '<span style=color:#DB4040; font-weight:bold;>' +nameProd + '</span><br>' + msjProd, 
          type: 'warning',
          showCancelButton: true,
          closeOnConfirm: false,
          closeOnCancel: true,
          animation: false,
          html: true
        }, function(isConfirm){
              if (isConfirm) {
                window.location.href = linkURL;
              } else {
                return false;	
              }
            });
      }

    </script>";

$fileJSLayout .= "<script>    
$(document).ready(function(){  
	jQuery('img').each(function(){  
		jQuery(this).attr('src',jQuery(this).attr('src')+ '?' + (new Date()).getTime());  
	});  
}); 
</script>";   

$fileJSLayout .= "<script>        
$('input[type=number]').on('focus', function (e) {
  $(this).on('mousewheel.disableScroll', function (e) {
    e.preventDefault();
  })
});
$('input[type=number]').on('blur', function (e) {
  $(this).off('mousewheel.disableScroll');
});
</script>";   

//ARCHIVOS JS PLUGIN

define('_CSSFILESLAYOUT_', $fileCssLayout);
define('_JSFILESLAYOUT_', $fileJSLayout);