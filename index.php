<?php
// Connexion à la base de données
$servername = 'ijtebowbascolm.mysql.db';
$dbname = 'ijtebowbascolm';
$username = 'ijtebowbascolm';
$password = 'BascolMelina2024';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // Détermine le numéro de page actuel (par défaut : 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50; // Nombre d'éléments par page
$offset = ($page - 1) * $limit;

// Requête avec LIMIT et OFFSET
$stmt = $pdo->prepare("
    SELECT Materiel.nom AS nom_materiel, Materiel.statut, Categorie.categorie AS nom_categorie 
    FROM Materiel
    JOIN Categorie ON Materiel.categorie_id = Categorie.id
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Comptage total des items pour la pagination
$totalItems = $pdo->query("SELECT COUNT(*) FROM Materiel")->fetchColumn();
$totalPages = ceil($totalItems / $limit);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventaire</title>
  <link rel="stylesheet" href="inventaire.css">
</head>
<body>
<header>
    <div class="logo">
      <a href="page2.html"><img src="img/iut.png" alt="Logo IUT"></a>
    </div>
    <nav>
      <ul class="menu">
        <li class="dropdown">
          <a href="#">Vidéo</a>
          <ul class="submenu">
            <li><a href="#">Caméras embarquées</a></li>
            <li><a href="#">Caméras</a></li>
            <li><a href="#">Moniteur Vidéo</a></li>
            <li><a href="#">Objectifs Vidéo</a></li>
            <li><a href="#">Appareil Photo</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Son</a>
          <ul class="submenu">
            <li><a href="#">Micros</a></li>
            <li><a href="#">Enregistreurs son</a></li>
            <li><a href="#">Perches</a></li>
            <li><a href="#">Casques</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Éclairages</a>
          <ul class="submenu">
            <li><a href="#">Pack panneaux LED</a></li>
            <li><a href="#">Pack mandarines</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Connectiques</a>
        </li>
        <li class="dropdown">
          <a href="#">Cadrages</a>
          <ul class="submenu">
            <li><a href="#">Trépieds</a></li>
            <li><a href="#">Stabilisateurs</a></li>
            <li><a href="#">Slider</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Ordinateurs</a>
        </li>
        <li class="dropdown">
          <a href="#">Studios</a>
        </li>
      </ul>

    </nav>
    <div class="user-menu">
      <a href="">Profil</a>
      <img src="img/profil.png" alt="Votre photo de profil">
      <a href="">Panier</a>
      <img src="img/panier.png" alt="Votre Panier">
    </div>
  </header>
  <section class="baniere-section">
      <img src="img/baniere.jpg" alt="Réservation matériel">

    </section>
  <main>
    <h2>Inventaire</h2>
    <div class="inventory">

      <div class="row header">
        <div class="column">Nom</div>
        <div class="column">Statut</div>
        <div class="column">Catégorie</div>
      </div>
      <?php if (!empty($materiels)): ?>
        <?php foreach ($materiels as $materiel): ?>
          <div class="row">
            <div class="column"><?= htmlspecialchars($materiel['nom_materiel']) ?></div>
            <div class="column"><?= htmlspecialchars($materiel['statut']) ?></div>
            <div class="column"><?= htmlspecialchars($materiel['nom_categorie']) ?></div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="row">
          <div class="column" colspan="3">Aucun matériel disponible.</div>
        </div>
      <?php endif; ?>
    </div>
          <!-- HTML Pagination -->
          <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">Précédent</a>
    <?php endif; ?>
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>">Suivant</a>
    <?php endif; ?>
    </div>
  </main>
</body>
</html>
