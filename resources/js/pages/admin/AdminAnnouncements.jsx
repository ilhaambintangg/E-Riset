import React, { useEffect, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { useNavigate, Link } from 'react-router-dom'
import {
  Scale, LayoutDashboard, ListChecks, HelpCircle, Megaphone,
  LogOut, Plus, Pencil, Trash2, X, AlertCircle, Loader2,
  Calendar, Eye, Menu
} from 'lucide-react'
import api from '../../lib/api'

import { Sidebar } from './AdminDashboard'

const emptyForm = { title: '', content: '', is_active: true }

export default function AdminAnnouncements() {
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [items, setItems] = useState([])
  const [loading, setLoading] = useState(true)
  const [modalOpen, setModalOpen] = useState(false)
  const [editItem, setEditItem] = useState(null)
  const [form, setForm] = useState(emptyForm)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')
  const [deleteId, setDeleteId] = useState(null)
  const [previewItem, setPreviewItem] = useState(null)

  const fetchItems = async () => {
    setLoading(true)
    try {
      const r = await api.get('/admin/announcements')
      setItems(r.data)
    } catch {}
    setLoading(false)
  }

  useEffect(() => { fetchItems() }, [])

  const handleLogout = async () => {
    try { await api.post('/admin/logout') } catch {}
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    navigate('/admin/login')
  }

  const openCreate = () => {
    setEditItem(null)
    setForm(emptyForm)
    setError('')
    setModalOpen(true)
  }

  const openEdit = (item) => {
    setEditItem(item)
    setForm({ title: item.title, content: item.content, is_active: item.is_active ?? true })
    setError('')
    setModalOpen(true)
  }

  const handleSave = async (e) => {
    e.preventDefault()
    if (!form.title.trim() || !form.content.trim()) { setError('Judul dan konten wajib diisi.'); return }
    setSaving(true); setError('')
    try {
      if (editItem) {
        await api.put(`/admin/announcements/${editItem.id}`, form)
      } else {
        await api.post('/admin/announcements', form)
      }
      setModalOpen(false)
      fetchItems()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menyimpan.')
    } finally { setSaving(false) }
  }

  const handleDelete = async (id) => {
    try { await api.delete(`/admin/announcements/${id}`) } catch {}
    setDeleteId(null)
    fetchItems()
  }

  return (
    <div className="min-h-screen bg-gray-50 flex">
      <Sidebar active="/admin/announcements" onLogout={handleLogout} open={sidebarOpen} setOpen={setSidebarOpen} />

      <main className="flex-1 lg:ml-64 min-w-0">
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
          <div className="flex items-center gap-3">
            <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-500"><Menu className="w-5 h-5" /></button>
            <div>
              <h1 className="font-bold text-blue-900 text-lg">Manajemen Pengumuman</h1>
              <p className="text-gray-400 text-xs">Kelola pengumuman yang ditampilkan di landing page</p>
            </div>
          </div>
          <button
            onClick={openCreate}
            className="flex items-center gap-2 bg-blue-800 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm"
          >
            <Plus className="w-4 h-4" />Tambah
          </button>
        </header>

        <div className="p-6">
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100">
            {loading ? (
              <div className="flex items-center justify-center py-20 text-gray-400">
                <Loader2 className="w-6 h-6 animate-spin mr-2" />Memuat...
              </div>
            ) : items.length === 0 ? (
              <div className="text-center py-20 text-gray-400">
                <Megaphone className="w-12 h-12 mx-auto mb-3 opacity-30" />
                <p>Belum ada pengumuman. Klik "Tambah" untuk mulai.</p>
              </div>
            ) : (
              <div className="divide-y divide-gray-100">
                {items.map((item) => (
                  <div key={item.id} className="flex items-start justify-between p-5 hover:bg-gray-50 transition-colors gap-4">
                    <div className="flex-1 min-w-0">
                      <div className="flex items-center gap-2 mb-1">
                        <p className="font-semibold text-gray-900 truncate">{item.title}</p>
                        <span className={`text-xs px-2 py-0.5 rounded-full font-medium shrink-0 ${item.is_active !== false ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}`}>
                          {item.is_active !== false ? 'Aktif' : 'Non-aktif'}
                        </span>
                      </div>
                      <p className="text-sm text-gray-500 line-clamp-2">{item.content}</p>
                      {item.created_at && (
                        <div className="flex items-center gap-1 mt-1.5 text-xs text-gray-400">
                          <Calendar className="w-3 h-3" />
                          {new Date(item.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}
                        </div>
                      )}
                    </div>
                    <div className="flex gap-1 shrink-0">
                      <button onClick={() => setPreviewItem(item)}
                        className="p-2 rounded-xl text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                        <Eye className="w-4 h-4" />
                      </button>
                      <button onClick={() => openEdit(item)}
                        className="p-2 rounded-xl text-gray-400 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                        <Pencil className="w-4 h-4" />
                      </button>
                      <button onClick={() => setDeleteId(item.id)}
                        className="p-2 rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      </main>

      {/* Modal Create/Edit */}
      <AnimatePresence>
        {modalOpen && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
            <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
              className="absolute inset-0 bg-black/50" onClick={() => setModalOpen(false)} />
            <motion.div initial={{ opacity: 0, scale: 0.9 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.9 }}
              className="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-lg">
              <div className="flex items-center justify-between mb-5">
                <h2 className="font-bold text-blue-900">{editItem ? 'Edit Pengumuman' : 'Tambah Pengumuman'}</h2>
                <button onClick={() => setModalOpen(false)} className="text-gray-400 hover:text-gray-600">
                  <X className="w-5 h-5" />
                </button>
              </div>

              {error && (
                <div className="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-2.5 mb-4 text-red-700 text-sm">
                  <AlertCircle className="w-4 h-4 shrink-0" />{error}
                </div>
              )}

              <form onSubmit={handleSave} className="space-y-4">
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-1.5">Judul <span className="text-red-500">*</span></label>
                  <input value={form.title} onChange={e => setForm(f => ({ ...f, title: e.target.value }))}
                    placeholder="Judul pengumuman..."
                    className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" />
                </div>
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-1.5">Konten <span className="text-red-500">*</span></label>
                  <textarea rows={5} value={form.content} onChange={e => setForm(f => ({ ...f, content: e.target.value }))}
                    placeholder="Isi pengumuman..."
                    className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 resize-none" />
                </div>
                <div className="flex items-center gap-3">
                  <input type="checkbox" id="is_active" checked={form.is_active}
                    onChange={e => setForm(f => ({ ...f, is_active: e.target.checked }))}
                    className="w-4 h-4 accent-blue-700" />
                  <label htmlFor="is_active" className="text-sm font-medium text-gray-700">Tampilkan di landing page (aktif)</label>
                </div>
                <div className="flex gap-3 pt-2">
                  <button type="submit" disabled={saving}
                    className="flex-1 bg-blue-800 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl transition-colors disabled:opacity-60">
                    {saving ? 'Menyimpan...' : (editItem ? 'Simpan Perubahan' : 'Tambah')}
                  </button>
                  <button type="button" onClick={() => setModalOpen(false)}
                    className="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                  </button>
                </div>
              </form>
            </motion.div>
          </div>
        )}
      </AnimatePresence>

      {/* Preview Modal */}
      <AnimatePresence>
        {previewItem && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
            <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
              className="absolute inset-0 bg-black/50" onClick={() => setPreviewItem(null)} />
            <motion.div initial={{ opacity: 0, scale: 0.9 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.9 }}
              className="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md">
              <div className="flex items-center justify-between mb-4">
                <h2 className="font-bold text-blue-900 flex items-center gap-2">
                  <Megaphone className="w-4 h-4 text-blue-600" />Preview Pengumuman
                </h2>
                <button onClick={() => setPreviewItem(null)} className="text-gray-400 hover:text-gray-600">
                  <X className="w-5 h-5" />
                </button>
              </div>
              <div className="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <h3 className="font-bold text-blue-900 mb-2">{previewItem.title}</h3>
                <p className="text-sm text-blue-800 leading-relaxed whitespace-pre-wrap">{previewItem.content}</p>
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>

      {/* Delete Confirm */}
      <AnimatePresence>
        {deleteId && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
            <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
              className="absolute inset-0 bg-black/50" onClick={() => setDeleteId(null)} />
            <motion.div initial={{ opacity: 0, scale: 0.9 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.9 }}
              className="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm text-center">
              <div className="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Trash2 className="w-7 h-7 text-red-600" />
              </div>
              <h3 className="font-bold text-gray-900 mb-1">Hapus Pengumuman?</h3>
              <p className="text-sm text-gray-500 mb-5">Tindakan ini tidak dapat dibatalkan.</p>
              <div className="flex gap-3">
                <button onClick={() => handleDelete(deleteId)}
                  className="flex-1 bg-red-600 hover:bg-red-500 text-white font-semibold py-2.5 rounded-xl transition-colors">
                  Hapus
                </button>
                <button onClick={() => setDeleteId(null)}
                  className="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                  Batal
                </button>
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  )
}
