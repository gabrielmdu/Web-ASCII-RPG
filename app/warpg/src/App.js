import React, { useState, useEffect } from 'react';
import { fetchGet } from './utils.js';
import { commons } from './consts.js';
import Game from './Game.js';

const App = () => {
  const [gameInfo, setGameInfo] = useState(null);

  useEffect(() => {
    const fetchGame = async () => {
      const request = await fetchGet(commons.API_BASE_URL + 'game');
      if (request.status === 200) {
        const info = await request.json();
        setGameInfo(info);
        console.log(info);
      }
    };

    fetchGame();
  }, []);

  const login = async () => {
    const req = await fetch(commons.API_BASE_URL + 'login', {
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
    const request = await fetchGet(commons.API_BASE_URL + 'game/reset');
    const info = await request.json();
    setGameInfo(info);
  };

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

export default App;