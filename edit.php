<?php
global $pdo;
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/constants.php");
$name="";
$description="";
$image="";
if(isset($_GET['id'])) {
    $id=$_GET['id'];
    include $_SERVER['DOCUMENT_ROOT'] . "/config/connection_database.php";
    $sql = "$id";

    // Prepare the SQL query
    $stmt = $pdo->prepare("SELECT id, name, image, description FROM categories WHERE id = :id");

    // Bind the parameter
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the result as an associative array
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result) {
        $name = $result['name'];
        $image = $result['image'];
        $description = $result['description'];
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $id=$_GET['id'];
    // Generate a unique ID
    $image_name = $image;
// Check if the form was submitted
    if (isset($_FILES['image'])&&$_FILES["image"]["size"]!=0) {
        $uploadDir = UPLOADING; // Specify the directory where you want to store uploaded files
        $image_name = uniqid() .".". pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $uploadedFile = $_SERVER['DOCUMENT_ROOT'] ."/". $uploadDir. "/" . $image_name;
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
            echo "<script> console.log('Upload image is good'); </script>";
            unlink($_SERVER['DOCUMENT_ROOT'] ."/". $uploadDir. "/" .$image);
        }
    }
    $description = $_POST['description'];
    //echo $name . " " . $image . " " . $description . "\n";
    include $_SERVER['DOCUMENT_ROOT'] . "/config/connection_database.php";
    $sql = "UPDATE categories SET  name = ?, image = ?, description = ? WHERE id = ?";

    $dataToInsert = [
        $name,
        $image_name,
        $description,
        $id
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

    <h1 class="text-center">Змінить категорію</h1>

    <form class="col-md-6 offset-md-3" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Назва</label>
            <input type="text" class="form-control"
                   name="name" id="name"
                   value="<?php echo $name ?>"
            >
        </div>

        <div class="row">
            <div class="col-md-3">
                <img src="<?php echo "/".UPLOADING."/".$image ?>"
                     alt="Обране фото" width="100%">
            </div>
            <div class="col-md-9">
                <div class="mb-3">
                    <label for="image" class="form-label">Оберіть фото</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Опис</label>
            <textarea class="form-control" placeholder="Вкажіть опис" name="description" id="description"><?php echo $description ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Додати</button>
        <button type="submit" class="btn btn-primary">Оновити</button>
    </form>
</div>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
