#!/bin/bash

echo "Waiting for Zookeeper to be ready..."
sleep 5

echo "Starting Kafka..."
/opt/bitnami/scripts/kafka/entrypoint.sh /opt/bitnami/scripts/kafka/run.sh &

echo "Waiting for Kafka to be ready..."
sleep 10

echo "Creating Kafka topics..."
kafka-topics.sh --create --if-not-exists --topic asset_price_update --bootstrap-server kafka:9092
kafka-topics.sh --create --if-not-exists --topic limit_pending_orders --bootstrap-server kafka:9092

echo "Kafka topics created successfully."

wait
