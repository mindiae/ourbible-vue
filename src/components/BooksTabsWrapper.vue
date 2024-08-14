<template>
<div class="m-0 m-auto">
  <div class="p-0 flex flex-wrap m-0">
    <span
      class="mt-1 w-50 text-center px-5 py-2.5
             bg-slate-400 rounded-t-sm mr-2
             cursor-pointer mb-0"
      :class="{'bg-cyan-700 text-white': title == selectedTitle}"
      v-for="title in tabTitles"
      :key="title"
      @click="selectedTitle = title"
      >
      {{ title }}
    </span>
  </div>
  <slot />
</div>
</template>

<script>
import { ref, inject } from 'vue'


export default {
  setup(props, { slots }) {
    const tabTitles = ref(slots.default().map((tab) => tab.props.title))
    const selectedTitle = inject("selectedTitle")
    return {
      selectedTitle,
      tabTitles
    }
  }
}
</script>
