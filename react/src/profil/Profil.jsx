import React from "react";
import { Accordion, AccordionBody, AccordionHeader, AccordionItem, Button, Placeholder } from "reactstrap";

export default function Pofil(props) {
    const [open, setOpen] = React.useState('1');
    const [loading, setLoading] = React.useState(true)
    const toggle = (id) => {
        if (open === id) {
        setOpen();
        } else {
        setOpen(id);
        }
    };
    React.useEffect(() => {
        props.setShowLeftNavigation(true)
        props.setShowLeaderboard(false)
    }, [])

    return (
        <div className="h-100 w-100 d-flex flex-row justify-content-evenly" style={{"color":"white"}}>
            <div className="d-flex flex-column align-items-center gap-3 mt-5" style={{width:"300px"}}>
                <Placeholder animation={"glow"}>
                    <Placeholder style={{height:"110px", width:"110px", borderRadius:"80px"}} />
                </Placeholder>
                <div style={{fontWeight:"600", fontSize:"20px"}}>
                    Bonjour Gabin
                </div>
                <div style={{fontWeight:"700", color:"#a899e7", fontSize:"30px"}}>
                    Défis
                </div>
                <div className="w-100">
                    <Accordion open={open} toggle={toggle} className="w-100">
                        <AccordionItem>
                            <AccordionHeader targetId="1">
                                <div className="d-flex flex-row w-100">
                                    <div className="w-50 h-100" style={{borderRight:"1px solid black"}}>
                                        Score global :
                                    </div>
                                    <div className="d-flex w-50 justify-content-center">
                                        <Placeholder className="d-flex flex-row justify-content-center" style={{width:"200px"}} animation="wave">
                                            <Placeholder xs={3}/>
                                        </Placeholder>
                                    </div>
                                </div>
                            </AccordionHeader>
                            <AccordionBody accordionId="1">
                                test
                            </AccordionBody>
                        </AccordionItem>
                    </Accordion>
                </div>
                <Button onClick={() => props.logout()}>Se Déconnecter</Button>
            </div>
            <div className="d-flex flex-column h-100 justify-content-center">
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