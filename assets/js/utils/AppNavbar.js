import React, { Component } from 'react';
import { Navbar, NavbarBrand } from 'reactstrap';

class AppNavbar extends Component {
    
    
    
    render() {
    return (
        <Navbar fixed={"top"} style={{backgroundColor: '#a899e7'}}>
            <NavbarBrand href="/">
                <img
                    alt="Defuit"
                    src="à cahnger"
                    style={{
                    height: 40,
                    width: 40
                    }}
                />
            </NavbarBrand>
        </Navbar>
        );
    }
}

export default AppNavbar;
