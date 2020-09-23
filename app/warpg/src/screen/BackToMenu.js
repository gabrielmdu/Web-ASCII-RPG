import React from 'react';
import { Link } from 'react-router-dom';

import './BackToMenu.scss';

const BackToMenu = () => (
    <Link className="back-to-menu" to="/">
        &lt;- Back to menu
    </Link>
);

export default BackToMenu;