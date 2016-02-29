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
    if  (empty($_POST['txtCuentaA'])){$cuentaA='';}else{ $cuentaA = $_POST['txtCuentaA'];}
    if  (empty($_POST['txtMontoA'])){$montoA='';}else{ $montoA= $_POST['txtMontoA'];}
    if  (empty($_POST['txtFechaA'])){$fechaA='';}else{ $fechaA= $_POST['txtFechaA'];}
    if  (empty($_POST['txtObservacionA'])){$observacionA='';}else{ $observacionA= $_POST['txtObservacionA'];}
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtCuentaM'])){$cuentaM='';}else{ $cuentaM = $_POST['txtCuentaM'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtMontoM'])){$montoM='';}else{ $montoM= $_POST['txtMontoM'];}
    if  (empty($_POST['txtFechaM'])){$fechaM='';}else{ $fechaA= $_POST['txtFechaM'];}
    if  (empty($_POST['txtObservacionM'])){$observacionM='';}else{ $observacionM= $_POST['txtObservacionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'depositos', 'dep_nom')==true){
                echo '<script type="text/javascript">
		alert("El Deposito ya existe. Ingrese otro Deposito.");
                window.location="http://localhost/disco/web/dependencias/ABMdependencia.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO depositos(cuen_cod,dep_nom,dep_obs,dep_monto,dep_fecha,dep_activo)"
                    . "VALUES ($cuentaA,'$nombreA','$observacionA',$montoA,'$fechaA','t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                sumarCuenta($cuentaA,$montoA);
                header("Refresh:0; url=http://localhost/disco/web/depositos/ABMdeposito.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update depositos set dep_nom='$nombreM',"
                    . "dep_obs= '$observacionM',"
                    . "dep_monto= '$montoM',"
                    . "cuen_cod= '$cuentaM',"
                    . "dep_activo='$estadoM'"
                    . "WHERE dep_cod=$codigoModif");
            $query = '';
            modificarCuenta($codigoModif,$montoM);
            header("Refresh:0; url=http://localhost/disco/web/depositos/ABMdeposito.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("delete from depositos WHERE dep_cod=$codigoElim");
            restablecerCuenta($codigoElim);
            header("Refresh:0; url=http://localhost/disco/web/depositos/ABMdeposito.php");
            
	}
        //esta funcion se realiza al realizar un nuevo deposito
        function sumarCuenta($cuenta,$monto){
            $codigo=obtenerUltimo('cuentas','cuen_cod');
             pg_query("update cuentas set cuen_anterior=cuen_saldo,cuen_saldo=(cuen_saldo+$monto) where cuen_cod=$codigo");
        }
        //esta funcion se realiza al modificar una cuenta
        function modificarCuenta($codigo,$monto){
            pg_query("update cuentas set cuen_saldo=cuen_anterior where cuen_cod=$codigo");
            pg_query("update cuentas set cuen_saldo=(cuen_saldo+$monto) where cuen_cod=$codigo");
        }
        //esta funcion se realiza al borrar una cuenta
        function restablecerCuenta($codigo){
             pg_query("update cuentas set cuen_saldo=cuen_anterior where cuen_cod=$cuenta");
        }