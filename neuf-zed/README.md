# neuf-zed - Nuxt.js Version

Nuxt.js (Vue 3) clone of neuf.tn real estate platform with exact same design and colors.

## 🚀 Quick Start

```bash
# Install dependencies
npm install

# Run development server
npm run dev
```

Open http://localhost:3000

## 📁 Project Structure

```
neuf-zed/
├── assets/css/main.css          # Tailwind + Theme colors
├── components/
│   ├── ui/                      # Button, Badge
│   ├── Header.vue
│   ├── HeroSection.vue
│   ├── ProjectsSection.vue
│   ├── AboutSection.vue
│   ├── BenefitsSection.vue
│   ├── DevelopersCTA.vue
│   ├── Footer.vue
│   ├── SearchBar.vue
│   └── DeveloperLogo.vue
├── pages/
│   ├── index.vue                # Homepage
│   ├── search.vue               # Search page
│   ├── project/[id].vue         # Project detail
│   └── dashboard/index.vue      # Developer dashboard
├── utils/cn.ts                  # Class utility
├── app.vue                      # Root component
├── nuxt.config.ts               # Nuxt config
└── tailwind.config.js           # Tailwind config
```

## 🎨 Design System

**Colors (Exact Match):**
- Primary: `#ff385c`
- Background: `#fff`
- Foreground: `#222`
- Muted: `#f7f7f7`
- Border: `#ddd`

**Container:** Max-width 1760px with px-6 lg:px-20

## 🔗 Routes

- `/` - Homepage
- `/search` - Search results
- `/project/[id]` - Project detail
- `/dashboard` - Developer dashboard

## 🛠️ Build

```bash
npm run build     # Build for production
npm run preview   # Preview production build
```

## 📦 Key Dependencies

- Nuxt 3
- Tailwind CSS
- TypeScript
- Lucide Vue (icons)
- Radix Vue (UI)
- Pinia (state)

---

**Note:** All colors, fonts, spacing match React version exactly.