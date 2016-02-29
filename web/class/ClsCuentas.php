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
    if  (empty($_POST['txtBancoA'])){$bancoA='';}else{ $bancoA = $_POST['txtBancoA'];}
    if  (empty($_POST['txtNombreA'])){$nombreA='';}else{ $nombreA = $_POST['txtNombreA'];}
    if  (empty($_POST['txtDescripcionA'])){$descripcionA='';}else{ $descripcionA= $_POST['txtDescripcionA'];}
    if  (empty($_POST['txtFechaA'])){$fechaA='';}else{ $fechaA= $_POST['txtFechaA'];}
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtBancoM'])){$bancoM='';}else{ $bancoM = $_POST['txtBancoM'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'cuentas', 'cuen_nom')==true){
                echo '<script type="text/javascript">
		alert("La Cuenta ya existe. Ingrese otra Cuenta Bancaria");
                window.location="http://localhost/disco/web/cuentas/ABMcuenta.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO cuentas(ban_cod,cuen_nom,cuen_des,cuen_fecha,cuen_activo)"
                    . "VALUES ($bancoA,'$nombreA','$descripcionA','$fechaA','t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/disco/web/cuentas/ABMcuenta.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update cuentas set cuen_nom='$nombreM',"
                    . "cuen_des= '$descripcionM',"
                    . "cuen_activo='$estadoM' ,"
                    . "ban_cod= $bancoM"
                    . "WHERE cuen_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/disco/web/cuentas/ABMcuenta.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update cuentas set cuen_activo='f' WHERE cuen_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/disco/web/cuentas/ABMcuenta.php");
	}
