<template>
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :stroke-width="strokeWidth" stroke="currentColor" :width="width" :height="height">
  <path stroke-linecap="round" stroke-linejoin="round" :d="path" />
</svg>
</template>

<script>
import paths from '../data/heroicons.js'

export default {
  props: {
    strokeWidth: {
      type: String,
      default: '2.5'
    },
    width: {
      type: String,
      default: '1em'
    },
    height: {
      type: String,
      default: '1em'
    },
    name: {
      type: String,
      default: 'chevron-down'
    }
  },
  computed: {
    path() {
      return paths[this.name]
    }
  }
}
</script>
