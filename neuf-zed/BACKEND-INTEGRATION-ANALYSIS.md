# Backend-Frontend Integration Analysis

## 📊 Backend API Overview

### Base URL Structure
- **API Base**: `/api`
- **Authentication**: Laravel Sanctum (Token-based)
- **Main Routes**: Auth, Promoter (protected), Admin (protected), Public

---

## 🎯 Backend API Endpoints Available

### 1. **Authentication** ✅
```
POST /api/register
POST /api/login
POST /api/logout (auth required)
GET  /api/user (auth required)
POST /api/user/profile-picture (auth required)
```

### 2. **Public Routes** ✅
```
GET  /api/projects (list with filters)
GET  /api/projects/{id} (by ID)
GET  /api/projects/{slug} (by slug)
GET  /api/projects/{id}/properties
GET  /api/properties (list with filters)
GET  /api/properties/{id}
GET  /api/properties/{id}/similar
GET  /api/promoters (list)
GET  /api/promoters/{id}
GET  /api/promoters/{id}/projects
GET  /api/search
GET  /api/cities
GET  /api/property-types
GET  /api/filters/options
```

### 3. **Promoter Dashboard** (Protected - auth:sanctum + promoter role) ✅
```
GET    /api/promoter/profile
PUT    /api/promoter/profile
POST   /api/promoter/logo
GET    /api/promoter/dashboard
GET    /api/promoter/stats
GET    /api/promoter/projects
POST   /api/promoter/projects
GET    /api/promoter/projects/{id}
POST   /api/promoter/projects/{id} (update with files)
DELETE /api/promoter/projects/{id}
PATCH  /api/promoter/projects/{id}/publish
POST   /api/promoter/projects/{id}/upload-brochure
POST   /api/promoter/projects/{id}/upload-gallery
POST   /api/promoter/projects/{id}/upload-floor-plans
DELETE /api/promoter/projects/{id}/images/{imageUrl}
```

### 4. **Properties (Promoter)** ✅
```
GET    /api/promoter/properties
POST   /api/promoter/projects/{projectId}/properties
GET    /api/promoter/properties/{id}
POST   /api/promoter/properties/{id} (update with files)
DELETE /api/promoter/properties/{id}
PATCH  /api/promoter/properties/{id}/availability
POST   /api/promoter/properties/{id}/upload-images
DELETE /api/promoter/properties/{id}/images/{imageUrl}
```

### 5. **Leads Management (Promoter)** ✅
```
GET    /api/promoter/leads
GET    /api/promoter/leads/stats
GET    /api/promoter/leads/{id}
PATCH  /api/promoter/leads/{id}/status
PATCH  /api/promoter/leads/{id}/priority
PUT    /api/promoter/leads/{id}/notes
DELETE /api/promoter/leads/{id}
POST   /api/leads (PUBLIC - form submission)
```

### 6. **Admin Routes** ✅
```
GET   /api/admin/dashboard
GET   /api/admin/promoters
GET   /api/admin/promoters/{id}
PATCH /api/admin/promoters/{id}/verify
GET   /api/admin/projects
GET   /api/admin/projects/{id}
PATCH /api/admin/projects/{id}/publish
PATCH /api/admin/projects/{id}/unpublish
GET   /api/admin/properties
PATCH /api/admin/properties/{id}/validate
```

---

## 📦 Data Models

### **Project Model**
```javascript
{
  id, promoter_id, name, slug, description, reference,
  city, district, address, latitude, longitude,
  status, // 'planning', 'under_construction', 'near_completion', 'completed'
  launch_date, expected_delivery,
  construction_progress, construction_progress_percentage,
  total_units, available_units, sold_units, reserved_units,
  total_floors, buildings_count,
  starting_price, average_price_per_sqm, price_range_min, price_range_max,
  amenities[], nearby_facilities[], tags[],
  images[], cover_image, floor_plans[], virtual_tours[],
  is_published, is_featured, published_at,
  meta_title, meta_description, seo_keywords[]
}
```

### **Property Model** (called "Listing" in frontend)
```javascript
{
  id, project_id, user_id, title, description,
  price, type, surface, reference,
  city, district, address, latitude, longitude,
  rooms, bedrooms, bathrooms, floor, total_floors,
  parking, elevator, terrace, garden,
  features[], images[],
  availability_status, // 'available', 'reserved', 'sold'
  validated, published_date,
  is_vefa, delivery_date, construction_progress
}
```

### **Promoter Model**
```javascript
{
  id, user_id, company_name, logo, description, website,
  primary_phone, additional_phones[], primary_email, additional_emails[],
  license_number, established_date, employee_count,
  specializations[], headquarters_address, headquarters_city,
  branch_offices[], total_projects, completed_projects, active_projects,
  rating, review_count, verified, featured, verified_at
}
```

### **Lead Model**
```javascript
{
  id, project_id, property_id, promoter_id,
  first_name, last_name, email, phone, message,
  status, priority, notes, source, user_agent, ip_address
}
```

---

## ✅ Frontend Pages WITH Backend APIs

