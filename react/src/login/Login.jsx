import React from "react";
import { useNavigate } from "react-router-dom";
import { Button, Form, FormGroup, Input, Label } from "reactstrap";
import CustomButton from "../utils/CustomButton";

function Login(props) {
   const navigate = useNavigate();
   
   const handleOnClickLogin = () => {
       navigate("/app");
   };
   
   return (
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
                    color:props.isDarkMode?"white":"#4625ba",
                    transition:"color 0.8s",
                    fontWeight:"600",
                }}
            >
            Connexion
           </h1>
            <div className="d-flex flex-column gap-3 align-items-center w-25">
                <FormGroup floating>
                    <Input
                        id="exampleEmail"
                        name="email"
                        placeholder="Email"
                        type="email"
                        className="shadow"
                    />
                    <Label for="exampleEmail">
                        Email
                    </Label>
                </FormGroup>
                <FormGroup floating>
                    <Input
                        id="examplePassword"
                        name="password"
                        placeholder="Password"
                        type="password"
                        className="shadow w-100"
                    />
                    <Label for="examplePassword">
                        Mot de passe
                    </Label>
                </FormGroup>
            </div>
            <div className="d-flex flex-column gap-4">
                <CustomButton
                    lightColor={"#4625ba"}
                    darkColor={"#4625ba"}
                >
                    <div className="d-flex flex-row align-items-center justify-content-center position-relative">
                        <div style={{fontWeight:"650"}}>Se connecter</div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right position-absolute" style={{right:"0"}} viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                        </svg>
                    </div>
                </CustomButton>
                <CustomButton
                    lightColor={"#a899e7"}
                    darkColor={"#a899e7"}
                >
                    <div className="d-flex flex-row align-items-center justify-content-between" style={{}}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-google" viewBox="0 0 16 16">
                            <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
                        </svg>
                        <div style={{fontWeight:"650", color:"black"}}>Google</div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                        </svg>
                    </div>
                </CustomButton>
                <div className="d-flex flex-row" style={{color: props.isDarkMode ? "white" : "black", transition: "all 0.8s"}}>
                    Pas de comptes ? &nbsp;
                    <div className="text-decoration-underline" style={{cursor:"pointer"}} onClick={() => alert("tu as cliqué !!")}>
                        S'inscrire ici
                    </div>
                </div>
            </div>
       </div>
   );
}

export default Login;