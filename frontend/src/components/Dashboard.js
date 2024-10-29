import React from 'react';

const App = () => {
    const goBack = () => {
        window.history.back(); // This will take the user to the previous page
    };

    const logout = () => {
        // Redirect to the logout endpoint or perform logout logic
        alert('You have been logged out.');
        // Example: window.location.href = 'logout.php';
    };

    return (
        <div className="App">
            <h1>Online Voting System</h1>

            <div className="button-container">
                <button className="button" onClick={goBack}>Back</button>
                <button className="button" onClick={logout}>Logout</button>
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
