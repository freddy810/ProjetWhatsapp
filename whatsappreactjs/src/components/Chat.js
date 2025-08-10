import React, { useState, useRef, useEffect } from 'react';
import './css/Chat.css';

import { FiPaperclip } from 'react-icons/fi';
import { BsEmojiSmile, BsSearch } from 'react-icons/bs';
import { MdVideoCall, MdMoreVert, MdEdit, MdBlock, MdReportProblem, MdDelete } from 'react-icons/md';

import EditContactModal from './modals/EditContactModal';
import ConfirmBlockModal from './modals/ConfirmBlockModal';
import ConfirmSignal from './modals/ConfirmSignal';
import ConfirmSupprContact from './modals/ConfirmSupprContact';
import EditMessageModal from './modals/EditMessageModal';
import ConfirmDeleteMessage from './modals/ConfirmDeleteMessage';

function Chat() {
    const [menuOpen, setMenuOpen] = useState(false);
    const [showEditContact, setShowEditContact] = useState(false);
    const [showConfirmBlock, setShowConfirmBlock] = useState(false);
    const [showConfirmSignal, setShowConfirmSignal] = useState(false);
    const [showConfirmDelete, setShowConfirmDelete] = useState(false);
    const [showMessageMenu, setShowMessageMenu] = useState(false);
    const [showSearchInput, setShowSearchInput] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');
    const [showConfirmDeleteMessage, setShowConfirmDeleteMessage] = useState(false);
    const [showEditMessageModal, setShowEditMessageModal] = useState(false);
    const [editedMessage, setEditedMessage] = useState('Bonjour !');

    // Nouvel état pour menu appel audio/vidéo
    const [showCallMenu, setShowCallMenu] = useState(false);

    // Références pour gérer clic hors menus
    const fileInputRef = useRef(null);
    const menuRef = useRef(null);
    const buttonRef = useRef(null);
    const messageMenuButtonRef = useRef(null);
    const messageMenuRef = useRef(null);
    const callMenuRef = useRef(null);
    const callButtonRef = useRef(null);

    // Contact fictif
    const contact = {
        name: "Jean Dupont",
        image: "https://randomuser.me/api/portraits/men/32.jpg",
        phoneNumbers: ["+33 6 12 34 56 78", "+33 1 23 45 67 89"],
    };

    // Fermeture menu options contact si clic hors menu
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                menuRef.current &&
                !menuRef.current.contains(event.target) &&
                buttonRef.current &&
                !buttonRef.current.contains(event.target)
            ) {
                setMenuOpen(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    // Fermeture menu options message si clic hors menu
    useEffect(() => {
        function handleClickOutsideMessageMenu(event) {
            if (
                messageMenuRef.current &&
                !messageMenuRef.current.contains(event.target) &&
                messageMenuButtonRef.current &&
                !messageMenuButtonRef.current.contains(event.target)
            ) {
                setShowMessageMenu(false);
            }
        }
        if (showMessageMenu) {
            document.addEventListener('mousedown', handleClickOutsideMessageMenu);
        }
        return () => {
            document.removeEventListener('mousedown', handleClickOutsideMessageMenu);
        };
    }, [showMessageMenu]);

    // Fermeture menu appel si clic hors menu
    useEffect(() => {
        function handleClickOutsideCallMenu(event) {
            if (
                callMenuRef.current &&
                !callMenuRef.current.contains(event.target) &&
                callButtonRef.current &&
                !callButtonRef.current.contains(event.target)
            ) {
                setShowCallMenu(false);
            }
        }
        if (showCallMenu) {
            document.addEventListener('mousedown', handleClickOutsideCallMenu);
        }
        return () => {
            document.removeEventListener('mousedown', handleClickOutsideCallMenu);
        };
    }, [showCallMenu]);

    // Handlers menu contact
    function handleEditClick() {
        setShowEditContact(true);
        setMenuOpen(false);
    }
    function handleBlockClick() {
        setShowConfirmBlock(true);
        setMenuOpen(false);
    }
    function handleReportClick() {
        setShowConfirmSignal(true);
        setMenuOpen(false);
    }
    function handleDeleteClick() {
        setShowConfirmDelete(true);
        setMenuOpen(false);
    }

    // Handlers menu message
    function handleDeleteMessageClick() {
        setShowConfirmDeleteMessage(true);
        setShowMessageMenu(false);
    }
    function handleEditMessageClick() {
        setShowEditMessageModal(true);
        setShowMessageMenu(false);
        setEditedMessage('Bonjour !');
    }

    // Fermetures modaux
    function closeConfirmDeleteModal() {
        setShowConfirmDelete(false);
    }
    function closeConfirmDeleteMessageModal() {
        setShowConfirmDeleteMessage(false);
    }
    function closeConfirmSignalModal() {
        setShowConfirmSignal(false);
    }
    function closeEditMessageModal() {
        setShowEditMessageModal(false);
    }

    // Validation édition message (placeholder)
    function validateEditMessage() {
        alert(`Message modifié : ${editedMessage}`);
        closeEditMessageModal();
    }

    // Gestion input fichier
    function handlePaperclipClick() {
        if (fileInputRef.current) {
            fileInputRef.current.click();
        }
    }
    function handleFileChange(event) {
        const files = event.target.files;
        if (files.length > 0) {
            alert(`Fichier sélectionné : ${files[0].name}`);
        }
    }

    // Gestion menu appel
    const handleCallClick = () => {
        setShowCallMenu(prev => !prev);
        setMenuOpen(false);
    };
    const handleCallOptionClick = (type) => {
        alert(`Démarrage appel ${type}`);
        setShowCallMenu(false);
    };

    return (
        <div className="col-md-8 col-12 chatWindow d-flex flex-column p-0">
            {/* Header chat */}
            <div className="chat__header w-100 px-3 py-2 d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center gap-2">
                    <img src={contact.image} alt="Profil" className="chat__avatar" />
                    <div>
                        <div className="chat__name fw-bold">{contact.name}</div>
                        <div className="chat__status" style={{ fontSize: "12px" }}>en ligne</div>
                    </div>
                </div>

                <div className="d-flex align-items-center gap-3 position-relative">
                    {/* Recherche */}
                    <div className="tooltip-wrapper">
                        <BsSearch
                            size={20}
                            className="icon"
                            onClick={() => setShowSearchInput(!showSearchInput)}
                            style={{ cursor: 'pointer' }}
                        />
                        <span className="tooltip-text">Rechercher</span>
                    </div>
                    {showSearchInput && (
                        <input
                            type="text"
                            placeholder="Recherche..."
                            value={searchQuery}
                            onChange={e => setSearchQuery(e.target.value)}
                            className="form-control"
                            style={{ width: '200px', marginLeft: '10px' }}
                            autoFocus
                        />
                    )}

                    {/* Icône appel */}
                    <div className="tooltip-wrapper" style={{ position: 'relative' }}>
                        <MdVideoCall
                            size={24}
                            className="icon"
                            style={{ cursor: 'pointer' }}
                            onClick={handleCallClick}
                            ref={callButtonRef}
                        />
                        <span className="tooltip-text">Appel</span>

                        {showCallMenu && (
                            <div
                                ref={callMenuRef}
                                className="call-menu"
                                style={{
                                    position: 'absolute',
                                    top: '100%',
                                    right: 0,
                                    backgroundColor: '#2e2f2f',
                                    borderRadius: '8px',
                                    boxShadow: '0 4px 8px rgba(0,0,0,0.3)',
                                    zIndex: 1000,
                                    width: '140px',
                                    marginTop: '8px',
                                }}
                            >
                                <div
                                    className="menu-item"
                                    onClick={() => handleCallOptionClick('audio')}
                                    style={{ padding: '10px', cursor: 'pointer', color: 'white' }}
                                >
                                    Appel Audio
                                </div>
                                <div
                                    className="menu-item"
                                    onClick={() => handleCallOptionClick('vidéo')}
                                    style={{ padding: '10px', cursor: 'pointer', color: 'white' }}
                                >
                                    Appel Vidéo
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Menu options contact */}
                    <div className="tooltip-wrapper">
                        <MdMoreVert
                            ref={buttonRef}
                            size={24}
                            className="icon"
                            onClick={() => setMenuOpen(!menuOpen)}
                            style={{ cursor: 'pointer' }}
                        />
                        <span className="tooltip-text">Options</span>
                    </div>

                    {showEditContact && (
                        <EditContactModal
                            contact={contact}
                            onClose={() => setShowEditContact(false)}
                        />
                    )}

                    <ConfirmBlockModal show={showConfirmBlock} onClose={() => setShowConfirmBlock(false)} />
                    <ConfirmSignal show={showConfirmSignal} onClose={closeConfirmSignalModal} />
                    <ConfirmSupprContact
                        show={showConfirmDelete}
                        onClose={closeConfirmDeleteModal}
                        onConfirm={() => {
                            alert("Contact supprimé !");
                            closeConfirmDeleteModal();
                        }}
                    />

                    {menuOpen && (
                        <div ref={menuRef} className="more-menu">
                            <div className="menu-item" onClick={handleEditClick}><MdEdit /> Modifier le contact</div>
                            <div className="menu-item" onClick={handleBlockClick}><MdBlock /> Bloquer le contact</div>
                            <div className="menu-item" onClick={handleReportClick}><MdReportProblem /> Signaler le contact</div>
                            <div className="menu-item" onClick={handleDeleteClick}><MdDelete /> Supprimer le contact</div>
                        </div>
                    )}
                </div>
            </div>

            {/* Modal confirmation suppression message */}
            <ConfirmDeleteMessage
                show={showConfirmDeleteMessage}
                onClose={closeConfirmDeleteMessageModal}
                onConfirm={() => {
                    alert("Message supprimé !");
                    closeConfirmDeleteMessageModal();
                }}
            />

            {/* Modal édition message */}
            <EditMessageModal
                show={showEditMessageModal}
                message={editedMessage}
                setMessage={setEditedMessage}
                onClose={closeEditMessageModal}
                onValidate={validateEditMessage}
            />

            {/* Corps discussion */}
            <div className="chat__body flex-grow-1 p-3 overflow-auto d-flex flex-column gap-2">
                {/* Message reçu avec menu options */}
                <div className="message received" style={{ position: 'relative' }}>
                    <button
                        ref={messageMenuButtonRef}
                        className="message-settings-button"
                        onClick={() => setShowMessageMenu(!showMessageMenu)}
                        aria-label="Paramètres message"
                        style={{
                            position: 'absolute',
                            top: 5,
                            right: -30,
                            background: 'transparent',
                            border: 'none',
                            color: '#abafaf',
                            cursor: 'pointer',
                        }}
                    >
                        <div className="tooltip-wrapper">
                            <MdMoreVert size={24} style={{ cursor: 'pointer' }} />
                            <span className="tooltip-text">Options</span>
                        </div>
                    </button>

                    <p>{editedMessage}</p>

                    {showMessageMenu && (
                        <div
                            ref={messageMenuRef}
                            className="message-settings-menu"
                            style={{
                                position: 'absolute',
                                top: 5,
                                left: 'calc(100% + 8px)',
                                backgroundColor: '#2e2f2f',
                                borderRadius: '8px',
                                boxShadow: '0 4px 8px rgba(0,0,0,0.3)',
                                zIndex: 1000,
                                width: '160px',
                            }}
                        >
                            <div className="menu-item" onClick={handleEditMessageClick}><MdEdit /> Modifier le message</div>
                            <div className="menu-item" onClick={handleDeleteMessageClick}><MdDelete /> Supprimer le message</div>
                        </div>
                    )}
                </div>

                {/* Messages exemples */}
                <div className="message sent"><p>Salut, ça va ?</p></div>
                <div className="message received">
                    <p>Oui super, regarde cette image :</p>
                    <img
                        src="https://randomuser.me/api/portraits/men/32.jpg"
                        alt="photo.png"
                        style={{ maxWidth: "200px", borderRadius: "8px" }}
                    />
                </div>
                <div className="message sent"><p>Parfait, merci !</p></div>
            </div>

            {/* Footer chat */}
            <div className="chat__footer d-flex p-3 align-items-center gap-2">
                <div className="tooltip-wrapper">
                    <BsEmojiSmile size={44} className="icon clickable" style={{ cursor: 'pointer' }} />
                    <span className="tooltip-text2">Emoji</span>
                </div>

                <div className="tooltip-wrapper">
                    <FiPaperclip size={44} className="icon clickable" onClick={handlePaperclipClick} style={{ cursor: 'pointer' }} />
                    <span className="tooltip-text2">Ajouter un fichier</span>
                </div>

                <input type="file" ref={fileInputRef} style={{ display: 'none' }} onChange={handleFileChange} />

                <input type="text" className="form-control me-2" placeholder="Tapez un message" />
                <button className="btn btn-success">Envoyer</button>
            </div>
        </div>
    );
}

export default Chat;
