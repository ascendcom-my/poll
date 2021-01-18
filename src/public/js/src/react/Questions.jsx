import React, { Component } from 'react'
import Options from './Options'
import PropTypes from 'prop-types';

export class Questions extends Component {
  render() {
    return this.props.questions.map(question => (
      <div key={ question.token } className="bg-white rounded-md shadow-xl px-4 py-4 my-2">
        <h4 className="text-large font-verdana">{ question.title } (min: { question.min }, max: { question.max })</h4>
        <div className="my-4">
          <Options options={ question.options } question={ question } toggleOption={ this.props.toggleOption } />
        </div>
        <button onClick={ this.props.castVote.bind(this, question) } className="bg-gray-200 rounded text-black px-4 py-2">Vote</button>
        <div>
          <span className="text-red-600">{ question.errorMessage }</span>
        </div>
      </div>
    ));
  }
}

Questions.propTypes = {
  questions: PropTypes.array.isRequired,
}

export default Questions
