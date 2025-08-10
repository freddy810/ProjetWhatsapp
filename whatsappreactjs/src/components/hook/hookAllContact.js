import { useState, useEffect } from 'react';

export default function useAllContacts(utilisateurId) {
    const [contacts, setContacts] = useState([]);
    const [contactsLoading, setContactsLoading] = useState(false);
    const [contactsError, setContactsError] = useState('');

    useEffect(() => {
        if (!utilisateurId) return;

        const fetchContacts = async () => {
            setContactsLoading(true);
            setContactsError('');
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/contacts-utilisateurs/utilisateur/${utilisateurId}`);
                const json = await res.json();

                if (res.ok && json.success) {
                    setContacts(json.data);
                } else {
                    setContactsError(json.message || 'Erreur lors du chargement des contacts.');
                }
            } catch (error) {
                setContactsError('Impossible de charger les contacts.');
            } finally {
                setContactsLoading(false);
            }
        };

        fetchContacts();
    }, [utilisateurId]);

    return { contacts, contactsLoading, contactsError };
}
