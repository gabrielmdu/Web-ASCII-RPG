import { useEffect } from 'react';
import { common } from './common/common.js';

const fetchAuth = (url, method, body) => {
  const token = localStorage.getItem('api_token');

  let options = {
    headers: {
      'Accept': 'application/json',
      'Authorization': 'Bearer ' + token
    },
    method: method
  };

  if (method === 'POST') {
    options.headers['Content-Type'] = 'application/json';
  }

  if (body) {
    options['body'] = body;
  }

  return fetch(url, options);
};

export const login = async (email, password) => {
  const result = {
    success: false,
    message: ''
  };

  try {
    const request = await fetch(common.API_BASE_URL + 'login', {
      headers: { 'content-type': 'application/json' },
      method: 'POST',
      body: JSON.stringify({
        'email': email,
        'password': password
      })
    });

    if (request.status === 200) {
      const response = await request.json();
      localStorage.setItem('api_token', response.access_token);
      console.log('logged in with token ' + response.access_token);

      result.success = true;
    } else if (request.status === 401) {
      result.message = 'Invalid credentials';
    }
  } catch (e) {
    result.message = e.message;
  }

  return result;
};

export const GlobalKeyUpEvent = ({ handler }) => {
  useEffect(() => {
    document.addEventListener('keyup', handler, false);

    return () => document.removeEventListener('keyup', handler, false);
  }, [handler]);

  return null;
};

export const fetchGet = url => fetchAuth(url, 'GET');
export const fetchPost = (url, body) => fetchAuth(url, 'POST', JSON.stringify(body));