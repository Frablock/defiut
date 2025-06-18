import React, { useState } from "react";
import CustomButton from "../utils/CustomButton";
import SVGDispatcher from "../utils/Utils";
import { Fade, Alert, FormGroup, Label, Input, FormText } from "reactstrap";
import Markdown from 'react-markdown';

export default function CreateDefis(props) {
    const [formData, setFormData] = useState({
        nom: '',
        desc: '',
        diff: '',
        key: '',
        score: '',
        category: '',
        tags: []
    });
    const [errors, setErrors] = useState({});
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [submitMessage, setSubmitMessage] = useState({ type: '', message: '' });
    const [viewSize, setViewSize] = useState("0");
    const [showPreview, setShowPreview] = useState(false);

    // Existing useEffect for size calculation here...

    // Styles for dark/light modes
    const getStyle = () => ({
        backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
        color: props.isDarkMode ? "white" : "black",
        border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`
    });

    const validateForm = () => {
        const newErrors = {};

        if (!formData.nom.trim()) {
            newErrors.nom = "Le nom du défi est obligatoire";
        } else if (formData.nom.length < 3) {
            newErrors.nom = "Le nom doit contenir au moins 3 caractères";
        }

        if (!formData.desc.trim()) {
            newErrors.desc = "La description est obligatoire";
        } else if (formData.desc.length < 10) {
            newErrors.desc = "La description doit contenir au moins 10 caractères";
        }

        if (!formData.diff) {
            newErrors.diff = "La difficulté est obligatoire";
        }

        if (!formData.key.trim()) {
            newErrors.key = "La clé/flag est obligatoire";
        } else if (formData.key.length < 5) {
            newErrors.key = "La clé doit contenir au moins 5 caractères";
        }

        if (!formData.score) {
            newErrors.score = "Le score est obligatoire";
        } else if (isNaN(formData.score) || parseInt(formData.score) < 1) {
            newErrors.score = "Le score doit être un nombre positif";
        }

        if (!formData.category.trim()) {
            newErrors.category = "La catégorie est obligatoire";
        }

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleInputChange = (field, value) => {
        setFormData(prev => ({ ...prev, [field]: value }));

        if (errors[field]) {
            setErrors(prev => ({
                ...prev,
                [field]: ''
            }));
        }
    };

    const handleTagsChange = (e) => {
        const newTag = e.target.value;
        if (e.key === 'Enter' && newTag.trim()) {
            setFormData(prev => ({
                ...prev,
                tags: [...prev.tags, newTag.trim()]
            }));
            e.target.value = ''; // Clear the input after adding
        }
    };

    const handleRemoveTag = (tagToRemove) => {
        setFormData(prev => ({
            ...prev,
            tags: prev.tags.filter(tag => tag !== tagToRemove)
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        setIsSubmitting(true);
        setSubmitMessage({ type: '', message: '' });

        try {
            const response = await props.sendData({
                route: "/defi_add",
                method: "POST",
                data: {
                    nom: formData.nom.trim(),
                    desc: formData.desc.trim(),
                    diff: formData.diff,
                    key: formData.key.trim(),
                    score: parseInt(formData.score),
                    category: formData.category.trim(),
                    tags: formData.tags
                }
            });

            if (response.error) {
                setSubmitMessage({
                    type: 'error',
                    message: response.error_message || "Erreur lors de la création du défi"
                });
            } else {
                setSubmitMessage({
                    type: 'success',
                    message: "Défi créé avec succès !"
                });
                setFormData({
                    nom: '',
                    desc: '',
                    diff: '',
                    key: '',
                    score: '',
                    category: '',
                    tags: []
                });
            }
        } catch (error) {
            setSubmitMessage({
                type: 'error',
                message: "Erreur de connexion. Veuillez réessayer."
            });
        } finally {
            setIsSubmitting(false);
        }
    };

    const difficultyOptions = [
        { value: 1, label: 'Facile' },
        { value: 2, label: 'Moyen' },
        { value: 3, label: 'Difficile' },
        { value: 4, label: 'Expert' }
    ];

    return (
        <Fade in={!props.unmount} className="w-100 h-100">
            <div 
                className="d-flex flex-column w-100 p-5 transition"
                style={{
                    color: props.isDarkMode ? "white" : "black",
                    backgroundColor: props.isDarkMode ? "#434343" : "#f2f2f2",
                    overflowY: "auto",
                    height: `calc(100vh - ${20 + viewSize}px)`
                }}
            >
                <div className="container-fluid">
                    <div className="row justify-content-center">
                        <div className="col-lg-8 col-md-10">
                            <h1 className="mb-4 text-center" style={{ fontWeight: "bold" }}>
                                Créer un nouveau défi
                            </h1>

                            {submitMessage.message && (
                                <Alert 
                                    color={submitMessage.type === 'success' ? 'success' : 'danger'}
                                    className="mb-4"
                                >
                                    {submitMessage.message}
                                </Alert>
                            )}

                            <form onSubmit={handleSubmit}>
                                <div className="row">
                                    <div className="col-md-6 mb-4">
                                        <label 
                                            className="form-label fw-bold"
                                            style={{color: props.isDarkMode ? "white" : "black"}}
                                        >
                                            Nom du défi *
                                        </label>
                                        <input
                                            type="text"
                                            className={`form-control ${errors.nom ? 'is-invalid' : ''} ${props.isDarkMode ? 'dark-mode-placeholder' : 'light-mode-placeholder'}`}
                                            value={formData.nom}
                                            onChange={(e) => handleInputChange('nom', e.target.value)}
                                            placeholder="Ex: Injection SQL basique"
                                            style={{
                                                backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                                color: props.isDarkMode ? "white" : "black",
                                                border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`
                                            }}
                                        />
                                        {errors.nom && (
                                            <div className="invalid-feedback d-block">
                                                {errors.nom}
                                            </div>
                                        )}
                                    </div>

                                    <div className="col-md-6 mb-4">
                                        <label 
                                            className="form-label fw-bold"
                                            style={{color: props.isDarkMode ? "white" : "black"}}
                                        >
                                            Difficulté *
                                        </label>
                                        <select
                                            className={`form-select ${errors.diff ? 'is-invalid' : ''}`}
                                            value={formData.diff}
                                            onChange={(e) => handleInputChange('diff', e.target.value)}
                                            style={{
                                                backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                                color: props.isDarkMode ? "white" : "black",
                                                border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`
                                            }}
                                        >
                                            <option 
                                                value=""
                                                style={{
                                                    backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                                    color: props.isDarkMode ? "#999" : "#6c757d"
                                                }}
                                            >
                                                Sélectionner une difficulté
                                            </option>
                                            {difficultyOptions.map(option => (
                                                <option 
                                                    key={option.value} 
                                                    value={option.value}
                                                    style={{
                                                        backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                                        color: props.isDarkMode ? "white" : "black"
                                                    }}
                                                >
                                                    {option.label}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.diff && (
                                            <div className="invalid-feedback d-block">
                                                {errors.diff}
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="d-flex w-100 gap-4">
                                    <FormGroup className="mb-4 w-100">
                                        <Label className="fw-bold" style={{ color: props.isDarkMode ? "white" : "black" }}>
                                            Catégorie *
                                        </Label>
                                        <Input
                                            type="text"
                                            className={errors.category ? 'is-invalid' : ''}
                                            value={formData.category}
                                            onChange={(e) => handleInputChange('category', e.target.value)}
                                            placeholder="Ex: Sécurité, Réseaux"
                                            style={getStyle()}
                                        />
                                        {errors.category && (
                                            <div className="invalid-feedback d-block">
                                                {errors.category}
                                            </div>
                                        )}
                                    </FormGroup>

                                    <FormGroup className="mb-4 w-100">
                                        <Label className="fw-bold d-flex flex-row gap-4" style={{ color: props.isDarkMode ? "white" : "black" }}>
                                            Tags *
                                            <small style={{color:"white"}}>Appuyer sur Entrée pour ajouter.</small>
                                        </Label>
                                        <Input
                                            type="text"
                                            onKeyDown={handleTagsChange}
                                            placeholder="Ex: sécurité, réseau"
                                            style={getStyle()}
                                        />
                                        <div className="mt-2">
                                            {formData.tags.map(tag => (
                                                <span key={tag} className="badge bg-primary me-2">
                                                    {tag}
                                                    <button 
                                                        type="button" 
                                                        className="btn-close btn-close-white" 
                                                        aria-label="Remove" 
                                                        onClick={() => handleRemoveTag(tag)} 
                                                    />
                                                </span>
                                            ))}
                                        </div>
                                    </FormGroup>
                                </div>

                                <div className="mb-4">
                                    <div className="d-flex justify-content-between align-items-center mb-2">
                                        <label 
                                            className="form-label fw-bold"
                                            style={{color: props.isDarkMode ? "white" : "black"}}
                                        >
                                            Description * (Markdown supporté)
                                        </label>
                                        <div className="btn-group" role="group">
                                            <button
                                                type="button"
                                                className={`btn btn-sm ${!showPreview ? 'btn-primary' : 'btn-outline-primary'}`}
                                                onClick={() => setShowPreview(false)}
                                            >
                                                Édition
                                            </button>
                                            <button
                                                type="button"
                                                className={`btn btn-sm ${showPreview ? 'btn-primary' : 'btn-outline-primary'}`}
                                                onClick={() => setShowPreview(true)}
                                            >
                                                Aperçu
                                            </button>
                                        </div>
                                    </div>
                                    
                                    {!showPreview ? (
                                        <textarea
                                            className={`form-control ${errors.desc ? 'is-invalid' : ''} ${props.isDarkMode ? 'dark-mode-placeholder' : 'light-mode-placeholder'}`}
                                            rows="6"
                                            value={formData.desc}
                                            onChange={(e) => handleInputChange('desc', e.target.value)}
                                            placeholder="Décrivez le défi en Markdown...&#10;&#10;Exemples:&#10;# Titre principal&#10;## Sous-titre&#10;**Texte en gras** et *italique*&#10;- Liste à puces&#10;``````"
                                            style={{
                                                backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                                color: props.isDarkMode ? "white" : "black",
                                                border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`,
                                                resize: "vertical",
                                                fontFamily: "monospace"
                                            }}
                                        />
                                    ) : (
                                        <div 
                                            className="form-control"
                                            style={{
                                                backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                                color: props.isDarkMode ? "white" : "black",
                                                border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`,
                                                minHeight: "150px",
                                                padding: "12px"
                                            }}
                                        >
                                            <Markdown
                                                components={{
                                                    h1: ({node, ...props}) => (
                                                        <h1 style={{color: props.isDarkMode ? "white" : "black"}} {...props} />
                                                    ),
                                                    h2: ({node, ...props}) => (
                                                        <h2 style={{color: props.isDarkMode ? "white" : "black"}} {...props} />
                                                    ),
                                                    h3: ({node, ...props}) => (
                                                        <h3 style={{color: props.isDarkMode ? "white" : "black"}} {...props} />
                                                    ),
                                                    p: ({node, ...props}) => (
                                                        <p style={{color: props.isDarkMode ? "white" : "black"}} {...props} />
                                                    ),
                                                    code: ({node, inline, ...props}) => (
                                                        <code 
                                                            style={{
                                                                backgroundColor: props.isDarkMode ? "#2d2d2d" : "#e9ecef",
                                                                color: props.isDarkMode ? "#bb86fc" : "#4625ba",
                                                                padding: "2px 4px",
                                                                borderRadius: "4px"
                                                            }}
                                                            {...props} 
                                                        />
                                                    ),
                                                    ul: ({node, ...props}) => (
                                                        <ul style={{color: props.isDarkMode ? "white" : "black"}} {...props} />
                                                    ),
                                                    li: ({node, ...props}) => (
                                                        <li style={{color: props.isDarkMode ? "white" : "black"}} {...props} />
                                                    )
                                                }}
                                            >
                                                {formData.desc || "*Aucun contenu à prévisualiser*"}
                                            </Markdown>
                                        </div>
                                    )}
                                    
                                    {errors.desc && (
                                        <div className="invalid-feedback d-block">
                                            {errors.desc}
                                        </div>
                                    )}
                                </div>

                                <div className="row">
                                    <div className="col-md-8 mb-4">
                                        <label 
                                            className="form-label fw-bold"
                                            style={{color: props.isDarkMode ? "white" : "black"}}
                                        >
                                            Clé/Flag *
                                        </label>
                                        <input
                                            type="text"
                                            className={`form-control ${errors.key ? 'is-invalid' : ''} ${props.isDarkMode ? 'dark-mode-placeholder' : 'light-mode-placeholder'}`}
                                            value={formData.key}
                                            onChange={(e) => handleInputChange('key', e.target.value)}
                                            placeholder="Ex: flag{exemple_de_flag}"
                                            style={{
                                                backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                                color: props.isDarkMode ? "white" : "black",
                                                border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`
                                            }}
                                        />
                                        {errors.key && (
                                            <div className="invalid-feedback d-block">
                                                {errors.key}
                                            </div>
                                        )}
                                    </div>

                                    <div className="col-md-4 mb-4">
                                        <label 
                                            className="form-label fw-bold"
                                            style={{color: props.isDarkMode ? "white" : "black"}}
                                        >
                                            Score *
                                        </label>
                                        <input
                                            type="number"
                                            className={`form-control ${errors.score ? 'is-invalid' : ''} ${props.isDarkMode ? 'dark-mode-placeholder' : 'light-mode-placeholder'}`}
                                            value={formData.score}
                                            onChange={(e) => handleInputChange('score', e.target.value)}
                                            placeholder="100"
                                            min="1"
                                            style={{
                                                backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                                color: props.isDarkMode ? "white" : "black",
                                                border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`
                                            }}
                                        />
                                        {errors.score && (
                                            <div className="invalid-feedback d-block">
                                                {errors.score}
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="d-flex justify-content-center gap-3 mt-4">
                                    <CustomButton 
                                        type="button"
                                        className="w-25"
                                        style={{minWidth:"200px", maxWidth: "250px"}}
                                        darkColor={"#6c757d"}
                                        lightColor={"#6c757d"}
                                        onClick={() => props.navigateTo("/lobby")}
                                    >
                                        <div className="d-flex flex-row align-items-center justify-content-center position-relative">
                                            Annuler
                                            <SVGDispatcher type="x" color="white" className="position-absolute end-0"/>
                                        </div>
                                    </CustomButton>

                                    <CustomButton 
                                        type="submit"
                                        className="w-25"
                                        style={{minWidth:"200px", maxWidth: "250px"}}
                                        darkColor={"#4625ba"}
                                        lightColor={"#4625ba"}
                                        disabled={isSubmitting}
                                    >
                                        <div className="d-flex flex-row align-items-center justify-content-center position-relative">
                                            {isSubmitting ? "Création..." : "Créer le défi"}
                                            <SVGDispatcher 
                                                type={isSubmitting ? "loader" : "check"} 
                                                color="white" 
                                                className="position-absolute end-0"
                                            />
                                        </div>
                                    </CustomButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </Fade>
    );
}
