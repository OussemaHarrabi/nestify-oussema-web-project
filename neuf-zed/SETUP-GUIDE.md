# 🚀 Setup Guide - neuf-zed (Nuxt.js Version)

Complete setup guide for migrating from React to Nuxt.js. This project is a pixel-perfect clone of the original React version with the exact same design, colors, and functionality.

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Development](#development)
- [Project Structure](#project-structure)
- [Key Differences from React](#key-differences-from-react)
- [Component Migration Guide](#component-migration-guide)
- [Styling Guide](#styling-guide)
- [Common Patterns](#common-patterns)
- [Troubleshooting](#troubleshooting)

---

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

- **Node.js**: Version 18.x or higher (LTS recommended)
- **npm**: Version 9.x or higher (comes with Node.js)
- **Git**: For version control

Check your versions:
```bash
node --version
npm --version
```

---

## 🛠️ Installation

### Step 1: Navigate to the Project

```bash
cd "Homepage Design for Neuf.tn/neuf-zed"
```

### Step 2: Install Dependencies

```bash
npm install
```

This will install all required packages including:
- Nuxt 3
- Tailwind CSS
- TypeScript
- Lucide Vue (icons)
- Radix Vue (UI components)
- Pinia (state management)
- Class Variance Authority (component variants)

### Step 3: Verify Installation

The installation should complete without errors. If you encounter any issues, see the [Troubleshooting](#troubleshooting) section.

---

## 🚀 Development

### Start Development Server

```bash
npm run dev
```

The application will be available at:
- **Local**: http://localhost:3000
- **Network**: http://[your-ip]:3000

### Build for Production

```bash
npm run build
```

### Preview Production Build

```bash
npm run preview
```

### Generate Static Site

```bash
npm run generate
```

---

## 📁 Project Structure

```
neuf-zed/
├── assets/
│   └── css/
│       └── main.css              # Tailwind CSS + Theme Variables
│
├── components/
│   ├── ui/                       # Reusable UI Components
│   │   ├── Button.vue            # Button with variants
│   │   └── Badge.vue             # Badge/Tag component
│   │
│   ├── Header.vue                # Site header with navigation
│   ├── HeroSection.vue           # Hero section with search
│   ├── ProjectsSection.vue       # Featured projects grid
│   ├── AboutSection.vue          # Mission and features
│   ├── BenefitsSection.vue       # Benefits of new properties
│   ├── DevelopersCTA.vue         # Call-to-action for developers
│   ├── Footer.vue                # Site footer
│   ├── SearchBar.vue             # Search input component
│   └── DeveloperLogo.vue         # Developer logo badge
│
├── pages/
│   ├── index.vue                 # Homepage
│   └── search.vue                # Search results page
│
├── utils/
│   └── cn.ts                     # Class name utility (clsx + twMerge)
│
├── composables/                  # Vue composables (to be added)
├── public/                       # Static assets
│
├── app.vue                       # Root component
├── nuxt.config.ts                # Nuxt configuration
├── tailwind.config.js            # Tailwind configuration
├── tsconfig.json                 # TypeScript configuration
└── package.json                  # Dependencies
```

---

## 🔄 Key Differences from React

### 1. **Component Syntax**

**React (JSX):**
```jsx
export function Button({ children, onClick, variant = 'default' }) {
  return (
    <button className={cn(buttonVariants({ variant }))} onClick={onClick}>
      {children}
    </button>
  )
}
```

**Vue (SFC - Single File Component):**
```vue
<template>
  <button :class="cn(buttonVariants({ variant }))" @click="onClick">
    <slot />
  </button>
</template>

<script setup lang="ts">
const props = defineProps<{
  variant?: 'default' | 'outline'
}>()

const emit = defineEmits<{
  click: []
}>()
</script>
```

### 2. **State Management**

**React:**
```jsx
const [count, setCount] = useState(0)
```

**Vue 3 (Composition API):**
```vue
<script setup>
import { ref } from 'vue'
const count = ref(0)
</script>
```

### 3. **Props**

**React:**
```jsx
interface ButtonProps {
  variant?: string
  onClick?: () => void
}

function Button({ variant, onClick }: ButtonProps) {
  // ...
}
```

**Vue:**
```vue
<script setup lang="ts">
interface Props {
  variant?: string
}

const props = defineProps<Props>()
const emit = defineEmits<{
  click: []
}>()
</script>
```

### 4. **Event Handling**

**React:**
```jsx
<button onClick={handleClick}>Click me</button>
```

**Vue:**
```vue
<button @click="handleClick">Click me</button>
```

### 5. **Conditional Rendering**

**React:**
```jsx
{condition && <Component />}
{isLoading ? <Spinner /> : <Content />}
```

**Vue:**
```vue
<Component v-if="condition" />
<Spinner v-if="isLoading" />
<Content v-else />
```

### 6. **Loops**

**React:**
```jsx
{items.map((item) => (
  <div key={item.id}>{item.name}</div>
))}
```

**Vue:**
```vue
<div v-for="item in items" :key="item.id">
  {{ item.name }}
</div>
```

### 7. **Routing**

**React (with state):**
```jsx
const [page, setPage] = useState('home')
```

**Nuxt (file-based routing):**
- `pages/index.vue` → `/`
- `pages/search.vue` → `/search`
- `pages/project/[id].vue` → `/project/:id`

Navigate with:
```vue
<NuxtLink to="/search">Search</NuxtLink>
<!-- or -->
<script setup>
const navigateTo = useRouter().push
navigateTo('/search')
</script>
```

---

## 🎨 Styling Guide

### Color System (EXACT MATCH)

All colors match the React version exactly:

```css
:root {
  --primary: 348 100% 61%;           /* #ff385c - Pink/Red */
  --background: 0 0% 100%;           /* #ffffff */
  --foreground: 0 0% 13.3%;          /* #222222 */
  --muted: 0 0% 96.9%;               /* #f7f7f7 */
  --muted-foreground: 0 0% 44.5%;    /* #717171 */
  --border: 0 0% 86.7%;              /* #dddddd */
  --radius: 0.75rem;                 /* 12px */
}
```

### Tailwind Classes

Use the same classes as React:

```vue
<div class="bg-primary text-primary-foreground">
  <h1 class="text-4xl lg:text-5xl font-semibold">Title</h1>
  <p class="text-muted-foreground">Description</p>
</div>
```

### Container Max Width

```vue
<div class="max-w-[1760px] mx-auto px-6 lg:px-20">
  <!-- Content -->
</div>
```

### Responsive Design

```vue
<!-- Mobile first approach -->
<div class="text-base lg:text-lg">
  <!-- Small by default, large on lg screens -->
</div>
```

---

## 🧩 Component Migration Guide

### Converting a React Component to Vue

**Step 1: Structure**
- Create `.vue` file instead of `.tsx`
- Use `<template>`, `<script setup>`, `<style>` tags

**Step 2: Import Icons**
```typescript
// Change from:
import { Search } from "lucide-react"

// To:
import { Search } from "lucide-vue-next"
```

**Step 3: Props & Events**
```typescript
// React
interface Props {
  onSearch?: () => void
  title: string
}

// Vue
interface Props {
  title: string
}
defineEmits<{
  search: []
}>()
```

**Step 4: State**
```typescript
// React
const [value, setValue] = useState('')

// Vue
const value = ref('')
```

**Step 5: Template**
```vue
<!-- React JSX -->
<div className="flex items-center">
  {items.map(item => (
    <span key={item.id}>{item.name}</span>
  ))}
</div>

<!-- Vue Template -->
<div class="flex items-center">
  <span v-for="item in items" :key="item.id">
    {{ item.name }}
  </span>
</div>
```

---

## 🔧 Common Patterns

### 1. Button with Click Handler

```vue
<template>
  <Button @click="handleClick">
    Click Me
  </Button>
</template>

<script setup lang="ts">
const handleClick = () => {
  console.log('Clicked!')
}
</script>
```

### 2. Form Input with v-model

```vue
<template>
  <input v-model="searchQuery" type="text" placeholder="Search..." />
</template>

<script setup lang="ts">
import { ref } from 'vue'
const searchQuery = ref('')
</script>
```

### 3. Computed Properties

```vue
<script setup lang="ts">
import { computed } from 'vue'

const items = ref([1, 2, 3, 4, 5])
const filteredItems = computed(() => {
  return items.value.filter(item => item > 2)
})
</script>
```

### 4. Watchers

```vue
<script setup lang="ts">
import { watch } from 'vue'

const searchQuery = ref('')

watch(searchQuery, (newValue) => {
  console.log('Search changed:', newValue)
})
</script>
```

### 5. Lifecycle Hooks

```vue
<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue'

onMounted(() => {
  console.log('Component mounted')
})

onUnmounted(() => {
  console.log('Component unmounted')
})
</script>
```

### 6. Navigation

```vue
<template>
  <!-- Link -->
  <NuxtLink to="/search">Go to Search</NuxtLink>
  
  <!-- Programmatic -->
  <button @click="goToSearch">Search</button>
</template>

<script setup lang="ts">
const goToSearch = () => {
  navigateTo('/search')
}
</script>
```

---

## 🐛 Troubleshooting

### Issue: Port Already in Use

**Solution:**
```bash
# Kill the process using port 3000
npx kill-port 3000

# Or use a different port
PORT=3001 npm run dev
```

### Issue: Module Not Found

**Solution:**
```bash
# Clear cache and reinstall
rm -rf node_modules package-lock.json .nuxt
npm install
```

### Issue: TypeScript Errors

**Solution:**
```bash
# Generate Nuxt types
npm run postinstall
```

### Issue: Tailwind Classes Not Working

**Solution:**
1. Check `tailwind.config.js` content paths
2. Ensure `main.css` is imported in `nuxt.config.ts`
3. Restart dev server

### Issue: Icons Not Showing

**Solution:**
```bash
# Ensure lucide-vue-next is installed
npm install lucide-vue-next
```

### Issue: Hot Reload Not Working

**Solution:**
```bash
# Restart dev server
npm run dev
```

---

## 📚 Additional Resources

### Official Documentation
- [Nuxt 3 Docs](https://nuxt.com/docs)
- [Vue 3 Docs](https://vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Lucide Icons](https://lucide.dev/)

### Nuxt 3 Key Features
- Auto-imports (no need to import components in pages)
- File-based routing
- Server-Side Rendering (SSR)
- API routes in `server/` directory
- Built-in TypeScript support

### Migration Tips
1. **Start with simple components** (Button, Badge)
2. **Test each component** individually
3. **Keep the same styling** (copy-paste Tailwind classes)
4. **Use exact same colors** (from `main.css`)
5. **Follow Vue 3 patterns** (Composition API with `<script setup>`)

---

## ✅ Checklist

Before considering migration complete:

- [ ] All UI components converted
- [ ] All pages converted
- [ ] Colors match exactly
- [ ] Fonts match exactly
- [ ] Spacing matches exactly
- [ ] Responsive design works
- [ ] Navigation works
- [ ] Forms work
- [ ] Images load correctly
- [ ] No console errors
- [ ] Build succeeds
- [ ] Preview looks correct

---

## 🎯 Next Steps

After basic setup:

1. **Complete remaining pages:**
   - Project detail page
   - Developer detail page
   - Dashboard
   - Create project flow
   - Create listing flow

2. **Add state management:**
   - Set up Pinia stores for complex state
   - User authentication state
   - Cart/favorites state

3. **Add API integration:**
   - Connect to backend API
   - Implement data fetching
   - Handle loading states

4. **Testing:**
   - Add unit tests (Vitest)
   - Add E2E tests (Playwright)
   - Test responsive design

5. **Performance optimization:**
   - Image optimization
   - Code splitting
   - Lazy loading

---

## 💡 Pro Tips

1. **Use Auto-imports:** Nuxt auto-imports components from `components/` directory
2. **Use Composables:** Extract reusable logic into `composables/`
3. **Use Layouts:** Create layouts in `layouts/` for different page structures
4. **Use Middleware:** Add route guards in `middleware/`
5. **Use Server Routes:** Add API endpoints in `server/api/`

---

## 📞 Support

If you encounter any issues:

1. Check this guide first
2. Check official Nuxt 3 documentation
3. Search GitHub issues
4. Ask the team lead

---

**Happy Coding! 🚀**