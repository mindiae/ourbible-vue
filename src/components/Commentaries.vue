<template>
<div>
  <!--
  <h2 class="font-semibold text-xl text-gray-800">
    <span  v-if="books.books && books.books[bookNumber]">
      {{books.books[bookNumber].long_name}} {{chapterNumber}}
    </span>
  </h2>
  -->
  <div>
    
    <!-- show books toggle -->
    <button @click="showingBooksDropdown = ! showingBooksDropdown;showingChaptersDropdown = false ; showingVersesDropdown = false "
            :class="buttonClass"
            class="rounded-l">
      <span v-if="books.books && books.books[bookNumber]">
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
      @click="showingChaptersDropdown = ! showingChaptersDropdown; showingBooksDropdown = false; showingVersesDropdown = false"
      :class="buttonClass">
      -- {{ chapterNumber }} --
      <heroicons-svg class="ml-2" name="chevron-down">
      </heroicons-svg>
    </button>
    
    <!-- show verses toggle -->
    <button
      @click="showingVersesDropdown = ! showingVersesDropdown;showingBooksDropdown = false;showingChaptersDropdown = false "
      :class="buttonClass"
      class="rounded-r">
      {{ verseNumber }} 
      <heroicons-svg class="ml-2" name="chevron-down">
      </heroicons-svg>
    </button>
    
    {{' '}}
    
    <!-- previous chapter -->
    <button
      :disabled="!verses.meta || !verses.meta.previous_chapter"
      @click="goToPreviousChapter()"
      :class="buttonClass"
      class="rounded-l">
      <heroicons-svg name="chevron-double-left"></heroicons-svg>
    </button>
    
    <!-- previous verse -->
    <button
      :disabled="!verses.meta || (!verses.meta.previous_chapter && verseNumber == 1)"
      @click="goToPreviousVerse()"
      :class="buttonClass">
      <heroicons-svg name="chevron-left"></heroicons-svg>
    </button>
    
    <!-- next verse -->
    <button
      :disabled="!verses.meta || (!verses.meta.next_chapter && verseNumber == lastVerse)"
      @click="goToNextVerse()"
      :class="buttonClass">
      <heroicons-svg name="chevron-right"></heroicons-svg>
    </button>
    
    <!-- next chapter -->
    <button
      :disabled="!verses.meta || !verses.meta.next_chapter"
      @click="goToNextChapter()"
      :class="buttonClass"
      class="rounded-r">
      <heroicons-svg name="chevron-double-right"></heroicons-svg>
    </button>
    
    
    
    <!-- books -->
    <div :class="{'block': showingBooksDropdown, 'hidden': ! showingBooksDropdown}">
      <span v-if="books.books"
            v-for="book in books.books">
        
        <br v-if="book.book_number == 470" />
        <button
          @click="goToBook(book.book_number)"
          class="border rounded px-2 disabled:font-bold"
          :style="{'background-color': book.book_color}"
          :key="book.book_number"
          :disabled="book.book_number == bookNumber">
          {{ book.short_name }}
        </button>
        
      </span>
    </div>
    
    <!-- chapters per book -->
    <div v-if="books.books && books.maxChapters[bookNumber]"
         :class="{'block': showingChaptersDropdown, 'hidden': ! showingChaptersDropdown}">
      <button
        @click="goToChapter(chapter_number)"
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
    
    <!-- verses per chapter -->
    <div v-if="verses.chapter && verses.chapter[verseNumber]"
         :class="{'block': showingVersesDropdown, 'hidden': ! showingVersesDropdown}">
      <button
        @click="goToVerse(verse_number)"
        class="bg-gray-600
               disabled:bg-gray-900
               text-white border
               rounded px-2"
        v-for="(verse,verse_number) in verses.chapter"
        :key="verse_number"
        :disabled="verse_number == verseNumber">
        {{ verse_number }}
      </button>
    </div>
    
    <!-- menu language -->
    <div class="mt-2">
      <button class="border rounded px-2"
              v-for="(value, key) in modules"
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
    
    <!-- commentary modules chooser -->
    <div class="mb-2">
      <button class="rounded border px-2"
              v-for="(val, module) in comments"
              :key="module">
        <label>
          {{ module }}
          <input
            type="checkbox"
            v-model="pickedModules"
            :value="module"
            />
        </label>
      </button>
    </div>
    
    <!-- actual content -->
    <article>
      <h1
        v-if="books.books && books.books[bookNumber]"
        class="font-semibold">
        {{books.books[bookNumber].long_name}}
        {{chapterNumber}}
      </h1>
      <p
        v-if="verses.chapter && verses.chapter[verseNumber]"
        id="content-top"
        class="my-3">
        <sup>{{verses.chapter[verseNumber].verse}}</sup>
        <verse-text
          v-if="Array.isArray(verses.chapter[verseNumber].text)"
          :data="verses.chapter[verseNumber].text"></verse-text>
        <span v-else v-html="verses.chapter[verseNumber].text"></span>
      </p>
      <div
        v-for="(pickedComment, commentary) in pickedComments"
        :key="commentary">
        <h1 class="my-2 font-semibold">{{comments[commentary].description }}</h1>
        <p v-html="pickedComment"></p>
      </div>
    </article>
    
    <!-- previous chapter -->
    <a href="#content-top">
      <button
        :disabled="!verses.meta || !verses.meta.previous_chapter"
        @click="goToPreviousChapter()"
        :class="buttonClass"
        class="rounded-l">
        <heroicons-svg name="chevron-double-left"></heroicons-svg>
      </button>
    </a>
    
    <!-- previous verse -->
    <a href="#content-top">
      <button
        :disabled="!verses.meta ||  (!verses.meta.previous_chapter && verseNumber == 1)"
        @click="goToPreviousVerse()"
        :class="buttonClass">
        <heroicons-svg name="chevron-left"></heroicons-svg>
      </button>
    </a>
    
    <!-- next verse -->
    <a href="#content-top">
      <button
        :disabled="!verses.meta ||  (!verses.meta.next_chapter && verseNumber == lastVerse)"
        @click="goToNextVerse()"
        :class="buttonClass">
        <heroicons-svg name="chevron-right"></heroicons-svg>
      </button>
    </a>
    
    <!-- next chapter -->
    <a href="#content-top">
      <button
        :disabled="!verses.meta || !verses.meta.next_chapter "
        @click="goToNextChapter()"
        :class="buttonClass"
        class="rounded-r">
        <heroicons-svg name="chevron-double-right"></heroicons-svg>
      </button>
    </a>
  </div>
  
