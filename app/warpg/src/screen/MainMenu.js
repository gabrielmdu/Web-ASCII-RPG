import React from 'react';
import { Link } from 'react-router-dom';

import './MainMenu.scss';

const MainMenu = () => {
  return (
    <div className="main-menu">
      <div className="menu-container">
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

          <Link to="/game-list">
            <button>Start</button>
          </Link>

          <Link to="/about">
            <button>About</button>
          </Link>

        </div>
      </div>
    </div>
  );
};

export default MainMenu;