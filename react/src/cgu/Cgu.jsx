import React from "react";
import CustomButton from "../utils/CustomButton";
import SVGDispatcher from "../utils/Utils";
import { Fade } from "reactstrap";

export default function CGU(props){

    React.useEffect(() => {
        props.setShowLeftNavigation(false)
        props.setShowLeaderboard(false)
    },[])

    return (
        <Fade in={!props.unmount} className="w-100 h-100">
            <div className="d-flex justify-content-center w-100 h-100 p-4">
                <div 
                    className="w-100"
                    style={{
                        maxWidth: "1000px",
                        background: props.isDarkMode ? "#34495e" : "white",
                        borderRadius: "15px",
                        boxShadow: "0 8px 32px rgba(0, 0, 0, 0.1)",
                        overflow: "hidden"
                    }}
                >
                    {/* Header */}
                    <div 
                        className="p-4"
                        style={{
                            background: "linear-gradient(135deg, #28a745 0%, #1e7e34 100%)",
                            color: "white"
                        }}
                    >
                        <h1 className="h3 mb-0 d-flex align-items-center">
                            <SVGDispatcher type="file-text" color="white" className="me-3" />
                            Conditions Générales d'Utilisation (CGU)
                        </h1>
                    </div>

                    {/* Content */}
                    <div 
                        className="p-4" 
                        style={{ 
                            color: props.isDarkMode ? "white" : "#333", 
                            lineHeight: "1.7",
                            maxHeight: "calc(100vh - 200px)", 
                            overflowY: "auto" 
                        }}
                    >
                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                1. Acceptation des Conditions
                            </h2>
                            <p>
                                En accédant et en utilisant le site défIUT (ci-après dénommé "le Site"), vous acceptez sans réserve les présentes Conditions Générales d'Utilisation (ci-après "CGU"). 
                                Si vous n'acceptez pas ces CGU, veuillez ne pas utiliser le Site.
                            </p>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                2. Description du Service
                            </h2>
                            <p>
                                Le Site défIUT est une plateforme destinée aux étudiants, enseignants et professionnels de l'IUT. Il propose notamment :
                            </p>
                            <ul>
                                <li>des ressources pédagogiques,</li>
                                <li>des challenges de type Capture The Flag (CTF),</li>
                            </ul>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                3. Accès au Site
                            </h2>
                            <p>
                                L'accès au Site est gratuit. Certaines fonctionnalités peuvent nécessiter une inscription avec la création d'un compte utilisateur. 
                                Vous êtes responsable de la confidentialité de vos identifiants et de toute activité réalisée via votre compte.
                            </p>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                4. Utilisation du Site
                            </h2>
                            <p>
                                Vous vous engagez à utiliser le Site de manière légale, responsable et respectueuse. Vous vous interdisez notamment :
                            </p>
                            <ul>
                                <li>d'usurper l'identité d'un tiers,</li>
                                <li>de tenter d'accéder à des zones restreintes sans autorisation,</li>
                                <li>de publier ou partager des contenus illicites, offensants, ou contraires à l'ordre public,</li>
                                <li>d'entraver le bon fonctionnement du Site par des attaques ou des scripts malveillants,</li>
                                <li>d'utiliser le Site à des fins commerciales sans accord préalable.</li>
                            </ul>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                5. Propriété Intellectuelle
                            </h2>
                            <p>
                                L'ensemble des contenus présents sur le Site (textes, images, logos, code, etc.) est protégé par le droit d'auteur. 
                                Toute reproduction, distribution ou utilisation non autorisée est strictement interdite sans l'accord écrit des ayants droit.
                            </p>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                6. Données Personnelles
                            </h2>
                            <p>
                                Vos données personnelles sont collectées et traitées conformément à notre Politique de Confidentialité (à insérer ou créer si besoin). 
                                Vous disposez d'un droit d'accès, de rectification et de suppression de vos données.
                            </p>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                7. Disponibilité du Service
                            </h2>
                            <p>
                                Le Site est accessible 24h/24 et 7j/7, sauf interruption pour maintenance ou cas de force majeure. 
                                Nous ne garantissons pas une disponibilité continue et pouvons suspendre ou modifier l'accès à tout moment sans préavis.
                            </p>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                8. Responsabilité
                            </h2>
                            <p>
                                L'équipe défIUT ne saurait être tenue responsable :
                            </p>
                            <ul>
                                <li>des erreurs, bugs ou interruptions du service,</li>
                                <li>des dommages résultant de l'usage du Site ou de l'impossibilité d'y accéder,</li>
                                <li>des contenus publiés par les utilisateurs.</li>
                            </ul>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                9. Modifications des CGU
                            </h2>
                            <p>
                                Nous nous réservons le droit de modifier à tout moment les présentes CGU. Les utilisateurs seront informés des mises à jour, 
                                et la poursuite de l'utilisation du Site vaudra acceptation des nouvelles conditions.
                            </p>
                        </div>

                        <div className="mb-4">
                            <h2 className="h4 mb-3" style={{ borderBottom: "2px solid #e9ecef", paddingBottom: "0.5rem" }}>
                                10. Droit applicable
                            </h2>
                            <p>
                                Les présentes CGU sont soumises au droit français. En cas de litige, les tribunaux compétents seront ceux du ressort du siège de l'éditeur du Site.
                            </p>
                        </div>

                        <div className="text-center mt-4">
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
            </div>
        </Fade>
    );
}
