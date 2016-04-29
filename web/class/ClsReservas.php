<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2015
 * Sistema de Compras y Pagos DiscoA 2.0
 */
session_start();
$codusuario=  $_SESSION["codigo_usuario"];

    include '../funciones.php';
    conexionlocal();
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
        //si es Modificar    
        if(isset($_POST['modificar'])){
            pg_query("update reservas set res_confirm='$estadoM' where res_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://dev.appwebpy.com/disco/web/reservas/ABMreserva.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update reservas set res_activo='f' WHERE res_cod=$codigoElim");
            header("Refresh:0; url=http://dev.appwebpy.com/disco/web/reservas/ABMreserva.php");
	}
