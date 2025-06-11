import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { Button } from "reactstrap";
import AppNavbar from "../utils/AppNavbar";

function Login(props) {
   const navigate = useNavigate();
   
   const handleOnClickLogin = () => {
       navigate("/app");
   };
   
   return (
       <>
           <h1>login pafge</h1><br></br>
           <Button onClick={handleOnClickLogin}>go aller à Browse Page</Button>
       </>
   );
}

export default Login;