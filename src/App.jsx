import { Nav } from './components/Nav.jsx'
import { Hero } from './components/Hero.jsx'
import { DirectBanner } from './components/DirectBanner.jsx'
import { Stats } from './components/Stats.jsx'
import { Villa } from './components/Villa.jsx'
import { Chambres } from './components/Chambres.jsx'
import { Exterieurs } from './components/Exterieurs.jsx'
import { BienEtre } from './components/BienEtre.jsx'
import { Avis } from './components/Avis.jsx'
import { Booking } from './components/Booking.jsx'
import { Region } from './components/Region.jsx'
import { Faq } from './components/Faq.jsx'
import { Footer } from './components/Footer.jsx'

export default function App() {
  return (
    <>
      <Nav />
      <main>
        <Hero />
        <Stats />
        <DirectBanner />
        <Villa />
        <Chambres />
        <Exterieurs />
        <BienEtre />
        <Avis />
        <Booking />
        <Region />
        <Faq />
      </main>
      <Footer />
    </>
  )
}
