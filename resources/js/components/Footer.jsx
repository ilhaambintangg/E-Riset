import React, { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { Scale, MapPin, Phone, Mail, Clock, Globe } from 'lucide-react'
import api from '../lib/api'

export default function Footer() {
  const [setting, setSetting] = useState(null)

  useEffect(() => {
    api.get('/public/settings').then(res => setSetting(res.data)).catch(() => {})
  }, [])
  return (
    <footer className="bg-blue-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-10">
          {/* Brand */}
          <div>
            <div className="flex items-center gap-2 mb-4">
              <div className="w-9 h-9 bg-amber-400 rounded-lg flex items-center justify-center">
                <Scale className="w-5 h-5 text-blue-900" />
              </div>
              <div>
                <p className="font-bold text-lg text-white">E-RISET</p>
                <p className="text-xs text-blue-300">{setting?.nama_instansi || 'PT Tanjungkarang'}</p>
              </div>
            </div>
            <p className="text-blue-200 text-sm leading-relaxed">
              Sistem Permohonan Izin Penelitian Elektronik Pengadilan Tinggi Tanjungkarang — layanan publik modern, transparan, dan akuntabel.
            </p>
          </div>

          {/* Navigasi */}
          <div>
            <h3 className="font-semibold text-amber-400 mb-4">Navigasi</h3>
            <ul className="space-y-2 text-sm text-blue-200">
              <li><a href="/#tentang" className="hover:text-white transition-colors">Tentang E-RISET</a></li>
              <li><a href="/#alur" className="hover:text-white transition-colors">Alur Pengajuan</a></li>
              <li><a href="/#persyaratan" className="hover:text-white transition-colors">Persyaratan</a></li>
              <li><Link to="/register-permit" className="hover:text-white transition-colors">Ajukan Izin Penelitian</Link></li>
              <li><Link to="/track" className="hover:text-white transition-colors">Cek Status Permohonan</Link></li>
              <li><a href="/#faq" className="hover:text-white transition-colors">FAQ</a></li>
            </ul>
          </div>

          {/* Kontak */}
          <div>
            <h3 className="font-semibold text-amber-400 mb-4">Kontak</h3>
            <ul className="space-y-3 text-sm text-blue-200">
              <li className="flex items-start gap-2">
                <MapPin className="w-4 h-4 mt-0.5 text-amber-400 shrink-0" />
                <span>{setting?.alamat || 'Jl. W.R. Supratman No.1, Tanjungkarang, Bandar Lampung 35121'}</span>
              </li>
              <li className="flex items-center gap-2">
                <Phone className="w-4 h-4 text-amber-400 shrink-0" />
                <span>{setting?.telepon || '(0721) 482163'}</span>
              </li>
              <li className="flex items-center gap-2">
                <Mail className="w-4 h-4 text-amber-400 shrink-0" />
                <span>{setting?.email || 'pt.tanjungkarang@gmail.com'}</span>
              </li>
              {setting?.website && (
                <li className="flex items-center gap-2">
                  <Globe className="w-4 h-4 text-amber-400 shrink-0" />
                  <a href={setting.website} target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors">{setting.website.replace(/^https?:\/\//, '')}</a>
                </li>
              )}
            </ul>

            {setting?.link_terkait && setting.link_terkait.length > 0 && (
              <div className="mt-6">
                <h3 className="font-semibold text-amber-400 mb-3">Link Terkait</h3>
                <ul className="space-y-2 text-sm text-blue-200">
                  {setting.link_terkait.map((link, i) => (
                    <li key={i}><a href={link.url} target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors">{link.title}</a></li>
                  ))}
                </ul>
              </div>
            )}
          </div>
        </div>

        <div className="border-t border-blue-800 mt-10 pt-6 text-center text-sm text-blue-400">
          © {new Date().getFullYear()} {setting?.nama_instansi || 'Pengadilan Tinggi Tanjungkarang'}. Hak Cipta Dilindungi. Sistem E-RISET.
        </div>
      </div>
    </footer>
  )
}
