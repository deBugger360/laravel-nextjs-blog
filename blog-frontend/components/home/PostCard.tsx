// components/home/PostCard.tsx
'use client';

import Link from 'next/link';

interface Post {
  id: number;
  title: string;
  excerpt: string;
  slug: string;
  author: string;
  created_at: string;
  category?: { name: string };
  featured_image?: string;
}

interface PostCardProps {
  post: Post;
}

export default function PostCard({ post }: PostCardProps) {
  const cleanExcerpt = (excerpt: string) => {
    return excerpt.replace(/<[^>]*>/g, ''); // Remove HTML tags
  };

  return (
    <article className="brick entry" data-animate-el>
      {/* Thumbnail */}
      <div className="entry__thumb">
        <Link href={`/posts/${post.slug}`} className="thumb-link">
          {post.featured_image ? (
            <img 
              src={post.featured_image} 
              alt={post.title}
              className="img-fluid"
            />
          ) : (
            <img
              srcSet="/theme/images/thumbs/blog/aaron-burden-y02jEX_B0O0-unsplash-400w.webp 400w, /theme/images/thumbs/blog/aaron-burden-y02jEX_B0O0-unsplash-600w.webp 600w, /theme/images/thumbs/blog/aaron-burden-y02jEX_B0O0-unsplash-800w.webp 800w"
              sizes="(max-width: 400px) 400px, (max-width: 600px) 600px, (max-width: 800px) 800px"
              src="/theme/images/thumbs/blog/aaron-burden-y02jEX_B0O0-unsplash.webp"
              alt={post.title}
              width="4592"
              height="3448"
              loading="lazy"
            />
          )}
        </Link>
      </div>

      {/* Text Content */}
      <div className="entry__text">
        <div className="entry__header">
          <div className="entry__meta">
            {post.category && (
              <span className="cat-links">
                <Link href={`/posts?category=${post.category.name}`}>
                  {post.category.name}
                </Link>
              </span>
            )}
            <span className="byline">
              By:
              <Link href={`/posts?author=${post.author}`}>
                {post.author}
              </Link>
            </span>
          </div>
          <h1 className="entry__title">
            <Link href={`/posts/${post.slug}`}>
              {post.title}
            </Link>
          </h1>
        </div>
        <div className="entry__excerpt">
          <p>{cleanExcerpt(post.excerpt)}</p>
        </div>
        <Link className="entry__more-link" href={`/posts/${post.slug}`}>
          Read More
        </Link>
      </div>
    </article>
  );
}