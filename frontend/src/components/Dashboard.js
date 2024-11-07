import React, { useEffect, useState } from 'react';
import '../css/style.css';
import { useNavigate } from 'react-router-dom';


const App = () => {
    const navigate = useNavigate(); // Initialize useNavigate hook
    const [username, setUsername] = useState(''); // State to hold the username

    useEffect(() => {
        // Retrieve the username from local storage (or your preferred method)
        const storedUsername = localStorage.getItem('username'); // Adjust this line based on where you store the username
        if (storedUsername) {
            setUsername(storedUsername); // Set the username if it exists
        }
    }, []);


    const goBack = () => {
        window.history.back(); // This will take the user to the previous page
    };

    const logout = () => {
        // Redirect to the logout endpoint to register page
        navigate('/');

    };

    return (
        <div className="App">
            <h1 class="top-heading">Online Voting System</h1>
            <div class="underline"></div>
            <div className="button-container">
                <button className="button" onClick={goBack}>Back</button>
                <button className="button" onClick={logout}>Logout</button>
            </div>

            <div class="user-info">
                <h2>Welcome {username}</h2>
            </div>
            <div className="content">
                <div id="profile">
                    <h2>Profile</h2>
                    <p>This is some random content for the profile section.</p>
                </div>
                <div id="group">
                    <h2>Group</h2>
                    <p>This is some random content for the group section.</p>
                </div>
            </div>
        </div>
    );
};

export default App;
