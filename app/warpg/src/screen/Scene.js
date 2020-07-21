import React, { useState, useEffect } from 'react';
import { values } from '../consts.js';

const SceneLine = ({ length, char, initial, final }) => {
  return <>{initial + char.repeat(length) + final + values.NEW_LINE}</>;
}

const SceneCenterText = ({ length, initial, final, text, textLeft, textRight, color, backgroundColor, elementClass }) => {
  const middleText = `${textLeft}${text}${textRight}`;
  const halfLength = (length - middleText.length) / 2;
  const firstHalf = Math.floor(halfLength);
  const secondHalf = Number.isInteger(halfLength) ? halfLength : halfLength + 1;

  return (
    <>
      {initial}
      <span className={elementClass} style={{ color: color, backgroundColor: backgroundColor }}>
        {' '.repeat(firstHalf) + middleText + ' '.repeat(secondHalf)}
      </span>
      {final + values.NEW_LINE}
    </>
  );
}

const SceneHeader = ({ imgWidth, title, colors, chars }) =>
  <>
    <SceneLine
      length={imgWidth}
      char={chars.top_char}
      initial={chars.top_left}
      final={chars.top_right}
    />
    <SceneCenterText
      length={imgWidth}
      initial={chars.mid_left}
      final={chars.mid_right}
      text={title}
      textLeft={chars.title_left}
      textRight={chars.title_right}
      color={colors.title_color}
      backgroundColor={colors.title_background}
      elementClass={'scene-title'}
    />
    <SceneLine
      length={imgWidth}
      char={chars.bottom_char}
      initial={chars.bottom_left}
      final={chars.bottom_right}
    />
  </>

const SceneImage = ({ imageLines, imageInterval, imageWidth, borderWidth, color, backgroundColor, chars }) => {
  const [image, setImage] = useState(imageLines[0]);
  const [currImgIndex, setCurrentImgIndex] = useState(0);

  useEffect(() => {
    if (imageLines.length === 1) {
      return;
    }

    const intervalId = setInterval(() => {
      setCurrentImgIndex(
        (currImgIndex + 1) === imageLines.length
          ? 0
          : currImgIndex + 1
      );

      setImage(imageLines[currImgIndex])
    }, imageInterval);

    return () => clearInterval(intervalId);
  }, [imageLines, imageInterval, currImgIndex]);

  return (
    <>
      {image.map((line, i) => {
        let lineLeft = (imageWidth - (borderWidth * 2)) - line.size;
        lineLeft = lineLeft < 0 ? 0 : lineLeft;

        return (
          <React.Fragment key={i}>
            {chars.left}
            <span key={i} className='scene-image' style={{ color: color, backgroundColor: backgroundColor }}>
              {' '.repeat(borderWidth) + line.str + ' '.repeat(lineLeft) + ' '.repeat(borderWidth)}
            </span>
            {chars.right + values.NEW_LINE}
          </React.Fragment>
        )
      })}
    </>
  );
};

const SceneText = ({ imgWidth, borderWidth, textLines, color, backgroundColor, chars }) => {
  return <>
    <SceneLine length={imgWidth} char={chars.top_char} initial={chars.top_left} final={chars.top_right} />
    {textLines.map((line, i) =>
      <React.Fragment key={i}>
        {chars.left + ' '.repeat(borderWidth)}
        <span className='scene-text' style={{ color: color, backgroundColor: backgroundColor }} >
          {line.str + ' '.repeat((imgWidth - line.size) - borderWidth)}
        </span>
        {chars.right + values.NEW_LINE}
      </React.Fragment>)}
    <SceneLine length={imgWidth} char={chars.bottom_char} initial={chars.bottom_left} final={chars.bottom_right} />
  </>
};

const Option = ({ option, length, borderWidth, color, backgroundColor, hoverColor, hoverBackgroundColor, leftChar, rightChar, setDestiny }) => {
  const [isHovered, setIsHovered] = useState(false);

  return <span
    onMouseEnter={() => setIsHovered(true)}
    onMouseLeave={() => setIsHovered(false)}
    onClick={() => setDestiny(option)}
  >
    {option.lines.map((line, i) =>
      <React.Fragment key={i}>
        {leftChar + ' '.repeat(borderWidth)}
        <span
          className={'scene-option'}
          style={
            !isHovered
              ? { color: color, backgroundColor: backgroundColor }
              : { color: hoverColor, backgroundColor: hoverBackgroundColor }
          }
        >
          {line.str + ' '.repeat((length - line.size) - borderWidth)}
        </span>
        {rightChar + values.NEW_LINE}
      </React.Fragment>)}
  </span>
};

const Options = ({ options, imgWidth, borderWidth, colors, chars, setDestiny }) => {
  return <>
    {options.map(opt =>
      <Option
        key={opt.destiny}
        length={imgWidth}
        borderWidth={borderWidth}
        option={opt}
        setDestiny={setDestiny}
        color={colors.option_color}
        backgroundColor={colors.option_background}
        hoverColor={colors.option_hover_color}
        hoverBackgroundColor={colors.option_hover_background}
        leftChar={chars.left}
        rightChar={chars.right}
      />
    )}
    <SceneLine
      length={imgWidth}
      char={chars.bottom_char}
      initial={chars.bottom_left}
      final={chars.bottom_right}
    />
  </>
};

const Scene = ({ info, setDestiny }) => {
  const {
    image_width,
    image_lines,
    image_interval,
    border_width,
    title,
    text_lines,
    options,
    colors,
    chars,
    type
  } = info;

  return (
    <pre>
      <SceneHeader
        imgWidth={image_width}
        title={title}
        colors={colors}
        chars={chars.header}
      />
      <SceneImage
        imageLines={image_lines}
        imageInterval={image_interval}
        imageWidth={image_width}
        borderWidth={border_width}
        color={colors.image_color}
        backgroundColor={colors.image_background}
        chars={chars.image}
      />
      <SceneText
        imgWidth={image_width}
        borderWidth={border_width}
        textLines={text_lines}
        color={colors.text_color}
        backgroundColor={colors.text_background}
        chars={chars.text}
      />
      {type === 'view'
        ? <Options
          options={options}
          imgWidth={image_width}
          borderWidth={border_width}
          setDestiny={setDestiny}
          colors={colors}
          chars={chars.option}
        />
        : 'THE END'}
    </pre>
  );
}

export default Scene;