<script>

import axios from 'axios'

export default {
  name: 'ContactForm',

  data () {
    return {
      sending: false,
      status: undefined,
      errorMessage: undefined,
      form: {
        name: undefined,
        email: undefined,
        subject: undefined,
        message: undefined
      }
    }
  },

  methods: {
    validateAndSubmit () {
      this.status = undefined
      const url = this.$refs.form.getAttribute('action')

      this.$validator.validateAll().then(valid => {
        if (valid) {
          this.sending = true
          axios.post(url, this.form)
            .then(response => {
              this.status = 'success'
              this.sending = false
            })
            .catch(error => {
              if (error.response.data.hasOwnProperty('error')) this.errorMessage = 'Fehlermeldung: ' + error.response.data.error
              this.status = 'error'
            })
        }
      })
    },

    hasError (fieldName) {
      // if (this.fields[fieldName]) {
      //   console.log(this.fields[fieldName])
      //   if (!this.fields[fieldName].dirty) return false
      // }
      return this.errors.has(fieldName)
    },

    isFilled (property) {
      if (!property) return false

      if (property.length > 0 && property !== ' ') return true

      return false
    }
  }
}
</script>
