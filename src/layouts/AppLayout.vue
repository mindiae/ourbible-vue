<template>
<div>
  <div class="min-h-screen bg-gray-100 font-sans">
    
    <nav class="bg-white border-b border-gray-100">

      <!-- Primary Navigation Menu -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            
            <div class="shrink-0 flex items-center">
              <g-link to="/">
                <application-mark class="block h-9 w-auto">
                </application-mark>
                
              </g-link>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
              <nav-link v-for="navItem in navItems" :key="navItem.title"
                        :href="navItem.path"
                        :active="navItem.title == $context.title">
                {{ navItem.title }}
              </nav-link>
            </div>
            
          </div>

          <!-- Hamburger -->
          <div class="-mr-2 flex items-center sm:hidden">
            <button @click="showingNavigationDropdown
                            = ! showingNavigationDropdown"
                    class="inline-flex items-center justify-center
                           p-2 rounded-md text-gray-400 hover:text-gray-500
                           hover:bg-gray-100 focus:outline-none
                           focus:bg-gray-100 focus:text-gray-500 transition">
              <svg class="h-6 w-6" stroke="currentColor" fill="none"
                   viewBox="0 0 24 24">
                <path :class="{'hidden': showingNavigationDropdown,
                              'inline-flex': ! showingNavigationDropdown }"
                      stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! showingNavigationDropdown,
                              'inline-flex': showingNavigationDropdown }"
                      stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': showingNavigationDropdown,
                     'hidden': ! showingNavigationDropdown}"
             class="sm:hidden">
          <div class="pt-2 pb-3 space-y-1">
            <responsive-nav-link v-for="navItem in navItems"
                                 :key="navItem.title" :href="navItem.path"
                                 :active="navItem.title == $context.title">
              {{ navItem.title }}
            </responsive-nav-link>
          </div>
        </div>
        
      </div>
      
    </nav>

    <!-- breadcrumbs -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto pt-1 px-4 sm:px-6 lg:px-8">
        <template v-for="breadcrumb in breadcrumbs">
          <span v-if="breadcrumb.path != '/'">/</span>
          <g-link class="text-blue-500 hover:underline"
                  :to="breadcrumb.path" >
            {{ breadcrumb.name }}
          </g-link>
        </template>
      </div>
    </div>
    
    <header class="bg-white shadow" v-if="$slots.header">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="header">
        </slot>
      </div>
    </header>
    
    <main>
      <slot>
      </slot>
    </main>
    
    <footer class="sticky top-[100vh] bg-white shadow" v-if="$slots.footer">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="footer">
        </slot>
      </div>
    </footer>
  </div>
</div>
</template>

<script>
import ApplicationMark from '~/components/ApplicationMark.vue'
import NavLink from '~/components/NavLink.vue'
import ResponsiveNavLink from '~/components/ResponsiveNavLink.vue'

export default {
  components: {
    ApplicationMark,
    NavLink,
    ResponsiveNavLink,
  },
  data() {
    return {
      showingNavigationDropdown: false,
      navItems: [
        {
          title: 'Home',
          path: '/'
        },
        {
          title: 'Bible',
          path: '/bible-index/'
        },
        {
          title: 'Commentaries',
          path: '/commentaries/'
        },
        {
          title: 'About',
          path: '/about/'
         }
       ]
     }
  },
  props: {
    breadcrumbs: {
      type: Array,
      default: () => [
        {
          name: '',
          path: '/'
        }
      ] 
    }
  }
 }
</script>
