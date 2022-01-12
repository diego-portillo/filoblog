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
    <title>Resumen</title>
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
                    <h1><i class="fas fa-cog" style="color:#dfd653;"></i>Resumen</h1>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="AddNewPost.php" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Agregar Nuevo Post
                    </a>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="Categories.php" class="btn btn-info btn-block">
                        <i class="fas fa-folder-plus"></i> Agregar Nueva Categoria
                    </a>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="Admins.php" class="btn btn-warning btn-block">
                        <i class="fas fa-user-plus"></i> Agregar Nuevo Admin
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
            <?php
            echo ErrorMessage();
            echo SuccessMessage();
            ?>
            <!--left side-->
            <div class="col-lg-2">
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body ">
                        <h1 class="lead">Posteos</h1>
                        <h4 class="display-5">
                            <i class="fab fa-readme"></i>
                            <?php countTableRows('posts')
                            ?>
                        </h4>
                    </div>
                </div>

                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body ">
                        <h1 class="lead">Categorias</h1>
                        <h4 class="display-5">
                            <i class="fas fa-folder"></i>
                            <?php countTableRows('category')
                            ?>
                        </h4>
                    </div>
                </div>

                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body ">
                        <h1 class="lead">Admins</h1>
                        <h4 class="display-5">
                            <i class="fas fa-users"></i>
                            <?php countTableRows('admins')
                            ?>
                        </h4>
                    </div>
                </div>

                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body ">
                        <h1 class="lead">Comentarios</h1>
                        <h4 class="display-5">
                            <i class="fas fa-comments"></i>
                            <?php countTableRows('comments')
                            ?>
                        </h4>
                    </div>
                </div>

            </div>
            <!--end of left side-->
            <!--right side-->
            <div class="col-lg-10">
                <h1>Ultimos Posteos</h1>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Titulo</th>
                            <th>Fecha</th>
                            <th>Autor</th>
                            <th>Comentarios</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <?php
                    $SrNo = 0;
                    global $ConnectingDB;
                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
                    $stmt = $ConnectingDB->query($sql);
                    while ($DataRows = $stmt->fetch()) {
                        $PostId = $DataRows["id"];
                        $DateTime = $DataRows["datetime"];
                        $Author = $DataRows["author"];
                        $Title = $DataRows["title"];
                        $SrNo++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo $SrNo; ?></td>
                                <td><?php echo $Title; ?></td>
                                <td><?php echo $DateTime; ?></td>
                                <td><?php echo $Author; ?></td>
                                <td><?php
                                    $TotalApproved = countRenderPosts($PostId, 'ON');
                                    if ($TotalApproved > 0) {
                                    ?><span class="badge alert-success"><?php
                                                                        echo $TotalApproved;
                                                                        ?></span><?php  }   ?>

                                    <?php
                                    $TotalDisapproved = countRenderPosts($PostId, 'OFF');
                                    if ($TotalDisapproved > 0) {
                                    ?><span class="badge alert-danger"><?php
                                                                        echo $TotalDisapproved;
                                                                        ?></span><?php  }   ?></td>
                                <td><a target='_blank' href="FullPost.php?id=<?php echo $PostId; ?>"><span class="btn btn-info">Vista Previa</span></a></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
            <!--end of right side-->
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