<?php include __DIR__ . '/../components/header.php'; ?>

<div class="join-group-container">
    <h1 style="color: #003366; margin-bottom: 0.5rem;">Rejoindre un groupe</h1>
    <p style="color: #666; margin-top: 0; margin-bottom: 2rem;">Entrez le code d'invitation pour rejoindre un groupe</p>

    <div class="form-card">
        <?php if (!empty($error)): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success-box"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form action="/groups/join" method="POST">
            <div class="form-group">
                <label for="code">Code d'invitation<span style="color: red;">*</span></label>
                <input type="text" id="code" name="code" placeholder="GROUPE123456" required
                       style="text-transform: uppercase;" maxlength="12">
            </div>

            <div class="info-box">
                <div class="info-title">Comment obtenir un code ?</div>
                <ul style="margin: 0; padding-left: 1.2rem; margin-top: 0.5rem;">
                    <li>Demandez le code au créateur du groupe</li>
                    <li>Le code ressemble à : GROUPE123456</li>
                </ul>
            </div>

            <div class="form-actions">
                <a href="/groups" class="btn-cancel">Annuler</a>
                <button type="submit" class="btn-submit">Rejoindre</button>
            </div>
        </form>
    </div>
</div>

<style>
    .join-group-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #333;
    }

    .form-group input {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1.2rem;
        letter-spacing: 2px;
        font-family: monospace;
    }

    .error-box {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #dc2626;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .success-box {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .info-box {
        background: #f8fbff;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 2rem;
        color: #555;
        font-size: 0.95rem;
    }

    .info-title {
        font-weight: 600;
        color: #003366;
    }

    .info-box ul li {
        margin-bottom: 0.3rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-cancel {
        flex: 1;
        padding: 0.8rem;
        display: block;
        text-align: center;
        border: 1px solid #003366;
        border-radius: 6px;
        color: #003366;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-cancel:hover {
        background: #f0f2f5;
    }

    .btn-submit {
        flex: 1;
        padding: 0.8rem;
        background: #003366;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: #002244;
    }
</style>

<?php include __DIR__ . '/../components/footer.php'; ?>
