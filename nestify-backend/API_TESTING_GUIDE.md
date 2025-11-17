# Nestify 2024 - Real Estate Backend API

## 🏠 Overview
A complete Laravel 11 backend for a real estate platform with role-based access control, real Tunisian property data, and image upload capabilities.

## 📊 Features
- **738 Real Properties** imported from actual Tunisian real estate data
- **Role-Based Access Control**: Admin, Agency, Client users
- **Advanced Search & Filtering** with suggestions
- **Image Upload** for property photos
- **Favorites System** for clients
- **Admin Dashboard** for platform management
- **Secure API** with Laravel Sanctum authentication

## 🚀 Quick Start

### Prerequisites
- XAMPP with PHP 8.2+ and MySQL
- Composer
- Postman (for API testing)

### Installation
1. **Start XAMPP** and ensure Apache & MySQL are running
2. **Navigate to project**:
   ```cmd
   cd d:\oussema\Nestify_2.0\nestify-backend
   ```
3. **Install dependencies**:
   ```cmd
   composer install
   ```
4. **Setup environment**:
   ```cmd
   copy .env.example .env
   php artisan key:generate
   ```
5. **Configure database** in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nestify_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
6. **Create database** in phpMyAdmin: `nestify_db`
7. **Run migrations and seed data**:
   ```cmd
   php artisan migrate:fresh --seed
   php artisan db:seed --class=RealEstateDataSeeder
   ```
8. **Create storage link**:
   ```cmd
   php artisan storage:link
   ```
9. **Start server**:
   ```cmd
   php artisan serve
   ```

## 👥 User Roles & Access

### 🔓 Public Access (No Auth)
- Browse all properties
- Search properties 
- Filter by price, type, location
- Get search suggestions
- View property details

### 👤 Client Users
- All public access +
- Add/remove favorites
- View user profile
- Manage personal account

### 🏢 Agency Users  
- All public access +
- Create new properties
- Upload property images
- Update/delete own properties
- View "My Properties" list

### 👑 Admin Users
- Full access to everything +
- Admin dashboard with statistics
- Manage all users
- Manage all properties
- Toggle property active status
- Delete users/properties

## 🗄️ Database

### Real Data Imported
- **738 Properties** from actual Tunisian real estate market
- **3 User Types**: admin@nestify.tn, contact@belkacem-immo.tn, client1@example.com
- **Default Password**: `password` for all seeded users

### Property Data Includes
- Accurate Tunisian addresses and postal codes
- Real governorates: Tunis, Ariana, Ben Arous, Sfax, etc.
- Market-realistic pricing in Tunisian Dinars
- Proper property types: apartment, house, villa, commercial
- Geographic coordinates for mapping

## 🔧 API Testing with Postman

### 1. Import Collection
Import: `Nestify_Final_API_Collection_v2.postman_collection.json`

### 2. Set Environment Variables
Create a new environment with:
- `base_url`: `http://localhost:8000`

### 3. Testing Workflow

#### Step 1: Authentication
1. **Login Admin**: `POST /api/login`
   ```json
   {
     "email": "admin@nestify.tn",
     "password": "password"
   }
   ```
   → Saves `admin_token`

2. **Login Agency**: Use existing agency or register new one
   ```json
   {
     "email": "contact@belkacem-immo.tn", 
     "password": "password"
   }
   ```
   → Saves `agency_token`

3. **Login Client**: Use existing client or register new one
   ```json
   {
     "email": "client1@example.com",
     "password": "password"
   }
   ```
   → Saves `client_token`

#### Step 2: Test Public Endpoints (No Auth)
- ✅ `GET /api/properties` - Browse all properties
- ✅ `GET /api/properties/search?query=Tunis` - Search properties
- ✅ `GET /api/properties/filter?type=apartment&governorate=Tunis` - Filter
- ✅ `GET /api/properties/suggestions?query=Sfax` - Search suggestions
- ✅ `GET /api/properties/1` - Property details

