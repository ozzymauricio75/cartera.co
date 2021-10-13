<?php
require_once '../lib/MysqliDb.php';
require_once "../../cxconfig/config.inc.php";

$fielQ = (empty($_POST['fieldq']))? "" : $_POST['fieldq'];
$fielCatQ = (empty($_POST['fieldCatq']))? "" : $_POST['fieldCatq'];

//GURPO TALLAS
if(isset($_POST['idgpz']) && $fielQ=="gpz"){
    
    //$varGet = (string)$_POST['idgpz'];
    //$varGet = $db->escape($varGet);  
    $varGet = $_POST['idgpz'];
    //$dataForGenero = array();
    
    $db->where('genero_ropa',$varGet);
    $db->orderBy('tipo_prenda','asc');
    $queryTbl = $db->get ('grupo_tallas'); 
    
    $rowQueryTbl = count($queryTbl);
    
    //$optionList = "<option value='0' class='txtCapitalice'>Selecciona un grupo</option>";
    
    if($rowQueryTbl>0){
        echo "<option value='0' >Selecciona un grupo de tallas</option>"; 
        foreach($queryTbl as $qKey){
            $idGrupoTalla = $qKey['id_grupo_talla'];
            $nameGrupoTalla = $qKey['tipo_prenda'];
            $tagGrupoTalla = $qKey['genero_ropa']; 
            $dataTallaList =  $tagGrupoTalla."_".$nameGrupoTalla;
            
            $optionList = "<option value='".$dataTallaList."' class='txtCapitalice' data-tallalist='".$dataTallaList."' data-tipoprenda='".$nameGrupoTalla."'>".$nameGrupoTalla."&nbsp;-&nbsp;".$tagGrupoTalla."</option>";
            echo $optionList;                        
        }        
    }
    
}

//ESPECIFICA GRUPO TALLAS
//$_POST['idgt'] = '2';

if(isset($_POST['idgt']) && $fielQ=="gtl"){//
    
    $varGet = (int)$_POST['idgt'];
    $varGet = $db->escape($varGet);  
    $varGet = $_POST['idgt'];
    
    //function ettpQ(){ 
        //global $db;
        //global $varGet;
        $dataForGenero = array();

        //$db->where('id_grupo_talla',$varGet);
        $db->orderBy('posi_talla','asc');
        $queryTbl = $db->get ('especifica_tallas_tipo_prenda'); 

        $rowQueryTbl = count($queryTbl);
        if($rowQueryTbl>0){
            foreach($queryTbl as $qKey){
                $idGrupoTalla = $qKey['id_talla_tablas'];
                $nameGrupoTalla = $qKey['talla_tipo_prenda'];
                $tagGrupoTalla = $qKey['genero_ropa'];        


                $optionList ="<div class='col-xs-4'>";            
                $optionList .="<label>";
                $optionList .="<input type='checkbox' name='tallaletras[]' class='flat-red' value='" .$idGrupoTalla ."'/>&nbsp;&nbsp;";
                $optionList .= $nameGrupoTalla;
                $optionList .="</label>";
                $optionList .="</div>";


                //$optionList = "<option value='".$idGrupoTalla."' class='txtCapitalice'>".$nameGrupoTalla."</option>";
                //echo $optionList;        
                
                $dataForGenero[] = $qKey;
                 /*$dataForGenero[] = array(
                    'codtalla' => $idGrupoTalla,
                    'nametalla' => $nameGrupoTalla
                );*/
                
            }
                                   
            //echo json_encode($dataForGenero);
            return $dataForGenero;
        }else{
            echo "NO HAY ARRAY";
        }
   // }
          
}


//CATEGORIAS
if(isset($_POST['idgpz']) && $fielCatQ =="catl2"){   
    $idEspecie=$_POST['idgpz'];
//function loadRaza($idEspecie){
    //$db global;
    //SELECT `id_catego_product`, `id_depart_prod`, `nome_catego_product`, `descri_catego_prod`, `tags_depatament_produsts`, `tipo_kit_4user`, `cant_pz_kit` FROM `categorias_productos` WHERE 1
    
    $db->where ('tags_depatament_produsts', $idEspecie);
    $db->orderBy("nome_catego_product","Asc");									
    $razaQuery = $db->get('categorias_productos');

    if ($db->count > 0){
        echo "<option value='' selected>Selecciona una categoría</option>";	
        foreach ($razaQuery as $razaKey) { 
            $idRaza = $razaKey['id_catego_product'];
            $nomeRaza = $razaKey['nome_catego_product']; 
            $descriCatego = $razaKey['descri_catego_prod'];  
            $tagDeptoCatego = $razaKey['tags_depatament_produsts'];  
            echo "<option value='".$idRaza."'>".$nomeRaza."&nbsp;&nbsp;".$descriCatego."&nbsp;-&nbsp;".$tagDeptoCatego."</option>";	
        }
    }	
//}
}


//CIUDADES
if(isset($_POST['depto']) && $fielQ=="city"){
    $idEspecie=$_POST['depto'];
//function loadRaza($idEspecie){
    //$db global;
    //SELECT `id_ciudad_user`, `name_ciudad_user`, `name_estado_user`, `id_estado_rel` FROM `ciudades_user` WHERE 1
    
    $db->where ('id_estado_rel', $idEspecie);
    $db->orderBy("name_ciudad_user","Asc");									
    $razaQuery = $db->get('ciudades_user');

    if ($db->count > 0){
        echo "<option value='' selected>Selecciona una Ciudad</option>";	
        foreach ($razaQuery as $razaKey) { 
            $idRaza = $razaKey['id_ciudad_user'];
            $nomeRaza = $razaKey['name_ciudad_user'];            
            echo "<option value='".$idRaza."'>".$nomeRaza."</option>";	
        }
    }	
//}
}

