import _ from 'lodash'
import ContactForm from './ContactForm.vue'
import Vue from 'vue'

const els = document.querySelectorAll('.ContactForm')

_.each(els, (el) => {
  new Vue(ContactForm).$mount(el) // eslint-disable-line
})
