<?php include __DIR__ . '/../components/header.php'; ?>

<div class="create-group-container">
    <h1 style="color: #003366; margin-bottom: 0.5rem;">Créer un groupe</h1>
    <p style="color: #666; margin-top: 0; margin-bottom: 2rem;">Créez un nouveau groupe de pronostics et invitez vos
        amis</p>

    <div class="form-card">
        <form action="/groups/create" method="POST">
            <div class="form-group">
                <label for="name">Nom du groupe<span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" placeholder="Les Champions de la Ligue 1" required>
            </div>

            <!-- Description field skipped as per user request -->

            <div class="info-box">
                <div class="info-title">ℹ️ Informations:</div>
                <ul style="margin: 0; padding-left: 1.2rem; margin-top: 0.5rem;">
                    <li>Un code d'invitation unique sera généré automatiquement</li>
                    <li>Vous pourrez partager ce code avec vos amis</li>
                    <li>Vous serez automatiquement administrateur du groupe</li>
                </ul>
            </div>

            <div class="form-actions">
                <a href="/mygroups" class="btn-cancel">Annuler</a>
                <button type="submit" class="btn-submit">Créer le groupe</button>
            </div>
        </form>
    </div>
</div>

<style>
    .create-group-container {
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
        font-size: 1rem;
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
        background: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: #218838;
    }
</style>

<?php include __DIR__ . '/../components/footer.php'; ?>