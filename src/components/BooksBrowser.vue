<template>
  <div>
    <!--
  <h2 class="font-semibold text-xl text-gray-800 py-6">
    <span  v-if="books && books.books[bookNumber]">
      {{books.books[bookNumber].long_name}} {{chapterNumber}}
    </span>
  </h2>
-->

    <div>
      <!-- show books toggle -->
      <button
        @click="
          showingBooksDropdown = !showingBooksDropdown;
          showingChaptersDropdown = false;
        "
        :class="buttonClass"
        class="rounded-l"
      >
        <span v-if="books && books.books[bookNumber]">
          {{ books.books[bookNumber].short_name }}
        </span>
        <span v-else>
          {{ "==" }}
        </span>
        <heroicons-svg class="ml-2" name="chevron-down"> </heroicons-svg>
      </button>

      <!-- show chapters toggle -->
      <button
        @click="
          showingChaptersDropdown = !showingChaptersDropdown;
          showingBooksDropdown = false;
        "
        :class="buttonClass"
        class="rounded-r"
      >
        -- {{ chapterNumber }} --
        <heroicons-svg class="ml-2" name="chevron-down"> </heroicons-svg>
      </button>
      {{ " " }}

      <!-- previous chapter -->
      <button
        :disabled="!pickedVerses[0] || !pickedVerses[0].meta.previous_chapter"
        @click="goToPreviousChapter()"
        :class="buttonClass"
        class="rounded-l"
      >
        <heroicons-svg name="chevron-left"></heroicons-svg>
      </button>

      <!-- next chapter -->
      <button
        :disabled="!pickedVerses[0] || !pickedVerses[0].meta.next_chapter"
        @click="goToNextChapter()"
        :class="buttonClass"
        class="rounded-r"
      >
        <heroicons-svg name="chevron-right"></heroicons-svg>
      </button>

      <!-- books -->
      <div
        :class="{ block: showingBooksDropdown, hidden: !showingBooksDropdown }"
      >
        <span v-if="books" v-for="book in books.books">
          <br v-if="book.book_number == 470" />
          <button
            @click="goToBook(book.book_number)"
            class="border rounded px-2 disabled:font-bold"
            :style="{ 'background-color': book.book_color }"
            :key="book.book_number"
            :disabled="book.book_number == bookNumber"
          >
            {{ book.short_name }}
          </button>
        </span>
      </div>

      <!-- chapters per book -->
      <div
        v-if="books && books.maxChapters[bookNumber]"
        :class="{
          block: showingChaptersDropdown,
          hidden: !showingChaptersDropdown,
        }"
      >
        <button
          @click="goToChapter(chapter_number)"
          class="bg-gray-600 disabled:bg-gray-900 text-white border rounded px-2"
          v-for="chapter_number in books.maxChapters[bookNumber].max_chapter"
          :key="chapter_number"
          :disabled="chapter_number == chapterNumber"
        >
          {{ chapter_number }}
        </button>
      </div>

      <!-- menu language -->
      <div class="mt-2">
        <button
          class="border rounded px-2"
          v-for="key in pickedModules"
          :key="key"
        >
          <label>
            {{ key }}
            <input type="radio" :value="key" v-model="uiModule" />
          </label>
        </button>
      </div>

      <!-- from 1 up to 3 modules chooser -->
      <div class="mb-2">
        <button
          class="rounded border px-2"
          v-for="(val, module) in modules"
          :key="module"
        >
          <label>
            {{ module }}
            <input
              type="checkbox"
              v-model="pickedModules"
              :value="module"
              :disabled="
                pickedModules.length > 2 && pickedModules.indexOf(module) === -1
              "
            />
          </label>
        </button>
      </div>

      <!-- actual content -->
      <article>
        <h1 v-if="books && books.books[bookNumber]">
          {{ books.books[bookNumber].long_name }}
          {{ chapterNumber
          }}<span v-if="startVerseNumber"
            >: {{ startVerseNumber
            }}<span v-if="endVerseNumber != startVerseNumber"
              >-{{ endVerseNumber }}</span
            >
            {{ " " }}
            <button
              :class="buttonClass"
              class="rounded text-sm"
              @click="
                startVerseNumber = false;
                endVerseNumber = false;
              "
            >
              <heroicons-svg name="chevron-right"></heroicons-svg>
            </button>
          </span>
        </h1>
        <p id="content-top">
          <template v-for="verse in pickedVerses[0].chapter">
            <template
              v-if="
                !startVerseNumber ||
                (startVerseNumber <= verse.verse &&
                  endVerseNumber >= verse.verse)
              "
            >
              <template v-if="pickedModules.length == 1">
                <sup>{{ verse.verse }}</sup>
                <verse-text
                  v-if="pickedModules[0].includes('+')"
                  :data="verse.text"
                >
                </verse-text>
                <span v-else v-html="verse.text"></span> {{ " " }}
              </template>

              <div v-else class="flex">
                <div class="flex py-2">{{ verse.verse }}</div>
                <div
                  class="flex-1 p-2"
                  v-for="moduleNumber in pickedModules.length"
                  :key="moduleNumber"
                >
                  <template
                    v-if="
                      pickedVerses[moduleNumber - 1] &&
                      pickedVerses[moduleNumber - 1].chapter[verse.verse]
                    "
                  >
                    <verse-text
                      v-if="pickedModules[moduleNumber - 1].includes('+')"
                      :data="
                        pickedVerses[moduleNumber - 1].chapter[verse.verse].text
                      "
                    >
                    </verse-text>

                    <span
                      v-else
                      v-html="
                        pickedVerses[moduleNumber - 1].chapter[verse.verse].text
                      "
                    >
                    </span>
                    {{ " " }}
                  </template>
                </div>
              </div>
            </template>
          </template>
        </p>
      </article>

      <!-- previous chapter -->
      <a href="#content-top">
        <button
          :disabled="!pickedVerses[0] || !pickedVerses[0].meta.previous_chapter"
          @click="goToPreviousChapter()"
          :class="buttonClass"
          class="rounded-l"
        >
          <heroicons-svg name="chevron-left"></heroicons-svg>
        </button>
      </a>

      <!-- next chapter -->
      <a href="#content-top">
        <button
          :disabled="!pickedVerses[0] || !pickedVerses[0].meta.next_chapter"
          @click="goToNextChapter()"
          :class="buttonClass"
          class="rounded-r"
        >
          <heroicons-svg name="chevron-right"></heroicons-svg>
        </button>
      </a>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import VerseText from "../components/VerseText.vue";
