<?php include __DIR__ . '/../components/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Inscription</h1>
            <p class="auth-subtitle">Rejoignez la communauté Ligue 1 Pronostics</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/register">
            <div class="form-group">
                <label class="form-label">Nom complet<span class="required-star">*</span></label>
                <input type="text" name="name" class="form-input" placeholder="Votre nom" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email<span class="required-star">*</span></label>
                <input type="email" name="email" class="form-input" placeholder="votre@email.com" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mot de passe<span class="required-star">*</span></label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-full">S'inscrire</button>
        </form>

        <div class="auth-links">
            <a href="/login" class="auth-link">Déjà un compte ? Se connecter</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>