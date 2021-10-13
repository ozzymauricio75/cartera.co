<?php require_once '../appweb/lib/MysqliDb.php'; ?>
<?php require_once '../cxconfig/config.inc.php'; ?>
<?php require_once '../cxconfig/global-settings.php'; ?>
<?php require_once '../appweb/inc/sessionvars.php'; ?>
<?php require_once '../appweb/inc/query-custom-user.php'; ?>
<?php require_once '../appweb/lib/gump.class.php'; ?>
<?php require_once '../appweb/inc/site-tools.php'; ?>
<?php //require_once '../appweb/inc/query-productos-catalogo.php'; ?>
<?php //require_once 'ssp.class.php'; ?>
<?php 




/*
*QUERY DEUDORES
*/
function queryDeudor($idAcreedor_){
    global $db;  
    $dataQuery = array();
    
    //$db->where('id_acreedor', $idAcreedor_);        
    $db->where('id_status_perfil_deduor', "5", "!=");        
    $queryTbl = $db->get ("deudor_tbl", null, "id_deudor, primer_nombre_deudor, segundo_nombre_deudor, primer_apellido_deudor, segundo_apellido_deudor, ciudad_domicilio_deudor, estado_domicilio_deudor, dir_geo_deudor, cedula_deudor, email_deudor, tel_uno_deudor, tel_dos_deudor, fecha_alta_deudor");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {                        
            $dataQuery[] = $qKey;
        }    
        return $dataQuery;
    }
}

$datas_array = array();
$datas_array = queryDeudor($idSSUser);


/*
 *==================================
 *CREA LAYOUT DATAS
 *==================================
*/

$layoutDataItem = "";
$totalRegistros = count($datas_array);
$num = 0;

if(is_array($datas_array) && !empty($datas_array)){
    foreach($datas_array as $daKey){
        
        $id_item = isset($daKey['id_deudor'])? $db->escape($daKey['id_deudor']) : "";                
        $primernombre_item = isset($daKey['primer_nombre_deudor'])? $db->escape($daKey['primer_nombre_deudor']) : "";                
        $segundonombre_item = isset($daKey['segundo_nombre_deudor'])? $db->escape($daKey['segundo_nombre_deudor']) : "";   
        $primerapellido_item = isset($daKey['primer_apellido_deudor'])? $db->escape($daKey['primer_apellido_deudor']) : "";    
        $segundoapellido_item = isset($daKey['segundo_apellido_deudor'])? $db->escape($daKey['segundo_apellido_deudor']) : "";
        $nombrecompleto_item = $primernombre_item." ".$segundonombre_item." ".$primerapellido_item." ".$segundoapellido_item;
        $dirgeo_item = isset($daKey['dir_geo_deudor'])? $db->escape($daKey['dir_geo_deudor']) : "";
        $email_item = isset($daKey['email_deudor'])? $db->escape($daKey['email_deudor']) : "";
        $cedula_item = isset($daKey['cedula_deudor'])? $db->escape($daKey['cedula_deudor']) : "";   
        $tel1_item = isset($daKey['tel_uno_deudor'])? $db->escape($daKey['tel_uno_deudor']) : "";
        $tel2_item = isset($daKey['tel_dos_deudor'])? $db->escape($daKey['tel_dos_deudor']) : "";        
        $fecharegistro_item = isset($daKey['fecha_alta_deudor'])? date("d/m/Y",strtotime($daKey['fecha_alta_deudor'])) : "";
        
        /*
        *LAYOUT ACCIONES BTN
        */
        //$detallesBTN = '<a href=\"details/?itemid_var='.$id_item.'&itemtype_var=deudordb\" type=\"button\" data-item=\"'.$id_item.'\" data-type=\"deudordb\" class=\"btn btn-primary \">Detalles <i class=\"fa fa-chevron-right margin-left-xs\" ></i></a>';
        
        
        /*
        *URLs
        */
        $pathNewCredito = $pathmm."/credits/new/";
        $pathDetalleUsers = $pathmm."/users/details/";
        
        $accionesBTN = "<div class='btn-group'>";
        
        $accionesBTN .= "<button type='button' class='btn bg-green gocredits' data-deudorvar='".$id_item."'><i class='fa fa-archive padd-hori-xs'></i> Crear credito</button>";
        $accionesBTN .="<script>$(document).ready(function(){ $('.gocredits').each(function(){ var itemid = $(this).attr('data-deudorvar'); $(this).click(function(){ $.redirect('".$pathNewCredito."',{ deudorcod: itemid}); }); }); });</script>";
        
        //$accionesBTN .= "<button type='button' class='btn btn-default godetails' data-item='".$id_item."' data-type='deudordb'>Detalles<i class='fa fa-chevron-right margin-left-xs'></i></button>";
        //$accionesBTN .="<script>$(document).ready(function(){ $('.godetails').each(function(){ var itemid = $(this).attr('data-item'); var itemtype = $(this).attr('data-type'); $(this).click(function(){ $.redirect('".$pathDetalleUsers."',{ itemid_var: itemid, itemtype_var: itemtype}); }); }); });</script>";
        
        $accionesBTN .="</div>";
                                        
        /*
        *VALORES DATAS JSON
        */
                
        $layoutDataItem.='{
        "item":"'.$num.'",
        "id":"'.$id_item.'",
        "nombre":"'.$nombrecompleto_item.'",  
        "documento":"'.$cedula_item.'",  
        "email":"'.$email_item.'",
        "tel1":"'.$tel1_item.'",
        "tel2":"'.$tel2_item.'",
        "direccion":"'.$dirgeo_item.'",
        "fecharegistro":"'.$fecharegistro_item.'",
        "actions":"'.$accionesBTN.'"
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
    "item":"0",
    "id":"0",
    "nombre":"-",  
    "documento":"-",  
    "email":"-",
    "tel1":"-",
    "tel2":"-",
    "direccion":"-",
    "fecharegistro":"-",
    "actions":"-"
    },';
    
    $layoutDataItem = substr($layoutDataItem,0, strlen($layoutDataItem) - 1);

    echo '{"draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "data":['.$layoutDataItem.']}';
}


?>