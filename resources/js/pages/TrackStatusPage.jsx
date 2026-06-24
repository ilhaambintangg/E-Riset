import React, { useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import {
  Search, CheckCircle2, Clock, XCircle, AlertCircle,
  FileText, Calendar, User, Building2, Scale, ChevronDown, ChevronUp
} from 'lucide-react'
import Navbar from '../components/Navbar'
import Footer from '../components/Footer'
import api from '../lib/api'

const STATUS_CONFIG = {
  'Menunggu Verifikasi': { color: 'text-amber-700 bg-amber-100 border-amber-300', icon: Clock, dot: 'bg-amber-500' },
  'Sedang Diproses':    { color: 'text-blue-700 bg-blue-100 border-blue-300', icon: AlertCircle, dot: 'bg-blue-500' },
  'Disetujui':          { color: 'text-green-700 bg-green-100 border-green-300', icon: CheckCircle2, dot: 'bg-green-500' },
  'Ditolak':            { color: 'text-red-700 bg-red-100 border-red-300', icon: XCircle, dot: 'bg-red-500' },
}
const DEFAULT_STATUS = { color: 'text-gray-700 bg-gray-100 border-gray-300', icon: Clock, dot: 'bg-gray-500' }

export default function TrackStatusPage() {
  const [regNum, setRegNum] = useState('')
  const [loading, setLoading] = useState(false)
  const [result, setResult] = useState(null)
  const [error, setError] = useState('')
  const [showHistory, setShowHistory] = useState(false)

  const handleSearch = async (e) => {
    e.preventDefault()
    if (!regNum.trim()) { setError('Masukkan nomor registrasi.'); return }
    setLoading(true); setError(''); setResult(null)
    try {
      const resp = await api.get(`/public/submissions/track/${regNum.trim()}`)
      setResult(resp.data)
    } catch (err) {
      if (err.response?.status === 404) {
        setError('Nomor registrasi tidak ditemukan. Pastikan nomor yang Anda masukkan benar.')
      } else {
        setError('Terjadi kesalahan. Coba lagi beberapa saat.')
      }
    } finally { setLoading(false) }
  }

  const submission = result?.submission || result
  const statusCfg = submission ? (STATUS_CONFIG[submission.current_status] || DEFAULT_STATUS) : null

  return (
    <div className="min-h-screen bg-gray-50">
      <Navbar />
      <div className="pt-20 min-h-[calc(100vh-4rem)] px-4 py-12">
        <div className="max-w-2xl mx-auto">
          {/* Header */}
          <div className="text-center mb-8">
            <div className="inline-flex items-center gap-2 bg-blue-100 px-4 py-1.5 rounded-full mb-4">
              <Scale className="w-4 h-4 text-blue-800" />
              <span className="text-blue-800 font-medium text-sm">E-RISET</span>
            </div>
            <h1 className="text-2xl sm:text-3xl font-bold text-blue-900 mb-2">Lacak Status Permohonan</h1>
            <p className="text-gray-500">Masukkan nomor registrasi yang Anda terima setelah pengajuan</p>
          </div>

          {/* Search Form */}
          <form onSubmit={handleSearch} className="bg-white rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-100 mb-6">
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              Nomor Registrasi
            </label>
            <div className="flex gap-3">
              <input
                value={regNum}
                onChange={(e) => setRegNum(e.target.value.toUpperCase())}
                className="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 focus:bg-white font-mono text-sm uppercase placeholder:normal-case placeholder:font-sans"
                placeholder="Contoh: ERS-2025-00001"
              />
              <button
                type="submit" disabled={loading}
                className="flex items-center gap-2 bg-blue-800 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 disabled:opacity-60 transition-colors shadow-md"
              >
                {loading ? (
                  <svg className="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"/>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                  </svg>
                ) : <Search className="w-5 h-5" />}
                {loading ? 'Mencari...' : 'Cari'}
              </button>
            </div>
            {error && (
              <div className="flex items-start gap-2 mt-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-red-700 text-sm">
                <AlertCircle className="w-4 h-4 mt-0.5 shrink-0" />
                {error}
              </div>
            )}
          </form>

          {/* Result */}
          <AnimatePresence>
            {submission && (
              <motion.div
                initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}
                className="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
              >
                {/* Status Header */}
                <div className={`p-5 border-b ${statusCfg?.color} border-opacity-50`}>
                  <div className="flex items-center justify-between flex-wrap gap-3">
                    <div>
                      <p className="text-xs text-gray-500 font-medium mb-0.5">Nomor Registrasi</p>
                      <p className="font-mono font-bold text-lg text-gray-900">{submission.registration_number}</p>
                    </div>
                    <div className={`flex items-center gap-2 px-4 py-2 rounded-full border font-semibold text-sm ${statusCfg?.color}`}>
                      <div className={`w-2 h-2 rounded-full ${statusCfg?.dot} animate-pulse`} />
                      {statusCfg?.label}
                    </div>
                  </div>
                </div>

                {/* Submission Detail */}
                <div className="p-6 space-y-4">
                  <h3 className="font-bold text-blue-900">Detail Permohonan</h3>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {[
                      { icon: User, label: 'Nama Pemohon', val: submission.name || submission.full_name },
                      { icon: Building2, label: 'Institusi', val: submission.university || submission.institution },
                      { icon: FileText, label: 'Judul Penelitian', val: submission.title || submission.research_title },
                      { icon: Calendar, label: 'Tanggal Pengajuan', val: submission.created_at ? new Date(submission.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-' },
                    ].map(({ icon: Icon, label, val }) => (
                      <div key={label} className="bg-gray-50 rounded-xl p-4">
                        <div className="flex items-center gap-2 mb-1">
                          <Icon className="w-4 h-4 text-gray-400" />
                          <p className="text-xs text-gray-500 font-medium">{label}</p>
                        </div>
                        <p className="text-sm text-gray-800 font-semibold">{val || '-'}</p>
                      </div>
                    ))}
                  </div>

                  {/* Surat Izin (Jika Disetujui) */}
                  {submission.current_status === 'Disetujui' && (
                    <div className="bg-green-50 border border-green-200 rounded-xl p-5">
                      <h3 className="font-bold text-green-800 mb-2 flex items-center gap-2">
                        <CheckCircle2 className="w-5 h-5" /> Surat Izin Tersedia
                      </h3>
                      <p className="text-sm text-green-700 mb-4">
                        Permohonan izin penelitian Anda telah disetujui. Anda dapat mengunduh surat izin elektronik melalui tombol di bawah ini.
                      </p>
                      <button
                        onClick={() => {
                          const apiUrl = api.defaults.baseURL || 'http://127.0.0.1:8000/api'
                          window.open(`${apiUrl}/public/submissions/${submission.registration_number}/download-permit`, '_blank')
                        }}
                        className="flex items-center gap-2 bg-green-700 hover:bg-green-600 active:bg-green-800 text-white font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm"
                      >
                        <FileText className="w-4 h-4" />
                        Unduh Surat Izin (PDF)
                      </button>
                    </div>
                  )}

                  {/* Admin Notes */}
                  {submission.admin_notes && (
                    <div className="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                      <p className="font-semibold mb-1">Catatan dari Admin:</p>
                      <p>{submission.admin_notes}</p>
                    </div>
                  )}

                  {/* Status History */}
                  {submission.status_logs?.length > 0 && (
                    <div>
                      <button
                        onClick={() => setShowHistory(!showHistory)}
                        className="flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-900 transition-colors"
                      >
                        Riwayat Status
                        {showHistory ? <ChevronUp className="w-4 h-4" /> : <ChevronDown className="w-4 h-4" />}
                      </button>
                      <AnimatePresence>
                        {showHistory && (
                          <motion.div
                            initial={{ opacity: 0, height: 0 }} animate={{ opacity: 1, height: 'auto' }}
                            exit={{ opacity: 0, height: 0 }} className="mt-3 space-y-2"
                          >
                            {submission.status_logs.map((h, i) => {
                              const cfg = STATUS_CONFIG[h.status] || DEFAULT_STATUS
                              return (
                                <div key={i} className="flex items-start gap-3 text-sm">
                                  <div className={`w-2 h-2 rounded-full mt-1.5 shrink-0 ${cfg.dot}`} />
                                  <div>
                                    <p className="font-semibold text-gray-800">{h.status}</p>
                                    {h.notes && <p className="text-gray-500">{h.notes}</p>}
                                    <p className="text-gray-400 text-xs">{new Date(h.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                                  </div>
                                </div>
                              )
                            })}
                          </motion.div>
                        )}
                      </AnimatePresence>
                    </div>
                  )}
                </div>
              </motion.div>
            )}
          </AnimatePresence>

          {/* Info Box */}
          <div className="mt-8 bg-blue-50 border border-blue-200 rounded-2xl p-5 text-sm text-blue-800">
            <p className="font-semibold mb-2 flex items-center gap-2">
              <AlertCircle className="w-4 h-4" /> Informasi
            </p>
            <ul className="list-disc list-inside space-y-1 text-blue-700">
              <li>Nomor registrasi dikirim melalui halaman konfirmasi setelah mengisi formulir.</li>
              <li>Format: <code className="font-mono bg-blue-100 px-1 rounded">ERS-YYYY-XXXXX</code></li>
              <li>Proses verifikasi memakan waktu 3–5 hari kerja.</li>
              <li>Hubungi kantor jika ada pertanyaan lebih lanjut.</li>
            </ul>
          </div>
        </div>
      </div>
      <Footer />
    </div>
  )
}
