import React from "react";
import { sendData } from "./Utils";
import { Button, Card, CardBody, CardImg, CardText, Fade, Placeholder, PlaceholderButton } from "reactstrap";
import CustomButton from "./CustomButton";

export default function LeftNavigation(props) {
    const [loading, setLoading] = React.useState(true);
    const [data, setData] = React.useState({});
    const [unmount, setUnmount] = React.useState(true)
    

    React.useEffect(() => {
        console.log("Fetching data...");
        sendData({ route: "/defis/get_left_menu_categories" })
            .then((result) => {
                setData(result);
                setLoading(false)
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
        console.log("tu as clique sur le truc "+data.categories[index]["title"])
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
                                <CardBody>
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
                        {data.categories?.map((element, index) => (
                            <CustomButton 
                            key={index} 
                            className="mb-2 shadow custom-button"
                            onClick={() => handleOnClick(index)}
                            style={{height:"60px"}}
                            isDarkMode={props.isDarkMode}
                            darkColor={"#a899e7"}
                            lightColor={"#e2ddf7"}
                            >
                                <CardBody className="d-flex flex-row justify-content-between align-items-center" style={{color:"black"}}>
                                    {element["title"]}
                                    <CardImg style={{width:"40px", height:"40px"}} src={element["img"]}></CardImg>
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
