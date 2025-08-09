import React, { useRef, useEffect } from 'react';

function ConfirmBlockModal({ show, onClose, onConfirm }) {
    const confirmBlockRef = useRef(null);

    // Fermeture modale si clic en dehors
    useEffect(() => {
        function handleClickOutside(event) {
            if (confirmBlockRef.current && !confirmBlockRef.current.contains(event.target)) {
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
                ref={confirmBlockRef}
                onClick={e => e.stopPropagation()}
            >
                <p>Vous êtes sûr de bloquer ce contact ?</p>
                <div className="confirm-buttons">
                    <button className="btn btn-danger" onClick={onConfirm}>Oui</button>
                    <button className="btn btn-secondary" onClick={onClose}>Non</button>
                </div>
            </div>
        </div>
    );
}

export default ConfirmBlockModal;
