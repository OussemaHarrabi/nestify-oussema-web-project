# 🔧 Technical Integration Guide - Main Platform ↔ Neuf.tn

## Overview
This document explains how to integrate Neuf.tn with your main real estate platform so that promoters can seamlessly register on the main platform and manage their projects on Neuf.tn.

---

## 🏗️ ARCHITECTURE OPTIONS

### Option 1: Microservices with Shared Database (Recommended)
```
┌────────────────────────────────────────────────────────────┐
│                    Shared Database                         │
│  ┌─────────────────────────────────────────────────────┐  │
│  │  users table (shared)                               │  │
│  │  projects table (Neuf.tn)                          │  │
│  │  properties table (Neuf.tn)                        │  │
│  │  leads table (Neuf.tn)                             │  │
│  └─────────────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────────────┘
         ↑                                    ↑
         │                                    │
┌────────────────┐                 ┌────────────────────┐
│ Main Platform  │                 │     Neuf.tn        │
│ (example.com)  │                 │ (neuf.example.com) │
│                │                 │                    │
│ - Registration │                 │ - Project CRUD     │
│ - User Mgmt    │                 │ - Image Upload     │
│ - Dashboard    │                 │ - Search           │
│ - Payments     │                 │ - Lead Mgmt        │
└────────────────┘                 └────────────────────┘
```

**Pros:**
- Simple data access
- No API calls needed
- Instant data consistency
- Easy to implement

**Cons:**
- Tight coupling
- Both need same DB credentials
- Scaling is harder

---

### Option 2: API-Based Integration (Better for Scale)
```
┌────────────────┐                 ┌────────────────────┐
│ Main Platform  │                 │     Neuf.tn        │
│   Database     │                 │    Database        │
│                │                 │                    │
│  - users       │                 │  - projects        │
│  - orders      │                 │  - properties      │
│  - payments    │                 │  - leads           │
└────────────────┘                 └────────────────────┘
         ↑                                    ↑
         │                                    │
┌────────────────┐  API Calls      ┌────────────────────┐
│ Main Platform  │ ←────────────→  │     Neuf.tn        │
│   (Backend)    │                 │    (Backend)       │
└────────────────┘                 └────────────────────┘
         ↓                                    ↓
         │                                    │
┌────────────────┐                 ┌────────────────────┐
│ Main Platform  │                 │  Neuf.tn Frontend  │
│   Frontend     │                 │                    │
└────────────────┘                 └────────────────────┘
```

**Pros:**
- Loose coupling
- Independent scaling
- Better security
- Clear boundaries

**Cons:**
- More complex
- API latency
- Need API authentication

---

## 🔐 AUTHENTICATION INTEGRATION

### Method 1: JWT Tokens (Recommended for SSO)

#### Flow:
```
1. User logs into Main Platform
   ↓
2. Main Platform generates JWT token
   {
     "user_id": 123,
     "email": "promoter@example.com",
     "role": "promoter",
     "exp": 1640000000
   }
   ↓
3. User clicks "Manage Properties" on Main Platform
   ↓
4. Redirect to: neuf.example.com?token=JWT_TOKEN
   ↓
5. Neuf.tn validates JWT token
   ↓
6. If valid, logs user in automatically
```

#### Implementation:

**Main Platform (Generate Token):**
```php
// In your main platform
use Firebase\JWT\JWT;

public function generateNeufToken(User $user)
{
    $payload = [
        'iss' => 'main-platform.com',
        'sub' => $user->id,
        'email' => $user->email,
        'role' => $user->role,
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24 * 7) // 7 days
    ];
    
    $secret = env('JWT_SECRET'); // Shared secret
    return JWT::encode($payload, $secret, 'HS256');
}

// When user clicks "Manage Properties"
public function redirectToNeuf()
{
    $token = $this->generateNeufToken(auth()->user());
    return redirect('https://neuf.example.com?token=' . $token);
}
```

**Neuf.tn (Validate Token):**
```php
// In Neuf.tn AuthController
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

public function loginWithToken(Request $request)
{
    $token = $request->query('token');
    
    try {
        $secret = env('JWT_SECRET'); // Same secret as main platform
        $decoded = JWT::decode($token, new Key($secret, 'HS256'));
        
        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $decoded->email],
            [
                'name' => $decoded->name ?? '',
                'role' => $decoded->role,
                'external_id' => $decoded->sub // Main platform user ID
            ]
        );
        
        // Log user in
        auth()->login($user);
        
        return redirect('/dashboard');
        
    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Invalid token');
    }
}
```

