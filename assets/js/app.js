import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import RoutesDispatcher from './RoutesDispatcher';
import { BrowserRouter } from 'react-router-dom';
import AppNavbar from './utils/AppNavbar';

function App(props) {
    const [isLogedIn, setLogedIn] = useState(false);
    const [isDarkMode, setDarkMode] = useState(false)
    
    return (
        <>
            <AppNavbar {...isLogedIn} />
            <BrowserRouter>
                <RoutesDispatcher isLogedIn={isLogedIn} />
            </BrowserRouter>
        </>
    );
}

// Mount the component to the DOM
const domContainer = document.getElementById('root');
if (domContainer) {
    const root = createRoot(domContainer);
    root.render(<App />);
} else {
    console.log('WARNING : div ROOT non trouvé !')
}

export default App;