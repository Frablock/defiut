import React, {useState} from 'react';
import { Button, Collapse, Navbar, NavbarBrand, NavbarText, NavbarToggler } from 'reactstrap';
import DarkModeSwitch from './DarkModeSwitch';
import CustomButton from './CustomButton';


function AppNavbar(props) {

 return (
  <>
<Navbar
  className="w-100 shadow"
  style={{
    backgroundColor: props.isDarkMode ? "#535353" : "#a899e7",
    transition: "background-color 0.8s ease, opacity 0.8s"
  }}
  ref={props.navbarRef}
>
    <div style={{cursor:"pointer"}} onClick={() => props.navigateTo("/lobby")}>  
    <img
      alt="dark logo"
      src="/files/images/darkLogoDefiut.png"
      style={{
        position: "absolute",
        top:"50%",
        transform: "translateY(-50%)",
        height: 40,
        opacity: props.isDarkMode ? 0 : 1,
        transition: "opacity 0.8s",
      }}
    />
    <img
      alt="light logo"
      src="/files/images/whiteLogoDefiut.png"
      style={{
        position: "absolute",
        transform: "translateY(-50%)",
        height: 40,
        opacity: props.isDarkMode ? 1 : 0,
        transition: "opacity 0.8s",
      }}
    />
    </div>
    <NavbarText style={{display:"flex", gap:"10px", flexWrap: "wrap", alignItems: "center"}}>
      <DarkModeSwitch 
        setDarkMode={props.setDarkMode}
        isDarkMode={props.isDarkMode}
        height={"40px"}
        />
      <CustomButton
        isDarkMode={props.isDarkMode}
        darkColor={"#a899e7"}
        lightColor={"#535353"}
        onClick={() => props.navigateTo("/register")}

      >
        Inscription
      </CustomButton>
      <CustomButton 
        isDarkMode={props.isDarkMode}
        darkColor={"#a899e7"}
        lightColor={"#535353"}
        onClick={() => props.navigateTo("/login")}
      >
        Connexion
      </CustomButton>
    </NavbarText>
  </Navbar>
</>
 );
}

export default AppNavbar;