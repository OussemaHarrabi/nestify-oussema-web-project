# 🚀 QUICK COMMANDS REFERENCE

Essential commands for the neuf-zed project.

---

## 📦 Installation

```bash
# Navigate to project
cd "Homepage Design for Neuf.tn/neuf-zed"

# Install dependencies
npm install

# Run development server
npm run dev
```

---

## 🏃 Running the Project

```bash
# Development (with hot reload)
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Generate static site
npm run generate
```

---

## 🧹 Clear Cache

```bash
# Clear Nuxt cache
rm -rf .nuxt

# Clear node modules
rm -rf node_modules package-lock.json

# Clear everything
rm -rf .nuxt node_modules dist package-lock.json

# Full clean install
rm -rf .nuxt node_modules package-lock.json && npm install
```

---

## 🔧 Fix Common Issues

```bash
# Fix: Port already in use
npx kill-port 3000

# Fix: TypeScript errors
npx nuxi prepare

# Fix: Module not found
npm install

# Fix: Styles not working
rm -rf .nuxt && npm run dev

# Fix: Everything broken (nuclear option)
rm -rf node_modules package-lock.json .nuxt dist
npm cache clean --force
npm install
npm run dev
```

---

## 📝 Type Generation

```bash
# Generate TypeScript types
npm run postinstall

# Or manually
npx nuxi prepare
```

---

## 🎨 Tailwind

```bash
# Rebuild Tailwind (if styles not working)
rm -rf .nuxt
npm run dev
```

---

## 🔍 Debugging

```bash
# Run with verbose output
DEBUG=* npm run dev

# Check component auto-imports
cat .nuxt/components.d.ts

# Analyze bundle size
npm run build
npx nuxi analyze
```

---

## 📦 Package Management

```bash
# Check installed packages
npm list --depth=0

# Update all packages
npm update

# Check for outdated packages
npm outdated

# Install specific package
npm install <package-name>
```

---

## 🌐 Browser

```bash
# Open in browser
# http://localhost:3000

# Nuxt DevTools
# http://localhost:3000/__inspect/
```

---

## 🏗️ Build & Deploy

```bash
# Production build
npm run build

# Preview build locally
npm run preview

# Static site generation
npm run generate

# Check build output
ls -la .output
```

---

## 💾 Git Commands (Optional)

```bash
# Initialize git (if not already)
git init

# Add all files
git add .

# Commit
git commit -m "Initial Nuxt.js clone"

# Check status
git status
```

---

## 🔄 Restart Everything

```bash
# Full restart sequence
rm -rf .nuxt
npm run dev

# Full clean restart
rm -rf .nuxt node_modules
npm install
npm run dev
```

---

## ⚡ Quick Start (First Time)

```bash
# 1. Navigate
cd "Homepage Design for Neuf.tn/neuf-zed"

# 2. Install
npm install

# 3. Run
npm run dev

# 4. Open browser
# http://localhost:3000
```

---

## 🎯 Most Used Commands

```bash
# Start development
npm run dev

# Clear cache and restart
rm -rf .nuxt && npm run dev

# Full reinstall
rm -rf node_modules package-lock.json && npm install

# Build for production
npm run build
```

---

## 🆘 Emergency Commands

```bash
# Everything is broken - use this:
rm -rf node_modules package-lock.json .nuxt dist
npm cache clean --force
npm install
npm run dev
```

---

**Remember:** `npm run dev` is your friend! 🚀