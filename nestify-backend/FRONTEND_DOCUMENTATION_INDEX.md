# 📚 NESTIFY BACKEND - FRONTEND DOCUMENTATION INDEX

**Project:** Nestify Real Estate Platform API  
**Backend:** Laravel 12.0 + MySQL + Sanctum Authentication  
**Last Updated:** October 19, 2025  
**Status:** ✅ Production Ready - All APIs Tested & Working

---

## 🎯 START HERE - Essential Documents for Frontend Development

These are the **MUST-READ** documents for your frontend team to integrate with the backend:

### 1️⃣ **BACKEND_CONNECTION_TERMS.md** 🔥 **[MOST IMPORTANT]**
**File:** `BACKEND_CONNECTION_TERMS.md`  
**Purpose:** Complete field mapping extracted from Laravel models  
**What You'll Find:**
- ✅ EXACT field names for all models (User, Project, Property, Promoter, Lead, Agency)
- ✅ EXACT enum values (case-sensitive!)
- ✅ Data types for every field (string, integer, boolean, array, decimal, datetime)
- ✅ Common mistakes to avoid (e.g., Project uses `name` not `title`)
- ✅ Validation rules for each endpoint
- ✅ Image/file handling patterns
- ✅ API response structures
- ✅ Relationship loading examples

**Critical Info:**
```php
// Property type enum (CAPITALIZED!)
'Appartement', 'Villa', 'Maison', 'Studio', 'Duplex'

// Project uses 'name' but Property uses 'title'
project.name vs property.title

// Availability status
'available', 'reserved', 'sold', 'not_available' (NOT 'unavailable')
```

---

### 2️⃣ **API_DOCUMENTATION.md** 📡
**File:** `API_DOCUMENTATION.md`  
**Purpose:** Complete API endpoint reference  
**What You'll Find:**
- All 22 API endpoints with examples
- Request/response formats
- Authentication flow
- Public endpoints (no auth required)
- Authenticated user endpoints
- Promoter dashboard endpoints
- Admin panel endpoints
- Search & filtering options
- Pagination structure

**Sections:**
1. Authentication (Login/Register/Logout)
2. Public APIs (Projects, Properties listing)
3. User APIs (Favorites, Profile)
4. Promoter APIs (Manage projects/properties/leads)
5. Admin APIs (Full CRUD operations)

---

### 3️⃣ **FRONTEND_INTEGRATION_GUIDE.md** 🚀
**File:** `FRONTEND_INTEGRATION_GUIDE.md`  
**Purpose:** Quick start guide with code examples  
**What You'll Find:**
- Base URLs and configuration
- Authentication setup
- Token management
- API call examples
- Error handling patterns
- Image URL construction
- File upload examples

---

### 4️⃣ **MEETING_READY.md** ⚡
**File:** `MEETING_READY.md`  
**Purpose:** Quick reference for demos and testing  
**What You'll Find:**
- Test credentials (Admin, Promoter, Client)
- Working API token
- Quick endpoint examples
- Demo script
- Common API calls

**Test Credentials:**
```
Admin:    admin@nestify.tn / password
Promoter: promoteur1@nestify.tn / password123
Client:   client1@nestify.tn / password123
```

---

## 📋 Supporting Documentation

### Development & Testing

#### **API_TESTING_GUIDE.md**
- How to test APIs locally
- Using quick_api_test.php script
- Postman collection setup
- Debugging tips

#### **POSTMAN_TESTING_DOCUMENTATION.md**
- Complete Postman setup
- Auto-authentication configuration
- Collection organization
- Environment variables

#### **COMPREHENSIVE_API_TESTING_GUIDE.md**
- Advanced testing scenarios
- Error case testing
- Performance testing
- Integration testing

---

### Authentication & Security

#### **AUTHENTICATION_RESOLVED.md**
- Authentication system overview
- Sanctum token setup
- Common auth issues resolved
- Token lifecycle

#### **ADMIN_AUTH_FIX_GUIDE.md**
- Admin-specific authentication
- Permission system
- Role-based access control

#### **FINAL_AUTHENTICATION_STATUS.md**
- Current auth status
- All resolved issues
- Best practices

---

### Database & Setup

#### **DATABASE_SEEDING_COMPLETE.md**
- Database structure
- Seeded test data
- Sample records available

#### **MIGRATION_COMPLETE.md**
- Database migrations applied
- Schema overview
- Table relationships

