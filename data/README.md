# 数据目录说明

此目录用于存储所有持久化数据。

## 目录结构

在服务器上运行前，请创建以下子目录：

```bash
mkdir -p data/moodledata
mkdir -p data/postgres
mkdir -p data/redis
mkdir -p data/mongodb
```

## 权限设置

```bash
chmod -R 755 data/
```

## 各目录用途

- `moodledata/` - Moodle 用户上传文件、缓存等
- `postgres/` - PostgreSQL 数据库文件
- `redis/` - Redis 持久化数据
- `mongodb/` - MongoDB 数据库文件（Chatbot）

## ⚠️ 重要提醒

- 这些目录包含重要数据，请定期备份
- 不要手动修改数据库文件
- 确保有足够的磁盘空间
