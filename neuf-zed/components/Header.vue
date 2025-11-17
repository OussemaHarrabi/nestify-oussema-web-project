<template>
  <header class="sticky top-0 z-50 bg-white border-b border-border">
    <div class="max-w-[1760px] mx-auto px-4 sm:px-6 lg:px-20">
      <div class="flex items-center justify-between h-16 sm:h-20">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center space-x-2 sm:space-x-3 cursor-pointer flex-shrink-0">
          <div class="bg-primary rounded-lg p-1.5 sm:p-2">
            <div class="w-6 h-6 sm:w-7 sm:h-7 bg-white rounded-sm flex items-center justify-center">
              <span class="text-primary text-xs sm:text-sm font-bold">N</span>
            </div>
          </div>
          <span class="text-xl sm:text-2xl font-semibold text-primary">neuf.tn</span>
        </NuxtLink>

        <!-- Mobile Search Icon (when showSearchBar is true) -->
        <button 
          v-if="showSearchBar" 
          @click="toggleMobileSearch"
          class="lg:hidden p-2 text-muted-foreground hover:text-foreground transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </button>

        <!-- Desktop Search Bar -->
        <div v-if="showSearchBar" class="hidden lg:block flex-1 max-w-2xl mx-8">
          <SearchBar />
        </div>

        <!-- Navigation principale - cachée sur mobile et quand searchBar est affichée -->
        <div v-if="!showSearchBar" class="hidden lg:flex items-center space-x-8">
          <NuxtLink 
            to="/search" 
            class="relative text-foreground hover:text-primary transition-all duration-300 font-medium pb-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all after:duration-300 hover:after:w-full"
            active-class="text-primary after:!w-full"
          >
            Projets
          </NuxtLink>
          <NuxtLink 
            to="/promoteurs" 
            class="relative text-foreground hover:text-primary transition-all duration-300 font-medium pb-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all after:duration-300 hover:after:w-full"
            active-class="text-primary after:!w-full"
          >
            Promoteurs
          </NuxtLink>
          <NuxtLink 
            to="/financement" 
            class="relative text-foreground hover:text-primary transition-all duration-300 font-medium pb-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all after:duration-300 hover:after:w-full"
            active-class="text-primary after:!w-full"
          >
            Financement
          </NuxtLink>
          <NuxtLink 
            to="/a-propos" 
            class="relative text-foreground hover:text-primary transition-all duration-300 font-medium pb-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all after:duration-300 hover:after:w-full"
            active-class="text-primary after:!w-full"
          >
            À propos
          </NuxtLink>
        </div>

        <!-- Actions utilisateur -->
        <div class="flex items-center space-x-2 sm:space-x-4">
          <!-- Annoncer votre projet button - Desktop only -->
          <NuxtLink
            v-if="!isAuthenticated"
            to="/login"
            class="hidden lg:flex items-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-full px-5 py-2.5 transition-all font-semibold shadow-md hover:shadow-lg transform hover:scale-105"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Annoncer votre projet
          </NuxtLink>

          <!-- Profile Dropdown - Desktop (when logged in) -->
          <div v-if="isAuthenticated" class="hidden lg:block relative" ref="dropdownRef">
            <button
              @click="toggleDropdown"
              class="flex items-center border border-border rounded-full p-1 hover:shadow-lg transition-all cursor-pointer bg-white"
            >
              <div class="p-2.5">
                <Menu class="w-4 h-4 text-muted-foreground" />
              </div>
              <div class="p-2 bg-primary rounded-full">
                <User class="w-5 h-5 text-white" />
              </div>
            </button>

            <!-- Dropdown Menu -->
            <div
              v-if="dropdownOpen"
              class="absolute right-0 mt-2 w-56 bg-white border-2 border-border rounded-xl shadow-xl overflow-hidden z-50"
            >
              <!-- User Info -->
              <div class="px-4 py-3 border-b border-border bg-muted/30">
                <p class="text-sm font-semibold text-foreground">{{ userName }}</p>
                <p class="text-xs text-muted-foreground">{{ userEmail }}</p>
              </div>

              <!-- Menu Items -->
              <div class="py-2">
                <NuxtLink
                  to="/dashboard/profile"
                  class="flex items-center gap-3 px-4 py-2.5 text-sm text-foreground hover:bg-muted transition-colors"
                  @click="closeDropdown"
                >
                  <User class="w-4 h-4 text-muted-foreground" />
                  <span>Mon profil</span>
                </NuxtLink>

                <NuxtLink
                  to="/dashboard"
                  class="flex items-center gap-3 px-4 py-2.5 text-sm text-foreground hover:bg-muted transition-colors"
                  @click="closeDropdown"
                >
                  <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                  </svg>
                  <span>Tableau de bord</span>
                </NuxtLink>

                <NuxtLink
                  to="/dashboard/contacts"
                  class="flex items-center gap-3 px-4 py-2.5 text-sm text-foreground hover:bg-muted transition-colors"
                  @click="closeDropdown"
                >
                  <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                  </svg>
                  <span>Contacts</span>
                </NuxtLink>

                <!-- Divider -->
                <div class="my-2 border-t border-border"></div>

                <!-- Logout -->
                <button
                  @click="handleLogout"
                  class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-destructive hover:bg-destructive/10 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                  </svg>
                  <span>Se déconnecter</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="lg:hidden p-2 text-foreground hover:text-primary transition-colors"
            aria-label="Menu"
          >
            <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Search Bar Overlay -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div v-if="mobileSearchOpen" class="lg:hidden border-t border-border bg-white px-4 py-3">
        <SearchBar />
      </div>
    </transition>

    <!-- Mobile Menu Overlay -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-full"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-full"
    >
      <div v-if="mobileMenuOpen" class="lg:hidden border-t border-border bg-white">
        <nav class="px-4 py-4 space-y-1">
          <!-- Navigation Links -->
          <NuxtLink
            to="/search"
            @click="closeMobileMenu"
            class="flex items-center px-4 py-3 rounded-lg text-foreground hover:bg-muted transition-colors font-medium"
            active-class="bg-primary/10 text-primary"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Projets
          </NuxtLink>

          <NuxtLink
            to="/promoteurs"
            @click="closeMobileMenu"
            class="flex items-center px-4 py-3 rounded-lg text-foreground hover:bg-muted transition-colors font-medium"
            active-class="bg-primary/10 text-primary"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Promoteurs
          </NuxtLink>

          <NuxtLink
            to="/financement"
            @click="closeMobileMenu"
            class="flex items-center px-4 py-3 rounded-lg text-foreground hover:bg-muted transition-colors font-medium"
            active-class="bg-primary/10 text-primary"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Financement
          </NuxtLink>

          <NuxtLink
            to="/a-propos"
            @click="closeMobileMenu"
            class="flex items-center px-4 py-3 rounded-lg text-foreground hover:bg-muted transition-colors font-medium"
            active-class="bg-primary/10 text-primary"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            À propos
          </NuxtLink>

          <!-- Divider -->
          <div class="my-3 border-t border-border"></div>

          <!-- CTA Button or Profile Section -->
          <div v-if="!isAuthenticated">
            <NuxtLink
              to="/login"
              @click="closeMobileMenu"
              class="flex items-center justify-center gap-2 px-4 py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-lg transition-all font-semibold shadow-md"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Annoncer votre projet
            </NuxtLink>
          </div>

          <div v-else class="space-y-1">
            <!-- User Info -->
            <div class="px-4 py-3 bg-muted/30 rounded-lg">
              <p class="text-sm font-semibold text-foreground">{{ userName }}</p>
              <p class="text-xs text-muted-foreground">{{ userEmail }}</p>
            </div>

            <!-- Profile Links -->
            <NuxtLink
              to="/dashboard/profile"
              @click="closeMobileMenu"
              class="flex items-center px-4 py-3 rounded-lg text-foreground hover:bg-muted transition-colors"
            >
              <User class="w-5 h-5 mr-3 text-muted-foreground" />
              Mon profil
            </NuxtLink>

            <NuxtLink
              to="/dashboard"
              @click="closeMobileMenu"
              class="flex items-center px-4 py-3 rounded-lg text-foreground hover:bg-muted transition-colors"
            >
              <svg class="w-5 h-5 mr-3 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
              </svg>
              Tableau de bord
            </NuxtLink>

            <NuxtLink
              to="/dashboard/contacts"
              @click="closeMobileMenu"
              class="flex items-center px-4 py-3 rounded-lg text-foreground hover:bg-muted transition-colors"
            >
              <svg class="w-5 h-5 mr-3 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
              Contacts
            </NuxtLink>

            <!-- Logout -->
            <button
              @click="handleMobileLogout"
              class="w-full flex items-center px-4 py-3 rounded-lg text-destructive hover:bg-destructive/10 transition-colors"
            >
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              Se déconnecter
            </button>
          </div>
        </nav>
      </div>
    </transition>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Menu, User } from 'lucide-vue-next'
