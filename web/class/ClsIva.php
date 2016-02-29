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
    if  (empty($_POST['txtPorcentajeA'])){$porcentajeA='';}else{ $porcentajeA= str_replace(',', '.', $_POST['txtPorcentajeA']);}
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if (empty($_POST['txtPorcentajeM'])){$porcentajeM='';}else{$porcentajeM= str_replace(',', '.', $_POST['txtPorcentajeM']);}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'iva', 'iva_nom')==true){
                echo '<script type="text/javascript">
		alert("El impuesto ya existe. Ingrese otro nombre");
                window.location="http://localhost/disco/web/iva/ABMiva.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO iva(iva_nom,iva_des,iva_porcentaje,iva_activo)"
                    . "VALUES ('$nombreA','$descripcionA',$porcentajeA,'t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/disco/web/iva/ABMiva.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update iva set iva_nom='$nombreM',"
                    . "iva_des= '$descripcionM',"
                    . "iva_activo='$estadoM', "
                    . "iva_porcentaje=$porcentajeM"
                    . "WHERE iva_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/disco/web/iva/ABMiva.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update iva set iva_activo='f' WHERE iva_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/disco/web/iva/ABMiva.php");
	}
