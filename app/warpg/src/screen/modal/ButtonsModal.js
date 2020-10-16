import React from 'react';
import Modal from './Modal.js';
import './ButtonsModal.scss';

const ButtonsModal = ({ text, buttons }) => {
  return (
    <Modal modalClass="modal-buttons">
      <div className="modal-text">{text}</div>
      <div className="modal-buttons-wrapper">
        {buttons.map(b =>
          <button
            key={b.label}
            onClick={b.handleClick}
          >
            {b.label}
          </button>
        )}
      </div>
    </Modal>
  );
};

export default ButtonsModal;