#### **SETUP_GUIDE.md**
- Initial project setup
- Environment configuration
- Dependencies installation

---

### API Implementation Status

#### **API_FIXES_COMPLETE.md**
- All API fixes applied
- Fixed endpoints list
- Validation corrections

#### **ADMIN_API_COMPLETE.md**
- Admin endpoints status
- CRUD operations available
- Permission requirements

#### **FINAL_PROJECT_STATUS_REPORT.md**
- Overall project status
- All working endpoints
- Test results (22/22 passing)

---

## 🔗 API Base URLs

### Development
```
Backend API:  http://127.0.0.1:8000/api
Storage URL:  http://127.0.0.1:8000/storage/
```

### Production (When Deployed)
```
Backend API:  https://api.nestify.tn/api
Storage URL:  https://api.nestify.tn/storage/
```

---

## 🗂️ API Endpoint Categories

### 🌐 Public APIs (No Authentication)
```
GET  /api/projects                    // List all published projects
GET  /api/projects/{id}               // Get project details
GET  /api/projects/slug/{slug}        // Get project by slug
GET  /api/properties                  // List all validated properties
GET  /api/properties/{id}             // Get property details
POST /api/leads                       // Submit contact form
```

### 🔐 Authenticated User APIs
```
POST /api/login                       // Login
POST /api/register                    // Register new user
POST /api/logout                      // Logout
GET  /api/user                        // Get current user profile
PUT  /api/user                        // Update user profile

GET  /api/favorites                   // Get user's favorites
POST /api/favorites                   // Add to favorites
DELETE /api/favorites/{id}            // Remove from favorites
```

### 👨‍💼 Promoter APIs
```
GET    /api/promoter/profile          // Get promoter profile
PUT    /api/promoter/profile          // Update profile

GET    /api/promoter/projects         // List my projects
POST   /api/promoter/projects         // Create new project
PUT    /api/promoter/projects/{id}    // Update project
DELETE /api/promoter/projects/{id}    // Delete project

GET    /api/promoter/properties       // List my properties
POST   /api/promoter/properties       // Create property
PUT    /api/promoter/properties/{id}  // Update property
DELETE /api/promoter/properties/{id}  // Delete property

GET    /api/promoter/leads            // Get my leads
PATCH  /api/promoter/leads/{id}/status // Update lead status
```

### 🛡️ Admin APIs
```
GET    /api/admin/users               // List all users
GET    /api/admin/users/{id}          // Get user details
PUT    /api/admin/users/{id}          // Update user
DELETE /api/admin/users/{id}          // Delete user
PATCH  /api/admin/users/{id}/activate // Activate/deactivate user

GET    /api/admin/projects            // List all projects
PATCH  /api/admin/projects/{id}/publish // Publish/unpublish project
PATCH  /api/admin/projects/{id}/feature // Feature/unfeature project

GET    /api/admin/properties          // List all properties
PATCH  /api/admin/properties/{id}/validate // Validate property

GET    /api/admin/promoters           // List all promoters
PATCH  /api/admin/promoters/{id}/verify // Verify promoter
```

---

## 📊 Data Models Overview

### User Model
```
Fields: id, name, email, phone, user_type, is_active, profile_picture
Types: 'client', 'agency', 'promoter', 'admin'
```

### Promoter Model
```
Fields: id, user_id, company_name, logo, verified, featured, rating
Key: One promoter = One user_id
```

### Project Model
```
Fields: id, promoter_id, name, slug, city, status, total_units, starting_price
Status: 'planning', 'under_construction', 'near_completion', 'completed', 'on_hold'
Key: Projects belong to promoters
```

### Property Model
```
Fields: id, project_id, title, type, price, surface, bedrooms, availability_status
Types: 'Appartement', 'Villa', 'Maison', 'Studio', 'Duplex' (CAPITALIZED!)
Availability: 'available', 'reserved', 'sold', 'not_available'
Key: Properties belong to projects
```

### Lead Model
```
Fields: id, promoter_id, project_id, property_id, name, email, phone, status, priority
Status: 'new', 'contacted', 'qualified', 'converted', 'closed'
Priority: 'low', 'medium', 'high'
Key: Leads go to promoters (not directly to projects)
```

---

## ⚠️ Critical Integration Notes

