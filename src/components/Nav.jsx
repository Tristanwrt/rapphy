import { useEffect, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { Icon } from './Icon.jsx'
import { VILLA } from '../config/villa.js'

const LINKS = [
  { href: '#villa', label: 'La villa' },
  { href: '#chambres', label: 'Chambres' },
  { href: '#exterieurs', label: 'Piscine & jardin' },
  { href: '#avis', label: 'Avis' },
  { href: '#region', label: 'La région' },
  { href: '#contact', label: 'Contact' },
]

export function Nav() {
  const [scrolled, setScrolled] = useState(false)
  const [open, setOpen] = useState(false)

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 40)
    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  return (
    <header
      className={`fixed inset-x-0 top-0 z-50 transition-all duration-300 ${
        scrolled ? 'bg-canvas/90 backdrop-blur-md shadow-card' : 'bg-transparent'
      }`}
    >
      <nav className="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
        <a href="#accueil" className="flex items-baseline gap-2">
          <span
            className={`font-display text-xl font-medium tracking-wide transition-colors ${
              scrolled ? 'text-ink' : 'text-linen'
            }`}
          >
            Villa Raffy
          </span>
          <span className="hidden text-[0.65rem] uppercase tracking-[0.22em] text-brass sm:block">
            Oasis d'exception
          </span>
        </a>

        <div className="hidden items-center gap-7 lg:flex">
          {LINKS.map((l) => (
            <a
              key={l.href}
              href={l.href}
              className={`text-sm transition-colors hover:text-brass ${
                scrolled ? 'text-ink-soft' : 'text-linen/85'
              }`}
            >
              {l.label}
            </a>
          ))}
          <a
            href={`tel:${VILLA.telephoneMobileIntl}`}
            className={`flex items-center gap-2 text-sm font-medium transition-colors hover:text-brass ${
              scrolled ? 'text-ink' : 'text-linen'
            }`}
          >
            <Icon name="phone" className="h-4 w-4" />
            {VILLA.telephoneMobile}
          </a>
          <a
            href="#reserver"
            className="rounded-full bg-brass px-5 py-2.5 text-sm font-medium text-linen shadow-card transition-transform duration-200 hover:scale-[1.04] hover:bg-[#9a7a49]"
          >
            Réserver en direct
          </a>
        </div>

        <button
          type="button"
          aria-label={open ? 'Fermer le menu' : 'Ouvrir le menu'}
          onClick={() => setOpen(!open)}
          className={`flex h-11 w-11 cursor-pointer items-center justify-center rounded-full lg:hidden ${
            scrolled ? 'text-ink' : 'text-linen'
          }`}
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-6 w-6" aria-hidden="true">
            {open ? <path d="M6 6l12 12M18 6L6 18" /> : <path d="M3 7h18M3 12h18M3 17h18" />}
          </svg>
        </button>
      </nav>

      <AnimatePresence>
        {open && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            transition={{ duration: 0.25 }}
            className="overflow-hidden bg-canvas/97 backdrop-blur-md shadow-card lg:hidden"
          >
            <div className="flex flex-col gap-1 px-6 pb-6 pt-2">
              {LINKS.map((l) => (
                <a
                  key={l.href}
                  href={l.href}
                  onClick={() => setOpen(false)}
                  className="rounded-lg px-3 py-3 text-ink-soft transition-colors hover:bg-canvas-deep hover:text-ink"
                >
                  {l.label}
                </a>
              ))}
              <a
                href="#reserver"
                onClick={() => setOpen(false)}
                className="mt-3 rounded-full bg-brass px-5 py-3 text-center font-medium text-linen"
              >
                Réserver en direct
              </a>
              <a
                href={`tel:${VILLA.telephoneMobileIntl}`}
                className="mt-1 flex items-center justify-center gap-2 px-5 py-3 text-ink"
              >
                <Icon name="phone" className="h-4 w-4" />
                {VILLA.telephoneMobile}
              </a>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  )
}
