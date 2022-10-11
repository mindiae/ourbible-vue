<template>
<span>
  <template v-for="(word, key) in data">
    <span v-if="word.name == 'word'">
      <span v-if="key != 0">
        {{ ' ' }}
      </span>
      <a :href="`https://biblehub.com/greek/${word.S}.htm`" target="_blank">{{ word.data }}</a>
    </span>
    <span v-if="word.name == 'punctuation'">{{ word.data }}</span>
  </template>
</span>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      default: [{name:'word', data:'this verse is empty'}],
    }
  }
}
</script>
