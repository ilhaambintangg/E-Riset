import React, { useEffect, useState, useCallback } from 'react'
import { motion } from 'framer-motion'
import { useNavigate, Link } from 'react-router-dom'
import {
  Scale, LayoutDashboard, FileText, ListChecks, HelpCircle,
  Megaphone, LogOut, Search, ChevronLeft, ChevronRight,
  Clock, CheckCircle2, XCircle, Loader2, Users, Menu, X, Settings, BarChart
} from 'lucide-react'
import api from '../../lib/api'

const STATUS_COLORS = {
  'Menunggu Verifikasi': 'bg-yellow-100 text-yellow-800',
  'Sedang Diproses': 'bg-blue-100 text-blue-800',
  'Disetujui': 'bg-green-100 text-green-800',
  'Ditolak': 'bg-red-100 text-red-800',
}

const STATUS_ICONS = {
  'Menunggu Verifikasi': Clock,
  'Sedang Diproses': Loader2,
  'Disetujui': CheckCircle2,
  'Ditolak': XCircle,
}

export function Sidebar({ active, onLogout, open, setOpen }) {
  const nav = [
    { label: 'Dashboard', icon: LayoutDashboard, to: '/admin/dashboard' },
    { label: 'Data Panitera', icon: Users, to: '/admin/panitera' },
    { label: 'Template Surat', icon: FileText, to: '/admin/templates' },
    { label: 'Persyaratan', icon: ListChecks, to: '/admin/requirements' },
    { label: 'FAQ', icon: HelpCircle, to: '/admin/faqs' },
    { label: 'Pengumuman', icon: Megaphone, to: '/admin/announcements' },
    { label: 'Laporan', icon: BarChart, to: '/admin/reports' },
    { label: 'Pengaturan', icon: Settings, to: '/admin/settings' },
  ]

  return (
    <>
      {/* Mobile overlay */}
      {open && (
        <div className="fixed inset-0 bg-black/50 z-20 lg:hidden" onClick={() => setOpen(false)} />
      )}
      <aside className={`fixed top-0 left-0 h-full w-64 bg-blue-900 text-white flex flex-col z-30 transform transition-transform duration-300 ${open ? 'translate-x-0' : '-translate-x-full'} lg:translate-x-0`}>
        <div className="p-6 border-b border-blue-800">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-amber-400 rounded-xl flex items-center justify-center shadow-lg shrink-0">
              <Scale className="w-5 h-5 text-blue-900" />
            </div>
            <div>
              <p className="font-bold text-sm">E-RISET Admin</p>
              <p className="text-blue-300 text-xs">PT Tanjungkarang</p>
            </div>
          </div>
        </div>

        <nav className="flex-1 p-4 space-y-1">
          {nav.map((item) => {
            const isActive = active === item.to
            return (
              <Link
                key={item.to}
                to={item.to}
                onClick={() => setOpen(false)}
                className={`flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all ${
                  isActive
                    ? 'bg-white/20 text-white shadow-sm'
                    : 'text-blue-200 hover:bg-white/10 hover:text-white'
                }`}
              >
                <item.icon className="w-4 h-4" />
                {item.label}
              </Link>
            )
          })}
        </nav>

        <div className="p-4 border-t border-blue-800">
          <button
            onClick={onLogout}
            className="flex items-center gap-2 w-full px-4 py-2.5 rounded-xl text-sm font-medium text-red-300 hover:bg-red-500/20 hover:text-red-200 transition-all"
          >
            <LogOut className="w-4 h-4" />
            Keluar
          </button>
        </div>
      </aside>
    </>
  )
}

