import React, { useState } from 'react'
import { Sidebar } from './AdminDashboard'
import { BarChart, Download, Menu, ArrowLeft } from 'lucide-react'
import { Link, useNavigate } from 'react-router-dom'
import api from '../../lib/api'

export default function AdminReports() {
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  
  const currentYear = new Date().getFullYear()
  const currentMonth = new Date().getMonth() + 1
  
  const [monthlyYear, setMonthlyYear] = useState(currentYear)
  const [monthlyMonth, setMonthlyMonth] = useState(currentMonth)
  
  const [annualYear, setAnnualYear] = useState(currentYear)

  const handleLogout = async () => {
    try { await api.post('/admin/logout') } catch {}
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    navigate('/admin/login')
  }

  const printPdf = (url) => {
    // A simple approach is to open the API json or an HTML page in a new window, 
    // but since we only have the data endpoint, let's just make the user print this screen or provide a custom print view.
    // For now, we will open the CSV download. The user approved HTML print, but we don't have a printable HTML endpoint yet.
    // Let's create an iframe and print the current page, or simply prompt to download CSV for both Excel/PDF since it's universally accessible.
    // Actually, I can use window.print() and hide sidebar via CSS print rules.
    window.print()
  }

  const downloadCsv = (type) => {
    const url = type === 'monthly' 
      ? `http://127.0.0.1:8000/api/admin/reports/monthly?month=${monthlyMonth}&year=${monthlyYear}&export=csv`
      : `http://127.0.0.1:8000/api/admin/reports/annual?year=${annualYear}&export=csv`
    window.open(url, '_blank')
  }

  return (
    <div className="min-h-screen bg-gray-50 flex print:bg-white print:block">
      <div className="print:hidden">
        <Sidebar active="/admin/reports" onLogout={handleLogout} open={sidebarOpen} setOpen={setSidebarOpen} />
      </div>

      <main className="flex-1 lg:ml-64 min-w-0 print:ml-0 print:m-0 print:p-0">
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-3 sticky top-0 z-10 print:hidden">
          <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-500"><Menu className="w-5 h-5" /></button>
          <Link to="/admin/dashboard" className="text-gray-400 hover:text-blue-800 transition-colors">
            <ArrowLeft className="w-5 h-5" />
          </Link>
          <div>
            <h1 className="font-bold text-blue-900 text-lg">Laporan</h1>
            <p className="text-gray-400 text-xs">Laporan bulanan dan tahunan permohonan</p>
          </div>
        </header>

        <div className="p-6 max-w-5xl mx-auto space-y-8">
          
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 print:shadow-none print:border-none">
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
              <h2 className="font-bold text-blue-900 text-xl flex items-center gap-2">
                <BarChart className="w-6 h-6 text-blue-600 print:hidden" />
                Laporan Bulanan
              </h2>
              <div className="flex items-center gap-3 print:hidden">
                <select value={monthlyMonth} onChange={e => setMonthlyMonth(e.target.value)} className="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                  {Array.from({length: 12}, (_, i) => i + 1).map(m => (
                    <option key={m} value={m}>Bulan {m}</option>
                  ))}
                </select>
                <select value={monthlyYear} onChange={e => setMonthlyYear(e.target.value)} className="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                  {[currentYear - 1, currentYear, currentYear + 1].map(y => (
                    <option key={y} value={y}>{y}</option>
                  ))}
                </select>
                <button onClick={() => downloadCsv('monthly')} className="bg-green-600 hover:bg-green-500 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                  <Download className="w-4 h-4" /> Excel (CSV)
                </button>
                <button onClick={() => printPdf()} className="bg-blue-800 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                  <Download className="w-4 h-4" /> Print PDF
                </button>
              </div>
            </div>
            
            <ReportStats type="monthly" month={monthlyMonth} year={monthlyYear} />
          </div>

          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 print:shadow-none print:border-none print:mt-10">
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
              <h2 className="font-bold text-blue-900 text-xl flex items-center gap-2">
                <BarChart className="w-6 h-6 text-blue-600 print:hidden" />
                Laporan Tahunan
              </h2>
              <div className="flex items-center gap-3 print:hidden">
                <select value={annualYear} onChange={e => setAnnualYear(e.target.value)} className="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                  {[currentYear - 2, currentYear - 1, currentYear, currentYear + 1].map(y => (
                    <option key={y} value={y}>{y}</option>
                  ))}
                </select>
                <button onClick={() => downloadCsv('annual')} className="bg-green-600 hover:bg-green-500 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                  <Download className="w-4 h-4" /> Excel (CSV)
                </button>
                <button onClick={() => printPdf()} className="bg-blue-800 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                  <Download className="w-4 h-4" /> Print PDF
                </button>
              </div>
            </div>
            
            <ReportStats type="annual" year={annualYear} />
          </div>

        </div>
      </main>
    </div>
  )
}

function ReportStats({ type, month, year }) {
  const [data, setData] = React.useState(null)
  
  React.useEffect(() => {
    const url = type === 'monthly' ? `/admin/reports/monthly?month=${month}&year=${year}` : `/admin/reports/annual?year=${year}`
    api.get(url).then(res => setData(res.data)).catch(console.error)
  }, [type, month, year])

  if (!data) return <p className="text-gray-400 text-sm">Memuat data...</p>

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <StatBox label="Total Permohonan" value={data.total} bg="bg-blue-50" color="text-blue-800" />
        <StatBox label="Disetujui" value={data.approved} bg="bg-green-50" color="text-green-800" />
        <StatBox label="Ditolak" value={data.rejected} bg="bg-red-50" color="text-red-800" />
        <StatBox label="Diproses" value={data.processing} bg="bg-yellow-50" color="text-yellow-800" />
      </div>

      {type === 'annual' && data.breakdown && (
        <div className="mt-8 border border-gray-100 rounded-xl overflow-hidden print:border-black">
          <table className="w-full text-sm text-left">
            <thead className="bg-gray-50 text-gray-600 print:bg-transparent">
              <tr>
                <th className="px-4 py-3 font-semibold">Bulan</th>
                <th className="px-4 py-3 font-semibold text-center">Total</th>
                <th className="px-4 py-3 font-semibold text-center">Disetujui</th>
                <th className="px-4 py-3 font-semibold text-center">Ditolak</th>
                <th className="px-4 py-3 font-semibold text-center">Diproses</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100 print:divide-black">
              {data.breakdown.map((row) => (
                <tr key={row.month}>
                  <td className="px-4 py-3 font-medium">Bulan {row.month}</td>
                  <td className="px-4 py-3 text-center">{row.total}</td>
                  <td className="px-4 py-3 text-center text-green-700">{row.approved}</td>
                  <td className="px-4 py-3 text-center text-red-700">{row.rejected}</td>
                  <td className="px-4 py-3 text-center text-yellow-700">{row.processing}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  )
}

function StatBox({ label, value, bg, color }) {
  return (
    <div className={`p-4 rounded-xl border border-gray-100 ${bg} print:bg-transparent print:border-black print:border`}>
      <p className="text-xs text-gray-500 print:text-black mb-1">{label}</p>
      <p className={`text-3xl font-bold ${color} print:text-black`}>{value}</p>
    </div>
  )
}
