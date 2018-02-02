import '@/scss/reset.scss'
import '@/scss/default.scss'
import '@/scss/defaultText.scss'

// Import all .scss files of /modules
function importAll(r) {
  r.keys().map((item, index) => {
    r(item)
  })
}

importAll(require.context('@/modules/', true, /\.(scss)$/))
