# Frontend Integration Quick Reference

## 🎯 Essential Information

### Base URL
```javascript
const API_BASE_URL = 'http://127.0.0.1:8000/api';
```

### Admin Credentials (For Testing)
```javascript
const ADMIN_CREDENTIALS = {
  email: 'admin@nestify.tn',
  password: 'password'
};

// Or use this token directly:
const ADMIN_TOKEN = '58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12';
```

---

## 🔑 Authentication Flow

### 1. Login
```javascript
const login = async (email, password) => {
  const response = await fetch(`${API_BASE_URL}/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  
  if (response.ok) {
    // Save token
    localStorage.setItem('token', data.token);
    localStorage.setItem('user', JSON.stringify(data.user));
    return data;
  } else {
    throw new Error(data.message);
  }
};
```

### 2. Authenticated Request
```javascript
const getAdminDashboard = async () => {
  const token = localStorage.getItem('token');
  
  const response = await fetch(`${API_BASE_URL}/admin/dashboard`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  });
  
  return await response.json();
};
```

### 3. Logout
```javascript
const logout = async () => {
  const token = localStorage.getItem('token');
  
  await fetch(`${API_BASE_URL}/logout`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    }
  });
  
  localStorage.removeItem('token');
  localStorage.removeItem('user');
};
```

---

## 📚 Common Operations

### Get Projects List
```javascript
const getProjects = async (filters = {}) => {
  const params = new URLSearchParams(filters);
  const response = await fetch(`${API_BASE_URL}/projects?${params}`);
  return await response.json();
};

// Usage:
const projects = await getProjects({ city: 'Tunis', featured: true });
```

### Get Project Details
```javascript
const getProject = async (id) => {
  const response = await fetch(`${API_BASE_URL}/projects/${id}`);
  return await response.json();
};
```

### Create Project (Promoter)
```javascript
const createProject = async (projectData, coverImage) => {
  const token = localStorage.getItem('token');
  const formData = new FormData();
  
  // Add fields
  formData.append('name', projectData.name);
  formData.append('description', projectData.description);
  formData.append('city', projectData.city);
  formData.append('status', projectData.status);
  formData.append('total_units', projectData.total_units);
  
  // Add file
  if (coverImage) {
    formData.append('cover_image', coverImage);
  }
  
  const response = await fetch(`${API_BASE_URL}/promoter/projects`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
      // DON'T set Content-Type for FormData - browser sets it automatically
    },
    body: formData
  });
  
  return await response.json();
};
```

### Submit Lead (Public)
```javascript
const submitLead = async (leadData) => {
  const response = await fetch(`${API_BASE_URL}/leads`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(leadData)
  });
  
  return await response.json();
};

// Usage:
const lead = await submitLead({
  name: 'John Doe',
  email: 'john@example.com',
  phone: '+216 12 345 678',
  message: 'Interested in 2-bedroom apartments',
  project_id: 5
});
```

---

## 🖼️ Image Handling

### Display Images
```javascript
const STORAGE_URL = 'http://127.0.0.1:8000/storage/';

const getImageUrl = (imagePath) => {
  if (!imagePath) return '/placeholder.jpg';
  return `${STORAGE_URL}${imagePath}`;
};

// Usage in component:
<img src={getImageUrl(project.cover_image)} alt={project.name} />
```

---

## ⚠️ CRITICAL: Field Name Mapping

### Project Fields
```javascript
// ✅ CORRECT
const project = {
  name: 'Résidence Marina Bay',        // NOT 'title'
  is_published: true,                  // NOT 'published'
  cover_image: 'projects/cover.jpg',   // NOT 'image'
  published_at: '2025-10-14T13:09:42Z' // ISO date
};
```

### Property Fields
```javascript
// ✅ CORRECT
const property = {
  title: '2-Bedroom Apartment',           // NOT 'name'
  type: 'appartement',                    // NOT 'property_type'
  availability_status: 'available',       // NOT 'status'
  surface: 95.00                          // NOT 'area' or 'size'
};
```

### Status Values
```javascript
// Project Status
const PROJECT_STATUS = {
  PLANNING: 'planning',
  UNDER_CONSTRUCTION: 'under_construction',
  NEAR_COMPLETION: 'near_completion',
  COMPLETED: 'completed',
  ON_HOLD: 'on_hold'
};

// Property Types
const PROPERTY_TYPES = {
  APARTMENT: 'appartement',
  VILLA: 'villa',
  DUPLEX: 'duplex',
  STUDIO: 'studio',
  PENTHOUSE: 'penthouse',
  COMMERCIAL: 'commercial'
};

// Availability
const AVAILABILITY_STATUS = {
  AVAILABLE: 'available',
  RESERVED: 'reserved',
  SOLD: 'sold',
  UNAVAILABLE: 'unavailable'
};

