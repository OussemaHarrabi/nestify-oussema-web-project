# Nestify Backend API Documentation# Nestify Real Estate API Documentation

**Version:** 1.0  

**Base URL:** `http://127.0.0.1:8000/api`  ## Base URL

**Date:** October 14, 2025```

http://127.0.0.1:8000/api

---```



## 📋 Table of Contents## Authentication

1. [Quick Start](#quick-start)The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header for protected routes.

2. [Authentication](#authentication)

3. [Admin Endpoints](#admin-endpoints)```

4. [Promoter Endpoints](#promoter-endpoints)Authorization: Bearer YOUR_TOKEN_HERE

5. [Public Endpoints](#public-endpoints)```

6. [Data Models](#data-models)

7. [Error Handling](#error-handling)## Standard Response Format

8. [Important Notes](#important-notes)

### Success Response

---```json

{

## 🚀 Quick Start    "data": [...],

    "message": "Success message",

### Testing Credentials    "pagination": {

        "current_page": 1,

**Admin User:**        "last_page": 5,

```        "per_page": 12,

Email: admin@nestify.tn        "total": 60

Password: password    }

Fresh Token: 58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12}

``````



**Promoter User:**### Error Response

``````json

Email: promoteur1@nestify.tn{

Password: password123    "message": "Error message",

```    "errors": {

        "field": ["Validation error message"]

### Quick Test    }

```bash}

# Login```

curl -X POST http://127.0.0.1:8000/api/login \

  -H "Content-Type: application/json" \## Property Data Structure (MongoDB Compatible)

  -H "Accept: application/json" \

  -d '{"email":"admin@nestify.tn","password":"password"}'The API returns property data in a format compatible with your MongoDB schema:



# Get Dashboard (use token from login)```json

curl -X GET http://127.0.0.1:8000/api/admin/dashboard \{

  -H "Authorization: Bearer YOUR_TOKEN" \    "_id": "nestify_1",

  -H "Accept: application/json"    "url": "http://127.0.0.1:8000/properties/1",

```    "title": "Appartement à vendre à Sahloul 4",

    "description": "Découvrez la Résidence Panamera III...",

---    "published_date": "13 Jul 2025",

    "reference": "NEST_ABC12345",

## 🔐 Authentication    "price": 350000,

    "type": "Appartement",

### How It Works    "surface": 116,

- **Method:** Laravel Sanctum (Token-based authentication)    "rental_potential": "",

- **Token Type:** Bearer Token    "images": [

- **Header:** `Authorization: Bearer {token}`        "https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Appartement+1"

- **Token Lifetime:** Until logout or revoked    ],

    "views": 25,

### Login    "created_at": "2025-09-08T14:30:00.000Z",

**Endpoint:** `POST /api/login`    "validated": true,

    "location_id": {

**Request:**        "address": "",

```json        "city": "Sousse",

{        "district": "Sahloul",

    "email": "admin@nestify.tn",        "region": "",

    "password": "password"        "country": "Tunisie",

}        "coordinates": {

```            "lat": 35.8283900976025,

            "lng": 10.600180292889547

**Success Response (200):**        }

```json    },

{    "VEFA_details_id": {

    "token": "58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12",        "is_vefa": true,

    "user": {        "delivery_date": "April 2025",

        "id": 4,        "construction_progress": "En cours de construction",

        "name": "Admin Nestify",        "payment_schedule": [],

        "email": "admin@nestify.tn",        "guarantees": []

        "user_type": "admin"    },

    }    "terrain_details_id": {},

}    "apartment_details_id": {

```        "rooms": 3,

        "bedrooms": 2,

**Error Response (401):**        "bathrooms": 2,

```json        "floor": 1,

{        "total_floors": 4,

    "message": "The provided credentials are incorrect.",        "parking": true,

    "errors": {        "elevator": true,

        "email": ["The provided credentials are incorrect."]        "terrace": false,

    }        "garden": false,

}        "features": [

```            "Garage",

            "Ascenseur",

### Logout            "Piscine",

**Endpoint:** `POST /api/logout`              "Climatisation"

**Auth Required:** Yes        ]

    },

**Response (200):**    "promoter_id": {

```json        "name": "Société immobiliere Belkacem",

{        "contact": {

    "message": "Logged out successfully"            "phone": "+216 25925200",

}            "email": "contact@belkacem-immo.tn",

```            "website": "https://belkacem-immo.tn",

            "addresses": [],

### Get Current User            "additional_phones": [

**Endpoint:** `GET /api/user`                  "+216 20402650"

**Auth Required:** Yes            ]

        },

**Response (200):**        "verified": true

```json    }

{}

    "id": 4,```

    "name": "Admin Nestify",

    "email": "admin@nestify.tn",## API Endpoints

    "user_type": "admin",

    "profile_picture": null### Authentication

}

```#### POST /register

Register a new user (client, agency, or promoter)

---

**Headers:**

## 👨‍💼 Admin Endpoints```

Content-Type: application/json

**Base Path:** `/api/admin`  Accept: application/json

**Auth Required:** Yes (admin only)```



### Dashboard**Body:**

```json

**GET** `/api/admin/dashboard`{

    "name": "Test Agency",

**Response:**    "email": "testagency@example.com",

```json    "password": "password123",

{    "password_confirmation": "password123",

    "total_promoters": 10,    "phone": "+216 12345678",

    "total_projects": 25,    "user_type": "agency",

    "total_properties": 150,    "company_name": "Test Real Estate Agency",

    "pending_promoters": 3,    "website": "https://test-agency.com",

    "published_projects": 20,    "description": "Professional real estate agency"

    "recent_leads": []}

}```

```

#### POST /login

---Authenticate user and get access token



### Promoters**Headers:**

```

#### List PromotersContent-Type: application/json

**GET** `/api/admin/promoters`Accept: application/json

```

**Query Params:**

- `page` - Page number (default: 1)**Body:**

- `per_page` - Items per page (default: 15)```json

- `verified` - Filter: `true`, `false`{

    "email": "testagency@example.com",

**Example:** `/api/admin/promoters?verified=false&page=1`    "password": "password123"

}

**Response:**```

```json

{**Response:**

    "current_page": 1,```json

    "data": [{

        {    "message": "Login successful",

            "id": 7,    "user": {

            "user_id": 10,        "id": 1,

            "company_name": "Immobilier Tunisie",        "name": "Test Agency",

            "trade_register": "B123456789",        "email": "testagency@example.com",

            "primary_phone": "+216 71 123 456",        "user_type": "agency",

            "primary_email": "contact@immobilier-tunisie.tn",        "agency": {

            "verified": true,            "company_name": "Test Real Estate Agency",

            "logo": "logos/logo123.png",            "verified": false

            "user": {        }

                "id": 10,    },

                "name": "Promoter User",    "token": "1|abc123..."

                "email": "promoteur1@nestify.tn"}

            }```

        }

    ],#### POST /logout

    "total": 10,Logout and invalidate current token (Protected)

    "per_page": 15,

    "last_page": 1#### GET /user

}Get current authenticated user (Protected)

```

### Properties

#### Get Promoter Details

**GET** `/api/admin/promoters/{id}`#### GET /properties

Get all validated properties with optional filters

**Response:** Full promoter object with projects

**Query Parameters:**

#### Verify Promoter- `search` - Search in title, description, city

**PATCH** `/api/admin/promoters/{id}/verify`- `min_price` - Minimum price

- `max_price` - Maximum price

**Request:**- `min_surface` - Minimum surface area

```json- `max_surface` - Maximum surface area

{- `type` - Property type (Appartement, Villa, Maison, Studio, Duplex)

    "verified": true- `rooms` - Number of rooms

}- `city` - City name

```- `parking` - Has parking (1 for true)

- `elevator` - Has elevator (1 for true)

**Response:**

```json**Example:**

{```

    "id": 7,GET /properties?min_price=100000&max_price=500000&type=Appartement&city=Sousse&rooms=3&parking=1

    "verified": true,```

    "message": "Promoter verification status updated"

}#### GET /properties/{id}

```Get specific property details (increments view count)



---#### POST /properties

Create new property (Protected)

### Projects

**Body:**

#### List Projects```json

**GET** `/api/admin/projects`{

    "title": "Appartement moderne à Tunis",

**Query Params:**    "description": "Bel appartement moderne...",

- `status` - Filter: `pending`, `published`    "price": 250000,

- `city` - Filter by city name    "type": "Appartement",

    "surface": 85,

**Response:**    "city": "Tunis",

```json    "district": "Centre Ville",

{    "address": "Avenue Habib Bourguiba",

    "current_page": 1,    "latitude": 36.8065,

    "data": [    "longitude": 10.1815,

        {    "rooms": 3,

            "id": 5,    "bedrooms": 2,

            "promoter_id": 7,    "bathrooms": 1,

            "name": "Résidence Marina Bay",    "floor": 2,

            "slug": "residence-marina-bay",    "total_floors": 5,

            "reference": "PRJ-2024-005",    "parking": true,

            "city": "Sousse",    "elevator": true,

            "district": "Sousse Ville",    "terrace": false,

            "status": "under_construction",    "garden": false,

            "is_published": true,    "features": ["Climatisation", "Cuisine équipée"],

            "is_featured": false,    "is_vefa": false,

            "total_units": 120,    "images": [

            "available_units": 85,        "https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Appartement"

            "starting_price": 250000.00,    ]

            "cover_image": "projects/marina-bay-cover.jpg",}

            "published_at": "2025-10-14T13:09:42.000000Z",```

            "promoter": {

                "id": 7,#### PUT /properties/{id}

                "company_name": "Immobilier Tunisie",Update property (Protected - Owner or Admin only)

                "logo": "logos/logo123.png"

            }#### DELETE /properties/{id}

        }Delete property (Protected - Owner or Admin only)

    ],

    "total": 25#### GET /my-properties

}Get current user's properties (Protected)

```

### Favorites

#### Get Project Details

**GET** `/api/admin/projects/{id}`#### GET /favorites

Get user's favorite properties (Protected)

**Response:**

```json#### POST /favorites/{propertyId}

{Toggle property favorite status (Protected)

    "id": 5,

    "name": "Résidence Marina Bay",#### GET /favorites/check/{propertyId}

    "slug": "residence-marina-bay",Check if property is favorited by user (Protected)

    "description": "Luxury waterfront residence",

    "city": "Sousse",## Testing with Postman

    "address": "Avenue de la Corniche",

    "status": "under_construction",### Step-by-Step Testing Guide:

    "total_units": 120,

    "available_units": 85,1. **Test Registration:**

    "starting_price": 250000.00,   - Register agency user

    "amenities": ["Pool", "Gym", "Security 24/7"],   - Register client user

    "images": ["image1.jpg", "image2.jpg"],   - Verify response structure

    "cover_image": "cover.jpg",

    "is_published": true,2. **Test Authentication:**

    "promoter": {   - Login with agency credentials

        "id": 7,   - Save the token for subsequent requests

        "company_name": "Immobilier Tunisie"   - Test /user endpoint with token

    },

    "properties": [3. **Test Property Creation:**

        {   - Create property with agency account

            "id": 1,   - Verify property structure matches MongoDB schema

            "title": "2-Bedroom Apartment",   - Test validation errors with invalid data

            "price": 320000.00,

            "bedrooms": 24. **Test Property Retrieval:**

        }   - Get all properties (public)

    ]   - Get specific property by ID

}   - Test search filters

```   - Verify pagination



#### Publish Project5. **Test Favorites:**

**PATCH** `/api/admin/projects/{id}/publish`   - Login as client

   - Add properties to favorites

**Request (Optional):**   - Check favorites list

```json   - Remove from favorites

{

    "is_published": true6. **Test Property Management:**

}   - Update property (as owner)

```   - Try to update property (as different user - should fail)

   - Delete property (as owner)

**Note:** If no body sent, it toggles the current status.

### Expected Test Results:

**Response:**

```json✅ **Authentication flows work correctly**

{✅ **Property CRUD operations function properly**

    "id": 5,✅ **Search and filtering work as expected**

    "is_published": true,✅ **Data structure matches MongoDB schema**

    "published_at": "2025-10-14T13:09:42.000000Z",✅ **Authorization controls access correctly**

    "message": "Project publication status updated"✅ **Pagination works for large datasets**

}

```### Common Test Credentials:



#### Unpublish Project**Agency User:**

**PATCH** `/api/admin/projects/{id}/unpublish`- Email: contact@belkacem-immo.tn

- Password: password

**Response:**

```json**Client Users:**

{- Email: client1@example.com to client5@example.com

    "id": 5,- Password: password

    "is_published": false,

    "published_at": null**Admin User:**

}- Email: admin@nestify.tn

```- Password: password


---

### Properties

#### List Properties
**GET** `/api/admin/properties`

**Query Params:**
- `validated` - Filter: `true`, `false`
- `type` - Filter by property type

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "project_id": 5,
            "title": "2-Bedroom Apartment",
            "type": "appartement",
            "price": 320000.00,
            "surface": 95.00,
            "bedrooms": 2,
            "validated": true,
            "project": {
                "id": 5,
                "name": "Résidence Marina Bay"
            }
        }
    ]
}
```

#### Validate Property
**PATCH** `/api/admin/properties/{id}/validate`

**Request:**
```json
{
    "validated": true
}
```

---

## 🏢 Promoter Endpoints

**Base Path:** `/api/promoter`  
**Auth Required:** Yes (promoter only)

### Dashboard & Profile

#### Get Dashboard Stats
**GET** `/api/promoter/dashboard`  
**GET** `/api/promoter/stats` (alias)

**Response:**
```json
{
    "total_projects": 5,
    "published_projects": 3,
    "total_properties": 45,
    "available_properties": 32,
    "total_leads": 78,
    "new_leads": 12
}
```

#### Get Profile
**GET** `/api/promoter/profile`

**Response:**
```json
{
    "id": 7,
    "company_name": "Immobilier Tunisie",
    "primary_phone": "+216 71 123 456",
    "primary_email": "contact@immobilier-tunisie.tn",
    "website": "https://immobilier-tunisie.tn",
    "logo": "logos/logo123.png",
    "verified": true
}
```

#### Update Profile
**PUT** `/api/promoter/profile`

**Request:**
```json
{
    "company_name": "Updated Name",
    "primary_phone": "+216 71 123 456",
    "website": "https://example.com",
    "description": "Company description"
}
```

---

### Projects (Promoter)

#### List My Projects
**GET** `/api/promoter/projects`

**Response:**
```json
{
    "data": [
        {
            "id": 5,
            "name": "Résidence Marina Bay",
            "city": "Sousse",
            "status": "under_construction",
            "is_published": true,
            "total_units": 120,
            "available_units": 85
        }
    ]
}
```

#### Create Project
**POST** `/api/promoter/projects`  
**Content-Type:** `multipart/form-data`

**Request:**
```
name: "New Project"
description: "Description"
city: "Tunis"
district: "La Marsa"
status: "planning"
total_units: 50
starting_price: 200000
amenities: ["Pool", "Gym"]
cover_image: <file>
```

**Response:**
```json
{
    "id": 10,
    "name": "New Project",
    "slug": "new-project",
    "message": "Project created successfully"
}
```

#### Get Project
**GET** `/api/promoter/projects/{id}`

#### Update Project
**POST** `/api/promoter/projects/{id}`  
**Note:** Use POST (not PUT) when uploading files

#### Delete Project
**DELETE** `/api/promoter/projects/{id}`

#### Toggle Publish
**PATCH** `/api/promoter/projects/{id}/publish`

---

### Properties (Promoter)

#### List My Properties
**GET** `/api/promoter/properties`

**Query Params:**
- `project_id` - Filter by project

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "project_id": 5,
            "title": "2-Bedroom Apartment",
            "type": "appartement",
            "price": 320000.00,
            "surface": 95.00,
            "bedrooms": 2,
            "bathrooms": 1,
            "availability_status": "available",
            "validated": true
        }
    ]
}
```

#### Create Property
**POST** `/api/promoter/projects/{projectId}/properties`

**Request:**
```json
{
    "title": "3-Bedroom Penthouse",
    "type": "appartement",
    "description": "Spacious penthouse",
    "floor": 8,
    "surface": 150.00,
    "bedrooms": 3,
    "bathrooms": 2,
    "price": 550000.00,
    "availability_status": "available",
    "features": ["Terrace", "Sea View"]
}
```

**Property Types:**
- `appartement`
- `villa`
- `duplex`
- `studio`
- `penthouse`
- `commercial`

**Availability Status:**
- `available`
- `reserved`
- `sold`
- `unavailable`

#### Get Property
**GET** `/api/promoter/properties/{id}`

#### Update Property
**POST** `/api/promoter/properties/{id}`

#### Delete Property
**DELETE** `/api/promoter/properties/{id}`

#### Update Availability
**PATCH** `/api/promoter/properties/{id}/availability`

**Request:**
```json
{
    "availability_status": "reserved"
}
```

---

### Leads (Promoter)

#### List Leads
**GET** `/api/promoter/leads`

**Query Params:**
- `status` - Filter: `new`, `contacted`, `qualified`, `converted`, `lost`
- `priority` - Filter: `low`, `medium`, `high`, `urgent`
- `project_id` - Filter by project

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Client Name",
            "email": "client@example.com",
            "phone": "+216 12 345 678",
            "message": "Interested in apartments",
            "status": "new",
            "priority": "medium",
            "project_id": 5,
            "created_at": "2025-10-14T10:00:00.000000Z",
            "project": {
                "id": 5,
                "name": "Résidence Marina Bay"
            }
        }
    ]
}
```

#### Get Lead Details
**GET** `/api/promoter/leads/{id}`

#### Get Lead Stats
**GET** `/api/promoter/leads/stats`

**Response:**
```json
{
    "total": 78,
    "by_status": {
        "new": 12,
        "contacted": 25,
        "qualified": 18,
        "converted": 15,
        "lost": 8
    },
    "conversion_rate": 19.23
}
```

#### Update Lead Status
**PATCH** `/api/promoter/leads/{id}/status`

**Request:**
```json
{
    "status": "contacted"
}
```

#### Update Lead Priority
**PATCH** `/api/promoter/leads/{id}/priority`

**Request:**
```json
{
    "priority": "high"
}
```

#### Update Lead Notes
**PUT** `/api/promoter/leads/{id}/notes`

**Request:**
```json
{
    "notes": "Called client, scheduled visit for Oct 20"
}
```

#### Delete Lead
**DELETE** `/api/promoter/leads/{id}`

---

## 🌐 Public Endpoints

**No authentication required**

### Projects

#### List Projects
**GET** `/api/projects`

**Query Params:**
- `city` - Filter by city
- `featured` - Filter: `true`, `false`
- `min_price`, `max_price` - Price range

**Example:** `/api/projects?city=Tunis&featured=true`

**Response:**
```json
{
    "data": [
        {
            "id": 5,
            "name": "Résidence Marina Bay",
            "slug": "residence-marina-bay",
            "city": "Sousse",
            "starting_price": 250000.00,
            "cover_image": "projects/cover.jpg",
            "is_featured": true,
            "promoter": {
                "id": 7,
                "company_name": "Immobilier Tunisie",
                "logo": "logos/logo.png"
            }
        }
    ]
}
```

#### Get Project by ID
**GET** `/api/projects/{id}`

**Response:** Full project details

#### Get Project by Slug
**GET** `/api/projects/{slug}`

**Example:** `/api/projects/residence-marina-bay`

#### Get Project Properties
**GET** `/api/projects/{id}/properties`

**Query Params:**
- `type`, `bedrooms`, `min_price`, `max_price`

---

### Properties

#### List Properties
**GET** `/api/properties`

**Query Params:**
- `type` - Property type
- `city` - City name
- `bedrooms` - Number of bedrooms
- `min_price`, `max_price` - Price range
- `min_surface`, `max_surface` - Surface range

**Example:**
```
/api/properties?type=appartement&city=Tunis&bedrooms=2&max_price=350000
```

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "title": "2-Bedroom Apartment",
            "type": "appartement",
            "price": 320000.00,
            "surface": 95.00,
            "bedrooms": 2,
            "bathrooms": 1,
            "images": ["image1.jpg"],
            "project": {
                "id": 5,
                "name": "Résidence Marina Bay",
                "city": "Sousse"
            }
        }
    ]
}
```

#### Get Property Details
**GET** `/api/properties/{id}`

#### Get Similar Properties
**GET** `/api/properties/{id}/similar`

---

### Search

#### Search
**GET** `/api/search`

**Query Params:**
- `query` - Search term (optional)
- `type` - Filter: `project`, `property`

**Example:** `/api/search?query=residence&type=project`

**Response:**
```json
{
    "projects": [...],
    "properties": [...]
}
```

**Note:** If `query` is empty, returns featured projects.

#### Get Cities
**GET** `/api/cities`

**Response:**
```json
{
    "cities": ["Tunis", "Sousse", "Sfax", "Hammamet"]
}
```

#### Get Property Types
**GET** `/api/property-types`

**Response:**
```json
{
    "types": ["appartement", "villa", "duplex", "studio", "penthouse"]
}
```

#### Get Filter Options
**GET** `/api/filters/options`

**Response:**
```json
{
    "cities": ["Tunis", "Sousse"],
    "property_types": ["appartement", "villa"],
    "project_statuses": ["planning", "under_construction", "completed"],
    "bedrooms": [1, 2, 3, 4, 5]
}
```

---

### Lead Submission

#### Submit Lead
**POST** `/api/leads`

**Request:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+216 12 345 678",
    "message": "I'm interested in 2-bedroom apartments",
    "project_id": 5,
    "property_id": 1,
    "budget_min": 300000,
    "budget_max": 400000
}
```

