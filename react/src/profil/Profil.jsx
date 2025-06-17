import React from "react";
import { Placeholder } from "reactstrap";

export default function Pofil(props) {
    React.useEffect(() => {
        props.setShowLeftNavigation(true)
        props.setShowLeaderboard(false)
        props.logout()
    }, [])

    return (
        <div className="h-100 w-100 d-flex flex-row align-items-center justify-content-evenly" style={{"color":"white"}}>
            <div className="d-flex flex-column align-items-center gap-3">
                <Placeholder animation={"glow"}>
                    <Placeholder style={{height:"110px", width:"110px", borderRadius:"80px"}} />
                </Placeholder>
                <div style={{fontWeight:"600"}}>
                    Bonjour Gabin
                </div>
                <div style={{fontWeight:"800", color:"#a899e7"}}>
                    Défis
                </div>
            </div>
            <div className="d-flex flex-column">
                <div>
                    Défis récents
                </div>
                <div>
                    Défis récents
                </div>
                <div>
                    Défis récents
                </div>
            </div>
        </div>
    )
}