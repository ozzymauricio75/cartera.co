<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
//$_POST['id'] = 2;
if($_POST['depto']){
    $idDepto=$_POST['depto'];
//function loadRaza($idDepto){
    //$db global;
    //SELECT `id_ciudad_user`, `name_ciudad_user`, `name_estado_user`, `id_estado_rel` FROM `ciudades_user` WHERE 1
    
    $db->where ('id_estado_rel', $idDepto);
    $db->orderBy("nombre_estado_colombia","Asc");									
    $queryCiudades = $db->get('ciudades_colombia_tbl');

    if ($db->count > 0){
        echo "<option value='' selected>Selecciona una Ciudad</option>";	
        foreach ($queryCiudades as $cKey) { 
            $idCiudad = $cKey['id_ciudad_colombia'];
            $nombreCiudad = $cKey['nombre_ciudad_colombia'];
            $nombreEstado = $cKey['nombre_estado_colombia'];
            $ciudadFull = $nombreCiudad."&nbsp;-&nbsp;".$nombreEstado;
            //$nombreCiudadHTML = htmlentities($ciudadFull, "ENT_QUOTES", "UTF-8");
            
            echo "<option value='".$ciudadFull."' >".$nombreCiudad."</option>";	
        }
    }	
//}
}