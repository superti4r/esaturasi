#!/bin/sh
# This script is executed when the MySQL container is first started.
# It creates the dedicated user for the Prometheus MySQL exporter.

set -e

# The script receives the root password via the MYSQL_ROOT_PASSWORD environment variable.
# It's crucial that this script is idempotent. The `IF NOT EXISTS` clause ensures that.
mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" <<-EOSQL
    CREATE USER IF NOT EXISTS '${MYSQL_EXPORTER_USER}'@'%' IDENTIFIED WITH 'mysql_native_password' BY '${MYSQL_EXPORTER_PASSWORD}';
    GRANT PROCESS, REPLICATION CLIENT, SELECT ON *.* TO '${MYSQL_EXPORTER_USER}'@'%';
    FLUSH PRIVILEGES;
EOSQL
