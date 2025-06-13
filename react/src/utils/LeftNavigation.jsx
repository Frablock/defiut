import React from "react";
import { sendData } from "./Utils";
import { Button, Card, CardBody, CardImg, CardText, Placeholder, PlaceholderButton } from "reactstrap";

export default function LeftNavigation(params) {
    const [loading, setLoading] = React.useState(true);
    const [data, setData] = React.useState({});

    React.useEffect(() => {
        console.log("Fetching data...");
        sendData({ route: "/defis/get_left_menu_categories" })
            .then((result) => {
                console.log(result.categories)
                setData(result);
                setLoading(false)
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
                setLoading(false); // Set loading to false even on error
            });
    }, []);

    const handleOnClick = (index) => {
        console.log("tu as clique sur le truc "+data.categories[index]["title"])
    }

    return (
        <div className="h-100" style={{width:"200px"}}>
            <div className="d-flex flex-column my-5 ms-3 gap-4 h-100">
                {loading ? (
                    <>
                        {Array.from({ length: 6 }, (_, i) => (
                            <Card key={i} className="mb-2 shadow" style={{backgroundColor:"#e2ddf7"}}>
                                <CardBody>
                                    <Placeholder animation="wave" className="d-flex flex-row justify-content-between py-2">
                                        <Placeholder xs={7} />
                                        <Placeholder xs={2} />
                                    </Placeholder>
                                </CardBody>
                            </Card>
                        ))}
                    </>
                ) : (
                    <>
                        {data.categories?.map((element, index) => (
                            <Card key={index} className="mb-2 shadow custom-button " style={{backgroundColor:"#e2ddf7", cursor:"pointer"}} onClick={() => handleOnClick(index)}>
                                <CardBody className="d-flex flex-row justify-content-between align-items-center">
                                    {element["title"]}
                                    <CardImg style={{width:"40px", height:"40px"}} src={element["img"]}></CardImg>
                                </CardBody>
                            </Card>
                        ))}
                    </>
                )}
            </div>
        </div>
    );
}
