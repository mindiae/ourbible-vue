<template>
  <a href="/commentaries">commentaries</a>
  <books-browser :modules_obj="modules_object" default_module="OGB">
  </books-browser>
</template>

<script>
import BooksBrowser from "../components/BooksBrowser.vue";
import ModulesObj from "../data/bible/data.json";

export default {
  metaInfo: {
    title: "Bible",
  },
  components: {
    BooksBrowser,
  },
  data() {
    return {
      modules_object: ModulesObj,
    };
  },
};
</script>
