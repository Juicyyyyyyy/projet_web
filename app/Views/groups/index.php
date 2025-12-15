<?php include __DIR__ . '/../components/header.php'; ?>

<div class="groups-header">
    <h1 style="color: #003366; margin-bottom: 0.5rem;">Mes Groupes</h1>
    <p style="color: #666; margin-top: 0;">Gérez vos groupes de pronostics et créez-en de nouveaux</p>
</div>

<div class="groups-grid">
    <?php if (!empty($groups)): ?>
        <?php foreach ($groups as $group): ?>
            <div class="group-card">
                <h3 style="margin-top: 0; color: #003366;"><?= htmlspecialchars($group->name) ?></h3>
                <div class="group-meta">
                    <span class="icon">👥</span>
                    <?= $group->member_count ?> membres
                </div>
                <a href="/groups/view?id=<?= $group->id ?>" class="btn-view">Voir</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Create Group Card -->
    <a href="/groups/create" class="group-card create-card">
        <div class="plus-icon">+</div>
        <div>Créer un groupe</div>
    </a>
</div>

<style>
    .groups-header {
        margin-bottom: 2rem;
    }

    .groups-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .group-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 150px;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .group-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .group-meta {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view {
        background: #003366;
        /* Dark blue from design */
        color: white;
        text-align: center;
        padding: 0.8rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        margin-top: auto;
    }

    .btn-view:hover {
        background: #002244;
    }

    .create-card {
        justify-content: center;
        align-items: center;
        color: #003366;
        border: 2px dashed #e0e0e0;
        box-shadow: none;
    }

    .create-card:hover {
        border-color: #003366;
        background: #f8fbff;
    }

    .plus-icon {
        font-size: 3rem;
        line-height: 1;
        margin-bottom: 0.5rem;
        color: #ccc;
    }
</style>

<?php include __DIR__ . '/../components/footer.php'; ?>