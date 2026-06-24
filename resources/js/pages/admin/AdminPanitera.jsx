import React, { useState, useEffect } from 'react'
import { motion } from 'framer-motion'
import { useNavigate } from 'react-router-dom'
import { Plus, Edit, Trash2, CheckCircle2, XCircle, Menu, Loader2 } from 'lucide-react'
import api from '../../lib/api'
import { Sidebar } from './AdminDashboard'

export default function AdminPanitera() {
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [paniteras, setPaniteras] = useState([])
  const [loading, setLoading] = useState(true)
  const [showModal, setShowModal] = useState(false)
  const [formData, setFormData] = useState({ id: null, nama_panitera: '', nip: '', jabatan: '', status_aktif: true })
  
  const admin = JSON.parse(localStorage.getItem('admin_user') || '{}')

  const fetchPanitera = async () => {
    try {
      const { data } = await api.get('/admin/panitera')
      setPaniteras(data)
    } catch (e) {
      console.error(e)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchPanitera()
  }, [])

  const handleLogout = async () => {
    try { await api.post('/admin/logout') } catch {}
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    navigate('/admin/login')
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    try {
      if (formData.id) {
        await api.put(`/admin/panitera/${formData.id}`, formData)
      } else {
        await api.post('/admin/panitera', formData)
      }
      setShowModal(false)
      fetchPanitera()
    } catch (e) {
      alert('Gagal menyimpan data')
    }
  }

  const handleDelete = async (id) => {
    if (!confirm('Yakin ingin menghapus data panitera ini?')) return
    try {
      await api.delete(`/admin/panitera/${id}`)
      fetchPanitera()
    } catch (e) {
      alert('Gagal menghapus data')
    }
  }

  return (
    <div className="min-h-screen bg-gray-50 flex">
      <Sidebar active="/admin/panitera" onLogout={handleLogout} open={sidebarOpen} setOpen={setSidebarOpen} />
      
      <main className="flex-1 lg:ml-64 min-w-0">
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
          <div className="flex items-center gap-3">
            <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-500 hover:text-gray-700">
              <Menu className="w-5 h-5" />
            </button>
            <h1 className="font-bold text-blue-900 text-lg">Data Panitera</h1>
          </div>
          <button
            onClick={() => { setFormData({ id: null, nama_panitera: '', nip: '', jabatan: '', status_aktif: true }); setShowModal(true) }}
            className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2"
          >
            <Plus className="w-4 h-4" /> Tambah Panitera
          </button>
        </header>

        <div className="p-6">
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table className="w-full text-sm text-left">
              <thead className="bg-gray-50 text-gray-600 border-b border-gray-100">
                <tr>
                  <th className="px-6 py-4 font-semibold">Nama Panitera</th>
                  <th className="px-6 py-4 font-semibold">NIP</th>
                  <th className="px-6 py-4 font-semibold">Jabatan</th>
                  <th className="px-6 py-4 font-semibold">Status</th>
                  <th className="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {loading ? (
                  <tr><td colSpan={5} className="text-center py-10"><Loader2 className="w-6 h-6 animate-spin mx-auto text-blue-600"/></td></tr>
                ) : paniteras.length === 0 ? (
                  <tr><td colSpan={5} className="text-center py-10 text-gray-500">Belum ada data panitera</td></tr>
                ) : paniteras.map(p => (
                  <tr key={p.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 font-medium text-gray-900">{p.nama_panitera}</td>
                    <td className="px-6 py-4 text-gray-600">{p.nip}</td>
                    <td className="px-6 py-4 text-gray-600">{p.jabatan}</td>
                    <td className="px-6 py-4">
                      {p.status_aktif ? (
                        <span className="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold"><CheckCircle2 className="w-3 h-3"/> Aktif</span>
                      ) : (
                        <span className="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold"><XCircle className="w-3 h-3"/> Nonaktif</span>
                      )}
                    </td>
                    <td className="px-6 py-4 text-right flex justify-end gap-2">
                      <button onClick={() => { setFormData(p); setShowModal(true) }} className="text-blue-600 hover:text-blue-800 p-1"><Edit className="w-4 h-4"/></button>
                      <button onClick={() => handleDelete(p.id)} className="text-red-600 hover:text-red-800 p-1"><Trash2 className="w-4 h-4"/></button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </main>

      {showModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <motion.div initial={{ scale: 0.95 }} animate={{ scale: 1 }} className="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
            <div className="p-6 border-b border-gray-100 flex justify-between items-center">
              <h3 className="font-bold text-gray-900">{formData.id ? 'Edit Panitera' : 'Tambah Panitera'}</h3>
              <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600"><XCircle className="w-5 h-5"/></button>
            </div>
            <form onSubmit={handleSubmit} className="p-6 space-y-4">
              <div>
                <label className="block text-sm font-semibold mb-1">Nama Lengkap</label>
                <input required type="text" value={formData.nama_panitera} onChange={e => setFormData({...formData, nama_panitera: e.target.value})} className="w-full border rounded-xl px-4 py-2" />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-1">NIP</label>
                <input required type="text" value={formData.nip} onChange={e => setFormData({...formData, nip: e.target.value})} className="w-full border rounded-xl px-4 py-2" />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-1">Jabatan</label>
                <input required type="text" value={formData.jabatan} onChange={e => setFormData({...formData, jabatan: e.target.value})} className="w-full border rounded-xl px-4 py-2" />
              </div>
              <div className="flex items-center gap-2">
                <input type="checkbox" id="status" checked={formData.status_aktif} onChange={e => setFormData({...formData, status_aktif: e.target.checked})} className="w-4 h-4 text-blue-600" />
                <label htmlFor="status" className="text-sm font-medium">Panitera Aktif</label>
              </div>
              <div className="pt-4 flex gap-3">
                <button type="button" onClick={() => setShowModal(false)} className="flex-1 px-4 py-2 border rounded-xl font-semibold text-gray-600">Batal</button>
                <button type="submit" className="flex-1 px-4 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700">Simpan</button>
              </div>
            </form>
          </motion.div>
        </div>
      )}
    </div>
  )
}
