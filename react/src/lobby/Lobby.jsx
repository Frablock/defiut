import React from "react"
import { Fade, ListGroup, ListGroupItem, ListGroupItemHeading, Placeholder } from "reactstrap"
import { useParams } from "react-router-dom"
import LobbyCategory from "./LobbyCategory";

export default function Lobby(props) {
    const { category } = useParams();
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
    },[])


    return (
        <>
        {category ? 
            <Fade in={!props.unmount} className="w-100 h-100 mx-5 justify-content-start">
                <LobbyCategory {...props} {...{category}} />
            </Fade>
            :
            <Fade in={!props.unmount} className="w-100 h-100 mx-3 my-3 row justify-content-evenly align-items-center">
                <div className="d-flex flex-column w-auto align-items-center">
                    <h2 className="transition" style={{color: props.isDarkMode ? "white" : "black"}}>Tags tendances</h2>
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
                                data.tags_name.map((elem, i) => (
                                    <HandleListGroupItem index={i} isDarkMode={props.isDarkMode}>
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
                                data.defis_recents.map((elem, i) => (
                                    <HandleListGroupItem onClick={() => props.navigateTo("/defis/"+elem['id'])} index={i} isDarkMode={props.isDarkMode}>
                                        {elem.title}
                                    </HandleListGroupItem>
                                ))
                            }
                            </>
                            }
                        </ListGroup>
                    </div>                
                }
            </Fade>
        }
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