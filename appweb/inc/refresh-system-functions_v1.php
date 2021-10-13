<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";
require_once '../../cxconfig/global-settings.php';
require_once 'sessionvars.php';
require_once 'query-custom-user.php';
require_once "../lib/gump.class.php";
require_once "site-tools.php"; 

$siError = 0;
$errQueryTmpl ="";
$response = array();
$fieldPost = empty($_POST['datafield'])? "" : $_POST['datafield'];

/*
*RESETEAR SYSTEMA 
*Esta opcion elimina todos los datos de la base de datos
*Deja el sistema en su configuracion incial
*===============================================
*/

if(!empty($fieldPost) && $fieldPost == "resetsystem"){
    /*
    *CAJA MENOR
    */
    $truncate_caja_menor_tbl = $db->rawQuery("TRUNCATE TABLE caja_menor_tbl");
    
    /*if(!$truncate_caja_menor_tbl){

        $cajamenor_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los datos de caja menor</b>
            <br>Erro: ".$cajamenor_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *CIUDADES USUARIO
    */
    $truncate_ciudad_usuario_tbl = $db->rawQuery("TRUNCATE TABLE ciudad_usuario_tbl");
     
    /*if(!$truncate_ciudad_usuario_tbl){

        $ciudades_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar las ciudades de usuarios</b>
            <br>Erro: ".$ciudades_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *COBRADORES
    */
    $queryCobradores = array();
    $db->where("id_acreedor", $idSSUser);
    $queryCobradores = $db->get("cobrador_tbl", null, "id_cobrador");

    if(is_array($queryCobradores) && !empty($queryCobradores)){
        foreach($queryCobradores as $qcKey){

            //borra tabla usuario
            $db->where("id_usuario_plataforma", $qcKey['id_cobrador']);
            $db->where("tag_seccion_plataforma", "cobrador");
            $delete_usuario_tbl = $db->delete("usuario_tbl");

            //borra tabla cobrador
            $db->where("id_cobrador", $qcKey['id_cobrador']);        
            $delete_cobrador_tbl = $db->delete("cobrador_tbl");
                        
        }
        
        
    }
    
    /*
    *COBRADORES
    */
    $truncate_cobrador_tbl = $db->rawQuery("TRUNCATE TABLE cobrador_tbl");
    
    /*if(!$truncate_cobrador_tbl){

        $cobrador_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los cobradores</b>
            <br>Erro: ".$cobrador_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *CREDITOS
    */
    $truncate_creditos_tbl = $db->rawQuery("TRUNCATE TABLE creditos_tbl");
    
    /*if(!$truncate_creditos_tbl){

        $creditos_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los creditos</b>
            <br>Erro: ".$creditos_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
                
    /*if(!$delete_dinero_recibido_tbl){

        $dinerorecibe_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible borrar los dineros recibidos</b>
            <br>Erro: ".$dinerorecibe_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;  
        $siError = 1;
    }*/

    
    /*
    *INYECCIONES CAPITAL
    */        
    $truncate_inyeccion_capital_tbl = $db->rawQuery("TRUNCATE TABLE inyeccion_capital_tbl");
    
    
    /*
    *DEUDORES
    */
    $truncate_deudor_tbl = $db->rawQuery("TRUNCATE TABLE deudor_tbl");
    
        
    /*if(!$truncate_deudor_tbl){

        $deudor_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los deudores</b>
            <br>Erro: ".$deudor_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *CODEUDORES
    */
    $truncate_codeudor_tbl = $db->rawQuery("TRUNCATE TABLE codeudor_tbl");
    
    /*if(!$truncate_codeudor_tbl){

        $codeudor_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los codeudores</b>
            <br>Erro: ".$codeudor_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *DINERO RECIBIDO
    */
    $truncate_dinero_recibido_tbl = $db->rawQuery("TRUNCATE TABLE dinero_recibido_tbl");
    
    /*if(!$truncate_dinero_recibido_tbl){

        $ingresos_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los ingresos de dinero</b>
            <br>Erro: ".$ingresos_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    
    /*
    *DOCUMENTOS REQUISITOS
    */
    $truncate_documentos_requisitos_tbl = $db->rawQuery("TRUNCATE TABLE documentos_requisitos_tbl");
    
    /*if(!$truncate_documentos_requisitos_tbl){

        $docuemntos_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los documentos de usuario</b>
            <br>Erro: ".$docuemntos_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *ESPECIFICA RUTA
    */
    $truncate_especifica_ruta_tbl = $db->rawQuery("TRUNCATE TABLE especifica_ruta_tbl");
    
    /*if(!$truncate_especifica_ruta_tbl){

        $especiruta_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar las especificaciones de ruta</b>
            <br>Erro: ".$especiruta_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *ESTADOS / REGION USUARIO
    */
    $truncate_estado_usuario_tbl = $db->rawQuery("TRUNCATE TABLE estado_usuario_tbl");
    
    /*if(!$truncate_estado_usuario_tbl){

        $regiones_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar las regiones de los usuarios</b>
            <br>Erro: ".$regiones_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *PAISES USUARIO
    */
    $truncate_pais_usuario_tbl = $db->rawQuery("TRUNCATE TABLE pais_usuario_tbl");
    
    /*if(!$truncate_pais_usuario_tbl){

        $pais_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los paises de usuario</b>
            <br>Erro: ".$pais_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *PLANES PAGO
    */
    $truncate_planes_pago_tbl = $db->rawQuery("TRUNCATE TABLE planes_pago_tbl");
    
    /*if(!$truncate_planes_pago_tbl){

        $planpago_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los planes de pago</b>
            <br>Erro: ".$planpago_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *RECAUDOS
    */
    $truncate_recaudos_tbl = $db->rawQuery("TRUNCATE TABLE recaudos_tbl");
    
    /*if(!$truncate_recaudos_tbl){

        $recaudos_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar los recaudos</b>
            <br>Erro: ".$recaudos_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *REFERENCIA COMERCIAL
    */
    $truncate_referencia_comercial_tbl = $db->rawQuery("TRUNCATE TABLE referencia_comercial_tbl");
    
    /*if(!$truncate_referencia_comercial_tbl){

        $refcomer_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar las referencias comerciales</b>
            <br>Erro: ".$refcomer_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *REFERENCIA FAMILIAR
    */
    $truncate_referencia_familiar_tbl = $db->rawQuery("TRUNCATE TABLE referencia_familiar_tbl");
    
    /*if(!$truncate_referencia_familiar_tbl){

        $reffami_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar las referencias familiares</b>
            <br>Erro: ".$reffami_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *REFERENCIA PERSONAL
    */
    $truncate_referencia_personal_tbl = $db->rawQuery("TRUNCATE TABLE referencia_personal_tbl");
    
    /*if(!$truncate_referencia_personal_tbl){

        $refperso_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar las referencias personales</b>
            <br>Erro: ".$refperso_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    /*
    *RUTAS
    */
    $truncate_rutas_tbl = $db->rawQuery("TRUNCATE TABLE rutas_tbl");
    
    /*if(!$truncate_rutas_tbl){

        $rutas_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible eliminar las rutas</b>
            <br>Erro: ".$rutas_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER SISTEMA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;            
        $siError = 1;
    }*/
    
    
    
    /*
    *RESET CONFIGURACIONES USUARIO
    */
    $datasCustomUser=array(
        'status_configuraciones' => 0,
        'define_sabados' => 0,
        'define_domingos' => 0,
        'define_festivos' => 0,
        'define_sabado_diaria' => 0,
        'define_domingo_diaria' => 0,
        'define_festivos_diaria' => 0,
        'define_cuadre_caja_diario' => 1,
        'define_capital_inicial' => 0,
    );
    $db->where("id_usuario_plataforma", $idSSUser);
    $resetCustomUser = $db->update("usuario_configuraciones_tbl", $datasCustomUser);
    
    /*if(!$resetCustomUser){

        $customuser_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible reestablecer tu configuración de usuario</b>
            <br>Erro: ".$customuser_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;    
        
        $siError = 1;
    }*/
    
    /*
    *USUARIOS ASIGNADOS A CREDITOS
    */    
    $truncate_usuarios_asignados_tbl = $db->rawQuery("TRUNCATE TABLE usuario_asignado_credito");
    
    if($siError == 0){
        $response = true;    
    }
            
    exit(json_encode($response));
    
}//[FIN |  resetsystem]



