import React, { useState } from 'react'
import api from '../../services/api'

export default function Login(){
  const [form, setForm] = useState({ email:'', password:'' })
  const [error, setError] = useState('')

  const submit = async (e) => {
    e.preventDefault()
    try {
      const r = await api.post('/auth/login', form)
      localStorage.setItem('token', r.data.token)
      window.location.href = '/admin'
    } catch (e) {
      setError('Login failed')
    }
  }

  return (
    <div className="card card-wood p-3">
      <h5>Login</h5>
      {error && <div className="alert alert-danger">{error}</div>}
      <form onSubmit={submit} className="vstack gap-2">
        <input type="email" required className="form-control" placeholder="Email" value={form.email} onChange={e=>setForm(f=>({...f, email:e.target.value}))} />
        <input type="password" required className="form-control" placeholder="Password" value={form.password} onChange={e=>setForm(f=>({...f, password:e.target.value}))} />
        <button className="btn btn-wood">Login</button>
      </form>
    </div>
  )
}