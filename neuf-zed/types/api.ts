// types/api.ts - Backend API Types

// User & Auth
export interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  user_type: 'promoter' | 'user' | 'admin'
  profile_picture: string | null
  created_at: string
  updated_at: string
}

export interface Promoter {
  id: number
  user_id: number
  company_name: string
  logo: string | null
  description: string | null
  website: string | null
  primary_phone: string | null
  additional_phones: string[] | null
  primary_email: string | null
  additional_emails: string[] | null
  license_number: string | null
  established_date: string | null
  employee_count: number | null
  specializations: string[] | null
  headquarters_address: string | null
  headquarters_city: string | null
  branch_offices: any[] | null
  total_projects: number
  completed_projects: number
  active_projects: number
  rating: number | null
  review_count: number
  verified: boolean
  featured: boolean
  verified_at: string | null
  created_at: string
  updated_at: string
}

// Project (Backend) = Project (Frontend)
export interface Project {
  id: number
  promoter_id: number
  name: string
  slug: string
  description: string | null
  reference: string | null
  city: string
  district: string | null
  address: string | null
  latitude: number | null
  longitude: number | null
  status: 'planning' | 'under_construction' | 'near_completion' | 'completed'
  launch_date: string | null
  expected_delivery: string | null
  construction_progress: string | null
  construction_progress_percentage: number | null
  total_units: number
  available_units: number
  sold_units: number
  reserved_units: number
  total_floors: number | null
  buildings_count: number | null
  starting_price: number
  average_price_per_sqm: number | null
  price_range_min: number | null
  price_range_max: number | null
  amenities: string[] | null
  nearby_facilities: string[] | null
  tags: string[] | null
  images: string[] | null
  cover_image: string | null
  floor_plans: string[] | null
  virtual_tours: string[] | null
  is_published: boolean
  is_featured: boolean
  published_at: string | null
  meta_title: string | null
  meta_description: string | null
  seo_keywords: string[] | null
  created_at: string
  updated_at: string
  promoter?: Promoter
  properties?: Property[]
}

// Property (Backend) = Listing (Frontend)
export interface Property {
  id: number
  project_id: number
  user_id: number
  title: string
  description: string | null
  price: number
  type: string
  surface: number
  reference: string | null
  city: string
  district: string | null
  address: string | null
  latitude: number | null
  longitude: number | null
  rooms: number | null
  bedrooms: number
  bathrooms: number
  floor: number | null
  total_floors: number | null
  parking: boolean
  elevator: boolean
  terrace: boolean
  garden: boolean
  features: string[] | null
  images: string[] | null
  availability_status: 'available' | 'reserved' | 'sold'
  validated: boolean
  published_date: string | null
  is_vefa: boolean
  delivery_date: string | null
  construction_progress: string | null
  created_at: string
  updated_at: string
  project?: Project
}

// Lead (Contact Form)
export interface Lead {
  id: number
  project_id: number | null
  property_id: number | null
  promoter_id: number | null
  first_name: string
  last_name: string
  email: string
  phone: string
  message: string | null
  status: 'new' | 'contacted' | 'qualified' | 'converted' | 'closed'
  priority: 'low' | 'medium' | 'high'
  notes: string | null
  source: string | null
  user_agent: string | null
  ip_address: string | null
  created_at: string
  updated_at: string
}

// API Response Wrappers
export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  per_page: number
  total: number
  last_page: number
  from: number
  to: number
  links: {
    first: string | null
    last: string | null
    prev: string | null
    next: string | null
  }
}

export interface ApiError {
  message: string
  errors?: Record<string, string[]>
}

// Dashboard Stats
export interface PromoterDashboardStats {
  total_projects: number
  published_projects: number
  total_properties: number
  available_properties: number
  total_leads: number
  new_leads: number
  leads_this_month: number
}
