# Nestify Frontend - Complete Specification
## Premium Real Estate Platform Frontend

### 🎯 Project Overview
Nestify is a **premium real estate platform** specifically designed for the **Tunisian market**, connecting buyers with exclusive properties and verified agencies. The platform emphasizes **luxury, trust, and sophistication** while maintaining **modern usability**.

### 🎨 Design Philosophy
- **Premium & Luxurious**: High-end aesthetic with elegant typography and sophisticated color schemes
- **Modern & Clean**: Minimalist design with plenty of white space and subtle animations
- **Professional**: Trustworthy appearance that reflects the high-value nature of real estate transactions
- **Mobile-First**: Responsive design optimized for all devices
- **French Language**: Primary language for the Tunisian market

---

## 🏠 Core Pages & Features

### 1. **Hero Landing Page** 🌟
Based on the provided design mockup:

#### Header Navigation
- **Logo**: "Nestify" with elegant typography and premium styling
- **Navigation Menu**: 
  - 🏠 Accueil (Home)
  - 🏢 Propriétés (Properties) 
  - 🏢 Agences (Agencies)
  - 📞 Contact
- **Action Button**: "Se connecter" (Login) - prominent purple/violet CTA button

#### Hero Section
- **Background**: High-quality architectural image (modern villa/luxury property)
- **Overlay**: Professional gradient overlay for text readability
- **Main Heading**: "Trouvez votre propriété idéale" (Find your ideal property)
- **Subheading**: "Découvrez une sélection raffinée de propriétés premium en Tunisie. Nouvelles constructions uniques et projets exclusifs vous attendent."
- **Premium Badge**: "⭐ Immobilier raffiné et premium"

#### Search Section
- **Search Bar**: Large, prominent search input with placeholder "Où souhaitez-vous chercher ?"
- **Search Button**: Purple search icon button
- **Quick Action**: "Explorer toutes les propriétés" button

#### Statistics Display
- **742+** Propriétés Uniques
- **24** Gouvernorats  
- **4.9** ⭐ Satisfaction

### 2. **How It Works Section** 📋
- **Title**: "Comment ça marche"
- **3-Step Process**:
  1. **🔍 Recherchez**: "Utilisez nos filtres avancés pour trouver la propriété qui correspond exactement à vos critères et votre budget."
  2. **👁️ Explorez**: "Découvrez les détails complets, photos haute qualité, plans 3D et toutes les informations sur chaque propriété."
  3. **📞 Contactez**: "Entrez en contact direct avec les promoteurs certifiés pour organiser une visite et finaliser votre achat."

### 3. **What We Offer Section** 🎁
- **Title**: "Ce que nous offrons"
- **4 Key Features**:
  1. **🏗️ Projets VEFA**: "Accès exclusif aux projets en vente en l'état futur d'achèvement"
  2. **🏢 Promoteurs Certifiés**: "Partenariat avec les meilleurs promoteurs immobiliers de Tunisie"
  3. **🔍 Filtrage Avancé**: "Outils de recherche sophistiqués pour trouver votre propriété idéale"
  4. **🛡️ Garantie Qualité**: "Toutes nos propriétés sont vérifiées et certifiées conformes"

---

## 🏠 Property Listings & Details

### **Property Grid/List View** 📋
- **Modern Card Design**: Clean, elegant property cards with:
  - High-quality main image with hover effects
  - Property type badge (VEFA, Villa, Apartment, etc.)
  - Favorite heart icon (top-right)
  - Property title and location
  - Key details: bedrooms, bathrooms, area
  - Price prominently displayed
  - "Détails" button
- **Advanced Filters**: Sidebar with sophisticated filtering options
- **Sort Options**: Price, date, popularity, area
- **Pagination**: Smooth pagination with loading states

### **Property Detail Page** 🏡
This is a **critical page** that must be **exceptional**:

#### Image Gallery
- **Hero Image**: Large, high-resolution main image
- **Image Carousel**: Thumbnail navigation below
- **Lightbox View**: Click to view full-screen gallery
- **360° Views**: If available
- **Virtual Tour**: Integration for 3D tours

#### Property Information Panel
- **Price**: Large, prominent pricing (e.g., "440 000 DT")
- **Key Metrics**: Bedrooms, bathrooms, area in clear icons
- **Property Type**: Villa, Apartment, etc.
- **Status**: Available, Reserved, Sold
- **Location**: Full address with map integration

#### Detailed Description
- **Property Description**: Rich text with formatting
- **Features & Amenities**: Organized list with icons
- **Neighborhood Info**: Local amenities, schools, transport
- **Investment Potential**: For relevant properties

#### Contact & Action Section
- **Agency Information**: Logo, name, contact details
- **Contact Forms**: 
  - "Demander une visite" (Request visit)
  - "Obtenir plus d'informations" (Get more info)
  - "Télécharger la brochure" (Download brochure)
- **Share Options**: Social media sharing
- **Save to Favorites**: Heart icon

#### Additional Features
- **Interactive Map**: Property location with nearby amenities
- **Mortgage Calculator**: Payment estimation tool
- **Similar Properties**: Recommendations
- **Property History**: Price changes, availability

---

## 🎨 Design System & Styling

### **Color Palette**
- **Primary Purple**: #8B5CF6 (main brand color)
- **Secondary Purple**: #A855F7 (lighter variant)
- **Accent Purple**: #7C3AED (darker variant)
- **Neutral Grays**: #F8FAFC, #E2E8F0, #64748B
- **Success Green**: #10B981
- **Warning Orange**: #F59E0B
- **Error Red**: #EF4444

### **Typography**
- **Primary Font**: Modern, clean sans-serif (Inter, Poppins, or similar)
- **Headings**: Bold, elegant weights
- **Body Text**: Regular weight, excellent readability
- **French Language**: Proper accent support

