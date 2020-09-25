import React from 'react';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import { useLoggedUser } from './hooks/useLoggedUser.js';

import MainMenu from './screen/MainMenu.js';
import About from './screen/About.js';
import UserWidget from './screen/UserWidget.js';
import GameList from './screen/GameList';
import Game from './Game';
import Warpg404 from './screen/Warpg404';

import './App.scss';

const App = () => {
  const [user, setCheck] = useLoggedUser();

  /*const resetGame = async () => {
    setGameInfo(null);
    const request = await fetchAuthGet('game/reset');
    const info = await request.json();
    setGameInfo(info);
  };*/

  return (
    <Router>
      <div className="main-container">

        <UserWidget user={user} checkUser={() => setCheck()} />
        <Switch>

          <Route exact path="/">
            <MainMenu />
          </Route>

          <Route path="/game-list">
            <GameList />
          </Route>

          <Route path="/about">
            <About />
          </Route>

          <Route path="/play">
            <Game />
          </Route>

          <Route>
            <Warpg404 />
          </Route>
        </Switch>

      </div>
    </Router>
  );
};

export default App;