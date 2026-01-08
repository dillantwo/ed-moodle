# SSL 证书目录

此目录用于存放 SSL/TLS 证书文件。

## 使用 Let's Encrypt

推荐使用 Let's Encrypt 免费证书：

```bash
# 安装 certbot
sudo apt-get update
sudo apt-get install certbot

# 获取证书
sudo certbot certonly --standalone -d edcity.qefmoodle.com

# 证书会被保存到 /etc/letsencrypt/
# 将证书复制到此目录
sudo cp -r /etc/letsencrypt/* ./ssl/
```

## 目录结构

```
ssl/
├── live/
│   └── edcity.qefmoodle.com/
│       ├── fullchain.pem
│       ├── privkey.pem
│       ├── chain.pem
│       └── cert.pem
├── archive/
└── renewal/
```

## 配置 nginx-ssl.conf

确保 `nginx-ssl.conf` 中的证书路径正确：

```nginx
ssl_certificate /etc/letsencrypt/live/edcity.qefmoodle.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/edcity.qefmoodle.com/privkey.pem;
```

## 证书续期

Let's Encrypt 证书有效期 90 天，需要定期续期：

```bash
# 测试续期
sudo certbot renew --dry-run

# 实际续期
sudo certbot renew

# 设置自动续期（crontab）
0 0 * * * certbot renew --quiet
```

## 自签名证书（仅用于测试）

如果只是测试环境，可以使用自签名证书：

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ssl/privkey.pem \
  -out ssl/fullchain.pem \
  -subj "/C=CN/ST=State/L=City/O=Organization/CN=edcity.qefmoodle.com"
```

⚠️ **注意**: 自签名证书会在浏览器中显示安全警告，不适合生产环境。

## 权限设置

```bash
chmod 600 ssl/live/edcity.qefmoodle.com/privkey.pem
chmod 644 ssl/live/edcity.qefmoodle.com/fullchain.pem
```
