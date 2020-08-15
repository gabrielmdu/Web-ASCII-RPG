import React from 'react';
import '../scss/dropdown.scss';

const Dropdown = ({ visible, items }) => {
  return (
    visible
    && <div className="dropdown">
      <ul>
        {items.map((item, i) =>
          <li
            key={i}
            onClick={item.callback}>
            {item.name}
          </li>)}
      </ul>
    </div>
  );
};

export default Dropdown;