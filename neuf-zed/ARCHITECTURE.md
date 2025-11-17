# 🏗️ Neuf.tn Nuxt Architecture

## 📋 Overview

This document describes the new routing architecture for the Neuf.tn Nuxt application. The architecture has been refactored to follow proper REST conventions and eliminate modal-based navigation in favor of dedicated routes.

---

## 🎯 Key Architectural Principles

1. **No Modals for Pages** - Every major view has its own route
2. **RESTful Hierarchy** - URLs reflect the data hierarchy (project → listing)
3. **Listings Belong to Projects** - Every listing must be associated with a project
4. **Clean Separation** - Dashboard components are now standalone pages

---

## 🗺️ Route Structure

### **Public Routes**
```
/                           → Homepage
/search                     → Search with map & filters
/project/[id]              → Public project detail page
/developer/[name]          → Developer profile page
```

### **Dashboard Routes**
```
/dashboard                                          → Main dashboard overview
/dashboard/contacts                                 → All contact requests
/dashboard/create-project                           → Create new project wizard

/dashboard/projects/[id]/manage                     → Manage specific project
/dashboard/projects/[projectId]/listings/create     → Create listing for project
/dashboard/projects/[projectId]/listings/[id]       → View/edit listing detail
```

---

## 📂 File Structure

```
pages/
├── index.vue                              # Homepage
├── search.vue                             # Search page with map
├── project/
│   └── [id].vue                          # Project detail
├── developer/
│   └── [name].vue                        # Developer profile
└── dashboard/
    ├── index.vue                         # Dashboard overview (NEW STRUCTURE)
    ├── contacts.vue                      # Contacts page (MOVED FROM COMPONENT)
    ├── create-project.vue                # Create project wizard
    └── projects/
        ├── [id]/
        │   └── manage.vue                # Project management (MOVED FROM COMPONENT)
        └── [projectId]/
            └── listings/
                ├── create.vue            # Create listing (MOVED FROM COMPONENT)
                └── [id].vue              # Listing detail (MOVED FROM /listing)
```

---

## 🔄 Navigation Flow

### **Dashboard → Manage Project**
```javascript
// From: /dashboard
// Click "Gérer" button on project
window.location.href = `/dashboard/projects/${projectId}/manage`;
// Goes to: /dashboard/projects/1/manage
```

### **Manage Project → Create Listing**
```javascript
// From: /dashboard/projects/1/manage
// Click "Ajouter un bien" button
window.location.href = `/dashboard/projects/${projectId}/listings/create`;
// Goes to: /dashboard/projects/1/listings/create
```

### **Project Management → View Listing**
```javascript
// From: /dashboard/projects/1/manage
// Click on listing card
window.location.href = `/dashboard/projects/${projectId}/listings/${listingId}`;
// Goes to: /dashboard/projects/1/listings/5
```

### **Dashboard → Contacts**
```javascript
// From: /dashboard
// Click "Contacts" button
window.location.href = '/dashboard/contacts';
// Goes to: /dashboard/contacts
```

---

## 🔧 Key Components

### **1. Dashboard Index (`/dashboard/index.vue`)**
**Purpose:** Main dashboard overview with stats and project list

**Features:**
- Stats overview (projects, listings, leads, views)
- Quick actions (create project, view contacts)
- Project list with management links
- Direct navigation to all sub-pages

**Navigation Methods:**
```javascript
const handleManageProject = (id: number) => {
    window.location.href = `/dashboard/projects/${id}/manage`;
};

const handleCreateListing = (projectId: number) => {
    window.location.href = `/dashboard/projects/${projectId}/listings/create`;
};

const handleContactsClick = () => {
    window.location.href = "/dashboard/contacts";
};
```

---

### **2. Contacts Page (`/dashboard/contacts.vue`)**
**Route:** `/dashboard/contacts`

**Purpose:** View and manage all contact requests

**Features:**
- Filter by status (all, new, replied)
- Filter by project
- Stats cards (total, new, replied)
- Contact cards with actions
- Back button to dashboard

---

### **3. Project Management (`/dashboard/projects/[id]/manage.vue`)**
**Route:** `/dashboard/projects/:id/manage`

**Purpose:** Manage a specific project and its listings

**Features:**
- Project header with details
- Three tabs: Listings, Statistics, Settings
- Listings grid with status filtering
- Add new listing button
- Edit/delete project actions
- Navigation to contacts and listing details

**Route Params:**
```typescript
const route = useRoute();
const projectId = computed(() => parseInt(route.params.id as string));
```

---

### **4. Create Listing (`/dashboard/projects/[projectId]/listings/create.vue`)**
**Route:** `/dashboard/projects/:projectId/listings/create`

**Purpose:** Create a new listing within a specific project

**Features:**
- 3-step wizard (Details, Characteristics, Photos)
- Pre-linked to parent project
- Form validation
- Progress indicator
- Cancel returns to project management

**Route Params:**
```typescript
const route = useRoute();
const projectId = computed(() => parseInt(route.params.projectId as string));
```

**Navigation:**
```javascript
const handleCancel = () => {
    window.location.href = `/dashboard/projects/${projectId.value}/manage`;
};

const handleComplete = () => {
    // Save listing...
    window.location.href = `/dashboard/projects/${projectId.value}/manage`;
};
```

