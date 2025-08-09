// ConfirmDeleteMessage.js
import React, { useRef, useEffect } from "react";
import "../css/Chat.css"; // Pour garder ton style

function ConfirmDeleteMessage({ show, onClose, onConfirm }) {
    const modalRef = useRef(null);

    useEffect(() => {
        function handleClickOutside(event) {
            if (modalRef.current && !modalRef.current.contains(event.target)) {
                onClose();
            }
        }
        if (show) {
            document.addEventListener("mousedown", handleClickOutside);
        }
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [show, onClose]);

    if (!show) return null;

    return (
        <div className="confirm-overlay" onClick={onClose}>
            <div
                className="confirm-modal"
                ref={modalRef}
                onClick={(e) => e.stopPropagation()}
            >
                <p>Êtes-vous sûr de vouloir supprimer ce message ?</p>
                <div className="confirm-buttons">
                    <button
                        className="btn btn-danger"
                        onClick={() => { onConfirm(); onClose(); }}
                    >
                        Oui
                    </button>
                    <button className="btn btn-secondary" onClick={onClose}>
                        Non
                    </button>
                </div>
            </div>
        </div>
    );
}

export default ConfirmDeleteMessage;
