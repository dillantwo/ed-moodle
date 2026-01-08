/** @type {import('next').NextConfig} */
const nextConfig = {
  // 设置 basePath 用于反向代理
  basePath: '/chatbot',
  // 性能优化配置
  serverExternalPackages: ['mongoose'],
  // API 路由优化
  async headers() {
    return [
      {
        source: '/api/:path*',
        headers: [
          {
            key: 'Cache-Control',
            value: 'no-cache, no-store, must-revalidate',
          },
          {
            key: 'Connection',
            value: 'keep-alive',
          },
        ],
      },
    ];
  },
  // 启用 instrumentation
  experimental: {
    instrumentationHook: true,
  },
};

export default nextConfig;

