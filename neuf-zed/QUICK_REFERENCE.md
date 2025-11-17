# 🚀 Quick Reference Guide - Navigation Patterns

## 📍 Current Location
You are in: **Neuf.tn Dashboard - Route-Based Architecture v2.0**

---

## 🗺️ Route Cheat Sheet

### **Main Routes**
```
/dashboard                                          → Dashboard home
/dashboard/contacts                                 → All contacts
/dashboard/create-project                           → New project wizard
/dashboard/projects/:id/manage                      → Manage project
/dashboard/projects/:projectId/listings/create      → New listing
/dashboard/projects/:projectId/listings/:id         → Listing detail
```

---

## 🔗 Navigation Code Snippets

### **From Dashboard to Project Management**
```javascript
const goToProjectManagement = (projectId: number) => {
    window.location.href = `/dashboard/projects/${projectId}/manage`;
};
```

### **From Dashboard to Contacts**
```javascript
const goToContacts = () => {
    window.location.href = '/dashboard/contacts';
};
```

### **From Project Management to Create Listing**
```javascript
const createListing = (projectId: number) => {
    window.location.href = `/dashboard/projects/${projectId}/listings/create`;
};
```

### **From Project Management to Listing Detail**
```javascript
const viewListing = (projectId: number, listingId: number) => {
    window.location.href = `/dashboard/projects/${projectId}/listings/${listingId}`;
};
```

### **Back to Dashboard**
```javascript
const backToDashboard = () => {
    window.location.href = '/dashboard';
};
```

### **Back to Project Management**
```javascript
const backToProject = (projectId: number) => {
    window.location.href = `/dashboard/projects/${projectId}/manage`;
};
```

---

## 📊 Getting Route Parameters

### **In Project Management Page**
```typescript
const route = useRoute();
const projectId = computed(() => parseInt(route.params.id as string));

// Use it
console.log(`Managing project: ${projectId.value}`);
```

### **In Create Listing Page**
```typescript
const route = useRoute();
const projectId = computed(() => parseInt(route.params.projectId as string));

// Use it
console.log(`Creating listing for project: ${projectId.value}`);
```

### **In Listing Detail Page**
```typescript
const route = useRoute();
const projectId = computed(() => parseInt(route.params.projectId as string));
const listingId = computed(() => parseInt(route.params.id as string));

// Use it
console.log(`Viewing listing ${listingId.value} in project ${projectId.value}`);
```

---

## 🎨 Standard UI Patterns

### **Back Button**
```vue
<button
    @click="goBack"
    class="flex items-center gap-2 text-muted-foreground hover:text-foreground transition-colors"
>
    <ArrowLeft class="w-5 h-5" />
    <span class="font-medium">Retour</span>
</button>

<script setup>
const goBack = () => {
    window.location.href = '/dashboard';
};
</script>
```

### **Action Card**
```vue
<button
    @click="handleAction"
    class="flex items-center gap-4 p-4 rounded-xl border-2 border-border hover:border-primary hover:bg-primary/5 transition-all text-left group"
>
    <div class="w-12 h-12 rounded-full bg-primary/10 group-hover:bg-primary/20 flex items-center justify-center transition-colors flex-shrink-0">
        <Icon class="w-6 h-6 text-primary" />
    </div>
    <div class="flex-1 min-w-0">
        <p class="font-semibold text-foreground mb-1">Action Title</p>
        <p class="text-sm text-muted-foreground">Action description</p>
    </div>
    <ArrowRight class="w-5 h-5 text-muted-foreground group-hover:text-primary transition-colors flex-shrink-0" />
</button>
```

### **Stats Card**
```vue
<div class="bg-white rounded-xl p-6 border-2 border-border hover:border-primary transition-all">
    <div class="flex items-center justify-between mb-2">
        <p class="text-sm text-muted-foreground">Label</p>
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
            <Icon class="w-5 h-5 text-primary" />
        </div>
    </div>
    <p class="text-3xl font-bold text-foreground mb-1">{{ value }}</p>
    <p class="text-sm text-muted-foreground">Description</p>
</div>
```

---

## 🔐 Data Structure

### **Listing Object**
```typescript
interface Listing {
    id: number;
    projectId: number;        // ⚠️ REQUIRED - Always link to parent
    type: string;
    surface: number;
    price: string;
    bedrooms: number;
    bathrooms: number;
    status: 'available' | 'reserved' | 'sold';
    images: string[];
    // ... other fields
}
```

### **Project Object**
```typescript
interface Project {
    id: number;
    name: string;
    location: string;
    image: string;
    status: 'active' | 'draft';
    listingsCount: number;
    leadsCount: number;
    views: number;
}
```

---

## ⚠️ Common Mistakes to Avoid

### ❌ **DON'T: Use Modal Pattern**
```javascript
// WRONG
const showModal = ref(false);
const openModal = () => {
    showModal.value = true;
};
```

### ✅ **DO: Navigate to Route**
```javascript
// CORRECT
const openPage = (id: number) => {
    window.location.href = `/dashboard/projects/${id}/manage`;
};
```

---

### ❌ **DON'T: Forget Project Association**
```javascript
// WRONG - Listing without project
window.location.href = `/listing/${listingId}`;
```

