export const SHOW_COMMON_MODAL = "SHOW_COMMON_MODAL";
export const SHOW_LOADING_MODAL = "SHOW_LOADING_MODAL";
export const SHOW_INVENTORY_MODAL = "SHOW_INVENTORY_MODAL";
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
  items: items,
  handleClickItem: handleClickItem
});

export const hideModal = () => ({
  type: HIDE_MODAL
});