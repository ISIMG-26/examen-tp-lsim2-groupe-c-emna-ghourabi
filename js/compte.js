// ══════════════════════════════════════════════════════════════
//  UTILITAIRE FETCH
// ══════════════════════════════════════════════════════════════
async function postData(url, data) {
    try {
        const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin', // ← envoie le cookie de session
            body: data
        });

        const text = await res.text();

        if (!res.ok) {
            // Erreur HTTP (404, 500, etc.)
            console.error(`[${url}] HTTP ${res.status}:`, text);
            return `Erreur serveur (${res.status}). Vérifie la console.`;
        }

        return text;

    } catch (err) {
        console.error(`[${url}] Fetch error:`, err);
        return "Erreur réseau : impossible de contacter le serveur.";
    }
}

// ══════════════════════════════════════════════════════════════
//  ALERT UI
// ══════════════════════════════════════════════════════════════
function showAlert(el, type, msg) {
    el.className = `compte-alert ${type}`;
    el.textContent = msg;
    el.style.display = 'block';
}

function hideAlert(el) {
    el.style.display = 'none';
    el.textContent = '';
}

// ══════════════════════════════════════════════════════════════
//  CHANGE PASSWORD
// ══════════════════════════════════════════════════════════════
async function doChangePassword() {
    const cur  = document.getElementById('cur-pass').value.trim();
    const neu  = document.getElementById('new-pass').value.trim();
    const conf = document.getElementById('conf-pass').value.trim();
    const alertEl = document.getElementById('pwd-alert');
    const btn  = document.getElementById('btn-pwd');

    hideAlert(alertEl);

    // Validation front-end
    if (!cur || !neu || !conf) {
        return showAlert(alertEl, 'error', 'Tous les champs sont requis.');
    }
    if (neu.length < 8) {
        return showAlert(alertEl, 'error', 'Le nouveau mot de passe doit faire au moins 8 caractères.');
    }
    if (neu !== conf) {
        return showAlert(alertEl, 'error', 'La confirmation ne correspond pas au nouveau mot de passe.');
    }

    btn.disabled = true;
    btn.textContent = 'Mise à jour…';

    const data = new FormData();
    data.append('old_pass', cur);
    data.append('new_pass', neu);
    data.append('confirm_pass', conf);

    const responseText = await postData('../back/change_password.php', data);

    if (responseText.toLowerCase().includes('succès')) {
        showAlert(alertEl, 'success', responseText);
        ['cur-pass', 'new-pass', 'conf-pass'].forEach(id => {
            document.getElementById(id).value = '';
        });
    } else {
        showAlert(alertEl, 'error', responseText);
    }

    btn.disabled = false;
    btn.textContent = 'Mettre à jour le mot de passe';
}

// ══════════════════════════════════════════════════════════════
//  DELETE ACCOUNT
// ══════════════════════════════════════════════════════════════
async function doDeleteAccount() {
    const pass    = document.getElementById('del-pass').value.trim();
    const alertEl = document.getElementById('del-modal-alert');
    const btn     = document.getElementById('btn-del-confirm');

    hideAlert(alertEl);

    if (!pass) {
        return showAlert(alertEl, 'error', 'Veuillez entrer votre mot de passe.');
    }

    btn.disabled = true;
    btn.textContent = 'Suppression…';

    const data = new FormData();
    data.append('del_pass', pass);

    const responseText = await postData('../back/delete_account.php', data);

    if (responseText.toLowerCase().includes('succès')) {
        window.location.href = '../html/connexion.php';
    } else {
        showAlert(alertEl, 'error', responseText);
        btn.disabled = false;
        btn.textContent = 'Oui, supprimer';
    }
}

// ══════════════════════════════════════════════════════════════
//  MODAL CONTROLS
// ══════════════════════════════════════════════════════════════
function openDeleteModal() {
    document.getElementById('del-overlay').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('del-overlay').classList.remove('show');
    hideAlert(document.getElementById('del-modal-alert'));
    document.getElementById('del-pass').value = '';
}

// Fermeture au clic à l'extérieur
document.getElementById('del-overlay')?.addEventListener('click', e => {
    if (e.target.id === 'del-overlay') closeDeleteModal();
});

// ══════════════════════════════════════════════════════════════
//  PASSWORD STRENGTH (optionnel)
// ══════════════════════════════════════════════════════════════
function evalStrength(val) {
    // Peut être enrichi si besoin
}