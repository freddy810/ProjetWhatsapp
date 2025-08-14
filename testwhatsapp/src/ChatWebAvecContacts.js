import React, { useState, useEffect } from 'react';

export default function ChatWebAvecContacts() {
    // Liste des contacts avec historique de messages
    const [contacts, setContacts] = useState([
        {
            id: 1,
            name: 'Alice',
            messages: [
                { id: 1, text: 'Salut !', sender: 'other' },
                { id: 2, text: 'Bonjour, ça va ?', sender: 'me' },
            ],
        },
        {
            id: 2,
            name: 'Bob',
            messages: [
                { id: 1, text: 'Hey, tu es dispo ?', sender: 'other' },
            ],
        },
        {
            id: 3,
            name: 'Charlie',
            messages: [],
        },
    ]);

    const [selectedContactId, setSelectedContactId] = useState(contacts[0].id);
    const [inputText, setInputText] = useState('');
    const [messages, setMessages] = useState(contacts[0].messages);

    // Met à jour les messages affichés quand on change de contact
    useEffect(() => {
        const contact = contacts.find(c => c.id === selectedContactId);
        setMessages(contact ? contact.messages : []);
        setInputText('');
    }, [selectedContactId, contacts]);

    // Envoie un message et met à jour la liste des messages dans contacts
    const sendMessage = () => {
        if (!inputText.trim()) return;

        const newMessage = {
            id: messages.length + 1,
            text: inputText,
            sender: 'me',
        };

        // Met à jour localement messages
        const updatedMessages = [...messages, newMessage];
        setMessages(updatedMessages);
        setInputText('');

        // Met à jour la liste globale contacts avec les messages modifiés
        setContacts(prevContacts =>
            prevContacts.map(contact =>
                contact.id === selectedContactId
                    ? { ...contact, messages: updatedMessages }
                    : contact
            )
        );
    };

    return (
        <div
            style={{
                display: 'flex',
                maxWidth: 900,
                height: 500,
                margin: '20px auto',
                fontFamily: 'Arial',
                border: '1px solid #ccc',
                borderRadius: 8,
                overflow: 'hidden',
            }}
        >
            {/* Liste des contacts */}
            <div
                style={{
                    width: 250,
                    borderRight: '1px solid #ccc',
                    overflowY: 'auto',
                    backgroundColor: '#f9f9f9',
                }}
            >
                <h3
                    style={{
                        padding: '10px 15px',
                        margin: 0,
                        borderBottom: '1px solid #ddd',
                    }}
                >
                    Contacts
                </h3>
                {contacts.map(contact => (
                    <div
                        key={contact.id}
                        onClick={() => setSelectedContactId(contact.id)}
                        style={{
                            padding: '10px 15px',
                            cursor: 'pointer',
                            backgroundColor:
                                contact.id === selectedContactId ? '#0078fe' : 'transparent',
                            color: contact.id === selectedContactId ? 'white' : 'black',
                            borderBottom: '1px solid #ddd',
                        }}
                    >
                        {contact.name}
                    </div>
                ))}
            </div>

            {/* Zone de chat */}
            <div style={{ flex: 1, display: 'flex', flexDirection: 'column' }}>
                <div
                    style={{
                        padding: 15,
                        borderBottom: '1px solid #ddd',
                        fontWeight: 'bold',
                        backgroundColor: '#eee',
                    }}
                >
                    Chat avec {contacts.find(c => c.id === selectedContactId)?.name || ''}
                </div>

                <div
                    style={{
                        flex: 1,
                        padding: 15,
                        overflowY: 'auto',
                        backgroundColor: '#f5f5f5',
                    }}
                >
                    {messages.length === 0 && (
                        <p style={{ color: '#666', fontStyle: 'italic' }}>
                            Aucun message pour l'instant.
                        </p>
                    )}
                    {messages.map(m => (
                        <div
                            key={m.id}
                            style={{
                                textAlign: m.sender === 'me' ? 'right' : 'left',
                                margin: '10px 0',
                            }}
                        >
                            <span
                                style={{
                                    display: 'inline-block',
                                    padding: 10,
                                    borderRadius: 15,
                                    backgroundColor: m.sender === 'me' ? '#0078fe' : '#ccc',
                                    color: m.sender === 'me' ? 'white' : 'black',
                                    maxWidth: '70%',
                                    wordBreak: 'break-word',
                                }}
                            >
                                {m.text}
                            </span>
                        </div>
                    ))}
                </div>

                <div
                    style={{
                        padding: 10,
                        borderTop: '1px solid #ddd',
                        display: 'flex',
                    }}
                >
                    <input
                        type="text"
                        placeholder="Tapez un message..."
                        value={inputText}
                        onChange={e => setInputText(e.target.value)}
                        onKeyDown={e => {
                            if (e.key === 'Enter') sendMessage();
                        }}
                        style={{
                            flex: 1,
                            padding: 10,
                            fontSize: 16,
                            borderRadius: 20,
                            border: '1px solid #ccc',
                            outline: 'none',
                        }}
                    />
                    <button
                        onClick={sendMessage}
                        style={{
                            marginLeft: 10,
                            padding: '10px 20px',
                            borderRadius: 20,
                            border: 'none',
                            backgroundColor: '#0078fe',
                            color: 'white',
                            fontWeight: 'bold',
                            cursor: 'pointer',
                        }}
                    >
                        Envoyer
                    </button>
                </div>
            </div>
        </div>
    );
}
