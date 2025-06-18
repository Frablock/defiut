import React from "react";
import { useParams } from "react-router-dom";
import { Badge, Button, Fade, Input, Spinner } from "reactstrap";
import SVGDispatcher from "../utils/Utils";
import CustomButton from "../utils/CustomButton";

export default function Defis(props) {
    const { id } = useParams();
    const [currentDefis, setCurrentDefis] = React.useState();
    const [inputValue, setInputValue] = React.useState("");
    const [viewSize, setViewSize] = React.useState("0")

    const handleDefisTest = () => { 
        if(props.isLogedIn){
            props.sendData({
                route:"/defis/try_key", 
                method:"POST",
                data:{
                    id:id,
                    key:inputValue
                }
            }).then((data) => {
                if(!data.error){
                    props.setModalHeader("Bravo !")
                    props.setModalButtonText("Page des défis")
                    props.setModalContent("Bravo ! Vous avez trouvé le message secret ! Vos points viennent d'être comptabilisés.")
                    props.setModalOnClick(() => () => {
                        props.navigateTo("/lobby/all");
                        props.setModalActive(false)
                    })
                    props.setModalActive(true)
                }
            })
            //
        } else {
            props.setModalHeader("Veuillez vous connecter")
            props.setModalButtonText("Se Connecter")
            props.setModalContent("Pour pouvoir envoyer votre réponse, vous devez absolument créer un compte / vous connecter.")
            props.setModalOnClick(() => () => {
                props.navigateTo("/login");
                props.setModalActive(false)
            })
            props.setModalActive(true)
        }
    }

    React.useEffect(() => {
        if (!props.footerRef?.current || !props.navbarRef?.current) return;

        const calculateSize = () => {
            const footerHeight = props.footerRef.current.offsetHeight;
            const navbarHeight = props.navbarRef.current.offsetHeight;
            setViewSize(footerHeight + navbarHeight);
        };

        calculateSize();

        const resizeObserver = new ResizeObserver(calculateSize);
        resizeObserver.observe(props.footerRef.current);
        resizeObserver.observe(props.navbarRef.current);


        return () => resizeObserver.disconnect();
    }, [props.footerRef?.current, props.navbarRef?.current]);

    const handleFileDownload = async (id) => {
        const result = await props.sendData({
            route: "/file/" + id,
            isFileDownload: true
        });
        
        if (result.error) {
            // Handle error - show notification, alert, etc.
            console.error('Download failed:', result.error_message);
            alert('Failed to download file: ' + result.error_message);
        }
    };


    React.useEffect(() => {
        if(!currentDefis){
            props.sendData({route:'/defis/'+id}).then((elem) => {
                if(!elem.error){
                    setCurrentDefis(elem.data)
                }
            })
        }
        if (currentDefis) {
            setCurrentDefis(() => {
                return props.defis.find(defi => defi.id == id);
            })
        }
    },[id, props.defis])

    if (!currentDefis) {
        return <div className="w-100 h-100 d-flex flex-row justify-content-center align-items-center">
            <Spinner />
        </div>
    }
    
    const { user, difficulte, tags, nom, description, pointsRecompense } = currentDefis;

    return (
        <Fade in={!props.unmount} className="w-100 h-100 mx-5 justify-content-start">
            <div className="overflow-scroll" style={{height:`calc(100vh - ${viewSize}px)`}}>
                <div className="container-fluid py-4 transition" style={{color:props.isDarkMode?"white":"black"}}>
                    <div className="row justify-content-center">
                        <div className="col-12 col-lg-10">
                            <div className="border-0">
                                <div className="p-4">
                                    {/* Header Section */}
                                    <div className="d-flex flex-column flex-md-row gap-4 mb-4">
                                        <div 
                                            className="flex-shrink-0 d-flex align-items-center justify-content-center"
                                            style={{height:"150px", width:"150px", borderRadius:"25px", backgroundColor:"#a899e7"}}
                                        >
                                            <i className="bi bi-trophy" style={{fontSize:"3rem"}}></i>
                                        </div>
                                        
                                        <div className="row w-100">
                                            <div className="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3 mb-3 w-100">
                                                <div className="mb-2 mb-sm-0 w-100">
                                                    <div className="h1 mb-0 w-100" style={{fontWeight:"700"}}>{nom}</div>
                                                </div>
                                                <div className="d-flex flex-column align-items-end w-100 gap-2">
                                                    <div>
                                                        {Array.from({length: difficulte}, (_, i) => (
                                                        <i key={i} className="bi-star-fill text-warning" style={{fontSize:"1.2rem"}}></i>
                                                    ))}
                                                    </div>
                                                    <strong>{user}</strong>

                                                </div>
                                            </div>
                                            
                                            <div className="d-flex flex-wrap gap-4 h-auto w-100">
                                                {tags.map((elem, index) => {
                                                    return (
                                                        <Badge key={index} className="w-auto shadow px-3 d-flex align-items-center gap-1" style={{cursor: "default", backgroundColor:"#a899e7", fontSize:"15px"}}>
                                                            <span>{elem}</span>
                                                            <span 
                                                                onClick={() => handleDeleteTag(index)} 
                                                                className="ms-1 d-flex align-items-center"
                                                            >
                                                            </span>
                                                        </Badge>
                                                    )
                                                })}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr className="my-4"/>
                                    
                                    {/* Description Section */}
                                    <div className="mb-4">
                                        <h3 className="h4 mb-3">Description</h3>
                                        <p className="lead" style={{fontSize:"16px", lineHeight:"1.6"}}>
                                            {description}
                                        </p>
                                    </div>

                                    {
                                        (currentDefis.fichiers && currentDefis.fichiers.length > 0) && 
                                        <>
                                            <hr className="my-4"/>
                                            <div className="d-flex flex-column align-items-center gap-5">
                                                <div style={{fontWeight:"600"}}>
                                                    Pour ce défi vous aurez besoin de télécharger certains documents présentés ci-dessous :
                                                </div>
                                                <div>
                                                    {
                                                        currentDefis.fichiers.map((elem, index) => {
                                                            return (
                                                                <div className="d-flex flex-column gap-1 align-items-center">
                                                                    fichier {index+1}
                                                                    <CustomButton 
                                                                        className="custom-button"
                                                                        darkColor={"#4625ba"}
                                                                        lightColor={"#4625ba"}
                                                                        isDarkMode={props.isDarkMode}
                                                                        onClick={() => handleFileDownload(elem.id)}
                                                                    >
                                                                        <div className="d-flex flex-row gap-2">
                                                                            Télécharger
                                                                            <i class="bi bi-cloud-arrow-down"></i>
                                                                        </div>
                                                                    </CustomButton>
                                                                </div>
                                                            )
                                                        })
                                                    }
                                                </div>
                                            </div>
                                        </>
                                    }
                                    
                                    <hr className="my-4"/>
                                    
                                    <div className="d-flex flex-column gap-2 p-2 shadow w-auto transition" style={{backgroundColor:props.isDarkMode ? "#535353" : "#f2f2f2", borderRadius:"25px"}}>
                                        <div className="d-flex flex-row gap-2 justify-content-around align-items-center">
                                            <Input onChange={(e)=> setInputValue(e.target.value)} className="w-75" style={{backgroundColor:"#e2ddf7"}} />
                                            <CustomButton
                                            className={"w-auto"}
                                            isDarkMode={props.isDarkMode}
                                            darkColor={"#4625ba"}
                                            lightColor={"#4625ba"}
                                            onClick={() => {handleDefisTest()}}
                                            >
                                                <div className="d-flex flex-row gap-2">
                                                    Envoyer
                                                    <i class="bi bi-send"></i>
                                                </div>
                                            </CustomButton>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Fade>
    )
}
