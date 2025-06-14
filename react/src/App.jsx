import React, { useState } from 'react';
import RoutesDispatcher from './RoutesDispatcher';
import { useLocation, useNavigate } from 'react-router-dom';
import AppNavbar from './utils/AppNavbar';
import AppFooter from './utils/AppFooter';
import LeftNavigation from './utils/LeftNavigation';
import Leaderboard from './utils/Leaderboard';
import { Fade } from 'reactstrap';

export default function App(props) {
    const location = useLocation();
    const navigate = useNavigate();
    
    const [isLogedIn, setLogedIn] = useState(false);
    const [isDarkMode, setDarkMode] = useState(false);
    const [showLeftNavigation, setShowLeftNavigation] = useState(true);
    const [showLeaderboard, setShowLeaderboard] = useState(true);
    const [unmount, setUnmount] = useState(false);
    const [category, setCategory] = useState(false);

    const navigateTo = (url) => {
        if(location.pathname != url){
            setUnmount(true)
            setCategory(null)
            setTimeout(() => {
                navigate(url)
                setUnmount(false)
            }, 150);
        }
        
    }

    return (
        <Fade className='d-flex flex-column h-100' 
            style={{backgroundColor: isDarkMode ? "#434343" : "#f0f0f0", transition: "all 0.8s"}}
        >
            <AppNavbar {...{navigateTo,setDarkMode, isDarkMode, isLogedIn}}/>
            <div className='d-flex flex-row justify-content-between h-100'>
                <LeftNavigation {...{showLeftNavigation, setShowLeftNavigation, isDarkMode, navigateTo, category, setCategory}}/>
                <RoutesDispatcher {...{showLeftNavigation, setShowLeftNavigation, showLeaderboard, setShowLeaderboard, isLogedIn, isDarkMode, unmount, setUnmount, navigateTo, category}}/>
                <Leaderboard {...{showLeaderboard, setShowLeaderboard, isDarkMode, navigateTo}}/>
            </div>
            <AppFooter {...{isDarkMode, navigateTo}} />
        </Fade>
    );
}