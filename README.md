# 生产环境部署指南

此文件夹包含了在生产服务器上部署 Moodle 和 QEF Chatbot 所需的所有配置文件。

## 🌐 网络架构

```
Internet (80/443)
    ↓
[Nginx 反向代理]
    ├→ / → Moodle (PHP-FPM:9000)
    └→ /chatbot/ → QEF Chatbot (Node.js:3000 内部)
         ↓
    [Docker 内部网络]
         ├→ PostgreSQL:5432 (Moodle 数据库)
         ├→ Redis:6379 (Moodle 缓存)
         ├→ MongoDB:27017 (Chatbot 数据库)
         └→ pgAdmin:8081 (可选，数据库管理)
```

**端口说明：**
- 仅需开放 **80** 和 **443** 端口
- Chatbot 通过 Nginx 反向代理在 `/chatbot/` 路径访问
- 所有数据库端口仅在 Docker 内部网络通信，不对外暴露

## 📦 文件清单

- `docker-compose.yml` - Docker Compose 编排配置
- `Dockerfile` - Moodle 应用镜像构建文件
- `Dockerfile.chatbot` - QEF Chatbot 镜像构建文件
- `entrypoint.sh` - Moodle 容器启动脚本
- `nginx.conf` - Nginx 主配置文件
- `nginx-ssl.conf` - Nginx SSL 配置文件
- `.env.example` - 环境变量模板文件
- `data/` - 数据持久化目录（需要创建）
- `ssl/` - SSL 证书目录（需要配置）

## 🚀 部署步骤

### 1. 上传文件到服务器

将整个 `production-deploy` 文件夹上传到服务器：

```bash
# 使用 scp 或 rsync
rsync -avz production-deploy/ user@server:/opt/moodle-app/
```

### 2. 配置环境变量

```bash
cd /opt/moodle-app
cp .env.example .env
```

编辑 `.env` 文件，设置实际的密码和配置：

```bash
nano .env
```

**必须修改的变量：**
- `POSTGRES_PASSWORD` - PostgreSQL 数据库密码
- `PGADMIN_PASSWORD` - pgAdmin 管理密码
- `PGADMIN_EMAIL` - pgAdmin 登录邮箱

### 3. 准备 Moodle 源代码

你需要将 Moodle 源代码放置到服务器上：

**方式 A：从现有环境复制**
```bash
# 在本地打包
tar -czf moodle.tar.gz moodle/

# 上传到服务器
scp moodle.tar.gz user@server:/opt/moodle-app/

# 在服务器上解压
cd /opt/moodle-app
tar -xzf moodle.tar.gz
```

**方式 B：从 Git 克隆**
```bash
cd /opt/moodle-app
git clone -b MOODLE_404_STABLE https://github.com/moodle/moodle.git
```

### 4. 准备 QEF Chatbot 源代码

```bash
# 在本地打包
tar -czf qef_chatbot.tar.gz qef_chatbot/

# 上传到服务器
scp qef_chatbot.tar.gz user@server:/opt/moodle-app/

# 在服务器上解压
cd /opt/moodle-app
tar -xzf qef_chatbot.tar.gz
```

**创建 Chatbot 环境变量：**
```bash
cd qef_chatbot
cp .env.local.example .env.local
nano .env.local
```

必须配置的变量：
- `MONGODB_URI=mongodb://chatbot-db:27017/qef_chatbot`
- `NEXTAUTH_URL=https://edcity.qefmoodle.com/chatbot` （注意：使用反向代理路径）
- `NEXTAUTH_SECRET` - 使用 `openssl rand -base64 32` 生成
- `NEXT_PUBLIC_BASE_PATH=/chatbot` （重要：设置 Next.js 基础路径）
- LTI 配置变量（launch URL 应为 `https://edcity.qefmoodle.com/chatbot/api/lti/launch`）

**⚠️ 重要**：
1. Chatbot 通过 `/chatbot/` 路径访问，必须设置 `NEXT_PUBLIC_BASE_PATH=/chatbot`
2. **LTI 连接说明**：虽然 chatbot 使用内部端口（3000），但 LTI 连接完全正常工作，因为：
   - 用户浏览器通过 HTTPS 访问 `https://edcity.qefmoodle.com/chatbot/api/lti/launch`
   - Nginx 反向代理将请求转发到内部的 `chatbot:3000`
   - LTI 是浏览器和 Chatbot 之间的通信，不是 Moodle 容器直接调用
   - 详细说明请参阅 [LTI-NETWORK-GUIDE.md](LTI-NETWORK-GUIDE.md)

### 5. 配置 SSL 证书

将 SSL 证书放置在 `ssl/` 目录中：

