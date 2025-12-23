<style>
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(2px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        width: 90%;
        max-width: 600px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        padding: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .modal-header,
    .modal-footer {
        padding: 1.25rem;
        border-color: #e2e8f0;
        border-style: solid;
        display: flex;
        align-items: center;
    }

    .modal-header {
        border-bottom-width: 1px;
        justify-content: space-between;
    }

    .modal-footer {
        border-top-width: 1px;
        gap: 1rem;
    }

    .modal-title {
        color: #003366;
        margin: 0;
        font-weight: 600;
    }

    .modal-subtitle {
        margin-bottom: 1rem;
        color: #64748b;
    }

    .modal-body {
        padding: 1.25rem;
        overflow-y: auto;
    }

    .modal-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .modal-empty-msg {
        text-align: center;
        color: #64748b;
    }

    .modal-match-item {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-match-item:hover {
        border-color: #cbd5e1;
    }

    .modal-match-item.selected {
        border-color: #003366;
        background: #f0f7ff;
    }

    .modal-match-time {
        display: flex;
        align-items: center;
    }

    .modal-time-separator {
        margin-left: 0.5rem;
    }

    .modal-vs-span {
        color: #cbd5e1;
        margin: 0 5px;
    }

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

    .btn-modal-cancel {
        border: 1px solid #cbd5e1;
        background: white;
        color: #334155;
        padding: 0.75rem 1.5rem;
        flex: 1;
    }

    .btn-modal-add {
        background: #003366;
        color: white;
        padding: 0.75rem 1.5rem;
        flex: 1;
    }

    .btn-modal-add:hover {
        background: #002244;
    }

    .btn-modal-add:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
    }
</style>


<div id="addMatchModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter un match Ligue 1</h3>
            <button class="modal-close" onclick="closeAddMatchModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Sélectionnez un match à venir (max 30
                jours)</p>
            <div class="modal-list">
                <?php if (empty($availableMatches)): ?>
                    <p class="modal-empty-msg">Aucun match disponible pour les 30 prochains jours.</p>
                <?php else: ?>
                    <?php foreach ($availableMatches as $am): ?>
                        <div class="modal-match-item" onclick="selectMatch(this, <?= $am->id ?>)">
                            <div class="modal-match-details">
                                <div class="modal-match-time">
                                    <span> <?= date('d F Y', strtotime($am->date)) ?></span>
                                    <span class="modal-time-separator"> <?= date('H:i', strtotime($am->date)) ?></span>
                                </div>
                            </div>
                            <div class="modal-match-teams">
                                <?= $am->home_team_name ?> <span class="modal-vs-span">vs</span>
                                <?= $am->away_team_name ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="closeAddMatchModal()">Annuler</button>
            <button class="btn-modal-add" id="btnAddMatchConfirm" disabled onclick="confirmAddMatch()">Ajouter</button>
        </div>
    </div>
</div>

<script>
    let selectedMatchId = null;
    // groupId must be defined in the parent view

    function openAddMatchModal() {
        document.getElementById('addMatchModal').classList.add('active');
    }

    function closeAddMatchModal() {
        document.getElementById('addMatchModal').classList.remove('active');
        document.querySelectorAll('.modal-match-item').forEach(el => el.classList.remove('selected'));
        selectedMatchId = null;
        document.getElementById('btnAddMatchConfirm').disabled = true;
    }

    function selectMatch(element, matchId) {
        document.querySelectorAll('.modal-match-item').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        selectedMatchId = matchId;
        document.getElementById('btnAddMatchConfirm').disabled = false;
    }

    async function confirmAddMatch() {
        if (!selectedMatchId) return;

        if (!confirm("Confirmer l'ajout de ce match au groupe ?")) {
            return;
        }

        try {
            const response = await fetch(`/api/groups/${groupId}/matches`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ match_id: selectedMatchId })
            });

            const result = await response.json();

            if (response.ok) {
                alert('Match ajouté avec succès !');
                location.reload();
            } else {
                alert('Erreur: ' + (result.error || 'Impossible d\'ajouter le match'));
            }
        } catch (e) {
            console.error(e);
            alert('Erreur réseau');
        }
    }
</script>