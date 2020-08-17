import React, { useState } from 'react';
import Dropdown from './Dropdown.js';
import SignInModal from './SignInModal.js';
import { commons } from '../consts.js';
import '../scss/user-widget.scss';

const UserWidget = ({ userName, setUserName }) => {
  const [modal, setModal] = useState({
    show: false,
    type: null
  });

  const userItems = [{
    name: 'sign out x',
    callback: () => { }
  }];

  const guestItems = [{
    name: 'sign in >',
    callback: () => {
      setModal({
        show: true,
        type: commons.modalTypes.SIGN_IN
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
      case commons.modalTypes.SIGN_IN:
        return <SignInModal
          handleClose={() => setModal({ show: false })}
          handleSuccess={() => {
            setUserName();
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
            text={userName ? userName : 'guest'}
            items={userName ? userItems : guestItems}
          />
        </div>
      </div>
    </>
  );
};

export default UserWidget;