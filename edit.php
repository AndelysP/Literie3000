<?php
// Appel à la BDD
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

// Si le formulaire n'est pas vide, utilisation des strip_tags pour éviter la faille XSS et de trim pour supprimer les espaces en début et fin de chaine
if (!empty($_POST)) {

    if (!empty($_POST["picture_upload"])) {
        $picture_upload = trim(strip_tags($_POST["picture_upload"]));
    };

    if (!empty($_POST["picture_link"])) {
        $picture_link = trim(strip_tags($_POST["picture_link"]));
    };

    $brand = trim(strip_tags($_POST["brand"]));
    $name = trim(strip_tags($_POST["name"]));
    $selectSize = trim(strip_tags($_POST["selectSize"]));
    $price = trim(strip_tags($_POST["price"]));
    $newPrice = trim(strip_tags($_POST["newPrice"]));

    // Initialisation des messages d'erreurs
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

    // Condition d'upload

    if (isset($_FILES["picture_upload"]) && $_FILES["picture_upload"]["error"] == 0) {
        $fileTmPath = $_FILES["picture_upload"]["tmp_name"]; 
        $fileName = $_FILES["picture_upload"]["name"]; // Nom de l'image
        $fileType = $_FILES["picture_upload"]["type"]; // Type de l'image

        $fileNameArray = explode(".", $fileName);
        $fileExtension = end($fileNameArray);
        $newFileName = md5($fileName . time()) . "." . $fileExtension; // Hash unique
        $fileDestPath = "./assets/img/matelas/{$newFileName}";

        $allowedTypes = array("image/jpeg", "image/png", "image/webp");
        if (in_array($fileType, $allowedTypes)) {
            move_uploaded_file($fileTmPath, $fileDestPath);
        } else {
            $errors["picture_upload"] = "Le type de fichier est incorrect .jpg, .png ou .webp requis";
        }
    }

    if (!$errors) {

        if (isset($fileName)) {
            $query = $db->prepare("UPDATE matelas 
                                   SET picture = :picture_upload, brand = :brand, name = :name, size = :size, price = :price, newPrice = :newPrice 
                                   WHERE id = :id");
            $query->bindParam(":picture_upload", $newFileName, PDO::PARAM_STR);
        } else {
            $query = $db->prepare("UPDATE matelas 
                                   SET picture = :picture_link, brand = :brand, name = :name, size = :size, price = :price, newPrice = :newPrice 
                                   WHERE id = :id");
            $query->bindParam(":picture_link", $picture_link, PDO::PARAM_STR);
        }

        $query->bindParam(":brand", $brand, PDO::PARAM_STR);
        $query->bindParam(":name", $name, PDO::PARAM_STR);
        $query->bindParam(":size", $selectSize, PDO::PARAM_STR);
        $query->bindParam(":price", $price, PDO::PARAM_INT);
        $query->bindParam(":newPrice", $newPrice, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    // Si la requête s'est correctement exécutée et qu'il n'y a pas d'erreurs, cela renvoie vers la page de l'item
    if (!$errors) {
        header("Location: matelas.php?id=$id");
    }
}

include("templates/header.php");
?>


<main>
    <div class="form-data">
        <h1>Modification des données</h1>
        <?php
        if ($find) {
        ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="picture_upload">Image à uploader :</label>
                    <input type="file" name="picture_upload" id="picture_upload" value="<?= isset($picture_upload) ? $picture_upload : "" ?>">

                    <?php
                    if (isset($errors["picture_upload"])) {
                    ?>
                        <span class="info-error"><?= $errors["picture_upload"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <p>ou</p>

                <div class="form-group">
                    <label for="picture_link">Lien vers l'image :</label>
                    <input type="text" name="picture_link" id="picture_link" placeholder="http://exemple.com/image.jpg" value="<?= $matelas["picture"] ?>">
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