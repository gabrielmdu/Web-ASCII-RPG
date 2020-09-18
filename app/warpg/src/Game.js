import React, { useState, useEffect, useRef } from 'react';
import { CSSTransition } from 'react-transition-group';
import { fetchAuthGet, fetchAuthPost, GlobalKeyUpEvent } from './utils.js';
import { commons } from './consts.js';
import Tippy from '@tippyjs/react';
import { followCursor } from 'tippy.js';
import Modal from './screen/modal/Modal.js';
import LoadingModal from './screen/modal/LoadingModal.js';
import InventoryModal from './screen/modal/InventoryModal.js';
import Scene from './screen/Scene.js';
import 'animate.css/animate.min.css';
import './scss/index.scss';

const Game = ({ gameInfo }) => {
  const mainPanel = useRef(null);
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
  const defaultInvItem = {
    using: false,
    item: null
  };
  const [currInvItem, setCurrInvItem] = useState(defaultInvItem);

  useEffect(() => {
    const fetchScene = async () => {
      let info;
      const request = await fetchAuthGet('scene');
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

    const request = await fetchAuthPost('scene', {
      'option': index,
      'item': currInvItem.using ? currInvItem.item.id : null
    });
    const info = await request.json();

    console.log(info);

    switch (info.resource_type) {
      case commons.resourceTypes.NOTE:
        if (info.data && info.data.used === true) {
          setInventory(inventory.filter(i => i.id !== currInvItem.item.id));
        }
        createModal(commons.modalTypes.COMMON, info.text);
        setCanSetDestiny(true);
        break;

      case commons.resourceTypes.ITEM:
        setInventory([...inventory, info.attributes]);
        createModal(commons.modalTypes.COMMON, info.note);
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

    if (currInvItem.using) {
      setCurrInvItem(defaultInvItem);
    }
  };

  const handleModal = () => {
    if (!showModal) {
      return;
    }

    switch (modalType) {
      case commons.modalTypes.COMMON:
        return <Modal handleModalHide={() => setShowModal(false)}>
          {modalContent}
        </Modal>;

      case commons.modalTypes.LOADING:
        return <LoadingModal />;

      case commons.modalTypes.INVENTORY:
        return <InventoryModal items={inventory} handleClickItem={handleClickInvItem} />;

      default: return null;
    }
  };

  const handleKeyUp = ({ keyCode }) => {
    switch (keyCode) {
      case commons.keys.I:
        if (!showModal && canSetDestiny) {
          createModal(commons.modalTypes.INVENTORY);
        }
        break;

      case commons.keys.ESCAPE:
        if (showModal && modalType === commons.modalTypes.INVENTORY) {
          setShowModal(false);
        } else if (currInvItem.using) {
          setCurrInvItem(defaultInvItem);
        }
        break;

      default: return;
    }
  };

  const handleClickInvItem = item => {
    setCurrInvItem({
      using: true,
      item: item
    });

    setShowModal(false);
  };

  return (
    <>
      <GlobalKeyUpEvent handler={handleKeyUp} />
      {handleModal()}

      <div ref={mainPanel} className="main-panel">
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

      <Tippy
        content={currInvItem.item ? `Combine ${currInvItem.item.name} with...` : ''}
        followCursor
        duration={0}
        reference={mainPanel}
        plugins={[followCursor]}
        theme={'warpg-tooltip'}
        disabled={!currInvItem.using} />
    </>
  );
};

export default Game;