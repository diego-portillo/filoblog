<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
Confirm_Login();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Administrar Comentarios</title>
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
                    <h1><i class="fas fa-text-height" style="color:#dfd653;"></i>Administrar Comentarios</h1>
                </div>
            </div>
        </div>
    </header>
    <!--END OF HEADER-->
    <!--Main Area-->
    <section class="container py-2 mb-4">
        <div class="row" style="min-height: 30px;">
            <div class="col-lg-12" style="min-height: 400px;">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <h2>Comentarios sin aprobar</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Comentario</th>
                            <th>Aprobar</th>
                            <th>Borrar</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>

                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM comments WHERE status='OFF' ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $CommentId = $DataRows["id"];
                        $DateTimeOfComment = $DataRows["datetime"];
                        $CommenterName = $DataRows["name"];
                        $CommentContent = $DataRows["comment"];
                        $CommentPostId = $DataRows["post_id"];
                        $SrNo++;
                        if (strlen($CommenterName) > 10) {
                            $CommenterName = substr($CommenterName, 0, 10) . '...';
                        }
                        if (strlen($DateTimeOfComment) > 17) {
                            $DateTimeOfComment = substr($DateTimeOfComment, 0, 17) . '...';
                        }
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($SrNo); ?></td>
                                <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                                <td><?php echo htmlentities($CommenterName); ?></td>
                                <td><?php echo htmlentities($CommentContent); ?></td>
                                <td><a href="ApproveComment.php?id=<?php echo $CommentId; ?>" class="btn btn-success">Aprobar</a> </td>
                                <td><a href="DeleteComment.php?id=<?php echo $CommentId; ?>" class="btn btn-danger">Borrar</a> </td>
                                <td><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId; ?>" target="_blank">Vista Previa</a></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
                <h2>Comentarios Existentes</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Comentario</th>
                            <th>Revertir</th>
                            <th>Borrar</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>

                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM comments WHERE status='ON' ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $CommentId = $DataRows["id"];
                        $DateTimeOfComment = $DataRows["datetime"];
                        $CommenterName = $DataRows["name"];
                        $CommentContent = $DataRows["comment"];
                        $CommentPostId = $DataRows["post_id"];
                        $SrNo++;
                        if (strlen($CommenterName) > 10) {
                            $CommenterName = substr($CommenterName, 0, 10) . '...';
                        }
                        if (strlen($DateTimeOfComment) > 17) {
                            $DateTimeOfComment = substr($DateTimeOfComment, 0, 17) . '...';
                        }
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($SrNo); ?></td>
                                <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                                <td><?php echo htmlentities($CommenterName); ?></td>
                                <td><?php echo htmlentities($CommentContent); ?></td>
                                <td><a href="DisApproveComment.php?id=<?php echo $CommentId; ?>" class="btn btn-warning">Desaprobar</a> </td>
                                <td><a href="DeleteComment.php?id=<?php echo $CommentId; ?>" class="btn btn-danger">Borrar</a> </td>
                                <td><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId; ?>" target="_blank">Vista Previa</a></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </section>
    <!--end of main area-->
    <!--footer-->
    <div style=" position: absolute; bottom: 0px; left:0;right:0;">
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
    </div>
    <script src="jquery.js"></script>
    <script>
        $('#year').text(new Date().getFullYear());
    </script>

</html>