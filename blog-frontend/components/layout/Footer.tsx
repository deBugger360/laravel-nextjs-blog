// components/layout/Footer.tsx
'use client';

import Link from 'next/link';
import { useState, useEffect } from 'react';
import { api } from '@/lib/api';

interface Category {
  id: number;
  name: string;
}

export default function Footer() {
  const [categories, setCategories] = useState<Category[]>([]);

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
    <footer id="colophon" className="s-footer">
      {/* Newsletter Subscription */}
      <div className="row s-footer__subscribe">
        <div className="column lg-12">
          <h2>Subscribe to Our Newsletter.</h2>
          <p>Subscribe now to get all latest updates</p>

          <form id="mc-form" className="mc-form">
            <input 
              type="email" 
              name="email" 
              id="mce-EMAIL" 
              className="u-fullwidth text-center" 
              placeholder="Your Email Address" 
              required 
            />
            <input type="submit" name="subscribe" value="Subscribe" className="btn--small btn--primary u-fullwidth" />
            <div className="mc-status"></div>
          </form>
        </div>
      </div>

      {/* Main Footer Content */}
      <div className="row s-footer__main">
        <div className="column lg-5 md-6 tab-12 s-footer__about">
          <h4>Scribe & Share</h4>
          <p>
            Scribe & Share is a collaborative blog where we share our written words with the world. 
            Expect to find articles, poems, and musings on life, love, and everything in between.
          </p>
        </div>

        <div className="column lg-5 md-6 tab-12">
          <div className="row">
            <div className="column lg-6">
              <h4>Categories</h4>
              <ul className="link-list">
                {categories.map((category) => (
                  <li key={category.id}>
                    <Link href={`/posts?category=${category.name}`}>
                      {category.name}
                    </Link>
                  </li>
                ))}
                {categories.length === 0 && (
                  <li className="text-muted">No categories available.</li>
                )}
              </ul>
            </div>
            <div className="column lg-6">
              <h4>Site Links</h4>
              <ul className="link-list">
                <li><Link href="/">Home</Link></li>
                <li><Link href="/about">About</Link></li>
                <li><Link href="/contact">Contact</Link></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      {/* Footer Bottom */}
      <div className="row s-footer__bottom">
        <div className="column lg-7 md-6 tab-12">
          <ul className="s-footer__social">
            <li><a href="#">Facebook</a></li>
            <li><a href="#">Twitter</a></li>
            <li><a href="#">Instagram</a></li>
            <li><a href="#">Pinterest</a></li>
          </ul>
        </div>
        <div className="column lg-5 md-6 tab-12">
          <div className="ss-copyright">
            <span>&copy; Copyright Scribe & Share {new Date().getFullYear()}</span>
            <span>Crafted with ❤️ by <a href="mailto:debugger.labs@gmail.com" target="_blank">DEBUG LAB</a></span>
          </div>
        </div>
      </div>

      {/* Go to Top */}
      <div className="ss-go-top">
        <a className="smoothscroll" title="Back to Top" href="#top">
          <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M17.25 10.25L12 4.75L6.75 10.25"/>
            <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 19.25V5.75"/>
          </svg>
        </a>
      </div>
    </footer>
  );
}