**Frontend (Nuxt.js):**
```typescript
// In pages/index.vue or middleware
export default defineNuxtRouteMiddleware(async (to, from) => {
  const token = to.query.token
  
  if (token) {
    // Send token to backend for validation
    const { data } = await $fetch('/api/auth/validate-token', {
      method: 'POST',
      body: { token }
    })
    
    if (data.success) {
      // Store user data
      localStorage.setItem('user', JSON.stringify(data.user))
      localStorage.setItem('auth_token', data.auth_token)
      
      // Redirect to dashboard
      return navigateTo('/dashboard')
    }
  }
})
```

---

### Method 2: Shared Session (Same Domain Only)

#### Requirements:
- Both platforms on same root domain
- Example: `main.example.com` and `neuf.example.com`

#### Configuration:

**Laravel Session Config (Both Platforms):**
```php
// config/session.php
return [
    'driver' => 'database', // or redis
    'lifetime' => 120,
    'expire_on_close' => false,
    'domain' => '.example.com', // Note the leading dot!
    'secure' => true,
    'http_only' => true,
    'same_site' => 'lax',
];
```

**Shared Session Table:**
```sql
-- Same sessions table in shared database
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT,
    last_activity INT
);
```

**Flow:**
```
1. User logs into main.example.com
   → Session cookie set for .example.com
   
2. User navigates to neuf.example.com
   → Cookie is sent (same domain)
   
3. Neuf.tn reads session
   → User is already authenticated
```

---

### Method 3: API-Based Auth

#### Flow:
```
1. User enters credentials on Neuf.tn login page
   ↓
2. Neuf.tn sends credentials to Main Platform API
   POST https://api.main.com/auth/validate
   {
     "email": "user@example.com",
     "password": "password123"
   }
   ↓
3. Main Platform validates and returns user data
   {
     "success": true,
     "user": {
       "id": 123,
       "email": "user@example.com",
       "role": "promoter"
     }
   }
   ↓
4. Neuf.tn creates/updates local user record
   ↓
5. Issues its own session/token
```

**Implementation:**

**Main Platform API:**
```php
// routes/api.php
Route::post('/auth/validate', [AuthController::class, 'validateExternal']);

// AuthController.php
public function validateExternal(Request $request)
{
    $credentials = $request->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        // Only allow promoters
        if ($user->role !== 'promoter') {
            return response()->json([
                'success' => false,
                'message' => 'Not a promoter account'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'company_name' => $user->company_name,
                'phone' => $user->phone
            ]
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Invalid credentials'
    ], 401);
}
```

**Neuf.tn Backend:**
```php
// AuthController.php in Neuf.tn
public function login(Request $request)
{
    // Validate with main platform
    $response = Http::post(env('MAIN_PLATFORM_API') . '/auth/validate', [
        'email' => $request->email,
        'password' => $request->password
    ]);
    
    if ($response->successful() && $response->json('success')) {
        $userData = $response->json('user');
        
        // Create or update local user
        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'],
                'role' => $userData['role'],
                'external_id' => $userData['id'],
                'company_name' => $userData['company_name'],
                'phone' => $userData['phone']
            ]
        );
        
        // Issue JWT token
        $token = auth()->login($user);
        
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Invalid credentials'
    ], 401);
}
```

---

## 📊 DATABASE INTEGRATION

### Shared Database Approach

#### User Table (Shared):
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'promoter', 'user') DEFAULT 'user',
    company_name VARCHAR(255),
    company_logo VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    verified_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Connection Configuration:
```php
// Neuf.tn .env
DB_CONNECTION=mysql
DB_HOST=same-as-main-platform
DB_PORT=3306
DB_DATABASE=main_platform_db
DB_USERNAME=neuf_user
DB_PASSWORD=secure_password
```

