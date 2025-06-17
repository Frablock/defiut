import React, { useState } from "react";
import { Button, Fade, Form, FormGroup, Input, Label } from "reactstrap";
import CustomButton from "../utils/CustomButton";

function Login(props) {
    const [formData, setFormData] = useState({
        usermail: "",
        password: ""
    });
    const [errors, setErrors] = useState({
        usermail: false,
        password: false
    });

    React.useEffect(() => {
        props.setShowLeftNavigation(false)
        props.setShowLeaderboard(false)
    }, [])

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
        
        if (errors[name]) {
            setErrors(prev => ({
                ...prev,
                [name]: false
            }));
        }
    };

    const validateForm = () => {
        const newErrors = {
            usermail: !formData.usermail.trim(),
            password: !formData.password.trim()
        };
        
        setErrors(newErrors);
        return !Object.values(newErrors).some(error => error);
    };

    const handleLoginRequest = async () => {
        if (validateForm()) {
            try {
                const response = await props.sendData({
                    route: '/login',
                    method: "POST",
                    data: {
                        usermail: formData.usermail,
                        password: formData.password
                    }
                });

                if (!response.error) {
                    // Store token as cookie with expiration date
                    const expirationDate = new Date(response.data.expirationDate);
                    document.cookie = `auth_token=${response.data.token}; expires=${expirationDate.toUTCString()}; path=/; secure; samesite=strict`;
                    
                    // Update app state
                    props.setLogedIn(true);
                    props.setAuthToken(response.data.token);
                    
                    props.navigateTo('/lobby');
                } else {
                    console.error('Login failed:', response.error_message);
                }
            } catch (error) {
                console.error('Login request failed:', error);
            }
        }
    };

    const handleGoogleLogin = () => {
        // Add Google login logic here if needed
        console.log("Google login clicked");
    };

    return (
        <Fade in={!props.unmount} className="w-100 h-100">
            <div className="d-flex flex-column w-100 h-100 mt-5 gap-4 align-items-center">
                <div className="position-relative" style={{height:"20%", marginTop:"100px"}}>
                    <img
                        alt="dark logo"
                        src="/files/images/darkLogoDefiut.png"
                        style={{
                            position: "absolute",
                            transform: "translateX(-50%)",
                            height: "5vw",
                            minHeight:"70px",
                            opacity: props.isDarkMode ? 0 : 1,
                            transition: "opacity 0.8s",
                        }}
                    />
                    <img
                        alt="light logo"
                        src="/files/images/whiteLogoDefiut.png"
                        style={{
                            position: "absolute",
                            transform: "translateX(-50%)",
                            height: "5vw",
                            minHeight:"70px",
                            opacity: props.isDarkMode ? 1 : 0,
                            transition: "opacity 0.8s",
                        }}
                    />
                </div>
                <h1
                    style={{
                        textShadow: "2px 2px 5px rgba(0, 0, 0, 0.36)",
                        color: props.isDarkMode ? "white" : "#4625ba",
                        transition: "color 0.8s",
                        fontWeight: "600",
                    }}
                >
                    Connexion
                </h1>
                <div className="d-flex flex-column gap-3 align-items-center" style={{width:"40vw", maxWidth:"300px"}}>
                    <FormGroup floating className="w-100">
                        <Input
                            id="loginEmail"
                            name="usermail"
                            placeholder="Email"
                            type="email"
                            className="shadow"
                            value={formData.usermail}
                            onChange={handleInputChange}
                            invalid={errors.usermail}
                        />
                        <Label for="loginEmail">
                            Email
                        </Label>
                    </FormGroup>
                    <FormGroup floating className="w-100">
                        <Input
                            id="loginPassword"
                            name="password"
                            placeholder="Password"
                            type="password"
                            className="shadow w-100"
                            value={formData.password}
                            onChange={handleInputChange}
                            invalid={errors.password}
                        />
                        <Label for="loginPassword">
                            Mot de passe
                        </Label>
                    </FormGroup>
                </div>
                <div className="d-flex flex-column gap-4">
                    <CustomButton
                        lightColor={"#4625ba"}
                        darkColor={"#4625ba"}
                        onClick={handleLoginRequest}
                    >
                        <div className="d-flex flex-row align-items-center justify-content-center position-relative">
                            <div style={{fontWeight:"650"}}>Se connecter</div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-arrow-right position-absolute" style={{right:"0"}} viewBox="0 0 16 16">
                                <path fillRule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                            </svg>
                        </div>
                    </CustomButton>
                    <CustomButton
                        lightColor={"#a899e7"}
                        darkColor={"#a899e7"}
                        onClick={handleGoogleLogin}
                    >
                        <div className="d-flex flex-row align-items-center justify-content-between">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" className="bi bi-google" viewBox="0 0 16 16">
                                <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
                            </svg>
                            <div style={{fontWeight:"650", color:"black"}}>Google</div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" className="bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fillRule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                            </svg>
                        </div>
                    </CustomButton>
                    <div className="d-flex flex-row" style={{color: props.isDarkMode ? "white" : "black", transition: "all 0.8s"}}>
                        Pas de compte ? &nbsp;
                        <div className="text-decoration-underline" style={{cursor:"pointer"}} onClick={() => props.navigateTo("/register")}>
                            S'inscrire ici
                        </div>
                    </div>
                </div>
            </div>
        </Fade>
    );
}

export default Login;
