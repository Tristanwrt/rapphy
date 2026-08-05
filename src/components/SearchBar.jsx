import { useState } from 'react'
import { motion } from 'framer-motion'
import { Icon } from './Icon.jsx'
import { useBooking } from '../context/BookingContext.jsx'

// Barre de recherche type Airbnb/Booking : le CTA principal du héro.
// Les dates choisies pré-remplissent le calendrier de la section Réservation.
export function SearchBar() {
  const { setArrivee, setDepart, voyageurs, setVoyageurs } = useBooking()
  const [dateArrivee, setDateArrivee] = useState('')
  const [dateDepart, setDateDepart] = useState('')

  const aujourdhui = new Date().toISOString().split('T')[0]

  const parseLocal = (s) => {
    const [y, m, j] = s.split('-').map(Number)
    return new Date(y, m - 1, j)
  }

  const rechercher = (e) => {
    e.preventDefault()
    if (dateArrivee) setArrivee(parseLocal(dateArrivee))
    if (dateDepart && dateArrivee && dateDepart > dateArrivee) setDepart(parseLocal(dateDepart))
    document.getElementById('reserver')?.scrollIntoView({ behavior: 'smooth' })
  }

  return (
    <motion.form
      onSubmit={rechercher}
      initial={{ opacity: 0, y: 30 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.8, delay: 0.7 }}
      className="mt-9 flex w-full max-w-3xl flex-col overflow-hidden rounded-3xl bg-linen/95 shadow-soft backdrop-blur-md sm:flex-row sm:items-stretch sm:rounded-full"
    >
      <label className="flex flex-1 cursor-pointer flex-col gap-0.5 px-6 py-3.5 transition-colors hover:bg-canvas-deep/60">
        <span className="text-[0.65rem] font-medium uppercase tracking-[0.18em] text-ink-soft">
          Arrivée
        </span>
        <input
          type="date"
          value={dateArrivee}
          min={aujourdhui}
          onChange={(e) => setDateArrivee(e.target.value)}
          className="cursor-pointer bg-transparent text-sm font-medium text-ink outline-none"
          aria-label="Date d'arrivée"
        />
      </label>

      <div className="hidden w-px bg-ink/10 sm:block" aria-hidden="true" />

      <label className="flex flex-1 cursor-pointer flex-col gap-0.5 border-t border-ink/10 px-6 py-3.5 transition-colors hover:bg-canvas-deep/60 sm:border-t-0">
        <span className="text-[0.65rem] font-medium uppercase tracking-[0.18em] text-ink-soft">
          Départ
        </span>
        <input
          type="date"
          value={dateDepart}
          min={dateArrivee || aujourdhui}
          onChange={(e) => setDateDepart(e.target.value)}
          className="cursor-pointer bg-transparent text-sm font-medium text-ink outline-none"
          aria-label="Date de départ"
        />
      </label>

      <div className="hidden w-px bg-ink/10 sm:block" aria-hidden="true" />

      <label className="flex flex-1 cursor-pointer flex-col gap-0.5 border-t border-ink/10 px-6 py-3.5 transition-colors hover:bg-canvas-deep/60 sm:border-t-0">
        <span className="text-[0.65rem] font-medium uppercase tracking-[0.18em] text-ink-soft">
          Voyageurs
        </span>
        <select
          value={voyageurs}
          onChange={(e) => setVoyageurs(Number(e.target.value))}
          className="cursor-pointer bg-transparent text-sm font-medium text-ink outline-none"
          aria-label="Nombre de voyageurs"
        >
          {[1, 2, 3, 4, 5, 6, 7, 8].map((n) => (
            <option key={n} value={n}>
              {n} voyageur{n > 1 ? 's' : ''}
            </option>
          ))}
        </select>
      </label>

      <div className="p-2.5 sm:flex sm:items-center">
        <button
          type="submit"
          className="flex w-full cursor-pointer items-center justify-center gap-2 rounded-full bg-brass px-7 py-3.5 font-medium text-linen shadow-card transition-transform duration-200 hover:scale-[1.03] sm:w-auto"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4.5 w-4.5" aria-hidden="true">
            <circle cx="11" cy="11" r="7" />
            <path d="M21 21l-4.35-4.35" />
          </svg>
          Rechercher
        </button>
      </div>
    </motion.form>
  )
}
