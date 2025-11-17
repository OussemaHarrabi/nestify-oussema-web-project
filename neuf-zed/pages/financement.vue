<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Unified Header with Auth -->
    <Header />

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary/5 via-white to-primary/10 py-20">
      <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <h1 class="text-5xl lg:text-6xl font-bold text-foreground mb-6">
              Solutions de Financement Immobilier
            </h1>
            <p class="text-xl text-muted-foreground mb-8">
              Découvrez les meilleures options de financement pour réaliser votre projet immobilier en Tunisie. Des taux compétitifs et un accompagnement personnalisé.
            </p>
            <div class="flex gap-4">
              <Button @click="scrollToCalculator" size="lg" class="bg-primary hover:bg-primary/90 text-white px-8">
                Calculer mes mensualités
              </Button>
              <Button variant="outline" size="lg" class="px-8">
                Nos partenaires
              </Button>
            </div>
          </div>
          <div class="bg-white rounded-3xl shadow-2xl p-8">
            <div class="mb-6">
              <h3 class="text-2xl font-bold mb-2">Offre Spéciale</h3>
              <p class="text-muted-foreground">Taux d'intérêt à partir de</p>
            </div>
            <div class="text-6xl font-bold text-primary mb-4">4.5%</div>
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="bg-muted/30 rounded-xl p-4">
                <div class="text-sm text-muted-foreground mb-1">Durée jusqu'à</div>
                <div class="text-2xl font-bold">25 ans</div>
              </div>
              <div class="bg-muted/30 rounded-xl p-4">
                <div class="text-sm text-muted-foreground mb-1">Apport minimum</div>
                <div class="text-2xl font-bold">10%</div>
              </div>
            </div>
            <Button class="w-full bg-primary hover:bg-primary/90 text-white">
              Demander un devis
            </Button>
          </div>
        </div>
      </div>
    </section>

    <!-- Calculator Section -->
    <section ref="calculatorSection" class="py-20 bg-white">
      <div class="max-w-[1200px] mx-auto px-6 lg:px-20">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-foreground mb-4">
            Calculateur de Prêt Immobilier
          </h2>
          <p class="text-xl text-muted-foreground">
            Estimez vos mensualités en quelques clics
          </p>
        </div>

        <div class="bg-gradient-to-br from-muted/20 to-white rounded-3xl shadow-xl p-8 lg:p-12">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Inputs -->
            <div class="space-y-8">
              <div>
                <label class="block text-sm font-semibold mb-3">Prix du bien (TND)</label>
                <input
                  v-model.number="propertyPrice"
                  type="number"
                  class="w-full px-6 py-4 border-2 border-border rounded-xl focus:border-primary focus:outline-none text-lg"
                  placeholder="200000"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold mb-3">Apport personnel ({{ downPaymentPercent }}%)</label>
                <input
                  v-model.number="downPayment"
                  type="range"
                  min="10"
                  max="50"
                  class="w-full"
                  @input="updateDownPaymentPercent"
                />
                <div class="text-2xl font-bold text-primary mt-2">{{ formatCurrency(downPaymentAmount) }} TND</div>
              </div>

              <div>
                <label class="block text-sm font-semibold mb-3">Durée du prêt (années)</label>
                <input
                  v-model.number="loanDuration"
                  type="range"
                  min="5"
                  max="25"
                  step="5"
                  class="w-full"
                />
                <div class="text-2xl font-bold text-primary mt-2">{{ loanDuration }} ans</div>
              </div>

              <div>
                <label class="block text-sm font-semibold mb-3">Taux d'intérêt annuel (%)</label>
                <input
                  v-model.number="interestRate"
                  type="number"
                  step="0.1"
                  class="w-full px-6 py-4 border-2 border-border rounded-xl focus:border-primary focus:outline-none text-lg"
                  placeholder="4.5"
                />
              </div>
            </div>

            <!-- Results -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
              <h3 class="text-2xl font-bold mb-8 text-center">Résultat</h3>

              <div class="space-y-6">
                <div class="bg-primary/5 rounded-xl p-6">
                  <div class="text-sm text-muted-foreground mb-2">Montant à emprunter</div>
                  <div class="text-3xl font-bold text-foreground">{{ formatCurrency(loanAmount) }} TND</div>
                </div>

                <div class="bg-primary rounded-xl p-6 text-white">
                  <div class="text-sm mb-2 opacity-90">Mensualité</div>
                  <div class="text-4xl font-bold">{{ formatCurrency(monthlyPayment) }} TND</div>
                  <div class="text-sm mt-2 opacity-90">par mois pendant {{ loanDuration }} ans</div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div class="bg-muted/30 rounded-xl p-4">
                    <div class="text-xs text-muted-foreground mb-1">Coût total du crédit</div>
                    <div class="text-lg font-bold">{{ formatCurrency(totalCost) }} TND</div>
                  </div>
                  <div class="bg-muted/30 rounded-xl p-4">
                    <div class="text-xs text-muted-foreground mb-1">Coût des intérêts</div>
                    <div class="text-lg font-bold">{{ formatCurrency(totalInterest) }} TND</div>
                  </div>
                </div>

                <Button class="w-full bg-primary hover:bg-primary/90 text-white py-6 text-lg">
                  <Calculator class="w-5 h-5 mr-2" />
                  Demander un financement
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Partners Section -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-foreground mb-4">
            Nos Partenaires Bancaires
          </h2>
          <p class="text-xl text-muted-foreground">
            Travaillez avec les meilleures institutions financières de Tunisie
          </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
          <div v-for="bank in banks" :key="bank.name" class="bg-white rounded-2xl p-8 flex items-center justify-center hover:shadow-xl transition-shadow cursor-pointer group">
            <div class="text-center">
              <div class="w-20 h-20 bg-muted/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/10 transition-colors">
                <Building2 class="w-10 h-10 text-primary" />
              </div>
              <div class="font-semibold">{{ bank.name }}</div>
            </div>
          </div>
        </div>

        <div class="mt-12 bg-white rounded-3xl shadow-xl p-8 lg:p-12">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="text-center p-6">
              <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <FileText class="w-8 h-8 text-primary" />
              </div>
              <h3 class="text-xl font-bold mb-2">Dossier Simplifié</h3>
              <p class="text-muted-foreground">Documents minimums requis pour votre demande</p>
            </div>

            <div class="text-center p-6">
              <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <Clock class="w-8 h-8 text-primary" />
              </div>
              <h3 class="text-xl font-bold mb-2">Réponse Rapide</h3>
              <p class="text-muted-foreground">Obtenez une réponse de principe en 48h</p>
            </div>

            <div class="text-center p-6">
              <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <HeadphonesIcon class="w-8 h-8 text-primary" />
              </div>
              <h3 class="text-xl font-bold mb-2">Accompagnement</h3>
              <p class="text-muted-foreground">Un conseiller dédié pour votre dossier</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
      <div class="max-w-[1200px] mx-auto px-6 lg:px-20">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-foreground mb-4">
            Questions Fréquentes
          </h2>
        </div>

        <div class="space-y-4">
          <div v-for="(faq, index) in faqs" :key="index" class="bg-muted/20 rounded-2xl overflow-hidden">
            <button
              @click="toggleFaq(index)"
              class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-muted/30 transition-colors"
            >
              <span class="font-semibold text-lg">{{ faq.question }}</span>
              <ChevronDown :class="['w-6 h-6 transition-transform', activeFaq === index ? 'rotate-180' : '']" />
            </button>
            <div v-if="activeFaq === index" class="px-8 pb-6 text-muted-foreground">
              {{ faq.answer }}
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Menu, User, Calculator, Building2, FileText, Clock, HeadphonesIcon, ChevronDown } from 'lucide-vue-next'
import Button from '~/components/ui/Button.vue'
import Footer from '~/components/Footer.vue'

