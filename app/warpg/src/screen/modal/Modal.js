import React from 'react';
import './Modal.scss';

const Modal = ({ children, handleModalHide, modalClass, modalStyle }) => {
  const className = modalClass ? modalClass : 'modal-common';

  return (
    <div
      className="modal-background"
      onClick={handleModalHide}
    >
      <div
        className={'modal ' + className}
        style={modalStyle}
      >
        {children}
      </div>
    </div>
  );
};

export default Modal;