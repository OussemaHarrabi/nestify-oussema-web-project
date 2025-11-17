# Comprehensive API Testing Guide - Nestify Real Estate

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication Headers
For protected routes, include:
```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
Accept: application/json
```

---

## 🔐 Authentication Endpoints

### 1. Register User
**POST** `/api/register`

**Test Cases:**

#### Agency Registration:
```json
{
    "name": "Premium Real Estate",
    "email": "premium@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+216 12345678",
    "user_type": "agency",
    "company_name": "Premium Real Estate Agency",
    "website": "https://premium-estate.com",
    "description": "Leading real estate agency in Tunisia"
}
```

#### Client Registration:
```json
{
    "name": "Ahmed Ben Ali",
    "email": "ahmed@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+216 98765432",
    "user_type": "client"
}
```

### 2. Login
**POST** `/api/login`

**Test with existing users:**
```json
{
    "email": "contact@belkacem-immo.tn",
    "password": "password"
}
```

```json
{
    "email": "client1@example.com",
    "password": "password"
}
```

```json
{
    "email": "admin@nestify.tn",
    "password": "password"
}
```

### 3. Get Current User
**GET** `/api/user`
*Requires Authentication*

### 4. Logout
**POST** `/api/logout`
*Requires Authentication*

---

## 🏠 Property Endpoints with Advanced Filtering

### 1. Get All Properties (Public)
**GET** `/api/properties`

#### Basic Filters:

**Search by Text:**
```
GET /api/properties?search=appartement
GET /api/properties?search=Sousse
GET /api/properties?search=moderne
```

**Price Filters:**
```
GET /api/properties?min_price=100000&max_price=500000
GET /api/properties?min_price=250000
GET /api/properties?max_price=400000
```

**Surface Filters:**
```
GET /api/properties?min_surface=80&max_surface=150
GET /api/properties?min_surface=100
```

#### Property Type Filters:

**Single Type:**
```
GET /api/properties?type=Appartement
GET /api/properties?type=Villa
GET /api/properties?type=Studio
```

**Multiple Types (Array):**
```
GET /api/properties?type[]=Appartement&type[]=Villa
GET /api/properties?type[]=Studio&type[]=Duplex
```

#### Room Filters:

**Bedrooms:**
```
GET /api/properties?bedrooms=2
GET /api/properties?min_bedrooms=2&max_bedrooms=4
GET /api/properties?min_bedrooms=3
```

**Bathrooms:**
```
GET /api/properties?bathrooms=2
GET /api/properties?min_bathrooms=1
```

**Total Rooms:**
```
GET /api/properties?total_rooms=4
```

#### Location Filters:

**Single City:**
```
GET /api/properties?city=Sousse
GET /api/properties?city=Tunis
```

**Multiple Cities:**
```
GET /api/properties?city[]=Sousse&city[]=Tunis
GET /api/properties?city[]=Sfax&city[]=Monastir
```

**District Filter:**
```
GET /api/properties?district=Sahloul
GET /api/properties?district[]=Centre Ville&district[]=Menzah
```

#### Building Features:

**Basic Features:**
```
GET /api/properties?parking=1
GET /api/properties?elevator=1
GET /api/properties?terrace=1
GET /api/properties?garden=1
```

**Combined Features:**
```
GET /api/properties?parking=1&elevator=1&terrace=1
```

#### Advanced Features (JSON Search):

**Single Feature:**
```
GET /api/properties?piscine=1
GET /api/properties?garage=1
GET /api/properties?climatisation=1
GET /api/properties?meuble=1
GET /api/properties?cuisine_equipee=1
GET /api/properties?chauffage=1
GET /api/properties?securite=1
```

**Multiple Features:**
```
GET /api/properties?features[]=Piscine&features[]=Garage
GET /api/properties?features[]=Climatisation&features[]=Ascenseur&features[]=Garage
```

**Combined Advanced Features:**
```
GET /api/properties?piscine=1&garage=1&climatisation=1
```

#### Floor Filters:
```
GET /api/properties?min_floor=1&max_floor=5
GET /api/properties?min_floor=2
```

#### VEFA Filter:
```
GET /api/properties?is_vefa=1
GET /api/properties?is_vefa=0
```

#### Date Filters:
```
GET /api/properties?published_after=2024-01-01
GET /api/properties?published_before=2024-12-31
```

#### Sorting Options:

**By Price:**
```
GET /api/properties?sort_by=price&sort_order=asc
GET /api/properties?sort_by=price&sort_order=desc
```

**By Surface:**
```
GET /api/properties?sort_by=surface&sort_order=desc
GET /api/properties?sort_by=surface&sort_order=asc
```

**By Date:**
```
GET /api/properties?sort_by=created_at&sort_order=desc
GET /api/properties?sort_by=published_date&sort_order=desc
```

**By Views:**
```
GET /api/properties?sort_by=views&sort_order=desc
```

**By Rooms:**
```
GET /api/properties?sort_by=bedrooms&sort_order=desc
GET /api/properties?sort_by=bathrooms&sort_order=desc
```

#### Pagination:
```
GET /api/properties?per_page=20
GET /api/properties?page=2&per_page=15
```

#### Complex Filter Combinations:

**Luxury Apartments in Sousse:**
```
GET /api/properties?type=Appartement&city=Sousse&min_price=300000&min_bedrooms=3&piscine=1&garage=1&climatisation=1&sort_by=price&sort_order=desc
```

