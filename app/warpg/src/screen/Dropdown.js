import React from 'react';
import { useComponentVisible } from '../utils.js';
import '../scss/dropdown.scss';

const Dropdown = ({ text, items }) => {
  const {
    ref,
    isComponentVisible,
    setIsComponentVisible
  } = useComponentVisible(false);

  return (
    <div className="dropdown-wrapper" ref={ref}>
      <div
        className="dropdown-text"
        onClick={() => setIsComponentVisible(true)}
      >
        {text}
      </div>
      {isComponentVisible
        && <div className="dropdown" onClick={() => setIsComponentVisible(false)}>
          <ul>
            {items.map((item, i) =>
              <li
                key={i}
                onClick={item.callback}>
                {item.name}
              </li>)}
          </ul>
        </div>}
    </div>
  );
};

export default Dropdown;