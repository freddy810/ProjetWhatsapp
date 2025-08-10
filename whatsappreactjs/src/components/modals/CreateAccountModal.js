import React from 'react';
import '../css/CreateAccountModal.css';
import useCreateAccount from '../hook/hookCreateAccount';

export default function CreateAccountModal({ onClose }) {
    const {
        formData,
        preview,
        error,
        loading,
        handleChange,
        handleSubmit,
    } = useCreateAccount(onClose);

    return (
        <div className="modal-overlay">
            <div className="modal-content">
                <h2>Créer un compte</h2>

                {error && <p style={{ color: 'red' }}>{error}</p>}

                <form onSubmit={handleSubmit}>

                    <label htmlFor="photoProfil" className="image-upload-label">
                        {preview ? (
                            <img src={preview} alt="Aperçu" className="image-preview" />
                        ) : (
                            <span>Sélectionner une photo de profil</span>
                        )}
                    </label>
                    <input
                        type="file"
                        id="photoProfil"
                        name="photoProfil"
                        accept="image/*"
                        onChange={handleChange}
                        style={{ display: 'none' }}
                    />

                    <input
                        type="text"
                        name="nom"
                        placeholder="Nom"
                        value={formData.nom}
                        onChange={handleChange}
                        required
                    />

                    <input
                        type="text"
                        name="prenom"
                        placeholder="Prénom"
                        value={formData.prenom}
                        onChange={handleChange}
                        required
                    />

                    <input
                        type="date"
                        name="dateNaissance"
                        placeholder="Date de naissance"
                        value={formData.dateNaissance}
                        onChange={handleChange}
                        required
                    />

                    <input
                        type="tel"
                        name="telephone"
                        placeholder="Téléphone"
                        value={formData.telephone}
                        onChange={handleChange}
                        required
                    />

                    <input
                        type="password"
                        name="motDePasse"
                        placeholder="Mot de passe"
                        value={formData.motDePasse}
                        onChange={handleChange}
                        required
                    />

                    <input
                        type="password"
                        name="confirmeMotDePasse"
                        placeholder="Confirme mot de passe"
                        value={formData.confirmeMotDePasse}
                        onChange={handleChange}
                        required
                    />

                    <div className="modal-buttons">
                        <button type="submit" className="submit-button" disabled={loading}>
                            {loading ? "Création..." : "Créer"}
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
