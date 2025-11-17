# 🔄 REACT TO NUXT - QUICK TRANSLATION GUIDE
## For Jumping Between Projects

---

## 📁 PROJECT LOCATIONS

**React Original:**
```
D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\src
```

**Nuxt Clone:**
```
D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed
```

---

## 🗺️ FILE MAPPING (React → Nuxt)

### **Pages/Routes**

| React | Nuxt | Status |
|-------|------|--------|
| `App.tsx` (homepage) | `pages/index.vue` | ✅ |
| `SearchPage.tsx` | `pages/search.vue` | ✅ |
| `ProjectDetail.tsx` | `pages/project/[id].vue` | ✅ |
| `DeveloperDetail.tsx` | `pages/developer/[name].vue` | ✅ |
| `DeveloperDashboard.tsx` | `pages/dashboard/index.vue` | ✅ |
| `CreateProjectFlow.tsx` | `pages/dashboard/create-project.vue` | ✅ |
| `ListingDetail.tsx` | `pages/listing/[id].vue` | ❌ |

### **Components**

| React | Nuxt | Status |
|-------|------|--------|
| `Header.tsx` | `components/Header.vue` | ✅ |
| `Footer.tsx` | `components/Footer.vue` | ✅ |
| `SearchBar.tsx` | `components/SearchBar.vue` | ✅ |
| `HeroSection.tsx` | `components/HeroSection.vue` | ✅ |
| `ProjectsSection.tsx` | `components/ProjectsSection.vue` | ✅ |
| `AboutSection.tsx` | `components/AboutSection.vue` | ✅ |
| `BenefitsSection.tsx` | `components/BenefitsSection.vue` | ✅ |
| `DevelopersCTA.tsx` | `components/DevelopersCTA.vue` | ✅ |
| `DeveloperLogo.tsx` | `components/DeveloperLogo.vue` | ✅ |
| `ContactsView.tsx` | `components/ContactsView.vue` | ✅ |
| `CreateListingFlow.tsx` | `components/CreateListingFlow.vue` | ✅ |
| `ProjectManagement.tsx` | `components/ProjectManagement.vue` | ✅ |
| `LeadsManagement.tsx` | - | ⚠️ |
| `OnboardingGuide.tsx` | - | ⚠️ |
| `DevelopersCTAOptions.tsx` | - | ⚠️ |
| `SuccessToast.tsx` | `components/ui/Toast.vue` | ✅ |

### **UI Components**

| React (`ui/`) | Nuxt (`ui/`) | Status |
|---------------|--------------|--------|
| `button.tsx` | `Button.vue` | ✅ |
| `badge.tsx` | `Badge.vue` | ✅ |
| `input.tsx` | `Input.vue` | ✅ |
| `textarea.tsx` | `Textarea.vue` | ✅ |
| `select.tsx` | `Select.vue` | ✅ |
| `checkbox.tsx` | `Checkbox.vue` | ✅ |
| `card.tsx` | `Card.vue` | ✅ |
| All others | All ported | ✅ |

---

## 🔀 SYNTAX TRANSLATION

### **Component Structure**

#### React (TypeScript)
```tsx
import { useState } from "react";
import { Button } from "./ui/button";

interface MyComponentProps {
  title: string;
  onSave: (data: any) => void;
}

export function MyComponent({ title, onSave }: MyComponentProps) {
  const [count, setCount] = useState(0);

  const handleClick = () => {
    setCount(count + 1);
    onSave({ count: count + 1 });
  };

  return (
    <div className="p-4">
      <h1>{title}</h1>
      <p>Count: {count}</p>
      <Button onClick={handleClick}>Click me</Button>
    </div>
  );
}
```

#### Nuxt (Vue 3 Composition API)
```vue
<script setup lang="ts">
import { ref } from 'vue'

interface Props {
  title: string
}

const props = defineProps<Props>()

const emit = defineEmits<{
  save: [data: any]
}>()

const count = ref(0)

const handleClick = () => {
  count.value++
  emit('save', { count: count.value })
}
</script>

<template>
  <div class="p-4">
    <h1>{{ title }}</h1>
    <p>Count: {{ count }}</p>
    <Button @click="handleClick">Click me</Button>
  </div>
</template>
```

---

### **State Management**

#### React (Local State)
```tsx
const [items, setItems] = useState<Item[]>([]);
const [loading, setLoading] = useState(false);
const [error, setError] = useState<string | null>(null);

const fetchItems = async () => {
  setLoading(true);
  try {
    const response = await fetch('/api/items');
    const data = await response.json();
    setItems(data);
  } catch (err) {
    setError(err.message);
  } finally {
    setLoading(false);
  }
};
```

