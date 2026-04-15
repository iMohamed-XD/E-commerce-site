import './bootstrap';
import Alpine from 'alpinejs';
import React from 'react';
import { createRoot } from 'react-dom/client';

window.Alpine = Alpine;
Alpine.start();

const dashboardReactRoot = document.getElementById('dashboard-react-root');

if (dashboardReactRoot) {
  function DashboardReactBridge() {
    return null;
  }

  createRoot(dashboardReactRoot).render(React.createElement(DashboardReactBridge));
}
