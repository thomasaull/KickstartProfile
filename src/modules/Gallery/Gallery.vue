<script>

import PhotoSwipe from 'photoswipe' // eslint-disable-line
import PhotoSwipeUI_Default from 'photoswipe/dist/photoswipe-ui-default' // eslint-disable-line
import _ from 'lodash'

export default {
  name: 'Gallery',

  computed: {
    images () {
      let images = []

      _.each(this.$refs.images.children, el => {
        const imgEl = el.querySelector('img')
        const image = {
          src: el.getAttribute('href'),
          w: el.getAttribute('width'),
          h: el.getAttribute('height'),
          msrc: imgEl.currentSrc || imgEl.src,
          title: imgEl.getAttribute('alt')
        }

        images.push(image)
      })

      return images
    }
  },

  methods: {
    openGallery (index) {
      let photoswipeEl = document.querySelector('.pswp')

      let options = {
        index: index,
        getThumbBoundsFn: this.getThumbBounds,
        showHideOpacity: true,
        fullscreenEl: false,
        shareEl: false
      }

      // let images = [
      //   {
      //     src: 'https://placekitten.com/600/400',
      //     w: 600,
      //     h: 400,
      //     title: 'A cat'
      //     // msrc: 'path/to/small-image.jpg', // small image placeholder,
      //   },
      //   {
      //     src: 'https://placekitten.com/1200/900',
      //     w: 1200,
      //     h: 900
      //   }
      // ]

      this.photoSwipe = new PhotoSwipe(photoswipeEl, PhotoSwipeUI_Default, this.images, options)
      this.photoSwipe.init()
    },

    getThumbBounds (index) {
      const el = this.$refs.images.children[index].querySelector('img')
      const rect = el.getBoundingClientRect()
      const pageYScroll = window.pageYOffset || document.documentElement.scrollTop

      return {
        x: rect.x,
        y: rect.y + pageYScroll,
        w: rect.width,
        h: rect.height
      }
    }
  }
}
</script>
