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
include("templates/header.php")
?>

<main>
    <div class="container">
        <?php
        if ($find) {
        ?>
            <div class="matelas-wrapper">
                <div class="matelas-left-wrapper">
                    <?php if (strpos($item["picture"], 'http') === 0) { ?>
                        <img src="<?= $item["picture"] ?>" alt="<?= $item["name"] ?>">
                    <?php } else { ?>
                        <img src="assets/img/matelas/<?= $item["picture"] ?>" alt="<?= $item["name"] ?>">
                    <?php } ?>
                </div>
                <div class="matelas-right-wrapper">
                    <h1><?= $matelas["name"] ?></h1>

                    <p>Marque : <?= $matelas["brand"] ?></p>
                    <p>Dimensions : <?= $matelas["size"] ?>cm</p>
                    <p> Prix d'origine : <span><?= $matelas["price"] ?>€</span></p>
                    <p> Prix actuel : <?= $matelas["newPrice"] ?>€ </p>

                    <div class="matelas-btn">
                        <a href="edit.php?id=<?= $matelas["id"] ?>"><button class="edit"><i class="bi bi-pencil-square">Modifier</i></button></a>
                        <a href="delete.php?id=<?= $matelas["id"] ?>"><button class="delete"><i class="bi bi-trash3">Supprimer</i></button></a>
                    </div>
                </div>
            </div>
        <?php } else {
        ?>
            <h1><?= $data["name"] ?></h1>
        <?php } ?>
    </div>
</main>

<?php
include("templates/footer.php");
?>