<header class="navbar">
    <p class="navbar-brand">
        <a href="<?= $rootUrl; ?>">My first app PHP</a>
    </p>
    <ul class="navbar-links">
        <li class="navbar-link">Accueil</li>
        <li class="navbar-link">Articles</li>
    </ul>
    <ul class="navbar-links navbar-btn">
        <li class="navbar-links">
            <?php if(!empty($_SESSION['LOGGED_USER'])): ?>
                <a href="/logout.php" class="btn btn-danger">Se déconnecter</a>
            <?php else: ?>
                <a href="/login.php" class="btn btn-secondary">Se connecter</a>
            <?php endif;?>
        </li>
    </ul>
</header>