</div>
</template>



<script>
import axios from 'axios'
import VerseText from '../components/VerseText.vue'
import HeroiconsSvg from '../components/HeroiconsSvg.vue'
import {ref, onMounted, reactive, watch,
        watchEffect, nextTick, computed} from 'vue'

export default {
  props: ['modules_obj', 'default_module', 'comments_obj'],
  components: {
    VerseText,
    HeroiconsSvg
  },
  setup(props) {
    const books = reactive({}) 
    const bookNumber = ref(470)
    const chapterNumber = ref(1)
    const verseNumber = ref(1)
    const uiModule = ref(props.default_module)
    const modules = reactive(props.modules_obj)
    const comments = reactive(props.comments_obj)
    const pickedModules = ref([])
    const pickedComments = reactive({})
    
    for(const key in props.comments_obj){
      pickedModules.value.push(key)
    }
    
    const urlParams = new URLSearchParams(
      location.search)
    
    for (const [key, value] of urlParams) {
      if (key == "b") {
        chapterNumber.value = 1
        bookNumber.value = value
      } else if (key == "c" && Number.isInteger(Number(value))
                 && value >= 1) {
        chapterNumber.value = value
      } else if (key == "v") {
        verseNumber.value = value
      } else if (key == "t" && modules[value]) {
        uiModule.value = value
      }
      // else if (key == "a1" && modules.value[value]) {
      //   set(pickedModules.value, 0, value)
      // } else if (key == "a2" && modules.value[value]) {
      //   set(pickedModules.value, 1, value)
      // } else if (key == "a3" && modules.value[value]) {
      //   set(pickedModules.value, 2, value)
      // } 
    }
    
    const currentUrl = "https://evprosdektos.github.io"
    
    
    const verses = reactive({})
    const availableComments = reactive({})
    
    function setVerses() {
      return new Promise((resolve,reject)=>{
        if (modules[uiModule.value].st <= bookNumber.value 
            && modules[uiModule.value].end >= bookNumber.value) {
          axios
            .get(`${currentUrl}/${uiModule.value}/${bookNumber.value}/${chapterNumber.value}.json`)
            .then((response)=> Object.assign(verses, response.data))
            .catch((error)=>true)
        }else {delete verses.chapter ; delete verses.meta}
        for (const commentary in comments) {
          axios
            .get(`${currentUrl}/${commentary}/${bookNumber.value}/${chapterNumber.value}.json`)
            .then((response)=> {
              availableComments[commentary] = response.data
              if (Object.keys(comments)[Object.keys(comments).length - 1] == commentary)  resolve();
            })
            .catch((error)=>true)
        }
      })
    };
    
    function setComments() {
      for (const commentary in comments) {
        if (pickedModules.value.includes(commentary)) {
          if (availableComments[commentary]
              && availableComments[commentary][verseNumber.value]) {
            axios
              .get(`${currentUrl}/${commentary}/${bookNumber.value}/${chapterNumber.value}/${verseNumber.value}.json`)
              .then((response) => {
                pickedComments[commentary] = response.data.xml
              })
              .catch((error)=>true)
          } else delete pickedComments[commentary]
        } else delete pickedComments[commentary]
      }
    }
    
    
    const showingBooksDropdown = ref(false)
    const showingChaptersDropdown = ref(false)
    const showingVersesDropdown = ref(false)
    
    
    const buttonClass = ref("inline-flex bg-white hover:bg-gray-800 text-gray-800 font-semibold py-2 px-4 border border-gray-400 shadow align-middle leading-[1em] disabled:opacity-50")
    
    
    
    // watch(bookNumber, (newValue, oldValue) =>{
    //   if (newValue != oldValue) {
    //     chapterNumber.value = 1
    //   }
    // })
    
    watch(uiModule, () => {
      setBooks()
      setVerseCommentParams()
    }, {
      immediate: true,
    })
    
    watch(()=> [...pickedModules.value], () => {
      setComments()
    })
    
    function setBooks(){
      axios
        .get(`${currentUrl}/${uiModule.value}/books.json`)
        .then((response) => Object.assign(books, response.data))
        .catch((error) => console.log(error))
    }
    
    
    function setUrlParams() {
      const params = new URLSearchParams(
        location.search)
      params.set('b', bookNumber.value)
      params.set('c', chapterNumber.value)
      params.set('v', verseNumber.value)
      params.set('t', uiModule.value)
      // params.set('a1', pickedModules.value[0])
      // if (pickedModules.value[1]) {
      //   params.set('a2', pickedModules.value[1])
      // } else params.delete('a2')
      // if (pickedModules.value[2]) {
      //   params.set('a3', pickedModules.value[2])
      // } else params.delete('a3')
      window.history.replaceState({path: `${location.pathname}?${params.toString()}${location.hash}`}, '', `${location.pathname}?${params.toString()}${location.hash}`)
    }
    
    function goToPreviousChapter() {
      const previousChapterBook
            = verses.meta.previous_chapter.book_number
      const previousChapter
            = verses.meta.previous_chapter.chapter
      verseNumber.value = 1
      chapterNumber.value = 1
      bookNumber.value = previousChapterBook
      chapterNumber.value = previousChapter
      setVerseCommentParams()
    }
    
    function goToNextChapter() {
      const nextChapterBook
            = verses.meta.next_chapter.book_number
      const nextChapter
            = verses.meta.next_chapter.chapter
      verseNumber.value = 1
      chapterNumber.value = 1
      bookNumber.value = nextChapterBook
      chapterNumber.value = nextChapter
      setVerseCommentParams()
    }
    
    const lastVerse = computed(()=>{
      return Object.keys(verses.chapter)[Object.keys(verses.chapter).length - 1]
    })
    
    function goToPreviousVerse() {
      if (verseNumber.value > 1) {
        verseNumber.value--
        setComments()
        setUrlParams()
      } else {
        goToPreviousChapter()
        verseNumber.value = lastVerse.value
      }
    }
    
    function goToNextVerse() {
      if (verseNumber.value < lastVerse.value) {
        verseNumber.value++
        setComments()
        setUrlParams()
      } else {
        goToNextChapter()
      }
    }
    
    function goToBook(book_number) {
      chapterNumber.value = 1
      bookNumber.value = book_number
      setVerseCommentParams()
    }
    
    function goToChapter(chapter_number) {
      chapterNumber.value = chapter_number
      setVerseCommentParams()
    }
    
    function goToVerse(verse_number) {
      verseNumber.value = verse_number
      setUrlParams()
      setComments()
    }
    
    function setVerseCommentParams() {
      nextTick(()=>setVerses().then(setComments))
      setUrlParams()
    }
    
    return {
      books,
      verses,
      uiModule,
      modules,
      comments,
      pickedComments,
      availableComments,
      chapterNumber,
      verseNumber,
      lastVerse,
      showingBooksDropdown,
      showingChaptersDropdown,
      showingVersesDropdown,
      buttonClass,
      bookNumber,
      pickedModules,
      setUrlParams,
      setVerses,
      setBooks,
      setComments,
      goToPreviousChapter,
      goToNextChapter,
      goToPreviousVerse,
      goToNextVerse,
      goToBook,
      goToChapter,
      goToVerse
    }
  }
}
</script>



