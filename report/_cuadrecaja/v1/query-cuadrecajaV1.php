<?php require_once '../../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../../cxconfig/config.inc.php'; ?>
<?php require_once '../../cxconfig/global-settings.php'; ?>
<?php require_once '../../appweb/inc/sessionvars.php'; ?>
<?php require_once '../../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../../appweb/lib/gump.class.php'; ?>
<?php require_once '../../appweb/inc/site-tools.php'; ?>
<?php //require_once '../../appweb/inc/query-productos-catalogo.php'; ?>
<?php //require_once 'ssp.class.php'; ?>
<?php 

$qDateStart = "0000-00-00";  //"2017-07-04";//"0000-00-00"; 
$qDateEnd = "0000-00-00";  //"2017-07-04";//"0000-00-00"; 
if(isset($_POST['qstart'])){ 
    $qDateStart = (string)$_POST['qstart']; 
    $qDateEnd = (string)$_POST['qend'];     
    $qDateStart = $db->escape($qDateStart); 
    $qDateEnd = $db->escape($qDateEnd);    
}

//FUNCIONES REDONDEAR
function redondear_valor($valor) { 

    // Convertimos $valor a entero 
    $valor = intval($valor); 

    // Redondeamos al múltiplo de 10 más cercano 
    $n = round($valor, -2); 

    // Si el resultado $n es menor, quiere decir que redondeo hacia abajo 
    // por lo tanto sumamos 50. Si no, lo devolvemos así. 
    return $n < $valor ? $n + 50 : $n; 
    //return $n < $valor ? $n : $n + 50; 
} 

/*
*QUERY VALORES RECAUDO DEL DIA
*/
//SELECT `id_valores_cobrar_dia`, `id_acreedor`, `dinero_recaudar_hoy`, `dinero_capital_hoy`, `dinero_interes_hoy`, `fecha_valores_cobrar_dia` FROM `valores_cobrar_dia` WHERE 1
function queryValoresRecaudo($idAcreedor_, $qDateStart_){//, $qDateEnd_ = null
    global $db;  
    $dataQuery = array();    
        
    /*if($qDateStart_ != $qDateEnd_){        
        $db->where('fecha_valores_cobrar_dia', array($qDateStart_, $qDateEnd_), 'BETWEEN');    
    }else{
        $db->where('fecha_valores_cobrar_dia', $qDateStart_);    
    }*/ 
    
    $db->where('id_acreedor', $idAcreedor_);
    $db->where('fecha_valores_cobrar_dia', $qDateStart_);
    $queryTbl = $db->get ("valores_cobrar_dia", 1,"dinero_recaudar_hoy, dinero_capital_hoy, dinero_interes_hoy, fecha_valores_cobrar_dia");
            
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {        
                                            
            $dataQuery = $qKey;
        }           
    }
    return $dataQuery;
}

/*
*QUERY CAJA MENOR
*/
//SELECT `id_caja_meno`, `id_acreedor`, `status_caja_menor`, `recaudo_total_caja_menor`, `gastos_total_caja_menor`, `valor_disponible_caja_menor`, `descripcion_caja_menor`, `fecha_cuadre_caja_menor`, `fecha_registro_cuadre_caja`, `hora_cuadre_caja_menor`, `actividad_caja_menor` FROM `caja_menor_tbl` WHERE 1
function queryCajaMenor($idAcreedor_, $qDateStart_, $qDateEnd_ = null){
    global $db;  
    $dataQuery = array();    
    $dataValoresRecaudo = array();
    
    if($qDateStart_ != $qDateEnd_){        
        $db->where('fecha_cuadre_caja_menor', array($qDateStart_, $qDateEnd_), 'BETWEEN');    
    }else{
        $db->where('fecha_cuadre_caja_menor', $qDateStart_);    
    }              
    $db->where('actividad_caja_menor', "inicial", "!=");
    $db->where('id_acreedor', $idAcreedor_);
    $queryTbl = $db->get ("caja_menor_tbl");
            
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {
            $fecha_cuadre_caja = $qKey["fecha_cuadre_caja_menor"];
            
            $dataValoresRecaudo = queryValoresRecaudo($idAcreedor_, $fecha_cuadre_caja);//, $qDateEnd_
                    
            $dataQuery[] = array(
                //"datascajamenor" => $qKey["status_caja_menor"],
                "statuscajamenor" => $qKey["status_caja_menor"],
                "recaudocajamenor" => $qKey["recaudo_total_caja_menor"],
                "gastoscajamenor" => $qKey["gastos_total_caja_menor"],
                "valordisponiblecajamenor" => $qKey["valor_disponible_caja_menor"],
                "descricajamenor" => $qKey["descripcion_caja_menor"],
                "fechacuadrecajamenor" => $qKey["fecha_cuadre_caja_menor"],
                "fecharegistrocajamenor" => $qKey["fecha_registro_cuadre_caja"],
                "actividadcajamenor" => $qKey["actividad_caja_menor"],
                "datasvaloresrecaudo" => $dataValoresRecaudo
            );
            //$dataQuery[]["datasvaloresrecaudo"] = ;
        }           
    }
    return $dataQuery;
}


