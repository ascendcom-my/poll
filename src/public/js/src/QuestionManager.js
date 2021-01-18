import axios from "axios";

export default class QuestionManager {
  getQuestions(questions, route, callback) {
    axios.get(route, {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      params: {
        questions: questions,
      }
    }).then(response => {
      callback(response.data.questions);
    }).catch(error => {
      if (typeof error.response !== 'undefined') {
        console.log(error.response);
      } else {
        console.log(error);
      }
    });
  }
}