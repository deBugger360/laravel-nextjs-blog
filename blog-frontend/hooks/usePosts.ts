// hooks/usePosts.ts
'use client';

import useSWR from 'swr';
import { api } from '@/lib/api';

const fetcher = (url: string) => api.get(url).then(res => res.data);

export function usePosts(page: number = 1) {
  const { data, error, isLoading } = useSWR(
    `/posts?page=${page}`, 
    fetcher,
    {
      revalidateOnFocus: false,
    }
  );

  return {
    posts: data?.data?.data || [],
    pagination: data?.data,
    isLoading: isLoading,
    isError: error,
  };
}