**Family Homes with Garden:**
```
GET /api/properties?type[]=Villa&type[]=Maison&min_bedrooms=3&garden=1&parking=1&min_surface=120&sort_by=surface&sort_order=desc
```

**Modern Studios for Investment:**
```
GET /api/properties?type=Studio&max_price=150000&elevator=1&climatisation=1&city[]=Tunis&city[]=Sousse&sort_by=created_at&sort_order=desc
```

**Properties with Premium Features:**
```
GET /api/properties?piscine=1&garage=1&securite=1&cuisine_equipee=1&min_price=400000&sort_by=views&sort_order=desc
```

### 2. Get Filter Options
**GET** `/api/properties/filters/options`

Returns available filter options:
- Available property types
- Available cities and districts
- Price ranges (min/max)
- Surface ranges
- Bedroom/bathroom ranges
- Available features list
- Sort options

### 3. Get Statistics
**GET** `/api/properties/statistics/overview`

Returns market statistics:
- Total properties
- Average price/surface
- Properties by type
- Properties by city
- Price distribution
- Recent properties count

### 4. Search Suggestions
**GET** `/api/properties/search/suggestions?q=sou`

Returns search suggestions for autocomplete:
```
GET /api/properties/search/suggestions?q=apart
GET /api/properties/search/suggestions?q=tunis
GET /api/properties/search/suggestions?q=villa
```

### 5. Get Single Property
**GET** `/api/properties/{id}`

Test with existing IDs:
```
GET /api/properties/1
GET /api/properties/2
GET /api/properties/5
```

### 6. Create Property (Protected)
**POST** `/api/properties`
*Requires Authentication*

```json
{
    "title": "Appartement moderne à Tunis Centre",
    "description": "Magnifique appartement moderne situé au cœur de Tunis...",
    "price": 280000,
    "type": "Appartement",
    "surface": 95,
    "city": "Tunis",
    "district": "Centre Ville",
    "address": "Avenue Habib Bourguiba",
    "latitude": 36.8065,
    "longitude": 10.1815,
    "rooms": 4,
    "bedrooms": 3,
    "bathrooms": 2,
    "floor": 3,
    "total_floors": 8,
    "parking": true,
    "elevator": true,
    "terrace": true,
    "garden": false,
    "features": ["Climatisation", "Cuisine équipée", "Garage", "Sécurité"],
    "is_vefa": false,
    "images": [
        "https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Appartement+Tunis"
    ]
}
```

### 7. Update Property (Protected)
**PUT** `/api/properties/{id}`
*Requires Authentication - Owner or Admin only*

### 8. Delete Property (Protected)
**DELETE** `/api/properties/{id}`
*Requires Authentication - Owner or Admin only*

### 9. Get My Properties (Protected)
**GET** `/api/my-properties`
*Requires Authentication*

---

## ❤️ Favorites Endpoints

### 1. Get User Favorites (Protected)
**GET** `/api/favorites`
*Requires Authentication*

### 2. Toggle Favorite (Protected)
**POST** `/api/favorites/{propertyId}`
*Requires Authentication*

Test with property IDs:
```
POST /api/favorites/1
POST /api/favorites/2
POST /api/favorites/5
```

### 3. Check if Favorited (Protected)
**GET** `/api/favorites/check/{propertyId}`
*Requires Authentication*

```
GET /api/favorites/check/1
GET /api/favorites/check/2
```

---

## 🧪 Complete Testing Scenarios

### Scenario 1: User Registration & Property Search
1. Register as client
2. Login and get token
3. Search for apartments under 300k with parking
4. Get filter options
5. Add properties to favorites

### Scenario 2: Agency Property Management
1. Login as agency
2. Create new property with features
3. Get my properties
4. Update property details
5. View property statistics

### Scenario 3: Advanced Property Filtering
1. Search luxury properties (price > 400k, pool, garage)
2. Filter by multiple cities
3. Sort by price descending
4. Filter by specific features combination
5. Get properties with pagination

### Scenario 4: Market Analysis
1. Get filter options for dropdown menus
2. Get market statistics
3. Search properties by different criteria
4. Test search suggestions
5. Analyze price distributions

---

## 📋 Postman Collection Setup

### Environment Variables:
- `base_url`: `http://127.0.0.1:8000/api`
- `token`: Set after login response

### Headers Template:
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {{token}}
```

### Tests to Add in Postman:
```javascript
// For login endpoint
if (pm.response.code === 200) {
    const response = pm.response.json();
    pm.environment.set("token", response.token);
}

// For property listings
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has data array", function () {
    const jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
    pm.expect(jsonData.data).to.be.an('array');
});
```

---

## 🎯 Expected Results

### Properties Endpoint Response Format:
```json
{
    "data": [
        {
            "_id": "nestify_1",
            "url": "http://127.0.0.1:8000/properties/1",
            "title": "Property Title",
            "price": 350000,
            "type": "Appartement",
            "surface": 116,
            "location_id": {
                "city": "Sousse",
                "district": "Sahloul"
            },
            "apartment_details_id": {
                "bedrooms": 2,
                "bathrooms": 2,
                "features": ["Garage", "Piscine"]
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 12,
        "total": 36
    },
    "filters_applied": {
        "min_price": 100000,
        "type": "Appartement",
        "piscine": 1
    }
}
```

Start testing with these endpoints and let me know if you need any clarification or encounter any issues!
