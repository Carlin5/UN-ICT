import React, { useState, useEffect } from 'react';
import {
    Box,
    Paper,
    Typography,
    Grid,
    Card,
    CardContent,
    IconButton,
    Tooltip,
    Dialog,
    DialogTitle,
    DialogContent,
    DialogActions,
    Button,
    Chip,
} from '@mui/material';
import {
    Delete as DeleteIcon,
    CheckCircle as CheckCircleIcon,
    Email as EmailIcon,
} from '@mui/icons-material';
import axios from 'axios';

const Dashboard = () => {
    const [messages, setMessages] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [selectedMessage, setSelectedMessage] = useState(null);
    const [openDialog, setOpenDialog] = useState(false);

    useEffect(() => {
        fetchMessages();
    }, []);

    const fetchMessages = async() => {
        try {
            const token = localStorage.getItem('admin_token');
            const response = await axios.get('/api/get_messages.php', {
                headers: { Authorization: `Bearer ${token}` }
            });
            setMessages(response.data);
        } catch (err) {
            setError('Failed to fetch messages');
            console.error('Error fetching messages:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleMarkAsRead = async(messageId) => {
        try {
            const token = localStorage.getItem('admin_token');
            await axios.post('/api/mark_read.php', { message_id: messageId }, { headers: { Authorization: `Bearer ${token}` } });
            setMessages(messages.map(msg =>
                msg.id === messageId ? {...msg, is_read: true } : msg
            ));
        } catch (err) {
            console.error('Error marking message as read:', err);
        }
    };

    const handleDelete = async(messageId) => {
        try {
            const token = localStorage.getItem('admin_token');
            await axios.post('/api/delete_message.php', { message_id: messageId }, { headers: { Authorization: `Bearer ${token}` } });
            setMessages(messages.filter(msg => msg.id !== messageId));
        } catch (err) {
            console.error('Error deleting message:', err);
        }
    };

    const handleViewMessage = (message) => {
        setSelectedMessage(message);
        setOpenDialog(true);
    };

    const handleCloseDialog = () => {
        setOpenDialog(false);
        setSelectedMessage(null);
    };

    if (loading) {
        return <Typography > Loading... < /Typography>;
    }

    if (error) {
        return <Typography color = "error" > { error } < /Typography>;
    }

    return ( <
        Box >
        <
        Typography variant = "h4"
        gutterBottom >
        Dashboard <
        /Typography>

        <
        Grid container spacing = { 3 } >
        <
        Grid item xs = { 12 }
        md = { 4 } >
        <
        Card >
        <
        CardContent >
        <
        Typography color = "textSecondary"
        gutterBottom >
        Total Messages <
        /Typography> <
        Typography variant = "h3" > { messages.length } <
        /Typography> <
        /CardContent> <
        /Card> <
        /Grid> <
        Grid item xs = { 12 }
        md = { 4 } >
        <
        Card >
        <
        CardContent >
        <
        Typography color = "textSecondary"
        gutterBottom >
        Unread Messages <
        /Typography> <
        Typography variant = "h3" > { messages.filter(msg => !msg.is_read).length } <
        /Typography> <
        /CardContent> <
        /Card> <
        /Grid> <
        /Grid>

        <
        Paper sx = {
            { mt: 4, p: 2 } } >
        <
        Typography variant = "h6"
        gutterBottom >
        Recent Messages <
        /Typography> <
        Grid container spacing = { 2 } > {
            messages.map((message) => ( <
                Grid item xs = { 12 }
                key = { message.id } >
                <
                Card >
                <
                CardContent >
                <
                Box sx = {
                    { display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' } } >
                <
                Box >
                <
                Typography variant = "h6"
                component = "div" > { message.name } <
                /Typography> <
                Typography color = "textSecondary"
                gutterBottom > { message.email } <
                /Typography> <
                Typography variant = "body2"
                color = "textSecondary" > { new Date(message.date).toLocaleString() } <
                /Typography> <
                /Box> <
                Box > {!message.is_read && ( <
                        Tooltip title = "Mark as Read" >
                        <
                        IconButton onClick = {
                            () => handleMarkAsRead(message.id) } >
                        <
                        CheckCircleIcon / >
                        <
                        /IconButton> <
                        /Tooltip>
                    )
                } <
                Tooltip title = "View Message" >
                <
                IconButton onClick = {
                    () => handleViewMessage(message) } >
                <
                EmailIcon / >
                <
                /IconButton> <
                /Tooltip> <
                Tooltip title = "Delete" >
                <
                IconButton onClick = {
                    () => handleDelete(message.id) } >
                <
                DeleteIcon / >
                <
                /IconButton> <
                /Tooltip> <
                /Box> <
                /Box> <
                /CardContent> <
                /Card> <
                /Grid>
            ))
        } <
        /Grid> <
        /Paper>

        <
        Dialog open = { openDialog }
        onClose = { handleCloseDialog }
        maxWidth = "md"
        fullWidth > {
            selectedMessage && ( <
                >
                <
                DialogTitle >
                Message from { selectedMessage.name } <
                /DialogTitle> <
                DialogContent >
                <
                Box sx = {
                    { mt: 2 } } >
                <
                Typography variant = "subtitle1"
                gutterBottom >
                <
                strong > From: < /strong> {selectedMessage.email} <
                /Typography> <
                Typography variant = "subtitle1"
                gutterBottom >
                <
                strong > Date: < /strong> {new Date(selectedMessage.date).toLocaleString()} <
                /Typography> <
                Typography variant = "body1"
                sx = {
                    { mt: 2, whiteSpace: 'pre-wrap' } } > { selectedMessage.message } <
                /Typography> <
                /Box> <
                /DialogContent> <
                DialogActions >
                <
                Button onClick = { handleCloseDialog } > Close < /Button> <
                /DialogActions> <
                />
            )
        } <
        /Dialog> <
        /Box>
    );
};

export default Dashboard;