import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import RoutesDispatcher from './RoutesDispatcher';
import { BrowserRouter } from 'react-router-dom';
import AppNavbar from './utils/AppNavbar';

export default function App(props) {
    const [isLogedIn, setLogedIn] = useState(false);
    const [isDarkMode, setDarkMode] = useState(false)
    
    return (
        <div className='d-flex flex-column h-100'>
            <AppNavbar isLogedIn={isLogedIn} isDarkMode={isDarkMode} setDarkMode={setDarkMode}/>
            <BrowserRouter>
                <RoutesDispatcher isLogedIn={isLogedIn} isDarkMode={isDarkMode} />
            </BrowserRouter>
        </div>
    );
}