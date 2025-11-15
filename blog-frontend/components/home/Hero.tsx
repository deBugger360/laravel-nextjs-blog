// components/home/Hero.tsx
'use client';

interface Post {
  id: number;
  title: string;
  excerpt: string;
  slug: string;
  category?: { name: string };
  featured_image?: string;
}

interface HeroProps {
  latestPosts: Post[];
}

export default function Hero({ latestPosts }: HeroProps) {
  if (!latestPosts || latestPosts.length === 0) {
    return (
      <div className="hero">
        <div className="hero__slider swiper-container">
          <div className="swiper-wrapper">
            <article className="hero__slide swiper-slide">
              <div 
                className="hero__entry-image" 
                style={{ 
                  backgroundImage: `url('/theme/images/thumbs/featured/featured-01_2000.jpg')` 
                }}
              ></div>
              <div className="hero__entry-text">
                <div className="hero__entry-text-inner">
                  <h2 className="hero__entry-title">
                    Welcome to Scribe & Share
                  </h2>
                  <p className="hero__entry-desc">
                    A collaborative blog where writers and readers connect through the power of words.
                  </p>
                  <a className="hero__more-link" href="/posts">
                    Explore Posts
                  </a>
                </div>
              </div>
            </article>
          </div>
          <div className="swiper-pagination"></div>
        </div>
      </div>
    );
  }

  return (
    <div className="hero">
      <div className="hero__slider swiper-container">
        <div className="swiper-wrapper">
          {latestPosts.map((post) => (
            <article key={post.id} className="hero__slide swiper-slide">
              <div 
                className="hero__entry-image" 
                style={{ 
                  backgroundImage: `url('${post.featured_image || '/theme/images/thumbs/featured/featured-01_2000.jpg'}')` 
                }}
              ></div>
              <div className="hero__entry-text">
                <div className="hero__entry-text-inner">
                  <div className="hero__entry-meta">
                    {post.category && (
                      <span className="cat-links">
                        <a href={`/posts?category=${post.category.name}`}>
                          {post.category.name}
                        </a>
                      </span>
                    )}
                  </div>
                  <h2 className="hero__entry-title">
                    <a href={`/posts/${post.slug}`}>
                      {post.title}
                    </a>
                  </h2>
                  <p className="hero__entry-desc">
                    {post.excerpt}
                  </p>
                  <a className="hero__more-link" href={`/posts/${post.slug}`}>
                    Read More
                  </a>
                </div>
              </div>
            </article>
          ))}
        </div>
        <div className="swiper-pagination"></div>
      </div>

      <a href="#bricks" className="hero__scroll-down smoothscroll">
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M10.25 6.75L4.75 12L10.25 17.25"></path>
          <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M19.25 12H5"></path>
        </svg>
        <span>Scroll</span>
      </a>
    </div>
  );
}