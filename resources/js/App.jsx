import React from 'react'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import LandingPage from './pages/LandingPage'
import FormPage from './pages/FormPage'
import SuccessPage from './pages/SuccessPage'
import TrackPage from './pages/TrackPage'
import AdminLogin from './pages/admin/AdminLogin'
import AdminDashboard from './pages/admin/AdminDashboard'
import AdminSubmissions from './pages/admin/AdminSubmissions'
import AdminRequirements from './pages/admin/AdminRequirements'
import AdminFaqs from './pages/admin/AdminFaqs'
import AdminAnnouncements from './pages/admin/AdminAnnouncements'
import AdminPanitera from './pages/admin/AdminPanitera'
import AdminTemplateSurat from './pages/admin/AdminTemplateSurat'
import AdminReports from './pages/admin/AdminReports'
import AdminSettings from './pages/admin/AdminSettings'

function App() {
  return (
    <Router>
      <Routes>
        {/* Public Routes */}
        <Route path="/" element={<LandingPage />} />
        <Route path="/register-permit" element={<FormPage />} />
        <Route path="/success/:registration_number" element={<SuccessPage />} />
        <Route path="/track" element={<TrackPage />} />

        {/* Admin Routes */}
        <Route path="/admin/login" element={<AdminLogin />} />
        <Route path="/admin/dashboard" element={<AdminDashboard />} />
        <Route path="/admin/panitera" element={<AdminPanitera />} />
        <Route path="/admin/templates" element={<AdminTemplateSurat />} />
        <Route path="/admin/submissions" element={<AdminDashboard />} /> {/* Fallback or redirect to dashboard list */}
        <Route path="/admin/submissions/:id" element={<AdminSubmissions />} />
        <Route path="/admin/requirements" element={<AdminRequirements />} />
        <Route path="/admin/faqs" element={<AdminFaqs />} />
        <Route path="/admin/announcements" element={<AdminAnnouncements />} />
        <Route path="/admin/reports" element={<AdminReports />} />
        <Route path="/admin/settings" element={<AdminSettings />} />
      </Routes>
    </Router>
  )
}

export default App
