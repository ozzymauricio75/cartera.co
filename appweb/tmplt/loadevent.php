<?php
$loadingLyt = "<div id='eventloader' class='bgloader'>";
$loadingLyt .= "<a type='button' id='closeel' class='closeel' style='display:none;'><i class='fa fa-times' ></i></a>";
$loadingLyt .= "<div id='wraploader' class='wraploader'><img src='".$pathmm."/appweb/img/loadbg.gif' class='img-responsive  margin-top-md '></div>";
$loadingLyt .= "<div id='wraperr' class='box25 margin-top-md ' style='display:none;'></div>";
$loadingLyt .= "<div id='wrapok' class='box25 margin-top-md ' style='display:none;'></div>";
$loadingLyt .= "</div>";

echo $loadingLyt;