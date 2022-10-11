<template>
<div>
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span  v-if="books && books.books[bookNumber]">
          {{books.books[bookNumber].long_name}} {{chapterNumber}}
        </span>
      </h2>
    </div>
  </header>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div>
          <div class="bg-gray-200 bg-opacity-25 border-b border-gray-200">
	    <div class="px-6 py-4">
              
              <!-- previous chapter -->
              <button v-if="pickedVerses[0] && pickedVerses[0].meta.previous_chapter"
                      @click="goToPreviousChapter()"
                      :class="buttonClass">
                <heroicons-svg name="chevron-double-left"></heroicons-svg>
              </button>
              
              <!-- show books toggle -->
              <button @click="showingBooksDropdown = ! showingBooksDropdown"
                                        :class="buttonClass">
                <span v-if="books && books.books[bookNumber]">
                  {{ books.books[bookNumber].short_name }}
                </span>
                <span v-else>
                  {{ '==' }}
                </span>
                <heroicons-svg class="ml-2" name="chevron-down">
                </heroicons-svg>
              </button>
              
              <!-- show chapters toggle -->
              <button
                @click="showingChaptersDropdown = ! showingChaptersDropdown"
                :class="buttonClass">
                -- {{ chapterNumber }} --
                <heroicons-svg class="ml-2" name="chevron-down">
                </heroicons-svg>
              </button>
              
              <!-- next chapter -->
              <button
                v-if="pickedVerses[0] && pickedVerses[0].meta.next_chapter"
                @click="goToNextChapter()"
                :class="buttonClass">
                <heroicons-svg name="chevron-double-right"></heroicons-svg>
              </button>
              
              
              
              <!-- books -->
              <div :class="{'block': showingBooksDropdown, 'hidden': ! showingBooksDropdown}">
                <span v-if="books"
                      v-for="book in books.books">
                  
                  <br v-if="book.book_number == 470" />
                  <button
                    @click="bookNumber = book.book_number;setUrlParams()"
                    class="border rounded px-2 disabled:font-bold"
                    :style="{'background-color': book.book_color}"
                    :key="book.book_number"
                    :disabled="book.book_number == bookNumber">
                    {{ book.short_name }}
                  </button>
                  
                </span>
              </div>
              
              <!-- chapters per book -->
              <div v-if="books && books.maxChapters[bookNumber]"
                   :class="{'block': showingChaptersDropdown, 'hidden': ! showingChaptersDropdown}">
                <button
                  @click="chapterNumber = chapter_number ;setUrlParams()"
                  class="bg-gray-600
                         disabled:bg-gray-900
                         text-white border
                         rounded px-2"
                  v-for="chapter_number in books.maxChapters[bookNumber].max_chapter"
                  :key="chapter_number"
                  :disabled="chapter_number == chapterNumber">
                  {{ chapter_number }}
                </button>
              </div>
              
              <!-- menu language -->
              <div>
                <button class="border rounded px-2"
                        v-for="key in pickedModules"
                        :key="key">
                  <label>
                    {{ key }}
                    <input
                      type="radio"
                      :value="key"
                      v-model="uiModule">
                  </label>
                </button>
              </div>
              
              <!-- from 1 up to 3 modules chooser -->
              <div>
                <button class="rounded border px-2"
                        v-for="(val, module) in modules"
                        :key="module">
                  <label>
                    {{ module }}
                    <input
                      type="checkbox"
                      v-model="pickedModules"
                      :value="module"
                      :disabled="pickedModules.length > 2 && pickedModules.indexOf(module) === -1"
                    />
                  </label>
                </button>
              </div>
              
              <!-- actual content -->
              <h1 v-if="books && books.books[bookNumber]">
                {{books.books[bookNumber].long_name}}
                {{chapterNumber}}
              </h1>
              <div
                id="content-top"
                :class="{'lg:[column-count:2] xl:[column-count:3] 2xl:[column-count:4]': pickedModules.length == 1}">
                <template v-for="verse in pickedVerses[0].chapter">
                  <template v-if="pickedModules.length == 1">
	            
		    <sup>{{ verse.verse }}</sup>
		    <verse-text
                      v-if="pickedModules[0].includes('+')"
                      :data="verse.text">
                    </verse-text>
		    <span v-else>{{ verse.text }}</span> {{ ' ' }}
	          </template>
                  
                  
                  <div v-else class="flex" >
                    <div class="flex py-2">{{ verse.verse }}</div>
                    <div class="flex-1 p-2"
                         v-for="moduleNumber in pickedModules.length"
                         :key="moduleNumber">
                      <template v-if="pickedVerses[moduleNumber - 1] && pickedVerses[moduleNumber - 1].chapter[verse.verse]">
                        
                        <verse-text
                          v-if="pickedModules[moduleNumber - 1].includes('+')"
                          :data="pickedVerses[moduleNumber - 1].chapter[verse.verse].text">
                        </verse-text>
                        
		        <span v-else>
                          {{ pickedVerses[moduleNumber - 1].chapter[verse.verse].text }}
                        </span>
                        {{ ' ' }}
                      </template>
                    </div>
                  </div>
                </template>
              </div>
              
              <!-- previous chapter -->
              <a href="#content-top">
                <button
                  v-if="pickedVerses[0] && pickedVerses[0].meta.previous_chapter"
                  @click="goToPreviousChapter()"
                  :class="buttonClass">
                  <heroicons-svg name="chevron-double-left"></heroicons-svg>
                </button>
              </a>
              
              <!-- next chapter -->
              <a href="#content-top">
                <button
                  v-if="pickedVerses[0] && pickedVerses[0].meta.next_chapter"
                  @click="goToNextChapter()"
                  :class="buttonClass">
                  <heroicons-svg name="chevron-double-right"></heroicons-svg>
                </button>
              </a>
	    </div>
          </div>
        </div>
        
      </div>
    </div>
  </div> 
