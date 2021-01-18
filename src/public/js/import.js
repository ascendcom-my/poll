/******/ (() => { // webpackBootstrap
/*!********************************!*\
  !*** ./resources/js/import.js ***!
  \********************************/
window.addEventListener('load', function () {
  document.getElementById('truncate').addEventListener('click', function (evt) {
    var checkbox = evt.currentTarget;

    if (checkbox.checked) {
      var confirmation = confirm('Are you sure? This will delete all data in questions, options, and votes table.');

      if (confirmation == true) {
        checkbox.checked = true;
      } else {
        checkbox.checked = false;
      }
    }
  });
});
/******/ })()
;