<?php 

$layoutStepsCredits = "";
$stepsCreditsStatus_1 = "disabled";
$stepsCreditsStatus_2 = "disabled";
$stepsCreditsStatus_3 = "disabled";
$stepsCreditsStatus_4 = "disabled";
$stepsCreditsStatus_5 = "disabled";
$stepsCreditsStatus_6 = "disabled";
$stepsCreditsStatus_7 = "disabled";

switch($stepsCredits){
    case "step_1":
        $stepsCreditsStatus_1 = "active";
    break;
    case "step_2":
        $stepsCreditsStatus_2 = "active";
    break;
    case "step_3":
        $stepsCreditsStatus_3 = "active";
    break;
    case "step_4":
        $stepsCreditsStatus_4 = "active";
    break;
    case "step_5":
        $stepsCreditsStatus_5 = "active";
    break;
    case "step_6":
        $stepsCreditsStatus_6 = "active";
    break;
    case "step_7":
        $stepsCreditsStatus_7 = "active";
    break;
}

$layoutStepsCredits .= "<section class='clearfix margin-bottom-md maxwidth-usersforms'>";
$layoutStepsCredits .= "<div class='row'>";
//DEUDOR***********    
$layoutStepsCredits .= "<div class='col-xs-4 col-sm-2'>";
$layoutStepsCredits .= "<div class='wrapp-steps-circle  text-center'>";
$layoutStepsCredits .= "<span class='step-circle ".$stepsCreditsStatus_1."'>1</span>";
$layoutStepsCredits .= "<p>Deudor</p>";
$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "</div>";
//CODEUDOR**************    
$layoutStepsCredits .= "<div class='col-xs-4 col-sm-2'>";
$layoutStepsCredits .= "<div class='wrapp-steps-circle'>";
$layoutStepsCredits .= "<span class='step-circle ".$stepsCreditsStatus_2."'>2</span>";
$layoutStepsCredits .= "<p>Codeudor</p>";
$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "</div>";
//REFERENCIA PERSONAL**********        
$layoutStepsCredits .= "<div class='col-xs-4 col-sm-2'>";
$layoutStepsCredits .= "<div class='wrapp-steps-circle  text-center'>";
$layoutStepsCredits .= "<span class='step-circle ".$stepsCreditsStatus_3."'>3</span>";
$layoutStepsCredits .= "<p>Referencia personal</p>";
$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "<div class='clearfix visible-xs'></div>";
//REFERENCIA FAMILIAR***********   
$layoutStepsCredits .= "<div class='col-xs-4 col-sm-2'>";
$layoutStepsCredits .= "<div class='wrapp-steps-circle text-center'>";
$layoutStepsCredits .= "<span class='step-circle ".$stepsCreditsStatus_4."'>4</span>";
$layoutStepsCredits .= "<p>Referencia familiar</p>";
$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "</div>";

//REFERECIA COMERCIAL    
$layoutStepsCredits .= "<div class='col-xs-4 col-sm-2'>";
$layoutStepsCredits .= "<div class='wrapp-steps-circle text-center'>";
$layoutStepsCredits .= "<span class='step-circle ".$stepsCreditsStatus_5."'>5</span>";
$layoutStepsCredits .= "<p>Referencia comercial</p>";
$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "</div>";
//CREDITO        
$layoutStepsCredits .= "<div class='col-xs-4 col-sm-2'>";
$layoutStepsCredits .= "<div class='wrapp-steps-circle text-center'>";
$layoutStepsCredits .= "<span class='step-circle ".$stepsCreditsStatus_6."'>6</span>";
$layoutStepsCredits .= "<p>Credito</p>";
$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "</div>";
//COBRANZA    
/*$layoutStepsCredits .= "<div class='col-xs-4 col-sm-2'>";
$layoutStepsCredits .= "<div class='wrapp-steps-circle'>";
$layoutStepsCredits .= "<span class='step-circle ".$stepsCreditsStatus_7."'>7</span>";
$layoutStepsCredits .= "<p>Cobranza</p>";
$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "</div>";*/

$layoutStepsCredits .= "</div>";
$layoutStepsCredits .= "</section>";

echo $layoutStepsCredits;