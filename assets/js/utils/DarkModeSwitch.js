import React, { useState, useRef } from "react";

function DarkModeSwitch() {
    const [isChecked, setIsChecked] = useState(false);
    const switchBoxRef = useRef(null);

    const handleToggle = (e) => {
        const checked = e.target.checked;
        setIsChecked(checked);

        // Apply "move" class based on checkbox state
        if (switchBoxRef.current) {
            if (checked) {
                switchBoxRef.current.classList.add("move");
            } else {
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
