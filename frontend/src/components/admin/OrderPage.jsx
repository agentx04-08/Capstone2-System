import React, { useEffect, useState } from 'react'
import api from '../../services/api'

export default function OrderPage() {
  const [orders, setOrders] = useState([])
  const [inventory, setInventory] = useState([])
  const [form, setForm] = useState({ customer_name:'', customer_email:'', items:[] })
  const [newItem, setNewItem] = useState({ inventory_id:'', quantity:1, unit_price:0 })

  const load = () => Promise.all([api.get('/orders'), api.get('/inventory')]).then(([o,i]) => { setOrders(o.data); setInventory(i.data) })
  useEffect(() => { load(); const t=setInterval(load,5000); return ()=>clearInterval(t)}, [])

  const addItemLocal = () => {
    if (!newItem.inventory_id) return
    setForm(f => ({...f, items:[...f.items, newItem]}))
    setNewItem({ inventory_id:'', quantity:1, unit_price:0 })
  }

  const submit = (e) => {
    e.preventDefault()
    api.post('/orders', form).then(load)
  }

  return (
    <div className="card card-wood p-3">
      <div className="d-flex justify-content-between align-items-center">
        <h5>Orders</h5>
        <div className="btn-group">
          <a className="btn btn-outline-secondary" href="/api/export/orders/excel">Export Excel</a>
          <a className="btn btn-outline-secondary" href="/api/export/orders/pdf">Export PDF</a>
        </div>
      </div>

      <form onSubmit={submit} className="row g-2 align-items-end mt-2">
        <div className="col-md-3"><input required placeholder="Customer Name" className="form-control" value={form.customer_name} onChange={e=>setForm(f=>({...f, customer_name:e.target.value}))} /></div>
        <div className="col-md-3"><input required type="email" placeholder="Customer Email" className="form-control" value={form.customer_email} onChange={e=>setForm(f=>({...f, customer_email:e.target.value}))} /></div>

        <div className="col-md-2">
          <select className="form-select" value={newItem.inventory_id} onChange={e=>setNewItem(n=>({...n, inventory_id:e.target.value}))}>
            <option value="">Select Item</option>
            {inventory.map(i => <option key={i.id} value={i.id}>{i.sku} - {i.name}</option>)}
          </select>
        </div>
        <div className="col-md-1"><input type="number" className="form-control" placeholder="Qty" value={newItem.quantity} onChange={e=>setNewItem(n=>({...n, quantity:+e.target.value}))} /></div>
        <div className="col-md-2"><input type="number" step="0.01" className="form-control" placeholder="Unit Price" value={newItem.unit_price} onChange={e=>setNewItem(n=>({...n, unit_price:+e.target.value}))} /></div>
        <div className="col-md-1 d-grid"><button type="button" className="btn btn-secondary" onClick={addItemLocal}>Add</button></div>

        <div className="col-12 d-grid"><button className="btn btn-wood" type="submit">Create Order</button></div>
      </form>

      <div className="table-responsive mt-3">
        <table className="table table-wood table-sm">
          <thead><tr><th>Order #</th><th>Customer</th><th>Status</th><th>Total</th><th>Items</th></tr></thead>
          <tbody>
            {orders.map(o => (
              <tr key={o.id}>
                <td>{o.order_number}</td>
                <td>{o.customer_name}</td>
                <td>{o.status}</td>
                <td>{o.total_amount}</td>
                <td>{o.items?.length}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}