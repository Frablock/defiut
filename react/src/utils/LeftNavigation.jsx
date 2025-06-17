import React from "react";
import { sendData } from "./Utils";
import { Button, Card, CardBody, CardImg, CardText, Fade, Placeholder, PlaceholderButton } from "reactstrap";
import CustomButton from "./CustomButton";

export default function LeftNavigation(props) {
    const [loading, setLoading] = React.useState(true);
    const [data, setData] = React.useState({});
    const [unmount, setUnmount] = React.useState(true)
    

    React.useEffect(() => {
        sendData({ route: "/defis/get_left_menu_categories" })
            .then((result) => {
                if(!result.error){
                    setData(result.data);
                    setLoading(false)
                }
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
                setLoading(false);
            });
    }, []);

        React.useEffect(() => {
            setTimeout(() => {
                setUnmount(!props.showLeftNavigation)
            }, 150);
        },[props.showLeftNavigation])
    

    const handleOnClick = (index) => {
        props.navigateTo("/lobby"+data.categories[index]["url"]);
        props.setCategory(data.categories[index]["url"])
    }

    return (
    <>
        {unmount ?
            <></>
        :
        <Fade
            in={!unmount && props.showLeftNavigation} 
            className="h-100"
            onExited={() => setUnmount(true)}
        >
            <div className="d-flex flex-column gap-5 h-100 justify-content-center" style={{marginLeft:"20px"}}>
                {loading ? (
                    <>
                        {Array.from({ length: 6 }, (_, i) => (
                            <CustomButton 
                            key={i} 
                            className="mb-2 shadow" 
                            style={{backgroundColor:"#e2ddf7"}}
                            >
                                <CardBody style={{width:"150px"}}>
                                    <Placeholder animation="wave" className="d-flex flex-row justify-content-between py-2">
                                        <Placeholder xs={7} />
                                        <Placeholder xs={2} />
                                    </Placeholder>
                                </CardBody>
                            </CustomButton>
                        ))}
                    </>
                ) : (
                    <>
                        {data.map((element, index) => (
                            <CustomButton 
                            key={index} 
                            className="mb-2 shadow custom-button"
                            onClick={() => handleOnClick(index)}
                            style={{
                                height:"60px",
                                transform: props.category === element["url"] ? "scale(0.950)" : "scale(1)"
                            }}
                            isDarkMode={props.isDarkMode}
                            darkColor={props.category === element["url"] ? "#4625ba" : "#a899e7"}
                            lightColor={props.category === element["url"] ? "#4625ba" : "#e2ddf7"}
                            >
                                <CardBody className="d-flex flex-row transition justify-content-between align-items-center"
                                style={{
                                    color: props.category === element["url"] ? "white" : "black", 
                                    width:"150px"
                                }}>
                                    {element["title"]}
                                    <i className={element["img"]}></i>
                                </CardBody>
                            </CustomButton>
                        ))}
                    </>
                )}
            </div>
        </Fade>
        }
      </>
    );
}
