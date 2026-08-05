import { Stagger, StaggerItem } from './Reveal.jsx'
import { Icon } from './Icon.jsx'

const STATS = [
  { icon: 'ruler', valeur: '180 m²', label: 'de villa de plain-pied' },
  { icon: 'users', valeur: '8', label: 'voyageurs' },
  { icon: 'bed', valeur: '4', label: 'chambres & suites' },
  { icon: 'tree', valeur: '2300 m²', label: 'de terrain clos' },
  { icon: 'waves', valeur: '9 m', label: 'de piscine, bar immergé' },
  { icon: 'spa', valeur: '5 places', label: 'de jacuzzi' },
]

export function Stats() {
  return (
    <section className="mx-auto max-w-7xl px-5 py-16 lg:px-8">
      <Stagger className="grid grid-cols-2 gap-x-6 gap-y-10 sm:grid-cols-3 lg:grid-cols-6">
        {STATS.map((s) => (
          <StaggerItem key={s.label} className="text-center">
            <div className="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full border border-brass/30 text-brass">
              <Icon name={s.icon} className="h-5 w-5" />
            </div>
            <div className="font-display text-2xl font-medium">{s.valeur}</div>
            <div className="mt-1 text-sm text-ink-soft">{s.label}</div>
          </StaggerItem>
        ))}
      </Stagger>
    </section>
  )
}
