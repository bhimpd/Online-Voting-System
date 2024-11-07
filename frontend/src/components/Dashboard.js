import React, { useEffect, useState } from 'react';
import '../css/style.css'; // Ensure this file contains your styles
import { useNavigate } from 'react-router-dom';

const App = () => {
  const navigate = useNavigate(); // Initialize useNavigate hook

  // States to hold the user data
  const [user, setUser] = useState({
    name: '',
    address: '',
    mobile: '',
    status: '',
    no_of_votes: 0,
    image: ''
  });

  // State for loading indicator
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Simulate fetching data from an API (replace with actual API call)
    const fetchUserData = async () => {
      // Assuming you have already saved the user data in localStorage (adjust accordingly)
      const userData = JSON.parse(localStorage.getItem('user')); 

      if (userData) {
        setUser({
          name: userData.name,
          address: userData.address,
          mobile: userData.mobile,
          status: userData.status,
          no_of_votes: userData.no_of_votes,
          image: userData.image ? `http://localhost:8080/images/${userData.image}` : 'default-image.jpg' // Make sure the image path is correct
        });
      }

      setLoading(false); // Stop loading when data is fetched
    };

    fetchUserData(); // Fetch user data when the component mounts
  }, []);

  const goBack = () => {
    window.history.back(); // This will take the user to the previous page
  };

  const logout = () => {
    localStorage.removeItem('user'); // Clear user data from localStorage
    navigate('/'); // Redirect to login page (or adjust if you have a specific logout endpoint)
  };

  if (loading) {
    return <div className="loading">Loading...</div>; // Show loading state while data is being fetched
  }

  return (
    <div className="App">
      <h1 className="top-heading">Online Voting System</h1>
      <div className="underline"></div>
      <div className="button-container">
        <button className="button" onClick={goBack}>Back</button>
        <button className="button" onClick={logout}>Logout</button>
      </div>

      <div className="user-info">
        <h2>Welcome, {user.name} To The Voting Club.</h2>
        <i>"A single vote, a brighter hope"</i>
      </div>
      <div className="content">
        <div id="profile">
          {/* Ensure the profile image is displayed correctly */}
          <img src={user.image} alt="User Image" className="profile-image" /> 
          <h2>Profile</h2>
          <h3>Name: {user.name}</h3>
          <h3>Address: {user.address}</h3>
          <h3>Mobile: {user.mobile}</h3>
          <h3>Status: {user.status}</h3>
          <h3>No. of Votes: {user.no_of_votes}</h3>
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
