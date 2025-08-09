import React, { useState } from 'react';
import '../css/Sidebars.css';

function AllUsersModal({ onClose }) {
    // Exemple d’utilisateurs fictifs avec les nouvelles propriétés
    const [users, setUsers] = useState([
        {
            id: 1,
            nom: "Dupont",
            type: "Admin",
            prenom: "Jean",
            dateNaissance: "1980-04-15",
            telephone: "+33 6 12 34 56 78",
            photoProfil: "https://i.pravatar.cc/40?img=1",
            status: "Actif"
        },
        {
            id: 2,
            nom: "Martin",
            type: "Utilisateur",
            prenom: "Lucie",
            dateNaissance: "1992-10-22",
            telephone: "+33 6 98 76 54 32",
            photoProfil: "", // Pas de photo
            status: "Inactif"
        },
        {
            id: 3,
            nom: "Durand",
            type: "Modérateur",
            prenom: "Paul",
            dateNaissance: "1985-07-03",
            telephone: "+33 1 23 45 67 89",
            photoProfil: "https://i.pravatar.cc/40?img=3",
            status: "Actif"
        },
    ]);

    const handleDelete = (id) => {
        if (window.confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
            setUsers(users.filter(user => user.id !== id));
        }
    };

    // Fonction pour récupérer la première lettre majuscule d’un nom
    const getInitial = (name) => {
        if (!name) return '';
        return name.charAt(0).toUpperCase();
    };

    return (
        <div className="modal-overlay">
            <div className="modal-content all-users-modal">
                <div className="d-flex justify-content-between mb-3">
                    <h5>Liste des utilisateurs</h5>
                    <div className="text-end">
                        <button className="btn btn-secondary" onClick={onClose}>Fermer</button>
                    </div>
                </div>
                <div className="modal-body">
                    <table className="table table-striped">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Prénom</th>
                                <th>Date de Naissance</th>
                                <th>Téléphone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {users.length === 0 ? (
                                <tr><td colSpan="8" className="text-center">Aucun utilisateur</td></tr>
                            ) : (
                                users.map(user => (
                                    <tr key={user.id}>
                                        <td>
                                            {user.photoProfil ? (
                                                <img
                                                    src={user.photoProfil}
                                                    alt={`${user.prenom} ${user.nom}`}
                                                    className="photo-profil"
                                                />
                                            ) : (
                                                <div className="photo-profil-placeholder">
                                                    {getInitial(user.nom)}
                                                </div>
                                            )}
                                        </td>
                                        <td>{user.nom}</td>
                                        <td>{user.type}</td>
                                        <td>{user.prenom}</td>
                                        <td>{user.dateNaissance}</td>
                                        <td>{user.telephone}</td>
                                        <td>{user.status}</td>
                                        <td>
                                            <button
                                                className="btn btn-danger btn-sm"
                                                onClick={() => handleDelete(user.id)}
                                            >
                                                Supprim
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}

export default AllUsersModal;
