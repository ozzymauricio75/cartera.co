<?php
$tmplHeadAS = '<header class="main-header">';
//*
//LOGO
//*
$tmplHeadAS .= '<a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><i class="fa fa-shekel fa-lg margin-right-xs"></i><b>Cartera</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><i class="fa fa-shekel fa-lg margin-right-xs"></i><b>Cartera</b></span>
    </a>';
//*
//NAVBAR    
//*
$tmplHeadAS .= '<nav class="navbar navbar-static-top">';
//*
//TOGLE BUTTON SIDE BAR
//*
$tmplHeadAS .= '<a href="#" class="sidebar-toggle visible-xs visible-sm" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>';
//*
//WRAPP MAIN MENU TOP
//*
$tmplHeadAS .= '<div class="navbar-custom-menu">';
$tmplHeadAS .= '<ul class="nav navbar-nav">';

//*
//BTN SECTION
//*
/*$tmplHeadAS .= '<li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>';*/
          
//*
//BTN SECTION
//*
/*$tmplHeadAS .= '<li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>';*/
          
//*
//BTN SECTION
//*
/*$tmplHeadAS .= '<li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>';*/

//*
//FECHA HOY
//*
$tmplHeadAS .= '<li class="tasks-menu">
            <div class="untopdowpadding padd-hori-xs shades-text text-white">
             <!-- <i class="fa fa-calendar fa-lg margin-right-xs" style="position: absolute; margin-top:18px; margin-left:-25px;"></i>-->
             <!---/FECHA PC /---> 
              <span class="hidden-xs">
                <span class="margin-right-xs txtCapitalice text-size-x7 text-shadow">'.$nombre_dia.'</span>
                <span class="margin-right-xs txtCapitalice text-size-x4 text-shadow">'.$dateFormatHuman.'</span>
              </span>
             <!---/FECHA MOBILE /---> 
              <span class="visible-xs" style="margin-top:8px;">
                <span class="margin-right-xs txtCapitalice text-shadow" style="font-size:22px;">'.$nombre_dia.'</span>
                <span class="margin-right-xs txtCapitalice text-shadow" style="font-size:15px;">'.$dateFormatHuman.'</span>
              </span>
            </div>            
          </li>';

//*
//BTN SECTION CUENTA
//*
$tmplHeadAS .= '<li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user fa-lg"></i>
              <span class="hidden-xs">'.$pseudoSSUser.'</span>
            </a>';
            
//*
////////////////////
//WRAP USER MENU
//*
$tmplHeadAS .= '<ul class="dropdown-menu">';              
//*
////////////////////
//HEADER USER MENU
//*
$tmplHeadAS .= '<li class="user-header">
                <!----<img src="'.$portadaImgSSUser.'" class="img-circle  bg-white" alt="">---->
                <i class="fa fa-shekel fa-5x margin-top-md white-text "></i>

                <p>
                  '.$nameSSUser.'&nbsp;'.$lnameSSUser.'
                  <small></small>
                </p>
              </li>';
              
//*
////////////////////
//BODY USER MENU
//*
/*$tmplHeadAS .= '<li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>';*/
              
//*
////////////////////
//FOOTER USER MENU
//*
$tmplHeadAS .= '<li class="user-footer">
                <!---<div class="pull-left">
                  <a href="" class="btn btn-default btn-flat">Mi cuenta</a>
                </div>-->
                <div class="pull-right">
                  <a href="'.$pathmm.$admiDir.'/logout/" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>';
$tmplHeadAS .= '</ul>';
$tmplHeadAS .= '</li>';
//*
//BTN RIGHT BAR
//*
/*$tmplHeadAS .= '<li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>';*/
$tmplHeadAS .= '</ul>';
$tmplHeadAS .= '</div>';
$tmplHeadAS .= '</nav>';
$tmplHeadAS .= '</header>';
echo $tmplHeadAS;    