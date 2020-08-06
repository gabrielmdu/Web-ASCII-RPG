import React, { useState, useEffect } from 'react';
import { CSSTransition } from 'react-transition-group';
import { fetchGet, fetchPost } from './utils.js';
import { values } from './consts.js';
import Modal from './screen/Modal.js';
import LoadingModal from './screen/LoadingModal.js';
import Scene from './screen/Scene.js';
import 'animate.css/animate.min.css';
import './scss/index.scss';

const Game = ({ gameInfo }) => {
  const [sceneInfo, setSceneInfo] = useState(null);
  const [nextSceneInfo, setNextSceneInfo] = useState(null);
  const [showScene, setShowScene] = useState(false);
  const [canSetDestiny, setCanSetDestiny] = useState(false);
  const [isExited, setIsExited] = useState(false);
  const [outAnim, setOutAnim] = useState('');
  const [showModal, setShowModal] = useState(false);
  const [modalText, setModalText] = useState('');
  const [isModalLoading, setisModalLoading] = useState(false);

  const setModal = (type, text) => {
    if (type === 'loading') {
      setisModalLoading(true);
    } else if (type === 'normal') {
      setisModalLoading(false);
      setModalText(text);
    }

    setShowModal(true);
  };

  const setDestiny = async (index, option) => {
    if (!canSetDestiny) {
      return;
    }

    setModal('loading');
    setCanSetDestiny(false);

    const request = await fetchPost(values.API_BASE_URL + 'scene', { 'option': index });
    const info = await request.json();

    console.log(info);

    if (info.resource_type === 'note') {
      setModal('normal', info.text);
      setCanSetDestiny(true);
    } else if (info.resource_type === 'scene') {
      setShowModal(false);
      setOutAnim(option.out_anim || sceneInfo.out_anim);
      setShowScene(false);

      setNextSceneInfo(info);
    }
  };

  useEffect(() => {
    const fetchScene = async () => {
      let info;
      const request = await fetchGet(values.API_BASE_URL + 'scene');
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

  const handleModal = () => {
    if (!showModal) {
      return;
    }

    return (
      isModalLoading
        ? <LoadingModal />
        : <Modal handleModalHide={() => setShowModal(false)}>
          {modalText}
        </Modal>
    );
  };

  return (
    <>
      {handleModal()}

      <div className="main-panel">
        <div className="pre-wrapper">
          {sceneInfo
            ? <CSSTransition
              in={showScene}
              timeout={1500}
              classNames={{
                enter: values.ANIMATION_DEFAULT_CLASS,
                enterActive: values.ANIMATION_PREFIX_CLASS + sceneInfo.in_anim,
                exit: values.ANIMATION_DEFAULT_CLASS,
                exitActive: values.ANIMATION_PREFIX_CLASS + (outAnim ? outAnim : sceneInfo.out_anim)
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