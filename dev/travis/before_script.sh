#!/usr/bin/env bash

# Copyright © Magento, Inc. All rights reserved.
# See COPYING.txt for license details.

set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

# prepare for test suite
    graphql-api-functional-coverage)
        echo "Installing Magento"
        mysql -uroot -e 'CREATE DATABASE magento2;'
        php bin/magento setup:install -q \
            --language="en_US" \
            --timezone="UTC" \
            --currency="USD" \
            --base-url="http://${MAGENTO_HOST_NAME}/" \
            --admin-firstname="John" \
            --admin-lastname="Doe" \
            --backend-frontname="backend" \
            --admin-email="admin@example.com" \
            --admin-user="admin" \
            --use-rewrites=1 \
            --admin-use-security-key=0 \
            --admin-password="123123q"

        echo "Prepare api-functional tests for running"
        cd dev/tests/api-functional
        cp -r _files/Magento/TestModuleGraphQl* ../../../app/code/Magento # Deploy and enable test modules before running tests

        cp ./phpunit_graphql.xml.dist ./phpunit.xml
        sed -e "s?magento.url?${MAGENTO_HOST_NAME}?g" --in-place ./phpunit.xml

        cd ../../..
        php bin/magento setup:upgrade

        echo "Enabling production mode"
        php bin/magento deploy:mode:set production
        ;;
esac
