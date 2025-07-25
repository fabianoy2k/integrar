<template>
  <div class="vue-menu">
    <!-- Menu Dropdown -->
    <div class="relative">
      <button 
        @click="toggleMenu" 
        class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none"
      >
        {{ menuTitle }}
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      
      <div 
        v-show="isOpen" 
        @click.away="closeMenu"
        class="absolute left-0 mt-2 bg-white border rounded shadow-lg z-50"
        :class="menuWidth"
      >
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VueMenu',
  props: {
    menuTitle: {
      type: String,
      required: true
    },
    menuWidth: {
      type: String,
      default: 'w-48'
    }
  },
  data() {
    return {
      isOpen: false
    }
  },
  methods: {
    toggleMenu() {
      this.isOpen = !this.isOpen
    },
    closeMenu() {
      this.isOpen = false
    }
  }
}
</script>

<style scoped>
.vue-menu {
  display: inline-block;
}
</style> 