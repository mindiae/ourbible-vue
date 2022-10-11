<template>
<Layout :breadcrumbs="breadcrumbs">
  <books :modules_obj="abbrs_object" default_module="OGB"></books>
</Layout>
</template>

<script>
import Books from '~/components/Books.vue'
import AbbrsObject from '~/data/bible/data.json'

export default {
  metaInfo: {
    title: 'Bible'
  },
  components: {
    Books
  },
  data() {
    return {
      abbrs_object: AbbrsObject,
      breadcrumbs: [
        {
          name: 'Evprosdektos',
          path: '/'
        },
        {
          name: 'Bible Index',
          path: '/bible-index/'
        },
        {
          name: 'Bible',
          path: '/bible/'
        }
      ],
    }
  }
}
</script>

