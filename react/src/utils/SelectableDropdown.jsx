import React, { useState } from 'react';
import {
  Dropdown,
  DropdownToggle,
  DropdownMenu,
  DropdownItem,
} from 'reactstrap';

export default function SelectableDropdown({className, items, onClick}) {
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [selectedItem, setSelectedItem] = useState('Filtres');

  const toggle = () => setDropdownOpen(!dropdownOpen);

  const handleSelect = (item) => {
    setSelectedItem(item.title);
    setDropdownOpen(false);
    onClick(item);
  };

  return (
    <Dropdown className={className} isOpen={dropdownOpen} toggle={toggle}>
      <DropdownToggle className='custom-button shadow' caret style={{backgroundColor:"#a899e7", color:"black", height:"45px", fontWeight:"700"}}>
        {selectedItem}
      </DropdownToggle>
      <DropdownMenu className='shadow' style={{backgroundColor:"#e2ddf7"}}>
        {items.map((item, index) => (
          <DropdownItem 
            key={index}
            onClick={() => handleSelect(item)}
          >
            {item.title}
          </DropdownItem>
        ))}
      </DropdownMenu>
    </Dropdown>
  );
}
