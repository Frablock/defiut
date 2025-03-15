import React from 'react';
import { Button, Navbar, NavbarBrand } from 'reactstrap';
import DarkModeSwitch from './darkModeSwitch';

function AppNavbar(props) {
    
 return (
   <Navbar fixed={"top"} style={{height: "7vh", backgroundColor:"#a899e7", boxShadow:"0 10px 10px 0 rgb(0, 0, 0, 0.2)"}} container={true}>
     <NavbarBrand>
       <img
         alt="Defuit"
         src={"/files/images/"+(props.isDarkMode?"lightLogoDefiut":"darkLogoDefiut.png")}
         style={{
           position:"absolute",
           height: "min(calc(75%),calc(6vw))",
           transform: "translateY(-50%)",
         }}
       />
     </NavbarBrand>
     <div>
        <DarkModeSwitch/>
        <Button size={"sm"} color={"secondary"} style={{borderRadius:"25px"}}>
          Boutton 2
        </Button>
        <Button size={"sm"} color={"secondary"} style={{borderRadius:"25px"}}>
          Boutton 3
        </Button>
     </div>
   </Navbar>
 );
}

export default AppNavbar;