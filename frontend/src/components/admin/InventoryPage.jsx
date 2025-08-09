import React, { useEffect, useState } from 'react'
import api from '../../services/api'

export default function InventoryPage() {
  const [items, setItems] = useState([])
  const [form, setForm] = useState({ sku:'', name:'', unit:'pcs', stock:0, reorder_level:0, lead_time_days:0, consumption_rate_per_day:0 })

  const load = () => api.get('/inventory').then(r => setItems(r.data))
  useEffect(() => { load(); const t = setInterval(load, 5000); return ()=>clearInterval(t) }, [])

  const submit = (e) => {
    e.preventDefault()
    api.post('/inventory', form).then(load)
  }

  const updateField = (k,v) => setForm(f => ({...f, [k]: v}))

  const forecast = async (id) => {
    const r = await api.get(`/inventory/${id}/forecast`)
    alert(`Avg/day: ${r.data.avg_daily_consumption}\nDays until stockout: ${r.data.days_until_stockout ?? 'N/A'}\nReorder? ${r.data.should_reorder ? 'Yes' : 'No'}\nSuggested Qty: ${r.data.suggested_reorder_qty}`)
  }

  return (
    <div className="row g-3">
      <div className="col-md-4">
        <div className="card card-wood p-3">
          <h5>Add Inventory</h5>
          <form onSubmit={submit} className="vstack gap-2">
            {['sku','name','unit','stock','reorder_level','lead_time_days','consumption_rate_per_day'].map(k => (
              <input key={k} required className="form-control" placeholder={k.replaceAll('_',' ')} value={form[k]} onChange={e=>updateField(k, e.target.value)} />
            ))}
            <button className="btn btn-wood" type="submit">Save</button>
          </form>
        </div>
      </div>
      <div className="col-md-8">
        <div className="card card-wood p-3">
          <div className="d-flex justify-content-between align-items-center">
            <h5>Inventory</h5>
            <div className="btn-group">
              <a className="btn btn-outline-secondary" href="/api/export/inventory/excel">Export Excel</a>
              <a className="btn btn-outline-secondary" href="/api/export/inventory/pdf">Export PDF</a>
            </div>
          </div>
          <table className="table table-wood table-sm mt-2">
            <thead><tr><th>SKU</th><th>Name</th><th>Unit</th><th>Stock</th><th></th></tr></thead>
            <tbody>
              {items.map(i => (
                <tr key={i.id}>
                  <td>{i.sku}</td>
                  <td>{i.name}</td>
                  <td>{i.unit}</td>
                  <td>{i.stock}</td>
                  <td>
                    <button className="btn btn-sm btn-primary me-2" onClick={()=>forecast(i.id)}>Forecast</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}