### 1. Field Name Differences
```php
// ❌ WRONG → ✅ CORRECT
project.title     → project.name
property.name     → property.title
project.published → project.is_published
property.status   → property.availability_status
```

### 2. Enum Values are Case-Sensitive!
```php
// ❌ WRONG → ✅ CORRECT
'apartment'    → 'Appartement' (with capital A!)
'villa'        → 'Villa'
'unavailable'  → 'not_available'
'in_progress'  → 'under_construction'
```

### 3. Boolean Values
```php
// ❌ WRONG → ✅ CORRECT
is_published: 1        → is_published: true
is_published: "true"   → is_published: true (boolean not string!)
validated: 0           → validated: false
```

### 4. Image URLs
```php
// ❌ WRONG
<img src="{{ project.cover_image }}" />

// ✅ CORRECT
const STORAGE_URL = 'http://127.0.0.1:8000/storage/';
<img src="{{ STORAGE_URL + project.cover_image }}" />
```

### 5. Authorization Header
```php
// ✅ CORRECT FORMAT
Authorization: Bearer {token}

// Example
Authorization: Bearer 58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12
```

### 6. Content-Type Headers
```php
// For JSON requests
Content-Type: application/json
Accept: application/json

// For file uploads (multipart)
Content-Type: multipart/form-data
```

---

## 🧪 Testing & Validation

### Quick API Test Script
```bash
cd d:\oussema\Nestify_2.0\nestify-backend
php quick_api_test.php
```

**Test Results:** ✅ 22/22 endpoints passing (100% success rate)

### Available Test Scripts
- `quick_api_test.php` - Test all 22 endpoints
- `test_admin_auth.php` - Test admin authentication
- `check_database_status.php` - Verify database state

### Postman Collections
- `Nestify_Complete_API_Collection.postman_collection.json` - Full API collection
- `Nestify_Admin_Workflow.postman_collection.json` - Admin workflow
- Auto-authentication configured

---

## 🔧 Common Issues & Solutions

### Issue 1: "Unauthenticated" Error
**Solution:** Check Authorization header format
```
Authorization: Bearer {your-token-here}
```

### Issue 2: 422 Validation Error
**Solution:** Check EXACT field names and enum values in BACKEND_CONNECTION_TERMS.md

### Issue 3: 404 Not Found
**Solution:** 
- Verify endpoint URL is correct
- Check if resource exists and is published
- For projects: must have `is_published: true`
- For properties: must have `validated: true`

### Issue 4: Image Not Loading
**Solution:** Prepend storage URL
```javascript
const imageUrl = 'http://127.0.0.1:8000/storage/' + project.cover_image;
```

### Issue 5: Property Type Not Accepted
**Solution:** Use capitalized values
```
❌ 'appartement' → ✅ 'Appartement'
```

---

## 📞 Quick Reference

### Test Admin Token
```
58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12
```

### Test Credentials
```
Admin:
  Email: admin@nestify.tn
  Password: password

Promoter:
  Email: promoteur1@nestify.tn
  Password: password123

Client:
  Email: client1@nestify.tn
  Password: password123
```

### Server Commands
```bash
# Start Laravel server
php artisan serve

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# View routes
php artisan route:list
```

---

## 📦 Postman Collections

### Import These Collections
1. **Nestify_Complete_API_Collection.postman_collection.json**
   - All 22 endpoints
   - Auto-authentication
   - Pre-configured examples

2. **Nestify_Admin_Workflow.postman_collection.json**
   - Admin-specific workflows
   - User management
   - Content moderation

### Collection Features
- ✅ Auto token refresh
- ✅ Environment variables
- ✅ Pre-request scripts
- ✅ Test assertions
- ✅ Example responses

---

## 🎓 Frontend Integration Checklist

Before you start coding, make sure you:

- [ ] Read BACKEND_CONNECTION_TERMS.md completely
- [ ] Understand exact field names (project.name vs property.title)
- [ ] Know all enum values (case-sensitive!)
- [ ] Set up Authorization header correctly
- [ ] Configure storage URL for images
- [ ] Import Postman collection for testing
- [ ] Test login/register flow
- [ ] Test token persistence
- [ ] Handle pagination correctly
- [ ] Parse error responses properly
- [ ] Test file upload functionality
- [ ] Verify relationship loading (nested objects)

---

## 📁 File Structure Reference

