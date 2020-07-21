FROM nginx:1.17.9

COPY conf.d/api.conf /etc/nginx/conf.d
COPY conf.d/app.conf /etc/nginx/conf.d
RUN mv /etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.disabled

RUN echo "127.0.0.1   www.warpg.com warpg.com api.warpg.com" >> /etc/hosts