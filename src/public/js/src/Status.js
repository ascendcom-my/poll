export default class Status {
  constructor(code = 0, message = '', extra = null) {
    this.code = code;
    this.message = message;
    this.extra = extra;
  }

  getCode() {
    return this.code;
  }

  getMessage() {
    return this.message;
  }

  getExtra() {
    return this.extra;
  }

  setCode(code) {
    this.code = code;
  }

  setMessage(message) {
    this.message = message;
  }

  setExtra(extra) {
    this.extra = extra;
  }
}