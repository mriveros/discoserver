<?php
 include '../funciones.php';
 conexionlocal();
 if  (empty($_POST['codigo'])){$codigoCompra='';}else{$codigoCompra=$_POST['codigo'];}
 if  (empty($_POST['txtProductoA'])){$producto='';}else{$producto=$_POST['txtProductoA'];}
 if  (empty($_POST['txtCantidadA'])){$cantidad='';}else{$cantidad=$_POST['txtCantidadA'];}
 if  (empty($_POST['txtPrecioA'])){$precio='';}else{$precio=$_POST['txtPrecioA'];}
 if  (empty($_POST['txtCodigoE'])){$codigoDetalle='';}else{$codigoDetalle=$_POST['txtCodigoE'];}
 if  (empty($_POST['txtIVAM'])){$iva='f';}else{$iva=$_POST['txtIVAM'];}


//-------------------Obtenemos el codigo de Cabecera----------------------------
 $query = "Select max(ord_cod) from orden_compras;";
 $resultado=pg_query($query);
 $row=  pg_fetch_array($resultado);
 $codcabecera=$row[0];
//------------------------Agregar-----------------------------------------------
 if(isset($_POST['agregar'])){ 
$query = "INSERT INTO compras_detalles(ord_cod,pro_cod,comdet_cant,comdet_precio,comdet_subtotal,comdet_iva) VALUES ($codcabecera,$producto,$cantidad,$precio,$cantidad*$precio,'$iva');";
pg_query($query)or die('Error al realizar la carga');
calcularMonto($codcabecera);
header("Refresh:0; url=http://localhost/disco/web/orden_compras/IngDetalle.php");


}
//------------------------Borrar-----------------------------------------------
  if(isset($_POST['borrar'])){
        pg_query("delete from compras_detalles WHERE comdet_cod=$codigoDetalle");
        calcularMonto($codcabecera);
        header("Refresh:0; url=http://localhost/disco/web/orden_compras/IngDetalle.php");
  }
  
  function calcularMonto( $codcabecera){
    $query = "Select sum(comdet_subtotal) from compras_detalles where ord_cod=$codcabecera;";
    $resultado=pg_query($query);
    $row=  pg_fetch_array($resultado);
    $montoTotal=$row[0];
    $query = "update orden_compras set ord_monto=$montoTotal where ord_cod=$codcabecera;";
    pg_query($query)or die('Error al realizar la carga');
    //calcular IVA
    $query = "Select sum(comdet_subtotal) from compras_detalles where ord_cod=$codcabecera and comdet_iva='t';";
    $resultado=pg_query($query);
    $row=  pg_fetch_array($resultado);
    $montoTotalConIVA=$row[0];
    $query = "update orden_compras set ord_iva=(($montoTotalConIVA)/11) where ord_cod=$codcabecera";
    pg_query($query)or die('Error al realizar la carga');
  }
  
?>