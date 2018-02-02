<template>

<div class="map"></div>

</template>

<script>

import GoogleMapsLoader from 'google-maps'
import config from '@/js/config'
import MapStyles from './MapStyles'

const mapOptions = {
  zoom: 12,
  // scaleControl: true,
  // mapTypeControl: true,
  // scrollwheel: false,
  fullscreenControl: true,
  // streetViewControl: false,
  // zoomControl: false,
  // disableDoubleClickZoom: false,
  zoomControl: true,
  disableDefaultUI: true,
  draggable: true,
  styles: MapStyles
}

// https://developers.google.com/maps/documentation/javascript/symbols
const markerIcon = {
  // path: 'M-20,0a20,20 0 1,0 40,0a20,20 0 1,0 -40,0',
  path: 'M11.5,0 C8.172,0 5.46428571,2.673 5.46428571,5.95833333 C5.46428571,9.05575 10.8453571,21.2098333 11.0747143,21.7259167 C11.149,21.89275 11.3161429,22 11.5,22 C11.6838571,22 11.851,21.8918333 11.9252857,21.7259167 C12.1546429,21.2098333 17.5357143,9.05666667 17.5357143,5.95833333 C17.5357143,2.673 14.828,0 11.5,0 Z M11.5,8.25 C10.2176429,8.25 9.17857143,7.22425 9.17857143,5.95833333 C9.17857143,4.69241667 10.2176429,3.66666667 11.5,3.66666667 C12.7823571,3.66666667 13.8214286,4.69241667 13.8214286,5.95833333 C13.8214286,7.22425 12.7823571,8.25 11.5,8.25 Z',
  fillColor: '#FF5000',
  fillOpacity: 1,
  strokeWeight: 0,
  scale: 2.5
}

export default {
  el: document.querySelector('.map'),
  name: 'Map',

  created () {
    GoogleMapsLoader.KEY = config.map.googleApiKey
    GoogleMapsLoader.LANGUAGE = 'de'
  },

  mounted () {
    GoogleMapsLoader.load(google => {
      const position = new google.maps.LatLng(config.map.latitude, config.map.longitude)
      const map = new google.maps.Map(this.$el, {...mapOptions, center: position})

      const marker = new google.maps.Marker({ // eslint-disable-line
        position: new google.maps.LatLng(Number(config.map.latitude), Number(config.map.longitude)),
        map: map,
        icon: { ...markerIcon, anchor: new google.maps.Point(22 / 2, 22) }
      })

      // google.maps.event.addListener(marker, 'click', function () {
      //   console.log('click auf marker')
      // window.open('http:')
      // })

      // Resize Function
      google.maps.event.addDomListener(window, 'resize', function() {
        var center = map.getCenter()
        google.maps.event.trigger(map, 'resize')
        map.setCenter(center)
      })
    })
  }
}
</script>

<style lang="scss">

.map {
  width: 100%;
  height: 100%;
}

</style>
