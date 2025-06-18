import React, { useState, useRef } from "react";

function DarkModeSwitch(props) {
    const switchBoxRef = useRef(null);
    
    const handleToggle = (e) => {
        if (props.isDarkMode) {
            //to move to the sun 
            switchBoxRef.current.classList.add("move");
        } else {
            //to move to the moon
            switchBoxRef.current.classList.remove("move");
        }
        props.setDarkMode(() => !props.isDarkMode)
    };

    return (
        <div className="sun-moon shadow" ref={switchBoxRef} style={{height:props.height}}>
            <input 
                type="checkbox" 
                checked={props.darkMode} 
                onClick={handleToggle}
            />
            <span className="circle large" />
            <span className="circle small" />
        </div>
    );
}

export default DarkModeSwitch;
