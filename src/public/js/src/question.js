import flatpickr from 'flatpickr';

require('./micromodal.js');

window.addEventListener('load', function () {
    flatpickr('.flatpickr', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i'
    });
    document.querySelectorAll('.btn-update').forEach(el => {
        el.addEventListener('click', evt => {
            let dataset = evt.currentTarget.dataset;
            document.getElementById('update-form').action = dataset.route;
            document.getElementById('update-title').value = dataset.title;
            document.getElementById('update-start').value = dataset.start
            document.getElementById('update-stop').value = dataset.stop;
            document.getElementById('update-reveal').checked = (dataset.reveal == "1");
            document.getElementById('update-group').value = dataset.group;
        })
    });
    document.querySelectorAll('.btn-delete').forEach(el => {
        el.addEventListener('click', evt => {
            document.getElementById('delete-form').action = evt.currentTarget.dataset.route;
            document.getElementById('delete-title').innerHTML = evt.currentTarget.dataset.title;
        });
    });
    document.querySelectorAll('.btn-fill-in').forEach(el => {
        el.addEventListener('click', evt => {
            document.getElementById(evt.currentTarget.dataset.target).value = document.getElementById('required-data').dataset.serverTime;
        });
    });
});
