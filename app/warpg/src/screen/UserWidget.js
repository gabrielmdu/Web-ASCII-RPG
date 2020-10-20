import React from 'react';
import { useDispatch } from 'react-redux';
import { hideModal, showSignInModal } from '../actions/modalActions.js';
import { logout } from '../utils.js';

import Dropdown from './Dropdown.js';

import './UserWidget.scss';

const UserWidget = ({ user, checkUser }) => {
  const dispatch = useDispatch();
  
  const userItems = [{
    name: 'sign out x',
    callback: async () => {
      await logout();
      checkUser();
    }
  }];

  const guestItems = [{
    name: 'sign in >',
    callback: () => dispatch(showSignInModal(() => {
      checkUser();
      dispatch(hideModal());
    }))
  }, {
    name: 'sign up ^',
    callback: () => { }
  }];

  return (
    <>
      <div className={'user-widget-wrapper'}>
        <div className={'user-widget'}>
          Welcome,&nbsp;
          <Dropdown
            text={user ? user.name : 'guest'}
            items={user ? userItems : guestItems}
          />
        </div>
      </div>
    </>
  );
};

export default UserWidget;