| Frontend Page | Backend API | Status |
|--------------|-------------|--------|
| **Search Page** | `GET /api/projects` + filters | ✅ Ready |
| **Project Detail** | `GET /api/projects/{id}` | ✅ Ready |
| **Listing Detail** | `GET /api/properties/{id}` | ✅ Ready |
| **Developer Page** | `GET /api/promoters/{id}` + `/projects` | ✅ Ready |
| **Promoteurs Page** | `GET /api/promoters` | ✅ Ready |
| **Dashboard - Projects** | `GET /api/promoter/projects` | ✅ Ready |
| **Dashboard - Create Project** | `POST /api/promoter/projects` | ✅ Ready |
| **Dashboard - Create Listing** | `POST /api/promoter/projects/{id}/properties` | ✅ Ready |
| **Dashboard - Contacts** | `GET /api/promoter/leads` | ✅ Ready |
| **Contact Forms** | `POST /api/leads` | ✅ Ready |

---

## ❌ Frontend Pages WITHOUT Backend APIs

| Frontend Page | Missing Backend | Priority |
|--------------|-----------------|----------|
| **Financement Page** | No financing API | 🔴 LOW (Mock page) |
| **À Propos Page** | No about-us API | 🔴 LOW (Static content) |
| **User Authentication UI** | Auth exists but no UI | 🟡 MEDIUM |

---

## 🔴 Backend Features NOT in Frontend

| Backend Feature | Frontend Status | Priority |
|-----------------|----------------|----------|
| **Admin Dashboard** | Not implemented | 🟡 MEDIUM |
| **User Registration/Login** | No auth UI | 🟢 HIGH |
| **Property Validation** | Not shown to promoter | 🟡 MEDIUM |
| **Favorites System** | Not implemented | 🔴 LOW |
| **User Search History** | Not implemented | 🔴 LOW |
| **Orders/Products** | Not implemented | 🔴 LOW |
| **Agencies** | Not implemented | 🔴 LOW |
| **Property Views Tracking** | Not implemented | 🔴 LOW |

---

## 🎯 Recommended Integration Approach

### **Phase 1: Public Pages (No Auth Required)** 🟢
1. Search/Projects listing
2. Project details
3. Property/Listing details
4. Developer/Promoter pages
5. Contact form submissions

### **Phase 2: Authentication** 🟡
1. Login/Register UI
2. User profile
3. Session management
4. Protected routes

### **Phase 3: Promoter Dashboard** 🟢
1. Dashboard stats
2. Project management (CRUD)
3. Property/Listing management (CRUD)
4. Leads management
5. File uploads (images, brochures, floor plans)

### **Phase 4: Advanced Features** 🔴
1. Favorites
2. User search history
3. Admin dashboard
4. Analytics

---

## 🛠️ Technical Integration Strategy

### **1. API Service Layer**
Create centralized API services:
```
composables/
  api/
    useAuthApi.ts
    useProjectsApi.ts
    usePropertiesApi.ts
    usePromotersApi.ts
    useLeadsApi.ts
    useDashboardApi.ts
```

### **2. Environment Configuration**
```env
NUXT_PUBLIC_API_BASE_URL=http://localhost:8000/api
NUXT_PUBLIC_APP_URL=http://localhost:3000
```

### **3. Axios/Fetch Setup**
- Use Nuxt's `$fetch` or install Axios
- Create interceptors for auth tokens
- Handle CORS properly
- Error handling middleware

### **4. Data Mapping**
Backend → Frontend terminology:
- `Property` → `Listing`
- `Promoter` → `Developer`
- `availability_status` → `status`

### **5. File Uploads**
Backend accepts multipart/form-data for:
- Project images/gallery
- Property images
- Promoter logo
- Floor plans
- Brochures

---

## 🔑 Authentication Flow

1. User logs in: `POST /api/login`
2. Backend returns:
   ```json
   {
     "user": { ... },
     "token": "sanctum_token",
     "promoter": { ... } // if user is promoter
   }
   ```
3. Store token in cookie/localStorage
4. Add to all requests: `Authorization: Bearer {token}`
5. Logout: `POST /api/logout`

---

## 📝 Next Steps

### Immediate Tasks:
1. ✅ Create API service layer structure
2. ✅ Set up environment variables
3. ✅ Configure CORS in Laravel
4. ✅ Create auth composables
5. ✅ Replace mock data in search page
6. ✅ Test project detail page
7. ✅ Test contact form submission
8. ✅ Implement file upload for dashboard

### Testing Checklist:
- [ ] Search/filter projects
- [ ] View project details
- [ ] View property details
- [ ] Submit contact form
- [ ] Login as promoter
- [ ] Create new project
- [ ] Upload images
- [ ] Manage listings
- [ ] View leads

---

## 🚨 Important Notes

1. **CORS**: Laravel must allow frontend origin (localhost:3000)
2. **File Storage**: Backend uses Laravel storage - need public access
3. **Images**: Backend stores in `/storage/app/public` → needs symlink
4. **Sanctum**: Cookie-based auth requires same domain or CORS setup
5. **Data Types**: Backend uses `properties` but frontend calls them `listings`

---

## 💡 Recommendations

### HIGH Priority:
1. Start with public pages (no auth)
2. Test with real backend data
3. Add authentication UI
4. Implement promoter dashboard

### MEDIUM Priority:
1. File upload functionality
2. Image optimization
3. Error handling
4. Loading states

### LOW Priority:
1. Favorites feature
2. User search history
3. Admin panel
4. Analytics dashboard

---

**Status**: Ready to begin integration! 🚀
Backend is well-structured and has all necessary endpoints for main features.
