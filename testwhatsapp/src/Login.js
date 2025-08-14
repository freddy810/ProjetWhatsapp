import React, { useState } from 'react';

export default function Login({ onLogin }) {
    const [username, setUsername] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        if (username.trim()) {
            onLogin(username.trim());
        }
    };

    return (
        <div style={{
            maxWidth: 300,
            margin: '100px auto',
            padding: 20,
            border: '1px solid #ccc',
            borderRadius: 8,
            fontFamily: 'Arial',
            textAlign: 'center',
            boxShadow: '0 0 10px rgba(0,0,0,0.1)'
        }}>
            <h2>Connexion</h2>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    placeholder="Nom d'utilisateur"
                    value={username}
                    onChange={e => setUsername(e.target.value)}
                    style={{
                        width: '100%',
                        padding: 10,
                        marginBottom: 15,
                        fontSize: 16,
                        borderRadius: 5,
                        border: '1px solid #ccc',
                        outline: 'none'
                    }}
                />
                <button type="submit" style={{
                    width: '100%',
                    padding: 10,
                    backgroundColor: '#0078fe',
                    color: 'white',
                    border: 'none',
                    borderRadius: 5,
                    fontWeight: 'bold',
                    cursor: 'pointer'
                }}>
                    Se connecter
                </button>
            </form>
        </div>
    );
}
