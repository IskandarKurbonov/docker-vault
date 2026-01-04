# Docker Vault

> Production-ready Docker Compose stacks for popular applications and services

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=flat&logo=docker&logoColor=white)](https://www.docker.com/)

## Overview

Docker Vault is a curated collection of production-ready Docker Compose configurations for popular applications, development stacks, and infrastructure services. Each stack is optimized for performance, security, and ease of deployment.

## Features

- **Production Ready**: Battle-tested configurations
- **Security First**: SSL/TLS, secrets management, security best practices
- **Performance Optimized**: Proper resource limits, health checks, logging
- **Easy Deployment**: One command to deploy entire stacks
- **Well Documented**: Clear instructions and customization guides
- **Multi-Environment**: Development, staging, production configs

## Available Stacks

### Web Application Stacks

| Stack | Description | Services |
|-------|-------------|----------|
| **LAMP** | Linux, Apache, MySQL, PHP | Apache, MySQL 8, PHP-FPM, phpMyAdmin |
| **LEMP** | Linux, Nginx, MySQL, PHP | Nginx, MySQL 8, PHP-FPM, Redis |
| **MEAN** | MongoDB, Express, Angular, Node | MongoDB, Node.js, Nginx |
| **MERN** | MongoDB, Express, React, Node | MongoDB, Node.js, React, Nginx |

### Database Stacks

| Stack | Description | Services |
|-------|-------------|----------|
| **PostgreSQL HA** | High Availability PostgreSQL | PostgreSQL, pgpool, replication |
| **MySQL Cluster** | MySQL with replication | MySQL primary, replica, ProxySQL |
| **MongoDB Replica** | MongoDB replica set | 3-node MongoDB cluster |
| **Redis Cluster** | Redis with Sentinel | Redis, Redis Sentinel |

### DevOps & CI/CD Stacks

| Stack | Description | Services |
|-------|-------------|----------|
| **Jenkins** | Full CI/CD platform | Jenkins, Docker-in-Docker, Nginx |
| **GitLab** | Complete DevOps platform | GitLab CE, PostgreSQL, Redis |
| **SonarQube** | Code quality platform | SonarQube, PostgreSQL |
| **Nexus** | Artifact repository | Nexus Repository, Nginx |

### Monitoring & Logging

| Stack | Description | Services |
|-------|-------------|----------|
| **Prometheus Stack** | Complete monitoring | Prometheus, Grafana, AlertManager, Node Exporter |
| **ELK Stack** | Elasticsearch, Logstash, Kibana | Elasticsearch, Logstash, Kibana, Filebeat |
| **Zabbix** | Infrastructure monitoring | Zabbix Server, MySQL, Nginx, Agent |
| **Loki Stack** | Log aggregation | Loki, Promtail, Grafana |

### Development Tools

| Stack | Description | Services |
|-------|-------------|----------|
| **Portainer** | Docker management | Portainer CE, Agent |
| **Code Server** | VS Code in browser | code-server, Nginx |
| **Mailhog** | Email testing | Mailhog |
| **MinIO** | Object storage | MinIO, Console |

## Quick Start

### Prerequisites

- Docker 24.0+
- Docker Compose 2.20+
- Minimum 2GB RAM (4GB+ recommended)
- Linux/macOS/Windows with WSL2

### Basic Usage

1. **Clone the repository**
```bash
git clone https://github.com/IskandarKurbonov/docker-vault.git
cd docker-vault
```

2. **Choose a stack**
```bash
cd stacks/lemp-stack
```

3. **Configure environment**
```bash
cp .env.example .env
# Edit .env with your settings
nano .env
```

4. **Deploy the stack**
```bash
docker-compose up -d
```

5. **Check status**
```bash
docker-compose ps
docker-compose logs -f
```

## Example: LEMP Stack

### Directory Structure
```
lemp-stack/
├── docker-compose.yml
├── .env.example
├── nginx/
│   ├── conf.d/
│   │   └── default.conf
│   └── ssl/
├── php/
│   ├── Dockerfile
│   └── php.ini
└── www/
    └── index.php
```

### Configuration

**.env file:**
```env
# MySQL Configuration
MYSQL_ROOT_PASSWORD=your_secure_password
MYSQL_DATABASE=myapp
MYSQL_USER=myuser
MYSQL_PASSWORD=user_password

# PHP Configuration
PHP_VERSION=8.2

# Nginx Configuration
NGINX_PORT=80
NGINX_SSL_PORT=443
DOMAIN_NAME=localhost
```

### docker-compose.yml
```yaml
version: '3.8'

services:
  nginx:
    image: nginx:alpine
    ports:
      - "${NGINX_PORT}:80"
      - "${NGINX_SSL_PORT}:443"
    volumes:
      - ./www:/var/www/html
      - ./nginx/conf.d:/etc/nginx/conf.d
      - ./nginx/ssl:/etc/nginx/ssl
    depends_on:
      - php
    networks:
      - lemp-network

  php:
    build:
      context: ./php
      args:
        PHP_VERSION: ${PHP_VERSION}
    volumes:
      - ./www:/var/www/html
      - ./php/php.ini:/usr/local/etc/php/php.ini
    networks:
      - lemp-network

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${MYSQL_DATABASE}
      MYSQL_USER: ${MYSQL_USER}
      MYSQL_PASSWORD: ${MYSQL_PASSWORD}
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - lemp-network

  redis:
    image: redis:alpine
    command: redis-server --appendonly yes
    volumes:
      - redis-data:/data
    networks:
      - lemp-network

networks:
  lemp-network:
    driver: bridge

volumes:
  mysql-data:
  redis-data:
```

### Deployment
```bash
cd stacks/lemp-stack
docker-compose up -d
```

### Access Services
- Website: http://localhost
- MySQL: localhost:3306
- Redis: localhost:6379

## Advanced Features

### SSL/TLS Configuration

Generate self-signed certificate:
```bash
cd nginx/ssl
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout server.key -out server.crt
```

For production, use Let's Encrypt:
```bash
docker-compose -f docker-compose.yml -f docker-compose.letsencrypt.yml up -d
```

### Scaling Services

```bash
# Scale PHP-FPM workers
docker-compose up -d --scale php=3

# Scale application containers
docker-compose up -d --scale app=5
```

### Backup & Restore

**Backup MySQL:**
```bash
docker-compose exec mysql mysqldump -u root -p${MYSQL_ROOT_PASSWORD} ${MYSQL_DATABASE} > backup.sql
```

**Restore MySQL:**
```bash
docker-compose exec -T mysql mysql -u root -p${MYSQL_ROOT_PASSWORD} ${MYSQL_DATABASE} < backup.sql
```

### Health Checks

All stacks include health checks:
```bash
docker-compose ps
# HEALTHY status indicates service is running properly
```

### Resource Limits

Configure in docker-compose.yml:
```yaml
services:
  mysql:
    deploy:
      resources:
        limits:
          cpus: '2'
          memory: 2G
        reservations:
          cpus: '1'
          memory: 1G
```

## Stack-Specific Guides

### Prometheus Monitoring Stack

```bash
cd stacks/prometheus-stack
docker-compose up -d
```

Access:
- Prometheus: http://localhost:9090
- Grafana: http://localhost:3000 (admin/admin)
- AlertManager: http://localhost:9093

### GitLab DevOps Platform

```bash
cd stacks/gitlab-stack
docker-compose up -d
```

**Note**: GitLab requires 4GB+ RAM

Access: http://localhost (initial setup takes 2-3 minutes)

### ELK Stack for Logging

```bash
cd stacks/elk-stack
docker-compose up -d
```

Access Kibana: http://localhost:5601

## Troubleshooting

### Containers won't start
```bash
# Check logs
docker-compose logs -f service-name

# Verify port availability
netstat -tulpn | grep PORT_NUMBER

# Reset and restart
docker-compose down -v
docker-compose up -d
```

### Database connection issues
```bash
# Check network connectivity
docker-compose exec app ping mysql

# Verify environment variables
docker-compose config
```

### Performance issues
```bash
# Monitor resource usage
docker stats

# Increase resource limits in docker-compose.yml
```

## Best Practices

1. **Always use .env files** - Never hardcode credentials
2. **Use named volumes** - For data persistence
3. **Implement health checks** - Monitor service status
4. **Set resource limits** - Prevent resource exhaustion
5. **Use networks** - Isolate service communication
6. **Regular backups** - Backup volumes and databases
7. **Update images regularly** - `docker-compose pull`
8. **Use secrets for production** - Docker secrets or Vault

## Production Deployment

### Security Checklist
- [ ] Change all default passwords
- [ ] Enable SSL/TLS encryption
- [ ] Configure firewall rules
- [ ] Set up log rotation
- [ ] Enable automatic updates
- [ ] Implement backup strategy
- [ ] Use Docker secrets
- [ ] Enable container security scanning

### Production docker-compose.yml
```bash
# Use production override
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

## Contributing

Contributions welcome! To add a new stack:

1. Fork the repository
2. Create stack directory: `stacks/your-stack/`
3. Add docker-compose.yml, .env.example, README.md
4. Test thoroughly
5. Submit pull request

## Support

- **Issues**: [GitHub Issues](https://github.com/IskandarKurbonov/docker-vault/issues)
- **Email**: kurbonoviskandar23@gmail.com
- **Telegram**: [@iskandar2318](https://t.me/iskandar2318)

## License

MIT License - see [LICENSE](LICENSE) file

## Author

**Iskandar Kurbonov**
- DevOps Engineer
- Docker & Container Specialist
- Location: Tashkent, Uzbekistan

---

⭐ Star this repository if you find it useful!
