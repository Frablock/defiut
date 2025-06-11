import React, { useState, useRef } from "react";

function DarkModeSwitch(props) {
    const [isChecked, setIsChecked] = useState(false);
    const switchBoxRef = useRef(null);

    const handleToggle = (e) => {
        const checked = e.target.checked;
        setIsChecked(checked);

        if (switchBoxRef.current) {
            if (checked) {
                props.setDarkMode(true)
                switchBoxRef.current.classList.add("move");
            } else {
                props.setDarkMode(false)
                switchBoxRef.current.classList.remove("move");
            }
            
        }
    };

    return (
        <div className="sun-moon" ref={switchBoxRef}>
            <input 
                type="checkbox" 
                checked={isChecked} 
                onChange={handleToggle} 
            />
            <span className="circle large" />
            <span className="circle small" />
        </div>
    );
}

export default DarkModeSwitch;
