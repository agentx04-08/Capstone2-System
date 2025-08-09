import React from 'react'

export default function Report() {
  return (
    <div className="card card-wood p-3">
      <h5>Exports & Reports</h5>
      <p>Download latest datasets:</p>
      <div className="vstack gap-2">
        <div>
          <strong>Inventory</strong>
          <div className="btn-group ms-2">
            <a className="btn btn-outline-secondary btn-sm" href="/api/export/inventory/excel">Excel</a>
            <a className="btn btn-outline-secondary btn-sm" href="/api/export/inventory/pdf">PDF</a>
          </div>
        </div>
        <div>
          <strong>Orders</strong>
          <div className="btn-group ms-2">
            <a className="btn btn-outline-secondary btn-sm" href="/api/export/orders/excel">Excel</a>
            <a className="btn btn-outline-secondary btn-sm" href="/api/export/orders/pdf">PDF</a>
          </div>
        </div>
        <div>
          <strong>Productions</strong>
          <div className="btn-group ms-2">
            <a className="btn btn-outline-secondary btn-sm" href="/api/export/productions/excel">Excel</a>
            <a className="btn btn-outline-secondary btn-sm" href="/api/export/productions/pdf">PDF</a>
          </div>
        </div>
      </div>
    </div>
  )
}