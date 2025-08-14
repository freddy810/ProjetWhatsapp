import React, { useState } from 'react';
import Login from './Login'; // Assure-toi que le chemin est correct
import ChatWebAvecContacts from './ChatWebAvecContacts'; // Ton composant chat avec contacts

export default function App() {
  const [user, setUser] = useState(null);

  return (
    <>
      {!user ? (
        <Login onLogin={setUser} />
      ) : (
        <>
          <div style={{ padding: 10, backgroundColor: '#0078fe', color: 'white' }}>
            Connecté en tant que : <strong>{user}</strong>
          </div>
          <ChatWebAvecContacts />
        </>
      )}
    </>
  );
}
