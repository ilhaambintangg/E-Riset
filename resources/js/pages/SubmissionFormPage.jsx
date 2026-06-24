import React, { useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { Link } from 'react-router-dom'
import {
  Scale, User, Mail, Phone, GraduationCap, FileText,
  Upload, CheckCircle2, AlertCircle, ArrowLeft, X, Building, Layout, BookOpen, MapPin, Target, Calendar, Hash
} from 'lucide-react'
import Navbar from '../components/Navbar'
import Footer from '../components/Footer'
import api from '../lib/api'

const inputCls =
  'w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-800 text-sm ' +
  'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent ' +
  'transition-all bg-gray-50 focus:bg-white placeholder:text-gray-400'

const labelCls = 'block text-sm font-semibold text-gray-700 mb-1.5'

function FileUploadField({ label, fieldKey, accept, files, setFiles, errors }) {
  const file = files[fieldKey]
  const error = errors[fieldKey]

  const handleChange = (e) => {
    const f = e.target.files[0]
    if (!f) return

    // Validasi ukuran 2MB
    if (f.size > 2 * 1024 * 1024) {
      alert(`Ukuran file "${f.name}" terlalu besar. Maksimal ukuran file adalah 2 MB.`)
      setFiles(prev => ({ ...prev, [fieldKey]: null }))
      e.target.value = null
      return
    }
    setFiles(prev => ({ ...prev, [fieldKey]: f }))
  }

  const clear = (e) => {
    e.preventDefault()
    setFiles(prev => ({ ...prev, [fieldKey]: null }))
  }

  return (
    <div>
      <label className={labelCls}>
        {label} <span className="text-red-500">*</span>
      </label>
      <label
        className={`flex items-center gap-3 cursor-pointer border-2 border-dashed rounded-xl px-4 py-3.5 transition-all
          ${file ? 'border-blue-400 bg-blue-50' : error ? 'border-red-300 bg-red-50' : 'border-gray-200 hover:border-blue-300 bg-gray-50'}`}
      >
        <div className={`w-9 h-9 rounded-lg flex items-center justify-center shrink-0 ${file ? 'bg-blue-100' : 'bg-gray-100'}`}>
          <FileText className={`w-4 h-4 ${file ? 'text-blue-600' : 'text-gray-400'}`} />
        </div>
        <div className="flex-1 min-w-0">
          {file ? (
            <p className="text-sm text-blue-800 font-medium truncate">{file.name}</p>
          ) : (
            <>
              <p className="text-sm text-gray-500 font-medium">Klik untuk pilih file PDF</p>
              <p className="text-xs text-gray-400 mt-0.5">Format: PDF · Maks. 2 MB</p>
            </>
          )}
        </div>
        {file && (
          <button onClick={clear} className="text-gray-400 hover:text-red-500 transition-colors shrink-0">
            <X className="w-4 h-4" />
          </button>
        )}
        <input type="file" accept={accept} onChange={handleChange} className="hidden" />
      </label>
      {error && (
        <p className="text-xs text-red-600 mt-1 flex items-center gap-1">
          <AlertCircle className="w-3 h-3" />{error}
        </p>
      )}
    </div>
  )
}

export default function SubmissionFormPage() {
  const [form, setForm] = useState({
    name: '',
    gender: '',
    email: '',
    phone: '',
    address: 'Dilengkapi nanti', // Auto-fill or could be added to form
    university: '',
    faculty: '',
    study_program: '',
    semester: '',
    nim: '',
    title: '',
    purpose: '',
    location: '',
    start_date: '',
    end_date: '',
  })
  const [files, setFiles] = useState({
    surat_pengantar_kampus: null,
    proposal_penelitian: null,
  })
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)
  const [success, setSuccess] = useState(false)
  const [registrationNumber, setRegistrationNumber] = useState('')

  const set = (field) => (e) => {
    setForm(prev => ({ ...prev, [field]: e.target.value }))
    if (errors[field]) setErrors(prev => ({ ...prev, [field]: '' }))
  }

  const validate = () => {
    const errs = {}
    if (!form.name.trim()) errs.name = 'Nama lengkap wajib diisi.'
    if (!form.gender) errs.gender = 'Jenis kelamin wajib dipilih.'
    if (!form.email.trim()) {
      errs.email = 'Email wajib diisi.'
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
      errs.email = 'Format email tidak valid.'
    }
    if (!form.phone.trim()) errs.phone = 'Nomor HP wajib diisi.'
    if (!form.university.trim()) errs.university = 'Universitas wajib diisi.'
    if (!form.faculty.trim()) errs.faculty = 'Fakultas wajib diisi.'
    if (!form.study_program.trim()) errs.study_program = 'Program Studi wajib diisi.'
    if (!form.nim.trim()) errs.nim = 'NIM / NPM wajib diisi.'
    if (!form.semester.trim()) errs.semester = 'Semester wajib diisi.'
    
    if (!form.title.trim()) errs.title = 'Judul Penelitian wajib diisi.'
    if (!form.purpose.trim()) errs.purpose = 'Tujuan Penelitian wajib diisi.'
    if (!form.location.trim()) errs.location = 'Lokasi Penelitian wajib diisi.'
    if (!form.start_date) errs.start_date = 'Tanggal Mulai wajib diisi.'
    if (!form.end_date) errs.end_date = 'Tanggal Selesai wajib diisi.'

    if (!files.surat_pengantar_kampus) errs.surat_pengantar_kampus = 'Surat pengantar wajib diunggah.'
    if (!files.proposal_penelitian) errs.proposal_penelitian = 'Proposal penelitian wajib diunggah.'
    return errs
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    const errs = validate()
    if (Object.keys(errs).length > 0) {
      setErrors(errs)
      window.scrollTo({ top: 0, behavior: 'smooth' })
      return
    }

    setLoading(true)
    setErrors({})
    try {
      const fd = new FormData()
      Object.entries(form).forEach(([k, v]) => fd.append(k, v))
      fd.append('surat_pengantar_kampus', files.surat_pengantar_kampus)
      fd.append('proposal_penelitian', files.proposal_penelitian)

      const response = await api.post('/public/submissions', fd, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      setRegistrationNumber(response.data.registration_number || '')
      setSuccess(true)
      window.scrollTo({ top: 0, behavior: 'smooth' })
    } catch (err) {
      const data = err.response?.data
      if (data?.errors) {
        const mapped = {}
        Object.entries(data.errors).forEach(([k, v]) => { mapped[k] = v[0] })
        setErrors(mapped)
      } else {
        setErrors({ _global: data?.message || 'Terjadi kesalahan. Silakan coba lagi.' })
      }
    } finally {
      setLoading(false)
    }
  }

  if (success) {
    return (
      <div className="min-h-screen bg-gray-50">
        <Navbar />
        <div className="flex items-center justify-center min-h-[calc(100vh-4rem)] py-20 px-4">
          <motion.div
            initial={{ scale: 0.85, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            transition={{ duration: 0.5, type: 'spring' }}
            className="bg-white rounded-2xl shadow-xl p-10 max-w-lg w-full text-center border border-green-100"
          >
            <motion.div
              initial={{ scale: 0 }}
              animate={{ scale: 1 }}
              transition={{ delay: 0.2, type: 'spring', stiffness: 200 }}
              className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6"
            >
              <CheckCircle2 className="w-10 h-10 text-green-600" />
            </motion.div>

            <h2 className="text-2xl font-bold text-gray-900 mb-3">Permohonan Terkirim!</h2>
            <div className="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-left">
              <p className="text-sm text-blue-800 leading-relaxed mb-3">
                <strong>Permohonan izin penelitian berhasil dikirim.</strong><br />
                Silakan menunggu proses verifikasi dari Pengadilan Tinggi Tanjungkarang.
              </p>
              
              {registrationNumber && (
                <div className="bg-white rounded-lg p-3 border border-blue-100 text-center">
                  <p className="text-xs text-blue-600 font-semibold mb-1 uppercase tracking-wider">Nomor Registrasi Anda</p>
                  <p className="text-xl font-mono font-bold text-blue-900 tracking-tight">{registrationNumber}</p>
                  <p className="text-xs text-gray-500 mt-2">Simpan nomor ini untuk mengecek status permohonan Anda.</p>
                </div>
              )}
            </div>

            <div className="flex flex-col gap-3">
              <Link
                to="/"
                className="flex items-center justify-center gap-2 bg-blue-800 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-colors shadow-sm"
              >
                Kembali ke Beranda
              </Link>
            </div>
          </motion.div>
        </div>
        <Footer />
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <Navbar />
      <div className="pt-24 pb-16 px-4">
        <div className="max-w-[800px] mx-auto">
          {/* Header */}
          <div className="text-center mb-8">
            <div className="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 px-4 py-1.5 rounded-full mb-4">
              <Scale className="w-4 h-4 text-blue-800" />
              <span className="text-blue-800 font-semibold text-sm">E-RISET</span>
            </div>
            <h1 className="text-2xl sm:text-3xl font-bold text-blue-900 mb-2">
              Formulir Pengajuan Penelitian
            </h1>
            <p className="text-gray-500 text-sm">
              Pengadilan Tinggi Tanjungkarang · Lengkapi semua data di bawah ini
            </p>
          </div>

          <AnimatePresence>
            {errors._global && (
              <motion.div
                initial={{ opacity: 0, y: -10 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0 }}
                className="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5 text-red-700 text-sm"
              >
                <AlertCircle className="w-4 h-4 shrink-0" />
                {errors._global}
              </motion.div>
            )}
          </AnimatePresence>

          <form onSubmit={handleSubmit} className="space-y-6" noValidate>

            {/* ── CARD: Data Pemohon ───────────────────────────── */}
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
              <h2 className="text-base font-bold text-blue-900 mb-5 flex items-center gap-2 border-b border-gray-100 pb-4">
                <div className="w-7 h-7 bg-blue-800 rounded-lg flex items-center justify-center">
                  <User className="w-3.5 h-3.5 text-white" />
                </div>
                Data Pemohon
              </h2>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                {/* Nama Lengkap */}
                <div className="md:col-span-2">
                  <label className={labelCls}>Nama Lengkap <span className="text-red-500">*</span></label>
                  <input
                    type="text"
                    value={form.name}
                    onChange={set('name')}
                    placeholder="Masukkan nama lengkap"
                    className={`${inputCls} ${errors.name ? 'border-red-400 bg-red-50' : ''}`}
                  />
                  {errors.name && <p className="text-xs text-red-600 mt-1">{errors.name}</p>}
                </div>

                {/* Jenis Kelamin */}
                <div className="md:col-span-2">
                  <label className={labelCls}>Jenis Kelamin <span className="text-red-500">*</span></label>
                  <div className="flex gap-4">
                    {['Laki-laki', 'Perempuan'].map((jk) => (
                      <label
                        key={jk}
                        className={`flex items-center gap-2.5 cursor-pointer px-4 py-3 rounded-xl border-2 flex-1 transition-all
                          ${form.gender === jk ? 'border-blue-500 bg-blue-50 text-blue-800' : 'border-gray-200 bg-gray-50 hover:border-blue-300'}`}
                      >
                        <input
                          type="radio"
                          name="gender"
                          value={jk}
                          checked={form.gender === jk}
                          onChange={set('gender')}
                          className="accent-blue-700 w-4 h-4"
                        />
                        <span className="text-sm font-medium">{jk}</span>
                      </label>
                    ))}
                  </div>
                  {errors.gender && <p className="text-xs text-red-600 mt-1">{errors.gender}</p>}
                </div>

                {/* Email */}
                <div>
                  <label className={labelCls}>Alamat Email <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <Mail className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                      type="email"
                      value={form.email}
                      onChange={set('email')}
                      placeholder="contoh@email.com"
                      className={`${inputCls} pl-10 ${errors.email ? 'border-red-400 bg-red-50' : ''}`}
                    />
                  </div>
                  {errors.email && <p className="text-xs text-red-600 mt-1">{errors.email}</p>}
                </div>

                {/* No HP */}
                <div>
                  <label className={labelCls}>Nomor HP / WhatsApp <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <Phone className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                      type="tel"
                      value={form.phone}
                      onChange={set('phone')}
                      placeholder="08xxxxxxxxxx"
                      className={`${inputCls} pl-10 ${errors.phone ? 'border-red-400 bg-red-50' : ''}`}
                    />
                  </div>
                  {errors.phone && <p className="text-xs text-red-600 mt-1">{errors.phone}</p>}
                </div>

                {/* Universitas */}
                <div>
                  <label className={labelCls}>Universitas <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <Building className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                      type="text"
                      value={form.university}
                      onChange={set('university')}
                      placeholder="Nama Universitas"
                      className={`${inputCls} pl-10 ${errors.university ? 'border-red-400 bg-red-50' : ''}`}
                    />
                  </div>
                  {errors.university && <p className="text-xs text-red-600 mt-1">{errors.university}</p>}
                </div>

                {/* Fakultas */}
                <div>
                  <label className={labelCls}>Fakultas <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <Layout className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                      type="text"
                      value={form.faculty}
                      onChange={set('faculty')}
                      placeholder="Nama Fakultas"
                      className={`${inputCls} pl-10 ${errors.faculty ? 'border-red-400 bg-red-50' : ''}`}
                    />
                  </div>
                  {errors.faculty && <p className="text-xs text-red-600 mt-1">{errors.faculty}</p>}
                </div>

                {/* Program Studi */}
                <div>
                  <label className={labelCls}>Program Studi <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <BookOpen className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                      type="text"
                      value={form.study_program}
                      onChange={set('study_program')}
                      placeholder="Nama Program Studi"
                      className={`${inputCls} pl-10 ${errors.study_program ? 'border-red-400 bg-red-50' : ''}`}
                    />
                  </div>
                  {errors.study_program && <p className="text-xs text-red-600 mt-1">{errors.study_program}</p>}
                </div>

                {/* NIM */}
                <div>
                  <label className={labelCls}>NIM / NPM <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <GraduationCap className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                      type="text"
                      value={form.nim}
                      onChange={set('nim')}
                      placeholder="Nomor Induk Mahasiswa"
                      className={`${inputCls} pl-10 ${errors.nim ? 'border-red-400 bg-red-50' : ''}`}
                    />
                  </div>
                  {errors.nim && <p className="text-xs text-red-600 mt-1">{errors.nim}</p>}
                </div>

                {/* Semester */}
                <div>
                  <label className={labelCls}>Semester <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <Hash className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                      type="text"
                      value={form.semester}
                      onChange={set('semester')}
                      placeholder="Contoh: 6"
                      className={`${inputCls} pl-10 ${errors.semester ? 'border-red-400 bg-red-50' : ''}`}
                    />
                  </div>
                  {errors.semester && <p className="text-xs text-red-600 mt-1">{errors.semester}</p>}
                </div>
              </div>
            </div>

            {/* ── CARD: Data Penelitian ───────────────────────────── */}
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
              <h2 className="text-base font-bold text-blue-900 mb-5 flex items-center gap-2 border-b border-gray-100 pb-4">
                <div className="w-7 h-7 bg-blue-800 rounded-lg flex items-center justify-center">
                  <FileText className="w-3.5 h-3.5 text-white" />
                </div>
                Data Penelitian
              </h2>

              <div className="space-y-4">
                <div>
                  <label className={labelCls}>Judul Penelitian <span className="text-red-500">*</span></label>
                  <input
                    type="text"
                    value={form.title}
                    onChange={set('title')}
                    placeholder="Masukkan judul penelitian"
                    className={`${inputCls} ${errors.title ? 'border-red-400 bg-red-50' : ''}`}
                  />
                  {errors.title && <p className="text-xs text-red-600 mt-1">{errors.title}</p>}
                </div>

                <div>
                  <label className={labelCls}>Tujuan Penelitian <span className="text-red-500">*</span></label>
                  <textarea
                    rows={3}
                    value={form.purpose}
                    onChange={set('purpose')}
                    placeholder="Jelaskan tujuan penelitian"
                    className={`${inputCls} resize-none ${errors.purpose ? 'border-red-400 bg-red-50' : ''}`}
                  />
                  {errors.purpose && <p className="text-xs text-red-600 mt-1">{errors.purpose}</p>}
                </div>

                <div>
                  <label className={labelCls}>Lokasi Penelitian <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <MapPin className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                      type="text"
                      value={form.location}
                      onChange={set('location')}
                      placeholder="Nama Pengadilan/Instansi tempat penelitian"
                      className={`${inputCls} pl-10 ${errors.location ? 'border-red-400 bg-red-50' : ''}`}
                    />
                  </div>
                  {errors.location && <p className="text-xs text-red-600 mt-1">{errors.location}</p>}
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div>
                    <label className={labelCls}>Tanggal Mulai Penelitian <span className="text-red-500">*</span></label>
                    <div className="relative">
                      <Calendar className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
                      <input
                        type="date"
                        value={form.start_date}
                        onChange={set('start_date')}
                        className={`${inputCls} pl-10 ${errors.start_date ? 'border-red-400 bg-red-50' : ''}`}
                      />
                    </div>
                    {errors.start_date && <p className="text-xs text-red-600 mt-1">{errors.start_date}</p>}
                  </div>
                  <div>
                    <label className={labelCls}>Tanggal Selesai Penelitian <span className="text-red-500">*</span></label>
                    <div className="relative">
                      <Calendar className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
                      <input
                        type="date"
                        value={form.end_date}
                        onChange={set('end_date')}
                        className={`${inputCls} pl-10 ${errors.end_date ? 'border-red-400 bg-red-50' : ''}`}
                      />
                    </div>
                    {errors.end_date && <p className="text-xs text-red-600 mt-1">{errors.end_date}</p>}
                  </div>
                </div>
              </div>
            </div>

            {/* ── CARD: Persyaratan Dokumen ────────────────────── */}
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
              <h2 className="text-base font-bold text-blue-900 mb-1 flex items-center gap-2">
                <div className="w-7 h-7 bg-blue-800 rounded-lg flex items-center justify-center">
                  <Upload className="w-3.5 h-3.5 text-white" />
                </div>
                Dokumen Persyaratan
              </h2>
              <p className="text-xs text-gray-500 mb-5 ml-9">
                Format PDF · Maks. 2 MB per file
              </p>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                <FileUploadField
                  label="Surat Pengantar dari Kampus"
                  fieldKey="surat_pengantar_kampus"
                  accept=".pdf"
                  files={files}
                  setFiles={setFiles}
                  errors={errors}
                />
                <FileUploadField
                  label="Proposal Penelitian"
                  fieldKey="proposal_penelitian"
                  accept=".pdf"
                  files={files}
                  setFiles={setFiles}
                  errors={errors}
                />
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full bg-[#1E40AF] hover:bg-blue-900 active:bg-blue-950 text-white font-bold py-4 rounded-2xl transition-all shadow-lg hover:shadow-blue-800/30 disabled:opacity-60 disabled:cursor-not-allowed text-base flex items-center justify-center gap-2"
            >
              {loading ? (
                <>
                  <svg className="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                  </svg>
                  Mengirim Permohonan...
                </>
              ) : (
                <>
                  <CheckCircle2 className="w-5 h-5" />
                  Kirim Pengajuan
                </>
              )}
            </button>
          </form>
        </div>
      </div>
      <Footer />
    </div>
  )
}