```
nestify-backend/
├── 📄 BACKEND_CONNECTION_TERMS.md     ⭐ START HERE
├── 📄 API_DOCUMENTATION.md             ⭐ All endpoints
├── 📄 FRONTEND_INTEGRATION_GUIDE.md    ⭐ Code examples
├── 📄 MEETING_READY.md                 ⭐ Quick reference
│
├── 🧪 Testing Docs
│   ├── API_TESTING_GUIDE.md
│   ├── POSTMAN_TESTING_DOCUMENTATION.md
│   └── COMPREHENSIVE_API_TESTING_GUIDE.md
│
├── 🔐 Authentication Docs
│   ├── AUTHENTICATION_RESOLVED.md
│   ├── ADMIN_AUTH_FIX_GUIDE.md
│   └── FINAL_AUTHENTICATION_STATUS.md
│
├── 🗄️ Database Docs
│   ├── DATABASE_SEEDING_COMPLETE.md
│   ├── MIGRATION_COMPLETE.md
│   └── DATABASE_FIXES_FINAL.md
│
├── 📊 Status Reports
│   ├── FINAL_PROJECT_STATUS_REPORT.md
│   ├── API_FIXES_COMPLETE.md
│   └── ADMIN_API_COMPLETE.md
│
├── ⚙️ Setup Docs
│   ├── SETUP_GUIDE.md
│   ├── SETUP_COMPLETE.md
│   └── MIGRATION_GUIDE.md
│
└── 📦 Postman Collections
    ├── Nestify_Complete_API_Collection.postman_collection.json
    └── Nestify_Admin_Workflow.postman_collection.json
```

---

## 🚀 Getting Started (5 Minutes)

### Step 1: Read Core Docs (15 min)
1. Open `BACKEND_CONNECTION_TERMS.md` - Read all field names and enums
2. Skim `API_DOCUMENTATION.md` - Understand available endpoints
3. Check `MEETING_READY.md` - Get test credentials

### Step 2: Import Postman (2 min)
1. Open Postman
2. Import `Nestify_Complete_API_Collection.postman_collection.json`
3. Test login endpoint with admin credentials

### Step 3: First API Call (3 min)
```javascript
// Login
const response = await fetch('http://127.0.0.1:8000/api/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    email: 'admin@nestify.tn',
    password: 'password'
  })
});

const data = await response.json();
const token = data.token; // Save this!

// Get projects
const projects = await fetch('http://127.0.0.1:8000/api/projects', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  }
});
```

### Step 4: Verify Data
```bash
# Test all APIs
cd d:\oussema\Nestify_2.0\nestify-backend
php quick_api_test.php
```

**Expected Result:** ✅ 22/22 tests passing

---

## 🆘 Need Help?

### Debug Checklist
1. ✅ Is Laravel server running? (`php artisan serve`)
2. ✅ Is token valid? (Check Authorization header)
3. ✅ Are field names correct? (Check BACKEND_CONNECTION_TERMS.md)
4. ✅ Are enum values correct? (Case-sensitive!)
5. ✅ Is resource published? (projects) or validated? (properties)

### Test Scripts
```bash
# Test specific endpoint
php quick_api_test.php

# Check database
php check_database_status.php

# Verify admin user
php verify_admin.php
```

### Log Files
```
storage/logs/laravel.log   // Application logs
```

---

## 📈 Project Status

**Backend Development:** ✅ 100% Complete  
**API Endpoints:** ✅ 22/22 Working  
**Authentication:** ✅ Fully Functional  
**Database:** ✅ Seeded with Test Data  
**Documentation:** ✅ Complete  
**Testing:** ✅ All Tests Passing  

**Ready for Frontend Integration:** ✅ YES

---

## 📝 Document Versions

| Document | Version | Last Updated | Status |
|----------|---------|--------------|--------|
| BACKEND_CONNECTION_TERMS.md | 1.0 | Oct 19, 2025 | ✅ Current |
| API_DOCUMENTATION.md | 2.0 | Oct 14, 2025 | ✅ Current |
| FRONTEND_INTEGRATION_GUIDE.md | 1.0 | Oct 14, 2025 | ✅ Current |
| MEETING_READY.md | 1.0 | Oct 14, 2025 | ✅ Current |

---

**Last Updated:** October 19, 2025  
**Maintained By:** Backend Team  
**For:** Frontend Development Team  
**Status:** ✅ Ready for Production Integration