export default function AdminDashboard() {
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [stats, setStats] = useState(null)
  const [submissions, setSubmissions] = useState([])
  const [meta, setMeta] = useState({})
  const [search, setSearch] = useState('')
  const [statusFilter, setStatusFilter] = useState('')
  const [page, setPage] = useState(1)
  const [loading, setLoading] = useState(true)
  const admin = JSON.parse(localStorage.getItem('admin_user') || '{}')

  const fetchData = useCallback(async () => {
    setLoading(true)
    try {
      const [statsResp, subResp] = await Promise.all([
        api.get('/admin/dashboard'),
        api.get('/admin/submissions', { params: { search, status: statusFilter, page } })
      ])
      setStats(statsResp.data)
      setSubmissions(subResp.data.data || [])
      setMeta(subResp.data)
    } catch {
      // handled by interceptor
    } finally {
      setLoading(false)
    }
  }, [search, statusFilter, page])

  useEffect(() => { fetchData() }, [fetchData])

  const handleLogout = async () => {
    try { await api.post('/admin/logout') } catch {}
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    navigate('/admin/login')
  }

  const statCards = [
    { label: 'Total Permohonan', value: stats?.total ?? '-', color: 'blue', icon: Users },
    { label: 'Menunggu', value: stats?.pending ?? '-', color: 'yellow', icon: Clock },
    { label: 'Diproses', value: stats?.processing ?? '-', color: 'indigo', icon: Loader2 },
    { label: 'Disetujui', value: stats?.approved ?? '-', color: 'green', icon: CheckCircle2 },
    { label: 'Ditolak', value: stats?.rejected ?? '-', color: 'red', icon: XCircle },
  ]

  const colorMap = {
    blue: 'bg-blue-100 text-blue-700',
    yellow: 'bg-yellow-100 text-yellow-700',
    indigo: 'bg-indigo-100 text-indigo-700',
    green: 'bg-green-100 text-green-700',
    red: 'bg-red-100 text-red-700',
  }

  return (
    <div className="min-h-screen bg-gray-50 flex">
      <Sidebar active="/admin/dashboard" onLogout={handleLogout} open={sidebarOpen} setOpen={setSidebarOpen} />

      <main className="flex-1 lg:ml-64 min-w-0">
        {/* Topbar */}
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
          <div className="flex items-center gap-3">
            <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-500 hover:text-gray-700">
              <Menu className="w-5 h-5" />
            </button>
            <div>
              <h1 className="font-bold text-blue-900 text-lg">Dashboard</h1>
              <p className="text-gray-400 text-xs">Selamat datang, {admin?.name || 'Admin'}</p>
            </div>
          </div>
          <div className="w-8 h-8 bg-blue-900 rounded-full flex items-center justify-center text-white text-sm font-bold">
            {(admin?.name || 'A')[0]}
          </div>
        </header>

        <div className="p-6">
          {/* Stat Cards */}
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            {statCards.map((card) => (
              <motion.div
                key={card.label}
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                className="bg-white rounded-2xl p-5 shadow-sm border border-gray-100"
              >
                <div className={`w-10 h-10 ${colorMap[card.color]} rounded-xl flex items-center justify-center mb-3`}>
                  <card.icon className="w-5 h-5" />
                </div>
                <p className="text-2xl font-bold text-gray-900">{card.value}</p>
                <p className="text-xs text-gray-500 mt-0.5">{card.label}</p>
              </motion.div>
            ))}
          </div>

          {/* Submissions Table */}
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div className="p-5 border-b border-gray-100 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
              <h2 className="font-bold text-blue-900 text-base flex items-center gap-2">
                <FileText className="w-5 h-5 text-blue-600" />
                Daftar Permohonan
              </h2>
              <div className="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <div className="relative">
                  <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                  <input
                    placeholder="Cari nama, nomor reg..."
                    value={search}
                    onChange={(e) => { setSearch(e.target.value); setPage(1) }}
                    className="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-56"
                  />
                </div>
                <select
                  value={statusFilter}
                  onChange={(e) => { setStatusFilter(e.target.value); setPage(1) }}
                  className="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Semua Status</option>
                  <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                  <option value="Sedang Diproses">Sedang Diproses</option>
                  <option value="Disetujui">Disetujui</option>
                  <option value="Ditolak">Ditolak</option>
                </select>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th className="text-left px-5 py-3 font-semibold">No. Registrasi</th>
                    <th className="text-left px-5 py-3 font-semibold">Nama / Institusi</th>
                    <th className="text-left px-5 py-3 font-semibold hidden md:table-cell">Judul Penelitian</th>
                    <th className="text-left px-5 py-3 font-semibold">Status</th>
                    <th className="text-left px-5 py-3 font-semibold hidden sm:table-cell">Tanggal</th>
                    <th className="text-left px-5 py-3 font-semibold">Aksi</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-100">
                  {loading ? (
                    <tr>
                      <td colSpan={6} className="text-center py-12 text-gray-400">
                        <Loader2 className="w-6 h-6 animate-spin mx-auto mb-2" />
                        Memuat data...
                      </td>
                    </tr>
                  ) : submissions.length === 0 ? (
                    <tr>
                      <td colSpan={6} className="text-center py-12 text-gray-400">
                        Tidak ada permohonan ditemukan.
                      </td>
                    </tr>
                  ) : (
                    submissions.map((sub) => {
                      const StatusIcon = STATUS_ICONS[sub.current_status] || Clock
                      return (
                        <tr key={sub.id} className="hover:bg-blue-50/50 transition-colors">
                          <td className="px-5 py-4 font-mono text-xs text-blue-700 font-medium">
                            {sub.registration_number}
                          </td>
                          <td className="px-5 py-4">
                            <p className="font-semibold text-gray-900">{sub.name || sub.full_name}</p>
                            <p className="text-gray-400 text-xs">{sub.institution || sub.university}</p>
                          </td>
                          <td className="px-5 py-4 hidden md:table-cell">
                            <p className="text-gray-700 line-clamp-1 max-w-xs">{sub.title || sub.research_title}</p>
                          </td>
                          <td className="px-5 py-4">
                            <span className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold ${STATUS_COLORS[sub.current_status] || 'bg-gray-100 text-gray-600'}`}>
                              <StatusIcon className={`w-3 h-3 ${sub.current_status === 'Sedang Diproses' ? 'animate-spin' : ''}`} />
                              {sub.current_status}
                            </span>
                          </td>
                          <td className="px-5 py-4 text-gray-400 text-xs hidden sm:table-cell">
                            {new Date(sub.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}
                          </td>
                          <td className="px-5 py-4">
                            <Link
                              to={`/admin/submissions/${sub.id}`}
                              className="inline-flex items-center gap-1 bg-blue-800 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                            >
                              Detail
                            </Link>
                          </td>
                        </tr>
                      )
                    })
                  )}
                </tbody>
              </table>
            </div>

            {/* Pagination */}
            {meta.last_page > 1 && (
              <div className="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                <p className="text-xs text-gray-500">
                  Halaman {meta.current_page} dari {meta.last_page} ({meta.total} data)
                </p>
                <div className="flex gap-2">
                  <button
                    onClick={() => setPage(p => Math.max(1, p - 1))}
                    disabled={page === 1}
                    className="p-2 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 transition-colors"
                  >
                    <ChevronLeft className="w-4 h-4 text-gray-600" />
                  </button>
                  <button
                    onClick={() => setPage(p => Math.min(meta.last_page, p + 1))}
                    disabled={page === meta.last_page}
                    className="p-2 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 transition-colors"
                  >
                    <ChevronRight className="w-4 h-4 text-gray-600" />
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </main>
    </div>
  )
}
