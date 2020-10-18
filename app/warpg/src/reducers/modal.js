import React from 'react';

import {
  SHOW_COMMON_MODAL,
  SHOW_LOADING_MODAL,
  SHOW_INVENTORY_MODAL,
  SHOW_BUTTONS_MODAL,
  HIDE_MODAL
} from '../actions/modalActions.js';
import { common } from '../common/common.js';

import Modal from '../screen/modal/Modal.js';
import LoadingModal from '../screen/modal/LoadingModal.js';
import InventoryModal from '../screen/modal/InventoryModal.js';
import ButtonsModal from '../screen/modal/ButtonsModal.js';

const initialState = {
  show: false
};

export const modal = (state = initialState, action) => {
  switch (action.type) {
    case SHOW_COMMON_MODAL:
      return {
        ...state,
        show: true,
        component:
          <Modal handleModalHide={action.handleModalHide}>
            {action.content}
          </Modal>
      };

    case SHOW_LOADING_MODAL:
      return {
        ...state,
        modalType: common.modalTypes.LOADING,
        show: true,
        component: <LoadingModal />
      };

    case SHOW_INVENTORY_MODAL:
      return {
        ...state,
        modalType: common.modalTypes.INVENTORY,
        show: true,
        component:
          <InventoryModal
            items={action.items}
            handleClickItem={action.handleClickItem}
          />
      };

    case SHOW_BUTTONS_MODAL:
      return {
        ...state,
        modalType: common.modalTypes.BUTTONS,
        show: true,
        component:
          <ButtonsModal
            type={action.dialogType}
            title={action.title}
            text={action.text}
            buttons={action.buttons}
          />
      };

    case HIDE_MODAL:
      return {
        ...state,
        show: false
      };

    default:
      return state;
  }
};  