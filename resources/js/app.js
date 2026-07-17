import Alpine from 'alpinejs'

// Make Alpine available globally
window.Alpine = Alpine

// Theme store
document.addEventListener('alpine:init', () => {
  Alpine.store('theme', {
    current: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
    toggle() {
      this.current = this.current === 'light' ? 'dark' : 'light'
      document.documentElement.setAttribute('data-theme', this.current)
      localStorage.setItem('theme', this.current)
    },
    init() {
      document.documentElement.setAttribute('data-theme', this.current)
    }
  })
})

// Start Alpine
Alpine.start()
