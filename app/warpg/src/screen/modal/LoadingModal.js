import React from 'react';
import Modal from './Modal.js';
import './LoadingModal.scss';

const LoadingModal = () => {
  return (
    <Modal modalClass="modal-loading">
      loading...
    </Modal>
  );
};

export default LoadingModal;