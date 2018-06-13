import PhotoSwipe from 'photoswipe' // eslint-disable-line
import _ from 'lodash'
import Vue from 'vue'
import Gallery from './Gallery.vue'

const els = document.querySelectorAll('.Gallery')

_.each(els, el => {
  new Vue(Gallery).$mount(el) // eslint-disable-line
})
