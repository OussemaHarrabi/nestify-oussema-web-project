# 🔗 BACKEND CONNECTION TERMS - EXACT FIELD MAPPING

**Critical:** Use these EXACT field names from Laravel backend  
**Date:** October 14, 2025  
**Source:** Nestify Laravel Backend Models & Database

---

## ⚠️ CRITICAL: This is PHP Laravel Backend, NOT JavaScript!

**Note:** The previous guide showed JavaScript examples for frontend consumption. This document shows the EXACT backend terms your frontend MUST use when connecting.

---

## 📦 DATABASE MODELS & EXACT FIELDS

### 1. USER MODEL (`users` table)

```php
// app/Models/User.php

// ✅ EXACT Field Names
'name'              // string - User full name
'email'             // string - Email (unique)
'password'          // string - Hashed password
'phone'             // string - Phone number
'user_type'         // enum - See values below
'is_active'         // boolean - Account status
'preferences'       // array/json - User preferences
'profile_picture'   // string - Image path
'bio'               // text - Biography
'city'              // string - City name
'address'           // string - Full address
'last_login_at'     // datetime - Last login timestamp
'admin_role'        // enum - Admin role (if admin)
'permissions'       // array/json - Permissions array
'email_verified_at' // datetime - Email verification
'created_at'        // datetime - Auto
'updated_at'        // datetime - Auto
```

**user_type Values (ENUM):**
```php
'client'    // Regular client/buyer
'agency'    // Real estate agency
'promoter'  // Real estate promoter/developer
'admin'     // System administrator
```

**admin_role Values (ENUM):**
```php
'super_admin'       // Full access
'content_moderator' // Content management
'support'           // Support role
```

---

### 2. PROMOTER MODEL (`promoters` table)

```php
// app/Models/Promoter.php

// ✅ EXACT Field Names
'id'                    // bigint - Primary key
'user_id'               // bigint - Foreign key to users
'company_name'          // string - Company name (REQUIRED)
'logo'                  // string - Logo image path
'description'           // text - Company description
'website'               // string - Website URL
'primary_phone'         // string - Main phone (REQUIRED)
'additional_phones'     // array/json - Additional phones
'primary_email'         // string - Main email (REQUIRED)
'additional_emails'     // array/json - Additional emails
'license_number'        // string - Business license number
'established_date'      // date - Company establishment date
'employee_count'        // integer - Number of employees
'specializations'       // array/json - Areas of expertise
'headquarters_address'  // string - HQ address
'headquarters_city'     // string - HQ city
'branch_offices'        // array/json - Branch office locations
'total_projects'        // integer - Total projects count
'completed_projects'    // integer - Completed projects count
'active_projects'       // integer - Active projects count
'rating'                // float - Average rating (0.00-5.00)
'review_count'          // integer - Number of reviews
'verified'              // boolean - Verification status
'featured'              // boolean - Featured status
'verified_at'           // datetime - Verification date
'created_at'            // datetime - Auto
'updated_at'            // datetime - Auto
```

**Relationships:**
```php
user       // belongsTo User
projects   // hasMany Project
leads      // hasMany Lead
```

---

### 3. PROJECT MODEL (`projects` table)

