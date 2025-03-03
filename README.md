# Sell-Core-Flow-App

~~~
SellCoreFlow - это платформа для Backtesting торговых стратегий с целью применять их на биржах ценных бумаг/криптовалют.
Она позволяет тестировать торговые стратегии на исторических данных.
~~~
## Функциональность
✅ Архитектур по модулям

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
