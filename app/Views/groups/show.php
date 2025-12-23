<?php include __DIR__ . '/../components/header.php'; ?>

<style>
    /* Global & Layout */
    .group-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .main-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    /* Common Card Styles */
    .group-header,
    .sidebar-card,
    .match-card-new,
    .modal-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Flex Containers & Alignments */
    .group-header,
    .group-meta,
    .mc-teams,
    .prediction-options,
    .inputs-row,
    .score-inputs,
    .goal-diff-input,
    .member-item,
    .chat-input,
    .modal-header,
    .modal-footer,
    .modal-match-item,
    .modal-match-time {
        display: flex;
        align-items: center;
    }

    .group-header,
    .mc-teams,
    .modal-header,
    .modal-match-item {
        justify-content: space-between;
    }

    .group-info {
        flex: 1;
    }

    /* Typography */
    .group-info h1,
    .matches-section h2,
    .sidebar-title,
    .modal-title {
        color: #003366;
        margin: 0 0 1rem 0;
        font-weight: 600;
    }

    .group-info h1 {
        margin: 0;
        font-size: 1.8rem;
    }

    .matches-section h2 {
        font-size: 1.25rem;
    }

    .group-meta {
        color: #666;
        margin-top: 0.5rem;
        font-size: 0.95rem;
        gap: 1.5rem;
    }

    .code-badge {
        background: #f1f5f9;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        font-family: monospace;
        color: #334155;
    }

    .member-name {
        font-weight: 500;
        color: #334155;
    }

    /* Buttons */
    .btn-back,
    .btn-add-match,
    .btn-validate,
    .btn-send,
    .btn-modal-cancel,
    .btn-modal-add {
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        text-align: center;
    }

    .btn-back {
        border: 1px solid #003366;
        color: #003366;
        padding: 0.5rem 1rem;
        background: transparent;
    }

    .btn-back:hover {
        background: #f0f7ff;
    }

    .btn-add-match,
    .btn-send,
    .btn-modal-add {
        background: #003366;
        color: white;
        padding: 0.5rem 1rem;
    }

    .btn-add-match:hover,
    .btn-send:hover,
    .btn-modal-add:hover {
        background: #002244;
    }

    .btn-validate {
        background: #86efac;
        color: #065f46;
        width: 100%;
        padding: 1rem;
        font-weight: bold;
        margin-top: 0.5rem;
    }

    .btn-validate:hover {
        background: #4ade80;
    }

    .btn-modal-cancel {
        border: 1px solid #cbd5e1;
        background: white;
        color: #334155;
        padding: 0.75rem 1.5rem;
        flex: 1;
    }

    .btn-modal-add {
        flex: 1;
        padding: 0.75rem 1.5rem;
    }

    .btn-modal-add:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }

    /* Match Card Specifics */
    .mc-header {
        margin-bottom: 1.5rem;
    }

    .mc-date {
        font-weight: 500;
        color: #64748b;
        font-size: 0.9rem;
    }

    .mc-time {
        font-weight: 500;
        color: #94a3b8;
        font-size: 0.85rem;
        margin-top: 0.2rem;
    }

    .mc-team-name {
        flex: 1;
        text-align: center;
        font-weight: 600;
        color: #0f172a;
        font-size: 1rem;
    }

    .mc-vs {
        margin: 0 1rem;
        color: #cbd5e1;
        font-weight: 500;
    }

    .mc-label {
        display: block;
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .mc-label span {
        color: #94a3b8;
        font-weight: normal;
    }

   
    .prediction-options {
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .prediction-option {
        flex: 1;
        position: relative;
    }

    .prediction-option input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    .prediction-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        color: #0f172a;
        font-size: 0.9rem;
        transition: all 0.2s;
        height: 100%;
    }

    .prediction-option input:checked + .prediction-btn {
        border-color: #003366;
        background: #f8fafc;
        color: #003366;
        font-weight: 600;
    }

    .inputs-row {
        gap: 2rem;
        margin-bottom: 1.5rem;
        align-items: flex-start;
    }

    .input-col {
        flex: 1;
    }

    .score-inputs,
    .goal-diff-input {
        width: 100%;
        gap: 1rem;
    }

    .score-inputs input,
    .goal-diff-input {
        padding: 0.6rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        text-align: center;
        width: 100%;
    }
    
 
    .member-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .member-item:last-child {
        border-bottom: none;
    }

    .avatar-circle {
        width: 36px;
        height: 36px;
        background: #003366;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 0.9rem;
    }

    .chat-area {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        border: 1px dashed #e2e8f0;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .chat-input {
        gap: 0.5rem;
    }

    .chat-input input {
        flex: 1;
        padding: 0.6rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
    }

    /* Leaderboard */
    .leaderboard-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .leaderboard-item:last-child { border-bottom: none; }
    .leaderboard-item.current-user {
        background: #f0f7ff;
        margin: 0 -1rem;
        padding: 0.75rem 1rem;
        border-radius: 8px;
    }
    .leaderboard-rank {
        width: 24px;
        height: 24px;
        background: #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        margin-right: 0.5rem;
        color: #334155;
    }
    .leaderboard-item:nth-child(1) .leaderboard-rank { background: #ffd700; color: #000; }
    .leaderboard-item:nth-child(2) .leaderboard-rank { background: #c0c0c0; color: #000; }
    .leaderboard-item:nth-child(3) .leaderboard-rank { background: #cd7f32; color: #fff; }
    .leaderboard-info { flex: 1; margin-left: 0.5rem; }
    .leaderboard-stats { font-size: 0.75rem; color: #94a3b8; }
    .leaderboard-points {
        font-weight: 700;
        color: #003366;
        font-size: 1rem;
    }

    /* Match terminé */
    .match-finished {
        border-left: 4px solid #10b981;
    }
    .match-result {
        text-align: center;
        padding: 1rem;
        background: #f0fdf4;
        border-radius: 8px;
        margin: 1rem 0;
    }
    .match-result-score {
        font-size: 1.5rem;
        font-weight: 700;
        color: #065f46;
    }
    .match-result-label {
        font-size: 0.8rem;
        color: #059669;
        margin-bottom: 0.25rem;
    }
    .bet-result {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }
    .bet-result-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .bet-result-label { font-size: 0.85rem; color: #64748b; }
    .bet-result-points {
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }
    .points-high { background: #dcfce7; color: #166534; }
    .points-medium { background: #fef3c7; color: #92400e; }
    .points-low { background: #fee2e2; color: #991b1b; }
    .bet-result-score {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f172a;
    }

    /* Détails du pronostic */
    .bet-details {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .bet-detail-item {
        flex: 1;
        min-width: 100px;
        background: white;
        padding: 0.75rem;
        border-radius: 8px;
        text-align: center;
    }
    .bet-detail-label {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .bet-detail-value {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .bet-detail-value.correct {
        color: #059669;
    }
    .bet-detail-value.incorrect {
        color: #dc2626;
    }
</style>

<div class="group-container">
    <div class="group-header">
        <div class="group-info">
            <h1><?= htmlspecialchars($group->name) ?></h1>
            <div class="group-meta">
                <span>Par <?= htmlspecialchars($group->owner_name ?? 'Inconnu') ?></span>
                <span>👥 <?= count($members) ?> membres</span>
                <?php if (isset($group->id)): ?>
                                    <span>Code: <span class="code-badge">GROUPE<?= $group->id ?></span></span>
                <?php endif; ?>
            </div>
        </div>
        <a href="/groups" class="btn-back">Retour</a>
    </div>

    <div class="main-grid">
        
        <div class="matches-section">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h2>Matchs du groupe</h2>
                <?php if ($isOwner): ?>
                                    <button onclick="openAddMatchModal()" class="btn-add-match" type="button">+ Ajouter un match</button>
                <?php endif; ?>
            </div>
            <p style="color: #64748b; font-size: 0.9rem;">Faites vos pronostics</p>

            <?php if (empty($matches)): ?>
                                <div class="match-card-new">
                                    <p style="text-align: center; color: #94a3b8;">Aucun match pour le moment.</p>
                                </div>
            <?php else: ?>
                <?php foreach ($matches as $match): ?>
                    <?php
                    $bet = $userBets[$match->id] ?? null;
                    $hasBet = !empty($bet);
                    $isFinished = ($match->status === 'FT');
                    ?>
                    <div class="match-card-new <?= $isFinished ? 'match-finished' : '' ?>">
                        <div class="mc-header">
                            <div class="mc-date"><?= date('d F Y', strtotime($match->date)) ?></div>
                            <div class="mc-time"><?= date('H:i', strtotime($match->date)) ?></div>
                        </div>

                        <div class="mc-teams">
                            <div class="mc-team-name"><?= $match->home_team_name ?></div>
                            <div class="mc-vs">VS</div>
                            <div class="mc-team-name"><?= $match->away_team_name ?></div>
                        </div>

                        <?php if ($isFinished): ?>
                            <!-- Match terminé - Afficher le résultat -->
                            <div class="match-result">
                                <div class="match-result-label">Résultat final</div>
                                <div class="match-result-score">
                                    <?= $match->home_score ?> - <?= $match->away_score ?>
                                </div>
                            </div>

                            <?php if ($hasBet): ?>
                                <?php $details = $betDetails[$match->id] ?? null; ?>
                                <div class="bet-result">
                                    <div class="bet-result-header">
                                        <span class="bet-result-label">Votre pronostic</span>
                                        <?php $pointsClass = $details['points'] >= 7 ? 'points-high' : ($details['points'] >= 4 ? 'points-medium' : 'points-low'); ?>
                                        <span class="bet-result-points <?= $pointsClass ?>">+<?= $details['points'] ?> pts</span>
                                    </div>

                                    <div class="bet-details">
                                        <div class="bet-detail-item">
                                            <span class="bet-detail-label">Vainqueur</span>
                                            <span class="bet-detail-value <?= $details['winner_correct'] ? 'correct' : 'incorrect' ?>">
                                                <?= $details['winner_icon'] ?> <?= $details['predicted_winner'] ?>
                                            </span>
                                        </div>

                                        <div class="bet-detail-item">
                                            <span class="bet-detail-label">Score</span>
                                            <span class="bet-detail-value <?= $details['score_correct'] ? 'correct' : 'incorrect' ?>">
                                                <?= $bet->home_score ?> - <?= $bet->away_score ?>
                                            </span>
                                        </div>

                                        <?php if ($bet->goal_difference !== null): ?>
                                        <div class="bet-detail-item">
                                            <span class="bet-detail-label">Écart</span>
                                            <span class="bet-detail-value <?= $details['diff_correct'] ? 'correct' : 'incorrect' ?>">
                                                <?= $bet->goal_difference ?> but<?= $bet->goal_difference > 1 ? 's' : '' ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="bet-result" style="text-align: center; color: #94a3b8;">
                                    Vous n'avez pas parié sur ce match
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <!-- Match à venir - Formulaire de pari -->
                            <form class="bet-submit-form" data-match-id="<?= $match->id ?>">
                                <input type="hidden" name="group_id" value="<?= $group->id ?>">
                                <input type="hidden" name="match_id" value="<?= $match->id ?>">
                                <input type="hidden" name="home_team_id" value="<?= $match->home_team_id ?>">
                                <input type="hidden" name="away_team_id" value="<?= $match->away_team_id ?>">

                                <div class="mc-label">Résultat prédit *</div>
                                <div class="prediction-options">
                                    <div class="prediction-option">
                                        <input type="radio" name="prediction" value="home" required
                                            <?= ($hasBet && $bet->winner_team_id == $match->home_team_id) ? 'checked' : '' ?>>
                                        <div class="prediction-btn">Victoire <?= $match->home_team_name ?></div>
                                    </div>
                                    <div class="prediction-option">
                                        <input type="radio" name="prediction" value="draw" required
                                            <?= ($hasBet && $bet->winner_team_id === null && ($bet->home_score !== null || $bet->goal_difference !== null)) ? 'checked' : '' ?>>
                                        <div class="prediction-btn">Match nul</div>
                                    </div>
                                    <div class="prediction-option">
                                        <input type="radio" name="prediction" value="away" required
                                            <?= ($hasBet && $bet->winner_team_id == $match->away_team_id) ? 'checked' : '' ?>>
                                        <div class="prediction-btn">Victoire <?= $match->away_team_name ?></div>
                                    </div>
                                </div>

                                <div class="inputs-row">
                                    <div class="input-col">
                                        <label class="mc-label">Score exact <span>(optionnel)</span></label>
                                        <div class="score-inputs">
                                            <input type="number" name="home_score" min="0" placeholder="0"
                                                value="<?= $hasBet ? $bet->home_score : '' ?>">
                                            <span class="score-sep">-</span>
                                            <input type="number" name="away_score" min="0" placeholder="0"
                                                value="<?= $hasBet ? $bet->away_score : '' ?>">
                                        </div>
                                    </div>
                                    <div class="input-col">
                                        <label class="mc-label">Différence de buts <span>(optionnel)</span></label>
                                        <input type="number" name="goal_difference" min="0" class="goal-diff-input" placeholder="ex: 0"
                                            value="<?= $hasBet ? $bet->goal_difference : '' ?>">
                                    </div>
                                </div>

                                <button type="submit" class="btn-validate">Valider mon pari</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Classement -->
            <div class="sidebar-card">
                <h3 class="sidebar-title">Classement</h3>
                <?php if (!empty($leaderboard)): ?>
                    <?php $rank = 1; foreach ($leaderboard as $player): ?>
                        <div class="leaderboard-item <?= $player->id == $_SESSION['user_id'] ? 'current-user' : '' ?>">
                            <div class="leaderboard-rank"><?= $rank ?></div>
                            <div class="avatar-circle">
                                <?= strtoupper(substr($player->name ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="leaderboard-info">
                                <div class="member-name"><?= htmlspecialchars($player->name ?? 'Inconnu') ?></div>
                                <div class="leaderboard-stats"><?= $player->total_bets ?> paris</div>
                            </div>
                            <div class="leaderboard-points"><?= $player->total_points ?> pts</div>
                        </div>
                    <?php $rank++; endforeach; ?>
                <?php else: ?>
                    <p style="color: #94a3b8; text-align: center;">Aucun classement</p>
                <?php endif; ?>
            </div>

            <div class="sidebar-card">
                <h3 class="sidebar-title">Membres</h3>
                <?php foreach ($members as $member): ?>
                    <div class="member-item">
                        <div class="avatar-circle">
                            <?= strtoupper(substr($member->user_name ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="member-info">
                            <div class="member-name"><?= htmlspecialchars($member->user_name ?? 'Inconnu') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="sidebar-card">
                <h3 class="sidebar-title">Discussion</h3>
                <div class="chat-area">
                    Aucun message pour le moment.
                </div>
                <div class="chat-input">
                    <input type="text" placeholder="Ecrivez votre message...">
                    <button class="btn-send">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<?php include __DIR__ . '/partials/add_match_modal.php'; ?>

<script>
    const groupId = <?= $group->id ?>;


    document.querySelectorAll('.bet-submit-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            try {
                const response = await fetch('/bets', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (response.ok) {
                    alert('Pari enregistré avec succès !');
                } else {
                    alert('Erreur: ' + (result.error || 'Une erreur est survenue'));
                }
            } catch (error) {
                console.error(error);
                alert('Erreur lors de la communication avec le serveur');
            }
        });
    });
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>