/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import React, { Component } from 'react';
import { Button, Container } from 'reactstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

class App extends Component {
    render() {
        return (
            <Container>
                <h1>Welcome to My React App inside Symfony!</h1>
                <Button color="primary">Click Me</Button>
            </Container>
        );
    }
}

export default App;
