import { useState } from 'react';

export default function useCreateAccount(onClose) {
    const [formData, setFormData] = useState({
        nom: '',
        prenom: '',
        dateNaissance: '',
        telephone: '',
        motDePasse: '',
        confirmeMotDePasse: '',
        photoProfil: null,
    });

    const [preview, setPreview] = useState(null);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const handleChange = (e) => {
        const { name, value, files } = e.target;

        if (name === 'photoProfil') {
            const file = files[0];
            setFormData((prev) => ({ ...prev, photoProfil: file }));
            if (file) {
                const reader = new FileReader();
                reader.onloadend = () => {
                    setPreview(reader.result);
                };
                reader.readAsDataURL(file);
            } else {
                setPreview(null);
            }
        } else {
            setFormData((prev) => ({ ...prev, [name]: value }));
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');

        if (formData.motDePasse !== formData.confirmeMotDePasse) {
            setError("Les mots de passe ne correspondent pas !");
            return;
        }

        const data = new FormData();
        data.append('nom', formData.nom);
        data.append('prenom', formData.prenom);
        data.append('dateNaissance', formData.dateNaissance);
        data.append('telephone', formData.telephone);
        data.append('motDePasse', formData.motDePasse);
        data.append('confirmeMotDePasse', formData.confirmeMotDePasse);
        if (formData.photoProfil) {
            data.append('photoProfil', formData.photoProfil);
        }

        setLoading(true);
        try {
            const response = await fetch('http://127.0.0.1:8000/api/utilisateurs', {
                method: 'POST',
                body: data,
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert("Compte créé avec succès !");
                onClose();
            } else {
                setError(result.message || "Erreur lors de la création du compte");
            }
        } catch (error) {
            setError("Impossible de contacter le serveur");
        } finally {
            setLoading(false);
        }
    };

    return {
        formData,
        preview,
        error,
        loading,
        handleChange,
        handleSubmit,
    };
}
