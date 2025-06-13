import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import RoutesDispatcher from './RoutesDispatcher';
import { BrowserRouter } from 'react-router-dom';
import AppNavbar from './utils/AppNavbar';
import AppFooter from './utils/AppFooter';
import LeftNavigation from './utils/LeftNavigation';
import Leaderboard from './utils/Leaderboard';

export default function App(props) {
    const [isLogedIn, setLogedIn] = useState(false);
    const [isDarkMode, setDarkMode] = useState(false);
    const [showLeftNavigation, setShowLeftNavigation] = useState(true);
    const [showLeaderboard, setShowLeaderboard] = useState(true);
    
    return (
        <div className='d-flex flex-column h-100' 
            style={{backgroundColor: isDarkMode ? "#434343" : "#f0f0f0", transition: "all 0.8s"}}
        >
            <BrowserRouter>
                <AppNavbar isLogedIn={isLogedIn} isDarkMode={isDarkMode} setDarkMode={setDarkMode}/>
                <div className='h-100'>
                    <LeftNavigation {...{showLeftNavigation, setShowLeftNavigation}}/>
                    <RoutesDispatcher {...{showLeftNavigation, setShowLeftNavigation, showLeaderboard, setShowLeaderboard, isLogedIn, isDarkMode}}/>
                    <Leaderboard {...{showLeaderboard, setShowLeaderboard}}/>
                </div>
                <AppFooter isDarkMode={isDarkMode} />
            </BrowserRouter>
        </div>
    );
}