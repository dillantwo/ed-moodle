# 生产环境快速配置清单

## ✅ 部署前检查清单

### 服务器要求
- [ ] Docker 20.10+ 已安装
- [ ] Docker Compose 2.0+ 已安装
- [ ] 至少 8GB RAM
- [ ] 至少 50GB 可用磁盘空间
- [ ] 防火墙已配置（仅开放 80 和 443 端口）

### 文件准备
- [ ] 已上传 production-deploy 文件夹到服务器
- [ ] 已上传 moodle 源代码
- [ ] 已上传 qef_chatbot 源代码
- [ ] 已配置 .env 文件
- [ ] 已配置 qef_chatbot/.env.local 文件
- [ ] 已准备 SSL 证书

## 🔑 关键配置项

### 1. .env 文件（主目录）
```bash
POSTGRES_DB=moodle
POSTGRES_USER=moodleuser
POSTGRES_PASSWORD=<强密码>
PGADMIN_EMAIL=admin@edcity.qefmoodle.com
PGADMIN_PASSWORD=<强密码>
CHATBOT_DB_NAME=qef_chatbot
```

### 2. qef_chatbot/.env.local
```bash
# MongoDB 连接
MONGODB_URI=mongodb://chatbot-db:27017/qef_chatbot

# Next.js 配置（重要！）
NEXTAUTH_URL=https://edcity.qefmoodle.com/chatbot
NEXT_PUBLIC_BASE_PATH=/chatbot

# NextAuth 密钥（使用 openssl rand -base64 32 生成）
NEXTAUTH_SECRET=<生成的密钥>

# LTI 配置
LTI_LAUNCH_URL=https://edcity.qefmoodle.com/chatbot/api/lti/launch
```

### 3. Nginx SSL 配置修改
编辑 `nginx-ssl.conf`，修改以下行：
```nginx
server_name edcity.qefmoodle.com;  # 已配置
ssl_certificate /etc/letsencrypt/live/edcity.qefmoodle.com/fullchain.pem;  # 已配置
ssl_certificate_key /etc/letsencrypt/live/edcity.qefmoodle.com/privkey.pem;  # 已配置
```

## 🚀 部署命令

```bash
# 1. 进入部署目录
cd /opt/moodle-app

# 2. 创建数据目录
mkdir -p data/{moodledata,postgres,redis,mongodb}
chmod -R 755 data/

# 3. 构建镜像
docker-compose build

# 4. 启动服务
docker-compose up -d

# 5. 检查状态
docker-compose ps
docker-compose logs -f
```

## 🔍 访问地址

| 服务 | URL | 说明 |
|------|-----|------|
| Moodle | `https://edcity.qefmoodle.com` | 主应用 |
| Chatbot | `https://edcity.qefmoodle.com/chatbot/` | 通过反向代理 |
| pgAdmin | `http://edcity.qefmoodle.com:8081` | 数据库管理（建议限制访问） |

## 🔗 LTI 集成配置

**Moodle LTI 外部工具设置：**
- 工具 URL: `https://edcity.qefmoodle.com/chatbot/api/lti/launch`
- ✅ 使用外部域名 + `/chatbot/` 路径
- ❌ 不要使用 `http://chatbot:3000`（内部地址）
- ❌ 不要使用 `:3000` 端口（未开放）

**工作原理：**
- 用户点击 Moodle 中的 LTI 链接
- 浏览器访问 `https://edcity.qefmoodle.com/chatbot/api/lti/launch`
- Nginx 反向代理转发请求到内部 `chatbot:3000`
- LTI 连接完全正常，详见 [LTI-NETWORK-GUIDE.md](LTI-NETWORK-GUIDE.md)

## ⚠️ 重要提醒

1. **Chatbot 访问路径**
   - ✅ 正确：`https://edcity.qefmoodle.com/chatbot/`
   - ❌ 错误：`https://edcity.qefmoodle.com:3000`
   - 3000 端口不对外开放，仅容器内部使用

2. **LTI 配置**
   - Launch URL: `https://edcity.qefmoodle.com/chatbot/api/lti/launch`
   - 确保 Moodle LTI 配置使用上述 URL

3. **防火墙设置**
   ```bash
   # UFW 示例
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw enable
   ```

4. **密码强度**
   - 使用至少 16 位随机密码
   - 包含大小写字母、数字和特殊字符
   - 生成示例：`openssl rand -base64 24`

## 🔧 常用命令

```bash
# 查看日志
docker-compose logs -f moodleapp
docker-compose logs -f chatbot

# 重启服务
docker-compose restart chatbot
docker-compose restart moodleapp

# 进入容器
docker exec -it qef-chatbot sh
docker exec -it moodleapp bash

# 数据库备份
docker exec moodledb pg_dump -U moodleuser moodle > backup.sql
docker exec chatbot-mongodb mongodump --out=/data/backup

# 停止并删除所有容器（保留数据）
docker-compose down

# 完全清理（包括数据，慎用！）
docker-compose down -v
```

## 📋 故障排查

### Chatbot 无法访问
```bash
# 1. 检查容器状态
docker-compose ps chatbot

# 2. 查看日志
docker-compose logs chatbot

# 3. 测试内部连接
docker exec moodleapp curl http://chatbot:3000

# 4. 检查 Nginx 配置
docker exec moodleapp nginx -t
```

### Moodle 数据库连接失败
```bash
# 1. 检查数据库容器
docker-compose ps moodledb

# 2. 测试连接
docker exec moodleapp psql -h moodledb -U moodleuser -d moodle

# 3. 检查环境变量
docker exec moodleapp env | grep MOODLE
```

### SSL 证书问题
```bash
# 1. 检查证书文件
ls -la ssl/live/yourdomain.com/

# 2. 验证证书
openssl x509 -in ssl/live/yourdomain.com/fullchain.pem -text -noout

# 3. 重新加载 Nginx
docker-compose restart moodleapp
```

## 📞 获取帮助

遇到问题时：
1. 查看 Docker 日志：`docker-compose logs -f`
2. 检查容器状态：`docker-compose ps`
3. 查看详细配置：`docker inspect <container_name>`
4. 参考主 README.md 获取详细说明
