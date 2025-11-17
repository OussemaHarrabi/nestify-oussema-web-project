# 🔧 TROUBLESHOOTING GUIDE

Quick solutions for common issues when running the neuf-zed project.

---

## 🚨 Common Errors & Solutions

### 1. Failed to resolve extends base type (VariantProps)

**Error:**
```
[@vue/compiler-sfc] Failed to resolve extends base type.
interface Props extends BadgeVariants
```

**Solution:**
Already fixed! The Badge.vue and Button.vue components have been updated to not use `VariantProps` extends.

If you still see this error:
```bash
# Clear cache and restart
rm -rf .nuxt node_modules
npm install
npm run dev
```

---

### 2. Port Already in Use

**Error:**
```
Port 3000 is already in use
```

**Solution:**
```bash
# Option 1: Kill the process
npx kill-port 3000

# Option 2: Use different port
PORT=3001 npm run dev
```

---

### 3. Module Not Found Errors

**Error:**
```
Cannot find module 'lucide-vue-next'
Cannot find module 'pinia'
```

**Solution:**
```bash
# Clear and reinstall
rm -rf node_modules package-lock.json
npm install
```

---

### 4. Tailwind Classes Not Working

**Symptoms:**
- Styles not applying
- Colors not showing
- Layout broken

**Solution:**
```bash
# 1. Check if main.css is imported
# Should be in nuxt.config.ts: css: ['~/assets/css/main.css']

# 2. Restart dev server
npm run dev

# 3. Clear Nuxt cache
rm -rf .nuxt
npm run dev
```

---

### 5. TypeScript Errors

**Error:**
```
Cannot find name 'ref'
Cannot find name 'computed'
```

**Solution:**
```bash
# Generate types
npm run postinstall

# Or manually
npx nuxi prepare
```

---

### 6. Pinia Store Not Working

**Error:**
```
getActivePinia was called with no active Pinia
```

**Solution:**
Make sure `@pinia/nuxt` is in `nuxt.config.ts` modules:
```typescript
modules: [
  '@nuxtjs/tailwindcss',
  '@pinia/nuxt',  // ← Must be here
  '@vueuse/nuxt',
]
```

Then restart:
```bash
npm run dev
```

---

### 7. Toast Notifications Not Showing

**Solution:**
Check that `Toast.vue` is imported in `app.vue`:
```vue
<template>
  <div>
    <NuxtPage />
    <Toast />  <!-- ← Must be here -->
    <LoadingOverlay />
  </div>
</template>
```

---

### 8. Images Not Loading

**Symptoms:**
- Broken image icons
- 404 errors for images

**Solution:**
Images are using Unsplash URLs. Check your internet connection or replace with local images in `/public/images/`

---

### 9. Navigation Not Working

**Error:**
```
navigateTo is not defined
```

**Solution:**
Nuxt auto-imports `navigateTo`. If it's not working:
```bash
# Regenerate types
rm -rf .nuxt
npm run dev
```

---

### 10. Build Fails

**Error:**
```
npm run build fails
```

**Solution:**
```bash
# Clear everything and rebuild
rm -rf .nuxt node_modules dist
npm install
npm run build
```

---

## 🔍 Diagnostic Commands

### Check Installation
```bash
# Node version (should be 18+)
node --version

# NPM version
npm --version

# List installed packages
npm list --depth=0
```

### Clear All Caches
```bash
# Nuclear option - clears everything
rm -rf node_modules package-lock.json .nuxt dist
npm install
npm run dev
```

### Verify Project Structure
```bash
# Check if all directories exist
ls -la pages components stores assets
```

---

## 📱 Browser Issues

### Styles Not Loading in Browser
1. Open DevTools (F12)
2. Go to Network tab
3. Refresh page
4. Check if `main.css` is loaded
5. Check Console for errors

### Hot Reload Not Working
```bash
# Restart with clean cache
rm -rf .nuxt
npm run dev
```

---

## 🐛 Debugging Tips

### Enable Verbose Logging
```bash
DEBUG=* npm run dev
```

### Check Nuxt DevTools
When dev server is running, open:
```
http://localhost:3000/__inspect/
```

### Verify Component Auto-imports
Check `.nuxt/components.d.ts` to see if your components are registered.

---

## 🔧 Configuration Issues

### nuxt.config.ts Not Loading
Make sure the file is at project root:
```
neuf-zed/
├── nuxt.config.ts  ← Here
├── package.json
└── ...
```

### Tailwind Not Working
Check `tailwind.config.js` content paths:
```javascript
content: [
  "./components/**/*.{js,vue,ts}",
  "./layouts/**/*.vue",
  "./pages/**/*.vue",
  "./app.vue",
]
```

---

## 💻 VS Code Issues

### TypeScript Intellisense Not Working
1. Install Volar extension (disable Vetur if installed)
2. Reload VS Code window
3. Run: `npm run postinstall`

### Auto-imports Not Suggested
Create `.vscode/settings.json`:
```json
{
  "typescript.tsdk": "node_modules/typescript/lib",
  "typescript.enablePromptUseWorkspaceTsdk": true
}
```

---

## 🚀 Performance Issues

### Slow Dev Server
```bash
# Use faster package manager
npm install -g pnpm
pnpm install
pnpm dev
```

### Large Bundle Size
```bash
# Analyze bundle
npm run build
npx nuxi analyze
```

---

## 📦 Dependency Conflicts

### Peer Dependency Warnings
Already configured in `.npmrc`:
```
legacy-peer-deps=true
auto-install-peers=true
```

If still issues:
```bash
npm install --legacy-peer-deps
```

---

## 🔄 Fresh Start (Last Resort)

If nothing works, complete reset:

```bash
# 1. Delete everything
rm -rf node_modules package-lock.json .nuxt dist

# 2. Clear npm cache
npm cache clean --force

# 3. Reinstall
npm install

# 4. Run
npm run dev
```

---

## 📞 Still Having Issues?

1. **Check the Console**
   - Look for specific error messages
   - Copy the full error text

2. **Check Browser Console**
   - Open DevTools (F12)
   - Look for JavaScript errors

3. **Check Node Version**
   ```bash
   node --version
   # Should be 18.x or higher
   ```

4. **Check Project Location**
   - Make sure you're in the right directory
   ```bash
   pwd
   # Should show: .../neuf-zed
   ```

5. **Verify Installation**
   ```bash
   # Check if all deps installed
   npm list nuxt
   npm list vue
   npm list pinia
   ```

---

## ✅ Success Checklist

Before reporting issues, verify:

- [ ] Node.js version 18+ installed
- [ ] `npm install` completed without errors
- [ ] `.nuxt` directory exists after running dev
- [ ] Port 3000 is not in use
- [ ] Internet connection working (for images)
- [ ] No TypeScript errors in console
- [ ] Tailwind CSS classes working
- [ ] Components loading correctly

---

## 🎯 Quick Fix Commands

```bash
# Most common fix (90% of issues)
rm -rf .nuxt node_modules
npm install
npm run dev

# If Tailwind not working
rm -rf .nuxt
npm run dev

# If types not working
npx nuxi prepare
npm run dev

# If port blocked
npx kill-port 3000
npm run dev

# If nothing works
rm -rf node_modules package-lock.json .nuxt dist
npm cache clean --force
npm install
npm run dev
```

---

**Remember:** Most issues are solved by clearing cache and reinstalling! 🔄