#### Nuxt (Pinia Store)
```typescript
// stores/items.ts
import { defineStore } from 'pinia'

export const useItemsStore = defineStore('items', {
  state: () => ({
    items: [] as Item[],
    isLoading: false,
    error: null as string | null
  }),

  actions: {
    async fetchItems() {
      this.isLoading = true
      this.error = null
      try {
        const response = await fetch('/api/items')
        this.items = await response.json()
      } catch (err) {
        this.error = err.message
      } finally {
        this.isLoading = false
      }
    }
  }
})

// In component
const itemsStore = useItemsStore()
await itemsStore.fetchItems()
```

---

### **Navigation**

#### React
```tsx
import { useNavigate } from 'react-router-dom';

const navigate = useNavigate();

// Navigate programmatically
navigate('/projects');
navigate(`/project/${projectId}`);
navigate(-1); // Go back

// Link component
<Link to="/projects">View Projects</Link>
```

#### Nuxt
```vue
<script setup>
// Navigate programmatically (auto-imported)
navigateTo('/projects')
navigateTo(`/project/${projectId}`)
navigateTo(-1) // Go back

// Or using useRouter
const router = useRouter()
router.push('/projects')
</script>

<template>
  <!-- NuxtLink component -->
  <NuxtLink to="/projects">View Projects</NuxtLink>
</template>
```

---

### **Conditional Rendering**

#### React
```tsx
{loading && <LoadingSpinner />}
{error && <ErrorMessage error={error} />}
{items.length > 0 ? (
  <ItemsList items={items} />
) : (
  <EmptyState />
)}
```

#### Nuxt
```vue
<template>
  <LoadingSpinner v-if="loading" />
  <ErrorMessage v-if="error" :error="error" />
  <ItemsList v-if="items.length > 0" :items="items" />
  <EmptyState v-else />
</template>
```

---

### **List Rendering**

#### React
```tsx
{items.map((item, index) => (
  <div key={item.id}>
    <h3>{item.title}</h3>
    <p>{item.description}</p>
  </div>
))}
```

#### Nuxt
```vue
<template>
  <div v-for="(item, index) in items" :key="item.id">
    <h3>{{ item.title }}</h3>
    <p>{{ item.description }}</p>
  </div>
</template>
```

---

### **Event Handling**

#### React
```tsx
<Button onClick={handleClick}>Click</Button>
<Input onChange={(e) => setValue(e.target.value)} />
<form onSubmit={handleSubmit}>...</form>
```

#### Nuxt
```vue
<template>
  <Button @click="handleClick">Click</Button>
  <Input @input="setValue($event.target.value)" />
  <!-- Or with v-model -->
  <Input v-model="value" />
  <form @submit.prevent="handleSubmit">...</form>
</template>
```

---

### **Props & Emits**

#### React
```tsx
interface Props {
  title: string;
  onSave?: (data: any) => void;
  onCancel?: () => void;
}

export function MyComponent({ title, onSave, onCancel }: Props) {
  const handleSave = () => {
    if (onSave) onSave({ data: 'test' });
  };

  return (
    <div>
      <h1>{title}</h1>
      <Button onClick={handleSave}>Save</Button>
      <Button onClick={onCancel}>Cancel</Button>
    </div>
  );
}

// Usage
<MyComponent 
  title="Edit" 
  onSave={(data) => console.log(data)}
  onCancel={() => navigate(-1)}
/>
```

#### Nuxt
```vue
<script setup lang="ts">
interface Props {
  title: string
}

const props = defineProps<Props>()

const emit = defineEmits<{
  save: [data: any]
  cancel: []
}>()

const handleSave = () => {
  emit('save', { data: 'test' })
}
</script>

<template>
  <div>
    <h1>{{ title }}</h1>
    <Button @click="handleSave">Save</Button>
    <Button @click="emit('cancel')">Cancel</Button>
  </div>
</template>

<!-- Usage -->
<MyComponent 
  title="Edit" 
  @save="(data) => console.log(data)"
  @cancel="navigateTo(-1)"
/>
```

---

### **Computed Values**

#### React
```tsx
const filteredItems = useMemo(() => {
  return items.filter(item => item.status === 'active');
}, [items]);

const totalPrice = useMemo(() => {
  return items.reduce((sum, item) => sum + item.price, 0);
}, [items]);
```

#### Nuxt
```vue
<script setup lang="ts">
const filteredItems = computed(() => {
  return items.value.filter(item => item.status === 'active')
})

const totalPrice = computed(() => {
  return items.value.reduce((sum, item) => sum + item.price, 0)
})
</script>
```

