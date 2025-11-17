<template>
    <div class="relative w-full h-full">
        <div ref="mapContainer" class="w-full h-full rounded-2xl overflow-hidden"></div>

        <!-- Map Controls -->
        <div class="absolute top-4 right-4 z-[1000] flex flex-col gap-2">
            <button
                @click="zoomIn"
                class="w-10 h-10 bg-white rounded-lg shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors"
            >
                <Plus class="w-5 h-5 text-foreground" />
            </button>
            <button
                @click="zoomOut"
                class="w-10 h-10 bg-white rounded-lg shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors"
            >
                <Minus class="w-5 h-5 text-foreground" />
            </button>
            <button
                @click="resetView"
                class="w-10 h-10 bg-white rounded-lg shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors"
            >
                <Home class="w-5 h-5 text-foreground" />
            </button>
        </div>

        <!-- Loading State -->
        <div
            v-if="isLoading"
            class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-[1001] rounded-2xl"
        >
            <div class="text-center">
                <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-sm text-muted-foreground">Chargement de la carte...</p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'
import { Plus, Minus, Home } from 'lucide-vue-next'
import type { Map as LeafletMap, Marker, CircleMarker } from 'leaflet'

interface Project {
    id: number
    title: string
    location: string
    coordinates: { lat: number; lng: number }
    price: string
    image: string
    type: string
}

interface Props {
    projects: Project[]
    selectedProjectId?: number | null
}

const props = defineProps<Props>()
const emit = defineEmits<{
    'project-click': [project: Project]
}>()

const mapContainer = ref<HTMLElement | null>(null)
const isLoading = ref(true)

let map: LeafletMap | null = null
let L: any = null
let markers: { [key: number]: Marker | CircleMarker } = {}
let clusterGroup: any = null

const initMap = async () => {
    if (!mapContainer.value) return

    isLoading.value = true

    try {
        // Dynamically import Leaflet
        L = await import('leaflet')

        // Import Leaflet CSS
        await import('leaflet/dist/leaflet.css')

        // Fix default marker icons issue with Vite
        delete (L.Icon.Default.prototype as any)._getIconUrl
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        })

        // Initialize map centered on Tunisia
        map = L.map(mapContainer.value, {
            center: [36.8065, 10.1815], // Tunisia center
            zoom: 10,
            zoomControl: false,
            scrollWheelZoom: true,
        })

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map)

        // Add markers
        addMarkers()

        isLoading.value = false
    } catch (error) {
        console.error('Error initializing map:', error)
        isLoading.value = false
    }
}

const addMarkers = () => {
    if (!map || !L) return

    // Clear existing markers
    Object.values(markers).forEach(marker => {
        if (map) marker.remove()
    })
    markers = {}

    // Get current zoom level
    const zoom = map.getZoom()

    // Dynamic clustering threshold based on zoom level
    // Higher zoom = smaller threshold = less clustering
    const getClusterThreshold = (zoom: number) => {
        if (zoom >= 16) return 0.0005  // Very close - almost no clustering
        if (zoom >= 14) return 0.005   // Close - minimal clustering
        if (zoom >= 12) return 0.02    // Medium - moderate clustering
        if (zoom >= 10) return 0.05    // Far - aggressive clustering
        if (zoom >= 8) return 0.15     // Very far - very aggressive clustering
        if (zoom >= 6) return 0.3      // Extremely far - extreme clustering
        return 0.5                     // Country view - maximum clustering
    }

    const threshold = getClusterThreshold(zoom)

    // Group projects by proximity for clustering
    const projectGroups = groupProjectsByProximity(props.projects, threshold)

    projectGroups.forEach((group) => {
        if (group.length === 1) {
            // Single project - show individual marker
            const project = group[0]
            addSingleMarker(project)
        } else {
            // Multiple projects - show cluster marker
            addClusterMarker(group)
        }
    })
}

const addSingleMarker = (project: Project) => {
    if (!map || !L || !project.coordinates) return

    const customIcon = L.divIcon({
        html: `
            <div class="relative">
                <div class="w-10 h-10 bg-primary rounded-full shadow-lg flex items-center justify-center border-4 border-white cursor-pointer transition-transform hover:scale-110">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
            </div>
        `,
        className: 'custom-marker',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
    })

    const marker = L.marker([project.coordinates.lat, project.coordinates.lng], {
        icon: customIcon,
    }).addTo(map)

    // Create popup content
    const popupContent = `
        <div class="p-2 min-w-[200px]">
            <img src="${project.image}" alt="${project.title}" class="w-full h-32 object-cover rounded-lg mb-2" />
            <h3 class="font-semibold text-sm mb-1">${project.title}</h3>
            <p class="text-xs text-gray-600 mb-2">${project.location}</p>
            <p class="text-sm font-semibold text-primary">À partir de ${project.price} TND</p>
        </div>
    `

    marker.bindPopup(popupContent, {
        maxWidth: 250,
        className: 'custom-popup',
    })

    marker.on('click', () => {
        emit('project-click', project)
    })

    markers[project.id] = marker
}

