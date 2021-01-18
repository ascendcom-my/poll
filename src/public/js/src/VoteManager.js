import axios from "axios";

export default class VoteManager {
  castVote(options, route, callback) {
    axios.post(route, {
      option: options,
    }, {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(response => {
      callback(response.data.result);
    }).catch(error => {
      if (typeof error.response !== 'undefined') {
        console.log(error.response);
      } else {
        console.log(error);
      }
    })
  }
}