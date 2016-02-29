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

    if  (empty($_POST['txtCodigo'])){$codigoA='';}else{ $codigoA = $_POST['txtCodigo'];}
    if  (empty($_POST['txtCuentasC'])){$cuentaA='';}else{ $cuentaA= $_POST['txtCuentasC'];}
    if  (empty($_POST['txtFuenteA'])){$fuenteA='';}else{ $fuenteA= $_POST['txtFuenteA'];}    
    if  (empty($_POST['txtChequeA'])){$chequeA='';}else{ $chequeA= $_POST['txtChequeA'];}
    if  (empty($_POST['txtMontoA'])){$montoA='';}else{ $montoA= $_POST['txtMontoA'];}
    
     if(isset($_POST['agregar'])){
         //obtener codigo de banco
        $query = "Select ban_cod,cuen_saldo from cuentas where cuen_cod='$cuentaA' ;";
        $resultado=pg_query($query);
        $row=  pg_fetch_array($resultado);
        $codbanco=$row[0];
        $montoCuenta=$row[1];
        
        //----------------------------------------------------------------------
        if($montoCuenta >= $montoA){
                $query = "INSERT INTO orden_pago(ban_cod,fac_cod,pag_fuente,pag_fecha,pag_monto,pag_cheque,pag_activo,cuen_cod) "
                . "VALUES ($codbanco,$codigoA,$fuenteA,now(),$montoA,'$chequeA','t',$cuentaA);";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                $ejecucion = pg_query("update facturas set fac_activo='f' WHERE fac_cod=$codigoA")or die('Error al realizar la carga'); 
                //actualizar monto de la cuenta en banco
                $query = "Select cuen_saldo from cuentas where cuen_cod='$cuentaA' ;";
                $resultado=pg_query($query);
                $row=  pg_fetch_array($resultado);
                $ejecucion = pg_query("update cuentas set cuen_anterior=cuen_saldo, cuen_saldo=cuen_saldo-$montoA WHERE cuen_cod=$cuentaA")or die('Error al realizar la carga'); 
                header("Refresh:0; url=http://localhost/disco/web/orden_pagos/OrdenPago.php");
               }
           else{
                echo '<script type="text/javascript">
		alert("El Monto a Pagar es mayor que la disponible.Intente pagar con otra cuenta o comuniquese con el administrador");
                window.location="http://localhost/disco/web/orden_pagos/OrdenPago.php";
		</script>'; 
                
           }
                header("Refresh:0; url=http://localhost/disco/web/orden_pagos/OrdenPago.php");
                
     }
    