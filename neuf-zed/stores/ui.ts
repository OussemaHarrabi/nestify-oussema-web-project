import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface Notification {
  id: string
  type: 'success' | 'error' | 'warning' | 'info'
  title: string
  message?: string
  duration?: number
}

export const useUIStore = defineStore('ui', () => {
  // State
  const isLoading = ref(false)
  const loadingMessage = ref('')
  const notifications = ref<Notification[]>([])
  const isSidebarOpen = ref(false)
  const isMobileMenuOpen = ref(false)
  const activeModal = ref<string | null>(null)

  // Actions
  const setLoading = (loading: boolean, message = '') => {
    isLoading.value = loading
    loadingMessage.value = message
  }

  const showNotification = (notification: Omit<Notification, 'id'>) => {
    const id = Date.now().toString() + Math.random().toString(36).substr(2, 9)
    const newNotification: Notification = {
      id,
      ...notification,
      duration: notification.duration || 5000
    }

    notifications.value.push(newNotification)

    // Auto remove after duration
    if (newNotification.duration > 0) {
      setTimeout(() => {
        removeNotification(id)
      }, newNotification.duration)
    }

    return id
  }

  const removeNotification = (id: string) => {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index !== -1) {
      notifications.value.splice(index, 1)
    }
  }

  const clearNotifications = () => {
    notifications.value = []
  }

  const showSuccess = (title: string, message?: string) => {
    return showNotification({ type: 'success', title, message })
  }

  const showError = (title: string, message?: string) => {
    return showNotification({ type: 'error', title, message, duration: 7000 })
  }

  const showWarning = (title: string, message?: string) => {
    return showNotification({ type: 'warning', title, message })
  }

  const showInfo = (title: string, message?: string) => {
    return showNotification({ type: 'info', title, message })
  }

  const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
  }

  const closeSidebar = () => {
    isSidebarOpen.value = false
  }

  const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value
  }

  const closeMobileMenu = () => {
    isMobileMenuOpen.value = false
  }

  const openModal = (modalName: string) => {
    activeModal.value = modalName
  }

  const closeModal = () => {
    activeModal.value = null
  }

  return {
    // State
    isLoading,
    loadingMessage,
    notifications,
    isSidebarOpen,
    isMobileMenuOpen,
    activeModal,

    // Actions
    setLoading,
    showNotification,
    removeNotification,
    clearNotifications,
    showSuccess,
    showError,
    showWarning,
    showInfo,
    toggleSidebar,
    closeSidebar,
    toggleMobileMenu,
    closeMobileMenu,
    openModal,
    closeModal
  }
})
