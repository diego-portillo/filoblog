<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/sessions.php");
Confirm_Login();
$SearchQueryParameter = $_GET["id"];
global $ConnectingDB;
$sql = "SELECT * FROM posts WHERE id='$SearchQueryParameter'";
$stmt = $ConnectingDB->query($sql);
while ($DataRows = $stmt->fetch()) {
    $TitleToBeDeleted = $DataRows['title'];
    $CategoryToBeDeleted = $DataRows['category'];
    $ImageToBeDeleted = $DataRows['image'];
    $PostToBeDeleted = $DataRows['post'];
}
//           echo $ImageToBeDeleted;
//para tener acceso a $ImagetobeDeleted es importante declarar primero en nuestro loop
if (isset($_POST["Submit"])) {
    global $ConnectingDB;
    $sql = "DELETE FROM posts WHERE id='$SearchQueryParameter'";
    $Execute = $ConnectingDB->query($sql);
    if ($Execute) {
        $Target_for_delete_image = "Uploads/$ImageToBeDeleted";
        unlink($Target_for_delete_image); //ESTA FUNCION UNLINK CUMPLE EL TRABAJO DE ELIMINAR LA IMG DE LA CARPETA UPLOADS
        $_SESSION["SuccessMessage"] = "Posteo borrado";
        Redirect_to("Posts.php");
    } else {
        $_SESSION["ErrorMessage"] = "Algo salió mal... Intenta de nuevo!";
        Redirect_to("Posts.php");
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Borrar Post</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="Images/iconoFilo.png">
    <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f622bf9c0b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/style.css" type="text/css">

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
                    <h1><i class="fas fa-edit" style="color:#dfd653;"></i>Borrar Post</h1>
                </div>
            </div>
        </div>
    </header>
    <!--END OF HEADER-->
    <!--Main area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="offset-lg-1 col-lg-10" style="min-height: 400px">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();

                ?>
                <!--importante ubicar el codigo php correspondiente en el form action para que tenga efecto-->
                <form action="DeletePost.php?id=<?php echo $SearchQueryParameter; ?>" method="post" enctype="multipart/form-data">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h1>Borrar Post</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title"><span class="FieldInfo">Titulo: </span></label>
                                <input disabled class="form-control mb-2" type="text" name="PostTitle" id="title" placeholder="Escriba un titulo..." value="<?php echo $TitleToBeDeleted; ?>">
                            </div><!-- disabled evita que se pueda modificar el tag por el usuario, deja enegrecido-->
                            <div class="form-group">
                                <span class="FieldInfo">Category: </span>
                                <?php echo $CategoryToBeDeleted; ?>
                                <br>

                            </div>
                            <div class="form-group">
                                <span class="FieldInfo">Imagen: </span>
                                <img class="mb-1" src="Uploads/<?php echo $ImageToBeDeleted; ?>" width="170px" height="70px">

                            </div>
                            <div class="form-group">
                                <label for="Post"><span class="FieldInfo">Post: </span></label>
                                <textarea disabled class="form-control" id="Post" name="PostDescription" rows="8" cols="80"><?php echo $PostToBeDeleted; ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i>Volver a Resumen</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button type="submit" name="Submit" class="btn btn-danger btn-block"><i class="fas fa-trash"></i>Borrar</button>
                                </div>
                            </div>
                        </div>
                </form>
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