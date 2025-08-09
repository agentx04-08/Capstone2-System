import React, { useEffect, useState } from 'react'
import api from '../../services/api'

export default function ProductionPage() {
  const [productions, setProductions] = useState([])
  const [inventory, setInventory] = useState([])
  const [form, setForm] = useState({ inventory_id:'', quantity:0, workstation:'', status:'queued', produced_at:'' })
  const [schedule, setSchedule] = useState([])

  const load = () => Promise.all([
    api.get('/productions'), api.get('/inventory'), api.get('/productions/schedule/forecast')
  ]).then(([p,i,s]) => { setProductions(p.data); setInventory(i.data); setSchedule(s.data) })

  useEffect(() => { load(); const t=setInterval(load,5000); return ()=>clearInterval(t)}, [])

  const submit = (e) => {
    e.preventDefault()
    api.post('/productions', form).then(load)
  }

  return (
    <div className="row g-3">
      <div className="col-md-5">
        <div className="card card-wood p-3">
          <h5>Log Production</h5>
          <form onSubmit={submit} className="vstack gap-2">
            <select className="form-select" value={form.inventory_id} onChange={e=>setForm(f=>({...f, inventory_id:e.target.value}))}>
              <option value="">Select Item</option>
              {inventory.map(i => <option key={i.id} value={i.id}>{i.sku} - {i.name}</option>)}
            </select>
            <input type="number" className="form-control" placeholder="Quantity" value={form.quantity} onChange={e=>setForm(f=>({...f, quantity:+e.target.value}))} />
            <input className="form-control" placeholder="Workstation" value={form.workstation} onChange={e=>setForm(f=>({...f, workstation:e.target.value}))} />
            <select className="form-select" value={form.status} onChange={e=>setForm(f=>({...f, status:e.target.value}))}>
              <option value="queued">Queued</option>
              <option value="processing">Processing</option>
              <option value="completed">Completed</option>
            </select>
            <input type="datetime-local" className="form-control" value={form.produced_at} onChange={e=>setForm(f=>({...f, produced_at:e.target.value}))} />
            <button className="btn btn-wood" type="submit">Save</button>
          </form>
        </div>
      </div>
      <div className="col-md-7">
        <div className="card card-wood p-3">
          <div className="d-flex justify-content-between align-items-center">
            <h5>Production Records</h5>
            <div className="btn-group">
              <a className="btn btn-outline-secondary" href="/api/export/productions/excel">Export Excel</a>
              <a className="btn btn-outline-secondary" href="/api/export/productions/pdf">Export PDF</a>
            </div>
          </div>
          <div className="table-responsive">
            <table className="table table-wood table-sm mt-2">
              <thead><tr><th>Item</th><th>Qty</th><th>Workstation</th><th>Status</th><th>Produced At</th></tr></thead>
              <tbody>
                {productions.map(p => (
                  <tr key={p.id}>
                    <td>{p.inventory?.name}</td>
                    <td>{p.quantity}</td>
                    <td>{p.workstation}</td>
                    <td>{p.status}</td>
                    <td>{new Date(p.produced_at).toLocaleString()}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        <div className="card card-wood p-3 mt-3">
          <h5>Forecasted Schedule (14 days)</h5>
          <ul className="list-group">
            {schedule.map(s => (
              <li key={s.inventory_id} className="list-group-item d-flex justify-content-between">
                <span>{s.sku} - {s.name}</span>
                <span>{s.planned_date} • Suggest Qty: {s.suggested_qty}</span>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  )
}