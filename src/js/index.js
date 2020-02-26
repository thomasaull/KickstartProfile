import axios from 'axios'
import TweenMax from 'gsap' // eslint-disable-line
import Vue from 'vue'

import '@/js/reportErrors'
import '@/js/fonts'
// import '@/js/loadSvgSprite' // uncomment if you want to use an svg sprite!

axios.defaults.baseURL = window.location.origin

// Import and initialize all files of /modules
function importAll(r) {
  r.keys().map((item, index) => {
    r(item)
  })

  return r.keys().map(r)
}

// eslint-disable-next-line
const modules = importAll(require.context('@/modules/', true, /\.(js)$/))

// call init functions
// _.forEach(modules, module => {
//   module.default()
// })
