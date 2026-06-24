import React, { useEffect, useState } from 'react'
import { Sidebar } from './AdminDashboard'
import { Settings, Save, AlertCircle, CheckCircle2, Loader2, ArrowLeft, Menu } from 'lucide-react'
import { Link, useNavigate } from 'react-router-dom'
import api from '../../lib/api'

export default function AdminSettings() {
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [setting, setSetting] = useState(null)
  const [loading, setLoading] = useState(true)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')

  useEffect(() => {
    api.get('/public/settings')
      .then(res => setSetting(res.data))
      .catch(() => setError('Gagal memuat pengaturan.'))
      .finally(() => setLoading(false))
  }, [])

  const handleLogout = async () => {
    try { await api.post('/admin/logout') } catch {}
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    navigate('/admin/login')
  }

  const handleSave = async (e) => {
    e.preventDefault()
    setSaving(true); setError(''); setSuccess('')
    try {
      await api.put('/admin/settings', setting)
      setSuccess('Pengaturan berhasil diperbarui.')
    } catch (err) {
      setError('Gagal menyimpan pengaturan.')
    } finally {
      setSaving(false)
    }
  }

  if (loading) return (
    <div className="min-h-screen flex items-center justify-center">
      <Loader2 className="w-8 h-8 animate-spin text-blue-800" />
    </div>
  )

  return (
    <div className="min-h-screen bg-gray-50 flex">
      <Sidebar active="/admin/settings" onLogout={handleLogout} open={sidebarOpen} setOpen={setSidebarOpen} />

      <main className="flex-1 lg:ml-64 min-w-0">
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-3 sticky top-0 z-10">
          <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-500"><Menu className="w-5 h-5" /></button>
          <Link to="/admin/dashboard" className="text-gray-400 hover:text-blue-800 transition-colors">
            <ArrowLeft className="w-5 h-5" />
          </Link>
          <div>
            <h1 className="font-bold text-blue-900 text-lg">Pengaturan Website</h1>
            <p className="text-gray-400 text-xs">Ubah informasi kontak dan footer website</p>
          </div>
        </header>

        <div className="p-6 max-w-4xl mx-auto space-y-6">
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

          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form onSubmit={handleSave} className="space-y-4">
              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-1.5">Nama Instansi</label>
                <input
                  value={setting?.nama_instansi || ''}
                  onChange={e => setSetting({ ...setting, nama_instansi: e.target.value })}
                  className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50"
                  required
                />
              </div>
              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                <textarea
                  rows={3}
                  value={setting?.alamat || ''}
                  onChange={e => setSetting({ ...setting, alamat: e.target.value })}
                  className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50 resize-none"
                  required
                />
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-1.5">Telepon</label>
                  <input
                    value={setting?.telepon || ''}
                    onChange={e => setSetting({ ...setting, telepon: e.target.value })}
                    className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50"
                  />
                </div>
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                  <input
                    type="email"
                    value={setting?.email || ''}
                    onChange={e => setSetting({ ...setting, email: e.target.value })}
                    className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50"
                  />
                </div>
              </div>
              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-1.5">Website Utama</label>
                <input
                  type="url"
                  value={setting?.website || ''}
                  onChange={e => setSetting({ ...setting, website: e.target.value })}
                  className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-1.5">Google Maps Embed HTML (Iframe)</label>
                <textarea
                  rows={4}
                  value={setting?.google_maps || ''}
                  onChange={e => setSetting({ ...setting, google_maps: e.target.value })}
                  className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-blue-500 bg-gray-50 resize-none"
                />
              </div>
              <div className="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" disabled={saving} className="flex items-center gap-2 bg-blue-800 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-sm disabled:opacity-60">
                  {saving ? <Loader2 className="w-4 h-4 animate-spin" /> : <Save className="w-4 h-4" />}
                  {saving ? 'Menyimpan...' : 'Simpan Pengaturan'}
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  )
}
