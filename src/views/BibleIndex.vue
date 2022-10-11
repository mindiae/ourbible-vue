<template>
<Layout :breadcrumbs="breadcrumbs">
  <template #header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Bible Index
    </h2>
  </template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="bg-gray-200 bg-opacity-25 border-b border-gray-200">
	  <div class="px-6 py-4">
            <!-- menu language -->
            <div>
              <button class="border rounded px-2"
                      v-for="(value, module) in modules"
                      :key="module">
                <label>
                  {{ module }}
                  <input
                    type="radio"
                    :value="module"
                    v-model="uiModule">
                </label>
              </button>
            </div>
            
            <!-- links -->
            <div>
              <div v-for="book in books.books"
                   :key="book.book_number" >
                <g-link class="text-blue-500 hover:underline"
                        :to="`/bible-index/bible/?b=${book.book_number}&a1=${uiModule}`">
                  {{book.long_name}}
                </g-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</Layout>
</template>

<script>
import ModulesObject from '~/data/bible/data.json'
import axios from 'axios'

export default {
  metaInfo: {
    title: 'Bible Index'
  },
  components: {
  },
  data() {
    return {
      books: false,
      currentUrl: process.isClient ? location.origin : "http://localhost:8080",
      uiModule: "OGB",
      modules: ModulesObject,
      breadcrumbs: [
        {
          name: 'Evprosdektos',
          path: '/'
        },
        {
          name: 'Bible Index',
          path: '/bible-index/'
        }
      ],
    }
  },
  watch: {
    uiModule:{
      handler(){
        this.setBooks()
      },
      immediate:true
    }
  },
  methods: {
    setBooks(){
      axios
        .get(`${this.currentUrl}/${this.uiModule}/books.json`)
        .then((response) => this.books = response.data)
        .catch((error) => console.log(error))
    }
  }
}
</script>
