<?php //<!-- Left side column. contains the sidebar -->

$home_root = "";

$collet_root = "";
$collect_new = "";
$collet_list = "";
$collet_list="";
$collector_list="";

$pedido_root = "";

$empresa_root = "";

$user_root = "";
$user_new = "";
$user_list = "";

$custom_root = "";
$custom_level_prod = "";
$custom_feactures_prod = "";
$custom_sublevel_feacturesprod = "";
$custom_sublevel_levelsprod = "";

$custom_account = "";

$credits_list = "";

switch($rootLevel){
    case "home":
        $home_root = "active";        
    break;
    /*case "tienda":
        $collet_root = "active";
        if(!empty($sectionLevel)){
            if($sectionLevel=="level"){
                $collet_list = "active";    
            }            
            if($sectionLevel=="new"){
                $collect_new = "active";    
            }            
        }
    break;*/
    
    case "empresa":
        $empresa_root = "active";    
    break;
    case "usuarios":
        $user_root = "active";
        if(!empty($sectionLevel)){
            if($sectionLevel=="new"){
                $user_new = "active";    
            }
            if($sectionLevel=="lista"){
                $user_list = "active";    
            }            
        }
    break;
    case "usuarios":
        $user_root = "active";
        if(!empty($sectionLevel)){
            if($sectionLevel=="new"){
                $user_new = "active";    
            }
            if($sectionLevel=="lista"){
                $user_list = "active";    
            }            
        }
    break;
    case "especificaciones":
        $custom_root = "active";
        if(!empty($sectionLevel)){
            if($sectionLevel=="productos"){
                $custom_feactures_prod = "active";
                if(!empty($subSectionLevel)){
                    if($subSectionLevel == "customprod"){
                        $custom_sublevel_feacturesprod = "active";    
                    }
                    if($subSectionLevel == "customlevels"){
                        $custom_sublevel_levelsprod = "active";    
                    }
                    
                }
            }
            if($sectionLevel=="account"){
                $custom_account = "active";
                /*if(!empty($subSectionLevel)){
                    if($subSectionLevel == "customlevels"){
                        $custom_sublevel_levelsprod = "active";    
                    }
                    
                }*/
            }
        }
    break;
        
    case "account":
        $custom_account = "active";        
    break;
        
}

switch($rootLevel){
    case "cobrar":
        $collet_root = "active";
        if(!empty($sectionLevel)){
            if($sectionLevel=="rutas"){
                $collet_list = "active";    
            }
            if($sectionLevel=="newruta"){
                $collect_new = "active";    
            }
            if($sectionLevel=="cobradores"){
                $collector_list = "active";    
            }
            if($sectionLevel=="newcobrador"){
                $collector_new = "active";    
            }
            
        }
    break;
        
    /*case "creditos":
        $pedido_root = "active";        
    break;*/
    case "creditos":
        $pedido_root = "active";
        if(!empty($sectionLevel)){
            if($sectionLevel=="lista"){
                $credits_list = "active";    
            }
            if($sectionLevel=="orderdetalle"){
                $pedido_detalle = "active";    
            }
        }
    break;
        
}

$tmplSideAS = '<aside class="main-sidebar">';
    
$tmplSideAS .= '<section class="sidebar">';    
$tmplSideAS .= '
<div class="btn-group margin-hori-xs margin-verti-md">
  <button type="button" class="btn btn-lg bg-olive dropdown-toggle text-shadow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  Crear <i class="fa fa-plus fa-lg margin-left-xs"></i>
  </button>
  
  <a href="'.$pathmm.'/users/" type="button" class="btn btn-lg bg-olive text-shadow">
    <i class="fa fa-search fa-lg white-text"></i>
  </a>
  
  <ul class="dropdown-menu animated-dropdown-menu box-shadow" style="width:200px;">
    <li><a href="'.$pathmm.'/collect/new/" class="padd-verti-xs"><i class="fa fa-motorcycle fa-1x margin-right-xs"></i> Nuevo cobrador </a></li><li class="divider"></li>
    <li><a href="'.$pathmm.'/credits/new/" class="padd-verti-xs"><i class="fa fa-archive fa-1x margin-right-xs"></i> Nuevo credito </a></li>
    <li class="divider"></li>
    <li><a href="'.$pathmm.'/collect/" class="padd-verti-xs"> <i class="fa fa-map fa-1x margin-right-xs"></i> Nueva ruta</a></li>
  </ul>
