import React from 'react';
import UserWidget from './UserWidget.js';
import { useLoggedUser } from '../hooks/useLoggedUser.js';
import '../scss/main-menu.scss';
import '../scss/modal.scss';

const MainMenu = () => {
  const [user, setCheck] = useLoggedUser(true);

  return (
    <div className="main-menu">
      <div className="menu-container">
        <UserWidget user={user} checkUser={() => setCheck()} />
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
          <button>Start</button>
          <button>About</button>
        </div>
      </div>
    </div>
  );
};

export default MainMenu;