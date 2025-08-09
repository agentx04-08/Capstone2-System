import React, { useEffect, useState } from 'react'
import api from '../../services/api'

export default function ProductCatalog(){
  const [items, setItems] = useState([])

  const load = () => api.get('/inventory').then(r => setItems(r.data))
  useEffect(() => { load() }, [])

  const addToCart = (i) => {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]')
    cart.push({ id:i.id, sku:i.sku, name:i.name, price: i.price ?? 0, quantity: 1 })
    localStorage.setItem('cart', JSON.stringify(cart))
    alert('Added to cart')
  }

  return (
    <div className="row g-3">
      {items.map(i => (
        <div className="col-md-3" key={i.id}>
          <div className="card card-wood p-3 h-100">
            <h6>{i.name}</h6>
            <div className="small text-muted">{i.sku}</div>
            <div className="mt-auto d-grid">
              <button className="btn btn-wood" onClick={()=>addToCart(i)}>Add to Cart</button>
            </div>
          </div>
        </div>
      ))}
    </div>
  )
}