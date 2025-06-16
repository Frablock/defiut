import React from "react"
import {
  Input,
  Badge,
  Placeholder,
} from 'reactstrap';
import SelectableDropdown from "../utils/SelectableDropdown";
import SVGDispatcher, { sendData } from "../utils/Utils";

export default function LobbyCategory(props) {
    const [loading, setLoading] = React.useState(true)
    const [dropdownOpen, setDropdownOpen] = React.useState()
    const [inputValue, setInputValue] = React.useState("")
    const [filter, setFilter] = React.useState(
        [
            {
                "title" : "Difficulté croissante",
                "action": "asc"
            },
            {
                "title" : "Difficulté décroissante",
                "action": "desc"
            },
            {
                "title": "Aucun",
                "action": "",
                "separator": "true"
            }
        ]
    )
    const [tags, setTags] = React.useState([])
    const [viewSize, setViewSize] = React.useState("0");
    const headerRef = React.useRef(null);

    React.useEffect(() => {
        if (!props.footerRef?.current || !props.navbarRef?.current || !headerRef?.current) return;

        const calculateSize = () => {
            const footerHeight = props.footerRef.current.offsetHeight;
            const navbarHeight = props.navbarRef.current.offsetHeight;
            setViewSize(footerHeight + navbarHeight + headerRef.current.offsetHeight );
        };

        calculateSize();

        const resizeObserver = new ResizeObserver(calculateSize);
        resizeObserver.observe(props.footerRef.current);
        resizeObserver.observe(props.navbarRef.current);
        resizeObserver.observe(headerRef.current);


        return () => resizeObserver.disconnect();
    }, [props.footerRef?.current, props.navbarRef?.current]);

    React.useEffect(() => {
        sendData({route:"/defis"})
    })


    const handleOnClickFilter = (elem) => {
        console.log(viewSize)
    }

    const handleTagsSelected = () => {
        if (inputValue.trim() !== "" && !tags.includes(inputValue.trim())) {
            setTags([...tags, inputValue.trim()]);
            setInputValue("");
        }
    }

    const handleDeleteTag = (indexToDelete) => {
        setTags(tags.filter((_, index) => index !== indexToDelete));
    }
    
    return (
    <div className="d-flex flex-column h-100">
        <div ref={headerRef} className="row my-5 gap-2" style={{ flexShrink: 0 }}>
            <div 
                className="transition w-auto" 
                style={{
                    textShadow: "2px 2px 5px rgba(0, 0, 0, 0.36)",
                    color: props.isDarkMode ? "#a899e7" : "#4625ba",
                    fontWeight:"700",
                    fontSize:"50px"
                }}
                >
                    {props.category.charAt(0).toUpperCase() + props.category.slice(1)}
            </div>
            <SelectableDropdown className="w-auto align-content-center" items={filter} onClick={(elem) => handleOnClickFilter(elem)}/>
            <div className="row shadow align-items-center gap-2 py-2 my-3 w-auto h-auto" style={{backgroundColor:"#a899e7", borderRadius:"20px", minHeight:"40px"}}>
                <div className="d-flex position-relative flex-row align-items-center gap-2" style={{width:"230px"}}>
                    <div style={{fontWeight:"700"}}>
                        Tags:
                    </div>
                    <Input 
                        className="" 
                        style={{height:"32px", paddingRight:"40px"}}
                        value={inputValue}
                        onChange={(e) => setInputValue(e.target.value)}
                        onKeyDown={(e) => {
                            if (e.key === 'Enter') {
                                handleTagsSelected();
                            }
                        }}
                        placeholder="Ajouter un Tag"
                        />
                    <div onClick={handleTagsSelected} className="position-absolute end-0 me-4" style={{cursor:"pointer", top:"2px"}}>
                        <SVGDispatcher type="plus" color="black"/>
                    </div>
                </div>
                {tags.map((elem, index) => {
                    return (
                        <Badge key={index} className="w-auto shadow d-flex align-items-center gap-1" style={{cursor: "default", backgroundColor:"#a899e7"}}>
                            <span>{elem}</span>
                            <span 
                                onClick={() => handleDeleteTag(index)} 
                                style={{cursor: "pointer"}}
                                className="ms-1 d-flex align-items-center"
                            >
                                <SVGDispatcher type="close" color="white"/>
                            </span>
                        </Badge>
                    )
                })}
            </div>
        </div>
        <div className="w-100 d-flex pb-5 px-4 pt-3 flex-row flex-wrap gap-5 overflow-scroll align-items-center justify-content-center"
            style={{height:`calc(100vh - ${96+viewSize}px)`}}
        >
            {
                Array.from({ length: 12 }, (_, index) => (
                    <div 
                        className=" d-flex flex-column h-auto w-auto p-3 shadow" 
                        style={{backgroundColor:"#e2ddf7", borderRadius:"25px", minWidth:"440px", maxWidth:"500px", cursor:"pointer", transition: "transform 0.2s"}}
                        onMouseOver={(e) => e.currentTarget.style.transform = 'scale(1.02)'}
                        onMouseOut={(e) => e.currentTarget.style.transform = 'scale(1)'}
                    >
                        <div className="d-flex flex-row gap-3">
                            <Placeholder key={index} animation={"glow"}>
                                <Placeholder key={index} style={{height:"110px", width:"110px", borderRadius:"25px"}} />
                            </Placeholder>
                            <div className="d-flex flex-column w-100">
                                <div className="d-flex flex-row gap-2 justify-content-between w-100">
                                    <div className="w-auto h-auto" style={{fontSize:"15px"}}>
                                        <Placeholder animation="wave" tag="p" style={{width:"100px"}}>
                                            <Placeholder xs={12} />
                                        </Placeholder>
                                    </div>
                                    <div className="w-auto h-auto">
                                        {Array.from({length: 5}, () => (
                                            <>
                                                <i className="bi-star"></i>
                                            </>
                                        ))}
                                    </div>
                                </div>
                                <div className="row gap-2 ms-1">
                                    {Array.from({length: 2}, ((_, index) => (
                                        <Badge pill key={index} className="w-auto h-auto px-4 shadow" style={{fontSize:"15px", backgroundColor:"#a899e7"}}>
                                            <Placeholder className="h-auto mb-1" animation="wave" tag="p" style={{width:"80px"}}>
                                                <Placeholder className="h-auto" xs={12} />
                                            </Placeholder>
                                        </Badge>
                                    )))}
                                </div>
                            </div>
                        </div>
                        {loading ? 
                        <Placeholder className="h-auto mb-1 mt-2" animation="wave" tag="p" style={{width:"140px"}}>
                            <Placeholder className="h-auto" xs={12} />
                        </Placeholder>
                        :
                        <>
                        </>
                        }
                        <hr/>
                        <div className="d-flex flex-column align-items-center">
                            <Placeholder className="h-auto mb-1" animation="wave" tag="p" style={{width:"400px"}}>
                                <Placeholder className="h-auto" xs={12} />
                                <Placeholder className="h-auto" xs={12} />
                                <Placeholder className="h-auto" xs={12} />
                            </Placeholder>
                            <div className="d-flex flex-row justify-content-center align-items-center">
                                Points : 
                                    {
                                    loading ? 
                                    <Placeholder className="h-auto mb-1 ms-2" animation="wave" tag="p" style={{width:"40px"}}>
                                        <Placeholder className="h-auto" xs={12} />
                                    </Placeholder>
                                    :
                                    <>
                                    </>
                                }

                            </div>
                        </div>
                        <hr/>
                        <div className="d-flex flex-row gap-2 justify-content-center">
                            Cliquez pour voir le défis
                            <i class="bi bi-mouse"></i>
                        </div>
                    </div>
                ))
            }
        </div>
    </div>
    )
}