```php
// app/Models/Project.php

// ✅ EXACT Field Names
'id'                                // bigint - Primary key
'promoter_id'                       // bigint - Foreign key (REQUIRED)
'name'                              // string - Project name (NOT 'title')
'slug'                              // string - URL slug (unique)
'description'                       // text - Project description
'reference'                         // string - Project reference code
'city'                              // string - City (REQUIRED)
'district'                          // string - District/neighborhood
'address'                           // string - Full address
'latitude'                          // decimal(10,8) - GPS latitude
'longitude'                         // decimal(11,8) - GPS longitude
'status'                            // enum - See values below (REQUIRED)
'launch_date'                       // date - Project launch date
'expected_delivery'                 // date - Expected completion
'construction_progress'             // string - Progress description
'construction_progress_percentage'  // integer - Progress % (0-100)
'total_units'                       // integer - Total units (REQUIRED)
'available_units'                   // integer - Available units
'sold_units'                        // integer - Sold units
'reserved_units'                    // integer - Reserved units
'total_floors'                      // integer - Number of floors
'buildings_count'                   // integer - Number of buildings
'starting_price'                    // decimal(12,2) - Starting price
'average_price_per_sqm'             // decimal(10,2) - Avg price/m²
'price_range_min'                   // decimal(12,2) - Min price
'price_range_max'                   // decimal(12,2) - Max price
'amenities'                         // array/json - Amenities list
'nearby_facilities'                 // array/json - Nearby places
'tags'                              // array/json - SEO/filter tags
'images'                            // array/json - Image paths
'cover_image'                       // string - Main image path
'floor_plans'                       // array/json - Floor plan paths
'virtual_tours'                     // array/json - Virtual tour URLs
'is_published'                      // boolean - Published status
'is_featured'                       // boolean - Featured status
'published_at'                      // datetime - Publication date
'meta_title'                        // string - SEO title
'meta_description'                  // text - SEO description
'seo_keywords'                      // array/json - SEO keywords
'created_at'                        // datetime - Auto
'updated_at'                        // datetime - Auto
```

**status Values (ENUM):**
```php
'planning'           // In planning phase
'under_construction' // Currently being built
'near_completion'    // Almost finished
'completed'          // Construction complete
'on_hold'            // Temporarily paused
```

**Relationships:**
```php
promoter    // belongsTo Promoter
properties  // hasMany Property
leads       // hasMany Lead
```

---

### 4. PROPERTY MODEL (`properties` table)

```php
// app/Models/Property.php

// ✅ EXACT Field Names
'id'                    // bigint - Primary key
'project_id'            // bigint - Foreign key to projects
'user_id'               // bigint - Foreign key to users
'title'                 // string - Property title (NOT 'name')
'reference'             // string - Property reference code
'description'           // text - Property description
'type'                  // enum - Property type (see below)
'price'                 // decimal(12,2) - Price (REQUIRED)
'surface'               // decimal(10,2) - Surface area m²
'city'                  // string - City
'district'              // string - District
'address'               // string - Address
'latitude'              // decimal(10,8) - GPS latitude
'longitude'             // decimal(11,8) - GPS longitude
'rooms'                 // integer - Total rooms
'bedrooms'              // integer - Number of bedrooms
'bathrooms'             // integer - Number of bathrooms
'floor'                 // integer - Floor number
'total_floors'          // integer - Total floors in building
'parking'               // boolean - Has parking
'elevator'              // boolean - Has elevator
'terrace'               // boolean - Has terrace
'garden'                // boolean - Has garden
'features'              // array/json - Additional features
'images'                // array/json - Image paths
'availability_status'   // enum - Availability (see below)
'validated'             // boolean - Admin validation status
'published_date'        // date - Publication date
'is_vefa'               // boolean - VEFA (off-plan) property
'delivery_date'         // date - Expected delivery date
'construction_progress' // string - Construction progress
'created_at'            // datetime - Auto
'updated_at'            // datetime - Auto
```

**type Values (ENUM):**
```php
'Appartement' // Apartment (with capital A!)
'Villa'       // Villa
'Maison'      // House
'Studio'      // Studio
'Duplex'      // Duplex
```

**availability_status Values (ENUM):**
```php
'available'     // Available for sale
'reserved'      // Reserved by client
'sold'          // Sold
'not_available' // Not available (NOT 'unavailable')
```

**Relationships:**
```php
project    // belongsTo Project
user       // belongsTo User
favorites  // hasMany Favorite
```

---

### 5. LEAD MODEL (`leads` table)