import SearchBar from './SearchBar.vue'

interface Props {
  showSearchBar?: boolean
}

withDefaults(defineProps<Props>(), {
  showSearchBar: false
})

// Auth state
const { isAuthenticated, user, logout } = useAuthApi()
const router = useRouter()

// Dropdown state
const dropdownOpen = ref(false)
const dropdownRef = ref<HTMLElement | null>(null)

// Mobile menu state
const mobileMenuOpen = ref(false)
const mobileSearchOpen = ref(false)

// User info
const userName = computed(() => {
  if (user.value) {
    const userData = typeof user.value === 'string' ? JSON.parse(user.value) : user.value
    return userData.name || 'Utilisateur'
  }
  return 'Utilisateur'
})

const userEmail = computed(() => {
  if (user.value) {
    const userData = typeof user.value === 'string' ? JSON.parse(user.value) : user.value
    return userData.email || ''
  }
  return ''
})

// Toggle dropdown
const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

const closeDropdown = () => {
  dropdownOpen.value = false
}

// Mobile menu functions
const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value
  if (mobileMenuOpen.value) {
    mobileSearchOpen.value = false
  }
}

const closeMobileMenu = () => {
  mobileMenuOpen.value = false
}

const toggleMobileSearch = () => {
  mobileSearchOpen.value = !mobileSearchOpen.value
  if (mobileSearchOpen.value) {
    mobileMenuOpen.value = false
  }
}

// Handle logout
const handleLogout = async () => {
  await logout()
  closeDropdown()
  router.push('/')
}

const handleMobileLogout = async () => {
  await logout()
  closeMobileMenu()
  router.push('/')
}

// Click outside to close dropdown
const handleClickOutside = (event: MouseEvent) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
    closeDropdown()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
