<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2015
 * Sistema de Compras y Pagos HansaIIA 2.0
 */
session_start();
$codusuario=  $_SESSION["codigo_usuario"];

    include '../funciones.php';
    conexionlocal();
    
    //Datos del Form Agregar
    if  (empty($_POST['txtNombreA'])){$nombreA='';}else{ $nombreA = $_POST['txtNombreA'];}
    if  (empty($_POST['txtDescripcionA'])){$descripcionA='';}else{ $descripcionA= $_POST['txtDescripcionA'];}
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'objeto_gastos', 'obj_nom')==true){
                echo '<script type="text/javascript">
		alert("El objeto de gasto ya existe. Ingrese otro objeto de gastos");
                window.location="http://localhost/disco/web/objeto_gastos/ABMobjeto_gasto.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO objeto_gastos(obj_nom,obj_des,obj_activo)"
                    . "VALUES ('$nombreA','$descripcionA','t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/disco/web/objeto_gastos/ABMobjeto_gasto.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update objeto_gastos set obj_nom='$nombreM',"
                    . "obj_des= '$descripcionM',"
                    . "obj_activo='$estadoM' "
                    . "WHERE obj_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/disco/web/objeto_gastos/ABMobjeto_gasto.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update objeto_gastos set obj_activo='f' WHERE obj_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/disco/web/objeto_gastos/ABMobjeto_gasto.php");
	}
