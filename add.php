<?php
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

    // Mise en place des messages d'erreurs
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
        $fileName = $_FILES["picture_upload"]["name"];
        $fileType = $_FILES["picture_upload"]["type"];
        $fileNameArray = explode(".", $fileName);
        $fileExtension = end($fileNameArray);
        $newFileName = md5($fileName . time()) . "." . $fileExtension;
        $fileDestPath = "./assets/img/matelas/{$newFileName}";
        $allowedTypes = array("image/jpeg", "image/png", "image/webp");
        if (in_array($fileType, $allowedTypes)) {
            move_uploaded_file($fileTmPath, $fileDestPath);
        } else {
            $errors["picture_upload"] = "Le type de fichier est incorrect .jpg, .png ou .webp requis";
        }
    }
    if (!$errors) {

        $db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');

        if (isset($fileName)) {
            $query = $db->prepare("INSERT INTO matelas 
                                (picture, brand, name, size, price, newPrice) 
                                VALUES (:picture_upload, :brand, :name, :size, :price, :newPrice)");
            $query->bindParam(":picture_upload", $newFileName, PDO::PARAM_STR);
        } else {
            $query = $db->prepare("INSERT INTO matelas 
                                (picture, brand, name, size, price, newPrice) 
                                VALUES (:picture_link, :brand, :name, :size, :price, :newPrice)");
            $query->bindParam(":picture_link", $picture_link, PDO::PARAM_STR);
        }


        $query->bindParam(":brand", $brand, PDO::PARAM_STR);
        $query->bindParam(":name", $name, PDO::PARAM_STR);
        $query->bindParam(":size", $selectSize, PDO::PARAM_STR);
        $query->bindParam(":price", $price, PDO::PARAM_INT);
        $query->bindParam(":newPrice", $newPrice, PDO::PARAM_INT);

        $query->execute();
        header("Location: index.php");
    }
}

include("templates/header.php");
?>
<main>
    <h1>Ajouter un matelas</h1>
    <div class="form-data">
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
                <input type="text" name="picture_link" id="picture_link" placeholder="http://exemple.com/image.jpg">
            </div>
            <div class="form-group">
                <label for="brand"></label>
                <input type="text" name="brand" id="brand" placeholder="Marque du matelas" value="<?= isset($brand) ? $brand : "" ?>">
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
                <input type="text" name="name" id="name" placeholder="Nom du matelas" value="<?= isset($name) ? $name : "" ?>">
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
                <input type="number" id="price" name="price" placeholder="Prix du matelas" value="<?= isset($price) ? $price : "" ?>" min="0">
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
                <input type="number" id="newPrice" name="newPrice" placeholder="Prix soldé" value="<?= isset($newPrice) ? $newPrice : "" ?>" min="0">
                <?php
                if (isset($errors["newPrice"])) {
                ?>
                    <span class="info-error"><?= $errors["newPrice"] ?></span>
                <?php
                }
                ?>
            </div>
            <input type="submit" value="Ajouter le matelas" class="btn-edit">
        </form>
    </div>
</main>
<?php
include("templates/footer.php");
?>