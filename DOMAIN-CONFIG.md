# 域名配置摘要

**生产环境域名**: `edcity.qefmoodle.com`

## ✅ 已配置的文件

所有配置文件已更新为使用 `edcity.qefmoodle.com`：

### 核心配置文件
- ✅ `nginx-ssl.conf` - server_name 和 SSL 证书路径
- ✅ `.env.example` - pgAdmin 邮箱域名示例

### 文档文件
- ✅ `README.md` - 所有 URL 示例
- ✅ `QUICK-START.md` - 快速配置指南
- ✅ `LTI-NETWORK-GUIDE.md` - LTI 连接说明
- ✅ `ssl/README.md` - SSL 证书配置

## 🔗 关键 URL

| 服务 | URL |
|------|-----|
| Moodle 主站 | `https://edcity.qefmoodle.com` |
| QEF Chatbot | `https://edcity.qefmoodle.com/chatbot/` |
| LTI Launch URL | `https://edcity.qefmoodle.com/chatbot/api/lti/launch` |
| pgAdmin | `http://edcity.qefmoodle.com:8081` |

## 📋 部署前检查清单

### 1. 环境变量配置

**主 `.env` 文件**:
```bash
POSTGRES_DB=moodle
POSTGRES_USER=moodleuser
POSTGRES_PASSWORD=<设置强密码>
PGADMIN_EMAIL=admin@edcity.qefmoodle.com
PGADMIN_PASSWORD=<设置强密码>
CHATBOT_DB_NAME=qef_chatbot
```

**`qef_chatbot/.env.local` 文件**:
```bash
# MongoDB
MONGODB_URI=mongodb://chatbot-db:27017/qef_chatbot

# Next.js
NEXTAUTH_URL=https://edcity.qefmoodle.com/chatbot
NEXT_PUBLIC_BASE_PATH=/chatbot
NEXTAUTH_SECRET=<使用 openssl rand -base64 32 生成>

# LTI
LTI_LAUNCH_URL=https://edcity.qefmoodle.com/chatbot/api/lti/launch

# CORS
ALLOWED_ORIGINS=https://edcity.qefmoodle.com
```

### 2. SSL 证书

**获取 Let's Encrypt 证书**:
```bash
sudo certbot certonly --standalone -d edcity.qefmoodle.com
sudo cp -r /etc/letsencrypt/* ./ssl/
```

**证书路径（已在 nginx-ssl.conf 中配置）**:
- 证书: `/etc/letsencrypt/live/edcity.qefmoodle.com/fullchain.pem`
- 私钥: `/etc/letsencrypt/live/edcity.qefmoodle.com/privkey.pem`

### 3. DNS 配置

确保 DNS A 记录已配置：
```
edcity.qefmoodle.com  →  <服务器 IP 地址>
```

### 4. 防火墙设置

只需开放以下端口：
```bash
sudo ufw allow 80/tcp    # HTTP (会重定向到 HTTPS)
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

可选（数据库管理）：
```bash
sudo ufw allow 8081/tcp  # pgAdmin（建议仅允许特定 IP）
```

### 5. Moodle LTI 配置

在 Moodle 管理界面中：
1. 进入：网站管理 → 插件 → 活动模块 → 外部工具 → 管理工具
2. 添加外部工具：
   - **工具名称**: QEF Chatbot
   - **工具 URL**: `https://edcity.qefmoodle.com/chatbot/api/lti/launch`
   - **Consumer Key**: （与 chatbot .env.local 中的配置匹配）
   - **Shared Secret**: （与 chatbot .env.local 中的配置匹配）

## 🔍 测试步骤

### 1. 测试 SSL 证书
```bash
openssl s_client -connect edcity.qefmoodle.com:443 -servername edcity.qefmoodle.com
```

### 2. 测试 Moodle 访问
```bash
curl -I https://edcity.qefmoodle.com
```

### 3. 测试 Chatbot 访问
```bash
curl -I https://edcity.qefmoodle.com/chatbot/
```

### 4. 浏览器测试
1. 访问 `https://edcity.qefmoodle.com`
2. 访问 `https://edcity.qefmoodle.com/chatbot/`
3. 在 Moodle 中测试 LTI 链接

## ⚠️ 重要提醒

1. **所有配置文件已更新** - 无需手动替换域名
2. **仅需配置环境变量** - 按照上述检查清单设置 `.env` 文件
3. **SSL 证书必须配置** - 在启动服务前确保证书已放置在 `ssl/` 目录
4. **DNS 必须解析** - 确保域名指向服务器 IP
5. **端口仅开放 80/443** - Chatbot 不需要暴露 3000 端口

## 📞 快速参考

- 完整部署指南: [README.md](README.md)
- 快速开始: [QUICK-START.md](QUICK-START.md)
- LTI 配置详解: [LTI-NETWORK-GUIDE.md](LTI-NETWORK-GUIDE.md)
- SSL 证书配置: [ssl/README.md](ssl/README.md)
