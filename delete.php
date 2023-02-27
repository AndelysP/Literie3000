<?php
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');

// Si l'id correspond à l'élément recherché, alors on exécute cette requête
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = $db->prepare("DELETE FROM matelas 
                           WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
}

header("Location: index.php");
include("templates/header.php");
?>

<?php
include("templates/footer.php");
?>