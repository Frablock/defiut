import React from "react"
import {
  Dropdown,
  DropdownToggle,
  DropdownMenu,
  DropdownItem,
  Input,
  Badge,
} from 'reactstrap';
import SelectableDropdown from "../utils/SelectableDropdown";
import SVGDispatcher from "../utils/Utils";

export default function LobbyCategory(props) {
    const [loading, setLoading] = React.useState()
    const [dropdownOpen, setDropdownOpen] = React.useState()
    const [inputValue, setInputValue] = React.useState("") // Add this state
    const [filter, setFilter] = React.useState(
        [
            {
                "title" : "Difficulté croissante",
                "action": "asc"
            },
            {
                "title" : "Difficulté décroissante",
                "action": "desc"
            }
        ]
    )
    const [tags, setTags] = React.useState([])

    const handleOnClickFilter = (elem) => {
        console.log("test")
    }

    const handleTagsSelected = () => {
        if (inputValue.trim() !== "" && !tags.includes(inputValue.trim())) {
            setTags([...tags, inputValue.trim()]);
            setInputValue(""); // Clear input after adding
        }
    }

    // Add delete function
    const handleDeleteTag = (indexToDelete) => {
        setTags(tags.filter((_, index) => index !== indexToDelete));
    }
    
    return (
    <div className="column">
        <div className="row my-5 gap-2">
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

        <div>
            
        </div>
    </div>
    )
}
