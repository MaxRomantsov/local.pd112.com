<?php
global $pdo;
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/constants.php");
if($_SERVER["REQUEST_METHOD"]=="POST") {
    $name = $_POST['name'];
    // Generate a unique ID
    $image_name = "";
// Check if the form was submitted
    if (isset($_FILES['image'])) {
        $uploadDir = UPLOADING; // Specify the directory where you want to store uploaded files
        $image_name = uniqid() .".". pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $uploadedFile = $_SERVER['DOCUMENT_ROOT'] ."/". $uploadDir. "/" . $image_name;
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
            echo "<script> console.log('Upload image is good'); </script>";
        }
    }
    $description = $_POST['description'];
    //echo $name . " " . $image . " " . $description . "\n";
    include $_SERVER['DOCUMENT_ROOT'] . "/config/connection_database.php";
    $sql = "INSERT INTO categories (name, image, description) VALUES (?, ?, ?)";

    $dataToInsert = [
        $name,
        $image_name,
        $description
    ];
    $stmt = $pdo->prepare($sql);
    $stmt->execute($dataToInsert);
    header("Location: /");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Додати</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
<div class="container py-3">
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/_header.php";
    ?>

    <h1 class="text-center">Додати категорію</h1>

    <form class="col-md-6 offset-md-3" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Назва</label>
            <input type="text" class="form-control" name="name" id="name" >
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Фото</label>
            <input type="file" class="form-control" name="image" id="image" >
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Опис</label>
            <textarea class="form-control" placeholder="Вкажіть опис" name="description" id="description"></textarea>
        </div>

            <!--       <div class="row">-->
            <!--            <div class="col-md-3">-->
            <!--                <img src="https://t4.ftcdn.net/jpg/04/70/29/97/360_F_470299797_UD0eoVMMSUbHCcNJCdv2t8B2g1GVqYgs.jpg"-->
            <!--                     alt="Обране фото" width="150">-->
            <!--            </div>-->
            <!--            <div class="col-md-9">-->
            <!--                <div class="mb-3">-->
            <!--                    <label for="image" class="form-label">Оберіть фото</label>-->
            <!--                    <input class="form-control" type="file" id="image" name="image" accept="image/*">-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->

        <button type="submit" class="btn btn-primary">Додати</button>
    </form>

</div>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>