@echo off

if a%1==a goto noargs
if a%1==af goto update

:noargs
vendor/bin/doctrine orm:schema-tool:update --dump-sql
goto end

:update
vendor/bin/doctrine orm:schema-tool:update --force
goto end

:end
@echo on

