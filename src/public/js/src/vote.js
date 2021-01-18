import ReactDOM from 'react-dom';
import Widget from "./react/Widget";
import React from 'react';

window.addEventListener('load', function () {
  let data = document.getElementById('bigmom-poll-react-data');
  ReactDOM.render(
    <React.StrictMode>
        <Widget {...(data.dataset)} />
    </React.StrictMode>,
    document.getElementById('bigmom_poll_react_div')
  );
})