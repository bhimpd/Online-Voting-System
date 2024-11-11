import React, { useEffect, useState } from 'react';
import '../css/style.css';
import { useNavigate } from 'react-router-dom';
import Button from '@mui/material/Button';

const App = () => {
  const navigate = useNavigate();

  const [user, setUser] = useState({
    name: '',
    address: '',
    mobile: '',
    status: '',
    image: ''
  });

  const [groups, setGroups] = useState([]);
  const [loading, setLoading] = useState(true);
  const [hasVoted, setHasVoted] = useState(false); // Track if the user has voted

  useEffect(() => {
    const fetchUserData = async () => {
      const userData = JSON.parse(localStorage.getItem('user'));
      if (userData) {
        setUser({
          name: userData.name,
          address: userData.address,
          mobile: userData.mobile,
          status: userData.status,
          no_of_votes: userData.no_of_votes,
          image: userData.image ? `http://localhost:8080/uploads/${userData.image}` : 'default-image.jpg'
        });
      }
      setLoading(false);
    };

    const fetchGroupData = async () => {
      try {
        const response = await fetch('http://localhost:8080/getgroups');
        if (response.ok) {
          const data = await response.json();
          if (data.users && data.users.length > 0) {
            setGroups(data.users);
          }
        } else {
          console.error('Failed to fetch groups');
        }
      } catch (error) {
        console.error('Error fetching group data:', error);
      }
    };

    fetchUserData();
    fetchGroupData();
  }, []);

  const goBack = () => {
    window.history.back();
  };

  const logout = () => {
    localStorage.removeItem('user');
    navigate('/');
  };

  const handleVote = (groupId) => {
    // Update the group votes
    setGroups(prevGroups => 
      prevGroups.map(group => 
        group.id === groupId ? { ...group, no_of_votes: group.no_of_votes + 1 } : group
      )
    );

    // Mark as voted
    setHasVoted(true);

    // Update the user's status to "voted"
    setUser(prevUser => ({ ...prevUser, status: 'voted' }));
  };

  if (loading) {
    return <div className="loading">Loading...</div>;
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
          <img src={user.image} alt="User Image" className="profile-image" />
          <h2>Profile</h2>
          <h3>Name: {user.name}</h3>
          <h3>Address: {user.address}</h3>
          <h3>Mobile: {user.mobile}</h3>
          <h3>Status: {user.status === 'voted' ? 'Voted' : 'Not Voted'}</h3>
        </div>
        <div id="group">
          <h2>Groups</h2>
          <p>Here are the groups and their votes:</p>

          {/* Map over the groups array to display each group */}
          <div className="user-list">
            {groups.map((group, index) => (
              <div key={index} className="group-item">
                <div className='group-name-img'>
                  <h3>Group Name: {group.name}</h3>
                  <img src={`http://localhost:8080/uploads/${group.image}`} alt="Group Image" className="group-image" />
                </div>
                <div className='vote'>
                  <p>No. of Votes: {group.no_of_votes}</p>
                  <Button 
                    variant="outlined" 
                    color="primary" 
                    onClick={() => handleVote(group.id)}
                    disabled={hasVoted} // Disable all buttons if the user has voted
                  >
                    Vote
                  </Button>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default App;
