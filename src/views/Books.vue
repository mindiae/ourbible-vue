<template>
<tabs-wrapper>
  <tab :title="tabTitles[0]">
    <div class="text-center">
      <ul class="flex flex-wrap justify-center list-none">
        <li
          class="px-2 py-2"
          :class="{'text-green-700 cursor-pointer': available, 'text-gray-400 cursor-default': !available}"
          v-for="(available, letter) in georgianAlphabet"
          :key="letter"
          @click="selectLetter(available, letter)">
          {{ letter }}
        </li>
      </ul>
      <button
        v-if="chosenLetter"
        @click="chosenLetter = false"
        class="bg-green-700 text-white px-2 rounded-md">
        ყველას გამოჩენა
      </button>
    </div>
    <ul
      class="list-disc list-inside"
      v-if="chosenLetter">
      <div class="text-center my-3 text-red-600">{{chosenLetter}}</div>
      <li
        class="cursor-pointer"
        v-for="(author, key) in authors[chosenLetter]"
        :key="key"
        @click="goToAuthor(key)">
        {{author.name}}
        <span v-if="author.title">
          ({{author.title}})
        </span>
        <span class="text-gray-400">
          ({{author.birth}}<span v-if="author.death">-{{author.death}}</span>)
        </span>
      </li>
    </ul>
    <div v-else>
      <div
        v-for="(available, letter) in georgianAlphabet">
        <ul
          class="list-disc list-inside">
          <div class="text-center my-3 text-red-600">{{letter}}</div>
          <li
            class="cursor-pointer"
            v-for="(author, key) in authors[letter]"
            :key="key"
            @click="goToAuthor(key)">
            {{author.name}}
            <span v-if="author.title">
              ({{author.title}})
            </span>
            <span class="text-gray-400">
              ({{author.birth}}<span v-if="author.death">-{{author.death}}</span>)
            </span>
          </li>
        </ul>
      </div>
    </div>
  </tab>
  <tab :title="tabTitles[1]">
    <div v-if="Object.keys(books).length === 0">
      გთხოვთ, აირჩიოთ ავტორი
    </div>
    <ul
      v-else
      class="cursor-pointer list-disc list-inside">
      <li
        v-for="(bookData, key) in books"
        :key="key"
        @click="goToBook(key)">
        {{bookData.title}}
      </li>
    </ul>
  </tab>
  <tab :title="tabTitles[2]">
    <div v-if="Object.keys(tableOfContent).length === 0">
      გთხოვთ, აირჩიოთ წიგნი
    </div>
    <ul
      v-else
      class="cursor-pointer list-disc list-inside">
      <li
        v-for="(content, contentKey) in tableOfContent"
        :key="contentKey">
        <span @click="goToPage(content.page)">
          {{content.title}}
        </span>
        <ul
          v-if="content.children"
          class="cursor-pointer list-disc list-inside">
          <li
            v-for="(child1, child1Key) in content.children"
            :key="child1Key">
            <span
              @click="goToPage(child1.page)">
              {{child1.title}}
            </span>
            <ul
              v-if="child1.children"
              class="cursor-pointer list-disc list-inside">
              <li
                v-for="(child2, child2Key) in child1.children"
                :key="child2Key">
                <span @click="goToPage(child2.page)">
                  {{child2.title}}
                </span>
                <ul
                  v-if="child2.children"
                  class="cursor-pointer list-disc list-inside">
                  <li
                    v-for="(child3, child3Key) in child2.children"
                    :key="child3Key">
                    <span @click="goToPage(child3.page)">
                      {{child3.title}}
                    </span>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </tab>
  <tab :title="tabTitles[3]">
    <div v-if="!page">
      გთხოვთ, აირჩიოთ გვერდი
    </div>
    <div v-else>
      <button
        class="text-white bg-slate-500 mx-0.5 my-3 px-5 rounded-l-md disabled:opacity-50"
        :disabled="page == 1"
        @click="goToPage(page - 1)">
        &larr;
        წინა გვერდი
      </button>
      <button
        class="text-white bg-slate-500 mx-1 my-3 px-5 rounded-r-md disabled:opacity-50"
        :disabled="!pages[page + 1]"
        @click="goToPage(page + 1)">
        მომდევნო გვერდი
        &rarr;
      </button>
      <p
        :style="`background-color: ${backgroundColor}; color: ${textColor}`"
        v-if="pageContent && books[book].type === 'wp-rest'"
        v-html="pageContent">
      </p>
      <div v-else-if="books[book].type === 'pdf'">
        <vue-pdf-embed :source="pdfSource" :page="page" />
      </div>
      <button
        class="text-white bg-slate-500 mx-0.5 my-3 px-5 rounded-l-md disabled:opacity-50"
        :disabled="page == 1"
        @click="goToPage(page - 1)">
        &larr;
        წინა გვერდი
      </button>
      <button
        class="text-white bg-slate-500 mx-1 my-3 px-5 rounded-r-md disabled:opacity-50"
        :disabled="!pages[page + 1]"
        @click="goToPage(page + 1)">
        მომდევნო გვერდი
        &rarr;
      </button>
    </div>
    
  </tab>
  <tab :title="tabTitles[4]">
    <div class="lg:p-8 lg:m-5 rounded-md border shadow w-auto">
      <label for="bgcolorpicker">ფონის ფერი:</label>
      <input
        type="color"
        id="bgcolorpicker"
        v-model="backgroundColor" />
      <!--<label for="fgcolorpicker">ტექსტის ფერი:</label>
      <input type="color" id="fgcolorpicker"  v-model="textColor" />-->
    </div>
  </tab>
