import React from 'react';

const Modal = ({ children, handleModalHide, modalClass }) => {
  const className = modalClass ? modalClass : 'modal-common';

  return (
    <div
      className="modal-background"
      onClick={handleModalHide}
    >
      <div className={'modal ' + className}>
        {children}
      </div>
    </div>
  );
};

export default Modal;