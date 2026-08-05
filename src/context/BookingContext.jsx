import { createContext, useContext, useState } from 'react'

// État de réservation partagé entre la barre de recherche du héro
// et le calendrier de la section Réservation.
const BookingCtx = createContext(null)

export function BookingProvider({ children }) {
  const [arrivee, setArrivee] = useState(null)
  const [depart, setDepart] = useState(null)
  const [voyageurs, setVoyageurs] = useState(2)

  return (
    <BookingCtx.Provider
      value={{ arrivee, setArrivee, depart, setDepart, voyageurs, setVoyageurs }}
    >
      {children}
    </BookingCtx.Provider>
  )
}

export function useBooking() {
  return useContext(BookingCtx)
}
