import React, { useState } from 'react'
import { Link, useLocation } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import { Scale, Menu, X } from 'lucide-react'

export default function Navbar() {
  const [menuOpen, setMenuOpen] = useState(false)
  const location = useLocation()

  const navLinks = [
    { href: '/#tentang', label: 'Tentang' },
    { href: '/#alur', label: 'Alur Pengajuan' },
    { href: '/#persyaratan', label: 'Persyaratan' },
    { href: '/#faq', label: 'FAQ' },
    { href: '/#kontak', label: 'Kontak' },
  ]

  return (
    <nav className="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-blue-100 shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <Link to="/" className="flex items-center gap-2 group">
            <div className="w-9 h-9 bg-gradient-to-br from-blue-800 to-blue-600 rounded-lg flex items-center justify-center shadow-md group-hover:shadow-blue-300 transition-all">
              <Scale className="w-5 h-5 text-white" />
            </div>
            <div>
              <span className="text-lg font-bold text-blue-900">E-RISET</span>
              <p className="text-[10px] text-gray-500 leading-tight hidden sm:block">PT Tanjungkarang</p>
            </div>
          </Link>

          {/* Desktop Nav */}
          <div className="hidden md:flex items-center gap-6">
            {navLinks.map(link => (
              <a
                key={link.href}
                href={link.href}
                className="text-sm text-gray-600 hover:text-blue-800 font-medium transition-colors"
              >
                {link.label}
              </a>
            ))}
          </div>

          {/* CTA Buttons */}
          <div className="hidden md:flex items-center gap-3">
            <Link
              to="/track"
              className="text-sm text-blue-800 font-semibold border border-blue-800 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors"
            >
              Cek Status
            </Link>
            <Link
              to="/register-permit"
              className="text-sm bg-gradient-to-r from-blue-800 to-blue-700 text-white font-semibold px-4 py-2 rounded-lg hover:from-blue-700 hover:to-blue-600 shadow-md hover:shadow-blue-300 transition-all"
            >
              Ajukan Sekarang
            </Link>
          </div>

          {/* Mobile Menu Button */}
          <button
            className="md:hidden p-2 text-gray-700 hover:text-blue-800"
            onClick={() => setMenuOpen(!menuOpen)}
          >
            {menuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      <AnimatePresence>
        {menuOpen && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            className="md:hidden bg-white border-t border-blue-100 px-4 pb-4"
          >
            <div className="flex flex-col gap-3 pt-4">
              {navLinks.map(link => (
                <a
                  key={link.href}
                  href={link.href}
                  onClick={() => setMenuOpen(false)}
                  className="text-gray-700 font-medium hover:text-blue-800 py-2 border-b border-gray-100"
                >
                  {link.label}
                </a>
              ))}
              <Link
                to="/track"
                className="text-center text-blue-800 font-semibold border border-blue-800 px-4 py-2 rounded-lg mt-2"
                onClick={() => setMenuOpen(false)}
              >
                Cek Status
              </Link>
              <Link
                to="/register-permit"
                className="text-center bg-blue-800 text-white font-semibold px-4 py-2 rounded-lg"
                onClick={() => setMenuOpen(false)}
              >
                Ajukan Sekarang
              </Link>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </nav>
  )
}
