<!DOCTYPE html>
<?php
//Importante ejecutar las funciones require para tener usar la variables globales en esta pagina
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"]; //usamos esta variable para recordar la direccion a
//la que queriamos ir antes de requerir el login
//echo $_SESSION["TrackingURL"];
Confirm_Login();
?>
<html>

<head>
    <title>Revisar Posteos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="Images/iconoFilo.png">
    <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f622bf9c0b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/style.css">

</head>

<body>
    <!--NAVBAR START-->
    <div style="height:10px; background:#dfd653;"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Filoblog.com</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarcollapseCMS" aria-controls="navbarcollapseCMS" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarcollapseCMS">
                <ul class="navbar-nav mr-auto" style="padding-left: 7rem">
                    <li class="nav-item">
                        <a href="MyProfile.php" class="nav-link"><i class="fas fa-user" style="color: greenyellow"></i>Mi Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a href="Dashboard.php" class="nav-link">Resumen</a>
                    </li>
                    <li class="nav-item">
                        <a href="Posts.php" class="nav-link">Posteos</a>
                    </li>
                    <li class="nav-item">
                        <a href="Categories.php" class="nav-link">Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a href="Admins.php" class="nav-link">Gestionar Admins</a>
                    </li>
                    <li class="nav-item">
                        <a href="Comments.php" class="nav-link">Comentarios</a>
                    </li>
                    <li class="nav-item">
                        <a href="Blog.php?page=1" class="nav-link">Blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav" style=" padding-left: 9rem">
                    <li class="nav-item"><a href="Logout.php" class="nav-link"><i class="fas fa-user-times" style="color: red"></i>Cerrar Sesion</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height:10px; background:#dfd653;"></div>
    <!--NAVBAR END-->
    <!--HEADER-->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1><i class="fas fa-blog" style="color:#dfd653;"></i>Posteos del Blog</h1>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="AddNewPost.php" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Agregar Post
                    </a>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="Categories.php" class="btn btn-info btn-block">
                        <i class="fas fa-folder-plus"></i> Agregar Categoria
                    </a>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="Admins.php" class="btn btn-warning btn-block">
                        <i class="fas fa-user-plus"></i> Agregar Admin
                    </a>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="Comments.php" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Aprobar Comentarios
                    </a>
                </div>
            </div>
        </div>
    </header>
    <!--END OF HEADER-->
    <!--Main area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <table class="table table-striped table-hover">
                    <thead class="thead bg-dark text-white">
                        <tr>
                            <th>#</th>
                            <th>Titulo</th>
                            <th>Categoria</th>
                            <th>Fecha</th>
                            <th>Autor</th>
                            <th>Imagen</th>
                            <th>Comentarios</th>
                            <th>Accion</th>
                            <th>Vista Previa</th>
                        </tr>
                    </thead>
                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM posts";
                    $stmt = $ConnectingDB->query($sql);
                    $Sr = 0; //serial number
                    while ($DataRows = $stmt->fetch()) {
                        $Id = $DataRows["id"];
                        $DateTime = $DataRows["datetime"];
                        $PostTitle = $DataRows["title"];
                        $Category = $DataRows["category"];
                        $Admin = $DataRows["author"];
                        $Image = $DataRows["image"];
                        $PostText = $DataRows["post"];
                        $Sr++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo $Sr; ?></td>
                                <td><?php
                                    if (strlen($PostTitle) > 20) {
                                        $PostTitle = substr($PostTitle, 0, 20) . '...';
                                    }
                                    echo $PostTitle; ?></td>
                                <td><?php
                                    if (strlen($Category) > 8) {
                                        $Category = substr($Category, 0, 8) . '...';
                                    }
                                    echo $Category; ?></td>
                                <td><?php
                                    if (strlen($DateTime) > 16) {
                                        $DateTime = substr($DateTime, 0, 16) . '...';
                                    }
                                    echo $DateTime; ?></td>
                                <td><?php
                                    if (strlen($Admin) > 10) {
                                        $Admin = substr($Admin, 0, 10) . '...';
                                    }
                                    echo $Admin; ?></td>
                                <td><img src="Uploads/<?php echo $Image; ?>" height="50px" </td>
                                <td><?php
                                    $TotalApproved = countRenderPosts($Id, 'ON');
                                    if ($TotalApproved > 0) {
                                    ?><span class="badge alert-success"><?php
                                                                        echo $TotalApproved;
                                                                        ?></span><?php  }   ?>

                                    <?php
                                    $TotalDisapproved = countRenderPosts($Id, 'OFF');
                                    if ($TotalDisapproved > 0) {
                                    ?><span class="badge alert-danger"><?php
                                                                        echo $TotalDisapproved;
                                                                        ?></span><?php  }   ?></td>
                                <td>
                                    <a href="EditPost.php?id=<?php echo $Id; ?>"><span class="btn btn-warning">Editar</span></a>
                                    <a href="DeletePost.php?id=<?php echo $Id; ?>"><span class="btn btn-danger">Borrar</span></a>
                                </td>
                                <!--                    El atributo target=_blank permite redirigir el link a una nueva pestanha 
                    en lugar de sobreescribir la pestanha existente-->
                                <td><a href="FullPost.php?id=<?php echo $Id; ?>" target="_blank"><span class="btn btn-primary">Vista Previa</span></a></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </section>
    <!--end of main area-->
    <!--footer-->
    <div style="height:10px; background:#dfd653;"></div>
    <footer class="bg-dark text-white" style="padding-top: 1rem">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="lead text-center">Portafolio Demo de Diego Portillo <span id="year"></span> &copy; Todos los derechos reservados</p>

                </div>
            </div>
        </div>
    </footer>
    <div style="height:10px; background:#dfd653;"></div>

    <script src="jquery.js"></script>
    <script>
        $('#year').text(new Date().getFullYear());
    </script>

</html>