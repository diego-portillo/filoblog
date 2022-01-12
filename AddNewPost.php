<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
Confirm_Login();
if (isset($_POST["Submit"])) {
    $PostTitle = $_POST["PostTitle"];
    $Category = $_POST["Category"];
    $Image = $_FILES["Image"]["name"]; //para imagenes u otros tipos de datos usamos Files en lugar de post
    //lo que se guarda en files no es el archivo si no el nombre y ruta para ser ubicado
    $Target = "Uploads/" . basename($_FILES["Image"]["name"]);
    $PostText = $_POST["PostDescription"];
    $Admin = $_SESSION["Username"];
    date_default_timezone_set("America/Asuncion");
    $CurrentTime = time(); //en segundos
    $DateTime = strftime("%B %m %d %H: %M %S", $CurrentTime);
    //echo $DateTime;
    if (empty($PostTitle)) {
        $_SESSION["ErrorMessage"] = "No puede tener un titulo vacio";
        Redirect_to("AddNewPost.php");
    } elseif (strlen($PostTitle) < 5) {
        $_SESSION["ErrorMessage"] = "El post debe tener mas de 5 caracteres";
        Redirect_to("AddNewPost.php");
    } elseif (strlen($PostText) > 9999) {
        $_SESSION["ErrorMessage"] = "La descripcion debe tener menos de 1000 caracteres";
        Redirect_to("AddNewPost.php");
    } else {
        //insercion de datos en sql
        global $ConnectingDB;
        $sql = "INSERT INTO posts(datetime,title,category,author,image,post)";
        $sql .= "VALUES(:dateTime,:postTitle,:categoryName, :adminName, :imageName, :postDescription)"; //primero pasamos los nombres falsos por seguridad
        $stmt = $ConnectingDB->prepare($sql); // es necesario para enviar los datos
        $stmt->bindValue(':dateTime', $DateTime);
        $stmt->bindValue(':postTitle', $PostTitle);
        $stmt->bindValue(':categoryName', $Category); // con bindValue vinculamos la variable creada en php con la ubicacion en la tabla sql
        $stmt->bindValue(':adminName', $Admin);
        $stmt->bindValue(':imageName', $Image);
        $stmt->bindValue(':postDescription', $PostText);
        $Execute = $stmt->execute();
        //php crea un nombre temporal al subir archivos, por eso tmp_name
        move_uploaded_file($_FILES["Image"]["tmp_name"], $Target); //primer parametro en nombre y segundo es la locacion
        if ($Execute) {
            $_SESSION["SuccessMessage"] = "Post con id " . $ConnectingDB->lastInsertId() . " agregado con exito!";
            Redirect_to("AddNewPost.php");
        } else {
            $_SESSION["ErrorMessage"] = "Algo salio mal... Intenta de nuevo";
            Redirect_to("Categories.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Nuevo Post</title>
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
                        <a href="Admins.php" class="nav-link">Gestionar Admin</a>
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
                    <h1><i class="fas fa-edit" style="color:#dfd653;"></i> Agregar Post</h1>
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
                <form action="AddNewPost.php" method="post" enctype="multipart/form-data">
                    <!--El enctype multiform se utiliza para especificar que
                ademas de texto agregamos otros tipos de datos como imagenespor ejemplo-->
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h1>Agregar Post</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title"><span class="FieldInfo">Titulo del Post: </span></label>
                                <input class="form-control mb-2" type="text" name="PostTitle" id="title" placeholder="Escriba un titulo..." value="">
                            </div>
                            <div class="form-group">
                                <label for="CategoryTitle"><span class="FieldInfo">Elegir Categoria: </span></label>
                                <select class="form-control" id="CategoryTitle" name="Category">
                                    <?php
                                    //fetch/ir a buscaar categorias de la db
                                    global $ConnectingDB;
                                    $sql = "SELECT id, title FROM category";
                                    $stmt = $ConnectingDB->query($sql); //stmt es abreviacion de statement
                                    while ($DataRows = $stmt->fetch()) {
                                        $Id = $DataRows["id"];
                                        $CategoryName = $DataRows['title'];

                                    ?>
                                        <option><?php echo $CategoryName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label for="imageSelect"><span class="FieldInfo">Seleccionar Imagen</span></label>
                                <div class="custom-file">
                                    <input class="custom-file-input" type="File" name="Image" id="imageSelect" value="">
                                    <label for="imageSelect" class="custom-file-label"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Post"><span class="FieldInfo">Contenido: </span></label>
                                <textarea class="form-control" id="Post" name="PostDescription" rows="8" cols="80"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i>Volver a Resumen</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button type="submit" name="Submit" class="btn btn-success btn-block"><i class="fas fa-check"></i>Publicar</button>
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