**Required:**
- `name`
- `email` OR `phone` (at least one)
- `message`

**Response:**
```json
{
    "id": 150,
    "message": "Thank you! We'll contact you soon."
}
```

---

## 📦 Data Models

### Project Status Values
```typescript
'planning' | 'under_construction' | 'near_completion' | 'completed' | 'on_hold'
```

### Property Types
```typescript
'appartement' | 'villa' | 'duplex' | 'studio' | 'penthouse' | 'commercial'
```

### Property Availability
```typescript
'available' | 'reserved' | 'sold' | 'unavailable'
```

### Lead Status
```typescript
'new' | 'contacted' | 'qualified' | 'converted' | 'lost'
```

### Lead Priority
```typescript
'low' | 'medium' | 'high' | 'urgent'
```

---

## ⚠️ Error Handling

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthenticated
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

### Error Response Format
```json
{
    "message": "Error description",
    "errors": {
        "field_name": [
            "Validation error message"
        ]
    }
}
```

### Common Errors

**401 Unauthenticated:**
```json
{
    "message": "Unauthenticated."
}
```

**404 Not Found:**
```json
{
    "message": "No query results for model [Project] 999"
}
```

**422 Validation:**
```json
{
    "message": "The email field is required.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

---

## 📝 Important Notes for Frontend

### ⚠️ Critical Field Names

**Use EXACT field names - BE CAREFUL:**

#### Project
- ✅ `name` (NOT `title`)
- ✅ `is_published` (NOT `published`)
- ✅ `cover_image` (NOT `image`)

#### Property
- ✅ `title` (NOT `name`)
- ✅ `type` (NOT `property_type`)
- ✅ `availability_status` (NOT `status`)
- ✅ `surface` (NOT `area` or `size`)

### Boolean Values
All booleans are `true`/`false` (NOT 1/0):
- `is_published`
- `is_featured`
- `verified`
- `validated`

### Date Format
All dates are ISO 8601:
```
2025-10-14T13:09:42.000000Z
```

### File URLs
Prepend storage URL to file paths:
```
http://127.0.0.1:8000/storage/{file_path}
```

Example:
```javascript
const imageUrl = `http://127.0.0.1:8000/storage/${project.cover_image}`;
```

### Pagination
Standard Laravel pagination with:
- `data` - Array of items
- `current_page`, `last_page`
- `total` - Total items
- `per_page` - Items per page

### Headers Required
```javascript
{
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': 'Bearer ' + token  // For authenticated endpoints
}
```

### File Uploads
Use `FormData` for file uploads:
```javascript
const formData = new FormData();
formData.append('name', 'Project Name');
formData.append('cover_image', file);

// Header: Content-Type: multipart/form-data
```

---

## 🧪 Testing

### Test All Endpoints
```bash
cd D:\oussema\Nestify_2.0\nestify-backend
php quick_api_test.php
```

**Expected Result:**
```
RESULTS: 22 ✅  0 ❌
```

### Postman Collection
Import: `Nestify_Complete_Admin_Collection.postman_collection.json`

**Features:**
- Auto-saves token on login
- All endpoints pre-configured
- Example requests

---

## 🆘 Support

**Backend Location:** `D:\oussema\Nestify_2.0\nestify-backend`  
**Server:** `http://127.0.0.1:8000`  
**Laravel Version:** 12.0  
**PHP Version:** 8.2.12

**Start Server:**
```bash
cd D:\oussema\Nestify_2.0\nestify-backend
php artisan serve
```

**Admin Token (Ready to Use):**
```
58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12
```

---

**Last Updated:** October 14, 2025  
**Status:** ✅ All 22 endpoints tested and working  
**Ready for:** Frontend integration
