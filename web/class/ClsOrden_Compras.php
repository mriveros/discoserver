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
    if  (empty($_POST['txtOrdenTipoA'])){$ordenA='';}else{ $ordenA = $_POST['txtOrdenTipoA'];}
    if  (empty($_POST['txtNumeroA'])){$numeroA='';}else{ $numeroA = $_POST['txtNumeroA'];}
    if  (empty($_POST['txtTerminoA'])){$terminoA='';}else{ $terminoA= $_POST['txtTerminoA'];}
    if  (empty($_POST['txtResolucionA'])){$resolucionA='';}else{ $resolucionA= $_POST['txtResolucionA'];}
    if  (empty($_POST['txtObservacionA'])){$observacionA='';}else{ $observacionA= $_POST['txtObservacionA'];}
    if  (empty($_POST['txtProveedorA'])){$proveedorA='';}else{ $proveedorA= $_POST['txtProveedorA'];}
    if  (empty($_POST['txtObjetoGastoA'])){$objetogastoA='';}else{ $objetogastoA= $_POST['txtObjetoGastoA'];}    
    if  (empty($_POST['txtDependenciaA'])){$dependenciaA='';}else{ $dependenciaA= $_POST['txtDependenciaA'];}
    if  (empty($_POST['txtFirmanteA'])){$firmanteA='';}else{ $firmanteA= $_POST['txtFirmanteA'];}
    if  (empty($_POST['txtOrdenCompra'])){$nroOrdenCompra='';}else{ $nroOrdenCompra= $_POST['txtOrdenCompra'];}
    
    
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
     if(isset($_POST['agregar'])){
            if(func_existeDato($numeroA, 'orden_compras', 'ord_nro')==true){
                echo '<script type="text/javascript">
		alert("La Orden de Compra ya existe. Ingrese otro Orden de Compra.");
                window.location="http://localhost/disco/web/orden_compras/RegistrarOrden.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO orden_compras(ord_tipo,ord_nro,ord_fecha,ord_termino,ord_res,"
                    . "ord_obs,pro_cod,obj_cod,fir_cod,dep_cod,ord_activo,facturado)"
                    . "VALUES ('$ordenA','$numeroA',now(),'$terminoA','$resolucionA','$observacionA',"
                    . "$proveedorA,$objetogastoA,$dependenciaA,$firmanteA,'t','f');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/disco/web/orden_compras/RegistrarOrden.php");
                }
     }
    //----------------------------Borrar Datos---------------------------------- 
      if(isset($_POST['borrar'])){
            $ejecucion = pg_query("delete from compras_detalles  WHERE ord_cod=$codigoElim")or die('Error al realizar la carga'); 
            $ejecucion =pg_query("delete from orden_compras  WHERE ord_cod=$codigoElim")or die('Error al realizar la carga'); 
            header("Refresh:0; url=http://localhost/disco/web/orden_compras/RegistrarOrden.php");
            
	}
    //----------------------------Enviar Datos a Tesoreria------------------
        if(isset($_POST['enviarDatos'])){
            $ejecucion = pg_query("update orden_compras set facturado='t' where ord_nro='$nroOrdenCompra'")or die('Error al realizar la carga');
            header("Refresh:0; url=http://localhost/disco/web/orden_compras/RegistrarOrden.php");
            
	}