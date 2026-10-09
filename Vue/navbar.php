<nav id="navbar" aria-label="Navigation principale" data-base="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>" data-csrf="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=accueil"><img src="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/img/CookWithSoumi-logo.png" alt="Cook With Soumi — Accueil" class="nav-logo"></a>
    <ul class="nav-links" id="navigationLinks">
        <?php foreach (['accueil'=>'Accueil', 'recette'=>'Recettes', 'categories'=>'Catégories', 'favoris'=>'Favoris', 'about'=>'À propos'] as $route=>$label): ?>
        <li><a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=<?= $route ?>" <?= (($_GET['page'] ?? 'accueil') === $route) ? 'aria-current="page" class="active"' : '' ?>><?= $label ?></a></li>
        <?php endforeach; ?>
        <?php if (!empty($_SESSION['user_id'])): ?>
        <li><a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=profile"><?= htmlspecialchars($_SESSION['prenom'] ?? 'Profil', ENT_QUOTES, 'UTF-8') ?></a></li>
        <li><a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=logout">Déconnexion</a></li>
        <?php else: ?>
        <li><a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=login" class="nav-cta">Connexion</a></li>
        <?php endif; ?>
    </ul>
    <button type="button" id="navBurger" class="nav-burger" aria-label="Ouvrir le menu" aria-controls="navigationLinks" aria-expanded="false">☰</button>
</nav>
