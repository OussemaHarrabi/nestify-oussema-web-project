# Laravel CORS Configuration for Neuf.tn

## Current Configuration Issue
Your CORS is set to `'allowed_origins' => ['*']` and `'supports_credentials' => false`.

For authentication with cookies/tokens to work, you MUST change this.

## ⚠️ Required Changes

Open `D:\oussema\Nestify_2.0\nestify-backend\config\cors.php` and update:

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // CHANGE THIS - Replace ['*'] with specific origin
    'allowed_origins' => [
        'http://localhost:3000',  // Nuxt dev server
        'http://127.0.0.1:3000',  // Alternative localhost
        // Add production domain when deployed:
        // 'https://neuf.tn',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // CHANGE THIS - Must be true for authentication
    'supports_credentials' => true,
];
```

## Why This Matters

1. **`allowed_origins`**: Must list specific domains (not `*`) when using credentials
2. **`supports_credentials`**: Must be `true` to allow cookies/authorization headers
3. Without these changes, your login will fail with CORS errors

## Testing CORS

After making the changes:

1. **No restart needed** - Laravel auto-reloads config in development
2. Open browser console while testing login
3. If you see CORS errors, verify:
   - Frontend runs on http://localhost:3000
   - Backend runs on http://localhost:8000
   - Both URLs match the CORS config exactly

## Common CORS Errors

### Error: "Access-Control-Allow-Origin"
**Solution**: Add http://localhost:3000 to `allowed_origins`

### Error: "credentials mode is 'include'"
**Solution**: Set `supports_credentials => true`

### Error: "Origin not allowed"
**Solution**: Check frontend URL matches exactly (http vs https, localhost vs 127.0.0.1)

## Production Configuration

When deploying, update to:

```php
'allowed_origins' => [
    env('FRONTEND_URL', 'https://neuf.tn'),
],
```

Then add to `.env`:
```
FRONTEND_URL=https://neuf.tn
```

---

**⚡ Action Required**: Update the CORS config now before testing login!
