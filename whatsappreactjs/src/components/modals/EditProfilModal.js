import React, { useState, useRef } from 'react';
import '../css/EditProfilModal.css';

function EditProfileModal({ contact, onClose }) {
    const [firstName, setFirstName] = useState(contact.firstName || '');
    const [lastName, setLastName] = useState(contact.lastName || '');
    const [imagePreview, setImagePreview] = useState(contact.image || null);
    const fileInputRef = useRef(null);

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setImagePreview(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    const handleRemoveImage = () => {
        setImagePreview(null);
    };

    const handleAddImageClick = () => {
        fileInputRef.current.click();
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        alert(`Profil sauvegardé !\nNom: ${lastName}\nPrénom: ${firstName}`);
        onClose();
    };

    return (
        <div className="confirm-overlay" onClick={onClose}>
            <div
                className="confirm-modal edit-profile-modal p-4"
                onClick={(e) => e.stopPropagation()}
                style={{ maxWidth: 400, borderRadius: 10 }}
            >
                {/* Header avec image + nom */}
                <div className="edit-contact-header d-flex flex-column align-items-center mb-4 position-relative">
                    {imagePreview ? (
                        <div style={{ position: 'relative', display: 'inline-block' }}>
                            <img
                                src={imagePreview}
                                alt="Profil"
                                className="chat__avatar rounded-circle mb-3"
                                style={{
                                    width: 100, height: 100, objectFit: 'cover', border: '2px solid #06d9b2'
                                }}
                            />
                            <button
                                type="button"
                                onClick={handleRemoveImage}
                                style={{
                                    position: 'absolute', top: 0, right: 0, background: 'red', color: 'white', border: 'none', borderRadius: '50%', width: 20, height: 20, cursor: 'pointer', fontSize: 12, lineHeight: '18px', textAlign: 'center'
                                }}
                            >
                                ✕
                            </button>
                        </div>
                    ) : (
                        <button
                            type="button"
                            onClick={handleAddImageClick}
                            style={{
                                width: 100, height: 100, borderRadius: '50%', border: '2px dashed #06d9b2', background: 'transparent', color: '#06d9b2', fontSize: 14, cursor: 'pointer'
                            }}
                        >
                            + Ajouter une image
                        </button>
                    )}
                    <h5 className="mb-0">{`${firstName} ${lastName}`}</h5>
                </div>

                {/* Formulaire */}
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
                        <label htmlFor="inputLastName" className="form-label fw-semibold">
                            Date de Naissance
                        </label>
                        <input
                            type="text"
                            id="inputLastName"
                            className="form-control"
                            value="23/03/2002"
                            onChange={(e) => setLastName(e.target.value)}
                            required
                            placeholder="Entrez le nom"
                        />
                    </div>

                    <div className="mb-3">
                        <label htmlFor="inputLastName" className="form-label fw-semibold">
                            Telephone
                        </label>
                        <input
                            type="text"
                            id="inputLastName"
                            className="form-control"
                            value="0325547923"
                            onChange={(e) => setLastName(e.target.value)}
                            required
                            placeholder="Entrez le nom"
                        />
                    </div>

                    <div className="mb-3">
                        <label htmlFor="inputLastName" className="form-label fw-semibold">
                            Mot de Passe
                        </label>
                        <input
                            type="text"
                            id="inputLastName"
                            className="form-control"
                            value="32223"
                            onChange={(e) => setLastName(e.target.value)}
                            required
                            placeholder="Entrez le nom"
                        />
                    </div>

                    <input
                        type="file"
                        ref={fileInputRef}
                        id="profileImage"
                        accept="image/*"
                        className="d-none"
                        onChange={handleImageChange}
                    />

                    {/* Boutons */}
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

export default EditProfileModal;
