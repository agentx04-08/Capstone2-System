import React from 'react'
import { Routes, Route, Navigate, Link } from 'react-router-dom'
import AdminDashboard from './components/admin/AdminDashboard.jsx'
import InventoryPage from './components/admin/InventoryPage'
import OrderPage from './components/admin/OrderPage'
import ProductionPage from './components/admin/ProductionPage'
import Report from './components/admin/Report'
import CustomerDashboard from './components/customer/CustomerDashboard.jsx'
import ProductCatalog from './components/customer/ProductCatalog.jsx'
import Cart from './components/customer/Cart.jsx'
import Login from './components/auth/Login.jsx'
import Register from './components/auth/Register.jsx'

function Navbar() {
  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-wood shadow-sm">
      <div className="container-fluid">
        <Link to="/" className="navbar-brand d-flex align-items-center gap-2">
          <img src="/src/assets/logo.png" alt="Unick" height={28} />
          <span>Unick Enterprises Inc.</span>
        </Link>
        <div className="collapse navbar-collapse">
          <ul className="navbar-nav ms-auto gap-2">
            <li className="nav-item"><Link className="nav-link" to="/admin">Admin</Link></li>
            <li className="nav-item"><Link className="nav-link" to="/catalog">Catalog</Link></li>
            <li className="nav-item"><Link className="nav-link" to="/cart">Cart</Link></li>
            <li className="nav-item"><Link className="nav-link" to="/login">Login</Link></li>
          </ul>
        </div>
      </div>
    </nav>
  )
}

export default function App() {
  return (
    <div className="bg-wood-light min-vh-100">
      <Navbar />
      <div className="container py-4">
        <Routes>
          <Route path="/" element={<Navigate to="/catalog" />} />
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/admin/inventory" element={<InventoryPage />} />
          <Route path="/admin/orders" element={<OrderPage />} />
          <Route path="/admin/productions" element={<ProductionPage />} />
          <Route path="/admin/reports" element={<Report />} />

          <Route path="/catalog" element={<ProductCatalog />} />
          <Route path="/cart" element={<Cart />} />

          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
        </Routes>
      </div>
    </div>
  )
}