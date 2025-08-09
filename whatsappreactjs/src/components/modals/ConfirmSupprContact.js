import React, { useRef, useEffect } from 'react';

function ConfirmSupprContact({ show, onClose, onConfirm }) {
    const modalRef = useRef(null);

    // Fermeture si clic en dehors de la modale
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
        <div className="confirm-overlay" >
            <div
                className="confirm-modal"
                ref={modalRef}
                onClick={e => e.stopPropagation()}
            >
                <p>Êtes-vous sûr de vouloir supprimer ce contact ?</p>
                <div className="confirm-buttons">
                    <button
                        className="btn btn-danger"
                        onClick={() => {
                            onConfirm();
                        }}
                    >
                        Oui
                    </button>
                    <button
                        className="btn btn-secondary"
                        onClick={onClose}
                    >
                        Non
                    </button>
                </div>
            </div>
        </div>
    );
}

export default ConfirmSupprContact;
