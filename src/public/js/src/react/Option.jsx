import React, { Component } from 'react'
import PropTypes from 'prop-types';

export class Option extends Component {
  getHideOnVotedStyle = () => {
    if (this.props.question.has_voted == true) {
      return {
        display: 'none',
      }
    } else {
      return {}
    }
  }

  getShowOnVotedStyle = () => {
    if (this.props.question.has_voted == false) {
      return {
        display: 'none',
      }
    } else {
      return {}
    }
  }

  toggleOption = (option) => {
    this.props.toggleOption(option);
  }

  render() {
    const { option, question } = this.props;
    return (
      <label htmlFor={ option.token } className="flex items-center" key={ option.token }>
        <input id={ option.token } type={ question.input_type } name={ question.token }
          onChange={ this.toggleOption.bind(this, option) } checked={ option.isSelected }
          style={ this.getHideOnVotedStyle() } />
        <span className="mx-4">{ option.text }</span>
        <span style={ this.getShowOnVotedStyle() }>
          {
            typeof option.cache_count !== 'undefined'
              ? '(' + option.cache_count + ' votes)' 
              : '' 
          }
          </span>
      </label> 
    )
  }
}

Option.propTypes = {
  question: PropTypes.object.isRequired,
  option: PropTypes.object.isRequired,
}

export default Option
