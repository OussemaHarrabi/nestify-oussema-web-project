import { ref, reactive } from 'vue'

export interface ValidationRule {
  required?: boolean
  minLength?: number
  maxLength?: number
  pattern?: RegExp
  custom?: (value: any) => boolean | string
  message?: string
}

export interface ValidationRules {
  [key: string]: ValidationRule
}

export function useFormValidation<T extends Record<string, any>>(
  initialData: T,
  rules: ValidationRules
) {
  const formData = reactive<T>({ ...initialData })
  const errors = reactive<Record<string, string>>({})
  const isSubmitting = ref(false)
  const isValid = ref(false)

  const validateField = (field: string, value: any): string => {
    const rule = rules[field]
    if (!rule) return ''

    // Required validation
    if (rule.required && (!value || value.toString().trim() === '')) {
      return rule.message || 'Ce champ est requis'
    }

    // Skip other validations if field is empty and not required
    if (!value || value.toString().trim() === '') {
      return ''
    }

    // Min length validation
    if (rule.minLength && value.toString().length < rule.minLength) {
      return rule.message || `Minimum ${rule.minLength} caractères requis`
    }

    // Max length validation
    if (rule.maxLength && value.toString().length > rule.maxLength) {
      return rule.message || `Maximum ${rule.maxLength} caractères autorisés`
    }

    // Pattern validation
    if (rule.pattern && !rule.pattern.test(value.toString())) {
      return rule.message || 'Format invalide'
    }

    // Custom validation
    if (rule.custom) {
      const result = rule.custom(value)
      if (typeof result === 'string') {
        return result
      }
      if (!result) {
        return rule.message || 'Validation échouée'
      }
    }

    return ''
  }

  const validate = (): boolean => {
    let hasErrors = false

    Object.keys(rules).forEach((field) => {
      const error = validateField(field, (formData as any)[field])
      if (error) {
        errors[field] = error
        hasErrors = true
      } else {
        delete errors[field]
      }
    })

    isValid.value = !hasErrors
    return !hasErrors
  }

  const validateSingle = (field: string): boolean => {
    const error = validateField(field, (formData as any)[field])
    if (error) {
      errors[field] = error
      return false
    } else {
      delete errors[field]
      return true
    }
  }

  const clearErrors = () => {
    Object.keys(errors).forEach((key) => {
      delete errors[key]
    })
  }

  const resetForm = () => {
    Object.keys(formData).forEach((key) => {
      ;(formData as any)[key] = (initialData as any)[key]
    })
    clearErrors()
    isSubmitting.value = false
  }

  const setFieldValue = (field: string, value: any) => {
    ;(formData as any)[field] = value
  }

  const setFieldError = (field: string, message: string) => {
    errors[field] = message
  }

  const clearFieldError = (field: string) => {
    delete errors[field]
  }

  return {
    formData,
    errors,
    isSubmitting,
    isValid,
    validate,
    validateSingle,
    validateField,
    clearErrors,
    resetForm,
    setFieldValue,
    setFieldError,
    clearFieldError
  }
}

// Common validation patterns
export const validationPatterns = {
  email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
  phone: /^[\d\s\+\-\(\)]{8,}$/,
  url: /^https?:\/\/.+/,
  number: /^\d+$/,
  alphanumeric: /^[a-zA-Z0-9]+$/,
  letters: /^[a-zA-Z\s]+$/,
  tunisianPhone: /^(\+216)?[2-9]\d{7}$/
}

// Common validation rules
export const commonRules = {
  required: (message?: string): ValidationRule => ({
    required: true,
    message: message || 'Ce champ est requis'
  }),

  email: (message?: string): ValidationRule => ({
    pattern: validationPatterns.email,
    message: message || 'Adresse email invalide'
  }),

  phone: (message?: string): ValidationRule => ({
    pattern: validationPatterns.phone,
    message: message || 'Numéro de téléphone invalide'
  }),

  minLength: (min: number, message?: string): ValidationRule => ({
    minLength: min,
    message: message || `Minimum ${min} caractères requis`
  }),

  maxLength: (max: number, message?: string): ValidationRule => ({
    maxLength: max,
    message: message || `Maximum ${max} caractères autorisés`
  }),

  url: (message?: string): ValidationRule => ({
    pattern: validationPatterns.url,
    message: message || 'URL invalide'
  }),

  number: (message?: string): ValidationRule => ({
    pattern: validationPatterns.number,
    message: message || 'Doit être un nombre'
  })
}
