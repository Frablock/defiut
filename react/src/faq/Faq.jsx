import React, { useState, useEffect } from "react";
import CustomButton from "../utils/CustomButton";
import SVGDispatcher from "../utils/Utils";
import { Fade, Collapse } from "reactstrap";

export default function FAQ(props) {
    const [openItems, setOpenItems] = useState({});
    const [viewSize, setViewSize] = React.useState("0")

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

    React.useEffect(() => {
        props.setShowLeftNavigation(true); 
        props.setShowLeaderboard(false);
    }, []);

    const toggleItem = (index) => {
        setOpenItems(prev => ({
            ...prev,
            [index]: !prev[index]
        }));
    };

    // Questions 
    const faqItems = [
        {
            question: "Comment m'inscrire sur la plateforme ?",
            answer: "Pour vous inscrire sur défIUT, cliquez sur le bouton 'Inscription' en haut à droite de la page. Remplissez le formulaire avec vos informations personnelles et votre adresse email. Vous recevrez un email de confirmation pour activer votre compte."
        },
        {
            question: "Est-ce que l'inscription est obligatoire ?",
            answer: "L'inscription n'est pas obligatoire pour consulter le contenu public du site, mais elle est nécessaire pour participer aux défis, accéder aux ressources pédagogiques complètes et interagir avec la communauté."
        },
        {
            question: "Comment fonctionne le système de défi ?",
            answer: "Les défis sont organisés par catégories dans le menu de gauche. Chaque défi propose des challenges spécifiques avec des points à gagner. Vous pouvez suivre votre progression et celle des autres participants via le leaderboard."
        },
        {
            question: "Puis-je changer mon pseudo ou mon mot de passe ?",
            answer: "Oui, vous pouvez modifier vos informations personnelles en accédant à votre profil utilisateur. Cliquez sur votre nom d'utilisateur puis sur 'Paramètres du compte' pour modifier votre pseudo, mot de passe ou autres informations."
        },
        {
            question: "Que faire si j'oublie mon mot de passe ?",
            answer: "Sur la page de connexion, cliquez sur 'Mot de passe oublié ?'. Saisissez votre adresse email et vous recevrez un lien pour réinitialiser votre mot de passe. Suivez les instructions dans l'email reçu."
        },
        {
            question: "Les données personnelles sont-elles protégées ?",
            answer: "Oui, nous respectons strictement le RGPD. Vos données personnelles sont chiffrées et sécurisées. Nous ne partageons jamais vos informations avec des tiers sans votre consentement explicite. Consultez notre politique de confidentialité pour plus de détails."
        },
        {
            question: "Comment signaler un problème ou poser une question ?",
            answer: "Vous pouvez nous contacter via l'adresse email camille.lebrech14@gmail.com ou utiliser le formulaire de contact disponible dans le footer du site. Notre équipe vous répondra dans les plus brefs délais."
        }
    ];

    return (
        <Fade in={!props.unmount} className="w-100 h-100">
            <div 
                className="d-flex flex-column w-100 p-5 transition"
                style={{
                    color: props.isDarkMode ? "white" : "black",
                    backgroundColor: props.isDarkMode ? "#434343" : "#f2f2f2",
                    overflowY: "auto",
                    
                    height:`calc(100vh - ${20+viewSize}px)`
                }}
            >
                <div className="container-fluid">
                    <h1 className="mb-4" style={{fontWeight: "bold"}}>FAQ (Foire aux Questions)</h1>
                    
                    <div className="mb-5">
                        {faqItems.map((item, index) => (
                            <div key={index} className="mb-3">
                                <div 
                                    className="d-flex justify-content-between align-items-center p-3 rounded transition"
                                    style={{
                                        backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                        cursor: "pointer",
                                        border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`,
                                        transition: "all 0.3s ease"
                                    }}
                                    onClick={() => toggleItem(index)}
                                    onMouseEnter={(e) => {
                                        e.target.style.backgroundColor = props.isDarkMode ? "#4d4d4d" : "#f8f9fa";
                                    }}
                                    onMouseLeave={(e) => {
                                        e.target.style.backgroundColor = props.isDarkMode ? "#3d3d3d" : "white";
                                    }}
                                >
                                    <span style={{fontWeight: "500", fontSize: "16px"}}>
                                        {index + 1}. {item.question}
                                    </span>
                                    <SVGDispatcher 
                                        type={openItems[index] ? "chevron-up" : "chevron-down"} 
                                        color={props.isDarkMode ? "#bb86fc" : "#4625ba"}
                                        style={{
                                            transform: openItems[index] ? "rotate(180deg)" : "rotate(0deg)",
                                            transition: "transform 0.3s ease"
                                        }}
                                    />
                                </div>
                                <Collapse isOpen={openItems[index]}>
                                    <div 
                                        className="p-4 rounded-bottom"
                                        style={{
                                            backgroundColor: props.isDarkMode ? "#2a2a2a" : "#f8f9fa",
                                            border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`,
                                            borderTop: "none",
                                            lineHeight: "1.6"
                                        }}
                                    >
                                        {item.answer}
                                    </div>
                                </Collapse>
                            </div>
                        ))}
                    </div>

                    <div className="text-center mb-4 ">
                        <div 
                            className="p-4 rounded transition"
                            style={{
                                backgroundColor: props.isDarkMode ? "#3d3d3d" : "white",
                                border: `1px solid ${props.isDarkMode ? "#555" : "#ddd"}`
                            }}
                        >
                            <h5 style={{color: props.isDarkMode ? "#bb86fc" : "#4625ba", marginBottom: "15px"}}>
                                Vous ne trouvez pas la réponse à votre question ?
                            </h5>
                            <p style={{marginBottom: "20px"}}>
                                N'hésitez pas à nous contacter directement pour obtenir de l'aide personnalisée.
                            </p>
                            <a 
                                href="mailto:camille.lebrech14@gmail.com"
                                style={{
                                    color: props.isDarkMode ? "#bb86fc" : "#4625ba",
                                    textDecoration: "none",
                                    fontWeight: "bold"
                                }}
                            >
                                📧 camille.lebrech14@gmail.com
                            </a>
                        </div>
                    </div>

                    <div className="d-flex justify-content-center">
                        <CustomButton 
                            className="w-25"
                            style={{minWidth:"200px", maxWidth: "250px"}}
                            darkColor={"#4625ba"}
                            lightColor={"#4625ba"}
                            onClick={() => props.navigateTo("/lobby")}
                        >
                            <div className="d-flex flex-row align-items-center justify-content-center position-relative">
                                Retour à l'accueil
                                <SVGDispatcher type="arrow-right" color="white" className="position-absolute end-0"/>
                            </div>
                        </CustomButton>
                    </div>
                </div>
            </div>
        </Fade>
    );
}
