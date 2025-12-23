<?php include __DIR__ . '/../components/header.php'; ?>

<div class="container" style="max-width: 800px; margin-top: 2rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
        <h1>Ajouter des matchs</h1>
        <a href="/groups/<?= $group->id ?>" class="btn-back">Retour au groupe</a>
    </div>

    <style>
        .match-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .match-item {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .match-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .match-teams {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .match-date {
            color: #64748b;
            font-size: 0.9rem;
        }

        .btn-add {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-add:hover {
            background: #2563eb;
        }

        .btn-add.added {
            background: #22c55e;
            cursor: default;
        }
    </style>

    <div class="match-list">
        <?php if (empty($matches)): ?>
            <div style="text-align:center; padding: 2rem; color: #64748b;">
                Aucun match disponible à ajouter.
            </div>
        <?php else: ?>
            <?php foreach ($matches as $match): ?>
                <div class="match-item" id="match-<?= $match->id ?>">
                    <div class="match-info">
                        <div class="match-teams">
                            <div class="match-teams">
                                <?= $match->home_team_name ?> VS <?= $match->away_team_name ?>
                            </div>
                            <div class="match-date"><?= date('d F Y H:i', strtotime($match->date)) ?></div>
                        </div>

                        <button class="btn-add" onclick="addMatch(<?= $group->id ?>, <?= $match->id ?>)">Ajouter</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        async function addMatch(groupId, matchId) {
            try {
                const response = await fetch(`/api/groups/${groupId}/matches`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ match_id: matchId })
                });

                const result = await response.json();

                if (response.ok) {
                    const btn = document.querySelector(`#match-${matchId} .btn-add`);
                    btn.textContent = 'Ajouté';
                    btn.classList.add('added');
                    btn.disabled = true;
                } else {
                    alert('Erreur: ' + (result.error || 'Impossible d\'ajouter le match'));
                }
            } catch (e) {
                console.error(e);
                alert('Erreur réseau');
            }
        }
    </script>

    <?php include __DIR__ . '/../components/footer.php'; ?>