import React from "react";
import CustomButton from "../utils/CustomButton";
import SVGDispatcher from "../utils/Utils";
import { Fade } from "reactstrap";

export default function LegalNotices(props) {

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
        props.setShowLeftNavigation(false);
        props.setShowLeaderboard(false);
    }, []);

    return (
        <Fade in={!props.unmount} className="w-100 h-100">
            <div 
                className="d-flex flex-column w-100 p-5 overflow-scroll"
                style={{
                    color: props.isDarkMode ? "white" : "black",
                    backgroundColor: props.isDarkMode ? "#434343" : "#f2f2f2",
                    height:`calc(100vh - ${20+viewSize}px)`
                }}
            >
                <div className="container-fluid">
                    <h1 className="mb-4" style={{fontWeight: "bold"}}>Mentions légales</h1>
                    
                    <div className="mb-4">
                        <h3 className="mb-3" style={{color: props.isDarkMode ? "#bb86fc" : "#4625ba"}}>
                            Éditeur du site
                        </h3>
                        <p className="mb-2">Le site défIUT est édité par :</p>
                        <ul>
                            <li><strong>Nom de l'entité responsable :</strong> François PATINEC, Camille LE BRECH, Gabriel ZENSEN DA SILVA, Gabin LEGRAND</li>
                            <li><strong>Adresse postale :</strong> 56000 Vannes</li>
                            <li><strong>Adresse email :</strong> camille.lebrech14@gmail.com</li>
                            <li><strong>Responsable de la publication :</strong> Camille LE BRECH</li>
                            <li><strong>Statut juridique :</strong> Projet pédagogique universitaire (non professionnel)</li>
                        </ul>
                    </div>

                    <div className="mb-4">
                        <h3 className="mb-3" style={{color: props.isDarkMode ? "#bb86fc" : "#4625ba"}}>
                            Hébergement
                        </h3>
                        <p className="mb-2">Le site est hébergé localement par :</p>
                        <ul>
                            <li><strong>Nom de l'hébergeur :</strong> ton ordinateur (poste local)</li>
                            <li><strong>Adresse :</strong> Poste utilisateur local – hébergement non public</li>
                            <li><strong>Site web :</strong> https://localhost</li>
                        </ul>
                    </div>

                    <div className="mb-4">
                        <h3 className="mb-3" style={{color: props.isDarkMode ? "#bb86fc" : "#4625ba"}}>
                            Propriété intellectuelle
                        </h3>
                        <p className="mb-3">
                            L'ensemble du contenu publié sur le site défIUT (textes, images, logos, éléments graphiques, 
                            code source, etc.) est protégé par le droit d'auteur et la législation en vigueur sur la 
                            propriété intellectuelle.
                        </p>
                        <p className="mb-3">
                            Toute reproduction, distribution ou réutilisation, totale ou partielle, sans autorisation 
                            écrite préalable est interdite et peut faire l'objet de poursuites.
                        </p>
                        <p>
                            Les noms, marques, logos ou contenus appartenant à des tiers sont utilisés avec leur 
                            autorisation ou à des fins informatives uniquement.
                        </p>
                    </div>

                    <div className="mb-4">
                        <h3 className="mb-3" style={{color: props.isDarkMode ? "#bb86fc" : "#4625ba"}}>
                            Responsabilité
                        </h3>
                        <p className="mb-3">
                            L'équipe défIUT s'efforce d'assurer l'exactitude et la mise à jour régulière des 
                            informations publiées sur le site. Néanmoins, elle ne saurait être tenue responsable :
                        </p>
                        <ul className="mb-3">
                            <li>des erreurs ou omissions dans le contenu,</li>
                            <li>de l'indisponibilité du service,</li>
                            <li>de tout dommage direct ou indirect résultant de l'utilisation du site.</li>
                        </ul>
                        <p>
                            Les utilisateurs sont seuls responsables de l'usage qu'ils font du contenu et des 
                            informations disponibles sur le site.
                        </p>
                    </div>

                    <div className="mb-5">
                        <h3 className="mb-3" style={{color: props.isDarkMode ? "#bb86fc" : "#4625ba"}}>
                            Protection des données
                        </h3>
                        <p className="mb-3">
                            Conformément à la réglementation en vigueur (notamment le RGPD), toute donnée personnelle 
                            collectée via le site fait l'objet d'un traitement confidentiel.
                        </p>
                        <p>
                            Les utilisateurs disposent d'un droit d'accès, de rectification, de suppression et 
                            d'opposition à leurs données personnelles, qu'ils peuvent exercer en envoyant une demande 
                            à l'adresse suivante : <strong>camille.lebrech14@gmail.com</strong>.
                        </p>
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
