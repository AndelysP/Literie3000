<?php
// Appel à la BDD
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');

// Requête sur la BDD
$query = $db->query("SELECT *  FROM matelas
                    ORDER BY id ASC");
$matelas = $query->fetchAll(PDO::FETCH_ASSOC);

// var_dump($matelas);

include("templates/header.php")
?>

<main>
    <div class="items">
        <?php foreach ($matelas as $item) {
        ?>
            <div class="item">
                <img src="<?= $item["picture"] ?>" alt="<?= $item["name"] ?>">
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
