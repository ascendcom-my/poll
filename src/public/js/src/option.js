require('./micromodal.js');

window.addEventListener('load', function () {
    document.querySelectorAll('.btn-update').forEach(el => {
        el.addEventListener('click', evt => {
            document.getElementById('update-form').action = evt.currentTarget.dataset.route;
            document.getElementById('update-text').value = evt.currentTarget.dataset.text;
        });
    });
});