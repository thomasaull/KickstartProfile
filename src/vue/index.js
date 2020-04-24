import Vue from 'vue'

import EventHub from '@/vue/EventHub'

// Import Vue Components

Vue.prototype.$eventHub = EventHub

new Vue({
  components: {},
}).$mount('#vue')
