import React from "react"
import {
    Input,
    Badge,
    Placeholder,
    Fade,
} from 'reactstrap';
import SelectableDropdown from "../utils/SelectableDropdown";
import SVGDispatcher from "../utils/Utils";

export default function LobbyCategory(props) {
    const [loading, setLoading] = React.useState(true)
    const [inputValue, setInputValue] = React.useState("")
    const [categoryTitle, setCategoryTitle] = React.useState("")
    const [currentFilter, setCurrentFilter] = React.useState("")
    const [filter, setFilter] = React.useState(
        [
            {
                "title": "Difficulté croissante",
                "action": 
                {
                    attribute:"difficulte",
                    action:"asc"
                }
            },
            {
                "title": "Difficulté décroissante",
                "action": 
                {
                    attribute:"difficulte",
                    action:"desc"
                }
            },
            {
                "title": "Ordre Alphabétique croissante",
                "action": 
                {
                    attribute:"nom",
                    action:"asc"
                }
            },
            {
                "title": "Ordre Alphabétique décroissante",
                "action": 
                {
                    attribute:"nom",
                    action:"desc"
                }
            },
            {
                "title": "Points croissante",
                "action": 
                {
                    attribute:"points_recompense",
                    action:"asc"
                }
            },
            {
                "title": "Points décroissante",
                "action": 
                {
                    attribute:"points_recompense",
                    action:"desc"
                }
            },
            {
                "title": "Aucun",
                "action":                 
                {
                    attribute:"",
                    action:""
                },
                "separator": "true"
            },
        ]
    )
    const [tags, setTags] = React.useState([])
    const [data, setData] = React.useState([])
    const [viewSize, setViewSize] = React.useState("0");
    const headerRef = React.useRef(null);

    React.useEffect(() => {
        if (!props.footerRef?.current || !props.navbarRef?.current || !headerRef?.current) return;

        const calculateSize = () => {
            const footerHeight = props.footerRef.current.offsetHeight;
            const navbarHeight = props.navbarRef.current.offsetHeight;
            setViewSize(footerHeight + navbarHeight + headerRef.current.offsetHeight);
        };

        calculateSize();

        const resizeObserver = new ResizeObserver(calculateSize);
        resizeObserver.observe(props.footerRef.current);
        resizeObserver.observe(props.navbarRef.current);
        resizeObserver.observe(headerRef.current);


        return () => resizeObserver.disconnect();
    }, [props.footerRef?.current, props.navbarRef?.current]);

    const handleOnClickFilter = (elem) => {
        setCurrentFilter(elem)
    }

    React.useEffect(() => {
        props.sendData({ 
            route: "/defis", 
            data:{
                category: props.category == "Tout les défis" ? null : props.category,
                filter: currentFilter.action,
                tags: tags
            },
            method:"POST"
        }).then(
            (data) => {
                if (!data.error) {
                    setData(data.data)
                    setLoading(false)
                    props.setDefis(data.data)
                }
            }
        )
    }, [props.category, currentFilter, tags])

    const handleTagsSelected = () => {
        if (inputValue.trim() !== "" && !tags.includes(inputValue.trim())) {
            setTags([...tags, inputValue.trim()]);
            setInputValue("");
        }
    }

    React.useEffect(() => {
        setTimeout(() => {
            setCategoryTitle(props.category)
        }, 150);
    }, [props.category])

    const handleDeleteTag = (indexToDelete) => {
        setTags(tags.filter((_, index) => index !== indexToDelete));
    }

    return (
        <Fade in={!props.unmount} className="w-100 h-100 mx-5 justify-content-start">
            <div className="d-flex flex-column h-100">
                <div ref={headerRef} className="row my-5 gap-2" style={{ flexShrink: 0 }}>
                    <div
                        className="transition w-auto"
                        style={{
                            textShadow: "2px 2px 5px rgba(0, 0, 0, 0.36)",
                            color: props.isDarkMode ? "#a899e7" : "#4625ba",
                            fontWeight: "700",
                            fontSize: "50px"
                        }}
                    >
                        {categoryTitle}
                    </div>
                    <SelectableDropdown className="w-auto align-content-center" items={filter} onClick={(elem) => handleOnClickFilter(elem)} />
                    <div className="row shadow align-items-center gap-2 py-2 my-3 w-auto h-auto" style={{ backgroundColor: "#a899e7", borderRadius: "20px", minHeight: "40px" }}>
                        <div className="d-flex position-relative flex-row align-items-center gap-2" style={{ width: "230px" }}>
                            <div style={{ fontWeight: "700" }}>
                                Tags:
                            </div>
                            <Input
                                className=""
                                style={{ height: "32px", paddingRight: "40px" }}
                                value={inputValue}
                                onChange={(e) => setInputValue(e.target.value)}
                                onKeyDown={(e) => {
                                    if (e.key === 'Enter') {
                                        handleTagsSelected();
                                    }
                                }}
                                placeholder="Ajouter un Tag"
                            />
                            <div onClick={handleTagsSelected} className="position-absolute end-0 me-4" style={{ cursor: "pointer", top: "2px" }}>
                                <SVGDispatcher type="plus" color="black" />
                            </div>
                        </div>
                        {tags.map((elem, index) => {
                            return (
                                <Badge key={index} className="w-auto shadow d-flex align-items-center gap-1" style={{ cursor: "default", backgroundColor: "#a899e7" }}>
                                    <span>{elem}</span>
                                    <span
                                        onClick={() => handleDeleteTag(index)}
                                        style={{ cursor: "pointer" }}
                                        className="ms-1 d-flex align-items-center"
                                    >
                                        <SVGDispatcher type="close" color="white" />
                                    </span>
                                </Badge>
                            )
                        })}
                    </div>
                </div>
                <div className="w-100 d-flex pb-5 px-4 pt-3 flex-row flex-wrap gap-5 overflow-scroll align-items-center justify-content-around"
                    style={{ height: `calc(100vh - ${96 + viewSize}px)` }}
                >
                    {loading ?

                        <>
                            {
                                Array.from({ length: 12 }, (_, index) => (
                                    <HandleDefi props={props} loading={loading} index={index} isDarkMode={props.isDarkMode} />
                                ))
                            }
                        </>
                        :
                        <>
                            {
                                data.map((elem, index) => {
                                    return (
                                        <>
                                            <HandleDefi props={props} loading={loading} elem={elem} index={index} isDarkMode={props.isDarkMode} />
                                        </>
                                    )
                                })
                            }
                        </>
                    }
                </div>
            </div>
        </Fade>
    )
}

