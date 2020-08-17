import React, { useState, useEffect } from 'react';
import { getLoggedUser } from '../utils.js';
import UserWidget from './UserWidget.js';
import '../scss/main-menu.scss';
import '../scss/modal.scss';

const MainMenu = () => {
  const [user, setUser] = useState(null);

  useEffect(() => {
    setUser(getLoggedUser());
  }, []);

  return (
    <div className="main-menu">
      <div className="menu-container">
        <UserWidget userName={user} setUserName={() => setUser(getLoggedUser())} />
        <div className="game-title">
          <pre>
{String.raw`
          _______  _______  _______  _______ 
|\     /|(  ___  )(  ____ )(  ____ )(  ____ \
| )   ( || (   ) || (    )|| (    )|| (    \/
| | _ | || (___) || (____)|| (____)|| |      
| |( )| ||  ___  ||     __)|  _____)| | ____ 
| || || || (   ) || (\ (   | (      | | \_  )
| () () || )   ( || ) \ \__| )      | (___) |
(_______)|/     \||/   \__/|/       (_______)
`}
          </pre>
          <span>- Web ASCII RPG -</span>
        </div>

        <div className="menu-buttons">
          <button>New Game</button>
          <button disabled={user === null}>Continue</button>
          <button>About</button>
        </div>
      </div>
    </div>
  );
};

export default MainMenu;