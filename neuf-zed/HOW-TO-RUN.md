# 🚀 How to Run Neuf.tn Project - Backend & Frontend

## 📍 Project Locations

### Backend (Laravel):
```
Location: D:\oussema\Nestify_2.0\nestify-backend
Tech: Laravel with Sanctum (API)
Port: 8000
```

### Frontend (Nuxt.js):
```
Location: D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed
Tech: Nuxt 3 + Vue 3 + Tailwind
Port: 3003 (configured in nuxt.config.ts)
```

---

## ⚡ Quick Start (Development)

### Option 1: Using 2 Terminals (Recommended)

#### Terminal 1: Start Backend (Laravel)
```bash
# Navigate to backend
cd D:\oussema\Nestify_2.0\nestify-backend

# Start Laravel server
php artisan serve
```
**Backend will run on:** http://localhost:8000

#### Terminal 2: Start Frontend (Nuxt)
```bash
# Navigate to frontend
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"

# Start Nuxt dev server
npm run dev
```
**Frontend will run on:** http://localhost:3003

---

### Option 2: Using PowerShell Split

```powershell
# Terminal 1 (Left)
cd D:\oussema\Nestify_2.0\nestify-backend
php artisan serve

# Terminal 2 (Right)
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"
npm run dev
```

---

## 🔧 First Time Setup

### Backend Setup (Laravel)

1. **Navigate to backend:**
```bash
cd D:\oussema\Nestify_2.0\nestify-backend
```

2. **Install dependencies (if not done):**
```bash
composer install
```

3. **Configure environment:**
```bash
# Copy .env.example to .env (if .env doesn't exist)
copy .env.example .env

# Generate application key
php artisan key:generate
```

4. **Configure database in .env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nestify_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Run migrations:**
```bash
php artisan migrate
```

6. **Create storage link:**
```bash
php artisan storage:link
```

7. **IMPORTANT: Configure CORS:**
Open `config/cors.php` and update:
```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [
        'http://localhost:3003',  // Frontend URL
        'http://localhost:3000',
    ],
    
    'allowed_headers' => ['*'],
    
    'supports_credentials' => true,  // IMPORTANT!
];
```

8. **Start server:**
```bash
php artisan serve
```

---

### Frontend Setup (Nuxt)

1. **Navigate to frontend:**
```bash
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"
```

2. **Install dependencies (if not done):**
```bash
npm install
```

3. **Configure environment:**
Create `.env` file in the frontend root:
```env
# API Configuration
NUXT_PUBLIC_API_BASE=http://localhost:8000/api

# Storage URL (Backblaze or local)
NUXT_PUBLIC_STORAGE_URL=https://your-bucket.backblazeb2.com

# Or for local development:
# NUXT_PUBLIC_STORAGE_URL=http://localhost:8000/storage
```

4. **Start dev server:**
```bash
npm run dev
```

---

## 🌐 Access URLs

Once both servers are running:

### Frontend:
- **Homepage:** http://localhost:3003
- **Login:** http://localhost:3003/login
- **Register:** http://localhost:3003/register
- **Dashboard:** http://localhost:3003/dashboard
- **Search:** http://localhost:3003/search

### Backend:
- **API Base:** http://localhost:8000/api
- **Health Check:** http://localhost:8000/api/projects

---

## 🧪 Testing the Connection

### Step 1: Check Backend is Running
Open in browser: http://localhost:8000/api/projects

**Expected:** JSON response with projects list (or empty array)

### Step 2: Check Frontend is Running
Open in browser: http://localhost:3003

**Expected:** Homepage loads

### Step 3: Test API Connection
Open browser console (F12) on http://localhost:3003 and run:
```javascript
fetch('http://localhost:8000/api/projects')
  .then(r => r.json())
  .then(console.log)
```

**Expected:** No CORS errors, gets project data

### Step 4: Test Login Flow
1. Go to: http://localhost:3003/register
2. Fill in registration form
3. Submit
4. Should redirect to dashboard

---

## 📂 Project Structure Overview

```
D:\oussema\Nestify_2.0\
├── nestify-backend/                    ← Laravel Backend
│   ├── app/
│   │   ├── Http/Controllers/API/
│   │   │   ├── AuthController.php
│   │   │   ├── ProjectController.php
│   │   │   ├── PropertyController.php
│   │   │   └── LeadController.php
│   │   └── Models/
│   │       ├── User.php
│   │       ├── Project.php
│   │       └── Property.php
│   ├── config/
│   │   └── cors.php                   ← IMPORTANT: Configure this!
│   ├── routes/
│   │   └── api.php
│   └── .env                           ← Database config
│
└── nestify-tn-design/Neuf_design/Homepage Design for Neuf.tn/
    └── neuf-zed/                      ← Nuxt Frontend
        ├── components/
        ├── pages/
        ├── composables/
        │   └── api/                   ← API integration
        ├── middleware/                ← Auth guards
        ├── nuxt.config.ts            ← Port: 3003
        └── .env                       ← API URL config
```

---

## 🐛 Common Issues & Solutions

