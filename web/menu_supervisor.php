<?php 

if(!isset($_SESSION['codigo_usuario']))
    header("Location:http://104.236.113.194/disco/login/acceso.html");
    $catego=  $_SESSION["categoria_usuario"];
    if($catego==2){
        header("Location:http://104.236.113.194/disco/web/menu_usuario.php");
    }
    if($catego==3){
        header("Location:http://104.236.113.194/disco/web/menu_supervisor.php");
    }
    $conectate=pg_connect("host=192.168.0.99 port=5432 dbname=estaciones user=postgres password=postgres")or die ('Error al conectar a la base de datos');
    $consulta= pg_exec($conectate,"select sum(reg_cant)as cantidad,sum(reg_aprob) as aprobados,sum(reg_reprob)
    as reprobados,sum(reg_claus)as clausurados from registros where reg_fecha < now()");
    $cantidad=pg_result($consulta,0,'cantidad');
    $aprobados=pg_result($consulta,0,'aprobados');
    $reprobados=pg_result($consulta,0,'reprobados');
    $clausurados=pg_result($consulta,0,'clausurados');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <title>CONTROL ESTACIONES DE SERVICIOS</title>
</head>

<body>
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Sistema de Control- Estaciones de Servicios</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Precintos Aprobados</strong>
                                        <span class="pull-right text-muted"><?php echo $aprobados;?> Precintos</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $aprobados;?>" aria-valuemin="0" aria-valuemax="<?php echo $cantidad;?>" style="width: <?php echo $aprobados;?>%">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Precintos Reprobados</strong>
                                        <span class="pull-right text-muted"><?php echo $reprobados;?> Precintos</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $aprobados;?>" aria-valuemin="0" aria-valuemax="<?php echo $cantidad;?>" style="width: <?php echo $reprobados;?>%">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Precintos Clausurados</strong>
                                        <span class="pull-right text-muted"><?php echo $clausurados;?> Precintos</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $aprobados;?>" aria-valuemin="0" aria-valuemax="<?php echo $cantidad;?>" style="width: <?php echo $clausurados;?>%">
                                            <span class="sr-only">80% Complete (danger)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Cerrar</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <?php echo "USUARIO"//$_SESSION['usernom']." ".$_SESSION['userape']; ?> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Cambiar Contraseña</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Configuracion</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="http://104.236.113.194/disco/web/logout.php"><i class="fa fa-sign-out fa-fw"></i> Cerrar Sesion</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
          

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="#" value="Load new document" onclick="pageprinci()"><i class="fa fa-dashboard fa-fw"></i>Menu Principal</a>
                        </li>

                        <li>
                            <a href="#"><i class="fa  fa-file-text "></i>INFORMES<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="http://104.236.113.194/disco/web/informes/FrmRegistrosFecha.php">Resumen Registros por mes</a>
                                </li>
                                <li>
                                    <a href="http://104.236.113.194/disco/web/informes/frmClientesFecha.php">Resumen Registros por Cliente-Fecha</a>
                                </li>
                                 <li>
                                    <a href="http://104.236.113.194/disco/web/informes/frmDistribuidorFecha.php">Resumen Registros por Distribuidor</a>
                                </li>
                                <li>
                                    <a href="http://104.236.113.194/disco/web/informes/FrmDepartamentoFecha.php">Resumen por Departamentos-Fecha</a>
                                </li>
                                <li>
                                    <a href="http://104.236.113.194/disco/web/informes/FrmUsuariosFecha.php">Resumen por Usuarios-Fecha</a>
                                </li>
                                <li>
                                    <a href="http://104.236.113.194/disco/web/informes/frmRankingRegistrosFecha.php">Ranking Registros por mes</a>
                                </li>
                                <li>
                                    <a href="http://104.236.113.194/disco/web/informes/frmRankingClientesFecha.php">Ranking Registros por Clientes</a>
                                </li>
                                 <li>
                                    <a href="http://104.236.113.194/disco/web/informes/frmRankingDepartamentoFecha.php">Ranking Registros por Departamento</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
</body>
</html>
