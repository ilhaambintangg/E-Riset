import React, { useEffect, useState } from 'react'
import { motion } from 'framer-motion'
import { useNavigate, useParams, Link } from 'react-router-dom'
import {
  Scale, LayoutDashboard, ListChecks, HelpCircle, Megaphone, LogOut,
  ArrowLeft, FileText, Download, CheckCircle2, XCircle, Clock, Loader2,
  User, Building2, BookOpen, Calendar, Upload, ChevronDown, Menu, AlertCircle
} from 'lucide-react'
import api from '../../lib/api'

const STATUS_COLORS = {
  'Menunggu Verifikasi': 'bg-yellow-100 text-yellow-800 border-yellow-200',
  'Sedang Diproses': 'bg-blue-100 text-blue-800 border-blue-200',
  'Disetujui': 'bg-green-100 text-green-800 border-green-200',
  'Ditolak': 'bg-red-100 text-red-800 border-red-200',
}

import { Sidebar } from './AdminDashboard'

export default function AdminSubmissions() {
  const { id } = useParams()
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [submission, setSubmission] = useState(null)
  const [loading, setLoading] = useState(true)
  const [updating, setUpdating] = useState(false)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [statusForm, setStatusForm] = useState({ status: '', notes: '', permit_file: null })
  const [showStatusForm, setShowStatusForm] = useState(false)
  
  // Generate Surat state
  const [paniteras, setPaniteras] = useState([])
  const [selectedPanitera, setSelectedPanitera] = useState('')
  const [generating, setGenerating] = useState(false)
  const [generatedLetter, setGeneratedLetter] = useState(null)

  const admin = JSON.parse(localStorage.getItem('admin_user') || '{}')

  useEffect(() => {
    Promise.all([
      api.get(`/admin/submissions/${id}`),
      api.get('/admin/panitera')
    ])
      .then(([subRes, panRes]) => {
        setSubmission(subRes.data)
        setStatusForm(f => ({ ...f, status: subRes.data.current_status }))
        
        // Filter active panitera
        const activePanitera = panRes.data.filter(p => p.status_aktif)
        setPaniteras(activePanitera)
        
        // Check if letter already generated (assuming backend might send it, but we can also fetch it)
        // Wait, we need to check if we can download it. We'll handle it if it exists.
      })
      .catch(() => navigate('/admin/dashboard'))
      .finally(() => setLoading(false))
  }, [id, navigate])

  const handleLogout = async () => {
    try { await api.post('/admin/logout') } catch {}
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    navigate('/admin/login')
  }

  const handleUpdateStatus = async (e) => {
    e.preventDefault()
    if (!statusForm.status) { setError('Pilih status terlebih dahulu.'); return }
    setUpdating(true); setError(''); setSuccess('')
    try {
      const fd = new FormData()
      fd.append('status', statusForm.status)
      if (statusForm.notes) fd.append('notes', statusForm.notes)
      if (statusForm.status === 'Sedang Diproses' && selectedPanitera) {
        fd.append('panitera_id', selectedPanitera)
      }
      if (statusForm.status === 'Disetujui' && statusForm.permit_file) {
        fd.append('permit_file', statusForm.permit_file)
      }
      await api.post(`/admin/submissions/${id}/status`, fd, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      setSuccess('Status berhasil diperbarui.')
      setShowStatusForm(false)
      // Refresh
      const r = await api.get(`/admin/submissions/${id}`)
      setSubmission(r.data)
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal memperbarui status.')
    } finally { setUpdating(false) }
  }

  const handleGenerateLetter = async () => {
    if (!selectedPanitera) {
      setError('Pilih panitera terlebih dahulu.')
      return
    }
    setGenerating(true)
    setError('')
    try {
      const { data } = await api.post(`/admin/submissions/${id}/generate-letter`, { panitera_id: selectedPanitera })
      setSuccess('Surat berhasil dibuat!')
      setGeneratedLetter(data.download_url)
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal membuat surat. Pastikan template sudah diupload.')
    } finally {
      setGenerating(false)
    }
  }

  const downloadFile = (url, filename) => {
    const a = document.createElement('a')
    a.href = url
    a.download = filename
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
  }

  if (loading) return (
    <div className="min-h-screen flex items-center justify-center">
      <Loader2 className="w-8 h-8 animate-spin text-blue-800" />
    </div>
  )

  return (
    <div className="min-h-screen bg-gray-50 flex">
      <Sidebar active="/admin/submissions" onLogout={handleLogout} open={sidebarOpen} setOpen={setSidebarOpen} />

      <main className="flex-1 lg:ml-64 min-w-0">
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-3 sticky top-0 z-10">
          <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-500"><Menu className="w-5 h-5" /></button>
          <Link to="/admin/dashboard" className="text-gray-400 hover:text-blue-800 transition-colors">
            <ArrowLeft className="w-5 h-5" />
          </Link>
          <div>
            <h1 className="font-bold text-blue-900 text-lg">Detail Permohonan</h1>
            <p className="text-gray-400 text-xs font-mono">{submission?.registration_number}</p>
          </div>
        </header>

        <div className="p-6 space-y-5 max-w-4xl mx-auto">
          {/* Alerts */}
          {error && (
            <div className="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-red-700 text-sm">
              <AlertCircle className="w-4 h-4 shrink-0" />{error}
            </div>
          )}
          {success && (
            <div className="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm">
              <CheckCircle2 className="w-4 h-4 shrink-0" />{success}
            </div>
          )}

          {/* Status Card */}
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <p className="text-xs text-gray-500 mb-1">Status Permohonan</p>
                <span className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold border ${STATUS_COLORS[submission?.current_status] || 'bg-gray-100 text-gray-600 border-gray-200'}`}>
                  {submission?.current_status}
                </span>
                {submission?.admin_notes && (
                  <p className="text-sm text-gray-600 mt-2 italic">"{submission.admin_notes}"</p>
                )}
              </div>
              <button
                onClick={() => setShowStatusForm(!showStatusForm)}
                className="flex items-center gap-2 bg-blue-800 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm"
              >
                Update Status <ChevronDown className={`w-4 h-4 transition-transform ${showStatusForm ? 'rotate-180' : ''}`} />
              </button>
            </div>

            {showStatusForm && (
              <motion.form
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: 'auto' }}
                onSubmit={handleUpdateStatus}
                className="mt-5 pt-5 border-t border-gray-100 space-y-4"
              >
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-1.5">Status Baru</label>
                  <select
                    value={statusForm.status}
                    onChange={e => setStatusForm(f => ({ ...f, status: e.target.value }))}
                    className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                  >
                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                    <option value="Sedang Diproses">Sedang Diproses</option>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Ditolak">Ditolak</option>
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-1.5">Catatan Admin</label>
                  <textarea
                    rows={3}
                    value={statusForm.notes}
                    onChange={e => setStatusForm(f => ({ ...f, notes: e.target.value }))}
                    placeholder="Catatan untuk pemohon (opsional)..."
                    className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 resize-none"
                  />
                </div>
                {statusForm.status === 'Sedang Diproses' && (
                  <div>
                    <label className="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Panitera Penandatangan</label>
                    <select
                      value={selectedPanitera}
                      onChange={e => setSelectedPanitera(e.target.value)}
                      className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                      required
                    >
                      <option value="">-- Pilih Panitera --</option>
                      {paniteras.map(p => (
                        <option key={p.id} value={p.id}>{p.nama_panitera} - {p.jabatan}</option>
                      ))}
                    </select>
                    <p className="text-xs text-gray-500 mt-1.5">Pilih panitera untuk otomatis membuat draf surat izin (DOCX).</p>
                  </div>
                )}
                {statusForm.status === 'Disetujui' && (
                  <div>
                    <label className="block text-sm font-semibold text-gray-700 mb-1.5">Upload Surat Izin (PDF Bertanda Tangan)</label>
                    <label className={`flex items-center gap-2 border-2 border-dashed rounded-xl px-4 py-3 cursor-pointer transition-colors ${statusForm.permit_file ? 'border-blue-400 bg-blue-50' : 'border-gray-200 hover:border-blue-300 bg-gray-50'}`}>
                      <Upload className={`w-4 h-4 ${statusForm.permit_file ? 'text-blue-600' : 'text-gray-400'}`} />
                      <span className={`text-sm ${statusForm.permit_file ? 'text-blue-700 font-medium' : 'text-gray-500'}`}>
                        {statusForm.permit_file ? statusForm.permit_file.name : 'Pilih file PDF surat izin yang sudah ditandatangani...'}
                      </span>
                      <input type="file" accept=".pdf" className="hidden"
                        onChange={e => setStatusForm(f => ({ ...f, permit_file: e.target.files[0] || null }))} required />
                    </label>
                  </div>
                )}
                <div className="flex gap-3">
                  <button type="submit" disabled={updating}
                    className="bg-blue-800 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors disabled:opacity-60">
                    {updating ? 'Menyimpan...' : 'Simpan Status'}
                  </button>
                  <button type="button" onClick={() => setShowStatusForm(false)}
                    className="border border-gray-200 text-gray-600 text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                  </button>
                </div>
              </motion.form>
            )}
          </div>

          {/* Data Pemohon */}
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 className="font-bold text-blue-900 mb-4 flex items-center gap-2">
              <User className="w-4 h-4 text-blue-600" />Data Pemohon
            </h2>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3">
              {[
                ['Nama Lengkap', submission?.name || submission?.full_name],
                ['Email', submission?.email],
                ['No. HP', submission?.phone],
                ['Institusi', submission?.institution || submission?.university],
                ['Jenis Pemohon', submission?.researcher_type],
              ].map(([k, v]) => (
                <div key={k} className="flex flex-col">
                  <span className="text-xs text-gray-400 font-medium">{k}</span>
                  <span className="text-sm text-gray-800 font-semibold">{v || '-'}</span>
                </div>
              ))}
            </div>
          </div>

          {/* Data Penelitian */}
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 className="font-bold text-blue-900 mb-4 flex items-center gap-2">
              <BookOpen className="w-4 h-4 text-blue-600" />Data Penelitian
            </h2>
            <div className="space-y-3">
              <div>
                <span className="text-xs text-gray-400 font-medium">Judul Penelitian</span>
                <p className="text-sm text-gray-800 font-semibold">{submission?.title || submission?.research_title || '-'}</p>
              </div>
              <div>
                <span className="text-xs text-gray-400 font-medium">Bidang Penelitian</span>
                <p className="text-sm text-gray-800 font-semibold">{submission?.field || submission?.research_field || '-'}</p>
              </div>
              {(submission?.description || submission?.research_description) && (
                <div>
                  <span className="text-xs text-gray-400 font-medium">Deskripsi</span>
                  <p className="text-sm text-gray-700 leading-relaxed">{submission?.description || submission?.research_description}</p>
                </div>
              )}
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <span className="text-xs text-gray-400 font-medium">Tanggal Mulai</span>
                  <p className="text-sm text-gray-800 font-semibold">{submission?.start_date || '-'}</p>
                </div>
                <div>
                  <span className="text-xs text-gray-400 font-medium">Tanggal Selesai</span>
                  <p className="text-sm text-gray-800 font-semibold">{submission?.end_date || '-'}</p>
                </div>
              </div>
              {(submission?.supervisor_name) && (
                <div>
                  <span className="text-xs text-gray-400 font-medium">Dosen Pembimbing</span>
                  <p className="text-sm text-gray-800 font-semibold">{submission.supervisor_name}</p>
                </div>
              )}
            </div>
          </div>

          {/* Dokumen */}
          {submission?.documents?.length > 0 && (
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
              <h2 className="font-bold text-blue-900 mb-4 flex items-center gap-2">
                <FileText className="w-4 h-4 text-blue-600" />Dokumen Diunggah
              </h2>
              <div className="space-y-2">
                {submission.documents.map((doc) => (
                  <div key={doc.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-colors">
                    <div className="flex items-center gap-3">
                      <FileText className="w-4 h-4 text-blue-600 shrink-0" />
                      <div>
                        <p className="text-sm font-semibold text-gray-800">{doc.document_type || doc.type}</p>
                        <p className="text-xs text-gray-400">{doc.original_name || doc.file_name}</p>
                      </div>
                    </div>
                    <a
                      href={`http://127.0.0.1:8000/storage/${doc.file_path}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-semibold"
                    >
                      <Download className="w-3.5 h-3.5" />Unduh
                    </a>
                  </div>
                ))}
              </div>
            </div>
          )}

          {/* Surat Izin */}
          {(submission?.current_status === 'Sedang Diproses' || submission?.current_status === 'Disetujui') && (
            <div className="bg-blue-50 border border-blue-200 rounded-2xl p-5 space-y-4">
              <h2 className="font-bold text-blue-900 flex items-center gap-2">
                <FileText className="w-5 h-5" /> File Surat Izin
              </h2>
              
              {/* Draf DOCX */}
              <div className="bg-white rounded-xl p-4 border border-blue-100 flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <FileText className="w-5 h-5 text-blue-700" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-sm text-gray-800">Draf Surat (DOCX)</h3>
                    <p className="text-xs text-gray-500">Draf otomatis untuk diedit / ditandatangani</p>
                  </div>
                </div>
                <button
                  onClick={async () => {
                    try {
                      const res = await api.get(`/admin/submissions/${submission.id}/download-letter`, { responseType: 'blob' });
                      const url = window.URL.createObjectURL(new Blob([res.data]));
                      const a = document.createElement('a');
                      a.href = url;
                      a.download = `Surat_Izin_${submission.registration_number}.docx`;
                      document.body.appendChild(a);
                      a.click();
                      document.body.removeChild(a);
                    } catch (err) {
                      alert('Gagal mengunduh surat izin. File mungkin belum tersedia.');
                    }
                  }}
                  className="flex items-center gap-2 bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold px-4 py-2 rounded-xl transition-colors text-sm"
                >
                  <Download className="w-4 h-4" /> Download
                </button>
              </div>

              {/* Final PDF */}
              {submission?.permit_file_path && (
                <div className="bg-white rounded-xl p-4 border border-green-100 flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                      <CheckCircle2 className="w-5 h-5 text-green-700" />
                    </div>
                    <div>
                      <h3 className="font-semibold text-sm text-gray-800">Surat Izin Final (PDF)</h3>
                      <p className="text-xs text-gray-500">Surat yang sudah ditandatangani dan siap diunduh pemohon</p>
                    </div>
                  </div>
                  <a
                    href={`http://127.0.0.1:8000/api/public/submissions/${submission.registration_number}/download-permit`}
                    className="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm text-sm"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    <Download className="w-4 h-4" /> Download Final
                  </a>
                </div>
              )}
            </div>
          )}

          {/* Riwayat Status */}
          {submission?.status_logs?.length > 0 && (
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
              <h2 className="font-bold text-blue-900 mb-4 flex items-center gap-2">
                <Clock className="w-4 h-4 text-blue-600" />Riwayat Status
              </h2>
              <div className="space-y-3">
                {submission.status_logs.map((log, i) => (
                  <div key={log.id || i} className="flex gap-4">
                    <div className="flex flex-col items-center">
                      <div className={`w-8 h-8 rounded-full flex items-center justify-center shrink-0 ${i === 0 ? 'bg-blue-800' : 'bg-gray-200'}`}>
                        <Clock className={`w-3.5 h-3.5 ${i === 0 ? 'text-white' : 'text-gray-400'}`} />
                      </div>
                      {i < submission.status_logs.length - 1 && <div className="w-0.5 h-full bg-gray-200 mt-1" />}
                    </div>
                    <div className="pb-4">
                      <span className={`inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold ${STATUS_COLORS[log.status] || 'bg-gray-100 text-gray-600 border-gray-200'}`}>
                        {log.status}
                      </span>
                      {log.notes && <p className="text-sm text-gray-600 mt-1 italic">"{log.notes}"</p>}
                      <p className="text-xs text-gray-400 mt-1">
                        {new Date(log.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })}
                        {log.admin && ` · oleh ${log.admin.name}`}
                      </p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </main>
    </div>
  )
}
