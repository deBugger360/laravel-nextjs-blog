// app/page.tsx
import BlogLayout from '@/components/layout/BlogLayout';
import Hero from '@/components/home/Hero';
import PostGrid from '@/components/home/PostGrid';
import { api } from '@/lib/api';

// Fetch data on the server
async function getPosts() {
  try {
    const response = await api.get('/posts?page=1');
    return response.data.data.data || [];
  } catch (error) {
    console.error('Error fetching posts:', error);
    return [];
  }
}

export default async function HomePage() {
  const posts = await getPosts();
  
  // Get latest posts for hero slider (first 3 posts)
  const latestPosts = posts?.slice(0, 3) || [];

  return (
    <BlogLayout showHero={true}>
      {/* Hero Section */}
      <Hero latestPosts={latestPosts} />
      
      {/* Main Content */}
      <PostGrid posts={posts} isLoading={false} />
    </BlogLayout>
  );
}