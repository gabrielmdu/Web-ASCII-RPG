import { useEffect, useRef, useState } from 'react';
import jwt_decode from 'jwt-decode';

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

// https://stackoverflow.com/a/54570068
export const useComponentVisible = initialIsVisible => {
  const [isComponentVisible, setIsComponentVisible] = useState(initialIsVisible);
  const ref = useRef(null);

  const handleHideDropdown = (event) => {
    if (event.key === "Escape") {
      setIsComponentVisible(false);
    }
  };

  const handleClickOutside = event => {
    if (
      ref.current &&
      !ref.current.contains(event.target) &&
      isComponentVisible
    ) {
      setIsComponentVisible(false);
    }
  };

  useEffect(() => {
    document.addEventListener("keydown", handleHideDropdown, true);
    document.addEventListener("click", handleClickOutside, true);
    return () => {
      document.removeEventListener("keydown", handleHideDropdown, true);
      document.removeEventListener("click", handleClickOutside, true);
    };
  });

  return {
    ref,
    isComponentVisible,
    setIsComponentVisible
  };
};

export const getLoggedUser = () => {
  const token = localStorage.getItem('api_token');

  if (!token) {
    return null;
  }

  let decoded;

  try {
    decoded = jwt_decode(token);
  } catch (e) {
    localStorage.removeItem('api_token');
    return null;
  }

  if (decoded.exp < Math.floor(Date.now() / 1000)) {
    localStorage.removeItem('api_token');
    return null;
  }

  return decoded.user_name;
};

export const GlobalKeyUpEvent = ({ handler }) => {
  useEffect(() => {
    document.addEventListener("keyup", handler, false);

    return () => document.removeEventListener("keyup", handler, false);
  }, [handler]);

  return null;
};

export const fetchGet = url => fetchAuth(url, 'GET');
export const fetchPost = (url, body) => fetchAuth(url, 'POST', JSON.stringify(body));