</div>
';
/*$tmplSideAS .= '<div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>';*/
      
/*$tmplSideAS .= '<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>';*/
      
$tmplSideAS .= '<ul class="sidebar-menu">';
//$tmplSideAS .= '<li class="header">MAIN NAVIGATION</li>';
$tmplSideAS .= '<li class="'.$home_root.'">
          <a href="'.$pathmm.'/home/">
            <i class="fa fa-home"></i> 
            <span>Home</span>            
          </a>
        </li>';
$tmplSideAS .= '<li class="'.$pedido_root.'">
          <a href="'.$pathmm.'/credits/">
            <i class="fa fa-archive"></i> 
            <span>Creditos</span>            
          </a>
        </li>';

/*$tmplSideAS .= '<li class="treeview '.$pedido_root.'">
          <a href="#">
            <i class="fa fa-archive"></i>
            <span>Creditos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="'.$credits_list.'"><a href="'.$pathmm.'/credits/"><i class="fa fa-chevron-circle-right"></i> Lista</a></li>
            <!---<li><a href="'.$pathmm.'/credits/"><i class="fa fa-chevron-circle-right"></i> Recaudos</a></li>
            <li><a href="'.$pathmm.'/credits/"><i class="fa fa-chevron-circle-right"></i> Prox. Vencer</a></li> 
            <li><a href="'.$pathmm.'/credits/"><i class="fa fa-chevron-circle-right"></i> Mora</a></li> --->
          </ul>
        </li>';*/

$tmplSideAS .= '<li class="treeview '.$collet_root.'">
          <a href="#">
            <i class="fa fa-address-book-o"></i> 
            <span>Cobranza</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="'.$collet_list.'"><a href="'.$pathmm.'/collect/"><i class="fa fa-chevron-circle-right"></i> Rutas</a></li>
            <li class="'.$collector_list.'"><a href="'.$pathmm.'/collect/collectors.php"><i class="fa fa-chevron-circle-right"></i> Cobradores</a></li>
          </ul>
        </li>';




$tmplSideAS .= '<li class="'.$user_root.'">
          <a href="'.$pathmm.'/users/">
            <i class="fa fa-user-circle-o"></i>
            <span>Usuarios</span>            
          </a>          
        </li>';
$tmplSideAS .= '<li class="'.$custom_account.'">
          <a href="'.$pathmm.'/account/">
            <i class="fa fa-gear"></i> 
            <span>Personalizar</span>            
          </a>
        </li>';
/*$tmplSideAS .= '<li class="treeview">
          <a href="#">
            <i class="fa fa-area-chart"></i>
            <span>Informes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../UI/general.html"><i class="fa fa-chevron-circle-right"></i> Pedidos</a></li>
            <li><a href="../UI/icons.html"><i class="fa fa-chevron-circle-right"></i> Clientes</a></li>            
          </ul>
        </li>';*/


/*$tmplSideAS .= '<li class="treeview '.$custom_root.'">
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>Personalizar</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="'.$custom_feactures_prod.'">
                <a href="">
                    <i class="fa fa-chevron-circle-right"></i> Productos
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                
                <ul class="treeview-menu">
                    
                    <li class="'.$custom_sublevel_feacturesprod.'"><a href="'.$pathmm.'/custom/prods/features/"><i class="fa fa-chevron-circle-right"></i> Especificaciones</a></li>
                    <li class="'.$custom_sublevel_levelsprod.'"><a href="'.$pathmm.'/custom/prods/levels/"><i class="fa fa-chevron-circle-right"></i> Categorías</a></li>
                </ul>
            </li>
                                    
            <li class="'.$custom_account.'"><a href="'.$pathmm.'/custom/account/"><i class="fa fa-chevron-circle-right"></i> Cuenta</a></li>
          </ul>
        </li>';*/

$tmplSideAS .= '</ul>';
$tmplSideAS .= '</section>';    
$tmplSideAS .= '</aside>';
echo $tmplSideAS;
/*
<li class="'.custom_sublevel_prod.'"><a href="'.$pathmm.'/custom/prods/levels/"><i class="fa fa-chevron-circle-right"></i> Categorías</a></li>
*/
