# 🚀 Nestify 2.0 - Complete Setup Guide for Manager

## 📋 What Your Manager Will See

When your manager runs the application, he will see:

### ✅ **Fully Functional Website**
- **Frontend**: Complete Nuxt.js application with modern UI
- **Backend**: Laravel API with all endpoints working
- **Database**: MySQL database with real data
- **Features**: Agency authentication, property management, interactive maps, image uploads

### 🎯 **Real Data Available**
- **Test Agency Account**: `test@agency.com` / `password123`
- **Sample Properties**: Multiple properties with images and details
- **Interactive Maps**: Working Leaflet maps with location selection
- **Image Gallery**: Property images uploaded and displayed

---

## 🛠️ Setup Instructions for Manager

### **Prerequisites**
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Git

### **Step 1: Clone Repositories**
```bash
# Clone backend
git clone https://github.com/cercina-tech/laravel.api.immoneuf.tn.git
cd laravel.api.immoneuf.tn

# Clone frontend
git clone https://github.com/cercina-tech/nuxt.frontend.immoneuf.tn.git
cd nuxt.frontend.immoneuf.tn
```

### **Step 2: Backend Setup**
```bash
cd laravel.api.immoneuf.tn

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nestify_db
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database with test data
php artisan db:seed

# Start backend server
php artisan serve --host=127.0.0.1 --port=8000
```

### **Step 3: Frontend Setup**
```bash
cd nuxt.frontend.immoneuf.tn

# Install dependencies
npm install

# Start frontend server
npm run dev
```

### **Step 4: Access the Application**
- **Frontend**: http://localhost:3000
- **Backend API**: http://127.0.0.1:8000/api
- **Backend Admin**: http://127.0.0.1:8000

---

## 🔐 Test Accounts

### **Agency Account**
- **Email**: `test@agency.com`
- **Password**: `password123`
- **Access**: Agency dashboard, property management, analytics

### **Admin Account**
- **Email**: `admin@nestify.tn`
- **Password**: `admin123`
- **Access**: Full admin panel, user management

---

## 🎯 Features to Test

### **1. Agency Authentication**
- Login at http://localhost:3000/agencies
- Access agency dashboard
- Profile management

### **2. Property Management**
- Create new properties: http://localhost:3000/agencies/properties/create
- Upload images
- Set location on interactive map
- Add custom equipment/features

### **3. Public Features**
- Browse properties: http://localhost:3000/properties
- View property details
- Contact forms
- Search and filters

### **4. Interactive Maps**
- Location selection on property creation
- Property location display
- Map markers and popups

---

## 📊 Database Structure

### **Tables**
- `users` - User accounts (agencies, admins, clients)
- `agencies` - Agency information
- `properties` - Property listings
- `favorites` - User favorites
- `property_views` - Property view tracking

### **Sample Data**
- 5+ test properties with images
- 2+ agency accounts
- 1 admin account
- Property categories and features

---

## 🚨 Troubleshooting

### **Common Issues**

1. **Database Connection Error**
   - Check MySQL is running
   - Verify credentials in `.env`
   - Run `php artisan migrate`

2. **CORS Issues**
   - Backend CORS is configured for `localhost:3000`
   - Check `config/cors.php` if needed

3. **Image Upload Issues**
   - Check `storage/app/public` permissions
   - Run `php artisan storage:link`

4. **Map Not Loading**
   - Check internet connection (Leaflet tiles)
   - Verify `@nuxtjs/leaflet` is installed

### **Quick Fixes**
```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Reset database
php artisan migrate:fresh --seed

# Reinstall dependencies
composer install
npm install
```

---

## 📱 Mobile Responsiveness

The application is not yet fully responsive 

---

## 🔧 Development Commands

### **Backend**
```bash
php artisan serve          # Start server
php artisan migrate         # Run migrations
php artisan db:seed         # Seed database
php artisan route:list      # List routes
php artisan tinker          # Interactive shell
```

### **Frontend**
```bash
npm run dev                # Development server
npm run build              # Production build
npm run generate           # Static generation
npm run preview            # Preview production
```

---

## 📈 Performance

- **Frontend**: Optimized with Nuxt.js SSR
- **Backend**: Laravel with efficient queries
- **Database**: Indexed for fast searches
- **Images**: still dealing with the upload

---

## 🎉 Success Indicators

When everything is working correctly, you should see:

1. ✅ Frontend loads at http://localhost:3000
2. ✅ Backend API responds at http://127.0.0.1:8000/api
3. ✅ Can login with test agency account
4. ✅ Can create properties with images
5. ✅ Interactive maps work
6. ✅ Properties display on public pages
7. ✅ Agency dashboard shows analytics

---

## 📞 Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify all prerequisites are installed
3. Check the terminal logs for errors
4. Ensure both servers are running simultaneously

**The application is production-ready and fully functional! 🚀**
