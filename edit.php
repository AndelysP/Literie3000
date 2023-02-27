<?php
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');

$find = false;
$data = array("name" => "Matelas introuvable dans la base de données");

// Si l'id correspond à l'élément recherché, alors on exécute cette requête
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = $db->prepare("SELECT * FROM matelas 
                           WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $item = $query->fetch();

    // si on trouve le bon matelas
    if ($item) {
        $find = true;
        $matelas = $item;
    }
}


if (!empty($_POST)) {
    $picture = trim(strip_tags($_POST["picture"]));
    $brand = trim(strip_tags($_POST["brand"]));
    $name = trim(strip_tags($_POST["name"]));
    $selectSize = trim(strip_tags($_POST["selectSize"]));
    $price = trim(strip_tags($_POST["price"]));
    $newPrice = trim(strip_tags($_POST["newPrice"]));

    $errors = [];

    if (empty($name)) {
        $errors["name"] = "Le nom du matelas est obligatoire";
    }
    if (empty($brand)) {
        $errors["brand"] = "La marque du matelas est obligatoire";
    }
    if (empty($selectSize)) {
        $errors["selectSize"] = "La taille du matelas est obligatoire";
    }
    if ($price < 0) {
        $errors["price"] = "Le prix ne peut pas être inférieur à 0";
    }

    if ($newPrice < 0) {
        $errors["newPrice"] = "Le prix ne peut pas être inférieur à 0";
    }

    // if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] === UPLOAD_ERR_OK) {
    //     $fileTmPath = $_FILES["picture"]["tmp_name"];
    //     $fileName = $_FILES["picture"]["name"];
    //     $fileType = $_FILES["picture"]["type"];

    //     $fileNameArray = explode(".", $fileName);
    //     $fileExtension = end($fileNameArray);
    //     $newFileName = md5($fileName . time()) . "." . $fileExtension;

    //     $allowedTypes = array("image/jpeg", "image/png", "image/webp");
    //     if (in_array($fileType, $allowedTypes)) {
    //         move_uploaded_file($fileTmPath, $fileDestPath);
    //     } else {
    //         $errors["picture"] = "Le type de fichier est incorrect .jpg, .png ou .webp requis";
    //     }
    // }

    $query = $db->prepare("UPDATE matelas SET picture = :picture, brand = :brand, name = :name, size = :size, price = :price, newPrice = :newPrice WHERE id = :id");
    $query->bindParam(':picture', $picture, PDO::PARAM_STR);
    $query->bindParam(':brand', $brand, PDO::PARAM_STR);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':size', $selectSize, PDO::PARAM_STR);
    $query->bindParam(':price', $price, PDO::PARAM_INT);
    $query->bindParam(':newPrice', $newPrice, PDO::PARAM_INT);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    // Si la requête s'est correctement exécutée et qu'il n'y a pas d'erreurs, cela renvoie vers la page de l'item
    if (!$errors) {
        header("Location: matelas.php?id=$id");        
    }
}

include("templates/header.php");
?>


<main>
    <div class="form-data">
        <?php
        if ($find) {
        ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="picture">Image du matelas :</label>
                    <input type="text" id="picture" name="picture" value="<?= $matelas["picture"] ?>">

                    <?php
                    if (isset($errors["picture"])) {
                    ?>
                        <span class="info-error"><?= $errors["picture"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <div class="form-group">
                    <label for="brand"></label>
                    <input type="text" name="brand" id="brand" placeholder="Marque du matelas" value="<?= $matelas["brand"] ?>">

                    <?php
                    if (isset($errors["brand"])) {
                    ?>
                        <span class="info-error"><?= $errors["brand"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <div class="form-group">
                    <label for="name"></label>
                    <input type="text" name="name" id="name" placeholder="Nom du matelas" value="<?= $matelas["name"] ?>">

                    <?php
                    if (isset($errors["name"])) {
                    ?>
                        <span class="info-error"><?= $errors["name"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <div class="form-group">
                    <label for="selectSize"></label>
                    <select name="selectSize" id="selectSize">
                        <option value="" selected>Choisissez une taille</option>
                        <option value="80x200">80x200</option>
                        <option value="90x190">90x190</option>
                        <option value="100x200">100x200</option>
                        <option value="120x200">120x200</option>
                        <option value="140x190">140x190</option>
                        <option value="160x200">160x200</option>
                        <option value="180x200">180x200</option>
                        <option value="200x200">200x200</option>
                    </select>

                    <?php
                    if (isset($errors["selectSize"])) {
                    ?>
                        <span class="info-error"><?= $errors["selectSize"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <div class="form-group">
                    <label for="price"></label>
                    <input type="number" id="price" name="price" placeholder="Prix du matelas" value="<?= $matelas["price"] ?>" min="0">

                    <?php
                    if (isset($errors["price"])) {
                    ?>
                        <span class="info-error"><?= $errors["price"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <div class="form-group">
                    <label for="newPrice"></label>
                    <input type="number" id="newPrice" name="newPrice" placeholder="Prix soldé" value="<?= $matelas["newPrice"] ?>" min="0">

                    <?php
                    if (isset($errors["newPrice"])) {
                    ?>
                        <span class="info-error"><?= $errors["newPrice"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <input type="submit" value="Modifier le matelas" class="btn-edit">
            </form>
        <?php } else {
        ?>
            <h1><?= $data["name"] ?></h1>
        <?php } ?>
    </div>
</main>

<?php
include("templates/footer.php");
?>