**Grant Permissions:**
```sql
-- On main platform database
GRANT SELECT, INSERT, UPDATE ON main_platform_db.users TO 'neuf_user'@'%';
GRANT ALL PRIVILEGES ON main_platform_db.projects TO 'neuf_user'@'%';
GRANT ALL PRIVILEGES ON main_platform_db.properties TO 'neuf_user'@'%';
GRANT ALL PRIVILEGES ON main_platform_db.leads TO 'neuf_user'@'%';
GRANT ALL PRIVILEGES ON main_platform_db.project_images TO 'neuf_user'@'%';
```

---

### Separate Database with Sync

#### Neuf.tn Database:
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    external_id BIGINT, -- ID from main platform
    email VARCHAR(255) UNIQUE,
    name VARCHAR(255),
    role VARCHAR(50),
    -- Cached data from main platform
    company_name VARCHAR(255),
    phone VARCHAR(20),
    synced_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Sync Strategy:

**Option A: On Login (Simple)**
```php
public function syncUserData($mainPlatformUserId)
{
    // Fetch from main platform API
    $response = Http::get(env('MAIN_API') . "/users/{$mainPlatformUserId}");
    $userData = $response->json();
    
    // Update local user
    User::updateOrCreate(
        ['external_id' => $mainPlatformUserId],
        [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'company_name' => $userData['company_name'],
            'synced_at' => now()
        ]
    );
}
```

**Option B: Webhooks (Advanced)**
```php
// Main Platform sends webhook when user data changes
Route::post('/webhooks/user-updated', [WebhookController::class, 'userUpdated']);

public function userUpdated(Request $request)
{
    // Verify webhook signature
    if (!$this->verifySignature($request)) {
        abort(403);
    }
    
    $userData = $request->all();
    
    User::where('external_id', $userData['id'])->update([
        'name' => $userData['name'],
        'email' => $userData['email'],
        'company_name' => $userData['company_name'],
        'synced_at' => now()
    ]);
}
```

---

## 🔗 USER FLOW EXAMPLES

### Flow 1: New Promoter Registration

```
┌──────────────────────────────────────────────────────────┐
│ Step 1: Registration on Main Platform                   │
├──────────────────────────────────────────────────────────┤
│ User visits: https://main-platform.com/register         │
│ Fills form:                                              │
│   - Name: John Doe                                       │
│   - Email: john@example.com                              │
│   - Role: Promoter                                       │
│   - Company: Doe Properties                              │
│ Clicks "Register"                                        │
│                                                          │
│ → Account created in main platform database             │
│ → Role set to "promoter"                                │
└──────────────────────────────────────────────────────────┘
                          ↓
┌──────────────────────────────────────────────────────────┐
│ Step 2: Email Confirmation                               │
├──────────────────────────────────────────────────────────┤
│ User receives email:                                     │
│   "Welcome! Click here to verify your account"          │
│                                                          │
│ User clicks link                                         │
│ → Account verified                                       │
└──────────────────────────────────────────────────────────┘
                          ↓
┌──────────────────────────────────────────────────────────┐
│ Step 3: Access Neuf.tn                                   │
├──────────────────────────────────────────────────────────┤
│ Option A: Direct Link                                    │
│   Main platform dashboard shows:                         │
│   [Manage Your Projects] button                          │
│   → Generates JWT token                                  │
│   → Redirects to: neuf.example.com?token=XXX            │
│   → Neuf.tn validates token                             │
│   → User logged in automatically                         │
│                                                          │
│ Option B: Separate Login                                 │
│   User visits: neuf.example.com/login                    │
│   Enters same credentials                                │
│   → Neuf.tn validates with main platform API            │
│   → Creates local user record                            │
│   → Logs user in                                         │
└──────────────────────────────────────────────────────────┘
                          ↓
┌──────────────────────────────────────────────────────────┐
│ Step 4: Create First Project                             │
├──────────────────────────────────────────────────────────┤
│ User lands on: /dashboard                                │
│ Sees: "You have no projects yet"                         │
│ Clicks: [Create Your First Project]                      │
│                                                          │
│ Fills form:                                              │
│   - Project Name: Luxury Residence                       │
│   - Location: La Marsa                                   │
│   - Delivery: 2026-06-01                                │
│   - Upload logo                                          │
│                                                          │
│ Clicks "Save"                                            │
│ → Project saved to database                              │
│ → Images uploaded                                        │
│ → Project appears on homepage                            │
└──────────────────────────────────────────────────────────┘
```