```php
// app/Models/Lead.php

// ✅ EXACT Field Names
'id'                       // bigint - Primary key
'promoter_id'              // bigint - Foreign key (REQUIRED)
'project_id'               // bigint - Foreign key (nullable)
'property_id'              // bigint - Foreign key (nullable)
'name'                     // string - Client name (REQUIRED)
'email'                    // string - Client email
'phone'                    // string - Client phone
'message'                  // text - Inquiry message (REQUIRED)
'type'                     // enum - Lead type (see below)
'status'                   // enum - Lead status (see below)
'priority'                 // enum - Priority level (see below)
'budget_range'             // string - Budget range
'preferred_contact_method' // string - Contact preference
'preferences'              // array/json - Client preferences
'notes'                    // text - Internal notes
'source'                   // string - Lead source
'utm_source'               // string - UTM source parameter
'utm_medium'               // string - UTM medium parameter
'utm_campaign'             // string - UTM campaign parameter
'ip_address'               // string - Client IP
'user_agent'               // string - Browser user agent
'last_contacted_at'        // datetime - Last contact date
'next_follow_up_at'        // datetime - Next follow-up date
'contact_attempts'         // integer - Contact attempt count
'is_converted'             // boolean - Conversion status
'converted_at'             // datetime - Conversion date
'converted_property_id'    // bigint - Converted property FK
'created_at'               // datetime - Auto
'updated_at'               // datetime - Auto
```

**type Values (ENUM):**
```php
'contact_request' // General contact request
'viewing_request' // Property viewing request
'price_inquiry'   // Price information request
'general_inquiry' // General information
```

**status Values (ENUM):**
```php
'new'       // New lead, not contacted
'contacted' // Initial contact made
'qualified' // Lead qualified
'converted' // Successfully converted (NOT 'converted')
'closed'    // Lead closed (NOT 'lost')
```

**priority Values (ENUM):**
```php
'low'    // Low priority
'medium' // Medium priority (default)
'high'   // High priority
```

**Relationships:**
```php
promoter          // belongsTo Promoter
project           // belongsTo Project
property          // belongsTo Property
convertedProperty // belongsTo Property
```

---

## 🔄 API RESPONSE FORMATS

### Success Response Structure
```php
// Single Resource
{
    "id": 5,
    "name": "Résidence Marina Bay",
    "city": "Sousse",
    // ... other fields
}

// Collection (with pagination)
{
    "current_page": 1,
    "data": [
        { "id": 5, "name": "..." },
        { "id": 6, "name": "..." }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/projects?page=1",
    "from": 1,
    "last_page": 3,
    "last_page_url": "http://127.0.0.1:8000/api/projects?page=3",
    "next_page_url": "http://127.0.0.1:8000/api/projects?page=2",
    "path": "http://127.0.0.1:8000/api/projects",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 42
}
```

### Error Response Structure
```php
// Validation Error (422)
{
    "message": "The email field is required.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password must be at least 8 characters."
        ]
    }
}

// Authentication Error (401)
{
    "message": "Unauthenticated."
}

// Not Found Error (404)
{
    "message": "No query results for model [App\\Models\\Project] 5"
}
```

---

## 📸 IMAGE/FILE HANDLING

### Storage Path Structure
```php
// Base storage URL
http://127.0.0.1:8000/storage/

// Project images
'projects/project-name-cover.jpg'      // cover_image
'projects/project-name-gallery-1.jpg'  // images array
'projects/floor-plan-1.pdf'            // floor_plans array

// Property images
'properties/property-ref-1.jpg'        // images array

// Promoter logo
'logos/company-name-logo.png'          // logo

// User profile
'profiles/user-id-photo.jpg'           // profile_picture
```

### File Upload Response
```php
{
    "cover_image": "projects/residence-marina-bay-cover.jpg",
    "images": [
        "projects/residence-marina-bay-1.jpg",
        "projects/residence-marina-bay-2.jpg",
        "projects/residence-marina-bay-3.jpg"
    ]
}
```

### Frontend Display
```javascript
// ⚠️ IMPORTANT: Prepend storage URL
const STORAGE_URL = 'http://127.0.0.1:8000/storage/';

// Display image
const imageUrl = STORAGE_URL + project.cover_image;
// Result: http://127.0.0.1:8000/storage/projects/marina-bay-cover.jpg
```

