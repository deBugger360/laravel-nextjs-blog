// app/layout.tsx
import type { Metadata } from 'next';
import { Inter } from 'next/font/google';
import { AuthProvider } from '@/lib/auth-context';
import './globals.css';

const inter = Inter({ subsets: ['latin'] });

export const metadata: Metadata = {
  title: {
    default: 'Scribe & Share - A Blog for Writers and Readers',
    template: '%s | Scribe & Share'
  },
  description: 'A collaborative blog where we share our written words with the world. Expect articles, poems, and musings on life, love, and everything in between.',
  keywords: ['blog', 'writing', 'poetry', 'articles', 'creative writing'],
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" className="no-js">
      <head>
        <script
          dangerouslySetInnerHTML={{
            __html: `
              document.documentElement.classList.remove('no-js');
              document.documentElement.classList.add('js');
            `,
          }}
        />
        {/* Theme CSS */}
        <link rel="stylesheet" href="/theme/css/vendor.css" />
        <link rel="stylesheet" href="/theme/css/styles.css" />
        
        {/* Favicons */}
        <link rel="apple-touch-icon" sizes="180x180" href="/theme/images/favicons/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="/theme/images/favicons/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="/theme/images/favicons/favicon-16x16.png" />
        <link rel="manifest" href="/theme/images/favicons/site.webmanifest" />
      </head>
      <body className={inter.className}>
        <AuthProvider>
          {children}
        </AuthProvider>
      </body>
    </html>
  );
}