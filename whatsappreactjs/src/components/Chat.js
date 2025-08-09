// Import des dépendances React et hooks nécessaires
import React, { useState, useRef, useEffect } from 'react';
// Import du fichier CSS spécifique au chat
import './css/Chat.css';

// Import des icônes utilisées dans l'interface
import { FiPaperclip } from 'react-icons/fi';
import { BsEmojiSmile, BsSearch } from 'react-icons/bs';
import { MdVideoCall, MdMoreVert, MdEdit, MdBlock, MdReportProblem, MdDelete } from 'react-icons/md';

// Import des modaux utilisés dans ce composant
import EditContactModal from './modals/EditContactModal';
import ConfirmBlockModal from './modals/ConfirmBlockModal';
import ConfirmSignal from './modals/ConfirmSignal';
import ConfirmSupprContact from './modals/ConfirmSupprContact';
import EditMessageModal from './modals/EditMessageModal';
import ConfirmDeleteMessage from './modals/ConfirmDeleteMessage'; // ✅ Nouveau modal suppression message

function Chat() {
    // États pour gérer l'ouverture/fermeture des différents menus et modaux
    const [menuOpen, setMenuOpen] = useState(false); // Menu options contact
    const [showEditContact, setShowEditContact] = useState(false); // Modal éditer contact
    const [showConfirmBlock, setShowConfirmBlock] = useState(false); // Confirmation blocage contact
    const [showConfirmSignal, setShowConfirmSignal] = useState(false); // Confirmation signalement contact
    const [showConfirmDelete, setShowConfirmDelete] = useState(false); // Confirmation suppression contact
    const [showMessageMenu, setShowMessageMenu] = useState(false); // Menu options message
    const [showSearchInput, setShowSearchInput] = useState(false); // Affichage barre recherche
    const [searchQuery, setSearchQuery] = useState(''); // Texte de recherche
    const [showConfirmDeleteMessage, setShowConfirmDeleteMessage] = useState(false); // Confirmation suppression message
    const [showEditMessageModal, setShowEditMessageModal] = useState(false); // Modal édition message
    const [editedMessage, setEditedMessage] = useState('Bonjour !'); // Texte message à éditer

    // Références DOM pour détecter clics hors menu/modaux et gérer le focus
    const fileInputRef = useRef(null); // Input type file caché
    const menuRef = useRef(null); // Référence menu options contact
    const buttonRef = useRef(null); // Bouton qui ouvre le menu options contact
    const messageMenuButtonRef = useRef(null); // Bouton qui ouvre menu options message
    const messageMenuRef = useRef(null); // Menu options message

    // Données fictives du contact affiché
    const contact = {
        name: "Jean Dupont",
        image: "https://randomuser.me/api/portraits/men/32.jpg",
        phoneNumbers: ["+33 6 12 34 56 78", "+33 1 23 45 67 89"],
    };

    // Hook useEffect pour fermer le menu contact si clic en dehors
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

    // Hook useEffect pour fermer le menu message si clic en dehors
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

    // Ouvre modal édition contact et ferme menu options
    function handleEditClick() {
        setShowEditContact(true);
        setMenuOpen(false);
    }

    // Ouvre modal confirmation blocage contact
    function handleBlockClick() {
        setShowConfirmBlock(true);
        setMenuOpen(false);
    }

    // Ouvre modal confirmation signalement contact
    function handleReportClick() {
        setShowConfirmSignal(true);
        setMenuOpen(false);
    }

    // Ouvre modal confirmation suppression contact
    function handleDeleteClick() {
        setShowConfirmDelete(true);
        setMenuOpen(false);
    }

    // Ouvre modal confirmation suppression message
    function handleDeleteMessageClick() {
        setShowConfirmDeleteMessage(true);
        setShowMessageMenu(false);
    }

    // Ouvre modal édition message et initialise le texte à éditer
    function handleEditMessageClick() {
        setShowEditMessageModal(true);
        setShowMessageMenu(false);
        setEditedMessage('Bonjour !');
    }

    // Ferme modal suppression contact
    function closeConfirmDeleteModal() {
        setShowConfirmDelete(false);
    }

    // Ferme modal suppression message
    function closeConfirmDeleteMessageModal() {
        setShowConfirmDeleteMessage(false);
    }

    // Ferme modal signalement contact
    function closeConfirmSignalModal() {
        setShowConfirmSignal(false);
    }

    // Ferme modal édition message
    function closeEditMessageModal() {
        setShowEditMessageModal(false);
    }

    // Valide la modification du message (affiche alert ici, à remplacer par logique réelle)
    function validateEditMessage() {
        alert(`Message modifié : ${editedMessage}`);
        closeEditMessageModal();
    }

    // Simule le clic sur input type file caché pour ajout fichier
    function handlePaperclipClick() {
        if (fileInputRef.current) {
            fileInputRef.current.click();
        }
    }

    // Gère la sélection de fichier dans le input file
    function handleFileChange(event) {
        const files = event.target.files;
        if (files.length > 0) {
            alert(`Fichier sélectionné : ${files[0].name}`);
        }
    }

    return (
        <div className="col-md-8 col-12 chatWindow d-flex flex-column p-0">
            {/* Header de la fenêtre de chat avec infos contact et actions */}
            <div className="chat__header w-100 px-3 py-2 d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center gap-2">
                    {/* Avatar du contact */}
                    <img src={contact.image} alt="Profil" className="chat__avatar" />
                    <div>
                        {/* Nom du contact */}
                        <div className="chat__name fw-bold">Jean Dupont</div>
                        {/* Statut en ligne */}
                        <div className="chat__status" style={{ fontSize: "12px" }}>en ligne</div>
                    </div>
                </div>

                {/* Actions du header : recherche, appel, options */}
                <div className="d-flex align-items-center gap-3 position-relative">
                    {/* Bouton recherche avec tooltip */}
                    <div className="tooltip-wrapper">
                        <BsSearch
                            size={20}
                            className="icon"
                            onClick={() => setShowSearchInput(!showSearchInput)}
                            style={{ cursor: 'pointer' }}
                        />
                        <span className="tooltip-text">Rechercher</span>
                    </div>

                    {/* Champ de recherche affiché conditionnellement */}
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

                    {/* Icône appel vidéo */}
                    <div className="tooltip-wrapper">
                        <MdVideoCall size={24} className="icon" style={{ cursor: 'pointer' }} />
                        <span className="tooltip-text">Appel</span>
                    </div>

                    {/* Bouton menu options contact */}
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

                    {/* Modal édition contact */}
                    {showEditContact && (
                        <EditContactModal
                            contact={contact}
                            onClose={() => setShowEditContact(false)}
                        />
                    )}

                    {/* Modal confirmation blocage contact */}
                    <ConfirmBlockModal show={showConfirmBlock} onClose={() => setShowConfirmBlock(false)} />
                    {/* Modal confirmation signalement contact */}
                    <ConfirmSignal show={showConfirmSignal} onClose={closeConfirmSignalModal} />
                    {/* Modal confirmation suppression contact */}
                    <ConfirmSupprContact
                        show={showConfirmDelete}
                        onClose={closeConfirmDeleteModal}
                        onConfirm={() => {
                            alert("Contact supprimé !");
                            closeConfirmDeleteModal();
                        }}
                    />

                    {/* Menu déroulant options contact */}
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

            {/* Corps de la discussion avec messages */}
            <div className="chat__body flex-grow-1 p-3 overflow-auto d-flex flex-column gap-2">

                {/* Message reçu avec bouton options */}
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

                    {/* Texte du message */}
                    <p>{editedMessage}</p>

                    {/* Menu options message */}
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

                {/* Autres messages exemples */}
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

            {/* Footer avec emoji, ajout fichier et champ texte */}
            <div className="chat__footer d-flex p-3 align-items-center gap-2">
                {/* Bouton emoji */}
                <div className="tooltip-wrapper">
                    <BsEmojiSmile size={44} className="icon clickable" style={{ cursor: 'pointer' }} />
                    <span className="tooltip-text2">Emoji</span>
                </div>

                {/* Bouton ajout fichier */}
                <div className="tooltip-wrapper">
                    <FiPaperclip size={44} className="icon clickable" onClick={handlePaperclipClick} style={{ cursor: 'pointer' }} />
                    <span className="tooltip-text2">Ajouter un fichier</span>
                </div>

                {/* Input file caché déclenché par le bouton ci-dessus */}
                <input type="file" ref={fileInputRef} style={{ display: 'none' }} onChange={handleFileChange} />

                {/* Champ texte pour écrire message */}
                <input type="text" className="form-control me-2" placeholder="Tapez un message" />
                <button className="btn btn-success">Envoyer</button>
            </div>
        </div>
    );
}

export default Chat;
