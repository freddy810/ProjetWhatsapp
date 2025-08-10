import React, { useState, useRef, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './css/Sidebars.css';
import { FiSearch } from 'react-icons/fi';
import { MdChat, MdSettings, MdLogout, MdEdit, MdNotifications, MdPeople } from 'react-icons/md';
import { CgProfile } from 'react-icons/cg';
import { AiOutlineUserAdd } from 'react-icons/ai';

import EditProfileModal from './modals/EditProfilModal';
import AddContactModal from './modals/AddContactModal';
import AllUsersModal from './modals/AllUsersModal';

import useAllContacts from './hook/hookAllContact';
import useUtilisateurIdsParPhone from './hook/hookIdUtilisateurPhone';

function Sidebar({ utilisateur }) {
    const navigate = useNavigate();

    const [showSettings, setShowSettings] = useState(false);
    const [showLogoutConfirm, setShowLogoutConfirm] = useState(false);
    const [showEditProfile, setShowEditProfile] = useState(false);
    const [showAddContactModal, setShowAddContactModal] = useState(false);
    const [showAllUsersModal, setShowAllUsersModal] = useState(false);

    const [derniersMessages, setDerniersMessages] = useState({});

    const settingsModalRef = useRef(null);
    const logoutModalRef = useRef(null);

    // Chargement des contacts avec hook personnalisé
    const { contacts, contactsLoading, contactsError } = useAllContacts(utilisateur?.id);

    // Utilisation du hook pour récupérer les utilisateur_id par numéro de téléphone
    const utilisateurIds = useUtilisateurIdsParPhone(contacts);

    // Toggle menu paramètres
    const toggleSettings = () => setShowSettings(!showSettings);

    const openEditProfile = () => {
        setShowEditProfile(true);
        setShowSettings(false);
    };
    const closeEditProfile = () => setShowEditProfile(false);

    const openAddContactModal = () => setShowAddContactModal(true);
    const closeAddContactModal = () => setShowAddContactModal(false);

    const handleVoirUtilisateurs = () => {
        setShowAllUsersModal(true);
        setShowSettings(false);
    };
    const closeAllUsersModal = () => setShowAllUsersModal(false);

    // Gestion clic en dehors menu paramètres
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
        return () => document.removeEventListener('mousedown', handleClickOutsideSettings);
    }, [showSettings]);

    // Gestion clic en dehors modal logout
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

    const handleLogoutClick = () => {
        setShowLogoutConfirm(true);
        setShowSettings(false);
    };
    const confirmLogout = () => {
        alert("Déconnecté !");
        setShowLogoutConfirm(false);
        navigate('/login');
    };
    const cancelLogout = () => setShowLogoutConfirm(false);

    // Fonction pour récupérer le dernier message via API
    async function fetchDernierMessage(utilisateur1_id, utilisateur2_id, contactId) {
        try {
            const response = await fetch(
                `http://127.0.0.1:8000/api/messages/dernier-message/${utilisateur1_id}/${utilisateur2_id}`
            );
            const data = await response.json();

            if (data.success) {
                setDerniersMessages(prev => ({
                    ...prev,
                    [contactId]: data.dernier_message_id,
                }));
            } else {
                setDerniersMessages(prev => ({
                    ...prev,
                    [contactId]: null,
                }));
            }
        } catch (error) {
            console.error("Erreur fetch dernier message :", error);
            setDerniersMessages(prev => ({
                ...prev,
                [contactId]: null,
            }));
        }
    }

    // Appeler fetchDernierMessage pour chaque contact à chaque chargement ou mise à jour des contacts/utilisateur
    useEffect(() => {
        if (contacts && utilisateur?.id) {
            contacts.forEach(contact => {
                fetchDernierMessage(utilisateur.id, contact.id, contact.id);
            });
        }
    }, [contacts, utilisateur]);

    return (
        <div className="col-md-4 col-12 sidebar d-flex justify-content-around h-100 p-0">
            <div className='row m-0 w-100'>

                {/* Partie gauche sidebar */}
                <div className="col-md-2 sidebar__left d-flex flex-column align-items-center py-3">

                    <div className="position-relative mb-4">
                        <div className="tooltip-wrapper">
                            <MdChat size={24} className="sidebar__icon" />
                            <span className="tooltip-text4">Nouveaux Messages</span>
                            <span className="badge-new-msg">1</span>
                        </div>
                    </div>

                    <div className="tooltip-wrapper">
                        <CgProfile size={24} className="sidebar__icon mb-4" />
                        <span className="tooltip-text3">Listes Contacts</span>
                    </div>

                    <div className="tooltip-wrapper">
                        <MdNotifications size={24} className="sidebar__icon mb-4" />
                        <span className="tooltip-text3">Notifications</span>
                    </div>

                    <div className="sidebar__settings-container mt-auto position-relative">
                        <div className="tooltip-wrapper">
                            <MdSettings size={30} className="sidebar__icon" onClick={toggleSettings} />
                            <span className="tooltip-text">Paramètres</span>
                        </div>

                        {showSettings && (
                            <div className="modal-content small-left" ref={settingsModalRef}>
                                <h5>Paramètres</h5>
                                <ul className="list-unstyled mt-3">
                                    <li className="mb-2 d-flex align-items-center gap-2 clickable" onClick={openEditProfile}>
                                        <MdEdit size={20} /> Modifier le profil
                                    </li>
                                    <li className="mb-2 d-flex align-items-center gap-2 clickable" onClick={handleVoirUtilisateurs}>
                                        <MdPeople size={20} /> Voir la liste des utilisateurs
                                    </li>
                                    <li className="mb-2 d-flex align-items-center gap-2 clickable" onClick={handleLogoutClick}>
                                        <MdLogout size={20} /> Se déconnecter
                                    </li>
                                </ul>
                            </div>
                        )}

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

                    {/* Affichage ID utilisateur connecté */}
                    {utilisateur && (
                        <div className="mt-3 text-center text-white">
                            <small>ID Utilisateur :</small>
                            <p>{utilisateur.id}</p>
                        </div>
                    )}

                    {/* Avatar dynamique ou fallback */}
                    <img
                        src={utilisateur?.photoProfil
                            ? `http://127.0.0.1:8000/images/profils/${utilisateur.photoProfil}`
                            : "https://i.pravatar.cc/40?img=1"}
                        alt="avatar"
                        className="w-50 rounded-circle sidebar__icon mt-4"
                    />
                </div>

                {/* Partie droite sidebar */}
                <div className='col-md-10'>

                    <div className="sidebar__header w-100 px-3 py-3 d-flex justify-content-between align-items-center">
                        <h5 className="m-0">WhatsApp</h5>

                        <div className="tooltip-wrapper">
                            <AiOutlineUserAdd
                                size={38}
                                className="icon clickable"
                                onClick={() => setShowAddContactModal(true)}
                            />
                            <span className="tooltip-text">Nouvelle Contact</span>
                        </div>

                        {showAddContactModal && <AddContactModal onClose={() => setShowAddContactModal(false)} />}
                    </div>

                    <div className="sidebar__search p-2 position-relative">
                        <span className="search-text-icon"><FiSearch /></span>
                        <input
                            type="text"
                            className="form-control ps-5"
                            placeholder="Rechercher ou démarrer une discussion"
                        />
                    </div>

                    <div className="sidebar__messages__options d-flex py-2">
                        <a href="#" className="btn-active" onClick={e => e.preventDefault()}>Toutes</a>
                        <a href="#" className="btn-non-active ms-2" onClick={e => e.preventDefault()}>Non lues</a>
                        <a href="#" className="btn-non-active ms-2" onClick={e => e.preventDefault()}>Favoris</a>
                    </div>

                    {/* Affichage dynamique des contacts utilisateurs */}
                    <div className="sidebar__chats">
                        {contactsLoading && <p>Chargement des contacts...</p>}
                        {contactsError && <p style={{ color: 'red' }}>{contactsError}</p>}

                        {!contactsLoading && contacts.length === 0 && (
                            <p>Aucun contact trouvé.</p>
                        )}

                        {contacts.map((contact) => (
                            <div key={contact.id} className="chat d-flex align-items-center p-3">
                                <img
                                    src={contact.possedeurMessage?.photoProfil
                                        ? `http://127.0.0.1:8000/images/profils/${contact.possedeurMessage.photoProfil}`
                                        : "https://i.pravatar.cc/40?img=1"}
                                    alt="avatar"
                                    className="rounded-circle me-3"
                                />
                                <div>
                                    <h6 className="mb-0">{contact.nom} {contact.prenom}</h6>
                                    <small className="last_chat">
                                        {derniersMessages[contact.id]
                                            ? `Dernier message ID : ${derniersMessages[contact.id]}`
                                            : "Pas de message"}
                                    </small>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

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

                {showAllUsersModal && <AllUsersModal onClose={closeAllUsersModal} />}
            </div>
        </div>
    );
}

export default Sidebar;
