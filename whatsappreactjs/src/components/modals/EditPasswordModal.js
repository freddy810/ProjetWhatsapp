import React from 'react';
import '../css/EditPasswordModal.css';
import useEditPassword from '../hook/hookEditPassword';

export default function EditPasswordModal({ onClose }) {
    const {
        formData,
        error,
        loading,
        handleChange,
        handleSubmit,
    } = useEditPassword(onClose);

    return (
        <div className="modal-overlay">
            <div className="modal-content">
                <h2>Modifier le mot de passe</h2>

                {error && <p style={{ color: 'red' }}>{error}</p>}

                <form onSubmit={handleSubmit}>
                    <input
                        type="tel"
                        name="telephone"
                        placeholder="Numéro de téléphone"
                        value={formData.telephone}
                        onChange={handleChange}
                        required
                    />
                    <input
                        type="password"
                        name="nouveauMotDePasse"
                        placeholder="Nouveau mot de passe"
                        value={formData.nouveauMotDePasse}
                        onChange={handleChange}
                        required
                    />
                    <input
                        type="password"
                        name="confirmeNouveauMotDePasse"
                        placeholder="Confirmer nouveau mot de passe"
                        value={formData.confirmeNouveauMotDePasse}
                        onChange={handleChange}
                        required
                    />

                    <div className="modal-buttons">
                        <button type="submit" className="submit-button" disabled={loading}>
                            {loading ? 'Chargement...' : 'Valider'}
                        </button>
                        <button type="button" className="cancel-button" onClick={onClose} disabled={loading}>
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
