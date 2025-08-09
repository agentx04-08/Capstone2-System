import React, { useState } from 'react'
import api from '../../services/api'

export default function Register(){
  const [form, setForm] = useState({ name:'', email:'', password:'' })
  const [error, setError] = useState('')

  const submit = async (e) => {
    e.preventDefault()
    try {
      const r = await api.post('/auth/register', form)
      localStorage.setItem('token', r.data.token)
      window.location.href = '/admin'
    } catch (e) {
      setError('Register failed')
    }
  }

  return (
    <div className="card card-wood p-3">
      <h5>Register</h5>
      {error && <div className="alert alert-danger">{error}</div>}
      <form onSubmit={submit} className="vstack gap-2">
        <input required className="form-control" placeholder="Name" value={form.name} onChange={e=>setForm(f=>({...f, name:e.target.value}))} />
        <input type="email" required className="form-control" placeholder="Email" value={form.email} onChange={e=>setForm(f=>({...f, email:e.target.value}))} />
        <input type="password" required className="form-control" placeholder="Password" value={form.password} onChange={e=>setForm(f=>({...f, password:e.target.value}))} />
        <button className="btn btn-wood">Register</button>
      </form>
    </div>
  )
}