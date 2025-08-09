import React, { useRef, useEffect } from 'react';

function EditMessageModal({ show, message, onClose, onValidate, setMessage }) {
    const modalRef = useRef(null);

    // Fermeture de la modale si clic en dehors
    useEffect(() => {
        function handleClickOutside(event) {
            if (modalRef.current && !modalRef.current.contains(event.target)) {
                onClose();
            }
        }
        if (show) {
            document.addEventListener('mousedown', handleClickOutside);
        }
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [show, onClose]);

    if (!show) return null;

    return (
        <div className="confirm-overlay" onClick={onClose}>
            <div
                className="confirm-modal"
                ref={modalRef}
                onClick={(e) => e.stopPropagation()}
                style={{ maxWidth: '400px' }}
            >
                <p>Modifier le message :</p>
                <textarea
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                    rows={4}
                    style={{
                        width: '100%',
                        resize: 'none',
                        marginBottom: '1rem',
                        padding: '8px',
                        borderRadius: '4px',
                        border: '1px solid #ccc',
                        background: '#2e2f2f',
                        color: 'white',
                    }}
                    autoFocus
                />
                <div className="confirm-buttons">
                    <button className="btn btn-success" onClick={onValidate}>Valider</button>
                    <button className="btn btn-secondary" onClick={onClose}>Annuler</button>
                </div>
            </div>
        </div>
    );
}

export default EditMessageModal;
