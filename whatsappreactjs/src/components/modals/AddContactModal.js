import React, { useState, useRef } from 'react';
import '../css/Sidebars.css';

function AddContactModal({ onClose }) {
    const [firstName, setFirstName] = useState('');
    const [lastName, setLastName] = useState('');
    const [phone, setPhone] = useState('');
    const fileInputRef = useRef(null);

    const handleSubmit = (e) => {
        e.preventDefault();
        alert(
            `Contact ajouté !\nNom: ${lastName}\nPrénom: ${firstName}\nTéléphone: ${phone}`
        );
        onClose();
    };

    return (
        <div className="confirm-overlay" onClick={onClose}>
            <div
                className="confirm-modal edit-profile-modal p-4"
                onClick={(e) => e.stopPropagation()}
                style={{ maxWidth: 400, borderRadius: 10 }}
            >

                <form onSubmit={handleSubmit}>
                    <div className="mb-3">
                        <label htmlFor="inputLastName" className="form-label fw-semibold">
                            Nom
                        </label>
                        <input
                            type="text"
                            id="inputLastName"
                            className="form-control"
                            value={lastName}
                            onChange={(e) => setLastName(e.target.value)}
                            required
                            placeholder="Entrez le nom"
                        />
                    </div>

                    <div className="mb-3">
                        <label htmlFor="inputFirstName" className="form-label fw-semibold">
                            Prénom
                        </label>
                        <input
                            type="text"
                            id="inputFirstName"
                            className="form-control"
                            value={firstName}
                            onChange={(e) => setFirstName(e.target.value)}
                            required
                            placeholder="Entrez le prénom"
                        />
                    </div>

                    <div className="mb-3">
                        <label htmlFor="inputPhone" className="form-label fw-semibold">
                            Téléphone
                        </label>
                        <input
                            type="tel"
                            id="inputPhone"
                            className="form-control"
                            value={phone}
                            onChange={(e) => setPhone(e.target.value)}
                            required
                            placeholder="Entrez le téléphone"
                        />
                    </div>

                    <div className="d-flex justify-content-end gap-2">
                        <button type="submit" className="btn btn-success">
                            Ajouter
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

export default AddContactModal;
