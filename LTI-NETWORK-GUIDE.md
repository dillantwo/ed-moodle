# LTI 连接说明

## ✅ 为什么内部端口配置可以正常工作

### LTI 工作流程

```
用户浏览器
    ↓
1. 点击 Moodle 中的 LTI 链接
    ↓
2. Moodle 返回 HTML 表单，指向 chatbot LTI Launch URL
   (https://edcity.qefmoodle.com/chatbot/api/lti/launch)
    ↓
3. 浏览器自动提交表单到 chatbot
    ↓
4. 请求到达 Nginx (edcity.qefmoodle.com:443)
    ↓
5. Nginx 反向代理转发到 chatbot:3000
    ↓
6. Chatbot 验证 LTI 请求并返回内容
    ↓
7. 内容显示在用户浏览器中
```

### 关键点

**✅ LTI 是浏览器和 Chatbot 之间的通信**
- 不是 Moodle 容器直接调用 Chatbot 容器
- Moodle 只是告诉浏览器：去访问这个 URL
- 浏览器通过 HTTPS 访问外部域名
- Nginx 负责将请求路由到正确的容器

**✅ 所有容器在同一个 Docker 网络中**
```yaml
# docker-compose 默认创建一个网络
# 所有服务都在这个网络中，可以通过服务名互相访问：
- moodleapp 可以访问 chatbot:3000
- chatbot 可以访问 chatbot-db:27017
- moodleapp 可以访问 moodledb:5432
```

## 🔧 正确的 LTI 配置

### 1. Moodle LTI 外部工具配置

在 Moodle 管理界面配置：

```
工具 URL: https://edcity.qefmoodle.com/chatbot/api/lti/launch
或
Secure tool URL: https://edcity.qefmoodle.com/chatbot/api/lti/launch
```

**⚠️ 注意**：
- ✅ 使用 **外部域名** + **/chatbot/** 路径
- ❌ 不要使用 `http://chatbot:3000`（这是内部地址）
- ❌ 不要使用 `https://edcity.qefmoodle.com:3000`（端口未开放）

### 2. Chatbot 环境变量配置

在 `qef_chatbot/.env.local` 中：

```bash
# Next.js 基础路径（重要！）
NEXT_PUBLIC_BASE_PATH=/chatbot

# NextAuth URL（用于 OAuth 回调）
NEXTAUTH_URL=https://edcity.qefmoodle.com/chatbot

# LTI Launch URL（提供给 Moodle）
LTI_LAUNCH_URL=https://edcity.qefmoodle.com/chatbot/api/lti/launch

# MongoDB 连接（内部地址）
MONGODB_URI=mongodb://chatbot-db:27017/qef_chatbot
```

### 3. next.config.mjs 配置

确保 Next.js 配置文件包含：

```javascript
/** @type {import('next').NextConfig} */
const nextConfig = {
  basePath: process.env.NEXT_PUBLIC_BASE_PATH || '',
  // ... 其他配置
};

export default nextConfig;
```

## 🧪 测试 LTI 连接

### 1. 测试 Chatbot 可访问性

```bash
# 在服务器上测试
curl https://edcity.qefmoodle.com/chatbot/

# 应该返回 Next.js 页面内容
```

### 2. 测试内部网络连接

```bash
# 从 moodleapp 容器访问 chatbot
docker exec moodleapp curl http://chatbot:3000/

# 应该能正常连接
```

### 3. 测试 LTI Launch

在 Moodle 中：
1. 创建一个 LTI 外部工具
2. 配置 Launch URL: `https://edcity.qefmoodle.com/chatbot/api/lti/launch`
3. 添加到课程中
4. 点击链接测试

## 🔍 可能的问题和解决方案

### 问题 1：点击 LTI 链接后 404 错误

**原因**：Chatbot 的 basePath 未正确配置

**解决**：
```bash
# 检查 qef_chatbot/.env.local
NEXT_PUBLIC_BASE_PATH=/chatbot

# 重启 chatbot 容器
docker-compose restart chatbot
```

### 问题 2：LTI 验证失败

**原因**：
- LTI 密钥配置不匹配
- URL 不正确

**解决**：
1. 确认 Moodle 中的 Consumer Key 和 Shared Secret 与 Chatbot 配置匹配
2. 确认 Launch URL 使用外部域名，不是内部地址

### 问题 3：CORS 错误

**原因**：Next.js 需要知道允许的来源

**解决**：
在 `qef_chatbot/.env.local` 中添加：
```bash
ALLOWED_ORIGINS=https://edcity.qefmoodle.com
```

## 📊 网络通信图

```
┌─────────────────┐
│   用户浏览器    │
└────────┬────────┘
         │ HTTPS (443)
         ↓
┌─────────────────────────────────────┐
│  Nginx (在 moodleapp 容器中)        │
│  - 监听 80/443                       │
│  - 处理 SSL                          │
├─────────────────┬───────────────────┤
│ / → Moodle      │ /chatbot/ → Proxy │
│                 │                    │
│  ┌──────────┐   │   ┌────────────┐  │
│  │ PHP-FPM  │   │   │   转发到   │  │
│  │  :9000   │   │   │ chatbot:3000│ │
│  └──────────┘   │   └─────┬──────┘  │
└─────────────────┴─────────┼─────────┘
                            │ Docker 内部网络
                            ↓
                 ┌──────────────────┐
                 │ chatbot 容器     │
                 │ Node.js :3000    │
                 └──────────────────┘
```

## ✅ 总结

1. **LTI 连接完全正常**：因为是通过浏览器访问外部 URL
2. **不需要开放 3000 端口**：Nginx 反向代理处理所有外部请求
3. **容器间可以直接通信**：通过 Docker 内部网络（如需要）
4. **配置重点**：
   - Moodle LTI URL 使用外部域名 + /chatbot/ 路径
   - Chatbot 配置 NEXT_PUBLIC_BASE_PATH=/chatbot
   - Nginx 正确配置反向代理

这样的架构既安全又高效！🎉
