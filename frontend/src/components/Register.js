import React, { useState } from 'react';
import axios from 'axios';
import { Link, useNavigate } from 'react-router-dom';

function Register() {
  const navigate = useNavigate(); // Initialize the navigate function

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    confirmPassword: ''
  });
  const [message, setMessage] = useState('');

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    const data = {
      name: formData.name,
      email: formData.email,
      password: formData.password,
      confirm_password: formData.confirmPassword
    };

    axios.post('http://localhost:8080/register', data)
      .then((response) => {
        setMessage('User registered successfully!');
        setTimeout(() => navigate('/login'), 2000); // Redirect to login after 2 seconds
      })
      .catch((error) => {
        if (error.response && error.response.data && error.response.data.message) {
          setMessage(error.response.data.message);
        } else {
          setMessage('Error registering user. Please try again.');
        }
        console.error('There was an error registering the user!', error);
      });
  };

  return (
    <div style={{ maxWidth: '400px', margin: 'auto', padding: '20px', border: '1px solid #ccc', borderRadius: '8px' }}>
      <h2 className="text-center">Sign up</h2>
      <form onSubmit={handleSubmit}>
        <div style={{ marginBottom: '15px' }}>
          <label htmlFor='name'>Your Name</label>
          <input
            type='text'
            id='name'
            name='name'
            value={formData.name}
            onChange={handleChange}
            required
            style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }}
          />
        </div>

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

        <div style={{ marginBottom: '15px' }}>
          <label htmlFor='confirmPassword'>Confirm password</label>
          <input
            type='password'
            id='confirmPassword'
            name='confirmPassword'
            value={formData.confirmPassword}
            onChange={handleChange}
            required
            style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }}
          />
        </div>

        <button type='submit' style={{ width: '100%', padding: '10px', borderRadius: '4px', backgroundColor: '#007bff', color: 'white', border: 'none' }}>
          Register
        </button>
      </form>

      {message && <p style={{ marginTop: '15px', color: message.includes('successfully') ? 'green' : 'red' }}>{message}</p>}

      <p style={{ marginTop: '15px', textAlign: 'center' }}>
        Already registered? <Link to="/login" style={{ color: '#007bff', textDecoration: 'none' }}>Go to login</Link>
      </p>
    </div>
  );
}

export default Register;