</div>
</template>

<script>
import axios from 'axios'
import VerseText from '~/components/VerseText.vue'
import HeroiconsSvg from '~/components/HeroiconsSvg.vue'

export default {
  components: {
    VerseText,
    HeroiconsSvg,
  },
  data() {
    return {
      buttonClass: "inline-flex bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow",
      pickedModules: [this.default_module],
      pickedBooks: [false,false,false],
      pickedVerses: [false,false,false],
      uiModule: this.default_module,
      modules: this.modules_obj,
      bookNumber: '10',
      chapterNumber: '1',
      showingBooksDropdown: false,
      showingChaptersDropdown: false,
      currentUrl: process.isClient ? location.origin : "http://localhost:8080"
    }
  },
  props: {
    modules_obj: Object,
    default_module: String
  },
  watch: {
    chapterNumber(newValue, oldValue) {
      for (var i = 0; i< this.pickedModules.length; i++) {
        this.setVerses(i)
      }
    },
    bookNumber(newValue, oldValue) {
      if (newValue != oldValue) {
        this.chapterNumber = 1
        for (var i = 0; i< this.pickedModules.length; i++) {
          this.setVerses(i)
        }
      }
    },
    'pickedModules.length':{
      handler(newValue){
        if (!newValue){
          this.pickedModules.push(this.uiModule)
        }
        if (!this.pickedModules.includes(this.uiModule)){
          this.uiModule = this.pickedModules[0]
        }
      },
      immediate: true
    },
    pickedModules: {
      handler(newValue){
        for (var i = 0; i< this.pickedModules.length; i++) {
          this.setBooks(i)
          this.setVerses(i)
        }
      },
      immediate: true,
      deep: true
    }
  },
  methods: {
    setBooks(i){
      axios
        .get(`${this.currentUrl}/${this.pickedModules[i]}/books.json`)
        .then((response)=> this.$set(this.pickedBooks, i, response.data))
        .catch((error)=> console.log(error))
    },
    setVerses(i) {
      axios
        .get(`${this.currentUrl}/${this.pickedModules[i]}/${this.bookNumber}/${this.chapterNumber}.json`)
        .then((response)=>this.$set(this.pickedVerses, i, response.data))
        .catch((error) => console.log(error))
    },
    goToPreviousChapter() {
      const previousChapterBook
            = this.pickedVerses[0].meta.previous_chapter.book_number
      const previousChapter
            = this.pickedVerses[0].meta.previous_chapter.chapter
      this.chapterNumber = 1
      this.bookNumber = previousChapterBook
      this.$nextTick(() => {
        this.chapterNumber = previousChapter
      })
      this.setUrlParams()
    },
    goToNextChapter() {
      const nextChapterBook
            = this.pickedVerses[0].meta.next_chapter.book_number
      const nextChapter
            = this.pickedVerses[0].meta.next_chapter.chapter
      this.chapterNumber = 1
      this.bookNumber = nextChapterBook
      this.chapterNumber = nextChapter
      this.setUrlParams()
    },
    setUrlParams() {
      const params = new URLSearchParams(location.search)
      params.set('b', this.bookNumber)
      params.set('c', this.chapterNumber)
      params.set('t', this.uiModule)
      params.set('a1', this.pickedModules[0])
      if (this.pickedModules[1]) {
        params.set('a2', this.pickedModules[1])
      }
      if (this.pickedModules[2]) {
        params.set('a3', this.pickedModules[2])
      }
      if (process.isClient) {
        window.history.replaceState({path: `${location.pathname}?${params.toString()}${location.hash}`}, '', `${location.pathname}?${params.toString()}${location.hash}`)
      }
    }
  },
  computed: {
    books() {
      return this.pickedBooks[this.pickedModules.indexOf(this.uiModule)]
    }
  },
  mounted() {
    const urlParams = new URLSearchParams(location.search);
      
    for (const [key, value] of urlParams) {
      if (key == "b") {
        this.bookNumber = value
      } else if (key == "c" && Number.isInteger(Number(value))
                 && value >= 1) {
        this.$nextTick(() => {
          this.chapterNumber = value
        })
      } else if (key == "t" && this.modules[value]) {
        this.uiModule = value
      } else if (key == "a1" && this.modules[value]) {
        this.$set(this.pickedModules, 0, value)
      } else if (key == "a2" && this.modules[value]) {
        this.$set(this.pickedModules, 1, value)
      } else if (key == "a3" && this.modules[value]) {
        this.$set(this.pickedModules, 2, value)
      } 
    }
  }
}
</script>