### ✅ **DO: Include Project in URL**
```javascript
// CORRECT - Listing with project context
window.location.href = `/dashboard/projects/${projectId}/listings/${listingId}`;
```

---

### ❌ **DON'T: Use Old Routes**
```javascript
// WRONG - Old route
window.location.href = `/listing/5`;
```

### ✅ **DO: Use New Hierarchy**
```javascript
// CORRECT - New route
window.location.href = `/dashboard/projects/1/listings/5`;
```

---

## 🎯 Quick Actions

| Action | Code |
|--------|------|
| Go to Dashboard | `window.location.href = '/dashboard'` |
| View Contacts | `window.location.href = '/dashboard/contacts'` |
| Create Project | `window.location.href = '/dashboard/create-project'` |
| Manage Project | `window.location.href = '/dashboard/projects/${id}/manage'` |
| Create Listing | `window.location.href = '/dashboard/projects/${id}/listings/create'` |
| View Listing | `window.location.href = '/dashboard/projects/${projectId}/listings/${id}'` |

---

## 📱 Responsive Patterns

### **Mobile Menu Toggle**
```vue
<div class="lg:hidden">
    <button @click="toggleMenu">
        <Menu class="w-6 h-6" />
    </button>
</div>
```

### **Responsive Grid**
```vue
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Cards -->
</div>
```

### **Responsive Flex**
```vue
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
    <!-- Content -->
</div>
```

---

## 🎨 Design System Quick Reference

### **Colors**
```javascript
primary: '#ff385c'        // Main brand color
foreground: '#222222'     // Text
muted-foreground: '#717171' // Secondary text
border: '#dddddd'         // Borders
background: '#ffffff'     // White
muted: '#f7f7f7'          // Light gray
```

### **Spacing**
```javascript
Container: max-w-[1760px] mx-auto px-6 lg:px-20
Sections: py-20 lg:py-32
Cards: p-6 lg:p-8
Gaps: gap-4 lg:gap-6
```

### **Border Radius**
```javascript
Buttons: rounded-xl (12px)
Cards: rounded-xl (12px)
Inputs: rounded-xl (12px)
Badges: rounded-full
```

### **Font Sizes**
```javascript
H1: text-3xl lg:text-4xl (1.875rem - 2.25rem)
H2: text-2xl lg:text-3xl (1.5rem - 1.875rem)
Body: text-base (1rem)
Small: text-sm (0.875rem)
```

---

## 🔍 Debugging Tips

### **Check Current Route**
```javascript
const route = useRoute();
console.log('Current path:', route.path);
console.log('Params:', route.params);
```

### **Verify Project ID**
```javascript
const projectId = computed(() => parseInt(route.params.projectId as string));
console.log('Project ID:', projectId.value);
console.log('Is valid:', !isNaN(projectId.value));
```

### **Test Navigation**
```javascript
const testNavigation = () => {
    const url = `/dashboard/projects/1/manage`;
    console.log('Navigating to:', url);
    window.location.href = url;
};
```

---

## 📚 More Resources

- **Full Architecture:** See `ARCHITECTURE.md`
- **Migration Details:** See `MIGRATION_SUMMARY.md`
- **Nuxt Routing:** https://nuxt.com/docs/guide/directory-structure/pages
- **Vue Router:** https://router.vuejs.org/

---

## 💡 Pro Tips

1. **Always include `projectId` in listing URLs**
2. **Use `window.location.href` for navigation** (or implement proper `navigateTo`)
3. **Extract route params with `useRoute()` in pages**
4. **Add back buttons on all sub-pages**
5. **Test browser back/forward functionality**
6. **Keep URLs RESTful and semantic**

---

## ✅ Checklist for New Pages

When creating a new page in the dashboard:

- [ ] Create file in correct directory structure
- [ ] Add route parameters if needed
- [ ] Implement back button
- [ ] Add proper page title
- [ ] Follow design system (colors, spacing, etc.)
- [ ] Test navigation to/from page
- [ ] Test browser back button
- [ ] Add to this quick reference if needed

---

## 🎓 Examples

### **Example 1: Add "View Analytics" Button**
```vue
<Button @click="viewAnalytics">
    <TrendingUp class="w-4 h-4 mr-2" />
    Voir les statistiques
</Button>

<script setup>
const viewAnalytics = () => {
    // Navigate to analytics page (create if needed)
    window.location.href = '/dashboard/analytics';
};
</script>
```

### **Example 2: Project Card with Actions**
```vue
<div class="project-card" v-for="project in projects" :key="project.id">
    <h3>{{ project.name }}</h3>
    <div class="actions">
        <Button @click="manageProject(project.id)">Gérer</Button>
        <Button @click="addListing(project.id)">Ajouter un bien</Button>
    </div>
</div>

<script setup>
const manageProject = (id: number) => {
    window.location.href = `/dashboard/projects/${id}/manage`;
};

const addListing = (id: number) => {
    window.location.href = `/dashboard/projects/${id}/listings/create`;
};
</script>
```

---

**Last Updated:** 2024  
**Version:** 2.0  
**Status:** ✅ Production Ready