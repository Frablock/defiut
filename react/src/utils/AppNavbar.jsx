import React, {useState} from 'react';
import { Button, Collapse, Navbar, NavbarBrand, NavbarText, NavbarToggler } from 'reactstrap';
import DarkModeSwitch from './DarkModeSwitch';

function AppNavbar(props) {
 return (
  <>
<Navbar
  className="w-100"
  style={{
    backgroundColor: props.isDarkMode ? "#535353" : "#a899e7",
    boxShadow: "0 10px 10px 0 rgb(0, 0, 0, 0.2)", 
    top: 0, 
    left: 0,
    transition: "background-color 0.8s ease, opacity 0.8s"
  }}
>
    <NavbarBrand href="/">  
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
    </NavbarBrand>
    <NavbarText style={{display:"flex", gap:"10px", flexWrap: "wrap", alignItems: "center"}}>
      <DarkModeSwitch 
        setDarkMode={props.setDarkMode}
        isDarkMode={props.isDarkMode}
        height={"40px"}
        />
      <Button size="sm" style={{backgroundColor:"#535353", height: "40px", borderRadius: "20px"}}>
        boutton 1 coucou
      </Button>
      <Button size="sm" style={{backgroundColor:"#535353", height: "40px", borderRadius: "20px"}}>
        boutton 1 coucou
      </Button>
    </NavbarText>
  </Navbar>
</>
 );
}

export default AppNavbar;