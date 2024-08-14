<template>
<div
  class="border-t-2"
  v-show="title == selectedTitle">
  <slot />
</div>
</template>

<script>
import { inject } from 'vue'

export default {
 props: ['title'],
  components: {
  },
  setup(props) {
    const selectedTitle = inject("selectedTitle")
    
    return {
      selectedTitle
    }
  }
}
</script>
