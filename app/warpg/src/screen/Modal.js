import React from 'react';

const Modal = ({ children, handleModalHide }) => {
    return (
        <div
            className="modal-background"
            onClick={() => handleModalHide()}
        >
            <div className="modal">
                {children}
            </div>
        </div>
    );
};

export default Modal;