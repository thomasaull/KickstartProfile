import axios from 'axios'
import _ from 'lodash'

// report all errors, which occured before javascript was loaded
_.forEach(window.errorQueue, error => {
  logErrorToProcessWire(error.error)
})

function logErrorToProcessWire (error) {
  const message = (error && error.stack) || '(not set)'
  axios.post('/api/error', {
    message: message
  })
}
