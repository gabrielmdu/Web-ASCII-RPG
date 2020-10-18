import React from 'react';
import { createStore } from 'redux';
import { Provider } from 'react-redux';
import { reducers } from './reducers/index.js';
import ReactDOM from 'react-dom';

import App from './App.js';

const store = createStore(reducers);

ReactDOM.render(
  <Provider store={store}>
    <App />
  </Provider>,
  document.getElementById('root')
);