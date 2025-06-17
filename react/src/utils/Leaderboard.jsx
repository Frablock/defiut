import React from "react";
import { Card, CardBody, CardTitle, CloseButton, Collapse, Fade, Placeholder } from "reactstrap";

export default function Leaderboard(props) {
    const [loading, setLoading] = React.useState(true)
    const [data, setData] = React.useState({})
    const [unmount, setUnmount] = React.useState(true)

    const handlePlaceholder = () => {
        return (
            <Placeholder animation="wave">
                <Placeholder style={{width:"70px"}}/>
            </Placeholder>
        )
    }

    React.useEffect(() => {
        props.sendData({route:'/leaderboard'}).then((data) => {
            if(!data.error){
                setData(data.data)
                setLoading(false)
            }
        })
    },[])

    React.useEffect(() => {
        setTimeout(() => {
            setUnmount(!props.showLeaderboard)
        }, 150);
    },[props.showLeaderboard])

    return (
        <>
            {unmount ? 
                <></> 
                : 
                <Fade
                    in={!unmount && props.showLeaderboard} 
                    className="h-100"
                    onExited={() => setUnmount(true)}
                >
                    <div className="h-100 d-flex flex-column gap-5 h-100 justify-content-center" style={{marginRight:"20px"}}>
                        
                        <Card className="border-0 shadow transition" style={{backgroundColor: props.isDarkMode ? "#535353" : "#a899e7", color: props.isDarkMode ? "white" : "black", }}>
                            <CloseButton onClick={() => setUnmount(!unmount)}/>
                            <CardTitle className="d-flex flex-row justify-content-center my-5" style={{fontWeight:"700"}}>
                                PODIUM
                            </CardTitle>
                            <CardBody className="d-flex flex-column">
                                <div className="d-flex flex-row mb-5">
                                    <Podium className="h-25" number="3" zIndex="1" isDarkMode={props.isDarkMode}>{loading ? handlePlaceholder() : <div style={{color: props.isDarkMode ? "white" : "black"}}>{data[2]["username"]}</div> }</Podium>
                                    <Podium className="h-75" number="1" zIndex="2" isDarkMode={props.isDarkMode}>{loading ? handlePlaceholder() : <div style={{fontWeight:"700",color: props.isDarkMode ? "white" : "black"}}>{data[0]["username"]}</div> }</Podium>
                                    <Podium className="h-50" number="2" zIndex="1" isDarkMode={props.isDarkMode}>{loading ? handlePlaceholder() : <div style={{color: props.isDarkMode ? "white" : "black"}}>{data[1]["username"]}</div> }</Podium>
                                </div>
                                <CardBody className="d-flex flex-column">
                                    {loading ? 
                                    <div className="d-flex flex-column mx-5 gap-3">
                                    {Array.from({ length: 10 }, (_, i) => (
                                        <div className="d-flex flex-row justify-content-end">
                                            {i+1}. 
                                            <Placeholder key={i} className="d-flex flex-row justify-content-between" style={{width:"200px"}} animation="wave">
                                                <Placeholder xs={7}/>
                                                <Placeholder style={{width:"70px"}}/>
                                            </Placeholder>
                                        </div>
                                    ))}
                                    </div>
                                    :
                                    <>
                                    {
                                        
                                        data.map((elem, index) => {
                                            return(
                                            <div className="d-flex flex-row justify-content-between">
                                                {index+1}. {elem.username}
                                                <div key={index} style={{fontWeight:"700"}}>
                                                     {elem.total_score}
                                                </div>
                                            </div>)
                                        })
                                    }
                                    </>}
                                </CardBody>
                            </CardBody>
                        </Card>
                    </div>
                </Fade>
            }
        </>
    );
}

function Podium({className, number, zIndex, children, isDarkMode}){
    return (
        <div className="d-flex flex-column w-100 align-items-center justify-content-end" style={{height:"100px"}}>
            {children}
            <div className={"d-flex w-100 shadow justify-content-center transition "+className} style={{backgroundColor: isDarkMode ? "#a899e7" : "#e2ddf7", borderTopLeftRadius:"7px", borderTopRightRadius:"7px", zIndex: zIndex, fontWeight:"600"}}>
                {number}
            </div>
        </div>
    )
}