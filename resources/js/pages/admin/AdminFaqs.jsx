import React, { useEffect, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { useNavigate, Link } from 'react-router-dom'
import {
  Scale, LayoutDashboard, ListChecks, HelpCircle, Megaphone,
  LogOut, Plus, Pencil, Trash2, X, AlertCircle, Loader2,
  ChevronDown, ChevronUp, Menu
} from 'lucide-react'
import api from '../../lib/api'

import { Sidebar } from './AdminDashboard'

const emptyForm = { question: '', answer: '' }

export default function AdminFaqs() {
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
  const [openFaq, setOpenFaq] = useState(null)

  const fetchItems = async () => {
    setLoading(true)
    try {
      const r = await api.get('/admin/faqs')
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
    setForm({ question: item.question, answer: item.answer })
    setError('')
    setModalOpen(true)
  }

  const handleSave = async (e) => {
    e.preventDefault()
    if (!form.question.trim() || !form.answer.trim()) { setError('Pertanyaan dan jawaban wajib diisi.'); return }
    setSaving(true); setError('')
    try {
      if (editItem) {
        await api.put(`/admin/faqs/${editItem.id}`, form)
      } else {
        await api.post('/admin/faqs', form)
      }
      setModalOpen(false)
      fetchItems()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menyimpan.')
    } finally { setSaving(false) }
  }

  const handleDelete = async (id) => {
    try { await api.delete(`/admin/faqs/${id}`) } catch {}
    setDeleteId(null)
    fetchItems()
  }

  return (
    <div className="min-h-screen bg-gray-50 flex">
      <Sidebar active="/admin/faqs" onLogout={handleLogout} open={sidebarOpen} setOpen={setSidebarOpen} />

      <main className="flex-1 lg:ml-64 min-w-0">
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
          <div className="flex items-center gap-3">
            <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-500"><Menu className="w-5 h-5" /></button>
            <div>
              <h1 className="font-bold text-blue-900 text-lg">Manajemen FAQ</h1>
              <p className="text-gray-400 text-xs">Kelola pertanyaan yang sering diajukan</p>
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
                <HelpCircle className="w-12 h-12 mx-auto mb-3 opacity-30" />
                <p>Belum ada FAQ. Klik "Tambah" untuk mulai.</p>
              </div>
            ) : (
              <div className="divide-y divide-gray-100">
                {items.map((item) => (
                  <div key={item.id} className="p-5 hover:bg-gray-50 transition-colors">
                    <div className="flex items-start justify-between gap-4">
                      <button
                        className="flex-1 text-left flex items-center justify-between"
                        onClick={() => setOpenFaq(openFaq === item.id ? null : item.id)}
                      >
                        <p className="font-semibold text-gray-900 text-sm">{item.question}</p>
                        {openFaq === item.id
                          ? <ChevronUp className="w-4 h-4 text-blue-600 shrink-0 ml-3" />
                          : <ChevronDown className="w-4 h-4 text-gray-400 shrink-0 ml-3" />
                        }
                      </button>
                      <div className="flex gap-1 shrink-0">
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
                    {openFaq === item.id && (
                      <motion.p
                        initial={{ opacity: 0, height: 0 }}
                        animate={{ opacity: 1, height: 'auto' }}
                        className="text-sm text-gray-600 mt-3 pt-3 border-t border-gray-100 leading-relaxed"
                      >
                        {item.answer}
                      </motion.p>
                    )}
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      </main>

      {/* Modal */}
      <AnimatePresence>
        {modalOpen && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
            <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
              className="absolute inset-0 bg-black/50" onClick={() => setModalOpen(false)} />
            <motion.div initial={{ opacity: 0, scale: 0.9 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.9 }}
              className="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md">
              <div className="flex items-center justify-between mb-5">
                <h2 className="font-bold text-blue-900">{editItem ? 'Edit FAQ' : 'Tambah FAQ'}</h2>
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
                  <label className="block text-sm font-semibold text-gray-700 mb-1.5">Pertanyaan <span className="text-red-500">*</span></label>
                  <input value={form.question} onChange={e => setForm(f => ({ ...f, question: e.target.value }))}
                    placeholder="Tulis pertanyaan..."
                    className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" />
                </div>
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-1.5">Jawaban <span className="text-red-500">*</span></label>
                  <textarea rows={4} value={form.answer} onChange={e => setForm(f => ({ ...f, answer: e.target.value }))}
                    placeholder="Tulis jawaban lengkap..."
                    className="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 resize-none" />
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
              <h3 className="font-bold text-gray-900 mb-1">Hapus FAQ?</h3>
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
