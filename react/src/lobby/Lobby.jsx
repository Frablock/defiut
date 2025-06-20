import React from "react"
import { Fade, ListGroup, ListGroupItem, ListGroupItemHeading, Placeholder } from "reactstrap"
import { useParams } from "react-router-dom"
import LobbyCategory from "./LobbyCategory";

export default function Lobby(props) {
    const [data, setData] = React.useState({})
    const [loading, setLoading] = React.useState(true)
    
    React.useEffect(() => {
        props.setShowLeftNavigation(true)
        props.setShowLeaderboard(true)
        props.sendData({route:"/defis/get_lobby_categories"}).then((data) => {
            if(!data.error){
                setData(data.data)
                setLoading(false)
            }
        })
    },[props.isLogedIn])



    return (
        <>
        <Fade in={!props.unmount} className="w-100 h-100 justify-content-start">
            <div in={!props.unmount} className="w-100 h-100 my-3 row justify-content-evenly align-items-center">
                <div className="d-flex flex-column w-auto align-items-center">
                    <h2 className="transition" style={{color: props.isDarkMode ? "white" : "black"}}>Défis tendances</h2>
                    <ListGroup className="mx-4" style={{width:"400px", maxWidth: "100%"}}>
                            {loading ? 
                            <>
                                {
                                    Array.from({ length: 5 }, (_, i) => (
                                        <HandleListGroupItem index={i} isDarkMode={props.isDarkMode}>
                                            <Placeholder xs={6}/> 
                                        </HandleListGroupItem>
                                    ))
                                }
                            </>
                            :
                            <>
                            {
                                data.top_defis.map((elem, i) => (
                                    <HandleListGroupItem onClick={() => props.navigateTo("/defis/"+elem['id'])} index={i} isDarkMode={props.isDarkMode}>
                                        {loading ? 
                                        <Placeholder xs={6}/> 
                                        : 
                                        <>
                                            {elem.title}
                                        </>
                                        }
                                        </HandleListGroupItem>
                                ))
                            }
                            </>
                            }
                        </ListGroup>
                </div>
                {props.isLogedIn && 
                    <div className="d-flex flex-column w-auto align-items-center">
                        <h2 className="transition" style={{color: props.isDarkMode ? "white" : "black"}}>Défis récents</h2>
                        <ListGroup className="mx-4" style={{width:"400px", maxWidth: "100%"}}>
                            {loading ? 
                            <>
                                {
                                    Array.from({ length: 5 }, (_, i) => (
                                        <HandleListGroupItem index={i} isDarkMode={props.isDarkMode}>
                                            <Placeholder xs={6}/> 
                                        </HandleListGroupItem>
                                    ))
                                }
                            </>
                            :
                            <>
                            {
                                data.defis_recents.length > 0 ?
                                    data.defis_recents.map((elem, i) => (
                                        <HandleListGroupItem onClick={() => props.navigateTo("/defis/"+elem['id'])} index={i} isDarkMode={props.isDarkMode}>
                                            {elem.title}
                                        </HandleListGroupItem>
                                    ))
                                :
                                <>
                                    <HandleListGroupItem isDarkMode={props.isDarkMode}>
                                        Aucun défis récents !
                                    </HandleListGroupItem>
                                </>
                            }
                            </>
                            }
                        </ListGroup>
                    </div>                
                }
            </div>
        </Fade>
        </>
    )
}

function HandleListGroupItem({index, children, isDarkMode, onClick}){
    const [hoveredItem, setHoveredItem] = React.useState(null)
    const isHovered = hoveredItem === index
    
    return (
        <ListGroupItem 
            key={index}
            href="#" 
            tag="a"
            className="shadow"
            style={{
                backgroundColor: isHovered ? '#4625ba' : (isDarkMode ? "#a899e7" : "#e2ddf7"),
                transform: isHovered ? 'scale(1.02)' : 'scale(1)',
                transition: 'all 0.2s ease',
                cursor: 'pointer',
                color: isHovered ? "white" : "black",
                fontSize:"20px"
            }}
            onMouseEnter={() => setHoveredItem(index)}
            onMouseLeave={() => setHoveredItem(null)}
            onClick={onClick}
        >
            {children}
        </ListGroupItem>
    )
}