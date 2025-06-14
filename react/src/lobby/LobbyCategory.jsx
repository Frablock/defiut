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
                <Dropdown isOpen={dropdownOpen} toggle={() => setDropdownOpen(!dropdownOpen)}>
                    <DropdownToggle caret>Filtres</DropdownToggle>
                    <DropdownMenu {...props}>
                        <DropdownItem>Difficulté croissante</DropdownItem>
                        <DropdownItem>Difficulté décroissante</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </h1>
        </div>
    </div>
    )
}