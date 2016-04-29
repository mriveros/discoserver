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
    
    //Datos del Form Agregar
    if  (empty($_POST['txtNombreA'])){$nombreA='';}else{ $nombreA = $_POST['txtNombreA'];}
    if  (empty($_POST['txtPrecioA'])){$precioA='';}else{ $precioA= $_POST['txtPrecioA'];}
    if  (empty($_POST['txtDescripcionA'])){$descripcionA='';}else{ $descripcionA= $_POST['txtDescripcionA'];}
     if  (empty($_POST['txtImagenA'])){$imagenA='';}else{ $imagenA= $_POST['txtImagenA'];}
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtPrecioM'])){$precioM='';}else{ $precioM= $_POST['txtPrecioM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    if  (empty($_POST['txtImagenM'])){$imagenM='';}else{ $imagenM= $_POST['txtImagenM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['submit'])){
             
            if(func_existeDato($nombreA, 'productos', 'pro_nom')==true){
                echo '<script type="text/javascript">
		alert("El Producto ya existe. Ingrese otro Producto.");
                window.location="http://dev.appwebpy.com/disco/web/productos/ABMproducto.php";
		</script>';
                }else{              
                //se define el Query
                agregarProducto($nombreA,$precioA,$descripcionA);
                header("Refresh:0; url=http://dev.appwebpy.com/disco/web/productos/ABMproducto.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            modificarProducto($nombreM,$descripcionM,$precioM,$estadoM,$codigoModif);
            header("Refresh:0; url=http://dev.appwebpy.com/disco/web/productos/ABMproducto.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update productos set pro_activo='f' WHERE pro_cod=$codigoElim");
            header("Refresh:0; url=http://dev.appwebpy.com/disco/web/productos/ABMproducto.php");
            
	}
   function agregarProducto($nombre,$precio,$descripcion){ 

        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

        $uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'productos/'; 

        $uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMproducto.php'; 

        $uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMproducto.php'; 

        $fieldname = 'file';
        $errors = array(1 => 'php.ini tamaño de archivo excedido', 
                        2 => 'tamaño excedido en la imagen', 
                        3 => 'solo se subio la imagen parcialmente', 
                        4 => 'ningun archivo fue subido'); 

        isset($_POST['submit']) 
            or error('el formulario es necesario', $uploadForm); 

        ($_FILES[$fieldname]['error'] == 0) 
            or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 

        @is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
            or error('no es una subida http', $uploadForm); 

        @getimagesize($_FILES[$fieldname]['tmp_name']) 
            or error('solo esta permitido subir imagenes', $uploadForm); 

        $now = time();
        $nombreimagen='http://dev.appwebpy.com/disco/web/class/productos/'.$now.$_FILES[$fieldname]['name'];
        while(file_exists($uploadFilename = $uploadsDirectory.$now.$_FILES[$fieldname]['name'])) 
        { 
            $now++; 
        } 

        @move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
            or error('sin permisos en el directorio', $uploadForm); 
         
        $query = "INSERT INTO productos(pro_nom,pro_des,pro_precio,pro_activo,pro_imagen)"
                    . "VALUES ('$nombre','$descripcion',$precio,'t','$nombreimagen');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
        header("Refresh:0; url=http://dev.appwebpy.com/disco/web/productos/ABMproducto.php");
        
       // header('Location: ' . $uploadSuccess); 

}
function modificarProducto($nombre,$descripcion,$precio,$estado,$codigoModif){ 

        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

        $uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'productos/'; 

        $uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMproducto.php'; 

        $uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMproducto.php'; 

        $fieldname = 'txtImagenM';
        $errors = array(1 => 'php.ini tamaño de archivo excedido', 
                        2 => 'tamaño excedido en la imagen', 
                        3 => 'solo se subio la imagen parcialmente', 
                        4 => 'ningun archivo fue subido'); 

        isset($_POST['modificar']) 
            or error('el formulario es necesario', $uploadForm); 

        ($_FILES[$fieldname]['error'] == 0) 
            or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 

        @is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
            or error('no es una subida http', $uploadForm); 

        @getimagesize($_FILES[$fieldname]['tmp_name']) 
            or error('solo esta permitido subir imagenes', $uploadForm); 

        $now = time();
        $nombreimagen='http://dev.appwebpy.com/disco/web/class/productos/'.$now.$_FILES[$fieldname]['name'];
        while(file_exists($uploadFilename = $uploadsDirectory.$now.$_FILES[$fieldname]['name'])) 
        { 
            $now++; 
        } 

        @move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
            or error('sin permisos en el directorio', $uploadForm); 
         
        
         $query=("update productos set pro_nom='$nombre',"
                    . "pro_des= '$descripcion',"
                    . "pro_precio= '$precio',"
                    . "pro_imagen= '$nombreimagen',"
                    . "pro_activo='$estado'"
                    . "WHERE pro_cod=$codigoModif");
        //ejecucion del query
        $ejecucion = pg_query($query)or die('Error al realizar la carga');
        $query = '';
        header("Refresh:0; url=http://dev.appwebpy.com/disco/web/productos/ABMproducto.php");
        
       // header('Location: ' . $uploadSuccess); 

}
function error($error, $location, $seconds = 5) 
        { 
            header("Refresh: $seconds; URL=http://dev.appwebpy.com/disco/web/productos/ABMproducto.php"); 
            echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'.
            '"http://www.w3.org/TR/html4/strict.dtd">'.
            '<html lang="es">'.
            '    <head>'.
            '        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">'.
            '        <link rel="stylesheet" type="text/css" href="stylesheet.css">' .
            '    <title>Error al subir</title>'.
            '    </head>'.
            '    <body>'.
            '    <div id="Upload">'.
            '        <h1>Codigo de error.</h1>'.
            '        <p>Un error a ocurrido: '.
            '        <span class="red">' . $error . '...</span>'.
            '         el formulario esta recargandose.</p>' .
            '     </div>'.
            '</html>'; 
            exit; 
        }     