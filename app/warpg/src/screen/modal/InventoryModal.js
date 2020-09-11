import React from 'react';
import Modal from './Modal.js';

const InventoryModal = ({ items, handleClickItem }) => {
  return (
    <Modal modalClass="modal-inventory">
      <div className="inventory-container">
        <div className="inventory-title">
          <div className="inventory-title-text">Inventory</div>
        </div>
        <div className="inventory-items">
          {items.length > 0
            ? <table>
              <thead>
                <tr>
                  <th className="name">Name</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                {items.map(item =>
                  <tr key={item.id} onClick={() => handleClickItem(item)}>
                    <td className="name">{item.name}</td>
                    <td>{item.description}</td>
                  </tr>)}
              </tbody>
            </table>
            : <span>No items</span>}
        </div>
      </div>
    </Modal>
  );
};

export default InventoryModal;