</tabs-wrapper>
</template>

<script>
import axios from 'axios'
import VuePdfEmbed from 'vue-pdf-embed'
import VueCookies from 'vue-cookies'
import TabsWrapper from '../components/BooksTabsWrapper.vue'
import Tab from '../components/BooksTab.vue'
import alphabet from '../data/books/alphabet.json'
import { ref, reactive, provide, inject, computed, watch } from 'vue'

VueCookies.config('365d')

// set secure, only https works
//VueCookies.config('365d', '', '', true)

export default {
  components: {
    VuePdfEmbed,
    TabsWrapper,
    Tab
  },
  setup(props) {
    const georgianAlphabet = reactive(alphabet)
    const chosenLetter = ref("ე")
    const authors = reactive({})
    const author = ref(false)
    const tabTitles = ref(["ავტორები", "წიგნები", "შინაარსი", "წამკითხველი", "იერსახე"])
    const selectedTitle = ref(tabTitles.value[0])
    const books = reactive({})
    const fetchUrl = 'https://evprosdektos.github.io'
    const restUrl = 'https://evqaristia.ge/wp-json/wp/v2/posts'
    const book = ref(false)
    const tableOfContent = reactive({})
    const pages = reactive({})
    const page = ref(false)
    const pageContent =ref(false)
    const backgroundColor = ref("#ffffff")
    const textColor = ref("#000000")
    const pdfSource = ref(false)
    
    axios
      .get(`${fetchUrl}/books/authors.json`)
      .then((response) => Object.assign(authors, response.data))
      .catch((err) =>true)
    
    watch(backgroundColor, (newValue)=>{
      VueCookies.set("backgroundColor", backgroundColor.value)
    })

    watch(textColor, (newValue)=>{
      VueCookies.set("textColor", textColor.value)
    })
    
    function selectLetter(available, letter) {
      if (available) {
        chosenLetter.value = letter
      }
    }

    function goToAuthor(authorKey) {
      author.value = authorKey
      selectedTitle.value = tabTitles.value[1]
      axios
        .get(`${fetchUrl}/books/${authorKey}/books.json`)
        .then((response) => Object.assign(books, response.data))
        .catch((err) =>true)
      VueCookies.set("author", authorKey)
    }

    function goToBook(bookKey) {
      book.value = bookKey
      selectedTitle.value = tabTitles.value[2]
      axios
        .get(`${fetchUrl}/books/${author.value}/${bookKey}.json`)
        .then((response) => {
          if (books[book.value].type === 'pdf') {
            Object.assign(tableOfContent, response.data)
            pdfSource.value
              = `${fetchUrl}/books/${author.value}/${book.value}.pdf`
          } else if (books[book.value].type === 'wp-rest') {
            Object.assign(tableOfContent, response.data.content)
            Object.assign(pages, response.data.pages)
          }
          if (VueCookies.isKey(`${book.value}.page`)) goToPage(Number(VueCookies.get(`${book.value}.page`)))
        })
        .catch((err) =>true)
      VueCookies.set("book", bookKey)
    }

    function goToPage(pageNumber) {
      page.value = pageNumber
      selectedTitle.value = tabTitles.value[3]

      if (books[book.value].type === 'wp-rest') {
        axios
        //.get(`${restUrl}?slug=${pages[pageNumber].slug}`)
        
          .get(`${restUrl}?include=${pages[pageNumber].id}`)
          .then((response) => {
            pageContent.value = response.data[0].content.rendered
          })
          .catch((err) =>true)
      } else {
        
      }
      VueCookies.set(`${book.value}.page`, pageNumber)
    }

    
    if (VueCookies.isKey("author"))  goToAuthor(VueCookies.get("author"))
    if (VueCookies.isKey("book") && author.value)
      goToBook(VueCookies.get("book"))
    if (VueCookies.isKey("backgroundColor"))
      backgroundColor.value = VueCookies.get("backgroundColor")
    if (VueCookies.isKey("textColor"))
      textColor.value = VueCookies.get("textColor")
    
    
    provide("selectedTitle", selectedTitle)
    
    return {
      pdfSource,
      backgroundColor,
      textColor,
      pageContent,
      page,
      goToPage,
      pages,
      tableOfContent,
      selectedTitle,
      tabTitles,
      georgianAlphabet,
      chosenLetter,
      selectLetter,
      goToAuthor,
      goToBook,
      author,
      books,
      book,
      authors
    }
  }
}
</script>