import HeroiconsSvg from "../components/HeroiconsSvg.vue";
import { ref, reactive, watch, watchEffect, computed } from "vue";

export default {
  props: ["modules_obj", "default_module"],
  components: {
    VerseText,
    HeroiconsSvg,
  },
  setup(props) {
    const bookNumber = ref(470);
    const chapterNumber = ref(1);
    const startVerseNumber = ref(false);
    const endVerseNumber = ref(false);

    const uiModule = ref(props.default_module);
    const pickedModules = ref([props.default_module]);
    const modules = reactive(props.modules_obj);

    const urlParams = new URLSearchParams(location.search);

    for (const [key, value] of urlParams) {
      if (key == "b") {
        bookNumber.value = value;
      } else if (key == "c" && Number.isInteger(Number(value)) && value >= 1) {
        chapterNumber.value = value;
      } else if (key == "i") {
        startVerseNumber.value = value;
      } else if (key == "e") {
        endVerseNumber.value = value;
      } else if (key == "t" && modules[value]) {
        uiModule.value = value;
      } else if (key == "a1" && modules[value]) {
        pickedModules.value[0] = value;
      } else if (key == "a2" && modules[value]) {
        pickedModules.value[1] = value;
      } else if (key == "a3" && modules[value]) {
        pickedModules.value[2] = value;
      }
    }

    const currentUrl = "https://evprosdektos.github.io";

    const pickedVerses = ref([false, false, false]);

    function setVerses(i) {
      if (
        modules[pickedModules.value[i]].st <= bookNumber.value &&
        modules[pickedModules.value[i]].end >= bookNumber.value
      ) {
        axios
          .get(
            `${currentUrl}/${pickedModules.value[i]}/${bookNumber.value}/${chapterNumber.value}.json`,
          )
          .then((response) => (pickedVerses.value[i] = response.data))
          .catch((error) => console.log(error));
      } else pickedVerses.value[i] = false;
    }

    function setAllVerses() {
      for (var i = 0; i < pickedModules.value.length; i++) {
        setVerses(i);
      }
    }

    let showingBooksDropdown = ref(false);
    let showingChaptersDropdown = ref(false);

    const buttonClass = ref(
      "inline-flex bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 shadow align-middle leading-[1em] disabled:opacity-50",
    );

    watch(
      startVerseNumber,
      (newValue) => {
        if (newValue && !endVerseNumber.value) {
          endVerseNumber.value = newValue;
        }
      },
      { immediate: true },
    );

    watchEffect(() => {
      if (!pickedModules.value.length) {
        pickedModules.value.push(uiModule.value);
      }
      if (!pickedModules.value.includes(uiModule.value)) {
        uiModule.value = pickedModules.value[0];
      }
    });

    let pickedBooks = ref([false, false, false]);
    watch(
      () => [...pickedModules.value],
      () => {
        for (var i = 0; i < pickedModules.value.length; i++) {
          setBooks(i);
          setVerses(i);
        }
        setUrlParams();
      },
      {
        immediate: true,
      },
    );

    function setBooks(i) {
      axios
        .get(`${currentUrl}/${pickedModules.value[i]}/books.json`)
        .then((response) => (pickedBooks.value[i] = response.data))
        .catch((error) => console.log(error));
    }

    const books = computed(() => {
      return pickedBooks.value[pickedModules.value.indexOf(uiModule.value)];
    });

    function setUrlParams() {
      const params = new URLSearchParams(location.search);
      params.set("b", bookNumber.value);
      params.set("c", chapterNumber.value);
      params.set("t", uiModule.value);
      if (startVerseNumber.value) {
        params.set("i", startVerseNumber.value);
      } else params.delete("i");
      if (endVerseNumber.value) {
        params.set("e", endVerseNumber.value);
      } else params.delete("e");
      params.set("a1", pickedModules.value[0]);
      if (pickedModules.value[1]) {
        params.set("a2", pickedModules.value[1]);
      } else params.delete("a2");
      if (pickedModules.value[2]) {
        params.set("a3", pickedModules.value[2]);
      } else params.delete("a3");

      window.history.replaceState(
        { path: `${location.pathname}?${params.toString()}${location.hash}` },
        "",
        `${location.pathname}?${params.toString()}${location.hash}`,
      );
    }

    function goToPreviousChapter() {
      startVerseNumber.value = false;
      endVerseNumber.value = false;
      const previousChapterBook =
        pickedVerses.value[0].meta.previous_chapter.book_number;
      const previousChapter =
        pickedVerses.value[0].meta.previous_chapter.chapter;
      chapterNumber.value = 1;
      bookNumber.value = previousChapterBook;
      chapterNumber.value = previousChapter;
      setAllVerses();
      setUrlParams();
    }

    function goToNextChapter() {
      startVerseNumber.value = false;
      endVerseNumber.value = false;
      const nextChapterBook =
        pickedVerses.value[0].meta.next_chapter.book_number;
      const nextChapter = pickedVerses.value[0].meta.next_chapter.chapter;
      chapterNumber.value = 1;
      bookNumber.value = nextChapterBook;
      chapterNumber.value = nextChapter;
      setAllVerses();
      setUrlParams();
    }

    function goToBook(book_number) {
      startVerseNumber.value = false;
      endVerseNumber.value = false;
      chapterNumber.value = 1;
      bookNumber.value = book_number;
      setAllVerses();
      setUrlParams();
    }

    function goToChapter(chapter_number) {
      startVerseNumber.value = false;
      endVerseNumber.value = false;
      chapterNumber.value = chapter_number;
      setAllVerses();
      setUrlParams();
    }
    return {
      books,
      pickedBooks,
      pickedVerses,
      uiModule,
      modules,
      chapterNumber,
      showingBooksDropdown,
      showingChaptersDropdown,
      startVerseNumber,
      endVerseNumber,
      buttonClass,
      bookNumber,
      pickedModules,
      setUrlParams,
      setVerses,
      setAllVerses,
      setBooks,
      goToPreviousChapter,
      goToNextChapter,
      goToBook,
      goToChapter,
    };
  },
};
</script>
