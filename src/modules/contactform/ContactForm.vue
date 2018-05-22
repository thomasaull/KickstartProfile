<script>

import axios from 'axios'

export default {
  el: document.querySelector('.contactForm'),
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
      const url = this.$el.getAttribute('action')

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
    }
  }
}
</script>

<style lang="scss">

.ContactForm {
  // IE Styles
  // display: flex;
  // flex-direction: column;
  // .ContactForm-inputWrapper { margin-bottom: 10px;}

  @supports (grid-template-areas: "area") {
    .ContactForm-inputWrapper { margin-bottom: initial; }
  }

  display: grid;
  grid-gap: 10px;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: auto auto auto auto;
  grid-template-areas:
    "name email"
    "subject subject"
    "message message"
    "control control";

  @include media("<=550px") {
    grid-template-columns: 1fr;
    grid-template-rows: auto auto auto auto auto;
    grid-template-areas:
      "name"
      "email"
      "subject"
      "message"
      "control";
  }
}

.ContactForm-inputWrapper {
  width: 100%;

  &--name {
    grid-area: name;
  }

  &--email {
    grid-area: email;
  }

  &--subject {
    grid-area: subject;
  }

  &--message {
    grid-area: message;
  }
}

.ContactForm-inputText,
.ContactForm-textarea {
  display: block;
  width: 100%;
  // height: 100%;
  // font: $fontSize-default $fontStack-sans;
  // font: $fontSize-default var(--fontStack-sans);
  padding: 10px 12px;
  border: 1px solid transparent;
  margin: 0;

  &--error {
    // background-color: rgba(red, 0.1);
    border-color: red;
  }
}

.ContactForm-textarea {
  min-height: 120px;
  resize: vertical;
  width: 100%;
  max-width: 100%;
  min-width: 100%;
}

.ContactForm-error {
  padding: 8px 12px;
  color: red;
  text-transform: uppercase;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.25px;

  .NoJs & { display: none; }
}

.ContactForm-messageAndSend {
  grid-area: control;
  min-width: 0;
  display: flex;
  justify-content: flex-end;

  @include media("<=550px") {
    flex-direction: column;
  }
}

.ContactForm-button {
  // font: var(--fontSize-default) var(--fontStack-defualt);
  border: none;
  background-color: #fff;
  padding-left: 40px;
  padding-right: 40px;
  justify-self: end;
  height: 46px;
  flex: none;

  @include media("<=550px") {
    width: 100%;
    margin-bottom: 10px;
  }
}

.ContactForm-message {
  align-self: center;
  justify-self: end;
  padding-right: 20px;
  padding-left: 10px;

  &--error {
    color: red;
  }

  @include media("<=550px") {
    width: 100%;
    flex: none;
    order: 1;
  }

  .NoJs & { display: none; }
}

</style>
