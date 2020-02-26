import '@/node_modules/reset-css/reset.css'
import '@/scss/reset.scss'
import '@/scss/default.scss'
import '@/scss/defaultText.scss'
import '@/scss/layoutDefault.scss'

import '@/node_modules/photoswipe/dist/photoswipe.css'
import '@/modules/Photoswipe/default-skin/default-skin.css'

// Import all .scss files of /modules
function importAll(r) {
  r.keys().map((item, index) => {
    r(item)
  })
}

importAll(require.context('@/modules/', true, /\.(scss)$/))
