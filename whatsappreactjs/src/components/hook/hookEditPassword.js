import { useState } from 'react';

export default function useEditPassword(onClose) {
    const [formData, setFormData] = useState({
        telephone: '',
        nouveauMotDePasse: '',
        confirmeNouveauMotDePasse: '',
    });

    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');

        if (formData.nouveauMotDePasse !== formData.confirmeNouveauMotDePasse) {
            setError("Les nouveaux mots de passe ne correspondent pas !");
            return;
        }

        setLoading(true);

        try {
            const response = await fetch('http://127.0.0.1:8000/api/changer-mot-de-passe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    telephone: formData.telephone,
                    nouveau: formData.nouveauMotDePasse,
                    nouveau_confirmation: formData.confirmeNouveauMotDePasse,
                }),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert("Mot de passe modifié avec succès !");
                onClose();
            } else {
                setError(result.message || "Erreur lors du changement du mot de passe.");
            }
        } catch (error) {
            setError("Impossible de contacter le serveur.");
        } finally {
            setLoading(false);
        }
    };

    return {
        formData,
        error,
        loading,
        handleChange,
        handleSubmit,
    };
}
