import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import './bootstrap';

createInertiaApp({
  resolve: async (name) => {
    const pages = import.meta.glob('./Pages/**/*.tsx');
    const importPage = pages[`./Pages/${name}.tsx`];

    if (!importPage) {
      throw new Error(`Inertia page not found: ${name}`);
    }

    const page = await importPage();
    return page.default;
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />);
  },
  progress: {
    color: '#d4af37',
    showSpinner: false,
  },
});