---

### Flow 2: Returning Promoter

```
┌──────────────────────────────────────────────────────────┐
│ User is already registered on main platform             │
└──────────────────────────────────────────────────────────┘
                          ↓
┌──────────────────────────────────────────────────────────┐
│ Option A: SSO from Main Platform                         │
├──────────────────────────────────────────────────────────┤
│ User logs into main-platform.com                         │
│ Dashboard shows: [Manage Properties]                      │
│ Clicks button                                            │
│ → Redirects to neuf.example.com with token              │
│ → Auto-logged in                                         │
│ → Lands on dashboard                                     │
└──────────────────────────────────────────────────────────┘

                          OR

┌──────────────────────────────────────────────────────────┐
│ Option B: Direct Login to Neuf.tn                        │
├──────────────────────────────────────────────────────────┤
│ User visits neuf.example.com                             │
│ Clicks [Login]                                           │
│ Enters credentials                                       │
│ → Validated against main platform                        │
│ → Logged in                                              │
│ → Sees their existing projects                           │
└──────────────────────────────────────────────────────────┘
```

---

## 🔒 SECURITY CONSIDERATIONS

### JWT Security:
```php
// Strong secret key (both platforms must use same)
JWT_SECRET=your-256-bit-secret-key-here-make-it-long-and-random

// Token expiration
'exp' => time() + (60 * 60 * 24 * 7) // 7 days

// Validate issuer
if ($decoded->iss !== 'main-platform.com') {
    throw new Exception('Invalid token issuer');
}

// Check expiration
if ($decoded->exp < time()) {
    throw new Exception('Token expired');
}
```

### API Security:
```php
// API key authentication
Route::middleware('api.key')->group(function() {
    Route::post('/auth/validate', [AuthController::class, 'validateExternal']);
});

// Middleware
public function handle($request, Closure $next)
{
    $apiKey = $request->header('X-API-Key');
    
    if ($apiKey !== env('NEUF_API_KEY')) {
        abort(401, 'Unauthorized');
    }
    
    return $next($request);
}
```

### CORS Configuration:
```php
// config/cors.php in Main Platform
return [
    'paths' => ['api/*'],
    'allowed_origins' => [
        'https://neuf.example.com',
        'http://localhost:3003' // for development
    ],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

---

## 📋 INTEGRATION CHECKLIST

### Prerequisites:
- [ ] Main platform has user/promoter management
- [ ] Users can be identified as "promoter" role
- [ ] Decision made on auth method (JWT/Session/API)
- [ ] Decision made on database strategy (shared/separate)

### Setup Tasks:
- [ ] Configure shared secret key (if using JWT)
- [ ] Set up database access/permissions
- [ ] Configure CORS on main platform
- [ ] Create API endpoints for user validation
- [ ] Test authentication flow
- [ ] Test data synchronization

### Testing:
- [ ] Register new promoter on main platform
- [ ] Verify promoter can access Neuf.tn
- [ ] Create project on Neuf.tn
- [ ] Verify project saves correctly
- [ ] Test logout/re-login
- [ ] Test session expiration
- [ ] Test on production domain

---

## 🚀 QUICK START (Recommended Approach)

For fastest 5-day launch, use this simple approach:

### Day 1: Shared Database + API Auth
1. Connect Neuf.tn to main platform database
2. Use API-based authentication (Method 3 above)
3. Create simple validation endpoint on main platform
4. Test login works

### Advantages:
- No JWT complexity
- No session configuration
- Simple to implement
- Works immediately
- Can upgrade to SSO later

### Implementation:
```
Main Platform: Add one API endpoint for auth validation
Neuf.tn: Call that endpoint when user logs in
Both: Use same users table
```

**This gets you 80% of functionality with 20% of effort! Perfect for tight deadline.**

---

## 💡 RECOMMENDATIONS

### For 5-Day Launch:
✅ Use API-based auth (Method 3)
✅ Use shared database
✅ Simple validation endpoint
❌ Skip SSO for now
❌ Skip webhooks for now
❌ Skip complex token management

### After Launch:
⏭️ Implement JWT/SSO for better UX
⏭️ Add webhooks for real-time sync
⏭️ Consider separate databases
⏭️ Add advanced security

**Remember: Launch first, optimize later!**
