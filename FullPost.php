<!DOCTYPE html>
<?php
//Importante ejecutar las funciones require para tener usar la variables globales en esta pagina
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/sessions.php");
$SearchQueryParameter = $_GET["id"];
?>
<?php

if (isset($_POST["Submit"])) {
    $Name = $_POST["CommenterName"];
    $Email = $_POST["CommenterEmail"];
    $Comment = $_POST["CommenterThoughts"];
    date_default_timezone_set("America/Asuncion");
    $CurrentTime = time(); //en segundos
    $DateTime = strftime("%B %m %d %H: %M %S", $CurrentTime);

    if (empty($Name) || empty($Email) || empty($Comment)) {
        $_SESSION["ErrorMessage"] = "Deben llenarse todos los campos";
        Redirect_to("FullPost.php?id=$SearchQueryParameter");
    } elseif (strlen($Comment) > 500) {
        $_SESSION["ErrorMessage"] = "Los comentarios deben tener menos de 500 caracteres";
        Redirect_to("FullPost.php?id=$SearchQueryParameter");
    } else {
        //insercion de datos en sql
        global $ConnectingDB;
        $sql = "INSERT INTO comments(datetime,name,email,comment,approvedby, status, post_id)";
        $sql .= "VALUES(:dateTime, :name, :email, :comment, 'Pending', 'OFF', :postIdFromURL)"; //:nombrefalso, 'valor por default'
        $stmt = $ConnectingDB->prepare($sql); // es necesario para enviar los datos
        $stmt->bindValue(':dateTime', $DateTime);
        $stmt->bindValue(':name', $Name);
        $stmt->bindValue(':email', $Email);
        $stmt->bindValue(':comment', $Comment);
        $stmt->bindValue(':postIdFromURL', $SearchQueryParameter);
        $Execute = $stmt->execute();
        //IMPORTANTE: BORRAR UN POST ASOCIADO A UN COMENTARIO, BORRA EL COMENTARIO TAMBIEN (POR LA RELACION pRIMARY-FOREIGN KEY
        ////SIN EMBARGO, BORRAR UN COMENTARIO NO BORRA EL POST
        //buena erramienta para debuggear es usar el var_dump($Execute)
        if ($Execute) {
            $_SESSION["SuccessMessage"] = "Comentario enviado!";
            Redirect_to("FullPost.php?id=$SearchQueryParameter");
        } else {
            $_SESSION["ErrorMessage"] = "Algo salió mal... Intenta de nuevo!";
            Redirect_to("FullPost.php?id=$SearchQueryParameter");
        }
    }
}
?>
<html>

<head>
    <title>Filoblog Post</title>
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
                <h1>Filoblog.com</h1>
                <h1 class="lead">Reflexiones filosoficas de ayer y hoy</h1>
                <?php echo ErrorMessage();
                echo SuccessMessage(); ?>
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
                } else {
                    $PostIdFromURL = $_GET["id"];
                    if (!isset($PostIdFromURL)) {
                        $_SESSION["ErrorMessage"] = "Error en la consulta del cliente";
                        Redirect_to("Blog.php?page=1");
                    }
                    $sql = "SELECT * FROM posts WHERE id='$PostIdFromURL'";
                    $stmt = $ConnectingDB->query($sql);
                    $Result = $stmt->rowcount();
                    if ($Result != 1) {
                        $_SESSION["ErrorMessage"] = "Error en la consulta del cliente";
                        Redirect_to("Blog.php?page=1");
                    }
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
                            <span style="float:right;" class="badge badge-dark text-light bg-dark">Comments <?php echo countRenderPosts($PostId, 'ON'); ?></span>
                            <hr>
                            <p class="card-text">
                                <?php
                                echo nl2br($PostDescription); //aplicamos esta funcion para poder insertar codigo html funcional en el contenido de nuestro post            
                                ?></p>
                        </div>
                    </div>
                    <br>

                <?php } ?>
                <!--Comments Start-->
                <!--fetch comments-->
                <span class="FieldInfo">Comentarios</span>
                <br>
                <?php global $ConnectingDB;
                $sql = "SELECT * FROM comments WHERE post_id='$SearchQueryParameter' AND status='ON'";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows = $stmt->fetch()) {
                    $CommentDate = $DataRows['datetime'];
                    $CommenterName = $DataRows['name'];
                    $CommentContent = $DataRows['comment'];
                ?>
                    <div>
                        <div class="media CommentBlock">
                            <img class="d-block img-fluid align-self-start" src="Images/comment.png">
                            <div class="media-body ml-2">
                                <h6 class="lead"><?php echo $CommenterName; ?></h6>
                                <p class="small"><?php echo $CommentDate; ?></p>
                                <p><?php echo $CommentContent; ?></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php } ?>
                <!--end of comments fetch-->
                <div class="">
                    <form class="" action="FullPost.php?id=<?php echo $SearchQueryParameter; ?>" method="post">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="FieldInfo">Comparte lo que piensas de este Post</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input class="form-control" type="text" name="CommenterName" placeholder="Nombre">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input class="form-control" type="email" name="CommenterEmail" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea name="CommenterThoughts" class="form-control" rows="8" cols="80"></textarea>
                                </div>
                                <div class="">
                                    <button type="submit" name="Submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--Comments End-->
            </div>
            <?php require_once('footer.php'); ?>