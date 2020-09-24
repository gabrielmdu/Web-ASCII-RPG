import React from 'react';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import { useLoggedUser } from './hooks/useLoggedUser.js';

import MainMenu from './screen/MainMenu.js';
import About from './screen/About.js';
import UserWidget from './screen/UserWidget.js';
import GameList from './screen/GameList';
import Warpg404 from './screen/Warpg404';

import './App.scss';

const App = () => {
  const [user, setCheck] = useLoggedUser();
  //const [gameInfo, setGameInfo] = useState(null);

  //useEffect(() => {
  /*const fetchGame = async () => {
    const request = await fetchAuthGet('game');
    if (request.status === 200) {
      const info = await request.json();
      setGameInfo(info);
      console.log(info);
    }
  };

  fetchGame();*/
  //}, []);

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

          <Route>
            <Warpg404 />
          </Route>
        </Switch>

      </div>
    </Router>
    /*<>
      <button onClick={login}>Login</button>
      {gameInfo
        ? <>
          <Game gameInfo={gameInfo} />
          <button onClick={() => resetGame()}>Reset</button>
        </>
        : <span>loading game info...</span>
      }
    </>*/

  );
};

export default App;