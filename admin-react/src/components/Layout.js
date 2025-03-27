import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    AppBar,
    Box,
    CssBaseline,
    Drawer,
    IconButton,
    List,
    ListItem,
    ListItemIcon,
    ListItemText,
    Toolbar,
    Typography,
    useTheme,
} from '@mui/material';
import {
    Menu as MenuIcon,
    Dashboard as DashboardIcon,
    Message as MessageIcon,
    ExitToApp as LogoutIcon,
} from '@mui/icons-material';
import { useAuth } from '../contexts/AuthContext';

const drawerWidth = 240;

const Layout = ({ children }) => {
    const theme = useTheme();
    const navigate = useNavigate();
    const { logout } = useAuth();
    const [mobileOpen, setMobileOpen] = useState(false);

    const handleDrawerToggle = () => {
        setMobileOpen(!mobileOpen);
    };

    const handleLogout = () => {
        logout();
        navigate('/login');
    };

    const drawer = ( <
        div >
        <
        Toolbar >
        <
        Typography variant = "h6"
        noWrap component = "div" >
        UN - ICT Admin <
        /Typography> <
        /Toolbar> <
        List >
        <
        ListItem button onClick = {
            () => navigate('/') } >
        <
        ListItemIcon >
        <
        DashboardIcon / >
        <
        /ListItemIcon> <
        ListItemText primary = "Dashboard" / >
        <
        /ListItem> <
        ListItem button onClick = {
            () => navigate('/messages') } >
        <
        ListItemIcon >
        <
        MessageIcon / >
        <
        /ListItemIcon> <
        ListItemText primary = "Messages" / >
        <
        /ListItem> <
        ListItem button onClick = { handleLogout } >
        <
        ListItemIcon >
        <
        LogoutIcon / >
        <
        /ListItemIcon> <
        ListItemText primary = "Logout" / >
        <
        /ListItem> <
        /List> <
        /div>
    );

    return ( <
        Box sx = {
            { display: 'flex' } } >
        <
        CssBaseline / >
        <
        AppBar position = "fixed"
        sx = {
            {
                width: { sm: `calc(100% - ${drawerWidth}px)` },
                ml: { sm: `${drawerWidth}px` },
            }
        } >
        <
        Toolbar >
        <
        IconButton color = "inherit"
        aria - label = "open drawer"
        edge = "start"
        onClick = { handleDrawerToggle }
        sx = {
            { mr: 2, display: { sm: 'none' } } } >
        <
        MenuIcon / >
        <
        /IconButton> <
        Typography variant = "h6"
        noWrap component = "div" >
        UN - ICT Admin Panel <
        /Typography> <
        /Toolbar> <
        /AppBar> <
        Box component = "nav"
        sx = {
            { width: { sm: drawerWidth }, flexShrink: { sm: 0 } } } >
        <
        Drawer variant = "temporary"
        open = { mobileOpen }
        onClose = { handleDrawerToggle }
        ModalProps = {
            {
                keepMounted: true, // Better open performance on mobile.
            }
        }
        sx = {
            {
                display: { xs: 'block', sm: 'none' },
                '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth },
            }
        } >
        { drawer } <
        /Drawer> <
        Drawer variant = "permanent"
        sx = {
            {
                display: { xs: 'none', sm: 'block' },
                '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth },
            }
        }
        open >
        { drawer } <
        /Drawer> <
        /Box> <
        Box component = "main"
        sx = {
            {
                flexGrow: 1,
                p: 3,
                width: { sm: `calc(100% - ${drawerWidth}px)` },
                mt: '64px',
            }
        } >
        { children } <
        /Box> <
        /Box>
    );
};

export default Layout;