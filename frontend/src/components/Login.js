import React, { useState } from 'react';
import axios from 'axios';
import { Link, useNavigate } from 'react-router-dom';

function Login() {
  const [formData, setFormData] = useState({
    email: '',
    password: ''
  });
  const [message, setMessage] = useState('');
  const navigate = useNavigate(); // Initialize the useNavigate hook


  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const data = {
      email: formData.email,
      password: formData.password,
    };

    axios.post('http://localhost:8080/login', data)
      .then((response) => {
        setMessage('Login successful!');

        // Redirect to the dashboard
        navigate('/dashboard'); // Navigate to the Dashboard route
      })
      .catch((error) => {
        if (error.response && error.response.data && error.response.data.message) {
          setMessage(error.response.data.message);
        } else {
          setMessage('Error logging in. Please try again.');
        }
      });
  };

  return (
    <div style={{ maxWidth: '400px', margin: 'auto', padding: '20px', border: '1px solid #ccc', borderRadius: '8px' }}>
      <h2 className="text-center">Log in</h2>
      <form onSubmit={handleSubmit}>
        <div style={{ marginBottom: '15px' }}>
          <label htmlFor='email'>Your Email</label>
          <input
            type='email'
            id='email'
            name='email'
            value={formData.email}
            onChange={handleChange}
            required
            style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }}
          />
        </div>

        <div style={{ marginBottom: '15px' }}>
          <label htmlFor='password'>Password</label>
          <input
            type='password'
            id='password'
            name='password'
            value={formData.password}
            onChange={handleChange}
            required
            style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }}
          />
        </div>

        <button type='submit' style={{ width: '100%', padding: '10px', borderRadius: '4px', backgroundColor: '#007bff', color: 'white', border: 'none' }}>
          Log in
        </button>
      </form>

      {message && <p style={{ marginTop: '15px', color: message.includes('successful') ? 'green' : 'red' }}>{message}</p>}

      <p style={{ marginTop: '15px', textAlign: 'center' }}>
        No account? <Link to="/" style={{ color: '#007bff', textDecoration: 'none' }}>Go to register</Link>
      </p>
    </div>
  );
}

export default Login;
