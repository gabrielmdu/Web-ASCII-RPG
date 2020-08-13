import React from 'react';
import '../scss/main-menu.scss';

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
          <button>New Game</button>
          <button>Continue</button>
          <button>Credits</button>
        </div>
      </div>
    </div>
  );
};

export default MainMenu;