import React from 'react';
import logo from './logo.svg';
import './App.css';

import StupidButton from './StupidButton.js';

export default class App extends React.Component {
	render() {
		return (
			<div className="App">
				<header className="App-header">
					<img src={logo} className="App-logo" alt="logo" />
					<p>
						Edit <code>src/App.js</code> and save to reload.
        			</p>
					<a
						className="App-link"
						href="https://reactjs.org"
						target="_blank"
						rel="noopener noreferrer"
					>
						Learn React right now!
        			</a>

					<StupidButton text="Press me" counter="0" />
					<StupidButton text="Don't press me" counter="99" />
				</header>
			</div>
		);
	}
};