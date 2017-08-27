import React from 'react';
import { Navbar } from 'react-bootstrap';
import Logo from './Logo';

const MenuBar = () => (
    <Navbar>
        <Navbar.Header>
            <Navbar.Brand>
                <a href="#">
                    <Logo />
                </a>
            </Navbar.Brand>
        </Navbar.Header>
    </Navbar>
);

export default MenuBar;
