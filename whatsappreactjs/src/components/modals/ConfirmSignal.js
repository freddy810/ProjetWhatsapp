import React from 'react';

function ConfirmSignal({ show, onClose, onConfirm }) {
    if (!show) return null;

    return (
        <div className="confirm-overlay" onClick={onClose}>
            <div
                className="confirm-modal"
                onClick={(e) => e.stopPropagation()}
            >
                <p>Vous êtes sûr de signaler ce contact ?</p>
                <div className="confirm-buttons">
                    <button className="btn btn-danger" onClick={onConfirm}>Oui</button>
                    <button className="btn btn-secondary" onClick={onClose}>Non</button>
                </div>
            </div>
        </div>
    );
}

export default ConfirmSignal;
