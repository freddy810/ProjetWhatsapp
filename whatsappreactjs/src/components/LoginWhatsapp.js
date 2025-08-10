import React from 'react';
import { FaWhatsapp } from 'react-icons/fa';
import CreateAccountModal from './modals/CreateAccountModal';
import EditPasswordModal from './modals/EditPasswordModal';
import './css/LoginWhatsapp.css';
import useLogin from './hook/hookLogin';

export default function LoginWhatsapp() {
  const {
    showCreateModal,
    setShowCreateModal,
    showEditPasswordModal,
    setShowEditPasswordModal,
    telephone,
    setTelephone,
    motDePasse,
    setMotDePasse,
    error,
    handleLogin,
  } = useLogin();

  return (
    <div className="login-container">
      <div className="login-box">
        <FaWhatsapp className="whatsapp-icon" />
        <h1 className="mb-4">WhatsApp</h1>

        {error && <p style={{ color: 'red' }}>{error}</p>}

        <input
          type="text"
          placeholder="Numéro de téléphone"
          value={telephone}
          onChange={(e) => setTelephone(e.target.value)}
        />
        <input
          type="password"
          placeholder="Mot de passe"
          value={motDePasse}
          onChange={(e) => setMotDePasse(e.target.value)}
        />

        <button className="login-button" onClick={handleLogin}>
          Se connecter
        </button>

        <a
          href="#forgot"
          className="forgot-link"
          onClick={(e) => {
            e.preventDefault();
            setShowEditPasswordModal(true);
          }}
        >
          Mot de passe oublié ?
        </a>
        <hr />
        <button
          className="create-account-button"
          onClick={() => setShowCreateModal(true)}
        >
          Créer un compte
        </button>
      </div>

      <div className="footer-text">
        <p>WhatsApp - Clone interface de login</p>
      </div>

      {showCreateModal && <CreateAccountModal onClose={() => setShowCreateModal(false)} />}
      {showEditPasswordModal && <EditPasswordModal onClose={() => setShowEditPasswordModal(false)} />}
    </div>
  );
}
