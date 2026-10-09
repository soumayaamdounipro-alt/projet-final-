<?php $pageprojetcookwithsoumi = $pageprojetcookwithsoumi?? 'Mon Profil'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook With Soumi — <?= htmlspecialchars($pageprojetcookwithsoumi) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/css/profil.css">
</head>

<body>

    <main class="profile-page">
        <div class="profile-card">

            <div class="profile-avatar">👩‍🍳</div>

            <h1 class="profile-name">
                <?php
                $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                echo htmlspecialchars($fullName);
                ?>
            </h1>

            <p class="profile-email">
                <?= htmlspecialchars($user['email'] ?? '') ?>
            </p>

            <div class="profile-info">
                <div class="info-item">
                    <strong>ID du compte :</strong>
                    #<?= htmlspecialchars($user['id'] ?? '') ?>
                </div>
                <div class="info-item">
                    <strong>Statut :</strong> ✔ Actif
                </div>
            </div>

            <div class="profile-actions">
                <a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=accueil" class="btn-profile">🏠 Accueil</a>
                <a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=logout" class="btn-profile logout-btn">🚪 Se Déconnecter</a>
            </div>

        </div>
    </main>

</body>

</html>