import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import Layout from './components/Layout';
import { AuthProvider, useAuth } from './contexts/AuthContext';

const theme = createTheme({
    palette: {
        primary: {
            main: '#0066CC',
        },
        secondary: {
            main: '#dc3545',
        },
        background: {
            default: '#f5f5f5',
        },
    },
    typography: {
        fontFamily: '"Open Sans", "Helvetica", "Arial", sans-serif',
    },
});

const PrivateRoute = ({ children }) => {
    const { isAuthenticated } = useAuth();
    return isAuthenticated ? children : < Navigate to = "/login" / > ;
};

function App() {
    return ( <
        ThemeProvider theme = { theme } >
        <
        CssBaseline / >
        <
        AuthProvider >
        <
        Router >
        <
        Routes >
        <
        Route path = "/login"
        element = { < Login / > }
        /> <
        Route path = "/"
        element = { <
            PrivateRoute >
            <
            Layout >
            <
            Dashboard / >
            <
            /Layout> <
            /PrivateRoute>
        }
        /> <
        /Routes> <
        /Router> <
        /AuthProvider> <
        /ThemeProvider>
    );
}

export default App;