import config from './config'
import axios from 'axios'

console.log('load svg sprite from: ' + config.publicPath + 'sprite.svg')
axios.get(config.publicPath + 'sprite.svg').then((response) => {
  console.log('sprite loaded')
  let div = document.createElement('div')
  div.style.position = 'absolute'
  div.style.width = 0
  div.style.height = 0
  div.style.overflow = 'hidden'

  // eslint-disable-next-line
  div.innerHTML = response.data
  document.body.insertBefore(div, document.body.childNodes[0])
})
