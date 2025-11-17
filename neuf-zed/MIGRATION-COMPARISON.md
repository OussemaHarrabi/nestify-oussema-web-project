# React to Vue/Nuxt Migration Comparison

This document provides side-by-side comparisons of React vs Vue/Nuxt code for the neuf.tn project.

## Table of Contents

- [Components](#components)
- [State Management](#state-management)
- [Props & Events](#props--events)
- [Conditional Rendering](#conditional-rendering)
- [Lists & Loops](#lists--loops)
- [Forms & v-model](#forms--v-model)
- [Styling](#styling)
- [Routing](#routing)
- [Lifecycle](#lifecycle)
- [Common Patterns](#common-patterns)

---

## Components

### Basic Component Structure

**React (.tsx)**
```tsx
import { useState } from "react"
import { Button } from "./ui/button"

interface Props {
  title: string
  onSearch?: () => void
}

export function SearchBar({ title, onSearch }: Props) {
  const [query, setQuery] = useState("")
  
  return (
    <div className="flex items-center">
      <input 
        value={query}
        onChange={(e) => setQuery(e.target.value)}
        placeholder="Search..."
      />
      <Button onClick={onSearch}>Search</Button>
    </div>
  )
}
```

**Vue (.vue)**
```vue
<template>
  <div class="flex items-center">
    <input 
      v-model="query"
      placeholder="Search..."
    />
    <Button @click="$emit('search')">Search</Button>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Button from './ui/Button.vue'

interface Props {
  title: string
}

defineProps<Props>()

defineEmits<{
  search: []
}>()

const query = ref('')
</script>
```

### Button Component with Variants

**React**
```tsx
import { cva, type VariantProps } from "class-variance-authority"
import { cn } from "./utils"

const buttonVariants = cva(
  "inline-flex items-center justify-center rounded-md",
  {
    variants: {
      variant: {
        default: "bg-primary text-primary-foreground",
        outline: "border bg-background"
      },
      size: {
        default: "h-9 px-4",
        sm: "h-8 px-3"
      }
    }
  }
)

interface ButtonProps extends VariantProps<typeof buttonVariants> {
  children: React.ReactNode
  onClick?: () => void
}

export function Button({ children, variant, size, onClick }: ButtonProps) {
  return (
    <button 
      className={cn(buttonVariants({ variant, size }))}
      onClick={onClick}
    >
      {children}
    </button>
  )
}
```

**Vue**
```vue
<template>
  <button
    :class="cn(buttonVariants({ variant, size }), props.class)"
    @click="$emit('click')"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
import { cva, type VariantProps } from 'class-variance-authority'
import { cn } from '~/utils/cn'

const buttonVariants = cva(
  'inline-flex items-center justify-center rounded-md',
  {
    variants: {
      variant: {
        default: 'bg-primary text-primary-foreground',
        outline: 'border bg-background'
      },
      size: {
        default: 'h-9 px-4',
        sm: 'h-8 px-3'
      }
    }
  }
)

type ButtonVariants = VariantProps<typeof buttonVariants>

interface Props extends ButtonVariants {
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  size: 'default'
})

defineEmits<{
  click: []
}>()
</script>
```

---

## State Management

### Local State

**React**
```tsx
const [count, setCount] = useState(0)
const [user, setUser] = useState({ name: '', email: '' })

// Update
setCount(count + 1)
setUser({ ...user, name: 'John' })
```

**Vue**
```vue
<script setup>
import { ref, reactive } from 'vue'

const count = ref(0)
const user = reactive({ name: '', email: '' })

// Update
count.value++
user.name = 'John'
</script>
```

### Computed Values

**React**
```tsx
const [items] = useState([1, 2, 3, 4, 5])
const evenItems = items.filter(item => item % 2 === 0)
// Re-computes on every render
```

**Vue**
```vue
<script setup>
import { ref, computed } from 'vue'

const items = ref([1, 2, 3, 4, 5])
const evenItems = computed(() => 
  items.value.filter(item => item % 2 === 0)
)
// Only re-computes when items changes
</script>
```

---

## Props & Events

### Parent-Child Communication

**React**
```tsx
// Parent
function Parent() {
  const handleClick = (id: number) => {
    console.log('Clicked:', id)
  }
  
  return <Child title="Hello" onItemClick={handleClick} />
}

// Child
interface ChildProps {
  title: string
  onItemClick: (id: number) => void
}

function Child({ title, onItemClick }: ChildProps) {
  return (
    <div>
      <h1>{title}</h1>
      <button onClick={() => onItemClick(123)}>Click</button>
    </div>
  )
}
```

**Vue**
```vue
<!-- Parent -->
<template>
  <Child title="Hello" @item-click="handleClick" />
</template>

<script setup>
const handleClick = (id: number) => {
  console.log('Clicked:', id)
}
</script>

<!-- Child -->
<template>
  <div>
    <h1>{{ title }}</h1>
    <button @click="$emit('item-click', 123)">Click</button>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
}>()

defineEmits<{
  'item-click': [id: number]
}>()
</script>
```

---

## Conditional Rendering

**React**
```tsx
function Component() {
  const [isLoading, setIsLoading] = useState(false)
  const [user, setUser] = useState(null)
  
  return (
    <div>
      {isLoading && <Spinner />}
      
      {user ? (
        <UserProfile user={user} />
      ) : (
        <LoginForm />
      )}
      
      {user && user.isAdmin && <AdminPanel />}
    </div>
  )
}
```

**Vue**
```vue
<template>
  <div>
    <Spinner v-if="isLoading" />
    
    <UserProfile v-if="user" :user="user" />
    <LoginForm v-else />
    
    <AdminPanel v-if="user?.isAdmin" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const isLoading = ref(false)
const user = ref(null)
</script>
```

---

## Lists & Loops

**React**
```tsx
const projects = [
  { id: 1, title: "Project 1", price: "280 000" },
  { id: 2, title: "Project 2", price: "450 000" },
]

return (
  <div>
    {projects.map((project) => (
      <div key={project.id}>
        <h3>{project.title}</h3>
        <p>{project.price} TND</p>
      </div>
    ))}
  </div>
)
```

**Vue**
```vue
<template>
  <div>
    <div v-for="project in projects" :key="project.id">
      <h3>{{ project.title }}</h3>
      <p>{{ project.price }} TND</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const projects = [
  { id: 1, title: "Project 1", price: "280 000" },
  { id: 2, title: "Project 2", price: "450 000" },
]
</script>
```

---

## Forms & v-model

### Input Handling

**React**
```tsx
function SearchForm() {
  const [location, setLocation] = useState('')
  const [type, setType] = useState('')
  const [budget, setBudget] = useState('')
  
  return (
    <form>
      <input
        value={location}
        onChange={(e) => setLocation(e.target.value)}
        placeholder="Location"
      />
      <input
        value={type}
        onChange={(e) => setType(e.target.value)}
        placeholder="Type"
      />
      <input
        value={budget}
        onChange={(e) => setBudget(e.target.value)}
        placeholder="Budget"
      />
    </form>
  )
}
```

**Vue**
```vue
<template>
  <form>
    <input v-model="location" placeholder="Location" />
    <input v-model="type" placeholder="Type" />
    <input v-model="budget" placeholder="Budget" />
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const location = ref('')
const type = ref('')
const budget = ref('')
</script>
```

### Checkbox & Radio

**React**
```tsx
const [agree, setAgree] = useState(false)
const [selected, setSelected] = useState('option1')

<input
  type="checkbox"
  checked={agree}
  onChange={(e) => setAgree(e.target.checked)}
/>

<input
  type="radio"
  value="option1"
  checked={selected === 'option1'}
  onChange={(e) => setSelected(e.target.value)}
/>
```

**Vue**
```vue
<script setup>
const agree = ref(false)
const selected = ref('option1')
</script>

<template>
  <input type="checkbox" v-model="agree" />
  <input type="radio" value="option1" v-model="selected" />
</template>
```

---

## Styling

### Dynamic Classes

**React**
```tsx
const [isActive, setIsActive] = useState(false)

<div 
  className={cn(
    "px-4 py-2",
    isActive ? "bg-primary" : "bg-muted",
    "hover:shadow-lg"
  )}
>
  Content
</div>
```

**Vue**
```vue
<script setup>
const isActive = ref(false)
</script>

<template>
  <div 
    :class="[
      'px-4 py-2',
      isActive ? 'bg-primary' : 'bg-muted',
      'hover:shadow-lg'
    ]"
  >
    Content
  </div>
</template>
```

### Inline Styles

**React**
```tsx
<div style={{ 
  backgroundColor: color, 
  width: `${width}px` 
}}>
  Content
</div>
```

**Vue**
```vue
<div :style="{ 
  backgroundColor: color, 
  width: `${width}px` 
}">
  Content
</div>
```

---

## Routing

### Navigation Links

**React (with state-based routing)**
```tsx
function App() {
  const [page, setPage] = useState('home')
  
  return (
    <>
      <button onClick={() => setPage('search')}>
        Go to Search
      </button>
      
      {page === 'home' && <HomePage />}
      {page === 'search' && <SearchPage />}
    </>
  )
}
```

**Nuxt (file-based routing)**
```vue
<!-- Automatic routing based on files in pages/ -->
<!-- pages/index.vue → / -->
<!-- pages/search.vue → /search -->
<!-- pages/project/[id].vue → /project/:id -->

<template>
  <!-- Declarative -->
  <NuxtLink to="/search">Go to Search</NuxtLink>
  
  <!-- Programmatic -->
  <button @click="navigateTo('/search')">Go to Search</button>
</template>
```

### Dynamic Routes

**React**
```tsx
function ProjectDetail() {
  const { id } = useParams()
  // Fetch project with id
}
```

**Nuxt**
```vue
<!-- pages/project/[id].vue -->
<template>
  <div>Project: {{ route.params.id }}</div>
</template>

<script setup lang="ts">
const route = useRoute()
// Access: route.params.id
</script>
```

---

## Lifecycle

### Component Mount/Unmount

**React**
```tsx
useEffect(() => {
  console.log('Component mounted')
  
  return () => {
    console.log('Component unmounted')
  }
}, [])
```

**Vue**
```vue
<script setup>
import { onMounted, onUnmounted } from 'vue'

onMounted(() => {
  console.log('Component mounted')
})

onUnmounted(() => {
  console.log('Component unmounted')
})
</script>
```

### Watch/Effect

**React**
```tsx
const [search, setSearch] = useState('')

useEffect(() => {
  console.log('Search changed:', search)
  // Do something
}, [search])
```

**Vue**
```vue
<script setup>
import { ref, watch } from 'vue'

const search = ref('')

watch(search, (newValue) => {
  console.log('Search changed:', newValue)
  // Do something
})
</script>
```

---

## Common Patterns

### Header Component

**React**
```tsx
interface HeaderProps {
  onSearchClick?: () => void
  onDashboardClick?: () => void
}

export function Header({ onSearchClick, onDashboardClick }: HeaderProps) {
  return (
    <header className="sticky top-0 bg-white">
      <div className="flex items-center justify-between">
        <Logo />
        <nav>
          <button onClick={onSearchClick}>Search</button>
          <button onClick={onDashboardClick}>Dashboard</button>
        </nav>
      </div>
    </header>
  )
}
```

**Vue**
```vue
<template>
  <header class="sticky top-0 bg-white">
    <div class="flex items-center justify-between">
      <Logo />
      <nav>
        <button @click="$emit('search-click')">Search</button>
        <button @click="$emit('dashboard-click')">Dashboard</button>
      </nav>
    </div>
  </header>
</template>

<script setup lang="ts">
defineEmits<{
  'search-click': []
  'dashboard-click': []
}>()
</script>
```

### Project Card

**React**
```tsx
interface Project {
  id: number
  title: string
  price: string
}

function ProjectCard({ project, onClick }: { 
  project: Project
  onClick: (project: Project) => void 
}) {
  return (
    <div 
      onClick={() => onClick(project)}
      className="cursor-pointer hover:shadow-lg"
    >
      <h3>{project.title}</h3>
      <p>{project.price} TND</p>
    </div>
  )
}
```

**Vue**
```vue
<template>
  <div 
    @click="$emit('click', project)"
    class="cursor-pointer hover:shadow-lg"
  >
    <h3>{{ project.title }}</h3>
    <p>{{ project.price }} TND</p>
  </div>
</template>

<script setup lang="ts">
interface Project {
  id: number
  title: string
  price: string
}

defineProps<{
  project: Project
}>()

defineEmits<{
  click: [project: Project]
}>()
</script>
```

---

## Key Takeaways

### React → Vue Quick Reference

| React | Vue |
|-------|-----|
| `useState()` | `ref()` or `reactive()` |
| `useEffect()` | `watch()` or `watchEffect()` |
| `useMemo()` | `computed()` |
| `useCallback()` | Not needed (functions are stable) |
| `useRef()` | `ref()` for templates |
| `props.children` | `<slot />` |
| `onClick` | `@click` |
| `onChange` | `v-model` or `@input` |
| `className` | `class` or `:class` |
| `{condition && <Component />}` | `v-if="condition"` |
| `{items.map(...)}` | `v-for` |
| JSX | Template syntax |

### Important Notes

1. **Reactivity**: In Vue, use `.value` to access/modify `ref()` values in `<script>`, but NOT in `<template>`
2. **Events**: Vue uses kebab-case for custom events: `@item-click` not `@itemClick`
3. **Props**: Props are readonly in Vue (like React)
4. **Auto-imports**: Nuxt auto-imports components from `components/` directory
5. **File-based routing**: No need for route configuration, just create files in `pages/`

### When to Use What

- **`ref()`**: For primitive values (string, number, boolean)
- **`reactive()`**: For objects and arrays
- **`computed()`**: For derived values that depend on other reactive data
- **`watch()`**: For side effects when data changes
- **`onMounted()`**: For initialization that needs DOM access

---

## Migration Workflow

1. **Start with simple components** (Button, Badge, etc.)
2. **Copy the JSX** to `<template>`
3. **Convert syntax**: `className` → `class`, `onClick` → `@click`, etc.
4. **Move logic** to `<script setup>`
5. **Convert state**: `useState` → `ref`
6. **Convert props**: Add `defineProps<Props>()`
7. **Convert events**: Add `defineEmits<Events>()`
8. **Test the component** in isolation
9. **Move to next component**

---

**Happy Migrating! 🚀**