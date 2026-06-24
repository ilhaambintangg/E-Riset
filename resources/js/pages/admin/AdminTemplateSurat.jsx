import React, { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { Upload, FileText, Trash2, Menu, Loader2, CheckCircle2 } from 'lucide-react'
import api from '../../lib/api'
import { Sidebar } from './AdminDashboard'

export default function AdminTemplateSurat() {
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [templates, setTemplates] = useState([])
  const [loading, setLoading] = useState(true)
  const [uploading, setUploading] = useState(false)
  
  const fetchTemplates = async () => {
    try {
      const { data } = await api.get('/admin/templates')
      setTemplates(data)
    } catch (e) {
      console.error(e)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchTemplates()
  }, [])

  const handleLogout = async () => {
    try { await api.post('/admin/logout') } catch {}
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    navigate('/admin/login')
  }

  const handleUpload = async (e) => {
    const file = e.target.files[0]
    if (!file) return
    if (!file.name.endsWith('.docx')) {
      alert('Format file harus .docx')
      return
    }

    setUploading(true)
    const fd = new FormData()
    fd.append('template', file)

    try {
      await api.post('/admin/templates', fd, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      fetchTemplates()
    } catch (err) {
      alert('Gagal mengupload template')
    } finally {
      setUploading(false)
      e.target.value = null
    }
  }

  const handleDelete = async (id) => {
    if (!confirm('Hapus template ini?')) return
    try {
      await api.delete(`/admin/templates/${id}`)
      fetchTemplates()
    } catch (e) {
      alert('Gagal menghapus template')
    }
  }

  return (
    <div className="min-h-screen bg-gray-50 flex">
      <Sidebar active="/admin/templates" onLogout={handleLogout} open={sidebarOpen} setOpen={setSidebarOpen} />
      
      <main className="flex-1 lg:ml-64 min-w-0">
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
          <div className="flex items-center gap-3">
            <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-500 hover:text-gray-700">
              <Menu className="w-5 h-5" />
            </button>
            <h1 className="font-bold text-blue-900 text-lg">Template Surat</h1>
          </div>
        </header>

        <div className="p-6 max-w-4xl">
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 className="text-lg font-bold text-gray-900 mb-2">Upload Template Baru</h2>
            <p className="text-sm text-gray-500 mb-4">Gunakan format DOCX. Template yang baru diupload akan otomatis menjadi template aktif.</p>
            
            <div className="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-colors">
              <Upload className="w-8 h-8 mx-auto text-gray-400 mb-3" />
              <p className="text-sm font-medium text-gray-700 mb-1">Pilih File DOCX</p>
              <input type="file" accept=".docx" onChange={handleUpload} className="hidden" id="upload-template" disabled={uploading} />
              <label htmlFor="upload-template" className="inline-block px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-50 mt-2">
                {uploading ? 'Mengupload...' : 'Cari File'}
              </label>
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table className="w-full text-sm text-left">
              <thead className="bg-gray-50 text-gray-600 border-b border-gray-100">
                <tr>
                  <th className="px-6 py-4 font-semibold">Nama File</th>
                  <th className="px-6 py-4 font-semibold">Status</th>
                  <th className="px-6 py-4 font-semibold">Tanggal Upload</th>
                  <th className="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {loading ? (
                  <tr><td colSpan={4} className="text-center py-10"><Loader2 className="w-6 h-6 animate-spin mx-auto text-blue-600"/></td></tr>
                ) : templates.length === 0 ? (
                  <tr><td colSpan={4} className="text-center py-10 text-gray-500">Belum ada template</td></tr>
                ) : templates.map(t => (
                  <tr key={t.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 font-medium text-gray-900 flex items-center gap-2">
                      <FileText className="w-4 h-4 text-blue-600" />
                      {t.file_path.split('/').pop()}
                    </td>
                    <td className="px-6 py-4">
                      {t.is_active ? (
                        <span className="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold"><CheckCircle2 className="w-3 h-3"/> Aktif</span>
                      ) : (
                        <span className="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full text-xs font-semibold">Tidak Aktif</span>
                      )}
                    </td>
                    <td className="px-6 py-4 text-gray-600">{new Date(t.created_at).toLocaleDateString('id-ID')}</td>
                    <td className="px-6 py-4 text-right">
                      <button onClick={() => handleDelete(t.id)} className="text-red-600 hover:text-red-800 p-1"><Trash2 className="w-4 h-4"/></button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  )
}
