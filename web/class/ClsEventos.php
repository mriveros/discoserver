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
    if  (empty($_POST['txtFechaA'])){$fechaA='';}else{ $fechaA= $_POST['txtFechaA'];}
    if  (empty($_POST['txtImagenA'])){$imagenA='';}else{ $imagenA= $_POST['txtImagenA'];}
   
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtFechaM'])){$fechaM='';}else{ $fechaM= $_POST['txtFechaM'];}
    if  (empty($_POST['txtImagenM'])){$imagenM='';}else{ $imagenM= $_POST['txtImagenM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'eventos', 'eve_nom')==true){
                echo '<script type="text/javascript">
		alert("El Evento ya existe. Intente ingresar otro Evento! :( ");
                window.location="http://localhost/disco/web/eventos/ABMevento.php";
		</script>';
                }else{
                $imagenconvert=  pg_escape_bytea($imagenA);
                //se define el Query   
                $query = "INSERT INTO eventos(eve_nom,eve_des,eve_fecha,eve_imagen,eve_activo)"
                    . "VALUES ('$nombreA','$descripcionA','$fechaA','$imagenconvert','t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/disco/web/eventos/ABMevento.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            $imagenconvert=  pg_escape_bytea($imagenM);
            pg_query("update eventos set eve_nom='$nombreM',"
                    . "eve_des= '$descripcionM',"
                    . "eve_fecha='$fechaM',"
                    . "eve_imagen='$imagenconvert',"
                    . "eve_activo='$estadoM' "
                    . "WHERE eve_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/disco/web/eventos/ABMevento.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update eventos set eve_activo='f' WHERE eve_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/disco/web/eventos/ABMevento.php");
	}
