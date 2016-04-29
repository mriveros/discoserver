<?php
 //Recibimos el parametro des de Android
	include './funciones.php';
    conexionlocal();
	$evento = $_REQUEST['evento'];
	$nombre = $_REQUEST['nombre'];
	$observacion = $_REQUEST['observacion'];
	$telefono = $_REQUEST['telefono'];
	$imagen = $_REQUEST['imagen'];

 $imagenUrl='http://dev.appwebpy.com/disco/web/class/reservas/'.$imagen.'.jpg';
 $codigoEvento=obtenerCodigo('eventos','eve_cod','eve_nom',$evento);
 
 $query = "INSERT INTO reservas(res_nom,res_obs,eve_cod,res_fecha,res_telefono,res_activo,res_confirm,res_imagen)"
        . "VALUES ('$nombre','$observacion','$codigoEvento',now(),'$telefono','t','f','$imagenUrl');";
 //ejecucion del query
 $ejecucion = pg_query($query)or die('Error al realizar la carga');
 
	echo ("SERVER: Datos Recibidos Exitosamente..!");
	
	
	//echo ("SERVER: ok, parametros recibidos -> ".$cedula."\n");
	//echo ("SERVER: ok, parametros recibidos -> ".$motivo."\n");
	//echo ("SERVER: ok, parametros recibidos -> ".$observacion."\n");
	//echo ("SERVER: ok, parametros recibidos -> ".$telefono."\n");
	
 
?>