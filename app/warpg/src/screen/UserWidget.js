import React from 'react';
import Dropdown from './Dropdown.js';
import { useComponentVisible } from '../utils.js';
import '../scss/user-widget.scss';

const UserWidget = ({ user }) => {
  const {
    ref,
    isComponentVisible,
    setIsComponentVisible
  } = useComponentVisible(false);

  const userItems = [{
    name: 'sign out x',
    callback: () => { }
  }];

  const guestItems = [{
    name: 'sign in >',
    callback: () => {
    }
  }, {
    name: 'sign up ^',
    callback: () => { }
  }];

  return (
    <div className={'user-widget-wrapper'}>
      <div className={'user-widget'}>
        Welcome,&nbsp;
      <span
          ref={ref}
          className="user-name"
          onClick={() => setIsComponentVisible(true)}
        >
          {user ? user : 'guest'}
        </span>
        <Dropdown
          visible={isComponentVisible}
          items={user ? userItems : guestItems}
        />
      </div>
    </div>
  );
};

export default UserWidget;