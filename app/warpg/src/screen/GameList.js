import React, { useEffect, useState } from 'react';
import { fetchGet } from '../utils';
import { useLoggedUser } from '../hooks/useLoggedUser.js';

import BackToMenu from './BackToMenu';

import './GameList.scss';

const GameList = () => {
  const [gameList, setGameList] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [selectedGameId, setSelectedGameId] = useState(null);
  const [user] = useLoggedUser();

  useEffect(() => {
    const fetchGameList = async () => {
      const request = await fetchGet('game/list');

      if (request.status === 200) {
        setGameList(await request.json());
      }

      setIsLoading(false);
    };

    fetchGameList();
  }, []);

  return (
    <div className="game-list">
      <div className="title">Game List</div>
      {isLoading
        ? <div>Loading game list...</div>
        : <div>
          <div className="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>NAME</th>
                  <th>DESCRIPTION</th>
                </tr>
              </thead>
              <tbody>
                {gameList.map(game =>
                  <tr
                    className={game.id === selectedGameId ? 'selected' : ''}
                    key={game.id}
                    onClick={() => setSelectedGameId(game.id)}
                  >
                    <td>{game.name}</td>
                    <td>{game.description}</td>
                  </tr>)}
              </tbody>
            </table>
          </div>
          <div className="game-list-buttons">
            <button disabled={!user}>Continue</button>
            <button disabled={!selectedGameId}>New Game</button>
          </div>
        </div>}
      <BackToMenu />
    </div>
  );
};

export default GameList;