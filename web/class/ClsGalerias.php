<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2016
 * Sistema Servidor Disco
 */
session_start();
    $codusuario=  $_SESSION["codigo_usuario"];   
    include '../funciones.php';
    conexionlocal();
    
    //Datos del Form Agregar
    if  (empty($_POST['txtEventoA'])){$eventoA='';}else{ $eventoA = $_POST['txtEventoA'];}
    if  (empty($_POST['file'])){$imagenA='';}else{ $imagenA= $_POST['file'];}
    if  (empty($_POST['txtDescripcionA'])){$descripcionA='';}else{ $descripcionA= $_POST['txtDescripcionA'];}
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtEventoM'])){$eventoM='';}else{ $eventoM = $_POST['txtEventoM'];}
    if  (empty($_POST['txtImagenM'])){$imagenM='';}else{ $imagenM= $_POST['txtImagenM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    

        //Si es agregar
        if(isset($_POST['submit'])){
            if(func_existeDato($imagenA, 'galeria', 'img_picture')==true){
                
                echo '<script type="text/javascript">
		alert("La imagen ya existe. Ingrese otra imagen");
                window.location="http://localhost/disco/web/galerias/ABMgaleria.php";
		</script>';
                }else{
                    
                    subirImagen($eventoA,$descripcionA); 

                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            modificarImagen($eventoM,$descripcionM,$estadoM,$codigoModif);
            $query = '';
            header("Refresh:0; url=http://localhost/disco/web/galerias/ABMgaleria.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update galeria set img_activo='f' WHERE img_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/disco/web/galerias/ABMgaleria.php");
	}
        
        
        
        //Clase para alzar imagenes
function subirImagen($evento,$descripcion){ 

        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

        $uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'galerias/'; 

        $uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMgaleria.php'; 

        $uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMgaleria.php'; 

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
        $nombreimagen='http://192.168.43.163/disco/web/class/galerias/'.$now.$_FILES[$fieldname]['name'];
        while(file_exists($uploadFilename = $uploadsDirectory.$now.$_FILES[$fieldname]['name'])) 
        { 
            $now++; 
        } 

        @move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
            or error('sin permisos en el directorio', $uploadForm); 
         
        
        $query = "INSERT INTO galeria(img_picture,eve_cod,img_obs,img_activo)"
        . "VALUES ('$nombreimagen','$evento','$descripcion','t');";
        //ejecucion del query
        $ejecucion = pg_query($query)or die('Error al realizar la carga');
        $query = '';
        header("Refresh:0; url=http://localhost/disco/web/galerias/ABMgaleria.php");
        
       // header('Location: ' . $uploadSuccess); 

}
function modificarImagen($evento,$descripcion,$estado,$codigoModif){ 

        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

        $uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'galerias/'; 

        $uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMgaleria.php'; 

        $uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMgaleria.php'; 

        $fieldname = 'file';
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
        $nombreimagen='http://192.168.43.163/disco/web/class/galerias/'.$now.$_FILES[$fieldname]['name'];
        while(file_exists($uploadFilename = $uploadsDirectory.$now.$_FILES[$fieldname]['name'])) 
        { 
            $now++; 
        } 

        @move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
            or error('sin permisos en el directorio', $uploadForm); 
         
        
         $query =("update galeria set img_picture='$nombreimagen',"
                    . "eve_cod= '$evento',"
                    . "img_obs= '$descripcion',"
                    . "img_activo='$estado'"
                    . "WHERE img_cod=$codigoModif");
        //ejecucion del query
        $ejecucion = pg_query($query)or die('Error al realizar la carga');
        $query = '';
        header("Refresh:0; url=http://localhost/disco/web/galerias/ABMgaleria.php");
        
       // header('Location: ' . $uploadSuccess); 

}
function error($error, $location, $seconds = 5) 
        { 
            header("Refresh: $seconds; URL=http://localhost/disco/web/galerias/ABMgaleria.php"); 
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
    