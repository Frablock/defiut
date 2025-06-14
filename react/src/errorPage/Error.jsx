import React from "react";
import CustomButton from "../utils/CustomButton";
import SVGDispatcher from "../utils/Utils";
import { Fade } from "reactstrap";

export default function Error(props){

    React.useEffect(() => {
        props.setShowLeftNavigation(false)
        props.setShowLeaderboard(false)
    },[])

    return (
        <Fade in={!props.unmount} className="w-100 h-100">
        <div 
            className="d-flex flex-column justify-content-center align-items-center w-100 h-100 transition gap-4"
            style={{color: props.isDarkMode ? "white" : "black"}}
        >
            <div style={{fontWeight:"900", fontSize:"200px", marginBottom:"-50px"}}>404</div>
            <div>Cette page nest introuvable</div>
            <div className="d-flex flex-row gap-3 w-100 justify-content-center">
                <CustomButton 
                    className="w-25"
                    style={{minWidth:"200px", maxWidth: "250px"}}
                    darkColor={"#4625ba"}
                    lightColor={"#4625ba"}
                    onClick={() => props.navigateTo("/lobby")}
                >
                    <div className="d-flex flex-row align-items-center justify-content-center position-relative">
                        Acceuil
                        <SVGDispatcher type="arrow-right" color="white" className="position-absolute end-0"/>
                    </div>
                </CustomButton>
                <CustomButton 
                    className="w-25" 
                    style={{minWidth:"200px", maxWidth: "250px"}}
                    darkColor={"#4625ba"}
                    lightColor={"#4625ba"}
                    onClick={() => props.navigateTo(-1)} //HERE GO BACK TO THE PREVIOUS URL
                >
                    <div className="d-flex flex-row align-items-center justify-content-center position-relative">
                        Page précédente
                        <SVGDispatcher type="arrow-right" color="white" className="position-absolute end-0"/>
                    </div>
                </CustomButton>
            </div>
        </div>
    </Fade>
    );
}