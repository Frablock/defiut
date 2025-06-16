import React from "react"
import { Fade, ListGroup, ListGroupItem, ListGroupItemHeading, Placeholder } from "reactstrap"
import { sendData } from "../utils/Utils"
import { useParams } from "react-router-dom"
import LobbyCategory from "./LobbyCategory";

export default function Lobby(props) {
    const { category } = useParams();
    const [data, setData] = React.useState({})
    const [loading, setLoading] = React.useState(true)
    
    React.useEffect(() => {
        props.setShowLeftNavigation(true)
        props.setShowLeaderboard(true)
        sendData({route:"/defis/get_lobby_categories"}).then((data) => {
            setData(data)
            setLoading(false)
        })
    },[])

    React.useEffect(() => {
        sendData({route:category})
    })


    return (
        <>
        {category ? 
            <Fade in={!props.unmount} className="w-100 h-100 mx-5 justify-content-start">
                <LobbyCategory {...props} {...{category}} />
            </Fade>
            :
            <Fade in={!props.unmount} className="w-100 h-100 mx-3 my-3 row justify-content-evenly align-items-center">
                <div className="d-flex flex-column w-auto align-items-center">
                    <h2 className="transition" style={{color: props.isDarkMode ? "white" : "black"}}>Défis tendances</h2>
                    <ListGroup className="mx-4 mb-4 mb-md-0" style={{width:"400px", maxWidth: "100%"}}>
                        {
                            Array.from({ length: 5 }, (_, i) => (
                                <HandleListGroupItem index={i} props isDarkMode={props.isDarkMode}>
                                    {loading ? 
                                    <Placeholder xs={6}/> 
                                    : 
                                    <>
                                    {data.categories.tendances[i]}
                                    </>
                                    }
                                    </HandleListGroupItem>
                            ))
                        }
                    </ListGroup>
                </div>
                <div className="d-flex flex-column w-auto align-items-center">
                    <h2 className="transition" style={{color: props.isDarkMode ? "white" : "black"}}>Défis aléatoires</h2>
                    <ListGroup className="mx-4" style={{width:"400px", maxWidth: "100%"}}>
                        {
                            Array.from({ length: 5 }, (_, i) => (
                                <HandleListGroupItem index={i} isDarkMode={props.isDarkMode}>
                                    {loading ? 
                                    <Placeholder xs={6}/> 
                                    : 
                                    <>
                                    {data.categories.random[i]}
                                    </>
                                    }
                                    </HandleListGroupItem>
                            ))
                        }
                    </ListGroup>
                </div>
            </Fade>
        }
        </>
    )
}

function HandleListGroupItem({index, children, isDarkMode}){
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
                transition: 'transform 0.2s ease !important',
                cursor: 'pointer',
                color: isHovered ? "white" : "black"
            }}
            onMouseEnter={() => setHoveredItem(index)}
            onMouseLeave={() => setHoveredItem(null)}
        >
            {children}
        </ListGroupItem>
    )
}