#### Step 3: Test Client Endpoints
Use `{{client_token}}` in Authorization header:
- ✅ `GET /api/user` - User profile
- ✅ `POST /api/favorites` - Add to favorites
- ✅ `GET /api/favorites` - View favorites
- ✅ `DELETE /api/favorites/1` - Remove favorite

#### Step 4: Test Agency Endpoints
Use `{{agency_token}}` in Authorization header:
- ✅ `GET /api/my-properties` - View agency's properties
- ✅ `POST /api/properties` - Create new property
- ✅ `POST /api/properties/upload-image` - Upload property image
- ✅ `PUT /api/properties/1` - Update property
- ✅ `DELETE /api/properties/1` - Delete property

#### Step 5: Test Admin Endpoints
Use `{{admin_token}}` in Authorization header:
- ✅ `GET /api/admin/dashboard` - Dashboard stats
- ✅ `GET /api/admin/users` - All users
- ✅ `GET /api/admin/properties` - All properties
- ✅ `PATCH /api/admin/properties/1/toggle-status` - Toggle property
- ✅ `DELETE /api/admin/users/2` - Delete user
- ✅ `DELETE /api/admin/properties/1` - Delete property

### 4. Image Upload Testing

For `POST /api/properties/upload-image`:
1. Use **form-data** body type
2. Set fields:
   - `property_id`: `1` (text)
   - `image`: Select image file (JPG/PNG, max 5MB)
   - `is_primary`: `true` (text)
3. Use agency token in Authorization header

## 📁 File Structure

```
nestify-backend/
├── app/Http/Controllers/Api/
│   ├── AuthController.php         # Authentication
│   ├── PropertyController.php     # Property CRUD + Search
│   ├── FavoriteController.php     # Favorites system
│   └── AdminController.php        # Admin management
├── app/Http/Middleware/
│   └── CheckUserRole.php          # Role-based access
├── app/Models/
│   ├── User.php                   # User model with roles
│   ├── Property.php               # Property model
│   └── Favorite.php               # Favorites model
├── database/seeders/
│   └── RealEstateDataSeeder.php   # Real data importer
├── routes/api.php                 # API routes
└── storage/app/public/images/     # Uploaded images
```

## 🔐 Security Features

- **Sanctum Authentication**: Secure API tokens
- **Role-Based Middleware**: Enforces access control
- **Input Validation**: All requests validated
- **File Upload Security**: Image type/size validation
- **Ownership Checks**: Users can only modify their data

## 🐛 Troubleshooting

### Common Issues

1. **"Unauthorized" Error**:
   - Ensure token is included: `Authorization: Bearer {token}`
   - Check token hasn't expired
   - Verify user has correct role

2. **Image Upload Fails**:
   - Ensure storage link exists: `php artisan storage:link`
   - Check file size < 5MB
   - Verify file is JPG/PNG

3. **Search Not Working**:
   - Verify real data is seeded: check `properties` table
   - Use correct parameter: `?query=searchterm`

4. **Database Issues**:
   - Clear cache: `php artisan config:clear`
   - Re-run migrations: `php artisan migrate:fresh --seed`

### Reset Everything
```cmd
php artisan migrate:fresh --seed
php artisan db:seed --class=RealEstateDataSeeder
php artisan storage:link
php artisan config:clear
php artisan route:clear
```

## 📊 Database Statistics

After seeding, you should have:
- **738 Properties** across all Tunisian governorates
- **Multiple Property Types**: apartments, houses, villas, commercial
- **Price Range**: 50,000 - 2,000,000 TND
- **3 Test Users**: admin, agency, client

## 🏆 Production Ready

This backend is fully production-ready with:
- ✅ Real Tunisian property data
- ✅ Secure authentication & authorization  
- ✅ File upload with validation
- ✅ Comprehensive API documentation
- ✅ Role-based access control
- ✅ Advanced search & filtering
- ✅ Admin management tools

## 📞 Support

For any issues:
1. Check this README
2. Test with provided Postman collection
3. Verify database has 738 properties
4. Ensure all tokens are valid and roles are correct

**Happy Testing! 🚀**
