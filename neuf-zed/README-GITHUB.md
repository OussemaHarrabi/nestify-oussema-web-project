# Neuf.tn - Real Estate Platform Frontend

A modern, fully-featured real estate platform for Tunisia built with Nuxt 3, Vue 3, and Tailwind CSS.

## 🚀 Features

### ✅ Completed Features
- **Homepage**: Hero section with search bar, projects showcase, about section, benefits, and developer CTA
- **Search Page**: Advanced filtering, map/list views, real-time search with location, type, and budget filters
- **Project Details**: Gallery, description, amenities, available units, interactive map location
- **Listing Details**: Detailed property info, image gallery, contact form, map integration
- **Developer Pages**: Developer profile with all their projects
- **Navigation Pages**:
  - **Promoteurs**: Showcase of all developers with achievement cards
  - **Financement**: Interactive loan calculator, bank partners, FAQ
  - **À Propos**: Company mission, timeline, values, team
- **Dashboard** (Developer Portal):
  - Project creation with multi-step form
  - Listing management
  - Interactive map for project location selection
  - Contact leads management
- **Interactive Features**:
  - Leaflet maps integration
  - Smooth animations and transitions
  - Responsive design (mobile-first)
  - Auto-scroll search bar behavior

## 🛠️ Tech Stack

- **Framework**: Nuxt 3.17.7
- **UI Library**: Vue 3.5.22
- **Styling**: Tailwind CSS
- **Maps**: Leaflet.js
- **Icons**: Lucide Vue
- **State Management**: Pinia
- **Build Tool**: Vite

## 📦 Installation

```bash
# Install dependencies
npm install

# Run development server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

## 🌿 Branches

- `main` - Stable frontend with mock data
- `backend-integration` - Backend API integration branch

## 🔗 Backend Integration

To integrate with backend API:

1. Switch to integration branch:
```bash
git checkout backend-integration
```

2. Update API endpoints in composables/services
3. Test locally
4. If successful, merge to main
5. If issues, revert to main:
```bash
git checkout main
```

## 📁 Project Structure

```
neuf-zed/
├── assets/          # CSS and images
├── components/      # Vue components
│   ├── ui/         # Reusable UI components
│   └── ...         # Feature components
├── pages/          # File-based routing
│   ├── dashboard/  # Developer dashboard
│   ├── project/    # Project pages
│   ├── listing/    # Listing pages
│   └── ...
├── composables/    # Composable functions
├── stores/         # Pinia stores
├── public/         # Static assets
└── nuxt.config.ts  # Nuxt configuration
```

## 🎨 Key Components

- **SearchBar**: Multi-section dropdown search (Location, Type, Budget)
- **MapView**: Interactive Leaflet map with project markers
- **ProjectCard**: Reusable project card component
- **Header/Footer**: Global navigation components

## 📝 Development Notes

- All navigation links are functional
- Mock data is used for development
- Forms include validation
- Maps use real coordinates for Tunisia
- Responsive breakpoints: sm, md, lg, xl

## 🚧 Next Steps (Backend Integration)

1. Create API service layer
2. Replace mock data with API calls
3. Implement authentication
4. Add image upload functionality
5. Connect contact forms to backend
6. Implement real-time search
7. Add pagination

## 📄 License

Private project for Neuf.tn

## 👥 Contributors

- Development Team - Initial work and UI implementation
- Backend Team - API integration (upcoming)

---

**Current Status**: ✅ Frontend Complete - Ready for Backend Integration