/*
*CONFIGURACIONES INCIALES DE CUENTA DE USUARIO
*===============================================
*/

if(!empty($fieldPost) && $fieldPost == "resetuser"){
    

    /*
    *CAJA MENOR
    */
    $db->where("id_acreedor", $idSSUser);
    $delete_caja_menor_tbl = $db->delete("caja_menor_tbl");
    
    /*if(!$delete_caja_menor_tbl){
        
        $caja_menor_err = $db->getLastErrno();
        
        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible borrar los datos de caja menor</b>
            <br>Erro: ".$caja_menor_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";
        
        $response['error'] = $errQueryTmpl;  
        $siError = 1;
    }*/
        

    /*
    *COBRADORES
    */
    $queryCobradores = array();
    $db->where("id_acreedor", $idSSUser);
    $queryCobradores = $db->get("cobrador_tbl", null, "id_cobrador");

    if(is_array($queryCobradores) && !empty($queryCobradores)){
        foreach($queryCobradores as $qcKey){

            //borra tabla usuario
            $db->where("id_usuario_plataforma", $qcKey['id_cobrador']);
            $db->where("tag_seccion_plataforma", "cobrador");
            $delete_usuario_tbl = $db->delete("usuario_tbl");

            //borra tabla cobrador
            $db->where("id_cobrador", $qcKey['id_cobrador']);        
            $delete_cobrador_tbl = $db->delete("cobrador_tbl");
            
            /*if(!$delete_usuario_tbl){
        
                $usuario_cobrador_err = $db->getLastErrno();

                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
                    <b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible borrar los datos de usuario cobrador</b>
                    <br>Erro: ".$usuario_cobrador_err."
                    <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";

                $response['error'] = $errQueryTmpl;  
                $siError = 1;
            }

            if(!$delete_cobrador_tbl){

                $cobrador_err = $db->getLastErrno();

                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
                    <b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible borrar los cobradores</b>
                    <br>Erro: ".$cobrador_err."
                    <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";

                $response['error'] = $errQueryTmpl;   
                $siError = 1;
            }*/
        }
        
        
    }


    /*
    *CREDITOS
    */
    $queryCreditos = array();
    $db->where("id_acreedor", $idSSUser);
    $queryCreditos = $db->get("creditos_tbl", null, "id_creditos, code_consecutivo_credito");
    if(is_array($queryCreditos) && !empty($queryCreditos)){
        foreach($queryCreditos as $qcreKey){
            $idCredito = $qcreKey['id_creditos'];
            $consecutivoCredito = $qcreKey['code_consecutivo_credito'];
            
            //borra recaudos
            $db->where("ref_recaudo", $consecutivoCredito);
            $delete_recaudos_tbl = $db->delete("recaudos_tbl");

            //borra plan pagos
            $db->where("id_credito", $idCredito);
            $delete_planes_pago_tbl = $db->delete("planes_pago_tbl");

            //borra creditos
            $db->where("id_creditos", $idCredito);
            $delete_creditos_tbl = $db->delete("creditos_tbl");
            
            //borra usuarios asignados al credito
            $db->where("id_credito", $idCredito);
            $delete_creditos_tbl = $db->delete("usuario_asignado_credito");
            
            
            /*if(!$delete_recaudos_tbl){
        
                $recaudos_err = $db->getLastErrno();

                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
                    <b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible borrar los recaudos</b>
                    <br>Erro: ".$recaudos_err."
                    <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";

                $response['error'] = $errQueryTmpl;    
                $siError = 1;
            }

            if(!$delete_planes_pago_tbl){

                $planpagos_err = $db->getLastErrno();

                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
                    <b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible borrar los planes de pago</b>
                    <br>Erro: ".$planpagos_err."
                    <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";

                $response['error'] = $errQueryTmpl;    
                $siError = 1;
            }

            if(!$delete_creditos_tbl){

                $creditos_err = $db->getLastErrno();

                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
                    <b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible borrar los creditos</b>
                    <br>Erro: ".$creditos_err."
                    <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";

                $response['error'] = $errQueryTmpl;   
                $siError = 1;
            }*/
        }
        
        
    }


    /*
    *DINERO RECIBIDOS
    */
    $db->where("id_acreedor", $idSSUser);
    $delete_dinero_recibido_tbl = $db->delete("dinero_recibido_tbl");
    
    /*if(!$delete_dinero_recibido_tbl){

        $dinerorecibe_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible borrar los dineros recibidos</b>
            <br>Erro: ".$dinerorecibe_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;  
        $siError = 1;
    }*/

    
    /*
    *INYECCIONES CAPITAL
    */
    $db->where("id_acreedor", $idSSUser);
    $delete_inyeccion_capital_tbl = $db->delete("inyeccion_capital_tbl");
    
    /*if(!$delete_inyeccion_capital_tbl){

        $inyectacapital_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible borrar los capitales nuevos</b>
            <br>Erro: ".$inyectacapital_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;   
        $siError = 1;
    }*/

    

    /*
    *RUTAS
    */
    $queryRutas = array();
    $db->where("id_acreedor", $idSSUser);
    $queryRutas = $db->get("rutas_tbl", null, "id_ruta, consecutivo_ruta");
    if(is_array($queryRutas) && !empty($queryRutas)){
        foreach($queryRutas as $qrKey){

            $consecutivoRuta = $qrKey['consecutivo_ruta'];

            //borra especificaciones ruta
            $db->where("id_ruta",$qrKey['id_ruta']);
            $delete_especifica_ruta_tbl = $db->delete("especifica_ruta_tbl");

            //borra archivos marcadores google mapa
            $pathMarcador = "../files-display/markers/".$consecutivoRuta.".xml";
            @unlink($pathMarcador);

            //borra rutas
            $db->where("id_ruta",$qrKey['id_ruta']);
            $delete_rutas_tbl = $db->delete("rutas_tbl");
            
            /*if(!$delete_especifica_ruta_tbl){

                $epecificaruta_err = $db->getLastErrno();

                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
                    <b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible borrar las especificaciones de ruta de cobro</b>
                    <br>Erro: ".$epecificaruta_err."
                    <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";

                $response['error'] = $errQueryTmpl;   
                $siError = 1;
            }
            
            if(!$delete_rutas_tbl){

                $ruta_err = $db->getLastErrno();

                $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
                    <b>*** Algo salio mal ***</b><br>
                    <br>Wrong: <b>No fue posible borrar las ruta de cobro</b>
                    <br>Erro: ".$ruta_err."
                    <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
                    <br>Si el error continua, por favor entre en contacto con soporte</li>";

                $response['error'] = $errQueryTmpl;                   
                $siError = 1;
            }*/
        }
    }
    
    

    /*
    *RESET CONFIGURACIONES USUARIO
    */
    $datasCustomUser=array(
        'status_configuraciones' => 0,
        'define_sabados' => 0,
        'define_domingos' => 0,
        'define_festivos' => 0,
        'define_sabado_diaria' => 0,
        'define_domingo_diaria' => 0,
        'define_festivos_diaria' => 0,
        'define_cuadre_caja_diario' => 1,
        'define_capital_inicial' => 0,
    );
    $db->where("id_usuario_plataforma", $idSSUser);
    $resetCustomUser = $db->update("usuario_configuraciones_tbl", $datasCustomUser);
    
    /*if(!$resetCustomUser){

        $customuser_err = $db->getLastErrno();

        $errQueryTmpl .="<li class='list-group-item list-group-item-danger'>
            <b>*** Algo salio mal ***</b><br>
            <br>Wrong: <b>No fue posible reestablecer tu configuración de usuario</b>
            <br>Erro: ".$customuser_err."
            <br>Intenta de nuevo dando click en <b>REESTABLECER CUENTA</b>
            <br>Si el error continua, por favor entre en contacto con soporte</li>";

        $response['error'] = $errQueryTmpl;    
        
        $siError = 1;
    }*/
    
    if($siError == 0){
        $response = true;    
    }
            
    exit(json_encode($response));

}//[FIN |  resetuser]
