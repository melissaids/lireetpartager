<?php
session_start();
require_once 'config/db.php';

$search = $_GET['search'] ?? '';
$category_id = $_GET['category'] ?? '';

$sql = "SELECT l.*, COUNT(a.id) as nb_avis 
        FROM livres l 
        LEFT JOIN avis a ON l.id = a.id_livre 
        WHERE 1=1";

$params = [];

if (!empty($search)) {
    $sql .= " AND (l.titre LIKE ? OR l.auteur LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category_id)) {
    $sql .= " AND l.id_categorie = ?";
    $params[] = $category_id;
}

$sql .= " GROUP BY l.id ORDER BY l.titre ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tous_les_livres = $stmt->fetchAll();

$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY nom ASC");
$categories = $cat_stmt->fetchAll();

$page_title = "Catalogue complet - Lire et Partager";
include 'models/header.php';
?>

<style>
    .filters-section {
        background: #f4f7fe;
        padding: 30px;
        border-radius: 20px;
        margin: 20px auto 40px;
        max-width: 1000px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .search-form {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }
    .search-input, .category-select {
        border: 2px solid #e0e7ff;
        padding: 12px 20px;
        border-radius: 12px;
        outline: none;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .search-input:focus, .category-select:focus {
        border-color: #007bff;
        background: #fff;
    }
    .btn-search {
        background: #007bff;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-search:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }
    .btn-reset {
        color: #ff4757;
        text-decoration: none;
        font-weight: bold;
        padding: 10px;
    }
    .results-count {
        margin-top: 15px;
        font-size: 0.9rem;
        color: #667085;
        font-weight: 500;
    }
    .card-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0;
        font-size: 0.85rem;
    }
    .badge-avis {
        background: #eef4ff;
        color: #3538cd;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
</style>

<main class="content-wrapper">
    <h1 class="title-section">Explorez notre collection</h1>

    <section class="filters-section">
        <form action="catalogue.php" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" 
                   placeholder="Titre ou auteur..." 
                   value="<?= htmlspecialchars($search) ?>">

            <select name="category" class="category-select" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (string)$category_id === (string)$cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-search">Filtrer</button>

            <?php if (!empty($search) || !empty($category_id)): ?>
                <a href="catalogue.php" class="btn-reset" title="Réinitialiser">✕</a>
            <?php endif; ?>
        </form>
        
        <p class="results-count">
            🔍 <?= count($tous_les_livres) ?> livre(s) trouvé(s)
        </p>
    </section>

    <section class="catalog-section">
        <div class="cards-container">
            <?php if (count($tous_les_livres) > 0): ?>
                <?php foreach ($tous_les_livres as $livre): ?>
                    <div class="card">
                        <div class="card-image">
                            <?php if (!empty($livre['couverture'])): ?>
                                <img src="assets/photo/<?= htmlspecialchars($livre['couverture']) ?>" alt="Couverture">
                            <?php else: ?>
                                <img src="assets/photo/lplogobleu.png" alt="Pas de couverture">
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <div>
                                <h3><?= htmlspecialchars($livre['titre'] ?? '') ?></h3>
                                <p class="author">Par <?= htmlspecialchars($livre['auteur'] ?? 'Auteur inconnu') ?></p>
                                
                                <div class="card-meta">
                                    <span class="badge-avis">
                                        💬 <?= $livre['nb_avis'] ?> avis
                                    </span>
                                </div>

                                <p class="description">
                                    <?= htmlspecialchars(substr($livre['description'] ?? '', 0, 90)) ?>...
                                </p>
                            </div>
                            <div class="card-footer-item">
                                <span class="price"><?= number_format($livre['prix'], 2, ',', ' ') ?> €</span>
                                <a href="livre.php?id=<?= $livre['id'] ?>" class="btn-blue-icon">Détails</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; width: 100%; padding: 50px;">
                    <p style="font-size: 1.2rem; color: #666;">Aucun livre ne correspond à votre recherche. 😕</p>
                    <a href="catalogue.php" style="color: #007bff;">Voir tout le catalogue</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'models/footer.php'; ?>