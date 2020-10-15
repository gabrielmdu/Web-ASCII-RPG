import React, { useState } from 'react';
import { common } from '../common/common.js';
import { logout } from '../utils.js';

import Dropdown from './Dropdown.js';
import SignInModal from './modal/SignInModal.js';

import './UserWidget.scss';

const UserWidget = ({ user, checkUser }) => {
  const [modal, setModal] = useState({
    show: false,
    type: null
  });

  const userItems = [{
    name: 'sign out x',
    callback: async () => {
      await logout();
      checkUser();
    }
  }];

  const guestItems = [{
    name: 'sign in >',
    callback: () => {
      setModal({
        show: true,
        type: common.modalTypes.SIGN_IN
      });
    }
  }, {
    name: 'sign up ^',
    callback: () => { }
  }];

  const handleModal = () => {
    if (!modal.show) {
      return;
    }

    switch (modal.type) {
      case common.modalTypes.SIGN_IN:
        return <SignInModal
          handleClose={() => setModal({ show: false })}
          handleSuccess={() => {
            checkUser();
            setModal({ show: false });
          }}
        />;

      default: return null;
    }
  };

  return (
    <>
      {handleModal()}
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