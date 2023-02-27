<?php
// Appel à la BDD
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');

// Requête sur la BDD
$query = $db->query("SELECT * FROM matelas
                    ORDER BY id DESC");
$matelas = $query->fetchAll(PDO::FETCH_ASSOC);

// var_dump($matelas);

include("templates/header.php")
?>

<main>
    <div class="items">
        <h1>Nos matelas</h1>
        <?php foreach ($matelas as $item) {
        ?>
            <div class="item">

                <!-- On vérifie avec strpso() si la clé "picture" contient "http" en première position, ce qui signifie qu'il s'agit d'un lien, sinon on renvoie vers le chemin de l'image -->

                <?php if (strpos($item["picture"], 'http') === 0) { ?>
                    <img src="<?= $item["picture"] ?>" alt="<?= $item["name"] ?>">
                <?php } else { ?>
                    <img src="assets/img/matelas/<?= $item["picture"] ?>" alt="<?= $item["name"] ?>">
                <?php } ?>

                <!-- Affichage des détails de chaque matelas -->
                <div class="item-details">
                    <a href="matelas.php?id=<?= $item["id"] ?>"><?= $item["name"] ?></a>

                    <p><?= $item["brand"] ?></p>
                    <p><?= $item["size"] ?>cm</p>
                    <p><span><?= $item["price"] ?>€</span></p>
                    <p><?= $item["newPrice"] ?>€ </p>
                </div>
            </div>
        <?php }
        ?>
    </div>
</main>

<?php
include("templates/footer.php");