$datas_array = array();
$datas_array = queryCajaMenor($idSSUser, $qDateStart, $qDateEnd);
/*echo "<pre>";
print_r($datas_array);
echo "</pre>";*/


/*
 *==================================
 *CREA LAYOUT DATAS
 *==================================
*/

$layoutDataItem = "";
$totalRegistros = count($datas_array);
$num = 0;
$prevRefCredito = "";
 
if(is_array($datas_array) && !empty($datas_array)){
    foreach($datas_array as $daKey){
        //datas array        
        $status_cajamenor = isset($daKey['statuscajamenor'])? $db->escape($daKey['statuscajamenor']) : "";
        $recaudo_cajamenor = isset($daKey['recaudocajamenor'])? $db->escape($daKey['recaudocajamenor']) : "";
        $gastos_cajamenor = isset($daKey['gastoscajamenor'])? $db->escape($daKey['gastoscajamenor']) : "";
        $valordisponible_cajamenor = isset($daKey['valordisponiblecajamenor'])? $db->escape($daKey['valordisponiblecajamenor']) : "";
        $fecha_cajamenor = isset($daKey['fechacuadrecajamenor'])? $db->escape($daKey['fechacuadrecajamenor']) : ""; 
        $datas_valoresrecaudos = $daKey['datasvaloresrecaudo']; 
        
        
        $recaudo_faltante_hoy = 0;
        $diferencia_capital = 0;
        $diferencia_interes = 0;
        $abono_capital = 0;
        $abono_interes = 0;
        $ingreso_neto = 0;
        
        if(is_array($datas_valoresrecaudos) && !empty($datas_valoresrecaudos)){
            foreach($datas_valoresrecaudos as $dvrKey){
                $dinero_recaudar = isset($datas_valoresrecaudos['dinero_recaudar_hoy'])? $db->escape($datas_valoresrecaudos['dinero_recaudar_hoy']) : "";        
                $dinero_para_capital = isset($datas_valoresrecaudos['dinero_capital_hoy'])? $db->escape($datas_valoresrecaudos['dinero_capital_hoy']) : "";
                $dinero_para_interes = isset($datas_valoresrecaudos['dinero_interes_hoy'])? $db->escape($datas_valoresrecaudos['dinero_interes_hoy']) : "";
            }
        }
        
        //dinero libre despues de gastos
        $ingreso_neto = $recaudo_cajamenor - $gastos_cajamenor;
        
        //dinero faltante del total a recaudar hoy
        $recaudo_faltante_hoy = $dinero_recaudar - $recaudo_cajamenor;
        
        //cuanto se abona a capital
        $abono_capital = redondear_valor(($dinero_para_capital/$dinero_recaudar)*$ingreso_neto);//$recaudo_cajamenor - $dinero_para_capital;

        //cuanto se abona a interes
        $abono_interes = redondear_valor(($dinero_para_interes/$dinero_recaudar)*$ingreso_neto);//$recaudo_cajamenor - $dinero_para_capital - $dinero_para_interes;

        /*if($diferencia_capital < 0){
            $abono_capital = $diferencia_capital;
            $abono_interes = 0;
        }elseif($diferencia_capital == 0){
            $abono_capital = $recaudo_cajamenor;            
            $abono_interes = 0;
        }elseif($diferencia_capital > 0){
            $abono_capital = $dinero_para_capital;
            $abono_interes = $diferencia_interes;
        }*/
        
        /*if($abono_capital >= $dinero_para_capital){
            $abono_capital = $abono_capital; 
            $abono_interes = $abono_interes;   
        }else{
            $abono_capital = $recaudo_cajamenor;
            $abono_interes = 0;   
        }*/
        
                        
        //formato datos
        $fecha_cuadrecaja_human = date("d/m/Y",strtotime($fecha_cajamenor));        
        

        /*
        *VALORES DATAS JSON
        */

        $layoutDataItem.='{
        "item":"'.$num.'",
        "fecha":"'.$fecha_cuadrecaja_human.'",
        "valoracobrar":"'.$dinero_recaudar.'",
        "valorcobrado":"'.$recaudo_cajamenor.'",
        "valorfaltante":"'.$recaudo_faltante_hoy.'",  
        "valorgastos":"'.$gastos_cajamenor.'",  
        "ingresoneto":"'.$ingreso_neto.'",
        "abonoacapital":"'.$abono_capital.'",
        "abonoainteres":"'.$abono_interes.'",
        "valordisponible":"'.$valordisponible_cajamenor.'"
        },';

        $num++;                        
    }
    /*-/FIN FOREACH DATAS/-*/

        
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);
    
    echo '{"draw": 1,
    "recordsTotal": '.$totalRegistros.',
    "recordsFiltered": '.$totalRegistros.',
    "data":['.$layoutDataItem.']}';    
    

    
}else{
    //$data['error'] = "ERROR: No se encontraron registros";
   // echo json_encode( $data );
    $layoutDataItem.='{
    "item":"-",
    "fecha":"-",
    "valoracobrar":"-",
    "valorcobrado":"-",
    "valorfaltante":"-",  
    "valorgastos":"-",  
    "ingresoneto":"-",  
    "abonoacapital":"-",
    "abonoainteres":"-",
    "valordisponible":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>