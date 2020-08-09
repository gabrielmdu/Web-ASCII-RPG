import React, { useState, useEffect } from 'react';
import { CSSTransition } from 'react-transition-group';
import { fetchGet, fetchPost, GlobalKeyUpEvent } from './utils.js';
import { commons } from './consts.js';
import Modal from './screen/Modal.js';
import LoadingModal from './screen/LoadingModal.js';
import InventoryModal from './screen/InventoryModal.js';
import Scene from './screen/Scene.js';
import 'animate.css/animate.min.css';
import './scss/index.scss';

const Game = ({ gameInfo }) => {
  // scene
  const [sceneInfo, setSceneInfo] = useState(null);
  const [nextSceneInfo, setNextSceneInfo] = useState(null);
  const [showScene, setShowScene] = useState(false);
  const [canSetDestiny, setCanSetDestiny] = useState(false);
  // transitions
  const [isExited, setIsExited] = useState(false);
  const [outAnim, setOutAnim] = useState('');
  // modal
  const [showModal, setShowModal] = useState(false);
  const [modalType, setModalType] = useState('');
  const [modalContent, setModalContent] = useState('');
  // inventory
  const [inventory, setInventory] = useState(gameInfo.player_items);

  useEffect(() => {
    const fetchScene = async () => {
      let info;
      const request = await fetchGet(commons.API_BASE_URL + 'scene');
      if (request.status === 200) {
        info = await request.json();
      }

      setSceneInfo(info);
      setShowScene(true);
    };

    fetchScene();
  }, []);

  useEffect(() => {
    if (!nextSceneInfo || !isExited) {
      return;
    }

    setSceneInfo(nextSceneInfo);
    setNextSceneInfo(null);
    setIsExited(false);
    setShowScene(true);
  }, [nextSceneInfo, isExited]);

  const createModal = (type, content) => {
    setModalType(type);
    setModalContent(content);
    setShowModal(true);
  };

  const setDestiny = async (index, option) => {
    if (!canSetDestiny) {
      return;
    }

    createModal(commons.modalTypes.LOADING);
    setCanSetDestiny(false);

    const request = await fetchPost(commons.API_BASE_URL + 'scene', { 'option': index });
    const info = await request.json();

    console.log(info);

    switch (info.resource_type) {
      case commons.resourceTypes.NOTE:
        createModal(commons.modalTypes.MODAL, info.text);
        setCanSetDestiny(true);
        break;

      case commons.resourceTypes.ITEM:
        setInventory([...inventory, info.attributes]);
        createModal(commons.modalTypes.MODAL, info.note);
        setCanSetDestiny(true);
        break;
      
      case commons.resourceTypes.SCENE:
        setShowModal(false);
        setOutAnim(option.out_anim || sceneInfo.out_anim);
        setShowScene(false);
        setNextSceneInfo(info);
        break;
      
      default:
    }
  };

  const handleModal = () => {
    if (!showModal) {
      return;
    }

    switch (modalType) {
      case commons.modalTypes.MODAL:
        return <Modal handleModalHide={() => setShowModal(false)}>
          {modalContent}
        </Modal>;

      case commons.modalTypes.LOADING:
        return <LoadingModal />;

      case commons.modalTypes.INVENTORY:
        return <InventoryModal items={inventory} />;

      default: return null;
    }
  };

  const handleInventory = ({ keyCode }) => {
    if (!showModal) {
      if (keyCode !== commons.keys.I || !canSetDestiny) {
        return;
      }

      createModal(commons.modalTypes.INVENTORY);
    } else {
      if (modalType === commons.modalTypes.INVENTORY && keyCode === commons.keys.ESCAPE) {
        setShowModal(false);
      }
    }
  };

  return (
    <>
      <GlobalKeyUpEvent handler={handleInventory} />
      {handleModal()}

      <div className="main-panel">
        <div className="pre-wrapper">
          {sceneInfo
            ? <CSSTransition
              in={showScene}
              timeout={1500}
              classNames={{
                enter: commons.ANIMATION_DEFAULT_CLASS,
                enterActive: commons.ANIMATION_PREFIX_CLASS + sceneInfo.in_anim,
                exit: commons.ANIMATION_DEFAULT_CLASS,
                exitActive: commons.ANIMATION_PREFIX_CLASS + (outAnim ? outAnim : sceneInfo.out_anim)
              }}
              unmountOnExit
              onEntered={() => setCanSetDestiny(true)}
              onExited={() => setIsExited(true)}
            >
              <Scene info={sceneInfo} setDestiny={setDestiny} />
            </CSSTransition>
            : <div>loading scene...</div>}
        </div>
      </div>
    </>
  );
};

export default Game;