### **UI Components**
- **Buttons**: Rounded, gradient effects, hover animations
- **Cards**: Subtle shadows, rounded corners, hover effects
- **Forms**: Clean inputs with focus states
- **Icons**: Consistent icon library (Heroicons, Feather, or similar)
- **Loading States**: Elegant skeletons and spinners

---

## 📱 Responsive Design

### **Desktop** (1024px+)
- Full layout with sidebar filters
- Multi-column property grid
- Rich interactive elements

### **Tablet** (768px - 1023px)
- Adapted layout with collapsible filters
- 2-column property grid
- Touch-optimized interactions

### **Mobile** (< 768px)
- Single-column layout
- Bottom sheet filters
- Thumb-friendly navigation
- Swipe gestures for galleries

---

## ⚡ Performance & User Experience

### **Core Features**
- **Fast Loading**: Optimized images, lazy loading
- **Smooth Animations**: Subtle transitions and micro-interactions
- **Search Functionality**: Real-time search with suggestions
- **Favorites System**: Save and manage favorite properties
- **Advanced Filtering**: Multiple criteria with instant results
- **Map Integration**: Interactive property maps
- **Image Optimization**: Multiple formats and sizes

### **Interactive Elements**
- **Property Cards**: Hover effects and smooth transitions
- **Image Galleries**: Smooth carousels and lightboxes
- **Filter Sidebar**: Collapsible with smooth animations
- **Search Suggestions**: Real-time autocomplete
- **Loading States**: Elegant placeholders and transitions

---

## 🔐 User Authentication (For Later Phases)
While focusing on normal user pages now, the structure should accommodate:
- **Login/Register Modals**: Elegant overlay forms
- **User Dashboard**: Saved properties, search history
- **Profile Management**: User preferences and settings

---

## 🎯 Key Success Metrics
- **Premium Feel**: Users should immediately recognize this as a high-end platform
- **Trust & Credibility**: Professional design that builds confidence
- **User Engagement**: Easy property discovery and exploration
- **Mobile Experience**: Seamless mobile browsing and searching
- **Performance**: Fast loading times and smooth interactions

---

## 🚀 Technical Requirements

### **Frontend Technology Stack**
- **Framework**: Nuxt.js 3 with TypeScript
- **Styling**: Tailwind CSS for rapid, consistent styling
- **State Management**: Pinia for Vue state management
- **Routing**: Auto-routing (file-based routing system)
- **HTTP Client**: $fetch (built-in) or @nuxtjs/axios
- **UI Components**: Headless UI Vue or Nuxt UI for accessibility
- **Animations**: @vueuse/motion or CSS transitions
- **Icons**: Heroicons or @nuxt/icon
- **Maps**: @nuxtjs/google-maps or Mapbox integration
- **SEO**: Built-in meta management and SSR

### **API Integration**
- **Base URL**: `http://127.0.0.1:8000/api`
- **Authentication**: Bearer token system with Nuxt auth
- **SSR/SPA Mode**: Hybrid rendering for optimal performance
- **Endpoints**: 
  - Properties listing, filtering, search
  - Property details with SSR for SEO
  - User authentication (future)
  - Favorites management (future)

### **Key Pages to Build First**
1. **Landing Page** - Hero section with search (`pages/index.vue`)
2. **Property Listings** - Grid/list view with filters (`pages/properties/index.vue`)
3. **Property Details** - Comprehensive property page (`pages/properties/[id].vue`)
4. **Search Results** - Filtered and search results (`pages/search.vue`)
5. **About/Contact** - Basic informational pages (`pages/about.vue`, `pages/contact.vue`)

### **Nuxt.js Specific Features**
- **File-based Routing**: Automatic route generation
- **SSR for Property Pages**: Better SEO for property listings
- **Built-in SEO**: Meta tags, Open Graph for property sharing
- **Image Optimization**: `<NuxtImg>` for optimized property images
- **Auto-imports**: Composables and components auto-imported
- **Server API Routes**: `/server/api/` for proxy endpoints if needed
- **Middleware**: Authentication and route protection
- **Plugins**: Third-party integrations (maps, analytics)

### **Project Structure**
```
nestify-frontend/
├── assets/           # Static assets (images, fonts)
├── components/       # Vue components
│   ├── Property/     # Property-related components
│   ├── UI/          # Reusable UI components
│   └── Layout/      # Layout components
├── composables/      # Vue composables for logic
├── layouts/         # Layout templates
├── middleware/      # Route middleware
├── pages/           # File-based routing
├── plugins/         # Nuxt plugins
├── public/          # Public static files
├── server/          # Server-side API routes
├── stores/          # Pinia stores
└── types/           # TypeScript type definitions
```

---

## 📋 Development Priorities

### **Phase 1: Core User Experience** (Current Focus)
- ✅ Landing page with hero section
- ✅ Property listings with basic filtering
- ✅ Property detail pages with image galleries
- ✅ Search functionality
- ✅ Mobile responsiveness

### **Phase 2: Enhanced Features** (Future)
- 🔄 User authentication integration
- 🔄 Advanced filtering and search
- 🔄 Favorites system
- 🔄 User dashboard
- 🔄 Contact forms and agency interaction

### **Phase 3: Premium Features** (Future)
- 🔄 Virtual tours and 3D views
- 🔄 Mortgage calculator
- 🔄 Advanced analytics and recommendations
- 🔄 Multi-language support
- 🔄 Advanced map features

---

This specification provides a comprehensive foundation for building a **modern, premium real estate platform** that reflects the sophistication of the Tunisian luxury property market while delivering an exceptional user experience across all devices.