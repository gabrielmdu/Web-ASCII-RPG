import React, { useState, useEffect } from 'react';
import { CSSTransition } from 'react-transition-group';
import { fetchGet, fetchPost } from './utils.js';
import { values } from './consts.js';
import Modal from './screen/Modal.js';
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

  const setDestiny = async (option) => {
    if (!canSetDestiny) {
      return;
    }

    if (option.destiny) {
      setCanSetDestiny(false);
      setShowScene(false);
      setOutAnim(option.out_anim || sceneInfo.out_anim);

      const request = await fetchPost(values.API_BASE_URL + 'scene', { 'scene_id': option.destiny })
      const info = await request.json();
      setNextSceneInfo(info);
    } else if (option.note) {
      setModalText(option.note);
      setShowModal(true);
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

  return (
    <>
      {showModal
        && <Modal handleModalHide={() => setShowModal(false)}>
          {modalText}
        </Modal>}

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
                exitActive: values.ANIMATION_PREFIX_CLASS + outAnim
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
}

export default Game;