<template>
  <commentaries :modules_obj="modules_object"
                :comments_obj="comment_modules_obj"
                default_module="OGB">
  </commentaries>
</template>

<script>
import Commentaries from '../components/Commentaries.vue'
import ModulesObj from '../data/bible/data.json'
import CommentModulesObj from '../data/commentaries/data.json'

export default {
  metaInfo: {
    title: 'Commentaries'
  },
  components: {
    Commentaries
  },
  data() {
    return {
      modules_object: ModulesObj,
      comment_modules_obj: CommentModulesObj
    }
  }
}
</script>

