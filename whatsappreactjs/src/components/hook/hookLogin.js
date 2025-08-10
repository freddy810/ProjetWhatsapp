// useLogin.js (ajout d'un state pour l'utilisateur connecté)
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';

export default function useLogin() {
    const navigate = useNavigate();

    const [showCreateModal, setShowCreateModal] = useState(false);
    const [showEditPasswordModal, setShowEditPasswordModal] = useState(false);

    const [telephone, setTelephone] = useState('');
    const [motDePasse, setMotDePasse] = useState('');
    const [error, setError] = useState('');
    const [utilisateur, setUtilisateur] = useState(null); // <-- nouvel état utilisateur

    const handleLogin = async () => {
        try {
            const response = await fetch('http://127.0.0.1:8000/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ telephone, motDePasse }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                localStorage.setItem('utilisateur', JSON.stringify(data.data));
                setUtilisateur(data.data); // stocker utilisateur en state
                navigate('/home', { state: { utilisateur: data.data } }); // passer via route aussi
            } else {
                setError(data.message || 'Erreur de connexion');
            }
        } catch (err) {
            setError('Impossible de se connecter au serveur');
        }
    };

    return {
        showCreateModal,
        setShowCreateModal,
        showEditPasswordModal,
        setShowEditPasswordModal,
        telephone,
        setTelephone,
        motDePasse,
        setMotDePasse,
        error,
        setError,
        handleLogin,
        utilisateur,  // expose utilisateur connecté
    };
}