---

### **Effects / Lifecycle**

#### React
```tsx
import { useEffect } from 'react';

useEffect(() => {
  // Component mounted
  fetchData();

  return () => {
    // Component unmounted (cleanup)
    cleanup();
  };
}, []); // Run once on mount

useEffect(() => {
  // Run when selectedId changes
  fetchItem(selectedId);
}, [selectedId]);
```

#### Nuxt
```vue
<script setup lang="ts">
import { onMounted, onUnmounted, watch } from 'vue'

onMounted(() => {
  // Component mounted
  fetchData()
})

onUnmounted(() => {
  // Component unmounted (cleanup)
  cleanup()
})

// Watch for changes
watch(() => selectedId.value, (newId) => {
  fetchItem(newId)
})
</script>
```

---

### **Forms & Validation**

#### React
```tsx
const [formData, setFormData] = useState({
  email: '',
  name: ''
});

const [errors, setErrors] = useState({});

const validate = () => {
  const newErrors = {};
  if (!formData.email) newErrors.email = 'Required';
  if (!formData.name) newErrors.name = 'Required';
  setErrors(newErrors);
  return Object.keys(newErrors).length === 0;
};

const handleSubmit = (e) => {
  e.preventDefault();
  if (validate()) {
    // Submit
  }
};

return (
  <form onSubmit={handleSubmit}>
    <Input 
      value={formData.email}
      onChange={(e) => setFormData({...formData, email: e.target.value})}
    />
    {errors.email && <span>{errors.email}</span>}
  </form>
);
```

#### Nuxt (with composable)
```vue
<script setup lang="ts">
import { useFormValidation } from '~/composables/useFormValidation'

const { formData, errors, validate, isValid } = useFormValidation(
  { email: '', name: '' },
  {
    email: commonRules.email(),
    name: commonRules.required()
  }
)

const handleSubmit = async () => {
  if (validate()) {
    // Submit
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit">
    <Input v-model="formData.email" />
    <span v-if="errors.email" class="text-red-500">{{ errors.email }}</span>
  </form>
</template>
```

---

### **Styling**

#### React
```tsx
// Tailwind classes (same)
<div className="p-4 bg-white rounded-lg shadow-md">
  <h1 className="text-2xl font-semibold mb-4">Title</h1>
</div>

// Dynamic classes
<div className={`p-4 ${isActive ? 'bg-primary' : 'bg-muted'}`}>
```

#### Nuxt
```vue
<template>
  <!-- Tailwind classes (same) -->
  <div class="p-4 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold mb-4">Title</h1>
  </div>

  <!-- Dynamic classes -->
  <div :class="['p-4', isActive ? 'bg-primary' : 'bg-muted']">
  <!-- Or -->
  <div :class="{ 'bg-primary': isActive, 'bg-muted': !isActive }" class="p-4">
</template>
```

---

## 🎯 COMMON PATTERNS

### **Modal / Overlay**

#### React
```tsx
const [isOpen, setIsOpen] = useState(false);

return (
  <>
    <Button onClick={() => setIsOpen(true)}>Open Modal</Button>
    
    {isOpen && (
      <div className="fixed inset-0 bg-black/50 z-50">
        <div className="bg-white rounded-lg p-6">
          <h2>Modal Content</h2>
          <Button onClick={() => setIsOpen(false)}>Close</Button>
        </div>
      </div>
    )}
  </>
);
```

#### Nuxt
```vue
<script setup lang="ts">
const isOpen = ref(false)
</script>

<template>
  <Button @click="isOpen = true">Open Modal</Button>
  
  <div v-if="isOpen" class="fixed inset-0 bg-black/50 z-50">
    <div class="bg-white rounded-lg p-6">
      <h2>Modal Content</h2>
      <Button @click="isOpen = false">Close</Button>
    </div>
  </div>
</template>
```

---

### **Loading State**

#### React
```tsx
const [data, setData] = useState(null);
const [loading, setLoading] = useState(true);

useEffect(() => {
  fetchData().then(result => {
    setData(result);
    setLoading(false);
  });
}, []);

if (loading) return <LoadingSpinner />;
return <DataView data={data} />;
```

#### Nuxt
```vue
<script setup lang="ts">
const data = ref(null)
const loading = ref(true)

onMounted(async () => {
  const result = await fetchData()
  data.value = result
  loading.value = false
})
</script>

<template>
  <LoadingSpinner v-if="loading" />
  <DataView v-else :data="data" />
</template>
```

---

### **API Calls**

