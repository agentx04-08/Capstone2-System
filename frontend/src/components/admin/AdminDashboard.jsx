import React, { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../services/api'

export default function AdminDashboard() {
  const [stats, setStats] = useState({ inventory: 0, orders: 0, productions: 0 })

  useEffect(() => {
    let mounted = true
    Promise.all([
      api.get('/inventory'),
      api.get('/orders'),
      api.get('/productions')
    ]).then(([i,o,p]) => {
      if (mounted) setStats({ inventory: i.data.length, orders: o.data.length, productions: p.data.length })
    }).catch(()=>{})
    return () => { mounted = false }
  }, [])

  return (
    <div className="row g-3">
      <div className="col-md-4">
        <div className="card card-wood p-3">
          <h5>Inventory Items</h5>
          <p className="display-6">{stats.inventory}</p>
          <Link to="/admin/inventory" className="btn btn-wood">Manage</Link>
        </div>
      </div>
      <div className="col-md-4">
        <div className="card card-wood p-3">
          <h5>Orders</h5>
          <p className="display-6">{stats.orders}</p>
          <Link to="/admin/orders" className="btn btn-wood">Manage</Link>
        </div>
      </div>
      <div className="col-md-4">
        <div className="card card-wood p-3">
          <h5>Productions</h5>
          <p className="display-6">{stats.productions}</p>
          <Link to="/admin/productions" className="btn btn-wood">Manage</Link>
        </div>
      </div>
    </div>
  )
}