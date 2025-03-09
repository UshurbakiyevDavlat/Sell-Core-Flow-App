#!/bin/sh

echo "Starting Supervisor..."
exec supervisord -n -c /etc/supervisor/supervisord.conf
