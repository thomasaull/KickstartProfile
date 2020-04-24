import debounce from 'lodash/debounce'

export default {
  created() {
    this.debouncedLayout = debounce(() => {
      this.layout()
    }, 100)

    window.addEventListener('resize', this.debouncedLayout)

    // Trigger layout when webfonts were loaded
    document.fonts.ready.then(this.layout)
  },

  mounted() {
    this.layout()
  },

  beforeDestroy() {
    window.removeEventListener('resize', this.debouncedLayout)
  },

  methods: {
    layout() {
      console.warn(`layout() not implemented for ${this.$options.name}`)
    },
  },
}
