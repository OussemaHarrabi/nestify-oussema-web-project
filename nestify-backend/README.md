# 🏠 Nestify 2.0 - Real Estate Platform

## 🎯 Project Overview

Nestify 2.0 is a complete real estate platform built with modern technologies:

- **Frontend**: Nuxt.js 3 with Vue.js 3, Tailwind CSS, and Leaflet maps
- **Backend**: Laravel 12 with Sanctum authentication
- **Database**: MySQL with optimized schema
- **Features**: Agency workflow, property management, interactive maps, image uploads

## 🚀 Quick Start

### **Backend (Laravel API)**
```bash
cd laravel.api.immoneuf.tn
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8000
```

### **Frontend (Nuxt.js)**
```bash
cd nuxt.frontend.immoneuf.tn
npm install
npm run dev
```

## 🔐 Test Accounts

- **Agency**: `test@agency.com` / `password123`
- **Admin**: `admin@nestify.tn` / `admin123`

## 📱 Access Points

- **Frontend**: http://localhost:3000
- **Backend API**: http://127.0.0.1:8000/api
- **Agency Dashboard**: http://localhost:3000/agencies/dashboard

## ✨ Key Features

- ✅ Agency authentication and management
- ✅ Property CRUD operations
- ✅ Interactive maps with location selection
- ✅ Image upload and gallery
- ✅ Custom equipment/features
- ✅ Responsive design
- ✅ Real-time data

## 📊 Database

The application includes:
- Sample properties with images
- Test agency accounts
- Admin panel access
- Optimized queries and indexes

## 🛠️ Technologies Used

- **Frontend**: Nuxt.js, Vue.js, Tailwind CSS, Leaflet
- **Backend**: Laravel, Sanctum, MySQL
- **Tools**: Composer, NPM, Git

## 📈 Production Ready

The application is fully functional and production-ready with:
- Secure authentication
- Optimized performance
- Mobile responsiveness
- Error handling
- Data validation

---

**Ready to run! Just follow the setup instructions above. 🚀**