---

## 🔐 AUTHENTICATION TERMS

### Login Request
```php
POST /api/login
{
    "email": "admin@nestify.tn",
    "password": "password"
}
```

### Login Response
```php
{
    "token": "58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12",
    "user": {
        "id": 4,
        "name": "Admin Nestify",
        "email": "admin@nestify.tn",
        "user_type": "admin",
        "profile_picture": null,
        "email_verified_at": null,
        "created_at": "2024-10-10T10:00:00.000000Z",
        "updated_at": "2025-10-14T13:09:42.000000Z"
    }
}
```

### Authorization Header
```php
// ⚠️ EXACT Format
Authorization: Bearer 58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12
```

---

## ⚠️ COMMON MISTAKES TO AVOID

### ❌ WRONG → ✅ CORRECT

**Project Fields:**
```php
❌ project.title          → ✅ project.name
❌ project.published      → ✅ project.is_published
❌ project.image          → ✅ project.cover_image
❌ project.category       → ✅ project.tags (array)
```

**Property Fields:**
```php
❌ property.name          → ✅ property.title
❌ property.property_type → ✅ property.type
❌ property.status        → ✅ property.availability_status
❌ property.area          → ✅ property.surface
❌ property.size          → ✅ property.surface
```

**Status Values:**
```php
❌ 'in_progress'          → ✅ 'under_construction'
❌ 'done'                 → ✅ 'completed'
❌ 'apartment'            → ✅ 'Appartement' (capital A!)
❌ 'unavailable'          → ✅ 'not_available'
❌ 'lost'                 → ✅ 'closed' (for leads)
```

**Boolean Values:**
```php
❌ 1 or 0                 → ✅ true or false
❌ "true" or "false"      → ✅ true or false (not strings!)
❌ published: 1           → ✅ is_published: true
```

---

## 🎯 VALIDATION RULES

### Project Creation
```php
// Required fields
'promoter_id'  => 'required|exists:promoters,id'
'name'         => 'required|string|max:255'
'city'         => 'required|string|max:255'
'status'       => 'required|in:planning,under_construction,near_completion,completed,on_hold'
'total_units'  => 'required|integer|min:1'

// Optional fields
'description'  => 'nullable|string'
'amenities'    => 'nullable|array'
'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB
```

### Property Creation
```php
// Required fields
'project_id' => 'required|exists:projects,id'
'title'      => 'required|string|max:255'
'type'       => 'required|in:Appartement,Villa,Maison,Studio,Duplex'
'price'      => 'required|numeric|min:0'
'surface'    => 'required|numeric|min:0'

// Optional fields
'bedrooms'   => 'nullable|integer|min:0'
'bathrooms'  => 'nullable|integer|min:0'
'images'     => 'nullable|array'
```

### Lead Submission
```php
// Required fields
'name'       => 'required|string|max:255'
'message'    => 'required|string'

// At least one contact method required
'email'      => 'required_without:phone|email|nullable'
'phone'      => 'required_without:email|string|nullable'

// Optional fields
'project_id' => 'nullable|exists:projects,id'
'property_id'=> 'nullable|exists:properties,id'
```

---

## 📊 RELATIONSHIP LOADING

### Eager Loading Examples
```php
// Load project with promoter
GET /api/projects/5
// Returns project with 'promoter' object

// Load project with properties
GET /api/projects/5
// Returns project with 'properties' array

// Admin project details
GET /api/admin/projects/5
// Returns project with 'promoter' and 'properties'
```

### Relationship Structure
```php
{
    "id": 5,
    "name": "Résidence Marina Bay",
    "promoter": {
        "id": 7,
        "company_name": "Immobilier Tunisie",
        "logo": "logos/logo.png"
    },
    "properties": [
        {
            "id": 1,
            "title": "2-Bedroom Apartment",
            "price": 320000.00
        }
    ]
}
```

---

