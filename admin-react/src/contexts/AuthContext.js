import React, { createContext, useContext, useState, useEffect } from 'react';
import axios from 'axios';

const AuthContext = createContext(null);

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
};

export const AuthProvider = ({ children }) => {
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        checkAuth();
    }, []);

    const checkAuth = async() => {
        try {
            const token = localStorage.getItem('admin_token');
            if (token) {
                const response = await axios.get('/api/check-auth.php', {
                    headers: { Authorization: `Bearer ${token}` }
                });
                setIsAuthenticated(response.data.authenticated);
            }
        } catch (error) {
            console.error('Auth check failed:', error);
        } finally {
            setLoading(false);
        }
    };

    const login = async(username, password) => {
        try {
            const response = await axios.post('/api/login.php', { username, password });
            if (response.data.success) {
                localStorage.setItem('admin_token', response.data.token);
                setIsAuthenticated(true);
                return { success: true };
            }
            return { success: false, error: response.data.message };
        } catch (error) {
            return { success: false, error: 'Login failed. Please try again.' };
        }
    };

    const logout = () => {
        localStorage.removeItem('admin_token');
        setIsAuthenticated(false);
    };

    if (loading) {
        return <div > Loading... < /div>;
    }

    return ( <
        AuthContext.Provider value = {
            { isAuthenticated, login, logout } } > { children } <
        /AuthContext.Provider>
    );
};