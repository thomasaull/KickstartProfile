// var svgoplugins = require('../.svgoplugins')

function importAll(r) {
  let icons = {}
  r.keys().map((item, index) => {
    r(item)
  })

  return icons
}

// // // Docs: https://webpack.github.io/docs/context.html
importAll(require.context('./../icons/svgs', false, /\.(svg)$/))
// require('./../img/icons/iconcheckyo.svg')

// require('./../img/icons/' + name + '.svg')
// require('./../img/icons/check.svg')

// function importAll(r) {
//   let icons = {}
//   r.keys().map((item, index) => {
//     // r(item)
//     // console.log(item)
//     r('!file-loader!svgo-loader!' + item)
//   })

//   return icons
// }

// export default {
//   data() {
//     return {
//       // icons: importAll(require.context('file-loader!./../img/icons/', false, /\.(svg)$/))
//       icons: importAll(require.context('./../img/icons/', false, /\.(svg)$/))
//     }
//   }
// }
