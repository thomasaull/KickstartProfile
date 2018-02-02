// https://github.com/typekit/webfontloader
import WebFont from 'webfontloader'

// Session-Storage Flag from: https://www.bramstein.com/writing/web-font-loading-patterns.html
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
})
