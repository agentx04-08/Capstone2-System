import React, { useEffect, useState } from 'react'
import api from '../../services/api'

export default function Cart(){
  const [cart, setCart] = useState([])
  const [customer, setCustomer] = useState({ name:'', email:'' })
  const [placed, setPlaced] = useState(null)

  useEffect(()=>{ setCart(JSON.parse(localStorage.getItem('cart') || '[]')) }, [])

  const placeOrder = async () => {
    const items = cart.map(c => ({ inventory_id: c.id, quantity: c.quantity, unit_price: c.price || 1 }))
    const r = await api.post('/public/orders', { customer_name: customer.name, customer_email: customer.email, items })
    setPlaced(r.data)
    localStorage.removeItem('cart')
  }

  return (
    <div className="card card-wood p-3">
      <h5>Cart</h5>
      {!placed ? (
        <>
          <ul className="list-group">
            {cart.map((c, idx) => (
              <li key={idx} className="list-group-item d-flex justify-content-between">
                <span>{c.sku} - {c.name}</span>
                <span>Qty: {c.quantity}</span>
              </li>
            ))}
          </ul>
          <div className="row g-2 mt-3">
            <div className="col-md-4"><input className="form-control" placeholder="Your Name" value={customer.name} onChange={e=>setCustomer(s=>({...s, name:e.target.value}))} /></div>
            <div className="col-md-4"><input type="email" className="form-control" placeholder="Email" value={customer.email} onChange={e=>setCustomer(s=>({...s, email:e.target.value}))} /></div>
            <div className="col-md-4 d-grid"><button className="btn btn-wood" onClick={placeOrder}>Place Order</button></div>
          </div>
        </>
      ) : (
        <>
          <div className="alert alert-success">Order placed! Order #: {placed.order_number}</div>
        </>
      )}
    </div>
  )
}