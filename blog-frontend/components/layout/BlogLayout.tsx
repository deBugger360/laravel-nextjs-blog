// components/layout/BlogLayout.tsx
'use client';

import { ReactNode } from 'react';
import Header from './Header';
import Footer from './Footer';

interface BlogLayoutProps {
  children: ReactNode;
  showHero?: boolean;
}

export default function BlogLayout({ children, showHero = false }: BlogLayoutProps) {
  return (
    <div id="page" className="s-pagewrap">
      {/* Preloader - using your existing theme */}
      <div id="preloader">
        <div id="loader" className="dots-fade">
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>

      <Header />
      
      {/* Main Content */}
      <section id="content" className="s-content">
        {children}
      </section>
      
      <Footer />

      {/* Theme Scripts */}
      <script src="/theme/js/plugins.js" async />
      <script src="/theme/js/main.js" async />
    </div>
  );
}