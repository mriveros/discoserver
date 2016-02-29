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

    if  (empty($_POST['txtNumeroA'])){$numeroA='';}else{ $numeroA = $_POST['txtNumeroA'];}
    if  (empty($_POST['txtProveedorA'])){$proveedorA='';}else{ $proveedorA= $_POST['txtProveedorA'];}
    if  (empty($_POST['txtMontoA'])){$montoA='';}else{ $montoA= $_POST['txtMontoA'];}    
    if  (empty($_POST['txtIVAA'])){$ivaA='';}else{ $ivaA= $_POST['txtIVAA'];}
    if  (empty($_POST['txtCodigo'])){$ordenA='';}else{ $ordenA= $_POST['txtCodigo'];}
    
    //Cuando se crea una factura
    if  (empty($_POST['txtNumeroC'])){$numeroC='';}else{ $numeroC = $_POST['txtNumeroC'];}
    if  (empty($_POST['txtProveedorC'])){$proveedorC='';}else{ $proveedorC= $_POST['txtProveedorC'];}
    if  (empty($_POST['txtConceptoC'])){$conceptoC='';}else{ $conceptoC= $_POST['txtConceptoC'];}
    if  (empty($_POST['txtMontoC'])){$montoC='';}else{ $montoC= $_POST['txtMontoC'];}    
    if  (empty($_POST['txtIVAC'])){$ivaC='';}else{ $ivaC= $_POST['txtIVAC'];}
    if  (empty($_POST['txtObservacionC'])){$observacionC='';}else{ $observacionC= $_POST['txtObservacionC'];}
    //if  (empty($_POST['txtIVA10C'])){$iva10C='';}else{ $iva10C= $_POST['txtIVA10C'];}
    //if  (empty($_POST['txtExentaC'])){$ExentaC='';}else{ $ExentaC= $_POST['txtExentaC'];}
    if  (empty($_POST['txtCodigoC'])){$ordenC='';}else{ $ordenC= $_POST['txtCodigoC'];}
    
    
    //DAtos para el Eliminado de factura
    if  (empty($_POST['txtCodigoE'])){$nroElim=0;}else{$nroElim=$_POST['txtCodigoE'];}
    
    
    
     if(isset($_POST['agregar'])){
            if(func_existeDato($numeroA, 'facturas', 'fac_nro')==true){
                echo '<script type="text/javascript">
		alert("La Factura ya existe. Ingrese otro numero de Factura.");
                window.location="http://localhost/disco/web/facturacion/RegistrarFacturaCompra.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO facturas(fac_nro,pro_cod,ord_cod,fac_monto,fac_iva,"
                    . "fac_fecha,fac_activo,fac_retencion)"
                    . "VALUES ('$numeroA',$proveedorA,$ordenA,$montoA,$ivaA,now(),'t','f');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                $ejecucion = pg_query("update orden_compras set ord_activo='f' WHERE ord_cod=$ordenA")or die('Error al realizar la carga'); 
                header("Refresh:0; url=http://localhost/disco/web/facturacion/RegistrarFacturaCompra.php");
                }
     }
    //----------------------------Borrar Datos---------------------------------- 
      if(isset($_POST['borrar'])){
          //traer la ultima factura generada
        //$query = "Select max(fac_cod) from facturas;";
        //$resultado=pg_query($query);
        //$row=  pg_fetch_array($resultado);
        //$codfactura=$row[0];
        //seleccionar el orden de compra
        $query = "Select ord.ord_cod from orden_compras ord, facturas fac
        where ord.ord_cod=fac.ord_cod and fac.fac_nro='$nroElim';";
        $resultado=pg_query($query);
        $row=  pg_fetch_array($resultado);
        $codOrdenCompra=$row[0];
        
        $ejecucion = pg_query("delete from facturas  WHERE fac_nro='$nroElim'")or die('Error al realizar la carga'); 
        $ejecucion = pg_query("update orden_compras set ord_activo='t',facturado='t' WHERE ord_cod='$codOrdenCompra'")or die('Error al realizar la carga');    
        header("Refresh:0; url=http://localhost/disco/web/facturacion/RegistrarFacturaCompra.php");
            
	}
        
    //------------------------------Crear Nueva Factura---------------------------------------------
        if(isset($_POST['crearFactura'])){
            if(func_existeDato($numeroA, 'facturas', 'fac_nro')==true){
                echo '<script type="text/javascript">
		alert("La Factura ya existe. Ingrese otro numero de Factura.");
                window.location="http://localhost/disco/web/facturacion/CrearFactura.php";
		</script>';
                }else{
                //-----------------------Calculo del IVA---------------------------
                if($ivaC=='1'){$montoIVa=($montoC*5/100);}elseif($ivaC=='2'){$montoIVa=($montoC*10/100);}else{ $montoIVa=0;}
                //se define el Query   
                $query = "INSERT INTO facturas(fac_nro,con_cod,pro_cod,fac_monto,fac_iva,"
                    . "fac_fecha,fac_activo,fac_obs,fac_retencion)"
                    . "VALUES ('$numeroC',$conceptoC,$proveedorC,$montoC,$montoIVa,now(),'t','$observacionC','f');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/disco/web/facturacion/CrearFactura.php");
                }
     }
    //----------------------------Borrar la factura creada------------------
        if(isset($_POST['borrarCrearFactura'])){
            $ejecucion = pg_query("delete from facturas where fac_nro='$nroElim'")or die('Error al realizar la carga');
            header("Refresh:0; url=http://localhost/disco/web/facturacion/CrearFactura.php");
            
	}