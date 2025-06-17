import React, { useState, useEffect } from 'react';
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
    const [authToken, setAuthToken] = useState('');
    const [isDarkMode, setDarkMode] = useState(true);
    const [showLeftNavigation, setShowLeftNavigation] = useState(true);
    const [showLeaderboard, setShowLeaderboard] = useState(true);
    const [unmount, setUnmount] = useState(false);
    const [category, setCategory] = useState(false);
    const navbarRef = React.useRef(null);
    const footerRef = React.useRef(null);

    // Function to get cookie value
    const getCookie = (name) => {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    };

    // Function to delete cookie
    const deleteCookie = (name) => {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    };

    // Check for existing token on app load
    useEffect(() => {
        const token = getCookie('auth_token');
        if (token) {
            setAuthToken(token);
            setLogedIn(true);
        }
    }, []);

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

    const logout = () => {
        deleteCookie('auth_token');
        setAuthToken('');
        setLogedIn(false);
        navigateTo('/login');
    };

    const sendData = async ({route = "/", data = {}, method="GET"}) => {
        let options = {method: method}
        
        const requestToken = authToken;
        
        if(method == "POST"){
            options.headers = {
                "Content-Type": "application/json",
            }
            options.body = JSON.stringify(data)
        } else {
            options.headers = options.headers || {};
        }
        
        // Add authorization header if token exists
        if(requestToken) {
            options.headers = {
                ...options.headers,
                "Authorization": `Bearer ${requestToken}`
            };
        }
        
        try {
            const response = await fetch("/api"+route, options);
            
            // Check if token is expired (401 Unauthorized)
            if (response.status === 401 && isLogedIn) {
                logout(); // Auto logout if token expired
                return { error: true, error_message: "Session expired" };
            }
            
            return response.json();
        } catch (error) {
            console.error('Request failed:', error);
            return { error: true, error_message: "Network error" };
        }
    }

    return (
        <Fade className='d-flex flex-column h-100' 
            style={{backgroundColor: isDarkMode ? "#434343" : "#f0f0f0", transition: "all 0.8s"}}
        >
            <AppNavbar {...{navigateTo, setDarkMode, isDarkMode, isLogedIn, navbarRef, logout}}/>
            <div className='d-flex flex-row justify-content-between h-100'>
                <LeftNavigation {...{showLeftNavigation, setShowLeftNavigation, isDarkMode, navigateTo, category, setCategory}}/>
                <RoutesDispatcher {...{
                    navbarRef, 
                    footerRef, 
                    showLeftNavigation, 
                    setShowLeftNavigation, 
                    showLeaderboard, 
                    setShowLeaderboard, 
                    isLogedIn, 
                    setLogedIn,
                    authToken,
                    setAuthToken,
                    isDarkMode, 
                    unmount, 
                    setUnmount, 
                    navigateTo, 
                    category,
                    sendData,
                    logout
                }}/>
                <Leaderboard {...{showLeaderboard, setShowLeaderboard, isDarkMode, navigateTo, sendData}}/>
            </div>
            <AppFooter {...{isDarkMode, navigateTo, footerRef}} />
        </Fade>
    );
}