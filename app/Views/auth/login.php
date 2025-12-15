<?php include __DIR__ . '/../components/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <!-- Logo - Assuming the path is correct or will be fixed -->
            <img src="/public/images/logo_ballon.png" alt="Logo" class="auth-logo" onerror="this.style.display='none'">

            <h1 class="auth-title">Ligue 1 Pronostics</h1>
            <p class="auth-subtitle">Pariez entre amis sur vos matchs préférés</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login">
            <div class="form-group">
                <label class="form-label">Email<span class="required-star">*</span></label>
                <input type="email" name="email" class="form-input" placeholder="votre@email.com" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mot de passe<span class="required-star">*</span></label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-full">Se connecter</button>
        </form>

        <div class="auth-links">
            <a href="#" class="auth-link">Mot de passe oublié ?</a>
            <a href="/register" class="auth-link">Créer un compte</a>
        </div>

        <div class="auth-footer-text">
            💡 Démo: Utilisez n'importe quel email/mot de passe<br>
            (admin@example.com pour accès admin)
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>