import React, { useState } from 'react';
import '../css/EditContactModal.css';

function EditContactModal({ contact, onClose }) {
    // États locaux pour inputs du formulaire
    const [nom, setNom] = useState(contact.nom || '');
    const [prenom, setPrenom] = useState(contact.prenom || '');
    const [telephone, setTelephone] = useState(contact.telephone || '');

    // Gestion soumission formulaire (à adapter selon ton besoin)
    const handleSubmit = (e) => {
        e.preventDefault();
        // Ici tu peux gérer la sauvegarde, appel API, etc.
        alert(`Contact modifié:\nNom: ${nom}\nPrénom: ${prenom}\nTéléphone: ${telephone}`);
        onClose(); // fermer modal après sauvegarde
    };

    return (
        <div className="confirm-overlay" onClick={onClose}>
            <div
                className="confirm-modal edit-contact-modal p-4"
                onClick={(e) => e.stopPropagation()}
                style={{ maxWidth: 400, borderRadius: 10 }}
            >
                <div className="edit-contact-header d-flex flex-column align-items-center mb-4">
                    <img
                        src={contact.image}
                        alt="Profil"
                        className="chat__avatar rounded-circle mb-3"
                        style={{ width: 100, height: 100, objectFit: 'cover', border: '2px solid #06d9b2' }}
                    />
                    <h5 className="mb-0">{prenom} {nom}</h5>
                </div>

                <form onSubmit={handleSubmit}>
                    <div className="mb-3">
                        <label htmlFor="inputNom" className="form-label fw-semibold">
                            Nom
                        </label>
                        <input
                            type="text"
                            id="inputNom"
                            className="form-control"
                            value={nom}
                            onChange={(e) => setNom(e.target.value)}
                            required
                            placeholder="Entrez le nom"
                        />
                    </div>

                    <div className="mb-3">
                        <label htmlFor="inputPrenom" className="form-label fw-semibold">
                            Prénom
                        </label>
                        <input
                            type="text"
                            id="inputPrenom"
                            className="form-control"
                            value={prenom}
                            onChange={(e) => setPrenom(e.target.value)}
                            required
                            placeholder="Entrez le prénom"
                        />
                    </div>

                    <div className="mb-3">
                        <label htmlFor="inputTelephone" className="form-label fw-semibold">
                            Téléphone
                        </label>
                        <input
                            type="tel"
                            id="inputTelephone"
                            className="form-control"
                            value={telephone}
                            onChange={(e) => setTelephone(e.target.value)}
                            required
                            placeholder="Entrez le numéro de téléphone"
                            pattern="^\+?[0-9\s\-]{7,15}$"
                            title="Veuillez entrer un numéro valide"
                        />
                    </div>

                    <div className="d-flex justify-content-end gap-2">
                        <button type="submit" className="btn btn-success">
                            Sauvegarder
                        </button>
                        <button type="button" className="btn btn-secondary" onClick={onClose}>
                            Fermer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

export default EditContactModal;
