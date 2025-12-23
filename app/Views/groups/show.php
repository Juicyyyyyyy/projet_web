<?php include __DIR__ . '/../components/header.php'; ?>

<div class="group-container">
    <div class="group-header">
        <h1><?= htmlspecialchars($group->name) ?></h1>
        <div class="owner-badge">
            Par <?= htmlspecialchars($group->owner_name) ?>
        </div>
    </div>

    <div class="members-section">
        <h2>Membres (<?= count($members) ?>)</h2>
        <div class="members-list">
            <?php foreach ($members as $member): ?>
                <div class="member-card">
                    <div class="member-avatar">
                        <?= strtoupper(substr($member->user_name ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="member-info">
                        <div class="member-name"><?= htmlspecialchars($member->user_name ?? 'Utilisateur') ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    .group-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .group-header {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .group-header h1 {
        margin: 0 0 0.5rem 0;
        color: #0f172a;
    }

    .members-list {
        display: grid;
        gap: 1rem;
    }

    .member-card {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
</style>

<?php include __DIR__ . '/../components/footer.php'; ?>