---

### **5. Listing Detail (`/dashboard/projects/[projectId]/listings/[id].vue`)**
**Route:** `/dashboard/projects/:projectId/listings/:id`

**Purpose:** View and manage a specific listing

**Features:**
- Image gallery with thumbnails
- Full listing details
- Stats (views, contacts)
- Edit/delete/share actions
- Back to project management
- Link to all contacts

**Route Params:**
```typescript
const route = useRoute();
const projectId = computed(() => parseInt(route.params.projectId as string));
const listingId = computed(() => parseInt(route.params.id as string));
```

---

## 🔗 URL Hierarchy Examples

```
Example 1: View listing #5 in project #1
└─ /dashboard/projects/1/listings/5

Example 2: Create listing in project #2
└─ /dashboard/projects/2/listings/create

Example 3: Manage project #3
└─ /dashboard/projects/3/manage
```

---

## ✅ Benefits of New Architecture

### **1. RESTful URLs**
- URLs are semantic and bookmarkable
- Clear hierarchy: project → listings
- Easy to understand and maintain

### **2. Proper Browser Navigation**
- Back button works correctly
- Can refresh any page
- Can share direct links

### **3. No Modal State Management**
- No complex modal state tracking
- No `showProjectManagement` flags
- Simpler component logic

### **4. Better UX**
- Each page has its own URL
- Can open multiple tabs
- Browser history works properly

### **5. Easier Testing**
- Each page can be tested independently
- No need to trigger modals
- Simpler E2E tests

---

## 🚫 What Was Removed

### **Old Modal-Based Approach (DELETED)**
```vue
<!-- OLD: Don't use this pattern -->
<div v-if="showProjectManagement" class="fixed inset-0">
    <ProjectManagement :projectId="selectedProjectId" @back="closeModal" />
</div>
```

### **Old Components (MOVED TO PAGES)**
- `components/ProjectManagement.vue` → `pages/dashboard/projects/[id]/manage.vue`
- `components/CreateListingFlow.vue` → `pages/dashboard/projects/[projectId]/listings/create.vue`
- `components/ContactsView.vue` → `pages/dashboard/contacts.vue`

### **Old Route**
- `/listing/[id]` → `/dashboard/projects/[projectId]/listings/[id]`

---

## 📊 Data Flow

### **Project → Listings Relationship**
```typescript
// Every listing MUST have a projectId
interface Listing {
    id: number;
    projectId: number;  // REQUIRED - links to parent project
    type: string;
    price: string;
    // ... other fields
}
```

### **Navigation Context**
```typescript
// Always know which project we're in
const route = useRoute();
const projectId = route.params.projectId;  // From URL

// Use it for API calls
const listing = await fetchListing(projectId, listingId);
```

---

## 🎨 UI Patterns

### **Breadcrumb Pattern**
```
Dashboard → Projects → Les Jardins de Carthage → Listings → Appartement S+3
```

### **Back Button Pattern**
Every sub-page has a back button:
```vue
<button @click="goBack">
    <ArrowLeft class="w-5 h-5" />
    <span>Retour au tableau de bord</span>
</button>
```

### **Quick Actions Pattern**
Main pages have quick action cards:
```vue
<button class="action-card" @click="navigateToAction">
    <Icon />
    <div>
        <p class="title">Action Title</p>
        <p class="description">Action description</p>
    </div>
    <ArrowRight />
</button>
```

---

## 🔐 Access Control (Future)

When authentication is added:
```typescript
// Middleware to protect dashboard routes
definePageMeta({
    middleware: 'auth'  // All /dashboard/* routes
});

// Check project ownership
const canManageProject = (userId: number, projectId: number) => {
    // Verify user owns this project
};
```

---

## 📈 Future Enhancements

### **Phase 2:**
1. Add edit functionality for listings
2. Implement draft/publish workflow
3. Add real-time updates
4. Implement search within project listings

### **Phase 3:**
1. Add listing templates
2. Bulk operations (duplicate, delete)
3. Advanced analytics
4. Export functionality

---

## 🛠️ Migration Notes

### **If You Were Using Modal Pattern:**
**BEFORE:**
```javascript
const showProjectManagement = ref(false);
showProjectManagement.value = true;
```

**AFTER:**
```javascript
window.location.href = `/dashboard/projects/${id}/manage`;
```

### **If You Were Using Old Listing Route:**
**BEFORE:**
```
/listing/5
```

**AFTER:**
```
/dashboard/projects/1/listings/5
```

---

## 📞 Support

For questions about the architecture:
1. Check this document first
2. Review the code in `pages/dashboard/`
3. Test the navigation flows
4. Refer to Nuxt routing documentation

---

## 📝 Summary

✅ **Proper REST hierarchy** - `/dashboard/projects/:id/listings/:id`
✅ **No modals for pages** - Everything is a real route
✅ **Clear data ownership** - Listings belong to projects
✅ **Better UX** - Browser navigation works
✅ **Easier maintenance** - Simpler code structure

---

*Last Updated: 2024*
*Architecture Version: 2.0*