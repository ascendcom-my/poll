'use strict';

import React from 'react';
import QuestionManager from "../QuestionManager";
import Questions from "./Questions";
import PropTypes from 'prop-types';
import Status from '../Status';
import VoteManager from '../VoteManager';

export default class Widget extends React.Component {
  constructor(props) {
    super(props);
    this.state = { 
      questions: this.props.questions.split(','),
      voteRoute: props.voteroute,
      questionRoute: props.questionroute,
      voteManager: new VoteManager,
    };
  }

  componentDidMount() {
    let questionManager = new QuestionManager;
    questionManager.getQuestions(
      this.state.questions,
      this.state.questionRoute,
      (questions) => this.setState({ 
        questions: questions.map(question => {
          question.count = 0;
          question.abstain = false;
          question.errorMessage = null;
          question.options = question.options.map(option => {
            option.isSelected = false;
            option.question = question.token;
            let token = option.token;
            option.isAbstain = token.substring(token.length - 7, token.length) == 'ABSTAIN';
            return option;
          })
          return question;
        })
      })
    );
  }

  toggleOption = (option) => {
    if (option.isSelected == true) {
      return this.deselectOption(option);
    } else {
      return this.selectOption(option);
    }
  }

  selectOption = (optionMain) => {
    let questionMain = this.getQuestion(optionMain.question)

    if (optionMain.isSelected == true) {
      return new Status(1, 'Option is already selected.');
    }

    
    if (questionMain.input_type == 'radio') {
      this.setState({
        questions: this.state.questions.map(question => {
          if (question.token == questionMain.token) {
            question.count = 1;
            question.options = question.options.map(option => {
              if (option.token == optionMain.token) {
                option.isSelected = true;
              } else {
                option.isSelected = false;
              }
              return option;
            });
          }
          return question;
        })
      })
    } else {
      if (optionMain.isAbstain == true) {
        this.setState({ 
          questions: this.state.questions.map(question => {
            if (question.token == questionMain.token) {
              question.count = 1;
              question.abstain = optionMain.token;
              question.options = question.options.map(option => {
                option.isSelected = (option.token == optionMain.token)
                return option;
              });
            } 
            return question;
          })
        });
        return new Status(2, 'Abstain selected');
      } else if (questionMain.abstain != false) {
        this.setState({
          questions: this.state.questions.map(question => {
            if (question.token == questionMain.token) {
              this.deselectOption(this.getOption(question.abstain));
              question.abstain = false;
            }
            return question;
          })
        })
      }

      if (questionMain.count >= questionMain.max) {
        return new Status(1, 'Maximum options reached.');
      }

      this.setState({
        questions: this.state.questions.map(question => {
          if (question.token == questionMain.token) {
            question.count++;
            question.options = question.options.map(option => {
              if (option.token == optionMain.token) {
                option.isSelected = true;
              }
              return option;
            })
          }
          return question
        })
      })
    }

    return new Status(0, 'Option successfully selected.');
  }

  deselectOption = (optionMain) => {
    let questionMain = this.getQuestion(optionMain.question)
    
    if (optionMain.isSelected !== true) {
      return new Status(1, 'Option was not selected.');
    }

    if (questionMain.count <= 0) {
      return new Status(1, 'Question already has 0 options.');
    }

    this.setState({
      questions: this.state.questions.map(question => {
        if (question.token == questionMain.token) {
          question.count--;
          question.options = question.options.map(option => {
            if (option.token == optionMain.token) {
              option.isSelected = false;
            }
            return option;
          })
        }
        return question;
      })
    })
  }

  getQuestion = (token) => {
    let questions = this.state.questions;
    for (let key in this.state.questions) {
      let question = questions[key];
      if (question.token == token) {
        return question;
      }
    }
  }

  getOption = (token) => {
    let questions = this.state.questions;
    for (let questionKey in questions) {
      let question = questions[questionKey];
      let options = question.options;
      for (let optionKey in options) {
        let option = options[optionKey];
        if (option.token == token) {
          return option;
        }
      }
    }
  }

  castVote = (questionMain) => {
    questionMain = this.getQuestion(questionMain.token);
    let options = questionMain.options.filter(option => {
      return option.isSelected;
    }).map(option => {
      return option.token;
    });
    if (questionMain.abstain == false) {
      if (options.length < questionMain.min) {
        this.setState({
          questions: this.state.questions.map(question => {
            if (question.token == questionMain.token) {
              question.errorMessage = "Minimum options not reached.";
            }
            return question;
          })
        })
        return;
      } else if (options.length > questionMain.max) {
        this.setState({
          questions: this.state.questions.map(question => {
            if (question.token == questionMain.token) {
              question.errorMessage = "Maximum options exceeded.";
            }
            return question;
          })
        })
        return;
      }
    }
    this.state.voteManager.castVote(options, this.state.voteRoute, (result) => {
      this.setState({
        questions: this.state.questions.map(question => {
          if (question.token == questionMain.token) {
            question.has_voted = true;
            question.errorMessage = null;
            question.options = question.options.map(option => {
              if (option.token in result) {
                option.cache_count = result[option.token] + 1;
              }
              return option;
            })
          }
          return question;
        })
      })
    });
  }

  render() {
    return (typeof this.state.questions[0] === 'object') ?
    (
      <div>
        <Questions questions={ this.state.questions } toggleOption={ this.toggleOption } castVote={ this.castVote } />
      </div>
    )
    : null;
  }
}

Widget.propTypes = {
  questions: PropTypes.string.isRequired,
  voteroute: PropTypes.string.isRequired,
  questionroute: PropTypes.string.isRequired,
}
