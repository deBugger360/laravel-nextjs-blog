// components/home/PostGrid.tsx
'use client';

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

interface PostGridProps {
  posts: Post[];
  isLoading: boolean;
}

export default function PostGrid({ posts, isLoading }: PostGridProps) {
  if (isLoading) {
    return (
      <div id="bricks" className="bricks">
        <div className="masonry">
          <div className="bricks-wrapper" data-animate-block>
            <div className="grid-sizer"></div>
            <div className="text-center">Loading posts...</div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div id="bricks" className="bricks">
      <div className="masonry">
        <div className="bricks-wrapper" data-animate-block>
          <div className="grid-sizer"></div>

          {posts && posts.length > 0 ? (
            posts.map((post) => (
              <article key={post.id} className="brick entry" data-animate-el>
                <div className="entry__thumb">
                  <a href={`/posts/${post.slug}`} className="thumb-link">
                    {post.featured_image ? (
                      <img 
                        src={post.featured_image} 
                        alt={post.title}
                        className="img-fluid"
                      />
                    ) : (
                      <img
                        src="/theme/images/thumbs/blog/aaron-burden-y02jEX_B0O0-unsplash.webp"
                        alt={post.title}
                        className="img-fluid"
                      />
                    )}
                  </a>
                </div>

                <div className="entry__text">
                  <div className="entry__header">
                    <div className="entry__meta">
                      {post.category && (
                        <span className="cat-links">
                          <a href={`/posts?category=${post.category.name}`}>
                            {post.category.name}
                          </a>
                        </span>
                      )}
                      <span className="byline">
                        By:
                        <a href={`/posts?author=${post.author}`}>
                          {post.author}
                        </a>
                      </span>
                    </div>
                    <h1 className="entry__title">
                      <a href={`/posts/${post.slug}`}>
                        {post.title}
                      </a>
                    </h1>
                  </div>
                  <div className="entry__excerpt">
                    <p>{post.excerpt}</p>
                  </div>
                  <a className="entry__more-link" href={`/posts/${post.slug}`}>
                    Read More
                  </a>
                </div>
              </article>
            ))
          ) : (
            <div className="text-center">
              <h2>No blog posts found.</h2>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}