### Issue 1: CORS Errors
**Error:** "CORS policy: No 'Access-Control-Allow-Origin' header"

**Solution:**
1. Open `nestify-backend/config/cors.php`
2. Set `'supports_credentials' => true`
3. Add `'http://localhost:3003'` to `allowed_origins`
4. No restart needed (Laravel auto-reloads)

---

### Issue 2: Backend Not Starting
**Error:** "Address already in use"

**Solution:**
```bash
# Find process using port 8000
netstat -ano | findstr :8000

# Kill the process (replace PID)
taskkill /PID <PID> /F

# Or use different port
php artisan serve --port=8001
```

---

### Issue 3: Frontend Not Starting
**Error:** "Port 3003 is already in use"

**Solution:**
```bash
# Kill process using port 3003
netstat -ano | findstr :3003
taskkill /PID <PID> /F

# Or change port in nuxt.config.ts:
export default defineNuxtConfig({
  devServer: {
    port: 3004  // Use different port
  }
})
```

---

### Issue 4: Database Connection Failed
**Error:** "SQLSTATE[HY000] [1045] Access denied"

**Solution:**
1. Check MySQL is running
2. Verify credentials in `nestify-backend/.env`
3. Create database if doesn't exist:
```sql
CREATE DATABASE nestify_db;
```

---

### Issue 5: Node Modules Issues
**Error:** Module not found errors

**Solution:**
```bash
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"

# Clear cache and reinstall
Remove-Item -Recurse -Force node_modules
Remove-Item package-lock.json
npm install
```

---

### Issue 6: Composer Dependencies
**Error:** "Class not found"

**Solution:**
```bash
cd D:\oussema\Nestify_2.0\nestify-backend

# Update dependencies
composer install
composer dump-autoload
```

---

## 📝 Development Workflow

### Typical Daily Workflow:

1. **Start Backend:**
```bash
cd D:\oussema\Nestify_2.0\nestify-backend
php artisan serve
```
Leave this terminal running.

2. **Start Frontend:**
```bash
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"
npm run dev
```
Leave this terminal running.

3. **Open Browser:**
- Go to http://localhost:3003
- Open DevTools (F12) to see console logs

4. **Make Changes:**
- Edit files in your code editor
- Frontend auto-reloads on save
- Backend needs manual refresh (just reload page)

5. **When Done:**
- Press `Ctrl+C` in both terminals to stop servers

---

## 🔑 Authentication Testing

### Test Accounts:
Based on the mock data, you can create a test promoter:

**Registration:**
- Go to: http://localhost:3003/register
- Fill form with any data
- Submit → Creates account in database

**Demo Credentials (from mock):**
```
Email: demo@promoteur.tn
Password: demo123456
```

**After Login:**
- Redirects to: http://localhost:3003/dashboard
- Token stored in cookie (7 days)
- Can access protected routes

---

## 📊 Database Seeding (Optional)

To populate database with sample data:

```bash
cd D:\oussema\Nestify_2.0\nestify-backend

# Create seeder
php artisan make:seeder ProjectsSeeder

# Run seeders
php artisan db:seed
```

---

## 🚀 Production Build

### Frontend (Build for Production):
```bash
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"

# Build
npm run build

# Preview production build
npm run preview
```

### Backend (Production Setup):
```bash
cd D:\oussema\Nestify_2.0\nestify-backend

# Optimize
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📞 Need Help?

### Check Logs:

**Backend Logs:**
```bash
cd D:\oussema\Nestify_2.0\nestify-backend
type storage\logs\laravel.log
```

**Frontend Console:**
- Open browser DevTools (F12)
- Check Console tab for errors
- Check Network tab for API calls

### Documentation Files:
- `INTEGRATION-STATUS.md` - API setup status
- `CORS-SETUP.md` - CORS configuration
- `TESTING-GUIDE.md` - Testing instructions
- `LAUNCH-PLAN-5-DAYS.md` - Full launch plan

---

## ✅ Checklist Before Starting Development

### Backend Ready:
- [ ] Laravel installed (`composer install`)
- [ ] Database configured in `.env`
- [ ] Migrations run (`php artisan migrate`)
- [ ] Storage linked (`php artisan storage:link`)
- [ ] CORS configured in `config/cors.php`
- [ ] Server starts: `php artisan serve`

### Frontend Ready:
- [ ] Dependencies installed (`npm install`)
- [ ] `.env` file created with API URL
- [ ] Dev server starts: `npm run dev`
- [ ] Can access http://localhost:3003

### Integration Working:
- [ ] No CORS errors in browser console
- [ ] API calls reach backend
- [ ] Registration flow works
- [ ] Login flow works
- [ ] Dashboard loads

---

## 🎯 Summary

**To run the full project:**

```bash
# Terminal 1: Backend
cd D:\oussema\Nestify_2.0\nestify-backend
php artisan serve

# Terminal 2: Frontend  
cd "D:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"
npm run dev
```

**Then visit:** http://localhost:3003

**That's it! You're running both backend and frontend! 🎉**
