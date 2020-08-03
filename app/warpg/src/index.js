import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { fetchGet } from './utils.js';
import Game from './Game.js';
import { values } from './consts.js';

const App = () => {
  const [gameInfo, setGameInfo] = useState(null);

  const login = async () => {
    const req = await fetch(values.API_BASE_URL + 'login', {
      headers: { 'content-type': 'application/json' },
      method: 'POST',
      body: JSON.stringify({ 'email': 'admin@admin.com', 'password': '123' })
    });

    const result = await req.json();

    localStorage.setItem('api_token', result.access_token);
    console.log('logged in with token ' + result.access_token);
  };

  const resetGame = async () => {
    setGameInfo(null);
    const request = await fetchGet(values.API_BASE_URL + 'game/reset')
    const info = await request.json();
    setGameInfo(info);
  };

  const fetchGame = async () => {
    const request = await fetchGet(values.API_BASE_URL + 'game')
    if (request.status === 200) {
      const info = await request.json();
      setGameInfo(info);
      console.log(info.adventure);
    }
  }

  useEffect(() => {
    fetchGame();
  }, []);

  return (
    <>
      <button onClick={login}>Login</button>
      {gameInfo
        ? <>
          <Game gameInfo={gameInfo} />
          <button onClick={() => resetGame()}>Reset</button>
        </>
        : <span>loading game info...</span>
      }
    </>
  );
};

ReactDOM.render(
  <App />,
  document.getElementById('root')
);
