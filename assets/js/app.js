import '../styles/app.css';
import React, { Component } from 'react';
import { Button, Container } from 'reactstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import { createRoot } from 'react-dom/client';

class AppNavbar extends Component {
    render() {
    return (
            <AppNavbar>
                <Container>
                    <h1>Welcome to My React App inside Symfony!</h1>
                    <Button color="primary">Click Me</Button>
                </Container>
            </AppNavbar>
        );
    }
}

// Mount the component to the DOM
const domContainer = document.getElementById('root');
if (domContainer) {
    const root = createRoot(domContainer);
    root.render(<App />);
}else{
    console.log('WARNING : div ROOT non trouvé !')
}

export default Navbar;
