// components/layout/Header.tsx
'use client';

import Link from 'next/link';
import { useState, useEffect } from 'react';
import { api } from '@/lib/api';

interface Category {
  id: number;
  name: string;
}

export default function Header() {
  const [categories, setCategories] = useState<Category[]>([]);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isSearchOpen, setIsSearchOpen] = useState(false);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const response = await api.get('/categories');
        setCategories(response.data.data);
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    };

    fetchCategories();
  }, []);

  return (
    <header id="masthead" className="s-header">
      {/* Branding */}
      <div className="s-header__branding">
        <p className="site-title">
          <Link href="/" rel="home">Scribe & Share</Link>
        </p>
      </div>

      {/* Navigation */}
      <div className="row s-header__navigation">
        <nav className="s-header__nav-wrap">
          <h3 className="s-header__nav-heading">Navigate to</h3>

          <ul className="s-header__nav">
            <li className="current-menu-item">
              <Link href="/" title="">Home</Link>
            </li>
            
            {/* Categories Dropdown */}
            <li className="has-children">
              <a href="javascript:void(0)" title="categories">Categories</a>
              <ul className="sub-menu">
                {categories.map((category) => (
                  <li key={category.id}>
                    <Link href={`/posts?category=${category.name}`}>
                      {category.name}
                    </Link>
                  </li>
                ))}
              </ul>
            </li>
            
            <li>
              <Link href="/about" title="">About</Link>
            </li>
            <li>
              <Link href="/contact" title="">Contact</Link>
            </li>
          </ul>
        </nav>
      </div>

      {/* Search Bar */}
      <div className={`s-header__search ${isSearchOpen ? 'is-visible' : ''}`}>
        <div className="s-header__search-inner">
          <div className="row">
            <form role="search" method="get" className="s-header__search-form" action="/posts">
              <label>
                <span className="u-screen-reader-text">Search for:</span>
                <input 
                  type="search" 
                  className="s-header__search-field" 
                  placeholder="Search for..." 
                  name="search" 
                  title="Search for:" 
                  autoComplete="off"
                />
              </label>
              <input type="submit" className="s-header__search-submit" value="Search" />
            </form>
            <a 
              href="#" 
              title="Close Search" 
              className="s-header__search-close"
              onClick={() => setIsSearchOpen(false)}
            >
              Close
            </a>
          </div>
        </div>
      </div>

      {/* Mobile Menu Toggle */}
      <a 
        className="s-header__menu-toggle" 
        href="#0" 
        onClick={(e) => { e.preventDefault(); setIsMenuOpen(!isMenuOpen); }}
      >
        <span>Menu</span>
      </a>
      
      {/* Search Toggle */}
      <a 
        className="s-header__search-trigger" 
        href="#"
        onClick={(e) => { e.preventDefault(); setIsSearchOpen(!isSearchOpen); }}
      >
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" 
            d="M19.25 19.25L15.5 15.5M4.75 11C4.75 7.54822 7.54822 4.75 11 4.75C14.4518 4.75 17.25 7.54822 17.25 11C17.25 14.4518 14.4518 17.25 11 17.25C7.54822 17.25 4.75 14.4518 4.75 11Z" />
        </svg>
      </a>
    </header>
  );
}