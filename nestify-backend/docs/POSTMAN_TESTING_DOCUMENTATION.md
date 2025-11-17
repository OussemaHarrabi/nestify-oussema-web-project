# 🏠 Nestify Real Estate API - Complete Documentation

## 📋 Table of Contents
1. [Project Overview](#project-overview)
2. [Setup Instructions](#setup-instructions)
3. [Postman Collection Import](#postman-collection-import)
4. [API Architecture](#api-architecture)
5. [Authentication System](#authentication-system)
6. [Property Management](#property-management)
7. [Advanced Filtering System](#advanced-filtering-system)
8. [Testing Scenarios](#testing-scenarios)
9. [Error Handling](#error-handling)
10. [Performance Notes](#performance-notes)

---

## 🎯 Project Overview

**Nestify Real Estate API** is a comprehensive backend system for real estate property management and search. Built with Laravel 11 and designed to be MongoDB-compatible, it provides advanced filtering capabilities and a complete user management system.

### Key Features
- 🔐 **Multi-role Authentication** (Admin, Agency, Client)
- 🏠 **Advanced Property Management** with CRUD operations
- 🔍 **Sophisticated Filtering System** with 20+ filter types
- ❤️ **Favorites System** for users
- 📊 **Market Statistics and Analytics**
- 🎯 **Search Suggestions and Autocomplete**
- 📱 **Mobile-friendly API design**
- 🌐 **MongoDB-compatible response format**

### Technology Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: SQLite (for development), MongoDB-ready
- **Authentication**: Laravel Sanctum
- **API Format**: RESTful JSON API
- **Testing**: Postman Collection with automated tests

---

## ⚙️ Setup Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- XAMPP or similar local server
- Postman (for API testing)

### Installation Steps

1. **Start the Laravel Server**
   ```bash
   cd d:\oussema\Nestify_2.0\nestify-backend
   php artisan serve
   ```

2. **Verify Server is Running**
   - Open browser: `http://127.0.0.1:8000`
   - Should see Laravel welcome page

3. **Test Basic API**
   - Visit: `http://127.0.0.1:8000/api/properties`
   - Should return JSON with property data

---

## 📥 Postman Collection Import

### Import the Collection

1. **Open Postman**
2. **Click "Import"**
3. **Select the file**: `Nestify_Real_Estate_API.postman_collection.json`
4. **Import the collection**

### Setup Environment

1. **Create New Environment**: "Nestify Local"
2. **Add Variables**:
   ```
   base_url: http://127.0.0.1:8000/api
   token: (will be set automatically after login)
   user_id: (will be set automatically after login)
   property_id: (will be set automatically)
   ```

### Test Collection Structure
```
📁 Nestify Real Estate API
├── 🔐 Authentication (6 requests)
├── 🏠 Properties - Basic Operations (5 requests)
├── 🔍 Properties - Advanced Filtering (12 requests)
├── 📊 Properties - Utility Endpoints (3 requests)
└── ❤️ Favorites (3 requests)
```

---

## 🏗️ API Architecture

### Base URL
```
http://127.0.0.1:8000/api
```

### Standard Response Format

#### Success Response
```json
{
    "data": [...],
    "message": "Success message",
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 12,
        "total": 60
    }
}
```

#### Error Response
```json
{
    "message": "Error message",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## 🔐 Authentication System

### User Types
1. **Admin** - Full system access, property validation
2. **Agency** - Create/manage properties, view analytics
3. **Client** - Browse properties, manage favorites

### Authentication Flow

#### 1. Register New User
```http
POST /api/register
Content-Type: application/json

{
    "name": "Agency Name",
    "email": "agency@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "agency",
    "company_name": "Real Estate Co.",
    "website": "https://agency.com"
}
```

#### 2. Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "agency@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "Agency Name",
        "email": "agency@example.com",
        "user_type": "agency"
    },
    "token": "1|abcdef123456..."
}
```

#### 3. Use Token for Protected Routes
```http
Authorization: Bearer 1|abcdef123456...
```

### Pre-seeded Test Accounts
```
Agency:   contact@belkacem-immo.tn / password
Client:   client1@example.com / password
Admin:    admin@nestify.tn / password
```

---

## 🏠 Property Management

### Property Data Structure (MongoDB Compatible)
```json
{
    "_id": "nestify_1",
    "url": "http://127.0.0.1:8000/properties/1",
    "title": "Appartement à vendre à Sahloul 4",
    "description": "Découvrez la Résidence Panamera III...",
    "published_date": "13 Jul 2025",
    "reference": "NEST_ABC12345",
    "price": 350000,
    "type": "Appartement",
    "surface": 116,
    "images": ["https://example.com/image1.jpg"],
    "views": 25,
    "validated": true,
    "location_id": {
        "address": "",
        "city": "Sousse",
        "district": "Sahloul",
        "coordinates": {
            "lat": 35.8283900976025,
            "lng": 10.600180292889547
        }
    },
    "apartment_details_id": {
        "rooms": 3,
        "bedrooms": 2,
        "bathrooms": 2,
        "floor": 1,
        "parking": true,
        "elevator": true,
        "features": ["Garage", "Piscine", "Climatisation"]
    },
    "promoter_id": {
        "name": "Société immobiliere Belkacem",
        "contact": {
            "phone": "+216 25925200",
            "email": "contact@belkacem-immo.tn"
        }
    }
}
```

### CRUD Operations

#### Create Property
```http
POST /api/properties
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Moderne Appartement",
    "description": "Description détaillée...",
    "price": 280000,
    "type": "Appartement",
    "surface": 95,
    "city": "Tunis",
    "bedrooms": 3,
    "bathrooms": 2,
    "features": ["Climatisation", "Garage"]
}
```

#### Update Property
```http
PUT /api/properties/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "price": 290000,
    "title": "Updated Title"
}
```

#### Delete Property
```http
DELETE /api/properties/{id}
Authorization: Bearer {token}
```

---

## 🔍 Advanced Filtering System

### Filter Categories

#### 1. **Text Search**
```http
GET /api/properties?search=appartement
GET /api/properties?search=Sousse
```

#### 2. **Price Filters**
```http
GET /api/properties?min_price=100000&max_price=500000
GET /api/properties?min_price=250000
```

#### 3. **Property Type**
```http
# Single type
GET /api/properties?type=Appartement

# Multiple types
GET /api/properties?type[]=Appartement&type[]=Villa
```

#### 4. **Room Filters**
```http
# Bedrooms (excluding living room)
GET /api/properties?min_bedrooms=2&max_bedrooms=4

# Bathrooms
GET /api/properties?min_bathrooms=1

# Total rooms
GET /api/properties?total_rooms=4
```

#### 5. **Location Filters**
```http
# Single city
GET /api/properties?city=Sousse

# Multiple cities
GET /api/properties?city[]=Sousse&city[]=Tunis

# District
GET /api/properties?district=Sahloul
```

#### 6. **Surface Area**
```http
GET /api/properties?min_surface=80&max_surface=150
```

#### 7. **Basic Features**
```http
GET /api/properties?parking=1&elevator=1&terrace=1&garden=1
```

#### 8. **Premium Features**
```http
# Individual features
GET /api/properties?piscine=1
GET /api/properties?garage=1
GET /api/properties?climatisation=1
GET /api/properties?meuble=1
GET /api/properties?cuisine_equipee=1

# Multiple features
GET /api/properties?piscine=1&garage=1&climatisation=1
```

#### 9. **Advanced Features (JSON Search)**
```http
# Array-based feature search
GET /api/properties?features[]=Piscine&features[]=Garage
```

#### 10. **Floor Filters**
```http
GET /api/properties?min_floor=1&max_floor=5
```

#### 11. **VEFA Properties**
```http
GET /api/properties?is_vefa=1
```

#### 12. **Date Filters**
```http
GET /api/properties?published_after=2024-01-01&published_before=2024-12-31
```

### Sorting Options

```http
# By price
GET /api/properties?sort_by=price&sort_order=asc
GET /api/properties?sort_by=price&sort_order=desc

# By surface
GET /api/properties?sort_by=surface&sort_order=desc

# By date
GET /api/properties?sort_by=created_at&sort_order=desc
GET /api/properties?sort_by=published_date&sort_order=desc

# By popularity
GET /api/properties?sort_by=views&sort_order=desc

# By rooms
GET /api/properties?sort_by=bedrooms&sort_order=desc
```

### Pagination
```http
GET /api/properties?per_page=20&page=2
```

### Complex Filter Combinations

#### Luxury Apartments Search
```http
GET /api/properties?type=Appartement&city=Sousse&min_price=300000&min_bedrooms=3&piscine=1&garage=1&climatisation=1&sort_by=price&sort_order=desc
```

#### Family Homes Search
```http
GET /api/properties?type[]=Villa&type[]=Maison&min_bedrooms=3&garden=1&parking=1&min_surface=120&sort_by=surface&sort_order=desc
```

#### Investment Properties Search
```http
GET /api/properties?type=Studio&max_price=150000&elevator=1&climatisation=1&city[]=Tunis&city[]=Sousse&sort_by=created_at&sort_order=desc
```

---

## 📊 Utility Endpoints

### Get Filter Options
```http
GET /api/properties/filters/options
```

**Response:**
```json
{
    "types": ["Appartement", "Villa", "Maison", "Studio", "Duplex"],
    "cities": ["Tunis", "Sousse", "Sfax", "Monastir"],
    "districts": ["Centre Ville", "Sahloul", "Menzah"],
    "price_range": {"min": 50000, "max": 2000000},
    "surface_range": {"min": 30, "max": 500},
    "available_features": ["Garage", "Piscine", "Climatisation", ...]
}
```

### Get Market Statistics
```http
GET /api/properties/statistics/overview
```

**Response:**
```json
{
    "total_properties": 150,
    "average_price": 285000,
    "average_surface": 125,
    "properties_by_type": {
        "Appartement": 80,
        "Villa": 35,
        "Maison": 25
    },
    "price_distribution": {
        "under_100k": 15,
        "100k_300k": 65,
        "300k_500k": 45,
        "500k_1m": 20,
        "over_1m": 5
    }
}
```

### Search Suggestions
```http
GET /api/properties/search/suggestions?q=sou
```

**Response:**
```json
{
    "suggestions": [
        {"type": "city", "value": "Sousse", "label": "Sousse"},
        {"type": "district", "value": "Sahloul", "label": "Sahloul"},
        {"type": "property", "value": "123", "label": "Appartement à Sousse"}
    ]
}
```

---

## 🧪 Testing Scenarios

### Scenario 1: Complete User Journey
1. **Register** as client
2. **Login** and save token
3. **Browse** all properties
4. **Filter** by preferences
5. **View** specific property
6. **Add** to favorites
7. **Check** favorites list

### Scenario 2: Agency Property Management
1. **Login** as agency
2. **Create** new property
3. **View** my properties
4. **Update** property details
5. **Check** market statistics

### Scenario 3: Advanced Search Testing
1. **Test** basic filters (price, type, location)
2. **Test** room filters (bedrooms, bathrooms)
3. **Test** feature filters (pool, garage, AC)
4. **Test** complex combinations
5. **Test** sorting options
6. **Test** pagination

### Scenario 4: Filter Options and Statistics
1. **Get** filter options for UI
2. **Get** market statistics
3. **Test** search suggestions
4. **Verify** response formats

---

## ⚠️ Error Handling

### Common Error Responses

#### Validation Error (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "price": ["The price must be a number."]
    }
}
```

#### Authentication Error (401)
```json
{
    "message": "Unauthenticated."
}
```

#### Not Found Error (404)
```json
{
    "message": "Property not found."
}
```

#### Forbidden Error (403)
```json
{
    "message": "This action is unauthorized."
}
```

---

## 🚀 Performance Notes

### Optimization Features
- **Database Indexing** on key search fields
- **Pagination** to limit response size
- **Eager Loading** for related models
- **JSON Search** for feature arrays
- **Response Caching** for filter options

### Best Practices
- Use specific filters to reduce result sets
- Implement pagination for large datasets
- Cache filter options on frontend
- Use search suggestions for better UX

---

## 📝 Testing Checklist

### Authentication Tests
- [ ] Register agency user
- [ ] Register client user
- [ ] Login with valid credentials
- [ ] Login with invalid credentials
- [ ] Access protected route with token
- [ ] Access protected route without token
- [ ] Logout user

### Property Tests
- [ ] Get all properties
- [ ] Get single property
- [ ] Create property (as agency)
- [ ] Update property (as owner)
- [ ] Delete property (as owner)
- [ ] Try to update others' property (should fail)

### Filter Tests
- [ ] Text search
- [ ] Price range filtering
- [ ] Property type filtering
- [ ] Bedroom/bathroom filtering
- [ ] Location filtering
- [ ] Feature filtering
- [ ] Complex filter combinations
- [ ] Sorting options
- [ ] Pagination

### Utility Tests
- [ ] Get filter options
- [ ] Get market statistics
- [ ] Search suggestions
- [ ] API response times

### Favorites Tests
- [ ] Add property to favorites
- [ ] Remove property from favorites
- [ ] Get user favorites
- [ ] Check favorite status

---

## 🎯 Expected Results

After running all tests, you should see:
- ✅ All authentication flows working
- ✅ Property CRUD operations functioning
- ✅ Advanced filtering working with multiple combinations
- ✅ Proper error handling for invalid requests
- ✅ Consistent MongoDB-compatible response format
- ✅ Fast response times (< 2 seconds)

---

## 📞 Support

If you encounter any issues:
1. Check server is running on `http://127.0.0.1:8000`
2. Verify token is set in Postman environment
3. Check request headers include `Accept: application/json`
4. Review error responses for specific issues
5. Test with provided seeded user accounts

**Happy Testing! 🚀**