```bash
# 使用 Let's Encrypt
mkdir -p ssl/live/yourdomain.com
# 复制证书文件到 ssl/live/yourdomain.com/
```

或者修改 `nginx-ssl.conf` 指向正确的证书路径。

### 6. 创建数据目录

```bash
mkdir -p data/moodledata
mkdir -p data/postgres
mkdir -p data/redis
mkdir -p data/mongodb

# 设置正确的权限
chmod -R 755 data/
```

### 7. 启动服务

```bash
# 构建镜像
docker-compose build

# 启动所有服务
docker-compose up -d

# 查看运行状态
docker-compose ps

# 查看日志
docker-compose logs -f
```

### 8. 访问应用

- **Moodle**: `https://edcity.qefmoodle.com`
- **QEF Chatbot**: `https://edcity.qefmoodle.com/chatbot/` （通过 Nginx 反向代理）
- **pgAdmin**: `http://edcity.qefmoodle.com:8081`

**注意**：Chatbot 通过 Nginx 反向代理访问，不需要开放 3000 端口。

### 9. 初始化 Moodle（首次部署）

首次访问 Moodle 时，需要完成安装向导：
1. 访问 `https://edcity.qefmoodle.com`
2. 选择语言
3. 数据库配置（使用 docker-compose 中的环境变量）
4. 完成管理员账户设置

### 10. 配置 Moodle LTI 外部工具

在 Moodle 中配置 LTI 工具以集成 Chatbot：

1. 登录 Moodle 管理员账户
2. 进入：网站管理 → 插件 → 活动模块 → 外部工具 → 管理工具
3. 添加外部工具配置：
   - **工具名称**：QEF Chatbot
   - **工具 URL**：`https://edcity.qefmoodle.com/chatbot/api/lti/launch`
   - **Consumer Key**：（在 chatbot .env.local 中配置的值）
   - **Shared Secret**：（在 chatbot .env.local 中配置的值）
4. 保存配置

**⚠️ 重要**：LTI URL 必须使用外部域名 + `/chatbot/` 路径，不要使用内部地址或端口号。

## 🔧 维护命令

### 查看日志
```bash
docker-compose logs -f [service_name]
```

### 重启服务
```bash
docker-compose restart [service_name]
```

### 停止所有服务
```bash
docker-compose down
```

### 更新镜像
```bash
docker-compose pull
docker-compose up -d
```

### 备份数据
```bash
# 备份数据库
docker exec moodledb pg_dump -U moodleuser moodle > backup_$(date +%Y%m%d).sql

# 备份 MongoDB
docker exec chatbot-mongodb mongodump --out=/data/backup

# 备份文件
tar -czf data_backup_$(date +%Y%m%d).tar.gz data/
```

## ⚠️ 重要注意事项

1. **安全性**
   - 修改所有默认密码
   - 使用强密码（至少 16 位，包含大小写字母、数字和特殊字符）
   - 配置防火墙，仅开放必要端口（80, 443, 8081）
   - 端口说明：
     - 80/443：Nginx（Moodle 和 Chatbot 反向代理）
     - 8081：pgAdmin（可选，建议生产环境关闭或使用防火墙限制访问）
     - 3000：Chatbot 内部端口（不对外暴露，仅容器间通信）
     - 5432/6379/27017：数据库端口（不对外暴露，仅容器间通信）
   - 定期更新系统和 Docker 镜像

2. **性能优化**
   - 根据服务器配置调整 PHP、Nginx 和数据库参数
   - 配置 Redis 缓存
   - 考虑使用 CDN 加速静态资源

3. **数据备份**
   - 定期备份数据库和文件
   - 测试备份恢复流程
   - 将备份存储在不同位置

4. **监控**
   - 配置日志轮转
   - 监控磁盘空间
   - 监控容器健康状态

## 📝 系统要求

- Docker 20.10+
- Docker Compose 2.0+
- 最小 4GB RAM（推荐 8GB+）
- 最小 20GB 磁盘空间（推荐 50GB+）
- Ubuntu 20.04+ / CentOS 8+ / Debian 11+

## 🆘 故障排除

### 容器无法启动
```bash
docker-compose logs [service_name]
docker inspect [container_name]
```

### 数据库连接失败
- 检查 `.env` 文件中的数据库配置
- 确认 `moodledb` 容器正在运行
- 检查网络连接：`docker network inspect moodle-dockerfile-single_default`

### SSL 证书问题
- 确认证书文件路径正确
- 检查证书是否过期
- 验证 `nginx-ssl.conf` 配置

## 📞 支持

如有问题，请查看：
- Moodle 文档: https://docs.moodle.org
- Docker 文档: https://docs.docker.com
- 项目 Issues

---

**版本**: 1.0  
**最后更新**: 2026-01-07
