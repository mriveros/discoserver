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
    if  (empty($_POST['txtApellidoA'])){$apellidoA='';}else{ $apellidoA= $_POST['txtApellidoA'];}
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtApellidoM'])){$apellidoM='';}else{ $apellidoM= $_POST['txtApellidoM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'firmantes', 'fir_nom')==true){
                echo '<script type="text/javascript">
		alert("El Firmante ya existe. Ingrese otro Firmante");
                window.location="http://localhost/disco/web/firmantes/ABMfirmante.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO firmantes(fir_nom,fir_ape,fir_activo)"
                    . "VALUES ('$nombreA','$apellidoA','t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/disco/web//firmantes/ABMfirmante.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update firmantes set fir_nom='$nombreM',"
                    . "fir_ape= '$apellidoM',"
                    . "fir_activo='$estadoM' "
                    . "WHERE fir_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/disco/web/firmantes/ABMfirmante.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update firmantes set fir_activo='f' WHERE fir_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/disco/web/firmantes/ABMfirmante.php");
	}
