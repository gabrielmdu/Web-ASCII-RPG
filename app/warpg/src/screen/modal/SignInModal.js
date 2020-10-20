import React, { useState } from 'react';
import { login } from '../../utils.js';
import { hideModal } from '../../actions/modalActions.js';
import { useDispatch } from 'react-redux';
import { common } from '../../common/common.js';

import ButtonsModal from './ButtonsModal.js';

import './SignInModal.scss';

const SignInModal = ({ handleSuccess }) => {
  const dispatch = useDispatch();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async event => {
    event.preventDefault();
    setLoading(true);
    setError('');

    const result = await login(email, password);

    if (result.success) {
      handleSuccess();
    } else {
      setLoading(false);
      setError(result.message);
    }
  };

  return (
    <ButtonsModal
      title="Sign in"
      type={common.dialogTypes.NORMAL}
      buttons={[{
        label: 'Cancel',
        handleClick: () => dispatch(hideModal())
      }]}
    >

      <div className="modal-sign-in">
        {error && <div className="modal-error">{error}</div>}

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
            <button className="bt-sign-in" type="submit" disabled={loading}>
              {loading ? 'Signing in...' : 'Sign in'}
            </button>
          </div>
        </form>
      </div>

    </ButtonsModal>
  );
};

export default SignInModal;