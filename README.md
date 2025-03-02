# Sell-Core-Flow-App

SellCoreFlow - это платформа для бэктестинга (Backtesting) торговых стратегий, построенная на Laravel 10.  
Она позволяет тестировать стратегии на исторических данных, используя Redis, Kafka, Elasticsearch и PostgreSQL.

## Функциональность
✅ Поддержка SMA, EMA, RSI, MACD  
✅ Интеграция с Yahoo Finance / Binance API  
✅ Асинхронная обработка через Kafka  
✅ API-first подход (REST / OpenAPI)  
✅ Документированная архитектура  


## Развёртывание
```bash
git clone https://github.com/yourusername/SellCoreFlow.git
cd SellCoreFlow
docker-compose up -d --build
php artisan migrate
