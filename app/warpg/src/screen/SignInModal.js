import React, { useState } from 'react';
import Modal from './Modal.js';
import { login } from '../utils.js';

const SignInModal = ({ handleClose, handleSuccess }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleSubmit = async event => {
    event.preventDefault();

    const result = await login(email, password);
    if (result.success) {
      handleSuccess();
    } else {
      console.log(result.message);
    }
  };

  return (
    <Modal modalClass="modal-sign-in">
      <form onSubmit={handleSubmit}>
        <div className="modal-inputs">
          <div className="modal-input">
            <label>E-mail</label>
            <input type="email" onChange={event => setEmail(event.target.value)} required />
          </div>
          <div className="modal-input">
            <label>Password</label>
            <input type="password" onChange={event => setPassword(event.target.value)} required />
          </div>
        </div>

        <div className="modal-buttons">
          <button className="bt-sign-in" type="submit">Sign in</button>
          <button onClick={handleClose} type="button">Cancel</button>
        </div>
      </form>

    </Modal>
  );
};

export default SignInModal;