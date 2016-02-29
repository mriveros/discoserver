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
    if  (empty($_POST['txtPorcentajeA'])){$porcentajeA='';}else{ $porcentajeA= $_POST['txtPorcentajeA'];}
    if  (empty($_POST['txtMontoA'])){$montoA='';}else{ $montoA= $_POST['txtMontoA'];}
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtPorcentajeM'])){$porcentajeM='';}else{ $porcentajeM= $_POST['txtPorcentajeM'];}
    if  (empty($_POST['txtMontoM'])){$montoM='';}else{ $montoM= $_POST['txtMontoM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
   //Recepcion de Codigo de factura a Retener
   if  (empty($_POST['txtCodigoFactura'])){$codigoFactura=0;}else{$codigoFactura=$_POST['txtCodigoFactura'];}
   //Recepcion de Codigo de factura a Retener
   if  (empty($_POST['txtCodigoFacturaNORETEN'])){$codigoFacturaSINRETENCION=0;}else{$codigoFacturaSINRETENCION=$_POST['txtCodigoFacturaNORETEN'];}
   
   
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'retenciones', 'ret_nom')==true){
                echo '<script type="text/javascript">
		alert("La retencion ya existe. Ingrese otra retencion");
                window.location="http://localhost/disco/web/rentenciones/ABMretencion.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO retenciones(ret_nom,ret_des,ret_porcentaje,ret_minimo,ret_activo)"
                    . "VALUES ('$nombreA','$descripcionA',$porcentajeA,$montoA,'t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/disco/web/retenciones/ABMretencion.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update retenciones set ret_nom='$nombreM',"
                    . "ret_des= '$descripcionM',"
                    . "ret_porcentaje= $porcentajeM,"
                    . "ret_minimo= $montoM,"
                    . "ret_activo='$estadoM' "
                    . "WHERE ret_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/disco/web/retenciones/ABMretencion.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update retenciones set ret_activo='f' WHERE ret_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/disco/web/retenciones/ABMretencion.php");
	}
        
        if(isset($_POST['calcularRetencion'])){
        //Obtener el monto de la factura y el monto del IVA
        $query = "Select fac_monto,fac_iva from facturas where fac_cod=$codigoFactura;";
        $resultado=pg_query($query);
        $row=  pg_fetch_array($resultado);
        $montoFactura=$row[0];
        $montoIVA=$row[1]; 
        //si el monto es mayor, aplicamos IVA RETENCION DE 30% del IVA
        if ($montoFactura>1883000)
        {
        $retencion30=$montoIVA * 0.3;
        $query = "INSERT INTO pago_retenciones(ret_cod,fac_cod,pagret_fecha,pagret_monto,pagret_activo)"
                . "VALUES (1,$codigoFactura,now(),$retencion30,'t');";
                $ejecucion = pg_query($query)or die('Error al realizar la carga');  
        }
        //si el monto es mayor, ley de contrataciones
        if ($montoFactura>883000)
        {
        $retencion002=($montoFactura-$montoIVA) * 0.02;
        $query = "INSERT INTO pago_retenciones(ret_cod,fac_cod,pagret_fecha,pagret_monto,pagret_activo)"
        . "VALUES (3,$codigoFactura,now(),$retencion002,'t');";
        $ejecucion = pg_query($query)or die('Error al realizar la carga');  

        }
        //para cualquier monto de factura aplicamos impuesto a la renta
        $retencion004=$retencion002 * 0.004;
        $query = "INSERT INTO pago_retenciones(ret_cod,fac_cod,pagret_fecha,pagret_monto,pagret_activo)"
        . "VALUES (2,$codigoFactura,now(),$retencion004,'t');";
        $ejecucion = pg_query($query)or die('Error al realizar la carga');
        pg_query("update facturas set fac_retencion='t' WHERE fac_cod=$codigoFactura");
        header("Refresh:0; url=http://localhost/disco/web/facturacion/Retenciones.php");
	}
         if(isset($_POST['sinRetencion'])){
             
            pg_query("update facturas set fac_retencion='t' WHERE fac_cod=$codigoFacturaSINRETENCION");
            header("Refresh:0; url=http://localhost/disco/web/facturacion/Retenciones.php");
         }
         
        