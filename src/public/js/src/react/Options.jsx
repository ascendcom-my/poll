import React, { Component } from 'react'
import PropTypes from 'prop-types';
import Option from './Option';

export class Options extends Component {
  render() {
    return this.props.options.map(option => (
      <Option option={ option } question={ this.props.question } key={ option.token } toggleOption={ this.props.toggleOption } />
    ));
  }
}

Options.propTypes = {
  question: PropTypes.object.isRequired,
  options: PropTypes.array.isRequired,
}

export default Options
