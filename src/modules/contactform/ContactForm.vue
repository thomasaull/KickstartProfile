<script>

export default {
  el: document.querySelector('.contactForm'),
  name: 'ContactForm',

  data () {
    return {
      test: 'jo joj ojo!'
    }
  },

  computed: {
    classes () {
      return {
        '-error': this.errors.has('email')
      }
    }
  }
}
</script>

<style lang="scss">

.contactForm {
  color: red;
}

</style>
