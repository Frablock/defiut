import React, {useState} from 'react';
import { Button, Collapse, Navbar, NavbarBrand, NavbarText, NavbarToggler } from 'reactstrap';
import DarkModeSwitch from './DarkModeSwitch';
import CustomButton from './CustomButton';
import { useNavigate } from "react-router-dom";


export default function AppFooter(props) {
  const navigate = useNavigate();

 return (
  <>
    <div
      className="d-flex flex-column w-100 shadow justify-content-center align-items-center"
      style={{
        backgroundColor: props.isDarkMode ? "#535353" : "#e2ddf7",
        transition: "background-color 0.8s ease, opacity 0.8s",
        height:"75px"
      }}
    >
      <div className="text-decoration-underline" style={{cursor:"pointer"}} onClick={() => navigate("/legal")}>
          Mentions légales
      </div>
      <div className="text-decoration-underline" style={{cursor:"pointer"}} onClick={() => navigate("/login")}>
          Work in progress ...
      </div>
    </div>
  </>
 );
}