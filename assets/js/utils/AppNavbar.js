import React, {useState} from 'react';
import { Button, Collapse, Navbar, NavbarBrand, NavbarText, NavbarToggler } from 'reactstrap';
import DarkModeSwitch from './darkModeSwitch';

function AppNavbar(props) {
 return (
  <>
  <Navbar style={{backgroundColor:"#a899e7", boxShadow:"0 10px 10px 0 rgb(0, 0, 0, 0.2)"}} >
    <NavbarBrand href="/">  
      <img
        alt="logo"
        src="/files/images/darkLogoDefiut.png"
        style={{
          height: 40,
        }}
      />
    </NavbarBrand>
    <NavbarText style={{display:"flex", gap:"10px", flexWrap: "wrap", alignItems: "center"}}>
      <DarkModeSwitch setDarkMode={props.setDarkMode}/>
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