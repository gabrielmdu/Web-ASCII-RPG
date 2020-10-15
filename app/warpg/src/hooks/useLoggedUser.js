import { useState, useEffect } from 'react';
import jwt_decode from 'jwt-decode';

export const useLoggedUser = () => {
  const [check, setCheck] = useState(true);
  const [user, setUser] = useState(null);

  const checkUser = () => setCheck(!check);

  useEffect(() => {
    const token = localStorage.getItem('api_token');

    if (!token) {
      setUser(null);
      return;
    }

    let decoded;

    try {
      decoded = jwt_decode(token);
    } catch (e) {
      localStorage.removeItem('api_token');
      setUser(null);
      return;
    }

    if (decoded.exp < Math.floor(Date.now() / 1000)) {
      localStorage.removeItem('api_token');
      setUser(null);
      return;
    }

    setUser({
      name: decoded.user_name,
      guest: decoded.guest
    });
  }, [check]);

  return [user, checkUser];
};