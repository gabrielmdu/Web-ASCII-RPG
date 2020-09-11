import React from 'react';
import './About.scss';

const About = () => {
  return (
    <div className="about">
      <pre>{String.raw`
  ______  __                       __      
 /\  _  \/\ \                     /\ \__   
 \ \ \_\ \ \ \____    ___   __  __\ \  _\  
  \ \  __ \ \  __ \  / __ \/\ \/\ \\ \ \/  
   \ \ \/\ \ \ \_\ \/\ \_\ \ \ \_\ \\ \ \_ 
    \ \_\ \_\ \____/\ \____/\ \____/ \ \__\
     \/_/\/_/\/___/  \/___/  \/___/   \/__/ 
`}</pre>
      <div className="made-wrapper">
        <div>made by</div>
        <a
          href="https://gabrielmdu.github.io"
          target="blank"
        >
          Gabriel Schulte
        </a>
      </div>
    </div>
  );
};

export default About;