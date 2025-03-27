import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Box,
    Button,
    Container,
    Paper,
    TextField,
    Typography,
    Alert,
} from '@mui/material';
import { useAuth } from '../contexts/AuthContext';

const Login = () => {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);
    const navigate = useNavigate();
    const { login } = useAuth();

    const handleSubmit = async(e) => {
        e.preventDefault();
        setError('');
        setLoading(true);

        try {
            const result = await login(username, password);
            if (result.success) {
                navigate('/');
            } else {
                setError(result.error || 'Login failed. Please try again.');
            }
        } catch (err) {
            setError('An error occurred. Please try again.');
        } finally {
            setLoading(false);
        }
    };

    return ( <
        Container component = "main"
        maxWidth = "xs" >
        <
        Box sx = {
            {
                marginTop: 8,
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
            }
        } >
        <
        Paper elevation = { 3 }
        sx = {
            {
                padding: 4,
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                width: '100%',
            }
        } >
        <
        Typography component = "h1"
        variant = "h5"
        sx = {
            { mb: 3 } } >
        UN - ICT Admin Login <
        /Typography>

        {
            error && ( <
                Alert severity = "error"
                sx = {
                    { width: '100%', mb: 2 } } > { error } <
                /Alert>
            )
        }

        <
        Box component = "form"
        onSubmit = { handleSubmit }
        sx = {
            { width: '100%' } } >
        <
        TextField margin = "normal"
        required fullWidth id = "username"
        label = "Username"
        name = "username"
        autoComplete = "username"
        autoFocus value = { username }
        onChange = {
            (e) => setUsername(e.target.value) }
        /> <
        TextField margin = "normal"
        required fullWidth name = "password"
        label = "Password"
        type = "password"
        id = "password"
        autoComplete = "current-password"
        value = { password }
        onChange = {
            (e) => setPassword(e.target.value) }
        /> <
        Button type = "submit"
        fullWidth variant = "contained"
        sx = {
            { mt: 3, mb: 2 } }
        disabled = { loading } >
        { loading ? 'Logging in...' : 'Login' } <
        /Button> <
        /Box> <
        /Paper> <
        /Box> <
        /Container>
    );
};

export default Login;