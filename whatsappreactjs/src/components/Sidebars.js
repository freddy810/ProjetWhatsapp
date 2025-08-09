import React, { useState, useRef, useEffect } from 'react';
// Import des styles CSS spécifiques à la sidebar
import './css/Sidebars.css';

// Import des icônes utilisées dans la sidebar
import { FiSearch } from 'react-icons/fi';
import { MdChat, MdDonutLarge, MdSettings, MdLogout, MdEdit, MdNotifications, MdPeople } from 'react-icons/md';
import { CgProfile } from 'react-icons/cg';
import { AiOutlineUserAdd } from 'react-icons/ai';

// Import des composants modaux utilisés dans la sidebar
import EditProfileModal from './modals/EditProfilModal';
import AddContactModal from './modals/AddContactModal';
import AllUsersModal from './modals/AllUsersModal';  // Modal pour afficher la liste des utilisateurs

function Sidebar() {
    // États pour gérer l'affichage des différents modaux et menus
    const [showSettings, setShowSettings] = useState(false); // Affichage du menu paramètres
    const [showLogoutConfirm, setShowLogoutConfirm] = useState(false); // Confirmation de déconnexion
    const [showEditProfile, setShowEditProfile] = useState(false); // Modal édition profil
    const [showAddContactModal, setShowAddContactModal] = useState(false); // Modal ajout contact
    const [showAllUsersModal, setShowAllUsersModal] = useState(false); // Modal liste utilisateurs

    // Références vers les éléments DOM des modaux pour gérer les clics hors modaux
    const settingsModalRef = useRef(null);
    const logoutModalRef = useRef(null);

    // Fonction pour basculer l'affichage du menu paramètres
    const toggleSettings = () => setShowSettings(!showSettings);

    // Ouvre le modal d'édition de profil et ferme les paramètres
    const openEditProfile = () => {
        setShowEditProfile(true);
        setShowSettings(false);
    };

    // Ferme le modal d'édition de profil
    const closeEditProfile = () => setShowEditProfile(false);

    // Ouvre le modal d'ajout de contact
    const openAddContactModal = () => setShowAddContactModal(true);

    // Ferme le modal d'ajout de contact
    const closeAddContactModal = () => setShowAddContactModal(false);

    // Ouvre le modal affichant la liste des utilisateurs et ferme les paramètres
    const handleVoirUtilisateurs = () => {
        setShowAllUsersModal(true);
        setShowSettings(false);
    };

    // Ferme le modal liste utilisateurs
    const closeAllUsersModal = () => setShowAllUsersModal(false);

    // Hook pour gérer la fermeture du menu paramètres quand on clique en dehors
    useEffect(() => {
        const handleClickOutsideSettings = (event) => {
            if (settingsModalRef.current && !settingsModalRef.current.contains(event.target)) {
                setShowSettings(false);
            }
        };
        if (showSettings) {
            document.addEventListener('mousedown', handleClickOutsideSettings);
        } else {
            document.removeEventListener('mousedown', handleClickOutsideSettings);
        }
        // Nettoyage à la désactivation ou au démontage du composant
        return () => document.removeEventListener('mousedown', handleClickOutsideSettings);
    }, [showSettings]);

    // Hook pour gérer la fermeture du modal de confirmation déconnexion en cliquant en dehors
    useEffect(() => {
        const handleClickOutsideLogout = (event) => {
            if (logoutModalRef.current && !logoutModalRef.current.contains(event.target)) {
                setShowLogoutConfirm(false);
            }
        };
        if (showLogoutConfirm) {
            document.addEventListener('mousedown', handleClickOutsideLogout);
        } else {
            document.removeEventListener('mousedown', handleClickOutsideLogout);
        }
        return () => document.removeEventListener('mousedown', handleClickOutsideLogout);
    }, [showLogoutConfirm]);

    // Ouvre le modal de confirmation déconnexion et ferme les paramètres
    const handleLogoutClick = () => {
        setShowLogoutConfirm(true);
        setShowSettings(false);
    };

    // Confirme la déconnexion (ici juste une alerte, tu peux remplacer par la vraie logique)
    const confirmLogout = () => {
        alert("Déconnecté !");
        setShowLogoutConfirm(false);
    };

    // Annule la déconnexion (ferme le modal)
    const cancelLogout = () => setShowLogoutConfirm(false);

    return (
        <div className="col-md-4 col-12 sidebar d-flex justify-content-around h-100 p-0">
            <div className='row m-0 w-100'>

                {/* Partie gauche de la sidebar avec les icônes principales */}
                <div className="col-md-2 sidebar__left d-flex flex-column align-items-center py-3">

                    {/* Icône messages avec badge notification */}
                    <div className="position-relative mb-4">
                        <div className="tooltip-wrapper">
                            <MdChat size={24} className="sidebar__icon" />
                            <span className="tooltip-text4">Nouveaux Messages</span>
                            <span className="badge-new-msg">1</span>
                        </div>
                    </div>

                    {/* Icône liste contacts */}
                    <div className="tooltip-wrapper">
                        <CgProfile size={24} className="sidebar__icon mb-4" />
                        <span className="tooltip-text3">Listes Contacts</span>
                    </div>

                    {/* Icône notifications */}
                    <div className="tooltip-wrapper">
                        <MdNotifications size={24} className="sidebar__icon mb-4" />
                        <span className="tooltip-text3">Notifications</span>
                    </div>

                    {/* Menu paramètres et modaux liés */}
                    <div className="sidebar__settings-container mt-auto position-relative">
                        <div className="tooltip-wrapper">
                            {/* Icône paramètres : clic pour ouvrir/fermer */}
                            <MdSettings size={30} className="sidebar__icon" onClick={toggleSettings} />
                            <span className="tooltip-text">Paramètres</span>
                        </div>

                        {/* Menu déroulant des paramètres */}
                        {showSettings && (
                            <div className="modal-content small-left" ref={settingsModalRef}>
                                <h5>Paramètres</h5>
                                <ul className="list-unstyled mt-3">
                                    {/* Option modifier profil */}
                                    <li className="mb-2 d-flex align-items-center gap-2 clickable" onClick={openEditProfile}>
                                        <MdEdit size={20} /> Modifier le profil
                                    </li>
                                    {/* Option voir la liste des utilisateurs */}
                                    <li className="mb-2 d-flex align-items-center gap-2 clickable" onClick={handleVoirUtilisateurs}>
                                        <MdPeople size={20} /> Voir la liste des utilisateurs
                                    </li>
                                    {/* Option déconnexion */}
                                    <li className="mb-2 d-flex align-items-center gap-2 clickable" onClick={handleLogoutClick}>
                                        <MdLogout size={20} /> Se déconnecter
                                    </li>
                                </ul>
                            </div>
                        )}

                        {/* Modal confirmation de déconnexion */}
                        {showLogoutConfirm && (
                            <div className="modal-overlay">
                                <div className="modal-content logout-confirm" ref={logoutModalRef}>
                                    <h5>Confirmation</h5>
                                    <p>Voulez-vous vraiment vous déconnecter ?</p>
                                    <div className="d-flex justify-content-center gap-2 mt-3">
                                        <button className="btn btn-secondary" onClick={cancelLogout}>Non</button>
                                        <button className="btn btn-danger" onClick={confirmLogout}>Oui</button>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Avatar utilisateur en bas */}
                    <img src="https://i.pravatar.cc/40?img=1" alt="avatar" className="w-50 rounded-circle sidebar__icon mt-4" />
                </div>

                {/* Partie droite de la sidebar avec header, recherche et discussions */}
                <div className='col-md-10'>

                    {/* Header avec titre et bouton ajout contact */}
                    <div className="sidebar__header w-100 px-3 py-3 d-flex justify-content-between align-items-center">
                        <h5 className="m-0">WhatsApp</h5>

                        {/* Bouton pour ouvrir modal ajout contact */}
                        <div className="tooltip-wrapper">
                            <AiOutlineUserAdd
                                size={38}
                                className="icon clickable"
                                onClick={openAddContactModal}
                            />
                            <span className="tooltip-text">Nouvelle Contact</span>
                        </div>

                        {/* Modal ajout contact */}
                        {showAddContactModal && <AddContactModal onClose={closeAddContactModal} />}
                    </div>

                    {/* Barre de recherche des discussions */}
                    <div className="sidebar__search p-2 position-relative">
                        <span className="search-text-icon"><FiSearch /></span>
                        <input
                            type="text"
                            className="form-control ps-5"
                            placeholder="Rechercher ou démarrer une discussion"
                        />
                    </div>

                    {/* Options de filtrage des messages */}
                    <div className="sidebar__messages__options d-flex py-2">
                        <a href="#" className="btn-active" onClick={e => e.preventDefault()}>Toutes</a>
                        <a href="#" className="btn-non-active ms-2" onClick={e => e.preventDefault()}>Non lues</a>
                        <a href="#" className="btn-non-active ms-2" onClick={e => e.preventDefault()}>Favoris</a>
                    </div>

                    {/* Liste des discussions */}
                    <div className="sidebar__chats">

                        {/* Exemple de discussion 1 */}
                        <div className="chat d-flex align-items-center p-3">
                            <img src="https://i.pravatar.cc/40?img=1" alt="avatar" className="rounded-circle me-3" />
                            <div>
                                <h6 className="mb-0">Jean Dupont</h6>
                                <small className="last_chat">Salut, comment ça va ?</small>
                            </div>
                        </div>

                        {/* Exemple de discussion 2 */}
                        <div className="chat d-flex align-items-center p-3">
                            <img src="https://i.pravatar.cc/40?img=2" alt="avatar" className="rounded-circle me-3" />
                            <div className="flex-grow-1">
                                <div className="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 className="mb-0">Lucie</h6>
                                        <small className="last_chat">On se voit demain ?</small>
                                    </div>
                                    <div className="chat-meta text-end mt-0">
                                        <p className="chat-date mt-2">12/02/2003</p>
                                        <span className="badge-unread">1</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {/* Modal édition du profil affiché conditionnellement */}
                {showEditProfile && (
                    <EditProfileModal
                        contact={{
                            firstName: "Jean",
                            lastName: "Dupont",
                            image: "https://i.pravatar.cc/150?img=1",
                            phoneNumbers: ["+33 6 12 34 56 78", "+33 1 23 45 67 89"],
                        }}
                        onClose={closeEditProfile}
                    />
                )}

                {/* Modal liste des utilisateurs affiché conditionnellement */}
                {showAllUsersModal && <AllUsersModal onClose={closeAllUsersModal} />}
            </div>
        </div>
    );
}

export default Sidebar;
