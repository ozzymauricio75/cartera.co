<?php
$dataDeudor = array();
$datas_detalle_ruta = array();
$ruta_err = array();
$err_insert_lyt = "";

if(isset($_POST['field_var']) && $_POST['field_var']== "newcollect"){
    
    //field_var: field, itemid_var: itemid, uservar_var:uservar, usernick_var: usernick, fecharuta_var: fecharuta
    
    $idCobradorPOST = (int)$_POST['itemid_var'];
    $idCobradorPOST = $db->escape($idCobradorPOST);

    //$idCobradorPOST= '2';
    $nickAcreedorPOST = (string)$_POST['usernick_var'];
    $nickAcreedorPOST = $db->escape($nickAcreedorPOST);
    
    $idAcreedorPOST = (int)$_POST['uservar_var'];
    $idAcreedorPOST = $db->escape($idAcreedorPOST);
    
    $fechaPOST = $db->escape($_POST['fecharuta_var']);        

    /*
    *CONSULTA NOMBRE COBRADOR
    *OBJETIVOS INFORMATIVOS
    */ 
    $db->where("id_acreedor", $idAcreedorPOST);//$idSSUser
    $db->where("id_cobrador", $idCobradorPOST);
    $queryNombreCobrador = $db->getOne("cobrador_tbl", "nombre_cobrador");

    if(count($queryNombreCobrador)>0){
        $nombreCobradorSeleccionado = $queryNombreCobrador['nombre_cobrador'];    
    }
    
    
    /*
    *CONSULTA SI YA FUE CREADA LA RUTA
    *PARA ESTE COBRADOR EN ESTE DIA
    */ 
    $db->where('id_cobrador', $idCobradorPOST);
    $db->where('fecha_creacion_ruta', $fechaPOST);
    $existeRutaHoy = $db-> getOne('rutas_tbl');
    if(count($existeRutaHoy)>0){
        $ruta_err[] = "El cobrador: <strong>".$nombreCobradorSeleccionado."</strong>. Ya tiene una ruta asignada para el día de hoy";
        return $ruta_err;
        exit();
    }
    
    
    /*
    *DEFINE CODIGO NUMERICO DEL REGISTRO
    */ 
        
    $db->where('id_acreedor', $idAcreedorPOST);        
    $queryUltimaRuta = $db->getOne ("rutas_tbl", "MAX(id_ruta) AS ultimaruta");
        
    $lastItemDB = $queryUltimaRuta['ultimaruta'];
    $lastItemDB = $lastItemDB + 1;    
                        
    switch($lastItemDB) {
		
		case ($lastItemDB < 10):
			$prefijo = "00000";
			$lastItemDB = $prefijo.$lastItemDB;
		break;	
		
		case ($lastItemDB < 100):
			$prefijo = "0000";
			$lastItemDB = $prefijo.$lastItemDB;
		break;
		
		case ($lastItemDB < 1000):
			$prefijo = "000";
			$lastItemDB = $prefijo.$lastItemDB;
		break;	
	
		case ($lastItemDB < 10000):
			$prefijo = "00";
			$lastItemDB = $prefijo.$lastItemDB;
		break;	
		
		case ($lastItemDB < 100000):
			$prefijo = "0";
			$lastItemDB = $prefijo.$lastItemDB;
		break;
	
		case ($lastItemDB >= 100000):
			$lastItemDB = $lastItemDB;
		break;
	}
    
    //CREA CONSECUTIVO CREDITO
    $consecutivoRuta = $nickAcreedorPOST."_".$lastItemDB;
    
    
    
    /*
    *CREACION DE ARRAY PARA INSERTAR EN TABLAS DE RUTAS
    */    
    
    /*//---CONSULTA DE CREDITOS PARA ESTE COBRADOR-//*/
    $datas_detalle_ruta = queryCuotas($idAcreedorPOST, $idCobradorPOST, $fechaPOST);
    
    
    /*//--SI EXISTE ERROR EN EL QUERY--//*/
    $err_query_lyt = "";
    //$errores_query = "";    
    $errores_query = isset($datas_detalle_ruta['errquery'])? $datas_detalle_ruta['errquery'] : "";
    //echo "eror encontrado= " .$errores_query;
    
    if(isset($errores_query) && $errores_query != ""){        
        $err_query_lyt .= "<b>Hubo un problema en el servidor</b>";
        $err_query_lyt .= "<br>Error:&nbsp;".$errores_query;
        $err_query_lyt .= "<br>Error encontrado cuando se realizaba la consulta de recaudos asignados al cobrador ".$nombreCobradorSeleccionado;
        $ruta_err[] = $err_query_lyt;
        exit();   
        
    }else{
        
        $valorTotalRecaudos = 0;
        /*//---SE CREAN LOS ARRAYS CON LOS RESULTADOS OBTENIDOS-//*/
        if(is_array($datas_detalle_ruta) && !empty($datas_detalle_ruta)){

            /*//---ARRAY TABLA RUTAS-//*/
            $datasRutas = array(
                'id_acreedor' => $idAcreedorPOST,
                'id_cobrador' => $idCobradorPOST,                
                'consecutivo_ruta' => $consecutivoRuta,
                'nombre_cobrador_ruta' => $nombreCobradorSeleccionado,
                //'total_recaudo_ruta' => $valorTotalRecaudos,
                //'total_deudores_ruta' => ,
                'fecha_creacion_ruta' => $fechaPOST,

            );

            /*//---inserta en la tabla los datos-//*/
            $rutaINSERT = $db-> insert('rutas_tbl', $datasRutas );
            if(!$rutaINSERT){

                $err_insert = $db->getLastErrno();
                $err_insert_lyt .= "<b>Hubo un problema en el servidor</b>";
                $err_insert_lyt .= "<br>Error:&nbsp;".$err_insert;
                $err_insert_lyt .= "<br>Esto ocurrio cuando se intentaba guardar la ruta, por favor intentalo de nuevo";
                $ruta_err[] = $err_insert_lyt;
                exit();

            }else{


                foreach($datas_detalle_ruta as $drKey){
                    $idAcreedor = $drKey['idacreedor'];
                    $idCobrador = $drKey['idcobrador'];
                    $idRecaudo = $drKey['idrecaudo'];
                    $idPlanPago = $drKey['idplanpago'];
                    $consecutivoCredito = $drKey['consecutivo'];
                    $numeroCuota = $drKey['numeroCuota'];
                    $valorMoraCuota = $drKey['valorMora'];
                    $valorCuota = $drKey['valorCuota'];
                    $valorRecaudadoCuota = $drKey['valorRecaudado'];
                    $valorRecaudadoCuota = $drKey['valorRecaudado'];
                    $valorFaltanteCuota = $drKey['valorFaltanteCuota'];
                    $valorRecalculadoCuota = $drKey['valorRecalculado'];
                    $fechaMaxCuota = $drKey['fechaMaxRecaudo'];
                    $fechaRecaudoCuota = $drKey['fechaRecaudado'];
                    $comentariosCuota = $drKey['comentarioCuota'];
                    $datasCredito = $drKey['datascredito'];



                    //VALOR TOTAL RECAUDOS
                    $valor_cuota_final = 0;
                    if($valorFaltanteCuota != 0){
                        $valorTotalRecaudos = $valorTotalRecaudos + $valorFaltanteCuota;
                    }else{
                        $valorTotalRecaudos = $valorTotalRecaudos + $valorCuota;//querySumatoriaRecaudos($idRecaudo);    
                    }


                    //ARRAY DATAS CREDITO
                    if(is_array($datasCredito) && !empty($datasCredito)){
                        foreach($datasCredito as $dcKey){
                            $idCredito_ruta = $datasCredito['idcredito']; 
                            $idDeudor_ruta = $datasCredito['iddeudor'];     
                            //$datasDeudor_ruta = $datasCredito['datasdeudor'];
                            
                            //SOBRE EL DEUDOR
                            $dataDeudor[] = queryDeudor($idDeudor_ruta);
                            
                            /*//---ARRAY TABLA ESPECIFICACINES DE RUTAS-//*/
                            $datasEspecificaRutas = array(
                                'id_ruta' => $rutaINSERT,
                                'id_creditos' => $idCredito_ruta,
                                'id_deudor' => $idDeudor_ruta,
                                'id_plan_pago' => $idPlanPago,
                                'id_recaudo' => $idRecaudo,
                                'id_cobrador' => $idCobrador 
                            );   

                        }/*//---FIN FOREACH[$datasCredito]-//*/
                    }/*//---FIN IS_ARRAY[$datasCredito]-//*/

                    /*//---inserta en la tabla los datos-//*/
                    $especificaRutaINSERT = $db-> insert('especifica_ruta_tbl', $datasEspecificaRutas);

                    if(!$especificaRutaINSERT){
                        $err_insert = $db->getLastErrno();
                        $err_insert_lyt .= "<b>Hubo un problema en el servidor</b>";
                        $err_insert_lyt .= "<br>Error:&nbsp;".$err_insert;
                        $err_insert_lyt .= "<br>Esto ocurrio cuando se intentaba guardar los detalles de la ruta, por favor intentalo de nuevo";
                        $ruta_err[] = $err_insert_lyt;
                    }
                                    
                }/*//---FIN FOREACH[$datas_detalle_ruta]-//*/
                
                
                
                
                /*
                *CREA EL XML CON LOS MAKER DE LAS RUTAS PARA GOOGLE MAPS
                */
                // Start XML file, create parent node
                $dom = new DOMDocument("1.0");
                $node = $dom->createElement("markers");
                $parnode = $dom->appendChild($node);

                //header("Content-type: text/xml");

                // Iterate through the rows, adding XML nodes for each
                if(is_array($dataDeudor) && !empty($dataDeudor)){
                    foreach($dataDeudor as $ddrKey){
                        $nombre_deudor = $ddrKey['primer_nombre_deudor'];
                        $apellido_deudor = $ddrKey['primer_apellido_deudor']; 
                        $nombre_completo = $nombre_deudor." ".$apellido_deudor;
                        $tel1_deudor = $ddrKey['tel_uno_deudor'];
                        $tel2_deudor = $ddrKey['tel_dos_deudor'];
                        $direccion_deudor = $ddrKey['dir_geo_deudor'];
                        $lat_deudor = $ddrKey['latitud_geo_deudor'];
                        $long_deudor = $ddrKey['longitud_geo_deudor'];

                        $node = $dom->createElement("marker");
                        $newnode = $parnode->appendChild($node);
                        $newnode->setAttribute("name",$nombre_completo);
                        if($tel1_deudor != ""){
                            $newnode->setAttribute("tel1",$tel1_deudor);    
                        }
                        if($tel2_deudor != ""){
                            $newnode->setAttribute("tel2",$tel2_deudor);    
                        }                        
                        $newnode->setAttribute("address", $direccion_deudor);
                        $newnode->setAttribute("lat", $lat_deudor);
                        $newnode->setAttribute("lng", $long_deudor);            
                    }
                }

                //echo $dom->saveXML();
                $nome_archivo_xml = $consecutivoRuta.".xml";
                $path_xml_files = "../appweb/files-display/markers/".$nome_archivo_xml;
                $dom->formatOutput = true;
                //$el_xml = $xml->saveXML();
                $dom->save($path_xml_files);

            }/*// FIN[$rutaINSERT]//*/

        }else{
            $ruta_err[] = "<b>No fue posible crear esta ruta</b>
            <br>Parece que el cobrador <strong>".$nombreCobradorSeleccionado."</strong>, no tiene recaudos programados para el día de hoy";        
        }/*//---FIN IS_ARRAY[$datas_detalle_ruta]-//*/
        
    }/*//---FIN[$errores_query]-//*/
            
}
//echo "<pre>";
//print_r($datas_detalle_ruta);
