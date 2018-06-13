// https://github.com/typekit/webfontloader
/* import WebFont from 'webfontloader'

// Session-Storage Flag from: https://www.bramstein.com/writing/web-font-loading-patterns.html
console.log(sessionStorage.fontsLoaded)

if (sessionStorage.fontsLoaded) {
  var html = document.documentElement
  html.classList.add('wf-active')
}

WebFont.load({
  google: {
    families: ['Open Sans']
  },

  active: () => {
    sessionStorage.fontsLoaded = true
  }
}) */

import FontFaceObserver from 'fontfaceobserver'

let font = new FontFaceObserver('Kreon')

font.load().then(() => {
  document.querySelector('body').classList.add('-fontsLoaded')
  setCookie('fontsLoaded', true, 30)
})

function setCookie (name, value, days) { // eslint-disable-line
  var d = new Date()
  d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days)
  document.cookie = name + '=' + value + ';path=/;expires=' + d.toGMTString()
}
