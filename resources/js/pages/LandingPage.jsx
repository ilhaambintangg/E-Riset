import React, { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { motion } from 'framer-motion'
import {
  ClipboardList, Upload, ShieldCheck, CheckCircle2, FileCheck,
  FileText, BookOpen, CreditCard, GraduationCap, Building2, Paperclip,
  ChevronDown, ChevronUp, MapPin, Phone, Mail, Clock, ArrowRight,
  Zap, Users, Globe, Scale
} from 'lucide-react'
import Navbar from '../components/Navbar'
import Footer from '../components/Footer'
import api from '../lib/api'

const fadeUp = {
  hidden: { opacity: 0, y: 30 },
  show: { opacity: 1, y: 0, transition: { duration: 0.6 } }
}

const stagger = {
  hidden: {},
  show: { transition: { staggerChildren: 0.12 } }
}

export default function LandingPage() {
  const [faqs, setFaqs] = useState([])
  const [requirements, setRequirements] = useState([])
  const [openFaq, setOpenFaq] = useState(null)
  const [setting, setSetting] = useState(null)

  useEffect(() => {
    api.get('/public/faqs').then(r => setFaqs(r.data)).catch(() => {})
    api.get('/public/requirements').then(r => setRequirements(r.data)).catch(() => {})
    api.get('/public/settings').then(r => setSetting(r.data)).catch(() => {})
  }, [])

  const steps = [
    { icon: ClipboardList, label: 'Isi Formulir', desc: 'Lengkapi data pemohon dan penelitian' },
    { icon: Upload, label: 'Upload Dokumen', desc: 'Unggah berkas persyaratan yang dibutuhkan' },
    { icon: ShieldCheck, label: 'Verifikasi Berkas', desc: 'Admin memverifikasi kelengkapan dokumen' },
    { icon: FileCheck, label: 'Proses Persetujuan', desc: 'Permohonan diproses oleh pimpinan' },
    { icon: CheckCircle2, label: 'Surat Izin Terbit', desc: 'Unduh surat izin penelitian digital' },
  ]

  const docIcons = {
    'Surat Permohonan': FileText,
    'Proposal Penelitian': BookOpen,
    'Kartu Tanda Penduduk (KTP)': CreditCard,
    'Kartu Tanda Mahasiswa (KTM)': GraduationCap,
    'Surat Pengantar Kampus / Instansi': Building2,
    'Dokumen Pendukung Lainnya': Paperclip,
  }

  return (
    <div className="min-h-screen bg-white">
      <Navbar />

      {/* ── HERO ── */}
      <section className="relative min-h-screen flex items-center bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 overflow-hidden pt-16">
        {/* Background decoration */}
        <div className="absolute inset-0 overflow-hidden">
          <div className="absolute -top-40 -right-40 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl" />
          <div className="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl" />
          <div className="absolute top-1/3 right-1/4 w-64 h-64 bg-white/5 rounded-full blur-2xl" />
        </div>

        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <motion.div initial="hidden" animate="show" variants={stagger}>
              <motion.div variants={fadeUp} className="inline-flex items-center gap-2 bg-amber-400/20 border border-amber-400/30 rounded-full px-4 py-1.5 mb-6">
                <Scale className="w-4 h-4 text-amber-400" />
                <span className="text-amber-300 text-sm font-medium">Pengadilan Tinggi Tanjungkarang</span>
              </motion.div>

              <motion.h1 variants={fadeUp} className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4">
                Sistem Izin Penelitian{' '}
                <span className="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-500">
                  Elektronik
                </span>
              </motion.h1>

              <motion.p variants={fadeUp} className="text-blue-100 text-lg leading-relaxed mb-8 max-w-xl">
                Ajukan permohonan izin penelitian secara <strong className="text-white">cepat, mudah, dan transparan</strong> melalui sistem digital resmi Pengadilan Tinggi Tanjungkarang.
              </motion.p>

              <motion.div variants={fadeUp} className="flex flex-col sm:flex-row gap-4">
                <Link
                  to="/register-permit"
                  className="group flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-blue-900 font-bold px-8 py-4 rounded-xl shadow-xl hover:shadow-amber-400/40 transition-all duration-300 text-base"
                >
                  Ajukan Penelitian
                  <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                </Link>
                <a
                  href="#persyaratan"
                  className="flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-8 py-4 rounded-xl border border-white/20 transition-all duration-300"
                >
                  Lihat Persyaratan
                </a>
              </motion.div>

              <motion.div variants={fadeUp} className="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/20">
                {[
                  { icon: Zap, label: 'Cepat & Mudah', desc: 'Proses digital' },
                  { icon: Users, label: 'Untuk Semua', desc: 'Mahasiswa & umum' },
                  { icon: Globe, label: 'Transparan', desc: 'Lacak status realtime' },
                ].map((f) => (
                  <div key={f.label} className="text-center">
                    <div className="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mx-auto mb-2">
                      <f.icon className="w-5 h-5 text-amber-400" />
                    </div>
                    <p className="text-white font-semibold text-sm">{f.label}</p>
                    <p className="text-blue-300 text-xs">{f.desc}</p>
                  </div>
                ))}
              </motion.div>
            </motion.div>

            {/* Hero Illustration */}
            <motion.div
              initial={{ opacity: 0, x: 50 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ duration: 0.8, delay: 0.3 }}
              className="hidden lg:flex justify-center"
            >
              <div className="relative">
                <div className="w-80 h-80 bg-white/10 rounded-3xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-2xl">
                  <div className="text-center">
                    <div className="w-20 h-20 bg-amber-400 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                      <Scale className="w-10 h-10 text-blue-900" />
                    </div>
                    <h3 className="text-white text-2xl font-bold mb-1">E-RISET</h3>
                    <p className="text-blue-200 text-sm">Electronic Research Permit System</p>
                    <div className="mt-6 space-y-2">
                      {['Daftar & Ajukan', 'Upload Dokumen', 'Cek Status'].map((t, i) => (
                        <div key={i} className="flex items-center gap-2 bg-white/10 rounded-lg px-4 py-2">
                          <CheckCircle2 className="w-4 h-4 text-amber-400 shrink-0" />
                          <span className="text-white text-sm">{t}</span>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
                {/* Floating badges */}
                <motion.div
                  animate={{ y: [0, -10, 0] }}
                  transition={{ repeat: Infinity, duration: 3 }}
                  className="absolute -top-4 -right-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg"
                >
                  ✓ Gratis 100%
                </motion.div>
                <motion.div
                  animate={{ y: [0, 10, 0] }}
                  transition={{ repeat: Infinity, duration: 4, delay: 1 }}
                  className="absolute -bottom-4 -left-4 bg-amber-400 text-blue-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-lg"
                >
                  Nomor Registrasi Otomatis
                </motion.div>
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* ── TENTANG ── */}
      <section id="tentang" className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div
            initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger}
            className="text-center mb-12"
          >
            <motion.span variants={fadeUp} className="inline-block text-xs font-semibold text-amber-600 bg-amber-100 px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
              Tentang Sistem
            </motion.span>
            <motion.h2 variants={fadeUp} className="text-3xl sm:text-4xl font-bold text-blue-900 mb-4">Apa itu E-RISET?</motion.h2>
            <motion.p variants={fadeUp} className="text-gray-600 max-w-2xl mx-auto text-lg">
              E-RISET adalah sistem permohonan izin penelitian elektronik resmi Pengadilan Tinggi Tanjungkarang yang dirancang untuk mempermudah proses pengajuan riset secara digital.
            </motion.p>
          </motion.div>

          <motion.div
            initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger}
            className="grid grid-cols-1 md:grid-cols-3 gap-6"
          >
            {[
              {
                icon: Globe, color: 'blue',
                title: 'Platform Digital',
                desc: 'Menggantikan proses manual berbasis kertas dan Google Form menjadi sistem web modern yang terintegrasi dan terstruktur.'
              },
              {
                icon: ShieldCheck, color: 'green',
                title: 'Terverifikasi Resmi',
                desc: 'Setiap permohonan diverifikasi langsung oleh petugas Pengadilan Tinggi Tanjungkarang sesuai prosedur resmi instansi.'
              },
              {
                icon: Zap, color: 'amber',
                title: 'Cepat & Transparan',
                desc: 'Pemohon dapat memantau status permohonan secara realtime menggunakan nomor registrasi unik yang digenerate otomatis.'
              }
            ].map((card, i) => {
              const colorMap = {
                blue: 'bg-blue-100 text-blue-700',
                green: 'bg-green-100 text-green-700',
                amber: 'bg-amber-100 text-amber-700',
              }
              return (
                <motion.div
                  key={i} variants={fadeUp}
                  className="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow border border-gray-100 group"
                >
                  <div className={`w-14 h-14 ${colorMap[card.color]} rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform`}>
                    <card.icon className="w-7 h-7" />
                  </div>
                  <h3 className="text-xl font-bold text-blue-900 mb-3">{card.title}</h3>
                  <p className="text-gray-600 leading-relaxed">{card.desc}</p>
                </motion.div>
              )
            })}
          </motion.div>
        </div>
      </section>

      {/* ── ALUR PENGAJUAN ── */}
      <section id="alur" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger} className="text-center mb-14">
            <motion.span variants={fadeUp} className="inline-block text-xs font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
              Prosedur
            </motion.span>
            <motion.h2 variants={fadeUp} className="text-3xl sm:text-4xl font-bold text-blue-900 mb-4">Alur Pengajuan Izin Penelitian</motion.h2>
            <motion.p variants={fadeUp} className="text-gray-600 max-w-xl mx-auto">Ikuti 5 langkah mudah untuk mendapatkan izin penelitian di Pengadilan Tinggi Tanjungkarang</motion.p>
          </motion.div>

          <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger} className="relative">
            {/* Connector Line */}
            <div className="hidden lg:block absolute top-12 left-[10%] right-[10%] h-0.5 bg-gradient-to-r from-blue-200 via-amber-300 to-green-200" />

            <div className="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-6">
              {steps.map((step, i) => (
                <motion.div key={i} variants={fadeUp} className="relative flex flex-col items-center text-center">
                  <div className={`w-24 h-24 rounded-2xl flex flex-col items-center justify-center mb-4 shadow-lg relative z-10 ${i === steps.length - 1 ? 'bg-gradient-to-br from-green-500 to-green-600' : 'bg-gradient-to-br from-blue-800 to-blue-600'}`}>
                    <step.icon className="w-8 h-8 text-white" />
                    <span className="text-white text-xs font-bold mt-1">{i + 1}</span>
                  </div>
                  <h3 className="font-bold text-blue-900 mb-1">{step.label}</h3>
                  <p className="text-gray-500 text-sm">{step.desc}</p>
                </motion.div>
              ))}
            </div>
          </motion.div>
        </div>
      </section>

      {/* ── PERSYARATAN ── */}
      <section id="persyaratan" className="py-20 bg-blue-900">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger} className="text-center mb-12">
            <motion.span variants={fadeUp} className="inline-block text-xs font-semibold text-amber-400 bg-amber-400/20 px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
              Berkas Wajib
            </motion.span>
            <motion.h2 variants={fadeUp} className="text-3xl sm:text-4xl font-bold text-white mb-4">Persyaratan Dokumen</motion.h2>
            <motion.p variants={fadeUp} className="text-blue-200 max-w-xl mx-auto">Siapkan dokumen berikut sebelum mengajukan permohonan</motion.p>
          </motion.div>

          <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger} className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {(requirements.length > 0 ? requirements : [
              { id: 1, name: 'Surat Permohonan', description: 'Surat permohonan resmi izin penelitian', is_required: true },
              { id: 2, name: 'Proposal Penelitian', description: 'Proposal lengkap dengan metodologi', is_required: true },
              { id: 3, name: 'Kartu Tanda Penduduk (KTP)', description: 'KTP pemohon asli/fotokopi', is_required: true },
              { id: 4, name: 'Kartu Tanda Mahasiswa (KTM)', description: 'KTM untuk mahasiswa aktif', is_required: true },
              { id: 5, name: 'Surat Pengantar Kampus / Instansi', description: 'Surat pengantar dari pimpinan', is_required: true },
              { id: 6, name: 'Dokumen Pendukung Lainnya', description: 'Kuesioner atau berkas tambahan', is_required: false },
            ]).map((req) => {
              const Icon = docIcons[req.name] || FileText
              return (
                <motion.div key={req.id} variants={fadeUp} className="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 hover:bg-white/20 transition-all group">
                  <div className="flex items-start gap-4">
                    <div className="w-12 h-12 bg-amber-400/20 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-amber-400/40 transition-colors">
                      <Icon className="w-6 h-6 text-amber-400" />
                    </div>
                    <div>
                      <div className="flex items-center gap-2 mb-1">
                        <h3 className="font-semibold text-white">{req.name}</h3>
                        {req.is_required
                          ? <span className="text-[10px] bg-red-500/80 text-white px-2 py-0.5 rounded-full font-medium">Wajib</span>
                          : <span className="text-[10px] bg-gray-500/80 text-white px-2 py-0.5 rounded-full font-medium">Opsional</span>
                        }
                      </div>
                      <p className="text-blue-200 text-sm">{req.description}</p>
                    </div>
                  </div>
                </motion.div>
              )
            })}
          </motion.div>

          <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={fadeUp} className="text-center mt-10">
            <Link
              to="/register-permit"
              className="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-blue-900 font-bold px-8 py-4 rounded-xl shadow-xl hover:shadow-amber-400/40 transition-all"
            >
              Mulai Pengajuan Sekarang
              <ArrowRight className="w-5 h-5" />
            </Link>
          </motion.div>
        </div>
      </section>

      {/* ── FAQ ── */}
      <section id="faq" className="py-20 bg-gray-50">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger} className="text-center mb-12">
            <motion.span variants={fadeUp} className="inline-block text-xs font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
              Pertanyaan Umum
            </motion.span>
            <motion.h2 variants={fadeUp} className="text-3xl sm:text-4xl font-bold text-blue-900 mb-4">FAQ</motion.h2>
            <motion.p variants={fadeUp} className="text-gray-600">Jawaban atas pertanyaan yang sering diajukan</motion.p>
          </motion.div>

          <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger} className="space-y-3">
            {(faqs.length > 0 ? faqs : [
              { id: 1, question: 'Berapa lama proses pengajuan izin penelitian?', answer: 'Proses verifikasi memakan waktu 3–5 hari kerja sejak berkas dinyatakan lengkap.' },
              { id: 2, question: 'Dokumen apa saja yang wajib diunggah?', answer: 'Surat Permohonan, Proposal Penelitian, KTP, KTM, dan Surat Pengantar Kampus/Instansi.' },
              { id: 3, question: 'Bagaimana cara memantau status permohonan?', answer: 'Gunakan halaman Cek Status dengan memasukkan nomor registrasi yang Anda terima setelah mengirim formulir.' },
              { id: 4, question: 'Apa yang harus dilakukan jika permohonan ditolak?', answer: 'Baca catatan penolakan dari admin, perbaiki berkas, lalu kirimkan permohonan baru.' },
              { id: 5, question: 'Apakah ada biaya untuk pengajuan?', answer: 'Tidak ada biaya. Layanan E-RISET sepenuhnya gratis dan transparan.' },
            ]).map((faq) => (
              <motion.div key={faq.id} variants={fadeUp} className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button
                  className="w-full flex items-center justify-between p-5 text-left hover:bg-blue-50/50 transition-colors"
                  onClick={() => setOpenFaq(openFaq === faq.id ? null : faq.id)}
                >
                  <span className="font-semibold text-blue-900 pr-4">{faq.question}</span>
                  {openFaq === faq.id
                    ? <ChevronUp className="w-5 h-5 text-blue-700 shrink-0" />
                    : <ChevronDown className="w-5 h-5 text-gray-400 shrink-0" />
                  }
                </button>
                {openFaq === faq.id && (
                  <motion.div
                    initial={{ opacity: 0, height: 0 }}
                    animate={{ opacity: 1, height: 'auto' }}
                    className="px-5 pb-5 text-gray-600 leading-relaxed border-t border-gray-100"
                  >
                    <p className="pt-3">{faq.answer}</p>
                  </motion.div>
                )}
              </motion.div>
            ))}
          </motion.div>
        </div>
      </section>

      {/* ── KONTAK ── */}
      <section id="kontak" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger} className="text-center mb-12">
            <motion.span variants={fadeUp} className="inline-block text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
              Hubungi Kami
            </motion.span>
            <motion.h2 variants={fadeUp} className="text-3xl sm:text-4xl font-bold text-blue-900 mb-4">Alamat</motion.h2>
          </motion.div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <motion.div initial="hidden" whileInView="show" viewport={{ once: true }} variants={stagger} className="space-y-5">
              {[
                { icon: Building2, label: 'Instansi', value: setting?.nama_instansi || 'Pengadilan Tinggi Tanjungkarang' },
                { icon: MapPin, label: 'Alamat', value: setting?.alamat || 'Jl. W.R. Supratman No.1, Tanjungkarang, Bandar Lampung 35121' },
                { icon: Phone, label: 'Telepon', value: setting?.telepon || '(0721) 482163' },
                { icon: Mail, label: 'Email', value: setting?.email || 'pt.tanjungkarang@gmail.com' },
                { icon: Clock, label: 'Jam Operasional', value: 'Senin – Jumat: 08.00 – 16.00 WIB' },
              ].map((item, i) => (
                <motion.div key={i} variants={fadeUp} className="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                  <div className="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <item.icon className="w-5 h-5 text-blue-700" />
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 font-medium mb-0.5">{item.label}</p>
                    <p className="text-gray-800 font-medium">{item.value}</p>
                  </div>
                </motion.div>
              ))}
            </motion.div>

            <motion.div
              initial={{ opacity: 0, x: 30 }} whileInView={{ opacity: 1, x: 0 }}
              transition={{ duration: 0.6 }} viewport={{ once: true }}
              className="rounded-2xl overflow-hidden shadow-xl border border-gray-200 h-80 lg:h-auto"
            >
              {setting?.google_maps ? (
                <div dangerouslySetInnerHTML={{ __html: setting.google_maps }} className="w-full h-full min-h-[320px]" />
              ) : (
                <iframe
                  title="Lokasi Pengadilan Tinggi Tanjungkarang"
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.487258388!2d105.26390!3d-5.37684!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40db4070e02f01%3A0x3b4fb3b8d60c2fa5!2sPengadilan%20Tinggi%20Tanjungkarang!5e0!3m2!1sid!2sid!4v1700000000000"
                  width="100%" height="100%" style={{ border: 0, minHeight: '320px' }}
                  allowFullScreen loading="lazy"
                />
              )}
            </motion.div>
          </div>
        </div>
      </section>

      {/* ── CTA Banner ── */}
      <section className="bg-gradient-to-r from-amber-400 to-amber-500 py-14">
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h2 className="text-2xl sm:text-3xl font-bold text-blue-900 mb-3">Siap Mengajukan Izin Penelitian?</h2>
          <p className="text-blue-800 mb-6">Proses online, gratis, dan mudah dipantau kapan saja.</p>
          <Link
            to="/register-permit"
            className="inline-flex items-center gap-2 bg-blue-900 hover:bg-blue-800 text-white font-bold px-10 py-4 rounded-xl shadow-xl transition-all"
          >
            Ajukan Sekarang <ArrowRight className="w-5 h-5" />
          </Link>
        </div>
      </section>

      <Footer />
    </div>
  )
}
