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

    // Function to get cookie value
    const getCookie = (name) => {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    };
    
    const [authToken, setAuthToken] = useState(() => getCookie('auth_token') || '');
    const [isLogedIn, setLogedIn] = useState(() => !!getCookie('auth_token'));
    const [isDarkMode, setDarkMode] = useState(true);
    const [showLeftNavigation, setShowLeftNavigation] = useState(true);
    const [showLeaderboard, setShowLeaderboard] = useState(true);
    const [unmount, setUnmount] = useState(false);
    const [category, setCategory] = useState(false);
    const navbarRef = React.useRef(null);
    const footerRef = React.useRef(null);
    const [defis, setDefis] = React.useState({})

    // Function to delete cookie
    const deleteCookie = (name) => {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    };

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

    const sendData = async ({route = "/", data = {}, method="GET", isFileDownload = false}) => {
        let options = {
            method: method,
            headers: {}
        }
        
        // Add authorization header if token exists
        if(authToken) {
            options.headers['Authorization'] = authToken;
        }
        
        // For file downloads, don't set Accept header to application/json
        if (!isFileDownload) {
            options.headers['Accept'] = 'application/json';
        }
        
        if(method == "POST"){
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }
        
        console.log(data)
        
        try {
            const response = await fetch("/api"+route, options);
            
            if (response.status === 401 && isLogedIn) {
                logout();
                return { error: true, error_message: "Session expired" };
            }
            
            // Handle file download
            if (isFileDownload) {
                if (!response.ok) {
                    return { error: true, error_message: `Download failed: ${response.statusText}` };
                }
                
                // Get filename from Content-Disposition header
                const contentDisposition = response.headers.get('Content-Disposition');
                let filename = 'download';
                
                if (contentDisposition) {
                    const match = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                    if (match && match[1]) {
                        filename = match[1].replace(/['"]/g, '');
                    }
                }
                
                // Convert to blob and trigger download
                const blob = await response.blob();
                const url = URL.createObjectURL(blob);
                
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = filename;
                
                document.body.appendChild(a);
                a.click();
                
                // Cleanup
                URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                return { success: true, message: "File downloaded successfully" };
            }
            
            // Handle regular JSON response
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
            <AppNavbar {...{sendData,navigateTo, setDarkMode, isDarkMode, isLogedIn, navbarRef, logout}}/>
            <div className='d-flex flex-row justify-content-between h-100'>
                <LeftNavigation {...{sendData,showLeftNavigation, setShowLeftNavigation, isDarkMode, navigateTo, category, setCategory}}/>
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
                    logout,
                    defis,
                    setDefis
                }}/>
                <Leaderboard {...{sendData,showLeaderboard, setShowLeaderboard, isDarkMode, navigateTo, sendData}}/>
            </div>
            <AppFooter {...{sendData,isDarkMode, navigateTo, footerRef}} />
        </Fade>
    );
}