## 🔢 DATA TYPES

### String Fields
```php
name, email, phone, address, city, district, slug, reference
```

### Integer Fields
```php
id, user_id, promoter_id, project_id, property_id
total_units, available_units, sold_units, reserved_units
bedrooms, bathrooms, rooms, floor, total_floors
employee_count, contact_attempts
```

### Decimal/Float Fields
```php
price (12,2)              // 999999999999.99
surface (10,2)            // 99999999.99
latitude (10,8)           // 99.99999999
longitude (11,8)          // 999.99999999
average_price_per_sqm (10,2)
rating (float)            // 0.00-5.00
```

### Boolean Fields
```php
is_published, is_featured, is_active, is_vefa, is_converted
verified, featured, validated
parking, elevator, terrace, garden
```

### Array/JSON Fields
```php
amenities, nearby_facilities, tags, images, floor_plans
preferences, permissions, specializations
additional_phones, additional_emails, branch_offices
features, seo_keywords
```

### Date/DateTime Fields
```php
launch_date, expected_delivery, delivery_date (date)
published_at, verified_at, last_login_at (datetime)
created_at, updated_at (datetime - auto)
```

---

## 🎨 ENUM VALUES REFERENCE

### user_type
```
'client', 'agency', 'promoter', 'admin'
```

### project.status
```
'planning', 'under_construction', 'near_completion', 'completed', 'on_hold'
```

### property.type
```
'Appartement', 'Villa', 'Maison', 'Studio', 'Duplex'
```

### property.availability_status
```
'available', 'reserved', 'sold', 'not_available'
```

### lead.type
```
'contact_request', 'viewing_request', 'price_inquiry', 'general_inquiry'
```

### lead.status
```
'new', 'contacted', 'qualified', 'converted', 'closed'
```

### lead.priority
```
'low', 'medium', 'high'
```

### admin_role
```
'super_admin', 'content_moderator', 'support'
```

---

## 📡 API ENDPOINT PATTERNS

### Standard CRUD Patterns
```php
// List (GET - paginated)
/api/admin/projects              // List all projects
/api/promoter/properties         // List my properties

// Show (GET - single resource)
/api/projects/5                  // Get project by ID
/api/projects/residence-marina   // Get project by slug

// Create (POST)
/api/promoter/projects           // Create new project

// Update (POST with files, PUT/PATCH without files)
/api/promoter/projects/5         // Update project

// Delete (DELETE)
/api/promoter/projects/5         // Delete project

// Special Actions (PATCH)
/api/admin/projects/5/publish    // Publish project
/api/promoter/leads/10/status    // Update lead status
```

---

## ✅ INTEGRATION CHECKLIST

### Before Starting Frontend Development

- [ ] Use exact field names from this document
- [ ] Check ENUM values match exactly (case-sensitive!)
- [ ] Boolean values are true/false, not 1/0
- [ ] Property type is 'Appartement' with capital A
- [ ] Project uses 'name', Property uses 'title'
- [ ] Availability status is 'not_available' not 'unavailable'
- [ ] Storage URLs prepended: http://127.0.0.1:8000/storage/
- [ ] Authorization header: Bearer {token}
- [ ] Content-Type: application/json for JSON requests
- [ ] Accept: application/json header always included
- [ ] Handle pagination structure correctly
- [ ] Parse error responses properly
- [ ] Check relationship loading (nested objects)

---

## 📞 Quick Reference

**Base URL:** `http://127.0.0.1:8000/api`  
**Storage URL:** `http://127.0.0.1:8000/storage/`  
**Admin Token:** `58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12`

**Test Credentials:**
```
Admin:    admin@nestify.tn / password
Promoter: promoteur1@nestify.tn / password123
```

**Test All APIs:**
```bash
cd D:\oussema\Nestify_2.0\nestify-backend
php quick_api_test.php
```

---

**Last Updated:** October 14, 2025  
**Source:** Laravel Backend Models & Database Schema  
**Status:** ✅ All 22 endpoints tested and working