function HandleDefi({ props, loading, index, elem, isDarkMode }) {
    // Extract the defi data
    const { nom, description, difficulte, user, tags = [] } = elem || {};
    const pointsRecompense = elem ? elem['points_recompense'] : null

    return (
        <div
            className=" d-flex flex-column p-3 shadow transition"
            style={{ backgroundColor: isDarkMode ? "#535353" : "#e2ddf7", borderRadius: "25px", width: "500px", height: "380px", cursor: "pointer", transition: "transform 0.2s", color: isDarkMode ? "white" : "black" }}
            onMouseOver={(e) => e.currentTarget.style.transform = 'scale(1.02)'}
            onMouseOut={(e) => e.currentTarget.style.transform = 'scale(1)'}
            onClick={() => props.navigateTo('/defis/' + elem['id'])}
        >
            <div className="d-flex flex-row gap-3">
                {loading ? (
                    <Placeholder key={index} animation={"glow"}>
                        <Placeholder key={index} style={{ height: "110px", width: "110px", borderRadius: "25px" }} />
                    </Placeholder>
                ) : (
                    <div style={{ height: "110px", width: "110px", borderRadius: "25px", backgroundColor: "#a899e7" }} className="d-flex align-items-center justify-content-center">
                        <i className="bi bi-trophy" style={{ fontSize: "2rem" }}></i>
                    </div>
                )}

                <div className="d-flex flex-column w-100">
                    <div className="d-flex flex-row gap-2 justify-content-between w-100">
                        <div className="w-auto h-auto" style={{ fontSize: "15px" }}>
                            {loading ? (
                                <Placeholder animation="wave" tag="p" style={{ width: "100px" }}>
                                    <Placeholder xs={12} />
                                </Placeholder>
                            ) : (
                                <small>{user}</small>
                            )}
                        </div>
                        <div className="w-auto h-auto">
                            {loading ? (
                                Array.from({ length: difficulte || 4 }, (_, i) => (
                                    <i key={i} className="bi-star"></i>
                                ))
                            ) : (
                                Array.from({ length: difficulte }, (_, i) => (
                                    <i key={i} className="bi-star-fill text-warning"></i>
                                ))
                            )}
                        </div>
                    </div>
                    <div className="row gap-2 ms-1 mt-3">
                        {loading ? (
                            Array.from({ length: 2 }, ((_, index) => (
                                <Badge pill key={index} className="w-auto h-auto px-4 shadow" style={{ fontSize: "15px" }}>
                                    <Placeholder className="h-auto mb-1" animation="wave" tag="p" style={{ width: "80px" }}>
                                        <Placeholder className="h-auto" xs={12} />
                                    </Placeholder>
                                </Badge>
                            )))
                        ) : (
                            tags.map((tag, tagIndex) => (
                                <Badge pill key={tagIndex} className="w-auto h-auto px-4 shadow" style={{ fontSize: "15px" }}>
                                    {tag}
                                </Badge>
                            ))
                        )}
                    </div>
                </div>
            </div>

            {loading ? (
                <Placeholder className="h-auto mb-1 mt-2" animation="wave" tag="p" style={{ width: "140px" }}>
                    <Placeholder className="h-auto" xs={12} />
                </Placeholder>
            ) : (
                <div className="mt-2">
                    <strong style={{ fontSize: "25px" }} className="underline">{nom}</strong>
                </div>
            )}

            <hr />
            <div className="d-flex flex-column align-items-center">
                {loading ? (
                    <Placeholder className="h-auto mb-1" animation="wave" tag="p" style={{ width: "400px" }}>
                        <Placeholder className="h-auto" xs={12} />
                        <Placeholder className="h-auto" xs={12} />
                        <Placeholder className="h-auto" xs={12} />
                    </Placeholder>
                ) : (
                    <p className="" style={{ fontSize: "14px" }}>
                        {description.length > 200 ? `${description.substring(0, 200)}...` : description}
                    </p>
                )}

                <div className="d-flex flex-row justify-content-center align-items-center">
                    Points :
                    {loading ? (
                        <Placeholder className="h-auto mb-1 ms-2" animation="wave" tag="p" style={{ width: "40px" }}>
                            <Placeholder className="h-auto" xs={12} />
                        </Placeholder>
                    ) : (
                        <strong className="ms-2 text-success">{pointsRecompense}</strong>
                    )}
                </div>
            </div>
            <hr />
            <div className="d-flex flex-row gap-2 justify-content-center">
                Cliquez pour voir le défis
                <i className="bi bi-mouse"></i>
            </div>
        </div>
    )
}