// Lead Status
const LEAD_STATUS = {
  NEW: 'new',
  CONTACTED: 'contacted',
  QUALIFIED: 'qualified',
  CONVERTED: 'converted',
  LOST: 'lost'
};
```

---

## 🔄 Pagination Handling

```javascript
const PaginatedList = ({ endpoint }) => {
  const [data, setData] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [total, setTotal] = useState(0);
  
  const fetchData = async (page = 1) => {
    const response = await fetch(`${API_BASE_URL}${endpoint}?page=${page}`);
    const result = await response.json();
    
    setData(result.data);
    setCurrentPage(result.current_page);
    setLastPage(result.last_page);
    setTotal(result.total);
  };
  
  return (
    <div>
      {/* Display items */}
      {data.map(item => <ItemCard key={item.id} item={item} />)}
      
      {/* Pagination */}
      <Pagination 
        current={currentPage} 
        total={lastPage} 
        onChange={fetchData} 
      />
    </div>
  );
};
```

---

## 🚨 Error Handling

```javascript
const handleApiCall = async (apiFunction) => {
  try {
    const response = await apiFunction();
    
    if (!response.ok) {
      const error = await response.json();
      
      // Handle different error types
      if (response.status === 401) {
        // Unauthenticated - redirect to login
        logout();
        window.location.href = '/login';
      } else if (response.status === 422) {
        // Validation errors
        console.error('Validation errors:', error.errors);
        return { success: false, errors: error.errors };
      } else if (response.status === 404) {
        // Not found
        console.error('Resource not found');
      }
      
      throw new Error(error.message);
    }
    
    return await response.json();
  } catch (error) {
    console.error('API Error:', error);
    throw error;
  }
};
```

---

## 🎨 React/Vue/Angular Examples

### React Hook
```javascript
// useApi.js
import { useState, useEffect } from 'react';

export const useApi = (endpoint, dependencies = []) => {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  
  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const token = localStorage.getItem('token');
        
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
          headers: {
            'Authorization': token ? `Bearer ${token}` : '',
            'Accept': 'application/json'
          }
        });
        
        const result = await response.json();
        setData(result);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };
    
    fetchData();
  }, dependencies);
  
  return { data, loading, error };
};

// Usage:
const ProjectList = () => {
  const { data, loading, error } = useApi('/projects');
  
  if (loading) return <Spinner />;
  if (error) return <Error message={error} />;
  
  return (
    <div>
      {data.data.map(project => (
        <ProjectCard key={project.id} project={project} />
      ))}
    </div>
  );
};
```

### Vue Composable
```javascript
// useApi.js
import { ref, onMounted } from 'vue';

export const useApi = (endpoint) => {
  const data = ref(null);
  const loading = ref(true);
  const error = ref(null);
  
  const fetchData = async () => {
    try {
      loading.value = true;
      const token = localStorage.getItem('token');
      
      const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        headers: {
          'Authorization': token ? `Bearer ${token}` : '',
          'Accept': 'application/json'
        }
      });
      
      data.value = await response.json();
    } catch (err) {
      error.value = err.message;
    } finally {
      loading.value = false;
    }
  };
  
  onMounted(fetchData);
  
  return { data, loading, error, refetch: fetchData };
};

// Usage:
<script setup>
import { useApi } from './composables/useApi';

const { data, loading, error } = useApi('/projects');
</script>

<template>
  <div v-if="loading">Loading...</div>
  <div v-else-if="error">{{ error }}</div>
  <div v-else>
    <ProjectCard 
      v-for="project in data.data" 
      :key="project.id" 
      :project="project" 
    />
  </div>
</template>
```

---

## 📱 Axios Setup (Alternative)

```javascript
// api.js
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Request interceptor - add token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor - handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Logout and redirect
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;

// Usage:
import api from './api';

const getProjects = () => api.get('/projects');
const createProject = (data) => api.post('/promoter/projects', data);
const updateProject = (id, data) => api.post(`/promoter/projects/${id}`, data);
```

---

## 🧪 Testing Checklist

### Before Demo
- [ ] Server running: `php artisan serve`
- [ ] Test login with admin credentials
- [ ] Test getting projects list
- [ ] Test project details
- [ ] Test image display
- [ ] Test lead submission

### Quick Test Commands
```bash
# Backend
cd D:\oussema\Nestify_2.0\nestify-backend
php artisan serve

# Test all endpoints
php quick_api_test.php
```

---

## 📞 Quick Help

**Issue:** "Unauthenticated" error  
**Solution:** Check token is being sent in Authorization header

**Issue:** Images not displaying  
**Solution:** Check storage URL is correct: `http://127.0.0.1:8000/storage/`

**Issue:** 404 on routes  
**Solution:** Make sure server is running on port 8000

**Issue:** CORS errors  
**Solution:** Backend has CORS configured, but check your frontend URL

**Issue:** Field not found  
**Solution:** Check exact field names in documentation (e.g., `name` not `title` for projects)

---

## 🎯 Ready-to-Use Token
```
58|rBv9YPhZYRIxOZcFzJIswjlMI5XoUKdoNXuTuqaq24963b12
```

Use this token directly without logging in for quick testing!

---

**For full documentation:** See `API_DOCUMENTATION.md`  
**Postman Collection:** Import `Nestify_Complete_Admin_Collection.postman_collection.json`  
**Last Updated:** October 14, 2025
