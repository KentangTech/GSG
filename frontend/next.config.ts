import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  async rewrites() {
    return [
      {
        source: '/api/direksi',
        destination: 'https://adminweb.grahasaranagresik.com/api/direksi',
      },
    ];
  },
  images: {
    domains: ['https://adminweb.grahasaranagresik.com/api/'],
  },
};

export default nextConfig;
