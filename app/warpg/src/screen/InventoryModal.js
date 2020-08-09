import React from 'react';
import Modal from './Modal.js';

const InventoryModal = ({ items }) => {
  return (
    <Modal>
      <div className="inventory">
        <div className="title">
          <div className="title-text">Inventory</div>
        </div>
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
                <tr key={item.id}>
                  <td className="name">{item.name}</td>
                  <td>{item.description}</td>
                </tr>)}
            </tbody>
          </table>
          : 'No items'}
      </div>
    </Modal>
  );
};

export default InventoryModal;