// Calculator state
const propertyPrice = ref(200000)
const downPayment = ref(20)
const loanDuration = ref(20)
const interestRate = ref(4.5)
const activeFaq = ref<number | null>(null)
const calculatorSection = ref<HTMLElement | null>(null)

const downPaymentPercent = computed(() => downPayment.value)
const downPaymentAmount = computed(() => (propertyPrice.value * downPayment.value) / 100)
const loanAmount = computed(() => propertyPrice.value - downPaymentAmount.value)

const monthlyPayment = computed(() => {
  const principal = loanAmount.value
  const monthlyRate = interestRate.value / 100 / 12
  const numberOfPayments = loanDuration.value * 12

  if (monthlyRate === 0) return principal / numberOfPayments

  return (principal * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments)) /
    (Math.pow(1 + monthlyRate, numberOfPayments) - 1)
})

const totalCost = computed(() => monthlyPayment.value * loanDuration.value * 12)
const totalInterest = computed(() => totalCost.value - loanAmount.value)

const updateDownPaymentPercent = (event: Event) => {
  const target = event.target as HTMLInputElement
  downPayment.value = parseInt(target.value)
}

const formatCurrency = (value: number) => {
  return Math.round(value).toLocaleString('fr-TN')
}

const scrollToCalculator = () => {
  calculatorSection.value?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

const toggleFaq = (index: number) => {
  activeFaq.value = activeFaq.value === index ? null : index
}

const banks = [
  { name: 'BNA' },
  { name: 'BIAT' },
  { name: 'STB' },
  { name: 'Attijari Bank' },
  { name: 'Amen Bank' }
]

const faqs = [
  {
    question: 'Quel est l\'apport minimum requis?',
    answer: 'La plupart des banques tunisiennes exigent un apport personnel minimum de 10% à 20% du prix du bien immobilier. Certaines offres spéciales peuvent permettre des apports plus faibles.'
  },
  {
    question: 'Quelle est la durée maximale d\'un prêt immobilier?',
    answer: 'En Tunisie, la durée d\'un crédit immobilier peut aller jusqu\'à 25 ans, voire 30 ans dans certains cas particuliers. La durée optimale dépend de votre capacité de remboursement et de votre âge.'
  },
  {
    question: 'Quels documents sont nécessaires?',
    answer: 'Vous aurez besoin de : carte d\'identité, attestation de salaire ou relevés bancaires, justificatif de domicile, compromis de vente du bien, et tout document prouvant vos revenus complémentaires.'
  },
  {
    question: 'Peut-on renégocier son prêt immobilier?',
    answer: 'Oui, il est possible de renégocier votre prêt immobilier si les taux du marché ont baissé ou si votre situation financière s\'est améliorée. Cela peut permettre de réduire vos mensualités ou la durée du prêt.'
  },
  {
    question: 'Quels sont les frais annexes à prévoir?',
    answer: 'En plus du prix du bien, prévoyez : frais de dossier (environ 1% du prêt), frais de notaire, assurance emprunteur obligatoire, et les frais d\'enregistrement et de conservation foncière.'
  }
]
</script>
