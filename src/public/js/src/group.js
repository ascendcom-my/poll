import Sortable from 'sortablejs';
import axios from 'axios';
import MicroModal from 'micromodal';

window.addEventListener('load', function () {
    MicroModal.init();
    var sortable = Sortable.create(document.getElementById('sort-root'), {
        handle: '.sort-handle',
        dataIdAttr: 'data-token'
    });
    document.getElementById('sort-save').addEventListener('click', evt => {
        axios.post(evt.currentTarget.dataset.route, {
            'sorted': sortable.toArray(),
            'group_id': document.getElementById('sort-root').dataset.group
        })
        .then(response => {
            document.getElementById('modal-success-message').innerHTML = response.data.message;
            MicroModal.show('modal-success');
        })
        .catch(error => {
            document.getElementById('modal-error-message').innerHTML = error.response.data.message;
            MicroModal.show('modal-error');
        });
    });
});
