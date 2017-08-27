import React from 'react';
import { Navbar, NavbarBrand, Nav, NavItem } from 'react-bootstrap';
import { LinkContainer } from 'react-router-bootstrap';
import Logo from './Logo';

const MenuBar = () => (
    <Navbar>
        <Navbar.Header>
            <NavbarBrand>
                <LinkContainer to='/'>
                    <a><Logo /></a>
                </LinkContainer>
            </NavbarBrand>
        </Navbar.Header>
        <Nav>
            <LinkContainer to='/books'>
                <NavItem>Books</NavItem>
            </LinkContainer>
            <LinkContainer to='/readers'>
                <NavItem>Readers</NavItem>
            </LinkContainer>
        </Nav>
    </Navbar>
);

export default MenuBar;
