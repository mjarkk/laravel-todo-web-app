import axios from 'axios'

export default {
  // A wrapper around axios post
  fetch(url, body, callback) {
    if (typeof body != 'object') {
      body = {}
    }
    axios.post(url, 
      Object.assign({}, {
        credentials: 'same-origin',
        headers: {
          Accept: 'application/json',
          'X-CSRF-Token': window['csrf-token']
        }
      }, body)
    ).then(res => 
      (typeof callback == 'function')
        ? callback(res)
        : console.log(res)
    ).catch(err => 
      console.log(err)
    )
  }
}