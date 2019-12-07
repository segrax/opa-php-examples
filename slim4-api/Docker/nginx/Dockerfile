FROM nginx:alpine

COPY default.conf /etc/nginx/conf.d/default.conf
COPY default-fpm.template /etc/nginx/conf.d/

CMD envsubst '\$APP_PHPHOST' < /etc/nginx/conf.d/default-fpm.template > /etc/nginx/conf.d/default-fpm.config && exec nginx -g 'daemon off;'
