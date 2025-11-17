# Quick Start Testing Guide

## 🚀 Before You Start

### ⚠️ CRITICAL: Update CORS Configuration

**This MUST be done first or login/registration will fail!**

1. Open `D:\oussema\Nestify_2.0\nestify-backend\config\cors.php`
2. Find these two lines and change them:

```php
// CHANGE THIS:
'allowed_origins' => ['*'],

// TO THIS:
'allowed_origins' => [
    'http://localhost:3000',
    'http://127.0.0.1:3000',
],

// AND CHANGE THIS:
'supports_credentials' => false,

// TO THIS:
'supports_credentials' => true,
```

3. Save the file (no restart needed - Laravel auto-reloads in dev)

---

## 🏃 Start the Servers

### Terminal 1: Start Laravel Backend
```bash
cd D:\oussema\Nestify_2.0\nestify-backend
php artisan serve
```
Should see: `Server running on [http://127.0.0.1:8000]`

### Terminal 2: Start Nuxt Frontend
```bash
cd "d:\oussema\Nestify_2.0\nestify-tn-design\Neuf_design\Homepage Design for Neuf.tn\neuf-zed"
npm run dev
```
Should see: `Listening on http://localhost:3000`

---

## 🧪 Test Registration (New Promoter Signup)

1. **Open Browser:** http://localhost:3000/register

2. **Fill the form:**
   - Name: `Test Promoteur`
   - Email: `test@promoteur.tn`
   - Phone: `+216 XX XXX XXX`
   - Password: `testpass123`
   - Confirm Password: `testpass123`
   - Company Name: `Test Development SARL`
   - License Number: `LIC-2024-001` (optional)
   - Profile Picture: (optional - any image < 5MB)

3. **Accept terms** and click "S'inscrire"

4. **Expected Result:**
   - ✅ Green success message appears
   - ✅ Auto-redirect to `/dashboard` after 1.5 seconds
   - ✅ Cookies set: `auth_token`, `user`, `promoter`

5. **If it fails:**
   - Open browser DevTools (F12) → Console tab
   - Look for CORS errors (red text)
   - If you see CORS errors → Check you updated `cors.php` correctly
   - Check Network tab → Click failed request → See error details

---

## 🔐 Test Login (Existing User)

1. **Logout first** (if logged in):
   - Add logout button to dashboard OR
   - Clear cookies manually OR
   - Use Incognito window

2. **Open:** http://localhost:3000/login

3. **Enter credentials:**
   - Email: `test@promoteur.tn` (or existing promoter email from DB)
   - Password: `testpass123` (or correct password)

4. **Click "Se connecter"**

5. **Expected Result:**
   - ✅ Redirected to `/dashboard`
   - ✅ Dashboard shows "Tableau de bord" header
   - ✅ User menu appears in header

---

## 🐛 Troubleshooting

### CORS Error in Console
```
Access to fetch at 'http://localhost:8000/api/login' from origin 'http://localhost:3000' 
has been blocked by CORS policy
```

**Solution:** You didn't update `cors.php` correctly. Go back to step 1.

---

### Email Already Exists
```json
{
  "message": "The email has already been taken.",
  "errors": { "email": ["The email has already been taken."] }
}
```

**Solution:** Use a different email OR check your database and delete the test user:
```sql
DELETE FROM users WHERE email = 'test@promoteur.tn';
```

---

### 500 Internal Server Error on Registration
**Check Laravel logs:**
```bash
cd D:\oussema\Nestify_2.0\nestify-backend
type storage\logs\laravel.log
```

Common causes:
- Database connection issue
- Backblaze credentials missing in `.env`
- Image upload service not configured

---

### Registration Works but Login Fails
**Check:**
1. Password matches what you registered with
2. User exists in database: `SELECT * FROM users WHERE email = 'test@promoteur.tn'`
3. User is active: `is_active = 1`

---

### Dashboard Shows But No Data
**This is EXPECTED!** Currently the dashboard uses mock data.

Next step is to create `usePromoterApi` to fetch real data.

---

## ✅ Success Checklist

After successful registration + login, verify:

- [ ] Browser has cookie named `auth_token` (check DevTools → Application → Cookies)
- [ ] Cookie `user` contains your user data (JSON string)
- [ ] Cookie `promoter` contains your promoter profile
- [ ] Dashboard page loads at `/dashboard`
- [ ] You can navigate to `/dashboard/contacts`
- [ ] You can navigate to `/dashboard/create-project`
- [ ] Refresh page doesn't log you out (auth persists)

---

## 🎯 What Works Now

✅ Complete registration flow with file upload  
✅ Login/logout functionality  
✅ Authentication persistence (cookies)  
✅ Protected routes (redirect to login if not authenticated)  
✅ Promoter-only routes (redirect to home if not promoter)  

## 🚧 What's Next

After confirming auth works:

1. Create `usePromoterApi` composable for dashboard CRUD
2. Replace mock data in dashboard with real API calls
3. Implement project creation/editing
4. Implement property/listing management
5. Connect leads/contacts page to API

---

## 📞 Need Help?

If something doesn't work:

1. **Check browser console** for JavaScript errors
2. **Check Network tab** to see failed API requests
3. **Check Laravel logs** at `storage/logs/laravel.log`
4. **Verify CORS config** one more time
5. **Ensure both servers are running**

---

**Ready to test?** Start with the CORS configuration, then test registration! 🚀
