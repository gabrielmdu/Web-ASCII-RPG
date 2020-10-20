export const SHOW_COMMON_MODAL = "SHOW_COMMON_MODAL";
export const SHOW_LOADING_MODAL = "SHOW_LOADING_MODAL";
export const SHOW_INVENTORY_MODAL = "SHOW_INVENTORY_MODAL";
export const SHOW_BUTTONS_MODAL = "SHOW_BUTTONS_MODAL";
export const SHOW_SIGN_IN_MODAL = "SHOW_SIGN_IN_MODAL";
export const HIDE_MODAL = "HIDE_MODAL";

export const showCommonModal = (content, handleModalHide) => ({
  type: SHOW_COMMON_MODAL,
  content,
  handleModalHide
});

export const showLoadingModal = () => ({
  type: SHOW_LOADING_MODAL
});

export const showInventoryModal = (items, handleClickItem) => ({
  type: SHOW_INVENTORY_MODAL,
  items,
  handleClickItem
});

export const showButtonsModal = (dialogType, title, content, buttons) => ({
  type: SHOW_BUTTONS_MODAL,
  dialogType,
  title,
  content,
  buttons
});

export const showSignInModal = handleSuccess => ({
  type: SHOW_SIGN_IN_MODAL,
  handleSuccess
});

export const hideModal = () => ({
  type: HIDE_MODAL
});