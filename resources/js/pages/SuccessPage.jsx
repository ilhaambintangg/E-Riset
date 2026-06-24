import React from 'react'
import { useParams, Link, useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import { CheckCircle2, Copy, ArrowRight, Home } from 'lucide-react'
import Navbar from '../components/Navbar'
import Footer from '../components/Footer'

export default function SuccessPage() {
  const { registration_number } = useParams()
  const navigate = useNavigate()
  const [copied, setCopied] = React.useState(false)

  const copy = () => {
    navigator.clipboard.writeText(registration_number)
    setCopied(true)
    setTimeout(() => setCopied(false), 2000)
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <Navbar />
      <div className="flex items-center justify-center min-h-[calc(100vh-4rem)] py-20 px-4">
        <motion.div
          initial={{ scale: 0.8, opacity: 0 }}
          animate={{ scale: 1, opacity: 1 }}
          transition={{ duration: 0.5, type: 'spring' }}
          className="bg-white rounded-3xl shadow-2xl p-10 max-w-md w-full text-center border border-green-100"
        >
          <motion.div
            initial={{ scale: 0 }}
            animate={{ scale: 1 }}
            transition={{ delay: 0.3, type: 'spring', stiffness: 200 }}
            className="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6"
          >
            <CheckCircle2 className="w-12 h-12 text-green-600" />
          </motion.div>

          <h1 className="text-2xl font-bold text-gray-900 mb-2">Permohonan Terkirim!</h1>
          <p className="text-gray-500 mb-8 text-sm leading-relaxed">
            Permohonan Anda telah berhasil dikirim dan sedang menunggu verifikasi dari admin.
            Simpan nomor registrasi berikut untuk melacak status permohonan.
          </p>

          <div className="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-6">
            <p className="text-xs text-blue-500 font-semibold mb-2 uppercase tracking-wider">Nomor Registrasi Anda</p>
            <p className="text-2xl font-bold text-blue-900 font-mono tracking-widest mb-3">
              {registration_number}
            </p>
            <button
              onClick={copy}
              className="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
            >
              <Copy className="w-4 h-4" />
              {copied ? 'Tersalin!' : 'Salin Nomor'}
            </button>
          </div>

          <div className="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-left">
            <p className="text-xs text-amber-800 font-semibold mb-1">⚠ Penting</p>
            <p className="text-xs text-amber-700 leading-relaxed">
              Gunakan nomor registrasi ini untuk melacak status permohonan Anda. Proses verifikasi memakan waktu <strong>3–5 hari kerja</strong>.
            </p>
          </div>

          <div className="flex flex-col gap-3">
            <button
              onClick={() => navigate('/track')}
              className="flex items-center justify-center gap-2 bg-blue-800 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-colors shadow-md"
            >
              Lacak Status Permohonan
              <ArrowRight className="w-4 h-4" />
            </button>
            <Link
              to="/"
              className="flex items-center justify-center gap-2 border border-gray-200 text-gray-600 font-semibold py-3 rounded-xl hover:bg-gray-50 transition-colors"
            >
              <Home className="w-4 h-4" />
              Kembali ke Beranda
            </Link>
          </div>
        </motion.div>
      </div>
      <Footer />
    </div>
  )
}
