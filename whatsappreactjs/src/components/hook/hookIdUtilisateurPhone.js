import { useState, useEffect } from 'react';

function useUtilisateurIdsParPhone(contacts) {
    const [utilisateurIds, setUtilisateurIds] = useState({});

    // Fonction pour récupérer utilisateur_id via API Laravel
    const fetchUtilisateurIdParNumPhone = async (numPhone) => {
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/contacts-utilisateurs/utilisateur-id-par-numphone/${numPhone}`);
            if (!response.ok) throw new Error('Utilisateur non trouvé');
            const data = await response.json();
            return data.utilisateur_id;
        } catch (error) {
            return null;
        }
    };

    useEffect(() => {
        if (!contacts || contacts.length === 0) {
            setUtilisateurIds({});
            return;
        }

        Promise.all(
            contacts.map(contact =>
                fetchUtilisateurIdParNumPhone(contact.numPhone)
                    .then(utilisateur_id => ({ contactId: contact.id, utilisateur_id }))
            )
        ).then(results => {
            const newIds = {};
            results.forEach(({ contactId, utilisateur_id }) => {
                if (utilisateur_id) {
                    newIds[contactId] = utilisateur_id;
                }
            });
            setUtilisateurIds(newIds);
        });
    }, [contacts]);

    return utilisateurIds;
}

export default useUtilisateurIdsParPhone;
