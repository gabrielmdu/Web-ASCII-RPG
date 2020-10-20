import React from 'react';
import Modal from './Modal.js';
import { common } from '../../common/common.js';

import './ButtonsModal.scss';

const getTypeColor = type => {
  const { dialogTypes: dt } = common;

  switch (type) {
    case dt.CONFIRMATION: return '#0635bb';
    case dt.WARNING: return '#98af1a';
    case dt.ERROR: return '#c10707';
    case dt.SUCCESS: return '#2ebb00';
    case dt.NORMAL:
    default: return '#3b0075';
  }
};

const ButtonsModal = ({ children, type, title, buttons }) => {
  return (
    <Modal modalClass="modal-buttons" modalStyle={{ borderColor: getTypeColor(type) }}>
      {title
        && <div
          className="modal-title"
          style={{ background: getTypeColor(type) }}>
          {title}
        </div>}

      <div className="modal-content">
        {children}
      </div>

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