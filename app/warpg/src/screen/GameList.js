import React, { useEffect, useState } from 'react';
import { useDispatch } from 'react-redux';
import { useHistory } from 'react-router-dom';
import { fetchAuthPost, fetchGet } from '../utils';
import { hideModal, showButtonsModal } from '../actions/modalActions';
import { common } from '../common/common';

import BackToMenu from './BackToMenu';

import './GameList.scss';

const GameList = ({ user }) => {
  const dispatch = useDispatch();
  const history = useHistory();
  const [gameList, setGameList] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [selectedGameId, setSelectedGameId] = useState(null);

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

  const handleNewGame = async () => {
    const request = await fetchAuthPost('game', { 'game_id': selectedGameId });

    if (request.status === 200) {
      history.push('/play');
    } else {
      const response = await request.json();
      dispatch(showButtonsModal(
        common.dialogTypes.ERROR,
        'Error',
        response.message,
        [{
          label: "Close",
          handleClick: () => dispatch(hideModal())
        }]));
    }
  };

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
            <button
              disabled={!selectedGameId}
              onClick={handleNewGame}
            >
              New Game
            </button>
          </div>

        </div>}

      <BackToMenu />

    </div>
  );
};

export default GameList;