<!DOCTYPE html>
<?php
//Importante ejecutar las funciones require para tener usar la variables globales en esta pagina
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/sessions.php");
?>
<html>

<head>
    <title>Filoblog</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="Images/iconoFilo.png" type="image/ico">
    <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f622bf9c0b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/style.css">
    <style media="screen">
        .heading {
            font-family: Bitter, Georgia, "Times New Roman", Times, serif;
            font-weight: bold;
            color: #005E90;
        }

        .heading:hover {
            color: #0090DB;
        }
    </style>
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
                <ul class="navbar-nav mr-auto text-center" style="padding-left: 16rem; padding-right: 4rem;">

                    <li class="nav-item">
                        <a href="Blog.php?page=1" class="nav-link">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.html" class="nav-link">Sobre Nosotros</a>
                    </li>

                    <li class="nav-item">
                        <a href="contacto.html" class="nav-link">Contacto</a>

                </ul>
                <ul class="navbar-nav" style="padding-left: 8rem; padding-right: 1rem;">
                    <form class="navbar-nav mr-auto" action="Blog.php" method="GET">

                        <input class="form-control nav-item" type="text" name="Search" placeholder="...">

                        <button style="background:#dfd653;" class="btn btn-primary nav-item" name="SearchButton">Buscar</button>
                    </form>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height:10px; background:#dfd653;"></div>
    <!--NAVBAR END-->
    <!--HEADER-->
    <div class="container">
        <div class="row mt-4">
            <!--main area start-->
            <div class="col-sm-8 " style="min-height: 40px;">
                <h1>Filoblog</h1>
                <h1 class="lead">Reflexiones filosoficas de ayer y hoy</h1>
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <?php
                global $ConnectingDB;
                //RESOLUCION DEL BUG: EL FORMULARIO HTML ES ENVIADO POR POST EN LUGAR DE GET
                //RAZON POR LA CUAL NO SE ENCONTRABAN LOS DATOS EN EL OBJETO $_GET
                if (isset($_GET["SearchButton"])) {
                    $Search = $_GET["Search"];
                    $sql = "SELECT * FROM posts WHERE datetime LIKE :search OR title LIKE :search OR category LIKE :search OR post LIKE :search";
                    $stmt = $ConnectingDB->prepare($sql);
                    $stmt->bindValue(':search', '%' . $Search . '%');
                    $stmt->execute();
                } elseif (isset($_GET["page"])) { //cuando esta activada la paginacion
                    $Page = $_GET["page"];
                    if ($Page == 0 || $Page < 1) {
                        $ShowPostFrom = 0;
                    } else {
                        $ShowPostFrom = ($Page * 4) - 4;
                    }
                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,4";
                    $stmt = $ConnectingDB->query($sql);
                } elseif (isset($_GET["category"])) { //when show by category
                    $Category = $_GET["category"];
                    $sql = "SELECT * FROM posts WHERE category='$Category' ORDER BY id desc";
                    $stmt = $ConnectingDB->query($sql);
                } else { //default sin paginacion
                    //            echo "<h1> Error </h1>";
                    $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT 0,3";
                    $stmt = $ConnectingDB->query($sql);
                }

                while ($DataRows = $stmt->fetch()) {
                    $PostId = $DataRows["id"];
                    $DateTime = $DataRows["datetime"];
                    $PostTitle = $DataRows["title"];
                    $Category = $DataRows["category"];
                    $Admin = $DataRows["author"];
                    $Image = $DataRows["image"];
                    $PostDescription = $DataRows["post"];

                ?>
                    <div class="card">
                        <img src="Uploads/<?php echo htmlentities($Image); ?>" style="max-height: 350px" class="img-fluid card-img-top" />
                        <div class="card-body">
                            <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
                            <small><a href="Blog.php?category=<?php echo $Category; ?>"><b><?php echo $Category; ?></a>: </b>Written By <a href="Profile.php?username=<?php echo htmlentities(str_replace(' ', '', $Admin)); ?>"><?php echo htmlentities($Admin); ?></a> On <?php echo htmlentities($DateTime); ?> </small>
                            <span style="float:right;" class="badge badge-dark text-light bg-dark">Comentarios <?php echo countRenderPosts($PostId, 'ON'); ?></span>
                            <hr>
                            <p class="card-text">
                                <?php if (strlen($PostDescription) > 150) {
                                    $PostDescription = substr($PostDescription, 0, 150) . "...";
                                }
                                echo nl2br($PostDescription);

                                ?></p>
                            <a href="FullPost.php?id=<?php echo $PostId; ?>" style="float:right;">
                                <span class="btn btn-info">Leer Mas >> </span>
                            </a>
                        </div>
                    </div>
                    <br>
                <?php } ?>
                <!--Pagination-->
                <nav>
                    <ul class="pagination pagination-lg">
                        <!--creating backrward button-->
                        <?php
                        if (isset($Page)) {
                            if ($Page > 1) {
                        ?>
                                <li class="page-item">
                                    <a href="Blog.php?page=<?php echo $Page - 1; ?>" class="page-link">&laquo;</a>
                                </li>
                        <?php }
                        }
                        ?>
                        <?php
                        global $ConnectingDB; //conexion con db
                        $sql = "SELECT COUNT(*) FROM posts"; //creacio  del sql code
                        $stmt = $ConnectingDB->query($sql); //consultar la db con el codigo sql
                        $RowPagination = $stmt->fetch(); //convierte el pdo recibido en un array
                        $TotalPosts = array_shift($RowPagination); //quita el primer valor del array (0) para contar la cantidad de posts
                        ////                    echo $TotalPosts."<br>";
                        $PostPagination = $TotalPosts / 5; //dividimos en 5 para mostrar solo 5 por pagina
                        $PostPagination = ceil($PostPagination); //para redondear
                        ////                    echo $PostPagination;
                        for ($i = 1; $i <= $PostPagination; $i++) {
                            if (isset($Page)) { //condicion para mostrar paginas
                                if ($i == $Page) {
                        ?>
                                    <li class="page-item active">
                                        <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                    </li>
                                <?php
                                } else {
                                ?>
                                    <li class="page-item">
                                        <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                    </li>
                        <?php
                                }
                            }
                        }
                        ?>
                        <!--creating forward button-->
                        <?php
                        if (isset($Page) && !empty($Page)) {
                            if ($Page + 1 <= $PostPagination) {
                        ?>
                                <li class="page-item">
                                    <a href="Blog.php?page=<?php echo $Page + 1; ?>" class="page-link">&raquo;</a>
                                </li>
                        <?php }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
            <!--main area end-->
            <?php require_once('footer.php'); //para no tener que escribir el side area y footer en cada pagina
            ?>