import React from "react"
import {
  Dropdown,
  DropdownToggle,
  DropdownMenu,
  DropdownItem,
} from 'reactstrap';

export default function LobbyCategory(props) {
    const [loading, setLoading] = React.useState()
    const [dropdownOpen, setDropdownOpen] = React.useState()
    const [currentFilter, setCurrentFilter] = React.useState(
        {
            "title": "Fitlres",
            "action": ""
        }
    )
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

    const handleOnClickFilter = () => {
        console.log("test")
    }
    
    return (
    <div className="column">
        <div className="row my-5">
            <h1>
                <div 
                className="transition" 
                style={{
                    textShadow: "2px 2px 5px rgba(0, 0, 0, 0.36)",
                    color: props.isDarkMode ? "#a899e7" : "#4625ba",
                    fontWeight:"600"
                }}
                >
                    {props.category.charAt(0).toUpperCase() + props.category.slice(1)}
                </div>
                
            </h1>
            <Dropdown isOpen={dropdownOpen} toggle={() => setDropdownOpen(!dropdownOpen)}>
                <DropdownToggle caret style={{backgroundColor:"#a899e7"}}>Filtres</DropdownToggle>
                <DropdownMenu {...props} style={{backgroundColor:"#e2ddf7"}}>
                    {filter.map((elem, _) => {
                        <DropdownItem onCLick={() => handleOnClickFilter(elem.action)}>{elem.title} test</DropdownItem>
                    })}
                </DropdownMenu>
            </Dropdown>
        </div>
    </div>
    )
}