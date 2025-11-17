// composables/api/useLeadsApi.ts - Leads/Contact Forms API
import type { Lead } from '~/types/api'

interface LeadSubmission {
  first_name: string
  last_name: string
  email: string
  phone: string
  message?: string
  project_id?: number
  property_id?: number
}

export const useLeadsApi = () => {
  const { apiFetch } = useApi()

  // PUBLIC: Submit a lead (contact form)
  const submitLead = async (data: LeadSubmission) => {
    return apiFetch<Lead>('/leads', {
      method: 'POST',
      body: data,
    })
  }

  return {
    submitLead,
  }
}