#### React
```tsx
const fetchProject = async (id: number) => {
  try {
    const response = await fetch(`/api/projects/${id}`);
    if (!response.ok) throw new Error('Failed');
    return await response.json();
  } catch (error) {
    console.error(error);
  }
};
```

#### Nuxt (with composable)
```typescript
// composables/useApi.ts
export const useApi = () => {
  const fetchProject = async (id: number) => {
    try {
      const data = await $fetch(`/api/projects/${id}`)
      return data
    } catch (error) {
      console.error(error)
    }
  }

  return { fetchProject }
}

// In component
const { fetchProject } = useApi()
const project = await fetchProject(id)
```

---

## 🔧 TOOLS & IMPORTS

### **React**
```tsx
import { useState, useEffect, useMemo } from 'react'
import { useNavigate, Link } from 'react-router-dom'
import { Button } from './ui/button'
```

### **Nuxt (Auto-imported)**
```vue
<script setup lang="ts">
// No imports needed! Auto-imported:
// - ref, computed, watch, onMounted, etc.
// - navigateTo, useRoute, useRouter
// - Components (Button, etc.)
// - Stores (useProjectsStore, etc.)
// - Composables (useFormValidation, etc.)
</script>
```

---

## 📦 PACKAGE EQUIVALENTS

| React | Nuxt | Notes |
|-------|------|-------|
| `react-router-dom` | Built-in (file-based) | Auto routing |
| `react-query` | `useFetch` / `$fetch` | Built-in data fetching |
| `zustand` / `redux` | Pinia | State management |
| `styled-components` | - | Use Tailwind |
| `framer-motion` | - | Use CSS transitions |
| `react-hook-form` | Custom composable | `useFormValidation` |
| `axios` | `$fetch` / `ofetch` | Built-in |

---

## 🎨 DESIGN SYSTEM

### **Colors (Same in both)**
```
Primary:    #ff385c
Background: #ffffff
Foreground: #222222
Muted:      #f7f7f7
Border:     #dddddd
```

### **Tailwind Classes (Same)**
```
Spacing:  p-4, px-6, py-8, gap-4, space-y-4
Text:     text-sm, text-base, text-lg, text-2xl
Colors:   bg-primary, text-foreground, border-border
Radius:   rounded-lg, rounded-xl, rounded-full
Shadow:   shadow-sm, shadow-md, shadow-lg, shadow-xl
```

---

## 🚀 QUICK REFERENCE

### **When to use what:**

**Use React version to:**
- Check original design
- See feature implementation
- Get mock data structure
- Understand business logic
- Copy component structure

**Use Nuxt version to:**
- Build new features
- Run the app
- Test functionality
- Deploy to production
- Add backend integration

---

## 💡 PRO TIPS

### **Converting React to Nuxt:**

1. **Props:** 
   - React: `{ prop1, prop2 }: Props`
   - Nuxt: `defineProps<Props>()`

2. **Events:**
   - React: `onSave`, `onCancel`
   - Nuxt: `@save`, `@cancel` + `defineEmits()`

3. **State:**
   - React: `useState(value)`
   - Nuxt: `ref(value)` + `.value`

4. **Effects:**
   - React: `useEffect(() => {})`
   - Nuxt: `onMounted(() => {})`

5. **Navigation:**
   - React: `navigate('/path')`
   - Nuxt: `navigateTo('/path')`

6. **Conditional:**
   - React: `{condition && <Component />}`
   - Nuxt: `<Component v-if="condition" />`

7. **Loop:**
   - React: `{items.map(item => ...)}`
   - Nuxt: `<div v-for="item in items" :key="item.id">`

8. **Class:**
   - React: `className="p-4"`
   - Nuxt: `class="p-4"`

9. **Dynamic Class:**
   - React: `` className={`p-4 ${active ? 'bg-primary' : ''}` ``
   - Nuxt: `:class="['p-4', { 'bg-primary': active }]"`

10. **Two-way Binding:**
    - React: `value={x}` + `onChange={e => setX(e.target.value)}`
    - Nuxt: `v-model="x"`

---

## 📚 USEFUL COMMANDS

### **React Project**
```bash
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn"
npm run dev
# Opens on port 5173
```

### **Nuxt Project**
```bash
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"
npm run dev
# Opens on port 3000
```

---

## 🎯 READY TO CODE!

Use this guide whenever you need to:
1. 🔍 Check how React does something
2. 🔄 Convert React code to Nuxt
3. 🎨 Match design exactly
4. 🐛 Compare implementations
5. 📝 Understand patterns

**Happy coding! 🚀**
