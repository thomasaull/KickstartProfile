// import axios from 'axios'
// axios.defaults.baseURL = window.location.origin

// import TweenMax from 'gsap' // eslint-disable-line

import '@/js/reportErrors'
// import '@/js/loadSvgSprite' // uncomment if you want to use an svg sprite!
import '@/vue/index'

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
