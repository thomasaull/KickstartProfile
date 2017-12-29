// https://github.com/processwire-recipes/Recipes/blob/master/inline-critical-css.md

const path = require('path')
// const argv = require('yargs').argv
const critical = require('critical')
const chalk = require('chalk')
const pkg = require('../package.json')

// // Process data in an array synchronously, moving onto the n+1 item only after the nth item callback
const doSynchronousLoop = (data, processData, done) => {
  if (data.length > 0) {
    const loop = (data, i, processData, done) => {
      processData(data[i], i, () => {
        if (++i < data.length) {
          loop(data, i, processData, done)
        } else {
          done()
        }
      })
    }
    loop(data, 0, processData, done)
  } else {
    done()
  }
}

const createCriticalCSS = (element, i, callback) => {
  // const url = argv.url || pkg.urls.critical
  const criticalSrc = pkg.urls.dev + element.url
  const criticalDest = path.resolve(__dirname, `../../site/templates/dist/critical/${element.template}_critical.min.css`)
  console.log(chalk`-> Generating critical CSS: {cyan ${criticalSrc}} -> {magenta ${criticalDest}}`)
  critical.generate({
    src: criticalSrc,
    dest: criticalDest,
    inline: false,
    ignore: ['font-face'],
    minify: true,
    width: 1300, // 1440,
    height: 900 // 1280
  }).then((output) => {
    console.log(chalk`-> Critical CSS generated: {green ${element.template}_critical.min.css}`)
    callback()
  }).error((err) => {
    console.log(chalk`-> Something went wrong {red ${err}}`)
  })
}

doSynchronousLoop(pkg.criticalCSS, createCriticalCSS, () => {
  console.log(chalk`{green Done!}`)
})
