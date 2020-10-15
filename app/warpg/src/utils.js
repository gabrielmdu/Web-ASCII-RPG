import { useEffect } from 'react';
import { common } from './common/common.js';

export const fetchGet = url => {
  return fetch(common.API_BASE_URL + url);
};

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

  return fetch(common.API_BASE_URL + url, options);
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

export const logout = async () => {
  const result = {
    success: false,
    message: ''
  };

  try {
    const request = await fetchAuthPost('logout');
    if (request.status === 200) {
      const response = await request.json();

      result.success = true;
      result.message = response.message;
    } else {
      result.message = 'Failed loggin out';
    }
  } catch (e) {
    result.message = e.message;
  }

  // it doesn't matter the result, the user will be logged out
  localStorage.removeItem('api_token');
  return result;
};

export const GlobalKeyUpEvent = ({ handler }) => {
  useEffect(() => {
    document.addEventListener('keyup', handler, false);

    return () => document.removeEventListener('keyup', handler, false);
  }, [handler]);

  return null;
};

export const fetchAuthGet = url => fetchAuth(url, 'GET');
export const fetchAuthPost = (url, body) => fetchAuth(url, 'POST', JSON.stringify(body));