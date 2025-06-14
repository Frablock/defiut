import { Button } from "reactstrap";
import PropTypes from "prop-types"
import './css/CustomButton.css'

export default function CustomButton({ isDarkMode, darkColor, lightColor, onClick, children, style, className}) {
    return (
    <Button 
        size="sm" 
        style={{
            backgroundColor: isDarkMode ? darkColor : lightColor,
            ...style
        }}
        
        className={"shadow custom-button px-4 transition "+className}
        onClick={onClick}
        >
        {children}
    </Button>
    )
}

CustomButton.propTypes = {
    isDarkMode: PropTypes.bool.isRequired,
    darkColor: PropTypes.string.isRequired,
    lightColor: PropTypes.string.isRequired,
    onClick: PropTypes.func.isRequired,
    style: PropTypes.object,
}