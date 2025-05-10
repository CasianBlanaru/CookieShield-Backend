/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: 'https://cookieshield-backend-main-zdejjv.laravel.cloud/api/:path*',
      },
    ];
  },
};

module.exports = nextConfig;
