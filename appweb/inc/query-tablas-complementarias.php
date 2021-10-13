<?php
/*
* QUERY TABLAS COMPLEMENTARIAS
*/

//GENERO
function queryGenero($idGenero){
    global $db;  
    $dataQuery = "";
    
    $db->where('id_genero', $idGenero);        
    $queryTbl = $db->getOne ("genero_tbl", "nombre_genero");
    $dataQuery = $queryTbl['nombre_genero'];
    
    return $dataQuery;        
}

//ESTADO CIVIL
function queryEstadoCivil($idestadoCivil){
    global $db;  
    $dataQuery = "";
    
    $db->where('id_estado_civil', $idestadoCivil);        
    $queryTbl = $db->getOne ("estado_civil_tbl", "nombre_estado_civil");
    $dataQuery = $queryTbl['nombre_estado_civil'];
    
    return $dataQuery;  
}

//TIPO VIVIENDA
function queryTipoVivienda($idTipoVivienda){
    global $db;  
    $dataQuery = "";
    
    $db->where('id_tipo_vivienda', $idTipoVivienda);        
    $queryTbl = $db->getOne ("tipo_vivienda_tbl", "nombre_tipo_vivienda");
    $dataQuery = $queryTbl['nombre_tipo_vivienda'];
    
    return $dataQuery;
}

//ESTRATO SOCIAL
function queryEstratoSocial($idEstratoSocial){
    global $db;  
    $dataQuery = "";
    
    $db->where('id_estrato_social_tbl', $idEstratoSocial);        
    $queryTbl = $db->getOne ("estrato_social_tbl", "nombre_estrato_social_tbl");
    $dataQuery = $queryTbl['nombre_estrato_social_tbl'];
    
    return $dataQuery;
}

//ESCOLARIDAD
function queryEscolaridad($idEscolaridad){
    global $db;  
    $dataQuery = "";
    
    $db->where('id_escolaridad', $idEscolaridad);        
    $queryTbl = $db->getOne ("escolaridad_tbl", "nombre_escolaridad");
    $dataQuery = $queryTbl['nombre_escolaridad'];
    
    return $dataQuery;
}


//ESTATUS CREDITO 
function queryStatusdeCredito($idStatusCredito){
    global $db;  
    $dataQuery = "";
    
    $db->where('id_status', $idStatusCredito);        
    $queryTbl = $db->getOne ("status_credito_tbl", "nombre_status");
    $dataQuery = $queryTbl['nombre_status'];
    
    return $dataQuery;
}

//ESTATUS CREDITO 
function queryTipodeCredito($idTipoCredito){
    global $db;  
    $dataQuery = "";
    
    $db->where('id_tipo_credito', $idTipoCredito);        
    $queryTbl = $db->getOne ("tipo_credito_tbl", "nombre_tipo_credito");
    $dataQuery = $queryTbl['nombre_tipo_credito'];
    
    return $dataQuery;
}


//TABLA ESTATUS GENERAL
function queryStatusGB($idStatusGB){
    global $db;  
    $dataQuery = "";
    
    $db->where('id_status', $idStatusGB);        
    $queryTbl = $db->getOne ("status_tbl", "nombre_status");
    $dataQuery = $queryTbl['nombre_status'];
    
    return $dataQuery;
}

//QUERY TABAL DIAS FESTIVOS
function queryDiasFestivos(){
    global $db;  
    $dataQuery = array();
    
    $db->orderBy("fecha_dia_festivo","asc");           
    $queryTbl = $db->get ("dias_festivos_tbl");
        
    $rowQueryTbl = count($queryTbl);
    if ($rowQueryTbl > 0){        
        foreach ($queryTbl as $qKey) {        
            $dataQuery[] = $qKey;                 
        }    
        return $dataQuery;
    }
}