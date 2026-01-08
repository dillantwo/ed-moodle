#!/bin/bash
# QEF Chatbot 环境变量设置脚本

set -e

echo "==================================="
echo "QEF Chatbot 环境配置向导"
echo "==================================="
echo ""

# 1. 生成 NEXTAUTH_SECRET
echo "1. 生成 NEXTAUTH_SECRET..."
NEXTAUTH_SECRET=$(openssl rand -base64 32)
echo "   ✓ NEXTAUTH_SECRET 已生成"

# 2. 生成 RSA 密钥对
echo ""
echo "2. 生成 LTI 1.3 RSA 密钥对..."
if [ ! -f "lti-private.key" ]; then
    openssl genrsa -out lti-private.key 2048
    openssl rsa -in lti-private.key -pubout -out lti-public.key
    chmod 600 lti-private.key
    chmod 644 lti-public.key
    echo "   ✓ RSA 密钥对已生成"
else
    echo "   ℹ RSA 密钥已存在，跳过生成"
fi

# 读取私钥
PRIVATE_KEY=$(cat lti-private.key)

# 3. 创建 .env.local 文件
echo ""
echo "3. 创建 .env.local 文件..."

cat > .env.local << 'EOF'
# ============================================
# QEF Chatbot 环境变量配置
# ============================================

# MongoDB 配置
MONGODB_URI=mongodb://chatbot-db:27017/qef_chatbot

# Next.js 配置
NODE_ENV=production
NEXT_PUBLIC_BASE_PATH=/chatbot
NEXTAUTH_URL=https://edcity.qefmoodle.com/chatbot

EOF

# 添加生成的 NEXTAUTH_SECRET
echo "NEXTAUTH_SECRET=$NEXTAUTH_SECRET" >> .env.local

cat >> .env.local << 'EOF'

# LTI 1.3 配置
LTI_VERSION=1.3

# Platform（Moodle）信息
LTI_PLATFORM_ISSUER=https://edcity.qefmoodle.com
LTI_PLATFORM_AUTH_URL=https://edcity.qefmoodle.com/mod/lti/auth.php
LTI_PLATFORM_TOKEN_URL=https://edcity.qefmoodle.com/mod/lti/token.php
LTI_PLATFORM_JWKS_URL=https://edcity.qefmoodle.com/mod/lti/certs.php

# Tool（Chatbot）信息 - 需要从 Moodle 获取
LTI_TOOL_CLIENT_ID=REPLACE_WITH_MOODLE_CLIENT_ID
LTI_TOOL_DEPLOYMENT_ID=1

# 工具端点
LTI_LAUNCH_URL=https://edcity.qefmoodle.com/chatbot/api/lti/launch
LTI_JWKS_URL=https://edcity.qefmoodle.com/chatbot/api/lti/jwks
LTI_OIDC_LOGIN_URL=https://edcity.qefmoodle.com/chatbot/api/lti/oidc

# CORS
ALLOWED_ORIGINS=https://edcity.qefmoodle.com
EOF

# 添加私钥（保持多行格式）
echo "" >> .env.local
echo "# RSA 私钥" >> .env.local
echo "LTI_PRIVATE_KEY=\"$PRIVATE_KEY\"" >> .env.local

echo "   ✓ .env.local 文件已创建"

# 4. 显示公钥（用于 Moodle 配置）
echo ""
echo "==================================="
echo "✓ 配置完成！"
echo "==================================="
echo ""
echo "📋 下一步操作："
echo ""
echo "1. 编辑 .env.local 文件，添加从 Moodle 获取的 Client ID："
echo "   nano /qef_chatbot/.env.local"
echo "   找到 LTI_TOOL_CLIENT_ID 并替换为 Moodle 提供的值"
echo ""
echo "2. 在 Moodle LTI 配置中使用以下公钥 URL："
echo "   https://edcity.qefmoodle.com/chatbot/api/lti/jwks"
echo ""
echo "3. 或者使用以下公钥内容："
echo "-----------------------------------"
cat lti-public.key
echo "-----------------------------------"
echo ""
echo "4. 重启 chatbot 容器："
echo "   docker-compose restart chatbot"
echo ""