const addClusterMarker = (projects: Project[]) => {
    if (!map || !L) return

    const count = projects.length
    const centerLat = projects.reduce((sum, p) => sum + p.coordinates.lat, 0) / count
    const centerLng = projects.reduce((sum, p) => sum + p.coordinates.lng, 0) / count

    // Determine color based on count
    const getClusterColor = (count: number) => {
        if (count >= 10) return '#dc2626' // red
        if (count >= 5) return '#ea580c' // orange
        if (count >= 3) return '#f59e0b' // amber
        return '#ff385c' // primary
    }

    const color = getClusterColor(count)
    const size = Math.min(60, 40 + count * 2)

    const clusterIcon = L.divIcon({
        html: `
            <div class="relative">
                <div
                    class="rounded-full shadow-xl flex items-center justify-center border-4 border-white cursor-pointer transition-transform hover:scale-110"
                    style="width: ${size}px; height: ${size}px; background-color: ${color};"
                >
                    <span class="text-white font-bold" style="font-size: ${Math.min(20, 14 + count * 0.5)}px;">
                        ${count}
                    </span>
                </div>
            </div>
        `,
        className: 'cluster-marker',
        iconSize: [size, size],
        iconAnchor: [size / 2, size / 2],
    })

    const marker = L.marker([centerLat, centerLng], {
        icon: clusterIcon,
    }).addTo(map)

    marker.on('click', () => {
        if (map) {
            // Just zoom in to split cluster - no popup
            map.setView([centerLat, centerLng], Math.min(map.getZoom() + 3, 18), {
                animate: true,
                duration: 0.8,
            })
        }
    })

    // Store with special ID
    markers[`cluster-${centerLat}-${centerLng}`] = marker
}

const groupProjectsByProximity = (projects: Project[], threshold: number) => {
    const groups: Project[][] = []
    const processed = new Set<number>()

    projects.forEach((project) => {
        if (processed.has(project.id) || !project.coordinates) return

        const group = [project]
        processed.add(project.id)

        projects.forEach((otherProject) => {
            if (
                !processed.has(otherProject.id) &&
                otherProject.coordinates &&
                project.coordinates &&
                getDistance(project.coordinates, otherProject.coordinates) < threshold
            ) {
                group.push(otherProject)
                processed.add(otherProject.id)
            }
        })

        groups.push(group)
    })

    return groups
}

const getDistance = (
    coord1: { lat: number; lng: number } | undefined,
    coord2: { lat: number; lng: number } | undefined
) => {
    if (!coord1 || !coord2) return Infinity
    const latDiff = Math.abs(coord1.lat - coord2.lat)
    const lngDiff = Math.abs(coord1.lng - coord2.lng)
    return Math.sqrt(latDiff * latDiff + lngDiff * lngDiff)
}

const zoomIn = () => {
    if (map) map.zoomIn()
}

const zoomOut = () => {
    if (map) map.zoomOut()
}

const resetView = () => {
    if (map) {
        map.setView([36.8065, 10.1815], 10, {
            animate: true,
            duration: 0.5,
        })
    }
}

// Watch for zoom changes to update clusters
const setupZoomListener = () => {
    if (!map) return

    map.on('zoomend', () => {
        addMarkers()
    })
}

// Watch for project changes
watch(
    () => props.projects,
    () => {
        if (map) {
            addMarkers()
        }
    },
    { deep: true }
)

// Watch for selected project
watch(
    () => props.selectedProjectId,
    (newId) => {
        if (newId && markers[newId]) {
            const project = props.projects.find((p) => p.id === newId)
            if (project && map) {
                map.setView(
                    [project.coordinates.lat, project.coordinates.lng],
                    15,
                    { animate: true, duration: 0.5 }
                )
                markers[newId].openPopup()
            }
        }
    }
)

onMounted(async () => {
    await nextTick()
    await initMap()
    if (map) {
        setupZoomListener()
    }
})

onBeforeUnmount(() => {
    if (map) {
        map.remove()
        map = null
    }
})
</script>

<style>
.custom-marker,
.cluster-marker {
    background: transparent !important;
    border: none !important;
}

.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 12px !important;
    padding: 0 !important;
}

.custom-popup .leaflet-popup-content {
    margin: 0 !important;
}

.custom-popup .leaflet-popup-tip {
    background: white !important;
}

.leaflet-container {
    font-family: inherit;
}
</style>
