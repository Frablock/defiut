import React, { useState } from "react";
import CustomButton from "../utils/CustomButton";
import SVGDispatcher from "../utils/Utils";
import { Fade, Alert } from "reactstrap";

export default function CreateDefis(props) {
    const [formData, setFormData] = useState({
        nom: '',
        desc: '',
        diff: '',
        key: '',
        score: ''
    });
    const [errors, setErrors] = useState({});
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [submitMessage, setSubmitMessage] = useState({ type: '', message: '' });
    const [viewSize, setViewSize] = React.useState("0");

    // Gestion de la hauteur dynamique
    React.useEffect(() => {
        if (!props.footerRef?.current || !props.navbarRef?.current) return;

        const calculateSize = () => {
            const footerHeight = props.footerRef.current.offsetHeight;
            const navbarHeight = props.navbarRef.current.offsetHeight;
            setViewSize(footerHeight + navbarHeight);
        };

        calculateSize();

        const resizeObserver = new ResizeObserver(calculateSize);
        resizeObserver.observe(props.footerRef.current);
        resizeObserver.observe(props.navbarRef.current);

        return () => resizeObserver.disconnect();
    }, [props.footerRef?.current, props.navbarRef?.current]);

    // Styles CSS pour les placeholders
    React.useEffect(() => {
        const style = document.createElement('style');
        style.textContent = `
            .dark-mode-placeholder::placeholder {
                color: white !important;
                opacity: 0.7 !important;
            }
            
            .light-mode-placeholder::placeholder {
                color: #6c757d !important;
                opacity: 1 !important;
            }
        `;
        document.head.appendChild(style);
        
        return () => {
            if (document.head.contains(style)) {
                document.head.removeChild(style);
            }
        };
    }, []);

    React.useEffect(() => {
        props.setShowLeftNavigation(false);
        props.setShowLeaderboard(false);
    }, []);

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

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleInputChange = (field, value) => {
        setFormData(prev => ({
            ...prev,
            [field]: value
        }));
        
        // Clear error when user starts typing
        if (errors[field]) {
            setErrors(prev => ({
                ...prev,
                [field]: ''
            }));
        }
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
                    score: parseInt(formData.score)
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
                // Reset form
                setFormData({
                    nom: '',
                    desc: '',
                    diff: '',
                    key: '',
                    score: ''
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
        { value: 'facile', label: 'Facile' },
        { value: 'moyen', label: 'Moyen' },
        { value: 'difficile', label: 'Difficile' },
        { value: 'expert', label: 'Expert' }
    ];

    return (
        <Fade in={!props.unmount} className="w-100 h-100">
            <div 
                className="d-flex flex-column w-100 p-5 transition"
                style={{
                    color: props.isDarkMode ? "white" : "black",
                    backgroundColor: props.isDarkMode ? "#434343" : "#f2f2f2",
                    overflowY: "auto",
                    height: `calc(100vh - ${20+viewSize}px)`
                }}
            >
                <div className="container-fluid">
                    <div className="row justify-content-center">
                        <div className="col-lg-8 col-md-10">
                            <h1 className="mb-4 text-center" style={{fontWeight: "bold"}}>
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
                                    {/* Nom du défi */}
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

                                    {/* Difficulté */}
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

                                {/* Description */}
                                <div className="mb-4">
                                    <label 
                                        className="form-label fw-bold"
                                        style={{color: props.isDarkMode ? "white" : "black"}}
                                    >
                                        Description *
                                    </label>
                                    <textarea
                                        className={`form-control ${errors.desc ? 'is-invalid' : ''} ${props.isDarkMode ? 'dark-mode-placeholder' : 'light-mode-placeholder'}`}
                                        rows="4"
                                        value={formData.desc}
                                        onChange={(e) => handleInputChange('desc', e.target.value)}
                                        placeholder="Décrivez le défi, les objectifs et les consignes..."
                                        style={{
                                            backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                            color: props.isDarkMode ? "white" : "black",
                                            border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`,
                                            resize: "vertical"
                                        }}
                                    />
                                    {errors.desc && (
                                        <div className="invalid-feedback d-block">
                                            {errors.desc}
                                        </div>
                                    )}
                                </div>

                                <div className="row">
                                    {/* Clé/Flag */}
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

                                    {/* Score */}
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

                                {/* Boutons */}
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
