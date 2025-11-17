# Nestify Real Estate API Documentation

## Overview

The Nestify API is a comprehensive real estate platform that supports three types of users:
- **Regular Users**: Browse, search, and favorite properties
- **Agency Users**: Manage properties, view analytics, and track leads
- **Admin Users**: Platform administration, user management, and system oversight

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication

The API uses Laravel Sanctum for authentication. After login, include the Bearer token in the Authorization header:
```
Authorization: Bearer {your-token-here}
```

## User Types

1. **regular_user**: Standard users who browse and favorite properties
2. **agency**: Real estate agencies who can list and manage properties
3. **admin**: Platform administrators with full access

## API Endpoints

### Authentication Endpoints

#### POST /register
Register a new user account.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "user_type": "regular_user",
    "phone": "+1234567890",
    "city": "Tunis"
}
```

**Response:**
```json
{
    "message": "User registered successfully",
    "user": {...},
    "token": "your-auth-token"
}
```

#### POST /login
Authenticate user and receive access token.

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": {...},
    "token": "your-auth-token"
}
```

#### GET /user
Get current authenticated user information.

**Headers:** Authorization: Bearer {token}

**Response:**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "user_type": "regular_user",
    "created_at": "2025-09-19T14:30:00.000000Z"
}
```

#### PUT /profile
Update user profile information.

**Headers:** Authorization: Bearer {token}

**Request Body:**
```json
{
    "name": "Updated Name",
    "phone": "+1234567890",
    "bio": "Updated bio",
    "city": "Tunis",
    "address": "123 Main St"
}
```

#### PUT /change-password
Change user password.

**Headers:** Authorization: Bearer {token}

**Request Body:**
```json
{
    "current_password": "current_password",
    "new_password": "new_password123"
}
```

#### POST /logout
Logout current session.

**Headers:** Authorization: Bearer {token}

#### POST /logout-all
Logout from all sessions.

**Headers:** Authorization: Bearer {token}

---

### Property Endpoints (Public)

#### GET /properties
Get paginated list of properties.

**Query Parameters:**
- `page` (int): Page number (default: 1)
- `per_page` (int): Items per page (default: 20, max: 100)
- `sort_by` (string): Sort field (price, created_at, title)
- `sort_direction` (string): asc or desc

**Response:**
```json
{
    "data": [...],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 20,
        "total": 95
    }
}
```

#### GET /properties/{id}
Get detailed property information.

**Response:**
```json
{
    "id": 1,
    "title": "Beautiful Villa",
    "description": "...",
    "price": 250000,
    "currency": "TND",
    "type": "Villa",
    "bedrooms": 3,
    "bathrooms": 2,
    "area": 180,
    "location": "Tunis",
    "images": [...],
    "user": {...},
    "created_at": "2025-09-19T14:30:00.000000Z"
}
```

#### GET /properties/search
Search properties with various criteria.

**Query Parameters:**
- `query` (string): Search term
- `location` (string): Location filter
- `min_price` (int): Minimum price
- `max_price` (int): Maximum price
- `type` (string): Property type
- `bedrooms` (int): Number of bedrooms
- `bathrooms` (int): Number of bathrooms

#### GET /properties/filter
Filter properties by specific criteria.

**Query Parameters:**
- `type` (string): Property type
- `bedrooms` (int): Number of bedrooms
- `bathrooms` (int): Number of bathrooms
- `area_min` (int): Minimum area
- `area_max` (int): Maximum area
- `location` (string): Location

#### GET /properties/suggestions
Get search suggestions for autocomplete.

**Query Parameters:**
- `query` (string): Partial search term

#### GET /properties/locations
Get available locations for filtering.

#### GET /properties/filter-options
Get available filter options (types, bedrooms, bathrooms, etc.).

#### GET /properties/statistics
Get property market statistics.

---

### User Features (Authenticated)

#### GET /user/preferences
Get user preferences and saved search criteria.

**Headers:** Authorization: Bearer {token}

#### PUT /user/preferences
Update user search preferences.

**Headers:** Authorization: Bearer {token}

**Request Body:**
```json
{
    "preferred_types": ["Villa", "Apartment"],
    "preferred_locations": ["Tunis", "Sousse"],
    "price_range": {
        "min": 100000,
        "max": 500000
    },
    "preferred_bedrooms": [2, 3, 4],
    "notifications": {
        "email": true,
        "push": false
    }
}
```

#### GET /user/recommendations
Get personalized property recommendations.

**Headers:** Authorization: Bearer {token}

**Query Parameters:**
- `limit` (int): Number of recommendations (default: 10)

#### GET /user/history
Get user activity history.

**Headers:** Authorization: Bearer {token}

**Query Parameters:**
- `type` (string): views, searches, or all
- `page` (int): Page number

---

### Favorites (Authenticated)

#### GET /favorites
Get user's favorite properties.

**Headers:** Authorization: Bearer {token}

#### POST /favorites
Add property to favorites.

**Headers:** Authorization: Bearer {token}

**Request Body:**
```json
{
    "property_id": 1
}
```

#### DELETE /favorites/{id}
Remove property from favorites.

**Headers:** Authorization: Bearer {token}

---

### Agency Features (Agency Users Only)

#### GET /agency/dashboard
Get agency dashboard with overview statistics.

**Headers:** Authorization: Bearer {token}

**User Type Required:** agency

#### GET /agency/analytics
Get detailed agency analytics.

**Headers:** Authorization: Bearer {token}

**User Type Required:** agency

**Query Parameters:**
- `period` (string): last_week, last_month, last_year

#### GET /agency/profile
Get agency profile information.

**Headers:** Authorization: Bearer {token}

**User Type Required:** agency

#### PUT /agency/profile
Update agency profile.

**Headers:** Authorization: Bearer {token}

**User Type Required:** agency

**Request Body:**
```json
{
    "company_name": "Agency Name",
    "website": "https://agency.com",
    "description": "Agency description",
    "addresses": [
        {
            "type": "main",
            "address": "123 Main St, Tunis",
            "city": "Tunis",
            "postal_code": "1000"
        }
    ],
    "additional_phones": ["+21698765432"]
}
```

#### POST /agency/upload-logo
Upload agency logo.

**Headers:** Authorization: Bearer {token}

**User Type Required:** agency

**Request Body:** FormData with 'logo' file field

#### GET /agency/properties
Get agency's properties with statistics.

**Headers:** Authorization: Bearer {token}

**User Type Required:** agency

**Query Parameters:**
- `page` (int): Page number
- `status` (string): all, active, inactive

#### GET /agency/leads
Get agency leads and customer interactions.

**Headers:** Authorization: Bearer {token}

**User Type Required:** agency

**Query Parameters:**
- `page` (int): Page number
- `period` (string): last_week, last_month, last_year

---

### Admin Features (Admin Users Only)

#### GET /admin/dashboard
Get admin dashboard with platform overview.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### GET /admin/analytics
Get comprehensive platform analytics.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### GET /admin/system-health
Get system health status and performance metrics.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### GET /admin/recent-activity
Get recent platform activity.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### GET /admin/users
Get paginated list of all users.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### GET /admin/user-management
Get detailed user management data.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### PATCH /admin/users/{id}/role
Update user role/type.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

**Request Body:**
```json
{
    "user_type": "agency"
}
```

#### PATCH /admin/users/{id}/ban
Ban a user from the platform.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### PATCH /admin/users/{id}/unban
Unban a user from the platform.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### DELETE /admin/users/{id}
Delete a user from the platform.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### GET /admin/properties
Get paginated list of all properties.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### GET /admin/properties/pending
Get properties pending validation.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### PATCH /admin/properties/{id}/validate
Validate/approve a property.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### PATCH /admin/properties/{id}/reject
Reject and delete a property.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### PATCH /admin/properties/{id}/toggle-status
Toggle property active/inactive status.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

**Request Body:**
```json
{
    "is_active": true
}
```

#### DELETE /admin/properties/{id}
Delete a property from the platform.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

#### GET /admin/agencies
Get agency management data.

**Headers:** Authorization: Bearer {token}

**User Type Required:** admin

---

## Error Responses

### 400 Bad Request
```json
{
    "message": "Validation error",
    "errors": {
        "field": ["Error message"]
    }
}
```

### 401 Unauthorized
```json
{
    "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
    "message": "Access denied. Insufficient permissions."
}
```

### 404 Not Found
```json
{
    "message": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
    "message": "Internal server error"
}
```

---

## Testing Credentials

### Admin User
- Email: `admin@nestify.tn`
- Password: `admin123`

### Agency User
- Email: `agency@nestify.tn`
- Password: `agency123`

### Regular User
- Email: `user@nestify.tn`
- Password: `user123`

---

## Database Schema

The API uses the following main tables:

### Users
- id, name, email, password, phone, user_type, is_active, preferences, profile_picture, bio, city, address, last_login_at, admin_role, permissions

### Agencies
- id, user_id, company_name, website, addresses, additional_phones, verified, description

### Properties
- id, user_id, title, description, price, currency, type, bedrooms, bathrooms, area, location, images, validated, created_at, updated_at

### Favorites
- id, user_id, property_id, created_at, updated_at

### UserPropertyViews
- id, user_id, property_id, created_at

### UserSearch
- id, user_id, query, location, filters, created_at

---

## Rate Limiting

The API implements rate limiting to prevent abuse:
- Authenticated requests: 100 requests per minute
- Unauthenticated requests: 60 requests per minute

---

## Pagination

All paginated endpoints return data in the following format:
```json
{
    "data": [...],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 20,
        "total": 95
    }
}
```

---

## File Uploads

File uploads (like agency logos) should be sent as multipart/form-data. Supported formats:
- Images: JPG, PNG, GIF (max 5MB)

---

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error